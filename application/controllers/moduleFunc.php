<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
//$_SESSION['module']="";
class ModuleFunc extends MY_Controller {
	
	var $platInfoArray;//平台信息
	
	var $case = array(
		'caseid' => '',             //id
		'proname' => '',        //case名称
		'user' => '',           //case类型
		'purpose' => '',   
		'result' => '',         //case对应的产品线id
		'time' => '',
		'get' => '',
		'ip_port' => '',
		'post' => '',
		'test' => '',
		);
	
	function __construct(){
		parent::__construct();
		set_time_limit(1800);
		$this->load->helper('url');
		$this->load->helper("http");
		//var_dump(base_url());die();
		$this->cismarty->assign("baseurl", base_url());
	}
	
    public function index()
    {
        $this->show("",0);
    }
	public function exchangeLevel($module,$type,$offset=0)
    {
    	$this->show($module,$type,$offset);
    }
    
    private function show($module,$type,$offset=0)
    {   
    	$platInfoArray = $this->config->item('plat_info_array');
    	$platInfoArray['curModule']=$module;
		$this->load->model('case_model');
    	$arrModule=$this->case_model->get_all_modules('tb_module','name');
		$Interfaces=$this->case_model->get_interfaces_by_modName('tb_module',$module);
		$arrInterface=@split(";",$Interfaces);
		//var_dump($arrInterface);
		$caseInfo = $this->config->item('case_list_info');
	    $caseInfo['offset'] = $offset;	    	    	               
	    $caseInfo['total_rows'] = $this->case_model->get_total_byMod('tb_func',$module);
		//var_dump($caseInfo);
	    $cur_url=$this->getmanagetabURL($module,$type);
	    $cases = $this->case_model->get_func_cases('tb_func',$module,$caseInfo['per_page'],$offset);
		//var_dump($cases);
        $this->cismarty->assign('platInfo', $platInfoArray); 
		$this->cismarty->assign('caseInfo', $caseInfo); 
		$this->cismarty->assign('curModule', $module); 
		$this->cismarty->assign('arrModule', $arrModule); 
		$this->cismarty->assign('arrInterface', $arrInterface); 
		$this->cismarty->assign('cases', $cases);
		$this->cismarty->assign('cur_url', $cur_url);
	    //$this->cismarty->assign('cur_url', $cur_url);  
	    $this->cismarty->assign('caseInfo', $caseInfo);      
        $this->cismarty->display(APPPATH . '/views/module/func.tpl');   
    }
    private  function getmanagetabURL($module,$type){
		echo $this->uri->segment(6);
    	return base_url()."index.php/moduleFunc/exchangeLevel/".$module."/".$type.$this->uri->segment(6);
    } 
	public function delCases(){
		//$callback = $_GET['callback'];
    	//echo $callback.'('.json_encode($output).')';
	}
	public function runCases() {
	}
	
	public function getCases() {
	}
	
	public function getAutoTest() {
	}
	
    public function http_post($url, $data)
	{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
	}
	public function http_get($url)
	{
	        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $url);
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			        curl_setopt($ch, CURLOPT_HEADER, 0);
			        $output = curl_exec($ch);
				        curl_close($ch);
				        return $output;
	}
	public function http_send($url, $data='') {
	    $result = null;
			if(empty($data)) {
				$result = $this->http_get($url);
	        } else {
			    $result = $this->http_post($url, $data);
			}
		return $result;
	}	
}
