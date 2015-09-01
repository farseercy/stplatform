<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class GetAlertInfo extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		//var_dump(base_url());die();
		$this->cismarty->assign("baseurl", base_url());
	}
	
    public function index()
    {       
    	$statis['startTime'] = $_GET['st'];
    	$statis['endTime'] = $_GET['ed']; 
    	
    	$this->load->model('report_model');
    	$statis['undeal'] = $this->report_model->get_rows_byStatus($_SESSION['productid'],$_SESSION['selectlayer'],$_SESSION['selectitem'],0,$statis['startTime'],$statis['endTime']); 
    	$statis['dealing'] = $this->report_model->get_rows_byStatus($_SESSION['productid'],$_SESSION['selectlayer'],$_SESSION['selectitem'],1,$statis['startTime'],$statis['endTime']);    	    	
    	$statis['dealed'] = $this->report_model->get_rows_byStatus($_SESSION['productid'],$_SESSION['selectlayer'],$_SESSION['selectitem'],2,$statis['startTime'],$statis['endTime']);    	
    	$statis['total']=$statis['dealed']+$statis['undeal']+$statis['dealing'];
    	echo json_encode($statis);
    }
    
    //获取本层报警策略信息
	public function getAlertArray()
    {       
    	$this->load->model('alertcfg_model');
        $result = $this->alertcfg_model->GetAlertCfgByLayer($_SESSION['productid'], $_SESSION['selectlayer'], $_SESSION['selectitem']);
        
        $alert = array();
        foreach($result as $key => $value){
            $alert[$value->id] = $value->name;
        }
        
    	echo json_encode($alert);
    }
    
    //根据报警策略id查询报警策略
	public function getAlertById()
    {      
    	$alertid = $_GET['alertid']; 
    	$this->load->model('alertcfg_model');
        $alert = $this->alertcfg_model->GetAlertCfgById($alertid);               
    	echo json_encode($alert);
    }
    
    //保存报警策略
	public function saveAlert()
    {       
    	//获取POST参数
    	$rev=json_decode($_POST['data']);
    	//var_dump($rev);
    	$alertid=$rev->alertid;

		$alert['name '] = $rev->name ;		
		$alert['priority'] = $rev->priority;
		$alert['emaillist'] = $rev->emaillist;
		$alert['emailcnt'] = intval($rev->emailcnt);
		$alert['smslist'] = $rev->smslist;
		$alert['smscnt'] = intval($rev->smscnt);
		$alert['overtime'] = $rev->overtime;
		$alert['memo'] = $rev->memo;
		$alert['uptime'] = date('Y-m-d H:i:s', time());		
		
    	$this->load->model('alertcfg_model');    	
    	
    	//返回caseid，执行更新操作
    	if($alertid!=0){
    		$alert['id'] = $alertid;
    		$res=$this->alertcfg_model->UpdateAlertCfg($alert);
    	}
    	//否则，执行新增操作
    	else {
    		$alert['productid'] = intval($_SESSION['productid']);
			$alert['parentlayer'] = intval($_SESSION['selectlayer']);
			$alert['childlayer'] = intval($_SESSION['selectitem']);
    		$this->alertcfg_model->AddAlertCfg($alert);
    		$res = true;
    	}
    	//获取callback参数
    	$callback = $_GET['callback'];
    	echo $callback.'('.json_encode($res).')';
    }
    
    
	//显示报警详情页面
    public function showAlertDetail($offset=0)
    {     	
    	//加载平台基础信息
    	$this->platInfoArray = $this->config->item('plat_info_array');                
        //设置当前所选择的监控层及监控分类
        $this->platInfoArray['productId'] = $_SESSION['productid'];
        $this->platInfoArray['selectlayertab']=$_SESSION['selectlayer'];
        $this->platInfoArray['selectlayeritemtab']=$_SESSION['selectitem'];
        $this->cismarty->assign('platInfo', $this->platInfoArray);
        
        //加载报警详情配置
    	$reportInfo = $this->config->item('report_list_info');
    	$reportInfo['offset'] = $offset;
    	//获取报警的总数 	
    	$start = $_GET['st'];
    	$end = $_GET['ed']; 	    	
    	$this->load->model('report_model');
    	$reportInfo['total_rows'] = $this->report_model->get_total_rows($_SESSION['productid'],$_SESSION['selectlayer'],$_SESSION['selectitem'],$start,$end); 
    	//获取需要显示的用例
    	$reports=$this->report_model->get_report_bylayer($_SESSION['productid'],$_SESSION['selectlayer'],$_SESSION['selectitem'],$reportInfo['per_page'],$offset,$start,$end);   	
    	//var_dump($reports);die();
    	$this->cismarty->assign('reports', $reports);
	    $this->cismarty->assign('reportInfo', $reportInfo);
		
	    //显示报警详情页
    	$cur_url=$this->getAlertDetailURL();
	    $this->cismarty->assign('cur_url', $cur_url);
	    $this->cismarty->assign('query', $_SERVER['QUERY_STRING']);
    	$this->cismarty->display(APPPATH . '/views/templates/alertDetail.tpl');
    }
    
    //显示报警详情页面
    public function revJSerror()
    { 
    	echo $_GET['msg'];
    }
    
	//获取报警详情页的url
    private function getAlertDetailURL(){    	
    	
    	return base_url()."index.php/getAlertInfo/showAlertDetail";
    }
    
}
