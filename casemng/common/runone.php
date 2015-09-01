<?php
require_once dirname(__FILE__) . '/../helper/file_helper.php';
$filenames = get_filenamesbydir(dirname(__FILE__) . "/../case/".$pro);
echo "\n-----------------start run case-------------------\n\n";
$class="case_1415104050";
$Pro="ulog_up";
#require_once "/home/map/qa/liuyangyang/mysite/lighttpd/htdocs/mysite/casemng/case/footprint/case_1410241766.php";
require_once dirname(__FILE__) . "/../case/".$Pro."/".$class.".php";
$case = new $class();
$case->run();
echo "\n-----------------end run case-------------------\n";