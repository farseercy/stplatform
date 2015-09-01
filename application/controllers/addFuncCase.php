<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
$_SESSION['module']="";
class AddFuncCase extends MY_Controller {
	
	var $platInfoArray;//平台信息
	
	var $case = array(
		'interface' => '',        //case名称
		'desc' => '',           //case类型
		'get' => '',   
		'post' => '',         //case对应的产品线id
		'rtype' => '',
		'return' => '',
		'operate' => '',
		'sqlres' => '',
		'result' => '',
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
        $this->show("Footprint",0);
    }
	public function exchangeLevel($module,$interface="",$op=0)
    {
		$params=$_REQUEST;
		//var_dump($params);
		$return="";
		if($op==1){
			$this->createCase($params,$module,$interface);
		}
		else if($op==2){
			$return=$this->testCase($params);
			
		}
    	$this->show($module,$interface,$return);
    }
    
    private function show($module,$interface="",$return="")
    {   
    	$this->platInfoArray = $this->config->item('plat_info_array');
    	$this->platInfoArray['curModule']=$module;
		$this->load->model('case_model');
    	$arrModule=$this->case_model->get_all_modules('tb_module','name');
		$Interfaces=$this->case_model->findOne('tb_module','name',$module,'interface');
		$arrInterface=explode(";",$Interfaces);
		if($interface=="")
			$interface=$arrInterface[0];
		$gets=$this->case_model->findOne('tb_interface','name',$interface,'get');
		$gparams=array();
		$pparams=array();
		if($gets!=""){
			$getParams=explode(";",$gets);
			$gets=$this->case_model->findOne('tb_interface','name',$interface,'gval');
			$getVals=explode(";",$gets);
			for($i=0;$i<count($getParams);$i++){
				$gparams[$i]['key']=$getParams[$i];
				$gparams[$i]['val']=$getVals[$i];
			}
		}
		$posts=$this->case_model->findOne('tb_interface','name',$interface,'post');
		if($posts!=""){
			$postParams=explode(";",$posts);
			$posts=$this->case_model->findOne('tb_interface','name',$interface,'pval');
			$postVals=explode(";",$posts);	
			for($i=0;$i<count($postParams);$i++){
				$pparams[$i]['key']=$postParams[$i];
				$pparams[$i]['val']=$postVals[$i];
			}
		}
		$path=$this->case_model->findOne('tb_interface','name',$interface,'path');
        $this->cismarty->assign('platInfo', $this->platInfoArray); 
		$this->cismarty->assign('curModule', $module); 
		$this->cismarty->assign('curInterface', $interface);
		$this->cismarty->assign('arrModule', $arrModule); 
		$this->cismarty->assign('arrInterface', $arrInterface);
		$this->cismarty->assign('arrGet', $gparams);  
		$this->cismarty->assign('arrPost', $pparams); 
		$this->cismarty->assign('path', $path);
		$this->cismarty->assign('return', $return);         
        $this->cismarty->display(APPPATH . '/views/module/addCase.tpl');   
    }
    private  function getmanagetabURL(){
    	return base_url()."index.php/moduleFunc/exchangeLevel/".$this->uri->segment(3);
    } 
	public function createCase($params,$module,$interface) {
		var_dump($params);
		//$params=$_REQUEST;
		////////get params/////////////
		$getParams="";
		if( $params['gNum']!=0 ){
			for($i=0;$i<$params['gNum'];$i++){
				$tkey="gkey_".$i;
				$tval="gval_".$i;
				$getParams=$getParams."&".$params[$tkey]."=".$params[$tval];
			}
			$getParams=substr($getParams,1);
		}
		$postParams="";
		if( $params['pNum']!=null && $params['pNum']!=0 ){
			for($i=0;$i<$params['pNum'];$i++){
				$tkey="pkey_".$i;
				$tval="pval_".$i;
				$postParams=$postParams."&".$params[$tkey]."=".$params[$tval];
			}
			$postParams=substr($postParams,1);
		}
		////////return/////////////////
		if($params['r_json']=="on"){$case['rtype'] = "json";}
		else if($params['r_pb']=="on"){$case['rtype'] = "pb";}
		else if($params['r_mcpack']=="on"){$case['rtype'] = "mcpack";}
		//////////////////////////////
		$this->load->model('case_model');
        $case['interface'] = $interface;
        $case['desc'] =$params['desc'];
        $case['get'] =$getParams;
        $case['post'] =$postParams;
        $case['file'] ="";
        $case['return'] =$params['testArea'];
		$case['operate']=$params['operate'];
		$case['result']="";
		$case['sqlres']="";
		$case['module']=$module;
		$case['level']=$params['level'];
		var_dump($case);
        $this->case_model->AddCase('tb_func',$case);
		
	}
	public function testCase($params) {
		$getParams="";
		if( $params['gNum']!=0 ){
			for($i=0;$i<$params['gNum'];$i++){
				$tkey="gkey_".$i;
				$tval="gval_".$i;
				$getParams=$getParams."&".$params[$tkey]."=".$params[$tval];
			}
			$getParams=substr($getParams,1);
		}
		$postParams="";
		if( $params['pNum']!=null && $params['pNum']!=0 ){
			for($i=0;$i<$params['pNum'];$i++){
				$tkey="pkey_".$i;
				$tval="pval_".$i;
				$postParams=$postParams."&".$params[$tkey]."=".$params[$tval];
			}
			$postParams=substr($postParams,1);
		}
		$url=$params['ip_port'].$params['path'];
		$data=$this->http_send($url.$getParams);
		return $data;
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
