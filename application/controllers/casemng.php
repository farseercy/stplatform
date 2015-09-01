<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
$_SESSION['currentTab']=1;
$_SESSION['currentLayer']=0;

class AdmittanceTest extends MY_Controller {
	
	var $platInfoArray;//平台信息
	
	var $case = array(
		'caseid' => '',             //id
		'proname' => '',        //case名称
		'user' => '',           //case类型
		'purpose' => '',   
		'result' => '',         //case对应的产品线id
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
        $this->show(0);
    }
    
	public function showcase($proname,$caseid)
    {
      	$this->show($layerkey,$offset);
    }
    
    private function show($layerkey,$offset=0)
    {  
    	$this->platInfoArray = $this->config->item('plat_info_array');
    	$this->platInfoArray['currentTab'] = $_SESSION['currentTab'];
    	$this->platInfoArray['currentLayer'] = $layerkey;
    	//echo $this->platInfoArray['currentLayer'];
        $this->cismarty->assign('platInfo', $this->platInfoArray);
        
    	if($this->platInfoArray['currentLayer']==0){
    		//echo "1";
        	$this->cismarty->display(APPPATH . '/views/templates/admittanceTest/statistic/stat.tpl');   
    	}
    	else if($this->platInfoArray['currentLayer']==1){
    		//echo "2";
    		$this->load->model('case_model');
    		$sqlArray=array();
    		$sqlArray[]=$this->case_model->get_all();
    		//var_dump($sqlArray); 
    		$this->cismarty->assign('sqlres', $sqlArray);
    		//echo $this->case_model->GetCasesById("1409559972");
        	$this->cismarty->display(APPPATH . '/views/templates/admittanceTest/statistic/res.tpl');        	
    	}
    		
    }
}