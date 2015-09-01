<?php
$param_cnt =  $_SERVER["argc"];
if ($param_cnt != 3) {
	echo "param is error.";
	exit(1);
}
$path = $_SERVER["argv"][1];
$class = $_SERVER["argv"][2];
require_once "$path";
$case = new $class();
$case->run();



