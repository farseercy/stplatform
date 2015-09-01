<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * 
 * @author liuxiaochun03
 *
 */
class ExecuteFactory {
	static $hashmap = array(
		"oper" => "ExecuterOper",
		"proEffect" => "ExecuterProEffect",
		"jenkinsCase" => "ExecuterSystemCase",
		"performance" => "ExecuterPerformance",
		"bphpuiqtfail" => "ExecuterBphpuiqtfail",
		"bphpuiqtcost" => "ExecuterBphpuiqtcost",
		"violatefail" => "ExecuterKafkaViolatefail",
		"violatecost" => "ExecuterKafkaViolatecost",
		"fphpuipack" => "ExecuterKafkaFphpuipack",
		"bphpuiqt" => "ExecuterKafkaBphpuiqt"
	);
	
	/**
	 * 获取工厂单例
	 * @param 类型 $ctype
	 */	
	static public function getinstance($ctype){
		$class = self::$hashmap[$ctype];
		if(!class_exists($class))
		{
			require_once $class . '.php';			
		}
		return new $class();
	}
}