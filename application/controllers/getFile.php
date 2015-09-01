<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class GetFile extends MY_Controller{
	var $case = array(
		'id' => '',               //id
		'num' => '',              //case编号
		'title' => '',            //case名称
		'ctype' => '',            //case类型
		'productid' => '',        //case对应的产品线id
		'parentlayer' => '',      //父层次
		'childlayer' => '',       //子层次
		'alertid' => '',          //case对应告警策略的id
		'content' => '',          //case内容
		'clevel' => '',           //case级别
		'validtime' => '',        //case有效时间
		'frequency' => '',        //case执行周期
		'startflag' => '',        //case是否启动
		'lasttime' => '',
		'createtime' => '',       //case创建时间
		'starttime' => '',        //case执行时间
		'updatetime' => '',       //case更新时间
		);

	function __construct(){
		parent::__construct();

		$this->load->helper('url');
		$this->cismarty->assign("baseurl", base_url());
	}

	public function index(){
		$this->addFile();
		
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
		if(is_dir($path)){
			$dir=scandir($path);
			foreach($dir as $value){
				echo $value."</br>";
			}
		}
      	else{
 			echo "empty!";
	  	}	
	}
}