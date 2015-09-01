<?php
require_once 'AbstractExecuter.php';
/**
 * 
 * @author liuxiaochun03
 * phpui php日志统计 失败率的监控执行器
 *
 */
class ExecuterBphpuiqtfail extends AbstractExecuter {	
	var $result_str = array();

	protected function get_reportmsg(){
		$str = "";
		foreach ($this->result_str as $key => $value) {
			$str = $str . $key . ":" . $value . "; ";
		}
		
		return $str;
	}
	
	private function get_queryvalue($field, $start, $end, $qt) {
		$url = 'http://10.95.36.21:8080/DataCenter/test/Query?table=tb_bphpui_qt&field=' . $field .
			   '&condition=colqt%3D%22' . $qt . '%22&start=' . urlencode($start) . '&end=' . urlencode($end);
		$data = http_get($url);
		$json_data = json_decode($data);
		return $json_data->data[0]->value == null ? 0 : $json_data->data[0]->value;
	}
	
	protected function run_steps(){
		$run_result = 1;
		$rule = json_decode($this->case->content);
		$rate_jc = $rule->rate_jc;
		$rate_jz = $rule->rate_jz;
		$periods = $this->parse_periods($this->case);
		
		$qt = $rule->qt;
		if(empty($rule) || empty($rate_jc) || empty($rate_jz) || empty($qt)) {
			return true;
		}
		
		$real_failrate = $this->get_queryvalue("sum(fail/total)/count(total)", $periods->curr->start, $periods->curr->end, $qt);
		eval("\$conditon = ". $real_failrate . $rate_jz . ";");
		if(!$conditon) {
			$this->result_str["请求访问失败率" . $rate_jz] = "failed";
			$run_result = 0;				
		}
		
		//获取历史均值
		$real_allfailrate = $this->get_queryvalue("sum(fail/total)/count(total)", $periods->hisy->start, $periods->hisy->end, $qt);
		$diff = $real_failrate - $real_allfailrate; 
		if($real_allfailrate != 0) {
	
			$real_devrate = $diff / $real_allfailrate;
			eval("\$conditon = ". $real_devrate . $rate_jc . ";");
			if(!$conditon) {
				$this->result_str["请求访问失败增长比率" . $rate_jc] = "failed";
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
    	return false;
	}
}