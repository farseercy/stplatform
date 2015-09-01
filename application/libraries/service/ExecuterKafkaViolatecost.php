<?php
require_once 'ExecuterKafka.php';
/**
 * 
 * @author liuxiaochun03
 * phpui php日志统计 失败率的监控执行器
 *
 */
class ExecuterKafkaViolatecost extends ExecuterKafka {
	protected function get_jztable(){
		return "tb_violation_qt";
	}
	
	protected function get_jzfield(){
		return "sum(cost/total)/count(total)";
	}
	
	protected function get_jzhint(){
		return "请求响应时间(s)";
	}

	protected function get_jctable(){
		return "tb_violation_qt";
	}
	
	protected function get_jcfield(){
		return "sum(cost/total)/count(total)";
	}
	
	protected function get_jchint(){
		return "请求响应时间增长率";
	}	
}