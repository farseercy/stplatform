<?php
function obj_expect($src, $trg)
{
}

function obj_to_map($obj, $attr, &$map) {
	$format_attr = "";
	if (empty($obj)) {
		return;
	}
	if (empty($attr)) {
		$format_attr = $attr . "%s";
	} else if (gettype($obj) == 'array') {
		$format_attr = $attr . "[%s]";
	} else if (gettype($obj) == 'object') {
		$format_attr = $attr . "->%s";
	}
	foreach ($obj as $key =>$value) {
		$curr_attr = sprintf($format_attr, $key);
		if ((gettype($value) == 'array') || (gettype($value) == 'object')) {
			obj_to_map($value, $curr_attr, $map);
		} else {
			$map[$curr_attr] = $value;
		}
	}
}

function json_obj_expect($actual, $expect, &$result)
{
	$actual_map = array();
	$expect_map = array();
	obj_to_map($actual, "", $actual_map);
	obj_to_map($expect, "", $expect_map);
	foreach ($expect_map as $key =>$value) {
		if (array_key_exists($key, $actual_map)) {
			$actual_value = $actual_map[$key];
			if ($value != $actual_value) {
				$result[] = "[$key] actual:$actual_value expect: $value";
			}
		} else {
			$result[] = "[$key] not exists in actual.";
		}
	}
	if(count($result))
		return false;
	else
	    return true;
}