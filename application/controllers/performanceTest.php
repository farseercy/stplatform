<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
$_SESSION['currentTab']=3;
$_SESSION['currentLayer']=0;
$_SESSION['paramNum']=5;
$_SESSION['wordNum']=500000;
$_SESSION['offset']="0";

class PerformanceTest extends MY_Controller {
	
	var $platInfoArray;//平台信息
	var $case = array(
		'caseid' => '',             //id
		'proname' => '',        //case名称
		'user' => '',           //case类型
		'purpose' => '',   
		'file' => '',
		'ip' => '',         //case对应的产品线id
		'port' => '',         //case对应的产品线id
		'velocity' => '',        //case对应的产品线id
		'type' => '',
		'run' => '',
		'stime' => '',
	    'etime' => '',
		'period' => ''
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
	private function getFileList() {		   	    	               
	    $this->load->model('case_model');
	    $resArray=$this->case_model->get_all('tb_word');
        //var_dump($resArray);
        $temp="{\"data\":[";
        for($i=0;$i<count($resArray)-1;$i++){
			$temp=$temp."{\"file\":\"".$resArray[$i]->name."\"},";
        }
		$temp=$temp."{\"file\":\"".$resArray[$i]->name."\"}]}";
		return $temp;
    } 

	public function exchangeLevel($layerkey,$offset=0,$op="Emp")
    {
      	$_SESSION['selectlayer']=$layerkey;
      	$curOp=substr($op,0,2);
      	if($layerkey==1&&$op!="Emp"&&$curOp=="0_"){
      		$this->startOneCase(substr($op,2));
      	}
      	else if($layerkey==1&&$op!="Emp"&&$curOp=="1_"){
      		$this->stopOneCase(substr($op,2));
      	}
      	//else
    		$this->show($layerkey,$offset,$op);
    }
    
    private function show($layerkey,$offset=0,$op="Emp")
    {  
    	$this->platInfoArray = $this->config->item('plat_info_array');
    	$this->platInfoArray['currentTab'] = $_SESSION['currentTab'];
    	$this->platInfoArray['currentLayer'] = $layerkey;
    	$this->platInfoArray['wordListFile'] = $op;
    	//$this->platInfoArray['username']=phpCAS::getUser();
    	//echo $this->platInfoArray['currentLayer'];
        $this->cismarty->assign('platInfo', $this->platInfoArray);
        $svrarray = array();
		$res_data = $this->getFileList();
		//echo $res_data;
		$json_data = json_decode($res_data,true);
		//var_dump($json_data);
		$tmp_item = new stdClass();
		$tmp_item->title = "File";
		$tmp_item->id = "ID_File";
		$tmp_item->name = "ID_File";
		$values = array();
		foreach ($json_data['data'] as $item) {
			$values[] = $item['file'];
		}
		$tmp_item->values = $values;
		$svrarray[] = $tmp_item;
		//var_dump($svrarray);
    	$this->cismarty->assign('dataFiles', $svrarray);
    	if($this->platInfoArray['currentLayer']==0){
        	$this->cismarty->display(APPPATH . '/views/templates/performanceTest/statistic/stat.tpl');   
    	}
    	else if($this->platInfoArray['currentLayer']==1){
    		 $this->showlevelone($svrarray,$offset);   		 
    		 //$this->showWordList($svrarray,$offset);
    	}
    	else if($this->platInfoArray['currentLayer']==2){
    		$this->cismarty->assign('paramNum', $_SESSION['paramNum']);
    		$this->cismarty->assign('lineNum',$_SESSION['wordNum']);
    		$this->showWord($svrarray,$offset); 
    	}
    	else if($this->platInfoArray['currentLayer']==3){
    		  //$this->showResult($svrarray,$offset);
    		  //echo $this->uri->segment(3);
    		  $this->showWordList($svrarray,$offset);
    	}
        else if($this->platInfoArray['currentLayer']==4){
    		  $this->showWordList($svrarray,$offset);
    	}	
    }
    public function showResult($svrarray,$offset){
    	$this->cismarty->assign('dataFiles', $svrarray);
    	$this->cismarty->display(APPPATH . '/views/templates/performanceTest/statistic/show.tpl');
    }
	public function showWord($svrarray,$offset){
    	$this->cismarty->assign('dataFiles', $svrarray);
    	$this->cismarty->display(APPPATH . '/views/templates/performanceTest/statistic/word.tpl');
    }
    public function showlevelone($svrarray,$offset){
    		$this->cismarty->assign('dataFiles', $svrarray);   		
    		$this->load->database();
	    	$caseInfo = $this->config->item('case_list_info');
	    	$caseInfo['offset'] = $offset;
	    	$caseInfo['per_page'] = 5;  	    	    	               
	    	$this->load->model('oper_model');
	    	$caseInfo['total_rows'] = $this->oper_model->get_total_rows('tb_per_cases');
	    	$cur_url=$this->getmanagetabURL();
	    	$cases = $this->oper_model->get_cases('tb_per_cases',$caseInfo['per_page'],$offset);
	    	//var_dump($cases);
	    	$this->cismarty->assign('cases', $cases);
	    	$this->cismarty->assign('cur_url', $cur_url);  
	    	$this->cismarty->assign('caseInfo', $caseInfo); 
        	$this->cismarty->display(APPPATH . '/views/templates/performanceTest/statistic/res.tpl'); 
    }
    
    public function showWordList($svrarray,$offset){
    	$this->cismarty->assign('dataFiles', $svrarray);
    	$this->load->database();
	    $caseInfo = $this->config->item('case_list_info');
	    $caseInfo['offset'] = $offset;
	    $caseInfo['per_page'] = 10;  	    	    	               
	    $this->load->model('oper_model');
	    $caseInfo['total_rows']=$this->oper_model->get_total_rows('tb_word');
	    $cur_url=$this->getmanagetabURL();
	    $cases = $this->oper_model->get_cases('tb_word',$caseInfo['per_page'],$offset);
	    //var_dump($cases);
	    $this->cismarty->assign('cases', $cases);
	    $this->cismarty->assign('cur_url', $cur_url);  
	    $this->cismarty->assign('caseInfo', $caseInfo);
    	$this->cismarty->display(APPPATH . '/views/templates/performanceTest/statistic/wordlist.tpl');
    }
    
	private  function getmanagetabURL(){
    	return base_url()."index.php/performanceTest/exchangeLevel/".$this->uri->segment(3);//
    }
    ////////////创建任务///////////////////////////
    public function addConf() {
    	$time=date("ymdHi");//time();
    	$this->load->model('case_model');
        $case['caseid'] = $time;
        $case['proname'] =$this->input->post('ID_Pro');	
        //$case['user'] =phpCAS::getUser();
        $this->platInfoArray = $this->config->item('plat_info_array');
        $user=$this->platInfoArray['username'];
        $case['ip'] =$this->input->post('ID_HostName');
        $case['purpose'] =$this->input->post('ID_Url');
        $case['port'] =$this->input->post('ID_HostPort');
        $case['type'] =$this->input->post('ID_Type');
        $case['velocity'] =intval($this->input->post('ID_Vel'));
        $case['file'] =$this->input->post('ID_File');
        $case['run'] =intval(0);
        $case['period'] =intval($this->input->post('ID_Period'));
        $ckup=$this->input->post('ckup');
        $cksel=$this->input->post('cksel');
        if(substr($case['purpose'],0,1)!="/")
        {       $case['purpose']="/".$case['purpose'];
                        //echo $url;
        }
   		if(substr($case['purpose'],(strlen($case['purpose'])-1),1)=="?")
        {       $case['purpose']=substr($case['purpose'],0,(strlen($case['purpose'])-1));
                        //echo $url;    
        }     
        $HostName=$case['ip'];
        $HostPort=$case['port'];
		$url  =$case['purpose'];
		$file =$case['file'];    
		$type =$case['type'];
		//echo $ckup.",".$cksel;
        if($ckup=="on"){
			//echo $filePath;
        	exec("scp ".$shost.":".$filePath." ./uploadFiles/");
        }
        //echo $HostName.",".$HostPort.",".$file.",".$url.",".$type;
        exec("sh script/createABC.sh $HostName $HostPort $file $url $type > /dev/null");
        $this->case_model->AddCase('tb_per_cases',$case);
        $this->show(1,0);
    }
    private function matchValue($str1,$str2) {
    	
    }
    public function paramNumChange(){
    	//echo $this->input->post('pNum');
    	$_SESSION['paramNum']=$this->input->post('pNum');
    	$_SESSION['wordNum']=$this->input->post('lNum');
    	$this->show(2);
    }
    
    public function createWordList_params() {
        $fileName=$this->input->post('fileName');
		$lineNum=$this->input->post('lNum');
		$pNum=$this->input->post('pNum');
		$arr=array();
		$count=0;
		for($i=1;$i<=$pNum;$i++){
			$pName="pName".$i;
			$nameValue=$this->input->post($pName);
			if($nameValue!=""){
				$count++;
				$Scope="Scopes".$i;
				$scopeValue=$this->input->post($Scope);
				$SelType="SelType".$i;
				$typeValue=$this->input->post($SelType);
				if($typeValue=="随机整数"){
					$arr[$i]="int=&".$nameValue."=&".$scopeValue;
				}
				else if($typeValue=="固定值"){
					$arr[$i]="str=&".$nameValue."=&".$scopeValue;
				}
				else if($typeValue=="随机整数(md5)"){
					$arr[$i]="md5=&".$nameValue."=&".$scopeValue;
				}
			    else if($typeValue=="自定义范围"){
					$arr[$i]="sel=&".$nameValue."=&".$scopeValue;
				}
			}
		}
		$paramJson=json_encode($arr);
		$this->load->model('case_model');
		$oneArray=$this->case_model->GetCasesByKey('tb_word',"name",$fileName);
		if(count($oneArray)>0){
			$fileName=$fileName."_".date("His");;
		} 
		//echo "url=".base_url()."index.php/performanceTest/createList?pNum=".$paramJson."&lNum=".$lineNum."&fileName=".$fileName;
    	//exec("curl '".base_url()."index.php/performanceTest/createList?' -d  'pNum=".$paramJson."&lNum=".$lineNum."&fileName=".$fileName."' > /dev/null");
		exec("sh script/createNewList.sh '$paramJson' '$lineNum' '$fileName' > /dev/null");
    	
		$case['purpose'] ="test";
        $case['name'] =$fileName;
        $case['content'] =date("d/M/Y:H");
		$this->case_model->AddCase('tb_word',$case);
		$this->show(3);   
    }
    public function createWordList() {
    	$ckFile=$this->input->post('ckFile');
    	$ckParam=$this->input->post('ckParam');
    	echo $ckFile.",".$ckParam;
    	if($ckParam=="on"){
    		createWordList_params();
    	}
    	else{
    		$getM=$this->input->post('getFileM');
    		if($getM=="scp"){
    			$Host=$this->input->post('scpHost');
    			$Path=$this->input->post('scpPath');
    			//$filePath="scp -r map@".$Host.":".$Path;
    			exec("scp -r map@".$Host.":".$Path);
    			//exec("mv ./".$Path."");
    		}
    		else{
    			$Host=$this->input->post('wgetHost');
    			$Path=$this->input->post('wgetPath');
    			//$filePath="wget -r -nH -l 20 --limit-rate=8m ftp://".$Host.$Path;
    			exec("scp -r map@".$Host.":".$Path);
    		}
    		$fileT=$this->input->post('fileType');
    		if($fileT="lighttpd/nginx"){
    			$data=$this->input->post('lighttpdFormat');
    		}
    		else{
    			$data=$this->input->post('selfFile');
    		}
    		$this->createWordList_File($getM,$Host,$Path,$fileT,$data);
    	}
    	//echo "ckFile";
    }
    public function createWordList_File($getM,$Host,$Path,$fileT,$data){
    	if($getM=="scp"){
    		exec("scp -r map@".$Host.":".$Path);
    	}
    	
    	
    }
	public function createList() {
		//$fileName=$this->input->post('fileName');
		$lineNum=$this->input->post('lNum');
		$pNum=$this->input->post('pNum');
		/*
		$arr=json_decode($pNum);
		$count=count($arr);
		*/
		$arr=array();
 		$count=0;		
		for($i=1;$i<=$pNum;$i++){
			$pName="pName".$i;
			$nameValue=$this->input->post($pName);
			if($nameValue!=""){
				$count++;
				$Scope="Scopes".$i;
				$scopeValue=$this->input->post($Scope);
				$SelType="SelType".$i;
				$typeValue=$this->input->post($SelType);
				if($typeValue=="随机整数"){
					$arr[$i]="int=&".$nameValue."=&".$scopeValue;
				}
				else if($typeValue=="固定值"){
					$arr[$i]="str=&".$nameValue."=&".$scopeValue;
				}
				else if($typeValue=="随机整数(md5)"){
					$arr[$i]="md5=&".$nameValue."=&".$scopeValue;
				}
			    else if($typeValue=="自定义范围"){
					$arr[$i]="sel=&".$nameValue."=&".$scopeValue;
				}
				else if($typeValue=="从文件中读取"){
					$arr[$i]="file=&".$nameValue."=&".$scopeValue;
					//下载文件
				}
			}
		}
		//var_dump($arr);
		//$LineNum=10;
		$this->load->model('case_model');
		$oneArray=$this->case_model->GetCasesByKey('tb_word',"name",$fileName);
		if(count($oneArray)>0){
			$fileName=$fileName."_".date("His");;
		}
		$getpath="./uploadFiles/".$fileName;
	    @ $fp= fopen($getpath,'w');
		if(!$fp){//echo "<p>Your order could not be processed at this time.Please try again later.</p>";
 			exit;
		}
		for($i=0;$i<$lineNum;$i++){
			$str="";
			for($j=1;$j<=$count;$j++){
				$tempArr=explode("=&", $arr[$j]);
				$str=$str."&".$tempArr[1]."=".$this->getParamValue($tempArr[2],$tempArr[0]);
			}
			$str=substr($str,1)."\n";
			flock($fp,LOCK_EX);
			fwrite($fp,$str,strlen($str));
			flock($fp,LOCK_UN);		
		}
		fclose($fp);
        $case['purpose'] ="test";
        $case['name'] =$fileName;
        $case['content'] =date("d/M/Y:H");
		$this->case_model->AddCase('tb_word',$case);
        $this->show(3);
    }
    public function getParamValue($str,$type){
    	$tempArr=explode(",", $str);
    	$num=count($tempArr);
    	if($type=="str"||$type=="sel"){   
    		$cur=mt_rand(0,$num-1); 		
    		return $tempArr[$cur];
    	}
    	else if($type=="int"){
    		return mt_rand($tempArr[0],$tempArr[$num-1]);
    	}
    	else if($type=="md5"){
    		$cur=mt_rand($tempArr[0],$tempArr[$num-1]);
    		return md5($cur);
    	}
    	else if($type=="file"){
    		$cur=mt_rand($tempArr[0],$tempArr[$num-1]);
    		return md5($cur);
    	}
    }
	public function startOneCase($caseid) {
		$start=0;
		$this->load->model('case_model');
		//echo $caseid."\n";
        $case['caseid'] = $caseid;
        date_default_timezone_set('PRC');
        $case['stime']=date("d/M/Y:H:i:s");
        $case['etime']="";
        $case['run'] =1;
        //$resArray=array();
        //$oneArray=array();
        $oneArray=$this->case_model->GetCasesById('tb_per_cases',$caseid);
		$HostName=$oneArray[0]->ip;
        $HostPort=$oneArray[0]->port;
        $velocity=$oneArray[0]->velocity;
        $url=$oneArray[0]->purpose;
        $type=$oneArray[0]->type;
        $file=$oneArray[0]->file;
        $resArray=$this->case_model->get_all('tb_per_cases');
        //var_dump($resArray);
        for($i=0;$i<count($resArray);$i++){
        	if($resArray[$i]->run!=0){
        	    //echo $resArray[0][0]->ip
        	    $start=$start+$resArray[$i]->run;
        		//break;
        	}
        }	
        //echo $start."\n";
		if($start==0||$start==2){
			//exec("cp -r uploadFiles/".$file."_0 easyABC_1/data/".$file.".0");
		    $tempFile=$file.".";
			//exec("sh easyABC_1/addConf.sh $HostName $HostPort $velocity $caseid $tempFile $url $type > /dev/null");
			exec("sh easyABC_1/addConf.sh '$HostName' '$HostPort' '$velocity' '$tempFile' > /dev/null");	
			$this->case_model->UpdateCase('tb_per_cases',$case);	
		}
		else if($start==1){			
			$case['run'] =2;
			$tempFile=$file.".";
			//exec("cp -r uploadFiles/".$file."_0 easyABC_2/data/".$file.".0");
			exec("sh easyABC_2/addConf.sh '$HostName' '$HostPort' '$velocity' '$tempFile' > /dev/null");
			$this->case_model->UpdateCase('tb_per_cases',$case);		
		}
		$this->show(1);
    }
	public function stopOneCase($caseid) {
		$this->load->model('case_model');
        $case['caseid'] = $caseid;
        date_default_timezone_set('PRC');
        $case['etime']=date("d/M/Y:H:i:s");
        $case['run'] =0;
        //$path="@".dirname(__FILE__)."/easyABC/addConf.sh";
		$oneArray=$this->case_model->GetCasesById('tb_per_cases',$caseid);
		$runstatus=$oneArray[0]->run;
		if($runstatus==1){
    		$res = exec("sh easyABC_1/stopCase.sh ");
    		$this->case_model->UpdateCase('tb_per_cases',$case);
		}
	    if($runstatus==2){
    		$res = exec("sh easyABC_2/stopCase.sh ");
    		$this->case_model->UpdateCase('tb_per_cases',$case);
		}		
    }
    public function showCase() {
    	$this->load->database();
	    $caseInfo = $this->config->item('case_list_info');  
	    //获取要显示的用例的偏移量
	    $caseInfo['offset'] = $offset;	    	    	    	               
	    $this->load->model('oper_model');
	    //根据产品线获取相应用例的总数	   	    
   	    $caseInfo['total_rows'] = $this->oper_model->get_total_rows('tb_per_cases'); 
    	//获取用例信息
	    $cases = $this->oper_model->get_cases($caseInfo['per_page'],$offset);

	    //显示用例表
	    $this->cismarty->assign('cases', $cases);
	    $this->cismarty->assign('caseInfo', $caseInfo);
	    $cur_url=$this->getmanagetabURL();
	    $this->cismarty->assign('cur_url', $cur_url);
    	$this->cismarty->display(APPPATH . '/views/templates/performanceTest/statistic/casemanager.tpl'); 
    }
	public function showOneRes($caseid){
		$this->load->model('case_model');
        $oneArray=$this->case_model->GetCasesById('tb_per_cases',$caseid);
    	//var_dump($oneArray);
    	$m_case=array();
    	$m_case['ip']=$oneArray[0]->ip;
    	$m_case['port']=$oneArray[0]->port;
    	$m_case['url']=$oneArray[0]->purpose;
    	$m_case['stime']=$oneArray[0]->stime;
    	$m_case['etime']=$oneArray[0]->etime;
		$this->cismarty->assign('caseid', $caseid);
		$this->cismarty->assign('m_case', $m_case); 
		$this->show(3);
	}
	private function stats_summary($startTime='') {
    	if('' == $startTime)	    	
	    	$statis['startTime']=date('Y-m-d H:i:s', time()-86400);
	    else
	    	$statis['startTime']=$startTime;
    	$statis['endTime']=date('Y-m-d H:i:s', time()); 
    	$statisInfo = $this->config->item('report_statistic');   	    	    	
    	$this->load->model('report_model');
    	$statis['undeal'] = $this->report_model->get_rows_byStatus($_SESSION['productid'],$_SESSION['selectlayer'],$_SESSION['selectitem'],0,$statis['startTime'],$statis['endTime']); 
    	$statis['dealing'] = $this->report_model->get_rows_byStatus($_SESSION['productid'],$_SESSION['selectlayer'],$_SESSION['selectitem'],1,$statis['startTime'],$statis['endTime']);    	    	
    	$statis['dealed'] = $this->report_model->get_rows_byStatus($_SESSION['productid'],$_SESSION['selectlayer'],$_SESSION['selectitem'],2,$statis['startTime'],$statis['endTime']);    	
    	$statis['total']=$statis['dealed']+$statis['undeal']+$statis['dealing'];
    	//var_dump($statis);die();
    	$cur_url=$this->getAlertDetailURL();

	    $this->cismarty->assign('cur_url', $cur_url);
    	$this->cismarty->assign('statis', $statis);
	    $this->cismarty->assign('statisInfo', $statisInfo);	
	    $this->cismarty->assign('thirdparty_url', "");     	
    }
    public function getListCont($fileName){	
    	$line=0;
		$str="";
		exec("sh script/GetListCont.sh $fileName");
		$path="script/GetCont_".$fileName;
		if(file_exists($path)){
			$file = fopen($path, "r");
			while(!feof($file)){
               //echo fgets($file)."\n";
                $line++;
                 if($line==1)
                    $str=$str.fgets($file)."</p>";
                else
                    $str=$str.fgets($file);
            }//endwhile
            fclose($file);
			//$str="FILE: ".$fileName."</p>".$str;
			$str="[LineNum,FileName]    ".$str;
			exec("rm script/GetCont_$fileName");
			echo $str;
		}
		else
			echo "Empty!";
    }
}
