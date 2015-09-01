<?php 
##user is liuyangyang
require_once dirname(__FILE__) . '/../../common/basetest.php';
class Case_1412072292 extends BaseTest {
protected function get_id(){
return "1412072292";
}
protected function get_title(){
return "test_校验";
}
protected function execute(){
$data=http_send("http://10.99.33.40:8080/loc-monitor/slave.php?type=reg&ts=10982345&sign=cc&cuid=SLAVE3559A98E6E0894F17F08F4877CD|933 861000141225&os=android&pt=1&ap=map","");
$res="{\"error\":0,\"msg\":\"\",\"data\":{\"sid\":\"25ce7a038f015df67e96231000141225\"},\"t\":23}";
$this->assert_json(__LINE__,$data,$res);
}
}
