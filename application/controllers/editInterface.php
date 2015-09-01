<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
//$_SESSION['module']="";
class EditInterface extends MY_Controller {
	
	var $platInfoArray;//平台信息
	
	var $case = array(
		'name' => '',        
		'get' => '',           
		'post' => '',   
		'file' => '',         
		'path' => '',
		'gval' => '',
		'pval' => '',
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
        $this->show("Footprint","mainlist");
    }
	public function exchangeLevel($module,$interface)
    {
	    $params=$_REQUEST;
		var_dump($params);
		if( count($params)>3){
	    	$this->create($params,$interface);
		}
		$this->show($module,$interface);	
    }
    
    private function show($module,$interface)
    {   
    	$this->platInfoArray = $this->config->item('plat_info_array');
		$this->load->model('case_model');
		$gparams=array();
		$pparams=array();
		$gets=$this->case_model->get_getParams('tb_interface',$interface);
		if($gets!=""){
			$getParams=explode(";",$gets);
			$gets=$this->case_model->get_getParams_value('tb_interface',$interface);
			$getVals=explode(";",$gets);
			for($i=0;$i<count($getParams);$i++){
				$gparams[$i]['key']=$getParams[$i];
				$gparams[$i]['val']=$getVals[$i];
			}
		}
		$posts=$this->case_model->get_postParams('tb_interface',$interface);
		if($posts!=""){
			$postParams=explode(";",$posts);
			$posts=$this->case_model->get_postParams_value('tb_interface',$interface);
			$postVals=explode(";",$posts);	
			for($i=0;$i<count($postParams);$i++){
				$pparams[$i]['key']=$postParams[$i];
				$pparams[$i]['val']=$postVals[$i];
			}
		}
		$this->cismarty->assign('arrGet', $gparams);  
		$this->cismarty->assign('arrPost', $pparams); 
		$this->cismarty->assign('getNum', count($gparams));
		$this->cismarty->assign('postNum', count($pparams)); 
        $this->cismarty->assign('platInfo', $this->platInfoArray); 
		$this->cismarty->assign('curInterface', $interface);  
		$this->cismarty->assign('curModule', $module);
        $this->cismarty->display(APPPATH . '/views/module/editInterface.tpl');   
    }
    private  function getmanagetabURL(){
    	return base_url()."index.php/moduleFunc/exchangeLevel/".$this->uri->segment(3);
    } 
	public function create($params,$interface)
    {
		//var_dump($_REQUEST);
		//$interface=$params['interfaceName'];
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
		$case['name'] = $interface;
        $case['get'] =$getParams;
        $case['post'] =$postParams;
		$case['gval'] =$getVals;
        $case['pval'] =$postVals;
		$case['file'] =0;
		$case['path'] =$params['path'];
		//var_dump($case);
    	$res=$this->case_model->UpdateCaseOne('tb_interface',$case,'name');
		//echo $interface."接口添加成功!";
		echo $interface."接口添加:".$res;
    }
	public function addCase() {
    	$proname=$this->input->post('ID_Pro');
		$geturl=$this->input->post('ID_Url');
		$post=$this->input->post('ID_Post');		
		$test=$this->input->post('ID_Json');
		//$user=phpCAS::getUser();
		$this->platInfoArray = $this->config->item('plat_info_array');
    	$user=$this->platInfoArray['username'];
		$purpose=$this->input->post('ID_Purpose');
		$fileparam=$this->input->post('FileParam');	
		$file="";
		$time=time();
		if(!empty($_FILES['file']['name'])){
			$path="./cases/".$proname."/".$_FILES['file']['name'];
			$file=$_FILES['file']['name'];
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
		$content=$this->getPhpCont($geturl,$post,$time,$test,$user,$purpose,$file);
		//$path="cases/".$ProName;
		$path="cases/".$proname;
		@mkdir($path);
		$caseName="case_".$time.".php";
		$filepath=$path."/".$caseName;
		$fopen=fopen($filepath,"wb");
		fwrite($fopen,$content);
		////Function
		$str="";
		$getpath="./script/funCase.php";
		$file = fopen($getpath, "r");
		while(!feof($file)){
			$str=$str.fgets($file);			
		}//endwhile
		fclose($file);
		fwrite($fopen,$str);
		fclose($fopen);
				
		$this->load->model('case_model');
        $case['caseid'] = $time;
        $case['proname'] =$proname;
        $case['user'] =$user;
        $case['purpose'] =$purpose;
        $case['get'] =$geturl;
        $case['post'] =$post;
        $case['test'] =$test;
        $this->case_model->AddCase('tb_fun_cases',$case);
        //var_dump($case);
        //$url=base_url()."cases/".$proname."/".$caseName;
        //echo $this->http_send($url,"");     
        $this->show(2);
		
	}
	public function getPhpCont($url,$post,$time,$test,$user,$purpose,$file){
		
		$test=str_replace("\"","\\\"",$test);
		$purpose=str_replace("\"","\\\"",$purpose);
		//echo $test;		
		$content="<?php \n##user is ".$user."\n";
		$content=$content."echo \"[caseID:".$time."][Test:".$purpose."]\\n\";\n";
		if($file!=""){
			$PostJson=$this->rewriteParam($post);
			//$PostJson=str_replace("\"","\\\"",$PostJson);
			$content=$content."\$post=\"".$PostJson."\";\n";
			$content=$content."\$postParam=json_decode(\$post,true);\n";
			$content=$content."\$data=http_send(\"".$url."\",\$postParam);\n";
		}
		else{
			$content=$content."\$data=http_send(\"".$url."\",\"".$post."\");\n";
		}
		$content=$content."\$res=\"".$test."\";\n";
		$content=$content."if(assert_json(\$data,\$res))\n";
		$content=$content."{echo \"Passed\\n\";}\n";
		$content=$content."else{echo \"Failed\\n\";}\n";
		return $content;
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
		$res=$res."\\\"".$resArray1[0]."\\\":\\\"@\".dirname(__FILE__).\"/".$resArray1[1]."\\\"}";
		//$res=$res."\"".$resArray1[0]."\":\"".$resArray1[1]."\"}";
		return $res;
	}
    public function startOneCase($pro,$caseid) {
    	$url=base_url()."cases/".$pro."/case_".$caseid.".php";
        $res=$this->http_send($url,"");
        //echo $res;
        $result="Failed";
        if(strpos($res,"Passed")>0){
        	$result="Passed";
        } 
    	//$time=date('Y')."-".date('m')."-".date('d')." ".date('h').":".date('i').":".date('s');
    	$this->load->model('case_model');
    	$case['caseid'] = $caseid;
        $case['result'] =$result;
    	$this->case_model->UpdateCase('tb_fun_cases',$case);
    	//$this->show(1,0);
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
	public function testCase() {
		$proname=$this->input->post('ID_Pro');
    	$ip_port=$this->input->post('ID_IpPort');
    	$get=$this->input->post('ID_GetP');
		$geturl="http://".$ip_port."/".$get;
		$post=$this->input->post('ID_PostP');		
		$fileparam=$this->input->post('FileParam');
		$file="";
		if(!empty($_FILES['file']['name'])){
			$path="./casemng/case/".$proname."/".$_FILES['file']['name'];
			$file=$path;//$_FILES['file']['name'];
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
		$PostJson=$this->rewriteParam($post);
		//echo $PostJson;
		$postParam=json_decode($PostJson,true);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $geturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParam);
		$output = curl_exec($ch);
		curl_close($ch);
		//echo $output;
		$callback = $_GET['callback'];
    	echo $callback.'('.json_encode($output).')';
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
