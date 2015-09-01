<?php
$CONF = array();

$CONF["violate"] = array(	
	"mysql" => array(
		"ipaddr" => "10.81.14.31:5000",
		"db" => "violate",
		"user" => "violate",
		"pwd" => "violate"
	)
);
$CONF["local"] = array(	
	"mysql" => array(
		"ipaddr" => "localhost",
		"db" => "datacenter",
		"user" => "root",
		"pwd" => ""
	)
);
















function get_conf($module) {
	global $CONF;
	return $CONF[$module];
}