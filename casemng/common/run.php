<?php
require_once dirname(__FILE__) . '/../helper/file_helper.php';

$pro=$this->input->get('ID_Pro');	
$filenames = get_filenamesbydir(dirname(__FILE__) . "/../case/".$pro);
echo "\n-----------------start run case-------------------\n\n";
foreach ($filenames as $class => $path) {
	require_once "$path";
    $case = new $class();
    $case->run();
}
echo "\n-----------------end run case-------------------\n";



