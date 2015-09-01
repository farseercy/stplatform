<?php
require_once 'AbstractExecuter.php';
/**
 * 
 * @author wangshuang03
 *
 */
class ExecuterProEffect extends AbstractExecuter {	
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
			$str = $str . $key . ":" . $value . "; \n";
		}
		
		return $str;
	}
	
	protected function run_steps(){
		$run_result = 1;				
		
		$rule = json_decode($this->case->content);		
		$item=$rule->item;		
		
		//获取昨天数据，当天的数据需要第二天才能出结果
		$this->CI->load->model('log_model');
		$day = -2;
		$today = $this->CI->log_model->getItemCnt($this->case->productid,$day,$item);
		var_dump($today);
		
		//获取前一周的数据
		$day = $day-7;		
		$lastWeek = $this->CI->log_model->getItemCnt($this->case->productid,$day,$item);				
		var_dump($lastWeek);	
		
		//获取前两周的数据
		$day = $day-7;		
		$lastlastWeek = $this->CI->log_model->getItemCnt($this->case->productid,$day,$item);				
		var_dump($lastlastWeek);	
		
		//如果数据为0，直接认为失败
		if($today==0||$lastWeek==0||$lastlastWeek==0){
			if($today==0){
				$this->result_str["昨天的值"] = 0;
			}
			elseif ($lastWeek==0){
				$this->result_str["前一周的值"] = 0;
			}
			else {
				$this->result_str["前二周的值"] = 0;
			}
			$run_result = 0;
		}
		else{
			//获取前一周同比波动值
			$abs=abs(($lastWeek-$today)/$lastWeek);
			$abs=round($abs,2);	
			//获取前二周同比波动值
			$abs1=abs(($lastlastWeek-$today)/$lastlastWeek);
			$abs1=round($abs1,2);	
			var_dump($abs);	
			var_dump($abs1);	
			var_dump("rule".$rule->day);
			
			eval("\$condition = ". $abs . $rule->day . ";");
			eval("\$condition1 = ". $abs1 . $rule->day . ";");
			
			//如果同比结果浮动均超过预期值
			if((!$condition)&&(!$condition1)){
				//正向增长除外，可能为节假日高峰期
				if($today<$lastWeek){
					$this->result_str["昨天的值"] = $today;
					$this->result_str["前一周的值"] = $lastWeek;
					$this->result_str["前二周的值"] = $lastlastWeek;
					$this->result_str["预期周浮动值"] = $rule->day;
					$this->result_str["实际周浮动值"] = "一周对比:".$abs.",二周对比:".$abs1;
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
    	return $run_result;
	}
	
	
}