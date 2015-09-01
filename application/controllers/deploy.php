<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
$_SESSION['currentTab']=0;
$_SESSION['currentLayer']=0;
$_SESSION['modID']=14;

class Deploy extends MY_Controller {
	
	var $platInfoArray;//平台信息
	
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
        $this->show(0,0);
    }
	public function exchangeLevel($layerkey,$offset=0,$op="Emp")
    {
      	$_SESSION['selectlayer']=$layerkey;
      	$curOp=substr($op,0,2);
        if($layerkey==1&&$op!="Emp"&&$curOp=="0_"){
        	if(!empty($_FILES['file']['name'])){
				$path="./deploylog/".$_FILES['file']['name'];
				$file=$_FILES['file']['name'];
        		$fileinfo=$_FILES['file'];
	        	//var_dump($fileinfo);
    	    	if($fileinfo['size']>0){
                	move_uploaded_file($fileinfo['tmp_name'],$path);
                	$this->load->model('case_model');
        			$case['name'] =$_FILES['file']['name'];
        			$this->platInfoArray = $this->config->item('plat_info_array');
    	            $user=$this->platInfoArray['username'];
        			//$case['user'] =phpCAS::getUser();
        			$case['time'] =date("d/M/Y:H:i");//date("ymdHi");
        			$case['desc'] =$this->input->post('desc');
        			//var_dump($case);
        			$this->case_model->AddCase('tb_dep_file',$case);
        		}
	  		}	
      	}     	
    	$this->show($layerkey,$offset);
    }
    
    private function show($layerkey,$offset=0)//$modID,
    {  
    	//echo $modID;
    	$modID=$_SESSION['modID'];
    	$this->platInfoArray = $this->config->item('plat_info_array');
    	$this->platInfoArray['currentTab'] = $_SESSION['currentTab'];
    	$this->platInfoArray['currentLayer'] = $layerkey;
    	//$this->platInfoArray['username']=phpCAS::getUser();
    	//echo $this->platInfoArray['currentLayer'];
    	$this->load->model('case_model');
    	$paramArray=$this->case_model->get_cases_byModID($modID,'tb_deploy');
    	//var_dump($paramArray);
    	$params=explode(";",$paramArray[0]->params);
    	//var_dump($params);
        $this->cismarty->assign('platInfo', $this->platInfoArray);
        $this->cismarty->assign('params', $params);
        $this->cismarty->assign('moduleID', $modID);
        $this->cismarty->assign('moduleLab', $paramArray[0]->label);
    	if($this->platInfoArray['currentLayer']==0){
    		//echo "1";
        	$this->cismarty->display(APPPATH . '/views/templates/deploy/statistic/stat.tpl');   
    	}
    	else if($this->platInfoArray['currentLayer']==1){
    		$this->load->database();
	    	$caseInfo = $this->config->item('case_list_info');
	    	$caseInfo['offset'] = $offset;	
	    	$caseInfo['per_page'] = 15;		    	    	    	               
	    	$this->load->model('oper_model');
	    	$caseInfo['total_rows'] = $this->oper_model->get_total_rows('tb_dep_file');
	    	$cur_url=$this->getmanagetabURL();
	    	$cases = $this->oper_model->get_cases('tb_dep_file',$caseInfo['per_page'],$offset);
	    	$this->cismarty->assign('cases', $cases);
	    	$this->cismarty->assign('cur_url', $cur_url);  
	    	$this->cismarty->assign('caseInfo', $caseInfo); 
        	$this->cismarty->display(APPPATH . '/views/templates/deploy/statistic/list.tpl');        	
    	}
    		
    }
    private  function getmanagetabURL(){
    	return base_url()."index.php/deploy/exchangeLevel/".$this->uri->segment(3);
    } 
 	public function selModule($modID){
 		//$modID=$this->input->get('module');
 		//var_dump($modID);
 		$_SESSION['modID']=$modID;
    	$this->show(0,0);
    }
}

