<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {
	//10.94.23.71:8765/mtplatform/index.php
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->cismarty->assign("baseurl", base_url());
	}
	
    public function index()
    {
	echo "welcome";
    }

    /**
   	 * 显示opinfo.php配置的key对应的所有服务列表
   	 */
    public function showopergraph($key)
    {
    	$this->load->model('oper_model');
    	$groups = $this->oper_model->get_groups($groupkey);
	   	$this->cismarty->assign('groups', $groups);
	   	$this->cismarty->assign('prefix', $this->config->item("op_group_prefix"));
	    $this->cismarty->display('index.tpl');
    }

    /**
   	 * 获取对应服务的机器列表
   	 */
    public function showcurrgroup($service)
    {
    	$this->output->set_header('Content-Type: application/json; charset=utf-8');
    	$this->load->model('oper_model');
    	$hostinfos = $this->oper_model->get_hostinfos_bygroup($service);
    	echo json_encode($hostinfos);
    }

    /**
   	 * 显示所有case
   	 * @param 分页参数 $offset(等于页数乘每页行数)
   	 */
    public function showcases($offset)
    {
	    $this->load->library('pagination');
	    $this->load->database();
	    $config['total_rows'] = $this->db->count_all('tb_cases');
	    $config['per_page'] = 10;
	    $config['uri_segment'] = 3;	 
	    $this->pagination->initialize($config);	                
	    $this->load->model('oper_model');
    	$cases = $this->oper_model->get_cases($config['per_page'],$this->uri->segment(3));
	    $this->cismarty->assign('cases', $cases);
	    $this->cismarty->assign('total_rows', $config['total_rows']);
	    $this->cismarty->assign('per_page', $config['per_page']);
	    $this->cismarty->assign('offset', $offset);
	    $this->cismarty->assign("baseurl", base_url());
	    $this->cismarty->display('listcase.tpl');
    }  

   	/**
   	 * 显示所有case的运行结果
   	 * @param 分页参数 $offset(等于页数乘每页行数)
   	 */
    public function showresults($offset)
    {
	    $this->load->library('pagination');
	    $this->load->database();
	    $config['total_rows'] = $this->db->count_all('tb_cases');
	    $config['per_page'] = 10;
	    $config['uri_segment'] = 3;	 
	    $this->pagination->initialize($config);	                
	    $this->load->model('oper_model');
    	$cases = $this->oper_model->get_cases($config['per_page'],$this->uri->segment(3));
	    $this->cismarty->assign('cases', $cases);
	    $this->cismarty->assign('total_rows', $config['total_rows']);
	    $this->cismarty->assign('per_page', $config['per_page']);
	    $this->cismarty->assign('offset', $offset);
	    $this->cismarty->display('listresult.tpl');    	
    }    
}
