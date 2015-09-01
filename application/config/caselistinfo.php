<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//监控项配置
$config["case_list_info"] = array(
	"case_list_title" =>array(
		"编号"=>"50px",
		"接口"=>"200px",
		"级别"=>"40px",
		"描述"=>"",
		"上次执行时间"=>"40px",
		"上次执行结果"=>"40px",
	),
	"total_rows" => 0, 	//总行数
	"per_page" => 5,	//每页显示行数
	"uri_segment" => 6,
	"offset" => 0,		//显示第几页，默认显示第一页
	
);

//用例类型
$config["case_type_info"] = array(
	'oper'=>'运维类',
    'proEffect'=>'统计类',
    'jenkinsCase'=>'系统级用例',
	'bphpuiqtfail' =>'PHPUI PHP日志统计类失败率',
	'bphpuiqtcost' =>'PHPUI PHP日志统计类响应时间',
	'bphpuiqt' => 'PHPUI PHP日志请求量',
	'fphpuipack' =>'PHPUI Lighttpd数据包大小',
	'violatefail' =>'违章查询失败率',
	'violatecost' =>'违章查询响应时间',
	'performance' =>'Webspeed 性能类',	
);
