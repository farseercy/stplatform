<?php
require_once 'ExecuterKafka.php';
/**
 * 
 * @author liuxiaochun03
 * phpui php日志请求量监控
 *
 */
class ExecuterKafkaBphpuiqt extends ExecuterKafka {
	protected function get_tbtable(){
		return "tb_phpuipv";
	}
	
	protected function get_tbfield(){
		return "sum(cnt)";
	}
	
	protected function get_tbhint(){
		return "请求量同比";
	}

	protected function get_hbtable(){
		return "tb_phpuipv";
	}
	
	protected function get_hbfield(){
		return "sum(cnt)";
	}
	
	protected function get_hbhint(){
		return "请求量环比";
	}	
}