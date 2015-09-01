<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();

class GetCaseInfo extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		//var_dump(base_url());die();
		$this->cismarty->assign("baseurl", base_url());
	}
	
    public function index()
    {       
    	$caseid = $_GET['caseid'];
    	$table = $_GET['table'];
    	$this->load->model('case_model');
    	$case=$this->case_model->GetCasesById($table,$caseid);
    	echo json_encode($case[0]);
    }
    
 	public function getCtypeArray()
    {       
    	$ctype = $this->config->item('case_type_info');
    	echo json_encode($ctype);
    }
    
	public function saveperCase()
    {       
    	//获取POST参数
    	$rev=json_decode($_POST['data']);
    	//var_dump($rev);
    	$caseid=$rev->caseid;
    	$table=$rev->table;
		$case['proname'] = $rev->proname;
		$case['type'] = $rev->type;
		$case['port'] = $rev->port;
		$case['ip'] = $rev->ip;
		$case['velocity'] = intval($rev->velocity);
		$case['purpose'] = $rev->purpose;
		$case['file'] = $rev->file;	
		$case['stime'] = $rev->stime;	
		$case['etime'] = $rev->etime;			
    	$this->load->model('case_model');    	
    	
    	//返回caseid，执行更新操作
    	if($caseid!=0){
    		$case['caseid'] = $caseid;
    		$res=$this->case_model->UpdateCase($table,$case);
    	}
    	//否则，执行新增操作
    	else {
    		$case['num'] = '11';
			$case['createtime'] = date('Y-m-d H:i:s', time());
			$case['updatetime'] = date('Y-m-d H:i:s', time());
    		$this->case_model->AddCase($table,$case);
    		$res = true;
    	}
    	//获取callback参数
    	$callback = $_GET['callback'];
    	echo $callback.'('.json_encode($res).')';
    }
public function savefunCase()
    {       
    	//获取POST参数
    	$rev=json_decode($_POST['data']);
    	//var_dump($rev);
    	$caseid=$rev->caseid;
    	$table=$rev->table;
		$case['proname'] = $rev->proname;
		//$case['file'] = $rev->file;
		//$case['user'] = $this->platInfoArray['username'];
		$case['get'] = $rev->get;	
		$case['post'] = $rev->post;	
		$case['purpose'] = $rev->purpose;
		$case['test'] = $rev->test;
		$case['mysql'] = $rev->mysql;
		$case['redis'] = $rev->redis;				
    	$this->load->model('case_model');    	
    	
    	//返回caseid，执行更新操作
    	if($caseid!=0){
    		$case['caseid'] = $caseid;
    		$res=$this->case_model->UpdateCase($table,$case);
    	}
    	//否则，执行新增操作
    	else {
    		$case['num'] = '11';
			$case['createtime'] = date('Y-m-d H:i:s', time());
			$case['updatetime'] = date('Y-m-d H:i:s', time());
    		$this->case_model->AddCase($table,$case);
    		$res = true;
    	}
    	//获取callback参数
    	$callback = $_GET['callback'];
    	echo $callback.'('.json_encode($res).')';
    }
	public function getLigLog(){       
     	//获取POST参数
    	$rev=json_decode($_POST['data']);
    	//var_dump($rev);
    	$path="home";
    	$caseid=$rev->caseid;
    	$table=$rev->table;
		$path=$rev->path;
		$stime=$rev->stime;
    	$etime=$rev->etime;
		//echo $stime."\n".$etime."\n";	
    	$this->load->model('case_model');    		
    	//返回caseid，执行更新操作
    	if($caseid!=""){
    		$case=$this->case_model->GetCasesById($table,$caseid);
    	}
    	//var_dump($case);
   		$ch = curl_init();
		$postParam="";
    	$HostIp  =$case[0]->ip;
    	$url     =$case[0]->purpose;
    	//date_default_timezone_set('PRC');
    	//$ctime=date("d/M/Y:H:i:s");
		$m_url="http://".$HostIp.":8202/getLighttpd.php?stime=".urlencode($stime)."&etime=".urlencode($etime)."&url=".urlencode($url)."&path=".urlencode($path);
		//$m_url="http://10.99.33.40:8202/getLighttpd.php?stime=".urlencode($stime)."&etime=".urlencode($time)."&url=".urlencode($url)."&path=".urlencode($path);
		curl_setopt($ch, CURLOPT_URL, $m_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParam);
		$output = curl_exec($ch);
		curl_close($ch);
		//echo $output."\n";
		$sum=0;
		$rRes=array();
		if(strpos($output,"-")>0){
			$res=explode("-",$output);
			//var_dump($res);
			$pos=strpos($res[0],"[");
			$rRes[0]=substr($res[0],$pos);//time
			$rRes[1]=$res[1];//max
			$rRes[2]=$res[2];//min
			$rRes[3]=$res[3];//sum
			//$rRes[3]=$res[3];//err
			//$rRes[4]=substr($res[0],0,$pos);//sum
		}
    	//获取callback参数
    	//var_dump($rRes);
    	$callback = $_GET['callback'];
    	echo $callback.'('.json_encode($rRes).')';
    }
    public function testCase()
    {  
    	$rev=json_decode($_POST['data']);
    	$proname=$rev->proname;
    	$ip_port=$rev->ip_port;
    	$get=$rev->get;
    	if(substr($get,0,1)=="/")
			$geturl="http://".$ip_port.$get;
		else
			$geturl="http://".$ip_port."/".$get;
		$post=$rev->post;
	  	if(substr($post,(strlen($post)-1),1)== "&"){
	  		$post=substr($post,0,(strlen($post)-1));
	  	}
		if(substr($post,0,1)== "&"){
	  		$post=substr($post,1);
	  	}
	  	
		//$PostJson=$this->rewriteParam($post);
		//echo $PostJson;
		//$postParam=json_decode($PostJson,true);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $geturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "");
		$output = curl_exec($ch);
		curl_close($ch);
		//echo $output;
		$callback = $_GET['callback'];
    	echo $callback.'('.json_encode($output).')';
    }
	public function rewriteParam($str){
		$res="{";
		$resArray0=explode("&",$str);
		//var_dump($resArray0);		
		for($i=0;$i<count($resArray0)-1;$i++){
			$resArray1=explode("=",$resArray0[$i]);
			$res=$res."\\\"".$resArray1[0]."\\\":\\\"".$resArray1[1]."\\\",";
		}
		$resArray1=explode("=",$resArray0[$i]);
		//$res=$res."\\\"".$resArray1[0]."\\\":\\\"@\".dirname(__FILE__).\"/".$resArray1[1]."\\\"}";
		$res=$res."\"".$resArray1[0]."\":\"".$resArray1[1]."\"}";
		return $res;
	}
}
