<?php
require_once 'AbstractExecuter.php';
/**
 * 
 * @author wangshuang03
 *
 */
class ExecuterSystemCase extends AbstractExecuter {	
	var $result_str = array();
	
	function get_value($data, $key)
	{
		foreach ($data->item as $sitem) {
			if ($sitem->item_name === $key){
				return $sitem->value;
			}
		}
		return NULL;
	}
	
	protected function get_reportmsg(){
		$str = "";
		foreach ($this->result_str as $key => $value) {
			$str = $str . $key . ":" . $value . "; ";
		}
		
		return $str;
	}
	
	protected function run_steps(){
		//echo "111";die();
	    //执行前先把已保存的结果文件删除
		$file = $this->case->id.".xml";
		if (file_exists($file)) {
    		$result=unlink ($file);
  		}
  		
  		//运行job
		$this->run_job();
				
		return true;
	}	
	
	public  function deal_result(){			
		$run_result = 1;
		//判断运行结果
		$file = $this->case->id.".xml";
		$rule = json_decode($this->case->content);
		if(file_exists($file)){
			$xml = simplexml_load_file($file);
			$tsuite=$xml->testsuite;
			
			$failNum=$tsuite['failures'];
			$totalNum=$tsuite['tests'];
			//$failNum=2;
			eval("\$condition = ". $failNum . $rule->failed . ";");
			if($condition){
				$this->result_str["total"] =$totalNum;
				$this->result_str["failed"] =$failNum;
				$run_result = 0;
			}
		}
		
		if ($run_result) {
			return true;
		}

		//将执行结果存储到case里	
		$result = array();
		$result["caseid"] = $this->case->id;
		$result["detail"] = $this->get_reportmsg();
		$result["exerst"] = $run_result;
    	$this->CI->load->model('result_model');
    	$this->CI->result_model->add_result($result);
    	return $run_result;
	}
	
	private function run_job(){
		$jenInfo=$this->CI->load->config('jenkinsInfo');
		$jenInfo=$this->CI->config->item('jenkins_info');
		$jobInfo=$jenInfo[$this->case->productid][$this->case->id];
		
		//$url = "http://ns.jenkins.baidu.com/job/mapmo_webapp_functionTest_monitor/buildWithParameters";		
		$url = $jobInfo["url"];
		
		$post_data = array (
			'token'=>$jobInfo["proToken"],
			'id'=>$this->case->id,
		);
		//$querystr=$url.'?'.http_build_query($post_data);
		//echo $querystr;
		$ch = curl_init();
		$userAndToken=$jobInfo["user"].":".$jobInfo["apiToken"];
		curl_setopt($ch, CURLOPT_USERPWD, $userAndToken);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 我们在POST数据哦！
		curl_setopt($ch, CURLOPT_POST, 1);
		if(is_array($post_data)){
			$sets = array();
			foreach ($post_data as $key=>$value){
				$sets[]=$key .'='.urlencode($value);
			}
			$post_data=implode('&', $sets);
		}
		// 把post的变量加上
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
		$ret = curl_exec($ch);
		//$error = curl_error ($ch);
		if (curl_errno($ch) || !is_string($ret) || !strlen($ret)) {
             $ret = '';
        }
		curl_close($ch);
		return $ret;
	}
}