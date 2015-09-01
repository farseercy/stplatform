<?php 
##user is liuyangyang
require_once dirname(__FILE__) . '/../../common/basetest.php';
class Case_1415180288 extends BaseTest {
protected function get_id(){
return "1415180288";
}
protected function get_title(){
return "离线日志上传_校验";
}
protected function execute(){
$post="{\"ver\":\"2\",\"pd\":\"map\",\"im\":\"2\",\"os\":\"android\",\"datafile\":\"@".dirname(__FILE__)."/20130913100335.dat\"}";
$postParam=json_decode($post,true);
$data=http_send("http://10.99.33.39:8202/ulog/public/up.php",$postParam);
$res="{\"errno\":0}";
$this->assert_json(__LINE__,$data,$res);
}
}
