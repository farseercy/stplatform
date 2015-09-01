<?php
if(!defined('BASEPATH')) EXIT('No direct script asscess allowed');
require_once( APPPATH . 'libraries/Smarty/libs/Smarty.class.php' );

class Cismarty extends Smarty {
	protected $ci;

	function Cismarty(){
		parent::Smarty();
		log_message('debug', "Smarty Class Initialized");
	}

	function __construct(){
		parent::__construct();

        $this->ci = & get_instance();
        $this->ci->load->config('smarty');//加载smarty的配置文件
        //获取相关的配置项
        $this->template_dir   = $this->ci->config->item('template_dir');
        $this->setCompileDir($this->ci->config->item('compile_dir'));
        $this->cache_dir      = $this->ci->config->item('cache_dir');
        $this->config_dir     = $this->ci->config->item('config_dir');
        $this->caching        = $this->ci->config->item('caching');
        $this->cache_lifetime = $this->ci->config->item('lefttime');
        $this->left_delimiter = $this->ci->config->item('left_delimiter');
        $this->right_delimiter = $this->ci->config->item('right_delimiter');

		// Assign CodeIgniter object by reference to CI
		if ( method_exists( $this, 'assignByRef') ){
			$ci =& get_instance();
			$this->assignByRef("ci", $ci);
		}
		log_message('debug', "Smarty Class Initialized");
	}

	function view($template, $data = array(), $return = FALSE){
		foreach ($data as $key => $val){
			$this->assign($key, $val);
		}

		if ($return == FALSE){
			$CI =& get_instance();
			$CI->output->final_output = $this->fetch($template);
			return;
		}else{
			return $this->fetch($template);
		}
	}
}