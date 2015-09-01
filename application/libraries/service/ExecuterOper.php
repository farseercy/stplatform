<?php
require_once 'AbstractExecuter.php';
/**
 * 
 * @author liuxiaochun03
 *
 */
class ExecuterOper extends AbstractExecuter {	
	var $result_str = array();

	protected function get_reportmsg(){
		$str = "";
		foreach ($this->result_str as $key => $value) {
			$str = $str . $key . ":" . $value . "; ";
		}
		
		return $str;
	}
	
	private function get_monquery($host, $key) {
		//统计150秒前到50秒前的数据，因为统计工具有延迟，所以预留50秒的汇总时间
		$endtime = date("YmdHis", strtotime("-" . 50 . " seconds"));
		$begintime = date("YmdHis", strtotime("-" . 650 . " seconds"));		
		$cmd = dirname(__FILE__) . "/monquery -n " . $host . " -i ". $key . 
		       " -s " . $begintime . " -e " . $endtime . " -d 600 -o json 2> /dev/null";
		$result = exec($cmd);
		$json_data = json_decode($result);
		if(empty($json_data)) {
			echo $cmd;
			echo "\n";
			return NULL;
		}
		
		$len = count($json_data->item);
		if ($len == 0) {
			return NULL;
		}		
		return $json_data->item[0]->value;
	}
	
	private function ping($host) {
		$cmd = "ping -c 1 -w 2 " . $host . ' 2> /dev/null | grep "1 received" | wc -l';
		$success = exec($cmd);
		return $success;
	}
	
	protected function run_steps(){
		$run_result = 1;
		$rule = json_decode($this->case->content);
		$host = $rule->host;
		$frequency = $this->case->frequency;
		do {
			$connect = $this->ping($host);
			if (false == $connect) {
				$this->result_str["connectivity"] = "not ok";
				$run_result = 0;
				break;
			}
			
			//获取CPU_IDLE信息并根据阈值校验
			$svr_cpu_idle = $this->get_monquery($host, "CPU_IDLE");
			if(empty($svr_cpu_idle))
			{
				$run_result = 0;
				break;
			}
			eval("\$conditon = ". $svr_cpu_idle . $rule->cpu_idle . ";");
			if(!$conditon)
			{
				$this->result_str["cpu_idle".$rule->cpu_idle] = "failed";
				$run_result = 0;
			}
			
			//获取MEM_FREE信息并根据阈值校验
			$svr_mem_free = $this->get_monquery($host, "MEM_FREE_PERCENT");
			if(empty($svr_mem_free))
			{
				$run_result = 0;
				break;
			}
			eval("\$conditon = ". $svr_mem_free . $rule->mem_free . ";");
			if(!$conditon)
			{
				$this->result_str["mem_free".$rule->mem_free] = "failed";
				$run_result = 0;
			}
			
			//获取DISK_FREE信息并根据阈值校验
			$svr_disk_use = $this->get_monquery($host, "DISK_TOTAL_USED_PERCENT");
			if(empty($svr_disk_use))
			{
				$run_result = 0;
				break;
			}
			eval("\$conditon = " . (100 - $svr_disk_use) . $rule->disk_free . ";");
			if(!$conditon)
			{
				$this->result_str["disk_free".$rule->disk_free] = "failed";
				$run_result = 0;
			}
		} while (false);
		
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