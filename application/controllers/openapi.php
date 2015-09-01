<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * 
 * 对外开放的API接口
 * @author liuxiaochun03
 *
 */
class Openapi extends CI_Controller {
	function __construct()
	 {
	 	parent::__construct();
	 }
	 
	 /**
	  * 提供发送各种消息的功能
	  * type: 1为email, 2为hi群组消息, 3为短信
	  * receiver: type为1和3时直接填写域账号(如liuxiaochun03),为2时为hi群号
	  * title：type为2和3时会作为内容使用,type为1时为邮件的标题
	  * content：只有type为1时会用到，邮件正文
	  * token: 为授权标识，固定为21b35ed43a6a09f8c420da5609e14954
	  */
	 public function notify() {
	 	$this->load->library("common/Notifier");
		$type = $this->input->post("type");
		$receiver = $this->input->post("receiver");
		$title = $this->input->post("title");
		$content = $this->input->post("content");
		$token = $this->input->post("token");
	 	$this->notifier->send($type, $receiver, $title, $content, $token);
	 }

}
