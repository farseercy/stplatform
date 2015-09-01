<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
require_once dirname(__FILE__) . '/../common/Notifier.php';
/**
 * Case执行器抽象类
 * @author liuxiaochun03
 *
 */
abstract class AbstractExecuter {
	var $case = NULL;
	var $CI = NULL;

	
	private function in_time_range(){
		$curr_hour = (int)date('H',time());
		$curr_minute = (int)date('i',time());		
		$validtime = $this->case->validtime;
		$time_info = json_decode($validtime, true);
		$start = explode(':', $time_info["start"]);
		$end = explode(':', $time_info["end"]);
		if (count($start) != count($end) || count($start) == 0) {
			return true;
		}
		$hour_start = (int)$start[0]; 
		$hour_end = (int)$end[0]; 
		if($hour_start > $curr_hour || $hour_end < $curr_hour) {
			return false;
		}	
		if($hour_start < $curr_hour || $hour_end > $curr_hour) {
			return true;
		}	
		
		if($hour_start == $curr_hour) {
			if (count($start) == 1) {
				return true;				
			}
			$minute_start = (int)$start[1];
			if ($minute_start > $curr_minute) {
				return false;
			}
		}
		
		if($hour_end == $curr_hour) {
			if (count($end) == 1) {
				return true;				
			}
			$minute_end = (int)$end[1];
			if ($minute_end < $curr_minute) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 是否允许执行
	 */
	protected function run_permit(){	
		if (0 == $this->case->startflag || !$this->in_time_range()){
			return false;
		}
		return true;
	}	
	
	/**
	 * 
	 * 执行Case的具体步骤
	 */
	abstract protected function run_steps();
	
	/**
	 * 
	 * 获取报警的错误内容
	 */
	abstract protected function get_reportmsg();
	
	/**
	 * 根据运行的失败结果判断属于那种告警
	 */
	private function determine_alerttype($expect_cnt, $type){
		$seconds = $this->case->frequency * $expect_cnt;
		$btime = date("Y-m-d His", strtotime("-" . $seconds . " seconds"));
		$real_cnt = $this->CI->result_model->get_result_failcnt($this->case->id, $type ,$btime);
		if ($real_cnt >= $expect_cnt) {
			return $type;
		}
		
		return Notifier::NOTIFY_NONE;
	}
	
	/**
	 * 根据报告类型和错误信息等存储报警 并返回添加的report信息
	 */
	protected function add_report_bytype($reptype){
    	$report = array();
    	$report["caseid"] = $this->case->id;
    	//$report["ctype"] = $this->case->ctype;
    	$report["productid"] = $this->case->productid;
    	$report["parentlayer"] = $this->case->parentlayer;
    	$report["childlayer"] = $this->case->childlayer;
    	$report["title"] = "[监控报警]" .$this->case->title;
    	$report["reptype"] = $reptype;
    	$report["status"] = 0;   
    	$report["content"] = "标题: [Level-" . $this->case->clevel . "]" . $this->case->title ."\n详细信息：". $this->get_reportmsg();
    	$this->CI->load->model('report_model');
    	$report["id"] = $this->CI->report_model->add_report($report);
    	return $report;
	} 


	/**
	 * 执行运行结果的报告
	 */
	protected function run_report(){
		//获取case对应的报警策略
 		$this->CI->load->model('alertcfg_model');
    	$alertcfg = $this->CI->alertcfg_model->GetAlertCfgById($this->case->alertid);
    	if (empty($alertcfg)) {
    		return;
    	}
    	
    	//判断短信告警还是邮件告警               to-do 更新报警 / 是否在工作时间报警     	
    	$rec_list = "";
 		$reptype = Notifier::NOTIFY_NONE;
    	do {
    		$this->CI->load->model('result_model');
	    	//优先检查是否符合短信告警条件
	    	$reptype = $this->determine_alerttype($alertcfg->smscnt, Notifier::NOTIFY_SMS);
	    	if(Notifier::NOTIFY_SMS == $reptype){
	    		$rec_list = $alertcfg->smslist;
	    		break;
	    	}
	    	//检查是否符合邮件告警条件
	    	$reptype = $this->determine_alerttype($alertcfg->emailcnt, Notifier::NOTIFY_EMAIL);
	    	$rec_list = $alertcfg->emaillist;
	    	
    	} while (false);
    	
    	if (Notifier::NOTIFY_NONE == $reptype) {
    		return;
    	}
    	
    	//添加报告
    	$report = $this->add_report_bytype($reptype);
    	//更新case运行结果为已发送报告状态
    	$this->CI->load->model('result_model');
    	$this->CI->result_model->update_resultalert($this->case->id, $reptype);
    	//进行告警
    	$this->CI->load->library("common/Notifier");
 		$this->CI->notifier->send($reptype, $rec_list, $this->case->title, $report["content"], Notifier::NOTIFY_TOKEN);
	}
	
	protected function get_config($config_item){
		return $this->CI->config->item("alert_uname");
	}

	private function get_calcstr($data) {
		$unit = "";
		if (!empty($data)) {
			$unit_str = substr($data,-1);
			switch ($unit_str)
			{
				case "w":
					$unit = " weeks";
					break;
				case "d":
					$unit = " days";
					break;
				case "h":
					$unit = " hours";
					break;
				case "m":
					$unit = " minutes";
					break;
				default:
					$unit = " minutes";
			}
			$start_data = substr($data,0,-1);
			if(is_numeric($start_data)) {
				return "-" . $start_data . $unit;
			}
		}
		return "";	
	}
	
	protected function parse_periods($case){
		$periods = new stdClass();
		//当前周期
		$period_curr = new stdClass();
		//上个周期
		$period_last = new stdClass();
		//指定周期
		$period_spec = new stdClass();
		//历史周期(从当前时间算起到某个时间点)
		$period_hisy = new stdClass();	
		
		$rule = json_decode($this->case->content);
		$frequency = intval($this->case->frequency);
		
		$period_curr->end = date("Y-m-d H:i:s", strtotime("-" . 2 . " minutes"));	
		$rst = 2 + $frequency;
		$period_curr->start = date("Y-m-d H:i:s", strtotime("-" . $rst . " minutes"));
		
		
		$period_last->end = $period_curr->start;
		$rst = $rst + $frequency;
		$period_last->start = date("Y-m-d H:i:s", strtotime("-" . $rst . " minutes"));
		
		@$calcstr = $this->get_calcstr($rule->spectime);
		if (empty($calcstr)) {
			$period_spec->end = date("Y-m-d H:i:s", strtotime("-1 weeks"));
			$period_spec->start = date("Y-m-d H:i:s", strtotime("-1 weeks -" . $frequency . "minutes"));
		} else {
			$period_spec->end = date("Y-m-d H:i:s", strtotime($calcstr));
			$period_spec->start = date("Y-m-d H:i:s", strtotime($calcstr . " -" . $frequency . "minutes"));			
		}
		
		@$calcstr = $this->get_calcstr($rule->hisytime);
		if (empty($calcstr)) {
			$period_hisy->end = '';
			$period_hisy->start = '';		
		} else {
			$period_hisy->end = date("Y-m-d H:i:s", strtotime("-" . 2 . " minutes"));
			$period_hisy->start = date("Y-m-d H:i:s", strtotime($calcstr));
		}
		
		$periods->curr = $period_curr;
		$periods->last = $period_last;
		$periods->spec = $period_spec;
		$periods->hisy = $period_hisy;
		
		return $periods;
	}
	
	/**
	 * 
	 * 构造函数
	 */
	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper("http");
	} 
	
	/**
	 * 执行Case后清理
	 */
	protected function run_clean(){
		return;
	}
	
	/**
	 * 执行Case
	 */
	public function run($case){
		$this->case = $case;
		if (empty($this->case)){
			return;
		}

		//是否允许运行
		if ($this->run_permit()){
			//运行失败检查是否发送报告
			if (!$this->run_steps()){
				$this->run_report();
			}
			$this->run_clean();
		}
	}
	
	/**
	 * 处理结果，针对jenkins
	 */
	public function dealRev($case){
		$this->case = $case;
		if (empty($this->case)){
			return;
		}

		//运行失败检查是否发送报告
		if (!$this->deal_result()){
			$this->run_report();
		}
		$this->run_clean();
	}
}