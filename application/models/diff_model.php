<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* author: panhongguang@baidu.com
*/
class Diff_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	/**
		获取词表文件
		str_wget：wget 机器名、log文件路径信息
		str_regex： 词表匹配表达式
		log_pos：请求串在请求串的偏移量（以空格分隔）
	*/
	function get_dict($str_wget, $str_regex, $log_pos)
	{
		# get one hour ago log
		$time = `date -d "1 hour ago" +"%Y%m%d%H"`;
		$log_name = "log_".$time."log";
		$dict_name = "dict_".$time."txt";
		$dict_path = APPPATH.'diffPro/dict/';
		$str_wget = "wget -c -q -r -nH -l 20 --limit-rate=8m ftp://$str_wget.$time -P $dict_path -O $log_name";

		// 判断log文件是否存在，不存在则wget下载
		if (!file_exists($dict_path.$log_name)) {
			shell_exec($str_wget);
			// 下载是否失败：
			// TODO
		}

		$str_awk = "cd $dict_path && grep $str_regex $log_name | awk '{print $$log_pos}'>$dict_name";
		shell_exec($str_awk);
		// awk是否失败：
		// TODO
		return $dict_name;
	}

}