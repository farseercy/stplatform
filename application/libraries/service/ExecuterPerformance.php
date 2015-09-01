<?php
require_once 'AbstractExecuter.php';
/**
 * 
 * @author wangshuang03
 *
 */
class ExecuterPerformance extends AbstractExecuter {	
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
		//$item='205';	
		
		$today = 0;
		$lastDay = 0;
		$lastWeek = 0;
		$timeIndex = 0;		
		
		//落地页性能监控
		if($item!='205'){
			//获取实时数据
//			$realData = json_decode($this->getRealTime($item));
//			for ($i=0 ;$i<count($realData); $i++){
//				if($realData[$i]->name=="用户可操作"){
//					$timeIndex = count($realData[$i]->data)-1;
//					$realTime = $realData[$i]->data[$timeIndex][0];
//					var_dump($realData[$i]->data[$timeIndex][0]);
//					$today = $realData[$i]->data[$timeIndex][1];
//					//var_dump("today".$today);
//				}
//			}
//			 		
//			
//			//获取昨天的数据		
//			$lastDay = $this->getHistory($item, -1,$timeIndex);
//			//获取上周数据的平均值
//			$temp=0;
//			for($i=0;$i<7;$i++){
//				$temp += $this->getHistory($item, -7-$i,$timeIndex);
//			}	
//			$lastWeek = round($temp/7,2);
			$start = -8;
			$end = -1;
			$temp = json_decode($this->getLandingPageHistoryData($item, $start, $end));
			$today=$temp->data->item[2]->data[7];
			$lastDay=$temp->data->item[2]->data[6];
			$lastWeek=$temp->data->item[2]->data[0];
		}
		//底图性能监控
		else{
			$start = -8;
			$end = -1;
			$temp = json_decode($this->getMapsHistoryData($item, $start, $end));
			$today=$temp->data->item[8]->data[7];
			$lastDay=$temp->data->item[8]->data[6];
			$lastWeek=$temp->data->item[8]->data[0];
			
			//var_dump($temp);
			
		}
		var_dump("today:".$today);
		var_dump("lastday:".$lastDay);
		var_dump("lastweek:".$lastWeek);
		//die();

		if($lastDay==-1||$lastWeek==-1){
			$this->result_str["获取历史数据"] = '失败';
			$run_result = 0;
		}		
		//如果数据为0，直接认为失败
		elseif($today==0||$lastDay==0||$lastWeek==0){
			if($today==0){
				$this->result_str["实时的值"] = 0;
			}
			elseif ($lastDay==0){
				$this->result_str["昨天的值"] = 0;
			}
			else {
				$this->result_str["前一周的平均值"] = 0;
			}
			$run_result = 0;
		}
		else{
			//获取前一周同比波动值
			$abs=abs($lastDay-$today);
			$abs=round($abs,2);	
			//获取前二周同比波动值
			$abs1=abs($lastWeek-$today);
			$abs1=round($abs1,2);	
			var_dump("日同比:".$abs);	
			var_dump("周同比:".$abs1);	
			var_dump("rule".$rule->day);
			
			eval("\$condition = ". $abs . $rule->day . ";");
			eval("\$condition1 = ". $abs1 . $rule->week . ";");
			
			//var_dump($condition);
			//var_dump($condition1);
			
			//如果同比结果浮动均超过预期值
			if((!$condition)&&(!$condition1)){
				//正向增长除外，可能为节假日高峰期
				//if($today>$lastDay){
				if($item!='205'){
					$this->result_str["实时时间"] = $realTime;
				}
					$this->result_str["实时的值"] = $today;
					$this->result_str["昨天的值"] = $lastDay;
					$this->result_str["前一周的平均值"] = $lastWeek;
					$this->result_str["预期浮动值"] = $rule->day;
					$this->result_str["实际浮动值"] = "日同比:".$abs.",周同比:".$abs1;
					$run_result = 0;
				//}
			}				
		}
		//var_dump($today);var_dump($lastDay);
		//var_dump($run_result);
		if ($run_result) {
			var_dump("执行成功");
			return true;
		}

		var_dump("执行失败");
		
		//将执行结果存储到case里	
		$result = array();
		$result["caseid"] = $this->case->id;
		$result["detail"] = $this->get_reportmsg();
		$result["exerst"] = $run_result;
    	$this->CI->load->model('result_model');
    	$this->CI->result_model->add_result($result);
    	return $run_result;
	}
	
	//获取实时数据
	private function getRealTime($page){
		$url="http://webspeed.baidu.com/pms_v2/api/realTimeData.php";
		$post_data = array(
			'product' => '16',
			'page' => $page ,
			'cate' => 'wsp_all',
			'type' => 'trend',
			'period' => '1800'
		);
		
		//用get方式获取数据
		$querystr=$url.'?'.http_build_query($post_data);
		//var_dump($querystr);die();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $querystr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
		$ret = curl_exec($ch);

		if (curl_errno($ch) || !is_string($ret) || !strlen($ret)) {
             $ret = '';
        }
		curl_close($ch);
		return $ret;
	}
	
	//获取历史数据
	private function getHistory($page, $day, $timeIndex){
		$url="http://webspeed.baidu.com/pms_v2/api/service.php";
		$date = $temp['ctime']=date('Y-m-d',time() +$day * 86400);
		$post_data = array(
			'product' => '16',
			'page' => $page ,
			'lines' => 'core',
			'perf' => 'render',
			'action' => 'detailTrend',
			'type' => 'summary',
			'date' => $date
		);
		
		//用get方式获取数据
		$querystr=$url.'?'.http_build_query($post_data);
		//var_dump($querystr);die();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $querystr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
		$i=3;
		while($i!=0){
			$ret = curl_exec($ch);
			$data = json_decode($ret);
			if($data->status=='success'){
				break;
			}else {
				$i--;
			}
		}

		if (curl_errno($ch) || !is_string($ret) || !strlen($ret)) {
             $ret = '';
        }
		curl_close($ch);
		
		//获取历史同一时间的数据					
		if($i!=0){
			$data = json_decode($ret);
			//var_dump($date);
			//var_dump($data);die();
			var_dump($data->data->timePoint[$timeIndex+1]);
			$data = $data->data->item;
			for ($i=0 ;$i<count($data); $i++){
				if($data[$i]->name=="用户可操作"){
					//实时数据从0点半开始算，历史数据从0点开始算
					if($timeIndex+1==count($data[$i])){
						$today = $data[$i]->data[0];
					}else{
						$today = $data[$i]->data[$timeIndex+1];
					}				
					return $today;
				}
			}
		}else{
			return -1;
		}

		return -1;
	}
	
	//获取落地页性能数据
	private function getLandingPageHistoryData($page, $start, $end){
		$url="http://webspeed.offlineb.bae.baidu.com/pms_v2/api/service.php";
		$start=date('Y-m-d',time() + $start * 86400);
		$end=date('Y-m-d',time() + $end * 86400);
		
		$post_data = array(
			'product' => '16',
			'page' => $page,
			'lines' => 'core',
			'perf' => 'net',
			'type' => 'summary',
			'start' => $start,
			'end' => $end,
			'action' => 'summaryTrend',
		);
		
		//用get方式获取数据
		$querystr=$url.'?'.http_build_query($post_data);
		//var_dump($querystr);die();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $querystr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
		$ret = curl_exec($ch);

		if (curl_errno($ch) || !is_string($ret) || !strlen($ret)) {
             $ret = '';
        }
		curl_close($ch);
		return $ret;
	}
	
	//获取底图性能数据
	private function getMapsHistoryData($page, $start, $end){
		$url="http://webspeed.offlineb.bae.baidu.com/pms_v2/api/service.php";
		$start=date('Y-m-d',time() + $start * 86400);
		$end=date('Y-m-d',time() + $end * 86400);
		
		$post_data = array(
			'product' => '16',
			'app[]'=> $page,
			'type' => 'multi_summary',
			'start' => $start,
			'end' => $end,
			'action' => 'summaryTrend'
		);
		
		//用get方式获取数据
		$querystr=$url.'?'.http_build_query($post_data);
		//var_dump($querystr);die();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $querystr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
		$ret = curl_exec($ch);

		if (curl_errno($ch) || !is_string($ret) || !strlen($ret)) {
             $ret = '';
        }
		curl_close($ch);
		return $ret;
	}
}