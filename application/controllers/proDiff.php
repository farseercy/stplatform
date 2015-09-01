<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ProDiff extends MY_Controller {
	
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
        $this->show("","",0);
    }

    public function welcome() {
        $this->cismarty->display(APPPATH . '/views/diff/welcome.tpl');
    }

    /**
        一键diff
    */
    public function one_click() {
    	$this->cismarty->display(APPPATH . '/views/diff/one_click.tpl');
    }
    /**
        配置忽略字段，将配置信息保存至./conf/xxx.conf中
    */
    public function conf_ignore() {
        $this->cismarty->display(APPPATH . '/views/diff/conf_ignore.tpl');
    }

    /**
        查看结果
    */
    public function diff_ret() {
        $this->cismarty->display(APPPATH . '/views/diff/diff_ret.tpl');
    }
    
    /**
    	开始执行diff测试
    */
    function start_diff() {
        $log_rec = $this->input->post("log_rec");
        $log_regex = $this->input->post("log_regex");
        $log_pos = $this->input->post("log_pos");

    	$off_env = $this->input->post("offline_env");
    	$on_env = $this->input->post("online_env");

        // 获取线上log
        $this->load->model("Diff_model");
        $dict_name = $this->Diff_model->get_dict($log_rec, $log_regex, $log_pos);

        echo $dict_name;
        // 
    	// echo $off_env." string ".$on_env;
        // 执行diffPro程序

      //  $cmd = "../../diffPro/diffPro $off_env $online_env ";
     //   shell_exec($cmd);
        $s = system("D:/xampp/htdocs/stplatform/a.exe 5", $ret); // 会执行很长时间
        echo "s: ".$s;
        echo("ret is $ret  ");

    }


	public function exchangeLevel($pro,$module,$offset=0)
    {
    	$this->show($pro,$module,$offset);
    }
    private function show($pro,$module,$offset=0)
    {   
		$curPro=urldecode($pro);
    	$this->platInfoArray = $this->config->item('plat_info_array');
    	$this->platInfoArray['curModule']=$module;
		/*
		$this->load->model('case_model');
    	$modules=$this->case_model->get_mods_by_proName('tb_project',$curPro);
		$arrModule=@split(";",$modules);
		//var_dump($arrModule);
		$arrPro=$this->case_model->get_all_projects('tb_project','name');
		*/
		$arrModule = array('proDiff');
		$arrPro = array();
        $this->cismarty->assign('platInfo', $this->platInfoArray);
		$this->cismarty->assign('curPro', $curPro);  
		$this->cismarty->assign('curModule', $module); 
		$this->cismarty->assign('arrModule', $arrModule);
		$this->cismarty->assign('arrPro', $arrPro);       
        $this->cismarty->display(APPPATH . '/views/project/diff.tpl');   
    }
    private  function getmanagetabURL(){
    	return base_url()."index.php/proDiff/exchangeLevel/".$this->uri->segment(3);
    }
}
