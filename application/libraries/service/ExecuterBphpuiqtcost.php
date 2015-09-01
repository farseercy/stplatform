<?php
require_once 'AbstractExecuter.php';
/**
 * 
 * @author liuxiaochun03
 * phpui php日志统计 请求时长的监控执行器
 *
 */
class ExecuterBphpuiqtcost extends AbstractExecuter {	
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
		return $json_data->data[0]->value;
	}
	
	protected function run_steps(){
		$run_result = 1;
		$rule = json_decode($this->case->content);
		$devrate = $rule->devrate;
		$cost = $rule->cost;
		$qt = $rule->qt;
		if(empty($rule) || empty($devrate) || empty($cost) || empty($qt)) {
			return true;
		}
				
		//最近十分钟
		$curr_end = date("Y-m-d H:i:s", strtotime("-" . 120 . " seconds"));
		$curr_start = date("Y-m-d H:i:s", strtotime("-" . 720 . " seconds"));
		//获取最近十分钟的均值
		$real_cost = $this->get_queryvalue("sum(cost/total)/count(total)", $curr_start, $curr_end, $qt);
		eval("\$conditon = ". $real_cost . $cost . ";");
		if(!$conditon) {
			$this->result_str["请求响应时间(ms)" . $cost] = "failed";
			$run_result = 0;				
		}
		
		//获取历史均值
		$real_allavgcost = $this->get_queryvalue("sum(cost/total)/count(total)", '', '', $qt);
		$diff = $real_allavgcost - $real_cost; 
		if ($real_allavgcost != 0) {
			$real_devrate = $diff / $real_allavgcost;
			eval("\$conditon = ". $real_devrate . $devrate . ";");
			if(!$conditon) {
				$this->result_str["请求响应时间增长率" . $devrate] = "failed";
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