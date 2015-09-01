<?php
require_once 'json_helper.php';
function assert_json($actual, $expect) {
	$data=$actual;
	$result = array();
	$actual = json_decode($actual);
	if(empty($actual)) {
                echo " is empty,";
		echo "failed\n";
		exit(1);
	}
	$expect = json_decode($expect);
	if(empty($actual)) {
		echo $data." is empty,";
		echo "failed\n";
		exit(1);
	}
	$ret = json_obj_expect($actual, $expect, $result);	
	if ($ret == true) {
		return true;
	}
	foreach ($result as $key =>$value) {
	//	echo " ERROR!".$data."\n";
		echo $value . "\n";
	}
        //echo $data."\n";
	return false;	
}
