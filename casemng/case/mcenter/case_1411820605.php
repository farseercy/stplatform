<?php 
##user is liuyangyang
require_once dirname(__FILE__) . '/../../common/basetest.php';
class Case_1411820605 extends BaseTest {
protected function get_id(){
return "1411820605";
}
protected function get_title(){
return "n接口_校验";
}
protected function execute(){
$data=http_send("http://10.99.33.39:8000/mcenter/n?mcenter=1234","");
$res="{errno:0,errmsg:ok,result:{newnum:0}}";
$this->assert_json(__LINE__,$data,$res);
}
}
