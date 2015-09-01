<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class UploadFile extends MY_Controller {
	
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
        $this->show();
    }
   
    private function show($offset=0)
    {        
    	$this->platInfoArray = $this->config->item('plat_info_array');
        $this->cismarty->assign('platInfo', $this->platInfoArray);
        $this->cismarty->display(APPPATH . '/views/templates/uploadFile/statistic/stat.tpl');  
    }
public function addFile(){
		if(!empty($_FILES['file']['name'])){
		$path="./easyABC/data/files/".$_FILES['file']['name'];
		//$path=$_FILES['file']['name'];
		//echo $path;
        $fileinfo=$_FILES['file'];
        //var_dump($fileinfo);
        if($fileinfo['size']>0){
                move_uploaded_file($fileinfo['tmp_name'],$path);
                echo "upload OK";
        }
        else{
                echo "some unkown error!";
        }
	  }
      else{
 		//	echo "empty!";
	  }
		
	}
	
	public function readDir(){
		$path="./easyABC/data/files";
		$temp="";
		if(is_dir($path)){
			$dir=scandir($path);
			foreach($dir as $value){
				//echo $value."</br>";
				if($value!="."&&$value!=".."){
					$temp=$temp.$value."</br>";
				}
			}
			echo $temp;
		}
      	else{
 			echo "empty!";
	  	}	
	}
}