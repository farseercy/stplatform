<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
$_SESSION['module']="";
$_SESSION['currentTab']=2;
$_SESSION['currentLayer']=0;
$_SESSION['pro']="";
$_SESSION['Purpose']="";
$_SESSION['Url']="";
$_SESSION['postParam']="";
$_SESSION['testResult']="";
class AddInterface extends MY_Controller {
	
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
        $this->show("Footprint",0,0);
    }
	public function exchangeLevel($module,$getNum=0,$postNum=0)
    {
	    $params=$_REQUEST;
		//var_dump($params);
		if( count($params)>3){
			$this->create($module,$params,$getNum,$postNum);
			$getNum=0;
			$postNum=0;
		}
		$this->show($module,$getNum,$postNum);	
    }
    
    private function show($module,$getNum,$postNum)
    {   
    	$this->platInfoArray = $this->config->item('plat_info_array');
    	$this->platInfoArray['curModule']=$module;
		$this->load->model('case_model');
    	$arrModule=$this->case_model->get_all_modules('tb_module','name');
        $this->cismarty->assign('platInfo', $this->platInfoArray); 
		$this->cismarty->assign('curModule', $module);
		$this->cismarty->assign('getNum', $getNum);
		$this->cismarty->assign('postNum', $postNum); 
		$title="添加接口";
		$this->cismarty->assign('title', $title);    
        $this->cismarty->display(APPPATH . '/views/module/addInterface.tpl');  
    }
    private  function getmanagetabURL(){
    	return base_url()."index.php/moduleFunc/exchangeLevel/".$this->uri->segment(3);
    } 
	public function create($module,$params,$getNum,$postNum)
    {
		//var_dump($_REQUEST);
		$interface=$params['interfaceName'];
		$getParams="";
		$getVals="";
		if($getNum>0){
			for($i=0;$i<$getNum;$i++){
				$key="get_".$i;
				$param=$params[$key];
				$getParams=$getParams.";".$param;
				$key="gval_".$i;
				$param=$params[$key];
				$getVals=$getVals.";".$param;
			}
			$getParams=substr($getParams,1);
			$getVals=substr($getVals,1);
		}
		$postParams="";
		$postVals="";
		if($postNum>0){
			for($i=0;$i<$postNum;$i++){
				$key="get_".$i;
				$param=$params[$key];
				$postParams=$postParams.";".$param;
				$key="pval_".$i;
				$param=$params[$key];
				$postVals=$postVals.";".$param;
			}
			$postParams=substr($postParams,1);
			$postVals=substr($postVals,1);
		}
		//echo $getParams;
		//echo $postParams;
		$this->load->model('case_model');
		$case=array();
		$case['name'] = $interface;
        $case['get'] =$getParams;
        $case['post'] =$postParams;
		$case['gval'] =$getVals;
        $case['pval'] =$postVals;
		$case['file'] =0;
		$case['path'] =$params['path'];
		//var_dump($case);
    	$res=$this->case_model->AddCase('tb_interface',$case);
		$case=array();
		$str=$this->case_model->findOne('tb_module','name',$module,'interface');
        $case['interface'] = $str.";".$interface;
		$case['name']=$module;
		echo $str."\n";
		$this->case_model->UpdateCaseOne('tb_module',$case,'name');
		//echo $interface."接口添加成功!";
		//echo $interface."接口添加:".$res;
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
