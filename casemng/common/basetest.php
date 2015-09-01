<?php
require_once dirname(__FILE__) . '/../conf/conf.php';
require_once dirname(__FILE__) . '/../helper/http_helper.php';
require_once dirname(__FILE__) . '/../helper/json_helper.php';
require_once dirname(__FILE__) . '/../helper/db_helper.php';

abstract class BaseTest {
	
	var $ret = true;

	protected function assert_json($line, $actual, $expect) {
		$data= $actual;
		$assert_ret = true;
		$result = array();
		$actual = json_decode($actual);
		if(empty($actual)) {
		echo $data."\n";
			echo "[line:$line][" . $this->get_id() . "]". "failed\n";
			$this->ret = false;
			return;
		}
		$expect = json_decode($expect);
		if(empty($actual)) {
		echo $data."\n";
			$this->ret = false;
			echo "[line:$line][" . $this->get_id() . "]". "failed\n";
			return;
		}
		$assert_ret = json_obj_expect($actual, $expect, $result);	
		if ($assert_ret == true) {
			return;
		} 
		
		$this->ret = false;
		echo $data."\n";
		foreach ($result as $key =>$value) {
			echo "[line:$line][" . $this->get_id() . "]". $value . "\n";
		}
	}
	
	protected function assert_eq($line, $actual, $expect) {
		if(!isset($expect)) {
			echo "[line:$line] expect is null\n";
			$this->ret = false;
			return;			
		}
		
		if($expect === $actual) {
			return;
		}
		
		$this->ret = false;
		echo "[line:$line] actual:$actual expect: $expect";
	}
	
	protected function assert_gt($line, $actual, $expect) {
		if(!isset($expect)) {
			echo "[line:$line] expect is null\n";
			$this->ret = false;
			return;			
		}
		
		if(!isset($actual)) {
			echo "[line:$line] actual is null\n";
			$this->ret = false;
			return;			
		}
		
		if($actual > $expect) {
			return;
		}
		
		$this->ret = false;
		echo "[line:$line] [actual>expect] actual:$actual expect: $expect\n";
	}
	
	/**
	 * 针对指定配置执行sql
	 * @param unknown_type $module
	 * @param unknown_type $sql
	 */
	protected function sql_clean($module, $sql) {
		$conf = get_conf($module);
		$ipaddr = $conf["mysql"]["ipaddr"];
		$db = $conf["mysql"]["db"];
		$user = $conf["mysql"]["user"];
		$pwd = $conf["mysql"]["pwd"];
		sql_execute($ipaddr, $db, $user, $pwd, $sql);
	}
	
	protected function prepare(){}
	abstract protected function get_id();
	abstract protected function get_title();	
	abstract protected function execute();
	protected function clean(){}
	
	/**
	 * 执行case
	 */
	public function run() {
		$this->prepare();
		$this->execute();
		$result_str = "failed";
		if ($this->ret) {
			$result_str = "success";	
		}
		echo("[" . $this->get_id() . "]\t[" . $this->get_title() . "] " . $result_str . "\n");
		$this->clean();
	}
}
