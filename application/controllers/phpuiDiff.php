<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$_SESSION['currentTab']=4;
$_SESSION['currentLayer']=0;
$_SESSION['Url']="";

class PhpuiDiff extends MY_Controller {
	function __construct(){
		parent::__construct();
		set_time_limit(1800);
		$this->load->helper('url');
		$this->load->helper("http");
		$this->cismarty->assign("baseurl", base_url());
	}
	
	public function index() {
		$this->show(0);	// 默认显示第0个左侧列表页面
	}
	
	// 切换左侧列表页
	public function exchangeLevel($layerkey,$offset=0,$pro="all",$op="Emp")
	{
		$_SESSION['selectlayer']=$layerkey;

		$this->show($layerkey,$offset);
	}
	
	// 展示左侧列表
    private function show($layerkey,$offset=0)
    {  
    	// 从配置文件中检索元素, item中为$config数组中你期望检索的索引
    	$this->platInfoArray = $this->config->item('plat_info_array');
    	$this->platInfoArray['currentTab'] = $_SESSION['currentTab'];
    	$this->platInfoArray['currentLayer'] = $layerkey;
    	$this->platInfoArray['Url'] =$_SESSION['Url'];
    	
    	// 将platInfoArray数组assign到platInfo变量中，在view的tpl文件中使用。
        $this->cismarty->assign('platInfo', $this->platInfoArray);
        
    	if($this->platInfoArray['currentLayer']==0){
    		// 一键diff页面
    		// 1、一次diff完成后，如果没有再进行diff，多次配置忽略字段，一直使用的第一次diff请求的结果。
    		$cur_url = $this->getmanagetabURL();
    		$this->cismarty->assign('cur_url', $cur_url);
    		$this->cismarty->display(APPPATH . '/views/templates/phpuiDiff/diffByOneClick.tpl');
    	}
    	else if($this->platInfoArray['currentLayer']==1){
    		// 过滤字段页面。
    		// 1、默认设置某些字段自动过滤。
    		// 2、设置下拉列表，选择需要配置的接口。【可快速搜索某字段】
    		// 3、展示diff结果前，首先根据忽略字段配置进行过滤。
    		// echo "111";
	    	$cur_url = $this->getmanagetabURL();
    		$this->cismarty->assign('cur_url', $cur_url);
    		$this->cismarty->display(APPPATH . '/views/templates/phpuiDiff/diffByOneClick.tpl');
        	
    	} else if ($this->platInfoArray['currentLayer']==2) {
    		// 显示diff结果页面
    		// 1、首先显示出本次diff的每个接口的差异项总体统计。【按差异项多少降序排列】
    		// 2、设置下拉列表，选择接口，查看该接口具体差异项。
    		// 3、如果某接口差异项较多，采用分页展示。
//     		echo 222;
    		$cur_url = $this->getmanagetabURL();
    		$this->cismarty->assign('cur_url', $cur_url);
    		$this->cismarty->display(APPPATH . '/views/templates/phpuiDiff/diffByOneClick.tpl');
    	}
    }
    private  function getmanagetabURL(){
    	return base_url()."index.php/phpuiDiff/exchangeLevel/".$this->uri->segment(3);
    }
	
    public function startDiff() {
   // 	echo "startDiff";
    	// 准备好词表。获取词表的方式待改进。目前获取当前时间一个小时之前的线上日志。
//      	exec("sh phpui_diff/get_dict.sh", $dict_res, $dict_return);
//      	print_r($dict_res);
//      	print_r($dict_return);
    	$this->platInfoArray = $this->config->item('plat_info_array');
    	$this->platInfoArray['currentTab'] = $_SESSION['currentTab'];
    	$this->platInfoArray['Url'] =$_SESSION['Url'];
    	 
    	// 将platInfoArray数组assign到platInfo变量中，在view的tpl文件中使用。
    	$this->cismarty->assign('platInfo', $this->platInfoArray);
    	
    	$cur_url = $this->getmanagetabURL();
    	$this->cismarty->assign('cur_url', $cur_url);
    	$this->cismarty->display(APPPATH . '/views/templates/phpuiDiff/startDiff.tpl');
    }
   
}