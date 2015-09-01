<?php
require_once 'AbstractExecuter.php';
/**
 * 
 * @author liuxiaochun03
 * phpui php日志统计 失败率的监控执行器
 *
 */
class ExecuterKafka extends AbstractExecuter {	
	var $result_str = array();

	protected function get_reportmsg(){
		$str = "";
		foreach ($this->result_str as $key => $value) {
			$str = $str . $key . ":" . $value . "; ";
		}
		
		return $str;
	}
	
	private function get_queryvalue($table, $field, $start, $end, $qt) {
		$url = 'http://10.95.36.21:8080/DataCenter/test/Query?table=' . $table . '&field=' . $field .
			   '&condition=colqt%3D%22' . $qt . '%22&start=' . urlencode($start) . '&end=' . urlencode($end);
		$data = http_get($url);
		$json_data = json_decode($data);
		return $json_data->data[0]->value == null ? 0 : $json_data->data[0]->value;
	}
	
	protected function get_jztable(){
	}
	protected function get_jzfield(){
	}
	protected function get_jzhint(){
	}

	protected function get_jctable(){
	}
	protected function get_jcfield(){
	}
	protected function get_jchint(){
	}

	protected function get_tbtable(){
	}
	protected function get_tbfield(){
	}
	protected function get_tbhint(){
	}

	protected function get_hbtable(){
	}
	protected function get_hbfield(){
	}
	protected function get_hbhint(){
	}
	
	protected function run_steps(){
		$run_result = 1;
		$rule = json_decode($this->case->content);
		@$rate_jc = $rule->rate_jc;
		@$rate_jz = $rule->rate_jz;
		@$rate_tb = $rule->rate_tb;
		@$rate_hb = $rule->rate_hb;
		$periods = $this->parse_periods($this->case);
		
		$qt = $rule->qt;
		if(empty($rule) || empty($qt)) {
			return true;
		}
		
		//监控均值
		if(!empty($rate_jz)) {		
			$real_rate = $this->get_queryvalue($this->get_jztable(), $this->get_jzfield(), $periods->curr->start, $periods->curr->end, $qt);
			eval("\$conditon = ". $real_rate . $rate_jz . ";");
			if(!$conditon) {
				$this->result_str[$this->get_jzhint(). $rate_jz] = "failed";
				$run_result = 0;				
			}
		}
		
		//监控均差
		if(!empty($rate_jc)) {
			$real_allrate = $this->get_queryvalue($this->get_jctable(), $this->get_jcfield(), $periods->hisy->start, $periods->hisy->end, $qt);
			$diff = $real_rate - $real_allrate; 
			if($real_allrate != 0) {		
				$real_rate = $diff / $real_allrate;
				eval("\$conditon = ". $real_rate . $rate_jc . ";");
				if(!$conditon) {
					$this->result_str[$this->get_jchint() . $rate_jc] = "failed";
					$run_result = 0;				
				}	
			}	
		}
		
		//监控同比
		if(!empty($rate_tb)) {
			$rate_curr = $this->get_queryvalue($this->get_tbtable(), $this->get_tbfield(), $periods->curr->start, $periods->curr->end, $qt);
			$rate_spec = $this->get_queryvalue($this->get_tbtable(), $this->get_tbfield(), $periods->spec->start, $periods->spec->end, $qt);
			if ($rate_spec != 0) {
				$real_rate = ($rate_spec - $rate_curr) / $rate_spec;
				eval("\$conditon = ". $real_rate . $rate_tb . ";");
				if(!$conditon) {
					$this->result_str[$this->get_tbhint() . $rate_tb] = "failed";
					$run_result = 0;				
				}
			}
		}
		
		//监控环比
		if(!empty($rate_hb)) {
			$rate_curr = $this->get_queryvalue($this->get_hbtable(), $this->get_hbfield(), $periods->curr->start, $periods->curr->end, $qt);
			$rate_last = $this->get_queryvalue($this->get_hbtable(), $this->get_hbfield(), $periods->last->start, $periods->last->end, $qt);
			if ($rate_last != 0) {
				$real_rate = ($rate_last - $rate_curr) / $rate_last;
				eval("\$conditon = ". $real_rate . $rate_hb . ";");
				if(!$conditon) {
					$this->result_str[$this->get_hbhint() . $rate_hb] = "failed";
					$run_result = 0;				
				}
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