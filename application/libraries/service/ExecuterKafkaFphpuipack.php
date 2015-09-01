<?php
require_once 'ExecuterKafka.php';
/**
 * 
 * @author liuxiaochun03
 * phpui php日志统计 失败率的监控执行器
 *
 */
class ExecuterKafkaFphpuipack extends ExecuterKafka {
	protected function get_jztable(){
		return "tb_acphpui_qt";
	}
	
	protected function get_jzfield(){
		return "sum(length/total)/count(total)";
	}
	
	protected function get_jzhint(){
		return "数据包均值(byte/m)";
	}

	protected function get_jctable(){
		return "tb_acphpui_qt";
	}
	
	protected function get_jcfield(){
		return "sum(length/total)/count(total)";
	}
	
	protected function get_jchint(){
		return "数据包均值增长率(byte/m)";
	}	
}