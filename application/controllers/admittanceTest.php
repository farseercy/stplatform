<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$_SESSION['currentTab']=1;
$_SESSION['currentLayer']=0;
$_SESSION['pro']="";
$_SESSION['Purpose']="";
$_SESSION['Url']="";
$_SESSION['postParam']="";
$_SESSION['testResult']="";
$_SESSION['mysqlResult']="";
$_SESSION['redisResult']="";
//session_start();


class AdmittanceTest extends MY_Controller {
	
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
        $this->show(0);
    }
	public function exchangeLevel($layerkey,$offset=0,$pro="all",$op="Emp")
    {
      	$_SESSION['selectlayer']=$layerkey;
    	$curOp=substr($op,0,2);
    	$_SESSION['pro']=$pro;
      	if($layerkey==1&&$op!="Emp"){
      	    if($curOp=="0_"){
      			$this->startOneCase(substr($op,2));
      	    }
      	}
    	$this->show($layerkey,$offset);
    }
    
    private function show($layerkey,$offset=0)
    {  
    	$this->platInfoArray = $this->config->item('plat_info_array');
    	$this->platInfoArray['currentTab'] = $_SESSION['currentTab'];
    	$this->platInfoArray['currentLayer'] = $layerkey;
    	$this->platInfoArray['postParam'] =$_SESSION['postParam'];
    	$this->platInfoArray['testResult'] =$_SESSION['testResult'];
    	$this->platInfoArray['mysqlResult'] =$_SESSION['mysqlResult'];
    	$this->platInfoArray['redisResult'] =$_SESSION['redisResult'];
    	$this->platInfoArray['Url'] =$_SESSION['Url'];
    	$this->platInfoArray['curPro']=$_SESSION['pro'];
    	//$this->platInfoArray['username']=phpCAS::getUser();
    	$curPro=$_SESSION['pro'];
    	//echo $curPro;
        $this->cismarty->assign('platInfo', $this->platInfoArray);      
    	if($this->platInfoArray['currentLayer']==0){
    		//echo "1";
        	$this->cismarty->display(APPPATH . '/views/templates/admittanceTest/statistic/stat.tpl');   
    	}
    	else if($this->platInfoArray['currentLayer']==1){
    		/*$this->load->model('case_model');
    		$sqlArray=array();
    		$sqlArray[]=$this->case_model->get_all('tb_cases');
    		$this->cismarty->assign('sqlres', $sqlArray);
    		//echo $this->case_model->GetCasesById("1409559972");)*/
    		$this->load->database();
	    	$caseInfo = $this->config->item('case_list_info');
	    	$caseInfo['offset'] = $offset;	
	    	$caseInfo['per_page'] = 10;		    	    	    	               
	    	$this->load->model('oper_model');
	    	if($_SESSION['pro']=="all"){
	    	   $caseInfo['total_rows'] = $this->oper_model->get_total_rows('tb_cases');
	    	   $cases = $this->oper_model->get_cases('tb_cases',$caseInfo['per_page'],$offset);
	    	}
	    	else{
	    	   $caseInfo['total_rows'] = $this->oper_model->get_part_rows('tb_cases','proname',$curPro);
	    	   $cases = $this->oper_model->get_part_cases('tb_cases','proname',$curPro,$caseInfo['per_page'],$offset);
	    	}
	    	$cur_url=base_url()."index.php/admittanceTest/exchangeLevel/".$this->uri->segment(3);//."/".$this->uri->segment(4);	    	
	    	$this->cismarty->assign('cases', $cases);
	    	$this->cismarty->assign('cur_url', $cur_url);  
	    	$this->cismarty->assign('caseInfo', $caseInfo); 
        	$this->cismarty->display(APPPATH . '/views/templates/admittanceTest/statistic/res.tpl');        	
    	}
    		
    }
    private  function getmanagetabURL(){
    	return base_url()."index.php/admittanceTest/exchangeLevel/".$this->uri->segment(3);
    } 
	public function addCase() {
    	$proname=$this->input->post('ID_Pro');
		$geturl=$this->input->post('ID_Url');
		$post=$this->input->post('ID_Post');		
		$test=$this->input->post('ID_Test');
		$this->platInfoArray = $this->config->item('plat_info_array');
    	$user=$this->platInfoArray['username'];
		$purpose=$this->input->post('ID_Purpose');
		$time=date("ymdHi");//time();
	    if(substr($post,(strlen($post)-1),1)== "&"){
	  		$post=substr($post,0,(strlen($post)-1));
	  	}
		$ckUp=$this->input->post('ckUpload');
        //$ckJson=$this->input->post('ckJson');
        $ckSql=$this->input->post('ckSql');
        $ckRedis=$this->input->post('ckRedis');
        $mysqlStr="";
		if($ckSql=="on"){
			$Sql_case['ip']=trim($this->input->post('sql_ip'));
			$Sql_case['user']=trim($this->input->post('sql_user'));
			$Sql_case['pass']=trim($this->input->post('sql_pass'));		
			$Sql_case['db']=trim($this->input->post('sql_db'));
			$Sql_case['tb']=trim($this->input->post('sql_tb'));
			$Sql_case['scolumn']=trim($this->input->post('sql_col'));
			$Sql_case['svalue']=trim($this->input->post('sql_value'));
			$Sql_case['tcolumn']=trim($this->input->post('sqlc_col'));
			$Sql_case['tvalue']=trim($this->input->post('sqlc_value'));
			$mysqlStr=json_encode($Sql_case);
			//$case[$QueStr="select * from ".$sql_tb." where ".$column."=".$value;
			//assert_mysql($sql_ip,$User,$Password,$db,$Table,$Key)
			//echo $_SESSION['mysqlResult'];
		}
		$redisStr="";
	    if($ckRedis=="on"){
        	$Red_case['ip']=trim($this->input->post('rd_ip'));
			$Red_case['key']=trim($this->input->post('rd_key'));
			$Red_case['value']=trim($this->input->post('rd_value'));
			$redisStr=json_encode($Red_case);
        }
	    if($ckUp=="on"){
			$fileparam=$this->input->post('FileParam');	
			$file="";
			if(!empty($_FILES['file']['name'])){
				//$path="./casemng/case/".$proname."/".$_FILES['file']['name'];
				//$path="./uploadFiles/".$proname;
				//@mkdir($path);
				$path="./uploadFiles/".$proname."_".$_FILES['file']['name'];
				$file=$_FILES['file']['name'];
	        	$fileinfo=$_FILES['file'];
		        //var_dump($fileinfo);
    		    if($fileinfo['size']>0){
            	    move_uploaded_file($fileinfo['tmp_name'],$path);
        		}
		  	}
	    	if($fileparam!=""){
	  			if(substr($fileparam,(strlen($fileparam)-1),1)!= "="){
	  				$fileparam=$fileparam."=";
		  		}
		  		$post=$post."&".$fileparam.$proname."_".$file;
	  		}
			if(substr($post,0,1)== "&"){
	  			$post=substr($post,1);
		  	}
		  	if($file!=""){
           		$case['file'] =$proname."_".$file;
        	}
        	else
           		$case['file'] ="";            	
        }

		$this->load->model('case_model');
        $case['caseid'] = $time;
        $case['proname'] =$proname;
        $case['user'] =$user;
        $case['purpose'] =$purpose;
        $case['get'] =$geturl;
        $case['post'] =$post;
        $case['test'] =$test;
        $case['mysql'] =$mysqlStr;
        $case['redis'] =$redisStr;
        //var_dump($case);
        $this->case_model->AddCase('tb_cases',$case);    
        //$this->show(1);
        $this->exchangeLevel(1,0);
		
	}
	public function getPhpCont($url,$post,$time,$test,$user,$purpose,$file){
		
		$test=str_replace("\"","\\\"",$test);
		$purpose=str_replace("\"","\\\"",$purpose);
		//echo $test;		
		$content="<?php \n##user is ".$user."\n";
		$content=$content."require_once dirname(__FILE__) . '/../../common/basetest.php';\n";
		$content=$content."class Case_".$time." extends BaseTest {\n";
		$content=$content."protected function get_id(){\n";
		$content=$content."return \"".$time."\";\n";
		$content=$content."}\n";
		$content=$content."protected function get_title(){\n";
		$content=$content."return \"".$purpose."_校验\";\n";
		$content=$content."}\n";
		$content=$content."protected function execute(){\n";
		if($file!=""){
			$PostJson=$this->rewriteParam($post,1);
			//$PostJson=str_replace("\"","\\\"",$PostJson);
			$content=$content."\$post=\"".$PostJson."\";\n";
			$content=$content."\$postParam=json_decode(\$post,true);\n";
			$content=$content."\$data=http_send(\"".$url."\",\$postParam);\n";
		}
		else{
			$content=$content."\$data=http_send(\"".$url."\",\"".$post."\");\n";
		}
		$content=$content."\$res=\"".$test."\";\n";
		$content=$content."\$this->assert_json(__LINE__,\$data,\$res);\n";
		$content=$content."}\n";
		$content=$content."}\n";
		return $content;
	}
	public function rewriteParam($str,$hasFile){
		$res="{";
		$resArray0=explode("&",$str);
		//var_dump($resArray0);		
		for($i=0;$i<count($resArray0)-1;$i++){
			$resArray1=explode("=",$resArray0[$i]);
			//$res=$res."\\\"".$resArray1[0]."\\\":\\\"".$resArray1[1]."\\\",";
			//$res=$res."\"".$resArray1[0]."\":\"".$resArray1[1]."\",";
			$res=$res."\"".$resArray1[0]."\":\"".urlencode($resArray1[1])."\",";
		}
		$resArray1=explode("=",$resArray0[$i]);
		if($hasFile!=""){
			//$res=$res."\\\"".$resArray1[0]."\\\":\\\"@\".dirname(__FILE__).\"/".$resArray1[1]."\\\"}";
			//$res=$res."\"".$resArray1[0]."\":\"\\\"@\\\".dirname(__FILE__).\\\"/uploadFiles/".$resArray1[1]."\\\"\"}";
			$res=$res."\"".$resArray1[0]."\":\"@D:/wampServer/www/servertest/uploadFiles/".$resArray1[1]."\"}";
		}
		else{
		    $res=$res."\"".$resArray1[0]."\":\"".urlencode($resArray1[1])."\"}";
		}
		//echo $res;
		return $res;
	}
    public function startOneCase($caseid) {
    	$flage=1;
    	$this->load->model('case_model');
        $oneArray=$this->case_model->GetCasesById('tb_cases',$caseid);
        $url=$oneArray[0]->get;
        $post=$oneArray[0]->post;
        $file=$oneArray[0]->file;
        $test=$oneArray[0]->test;
        $mysql=$oneArray[0]->mysql;
        $redis=$oneArray[0]->redis;
        //echo $file;
        $case['caseid'] = $caseid;
        $case['result'] = "";
        if($test!=""){
        	if($post!=""){
				$PostJson=$this->rewriteParam($post,$file);
				//echo $PostJson;
				$postParam=json_decode($PostJson,true);
        	}
        	else{
        		$postParam="";
        	}
			//var_dump($postParam);
        	$res = http_post($url, $postParam);
       	 	//echo $res."\n";
    		//$time=date('Y')."-".date('m')."-".date('d')." ".date('h').":".date('i').":".date('s');
    		if($res==$test)
    			//$case['result'] ="[".date("ymdHi")."] passed";
    			$flage=$flage*1;
    		else{
    			$resArr=json_decode($res);
    			$testArr=json_decode($test);
    			$result = array();
  		  		if(!empty($resArr)){
    				$ret = $this->json_obj_expect($resArr, $testArr, $result);
    				if ($ret == true) {
    					//$case['result'] ="[".date("ymdHi")."] passed";
    					$flage=$flage*1;
    				}
    				else{
    					foreach ($result as $key =>$value) {   		
    					//echo $key.",".$value;
	        			//$case['result'] =$case['result'].$value;
	        				$flage=$flage*0;
    					}
    				}
    			}
    			//else{$case['result'] ="failed";}
    			$flage=$flage*0;
    		}
        }//end JsonTest
       if($mysql!=""){
        	$mysqlArr=json_decode($mysql,true);
        	$QueStr="select * from ".$mysqlArr['tb']." where ".$mysqlArr['scolumn']."=".$mysqlArr['svalue'];
        	$mysqlRes=$this->op_mysql($mysqlArr['ip'],$mysqlArr['user'],$mysqlArr['pass'],$mysqlArr['db'],$QueStr);
        	$mArr=json_decode($mysqlRes,true);
        	//var_dump($mArr);
        	if($mArr[$mysqlArr['tcolumn']]==$mysqlArr['tvalue']){
        		$flage=$flage*1;
        		//echo $mysqlRes."\n";
        		//echo $flage;
        	}/**/
        	else{$flage=$flage*0;}
        	$_SESSION['mysqlResult']=$mysqlRes;
        }
         if($redis!=""){
        	$redisArr=json_decode($redis,true);
        	$redisRes=$this->op_redis($redisArr['ip'],$redisArr['key'],"str");
        	$rArr=json_decode($redisRes);
        	if($rArr[$redisArr['key']]==$redisArr['value']){
        		$flage=$flage*1;
        		//echo $mysqlRes."\n";
        		//echo $flage;
        	}/**/
            else{$flage=$flage*0;}
        	$_SESSION['redisResult']=$redisRes;
        }/**/
        if($flage==1){$case['result'] ="[".date("ymdHi")."] passed";}
        else{$case['result'] ="failed";}
    	$this->case_model->UpdateCase('tb_cases',$case);
    	//$this->show(1,0);
    }
	public function json_obj_expect($actual, $expect, &$result)
	{
    	$actual_map = array();
	    $expect_map = array();
    	$this->obj_to_map($actual, "", $actual_map);
	    $this->obj_to_map($expect, "", $expect_map);
	    //var_dump($actual_map);
    	foreach ($expect_map as $key =>$value) {
    		//echo "$key".",".$value."</p>";
        	if (array_key_exists($key, $actual_map)) {
            	$actual_value = $actual_map[$key];
            	if ($value != $actual_value) {
                	$result[] = "[$key] actual:$actual_value expect: $value";
	            }
    	    } else {
        	    $result[] = "[$key] not exists in actual.";
        	}
    	}
    	if(count($result))
        	return false;
	    else
    	    return true;
	}
	function obj_to_map($obj, $attr, &$map) {
    	$format_attr = "";
	    if (empty($obj)) {
    	    return;
    	}
    	if (empty($attr)) {
        	$format_attr = $attr . "%s";
	    } else if (gettype($obj) == 'array') {
    	    $format_attr = $attr . "[%s]";
    	} else if (gettype($obj) == 'object') {
        	$format_attr = $attr . "->%s";
        	//echo $format_attr."//////</p>";
    	}
    	foreach ($obj as $key =>$value) {
        	$curr_attr = sprintf($format_attr, $key);
        	//echo $key.",".$value."</p>";
        	if ((gettype($value) == 'array') || (gettype($value) == 'object')) {
            	$this->obj_to_map($value, $curr_attr, $map);
        	} else {
            	$map[$curr_attr] = $value;
            	//echo "var_dump_map";
            	//var_dump($map);
            	
	        }
    	}
	}

	public function addFile(){
		if(!empty($_FILES['file']['name'])){
			$path="./casemng/case/".$_FILES['file']['name'];
			//$path=$_FILES['file']['name'];
			//echo $path;
        	$fileinfo=$_FILES['file'];
	        //var_dump($fileinfo);
    	    if($fileinfo['size']>0){
                move_uploaded_file($fileinfo['tmp_name'],$path);
        	//        echo "upload OK";
        	}
        //	else{
        	//        echo "some unkown error!";
        	//}
	  	}
      	//else{
 		//	echo "empty!";
	  	//}
	}
	public function changePro(){
		$_SESSION['pro']=trim($this->input->get('f_pro'));
		$this->show(1,0);
	}
	public function testCase() {
		$proname=$this->input->post('ID_Pro');
		$geturl=$this->input->post('ID_Url');
		$post=$this->input->post('ID_Post');
	    $ckUp=$this->input->post('ckUpload');
        $ckJson=$this->input->post('ckJson');
        $ckSql=$this->input->post('ckSql');
        $ckRedis=$this->input->post('ckRedis');
        //echo $ckSql."\n";
        $output="";
        if($ckSql=="on"){
        	$sql_ip=trim($this->input->post('sql_ip'));
			$sql_user=trim($this->input->post('sql_user'));
			$sql_pass=trim($this->input->post('sql_pass'));		
			$sql_db=trim($this->input->post('sql_db'));
			$sql_tb=trim($this->input->post('sql_tb'));
			$column=trim($this->input->post('sql_col'));
			$value=trim($this->input->post('sql_value'));
			$QueStr="select * from ".$sql_tb." where ".$column."=".$value." limit 10";
			$_SESSION['mysqlResult']=$this->op_mysql($sql_ip,$sql_user,$sql_pass,$sql_db,$QueStr);
			//echo $_SESSION['mysqlResult'];
        }
        if($ckRedis=="on"){
        	$rd_ip=trim($this->input->post('rd_ip'));
			$rd_key=trim($this->input->post('rd_key'));
        	$_SESSION['redisResult']=$this->op_redis($rd_ip, $rd_key, "str");
        }
        if($ckJson=="on"){
        	$fileparam=$this->input->post('FileParam');
			$file="";
			//echo $geturl.",".$post;
			if(!empty($_FILES['file']['name'])){
				//$path="./casemng/case/".$proname."/".$_FILES['file']['name'];
				$path="./uploadFiles/".$proname."_".$_FILES['file']['name'];
				$file=$proname."_".$_FILES['file']['name'];//$_FILES['file']['name'];
        		$fileinfo=$_FILES['file'];
	        	//var_dump($fileinfo);
    	    	if($fileinfo['size']>0){
                	move_uploaded_file($fileinfo['tmp_name'],$path);
        		}
	  		}
	  		if(substr($post,(strlen($post)-1),1)== "&"){
	  			$post=substr($post,0,(strlen($post)-1));
	  		}
	  		if($fileparam!=""){
	  			if(substr($fileparam,(strlen($fileparam)-1),1)!= "="){
	  				$fileparam=$fileparam."=";
	  			}
	  			$post=$post."&".$fileparam.$file;
	  		}
			if(substr($post,0,1)== "&"){
	  			$post=substr($post,1);
	  		}
	  		if($post!=""){
	  			$PostJson=$this->rewriteParam($post,$file);
	  			$postParam=json_decode($PostJson,true);
	  		}
	  		else {
	  			$postParam="";
	  		}
			//var_dump($postParam);
			$output = http_post($geturl, $postParam);
        }
		//echo $output;
		$_SESSION['testResult']=$output;
		$_SESSION['Url']=$geturl;
		$_SESSION['postParam']=$post;
		$this->show(0);
		//$callback = $_GET['callback'];
    	//echo $callback.'('.json_encode($output).')';
	
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
	public function assert_mysql($IP_Port,$User,$Password,$db,$Table,$Key){
		$con = mysql_connect($IP_Port, $User, $Password);
		if (!$con) {
       		echo('Could not connect: ' . mysql_error());
		}
		mysql_select_db($db, $con);
		$result=mysql_query("select * from ".$table."  where  ".$key."=".$value);
		if($row = mysql_fetch_array($result))
		{
     		mysql_close($con);
     		return true;
		}
		mysql_close($con);
		return false;	
	}
	public function op_mysql($IP_Port,$User,$Password,$db,$QueStr){
		$con = mysql_connect($IP_Port, $User, $Password);
		if (!$con) {
       		return('Could not connect: ' . mysql_error());
		}
		else{
			mysql_select_db($db, $con);
			$result=mysql_query($QueStr);
			//echo $QueStr;
			//var_dump($result);
			$res="";
			$row = mysql_fetch_object($result);
			do{
      			$i=0;
				foreach ($row as $key =>$value) {
					//$i=$i+1;
        		    //$curr_attr = sprintf($format_attr, $key);
        		    //echo $key.",".$value."\n";
            		//if($i%2==1){
            			$map[$key] = $value;
            		//}            	
	        	}
	        	$map[$key] = $value;
	        	$res=$res.json_encode($map)."\n";
			}while($row = mysql_fetch_array($result));
			//mysql_close($con);
			return $res;	
		}
	}
    public function assert_redis($IP_Port,$Key, $value){
		$redis = new Redis();
	    $redis->connect($IP_Port);
		$res=$redis->get($key);
		if($res==$value){
		    $redis->close();
			return true;
		}
		$redis->close();
		return false;	
	}
    public function op_redis($IP_Port,$key, $type){
		$redis = new Redis();
	    $redis->connect($IP_Port);
	    if($type=="str"){
			$res=$redis->get($key);
	    }
		$redis->close();
		return "{\"".$key."\":\"".$res."\"}";
	}	
}
