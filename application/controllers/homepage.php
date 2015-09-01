<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
//$_SESSION['productid']=1;
//$_SESSION['selectlayer']=4;
//$_SESSION['selectitem']=0;
//$_SESSION['manageTab']=0;
class Homepage extends MY_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		
		$this->cismarty->assign("baseurl", base_url());
	}

	function index()
	{
		$platInfoArray = $this->config->item('plat_info_array');
        $this->cismarty->assign('platInfo', $platInfoArray);
        
		$productLines = $this->config->item('product_line_array');
		$this->cismarty->assign('productLines', $productLines);
		
		$this->load->model('case_model');
		$arrPro=$this->case_model->get_all_projects('tb_project','name');
    	$arrModule=$this->case_model->get_all_modules('tb_module','name');
		//var_dump($arrPro);	
		$this->cismarty->assign('arrModule', $arrModule);
		$this->cismarty->assign('arrPro', $arrPro);    

	   // $this->cismarty->display(APPPATH . '/views/templates/homepage.tpl');
	   $this->cismarty->display(APPPATH . '/views/homepage.tpl');
//		$this->cismarty->display(APPPATH . '/views/module/func.tpl');
		//$this->cismarty->display(APPPATH . '/views/project/func.tpl');
	}
}