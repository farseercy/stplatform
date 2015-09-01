<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class Notifier {
	const NOTIFY_NONE = 0;
	const NOTIFY_EMAIL = 1;
	const NOTIFY_HI = 2;
	const NOTIFY_SMS = 3;
	const NOTIFY_SPLIT = ';';
	const NOTIFY_SUFFIX = '@baidu.com,';
	const NOTIFY_TOKEN = "21b35ed43a6a09f8c420da5609e14954";
	
	var $CI = NULL;

	/**
	 * 
	 * 构造函数
	 */
	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper("http");
		$this->CI->load->config("common");
		$this->CI->load->library('email');		
	} 

	private function get_emaillist($receiver){
		if (!isset($receiver)){
			return $receiver;
		}
		$result = str_replace(self::NOTIFY_SPLIT, self::NOTIFY_SUFFIX, $receiver . self::NOTIFY_SUFFIX);
		return $result;
	}
	
	/**
	 * 
	 * 发送通知的函数
	 * @param 通知类型 $type(sms-短信 email-邮件 hi-HI)
	 * @param 接受者列表 $receiver
	 * @param 标题 $title
	 * @param 内容 $content
	 * @param TOKEN $token
	 */
	public function send($type, $receiver, $title, $content, $token){
		$errmsg = '{"errmsg":"token is invalid."}';
		$okmsg = '{"errmsg":"ok"}';
		if (! in_array($token, $this->CI->config->item("token_array"))){
			return $errmsg;
		}
		
		if (self::NOTIFY_SMS == $type) {
			$req_str = "&receiver=" . $receiver . "&content=" . urlencode($title);
			$url = $this->CI->config->item("sms_url") . $req_str;
			http_get($url);
						
		} elseif (self::NOTIFY_EMAIL == $type) {
			$this->CI->load->library('email');
			$this->CI->email->from('liuxiaochun03@baidu.com', 'liuxiaochun03');
			$this->CI->email->to($this->get_emaillist($receiver));		
			$this->CI->email->subject('[监控报警]'.$title);
			$this->CI->email->message($content); 			
			$this->CI->email->send();			
			
		} elseif(self::NOTIFY_HI == $type){
			$cmd = 'echo "custom_send_grp_msg maphibot@163.com mapapptest ' .
				    $receiver . ' ' . $content . ' " | nc dbl-wise-vs-ios00.dbl01 14440';
			system($cmd);
		} else {
			return $errmsg;
		}
		
		return $okmsg;
	}
}