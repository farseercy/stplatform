<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class LogProcess extends MY_Controller{
	function __construct(){
		parent::__construct();

		$this->load->helper('url');
		$this->cismarty->assign("baseurl", base_url());
	}

	public function index(){
		$this->ProcessLog();		
	}
	public function showSv($os){
		$this->load->model('log_statistic_model');
		$svList = $this->log_statistic_model->GetSv($os);		
		echo json_encode($svList);		
	}
	public function showAction($os,$sv){
		$this->load->model('log_statistic_model');
		$actionList = $this->log_statistic_model->GetAction($os,$sv);
		echo json_encode($actionList);		
	}
	public function showOv($os){
		$this->load->model('log_statistic_model');
		$ovList = $this->log_statistic_model->GetOv($os);
		echo json_encode($ovList);	
	}
	public function showMb($os){
		$this->load->model('log_statistic_model');
		$mbList = $this->log_statistic_model->GetMb($os);
		echo json_encode($mbList);	
	}
	
	public function ProcessLog(){
		//获取post表单内容
		$recv = json_decode($_POST['data']);
		$startTime = $recv->startTime;
		$endTime = $recv->endTime;
		$action = $recv->dims->action;
		$os = $recv->dims->os;
		$mb = $recv->dims->mb;
		$sv = $recv->dims->sv;
		$ov = $recv->dims->ov;
		$net = $recv->dims->net;
		$option = $recv->dims->option;
		

		//加载数据库
		$this->load->model('log_statistic_model');
		//获取数据库中Top排行的action
		$yesterday = date('Y-m-d 12:00:00', time() - 86400);
//		$yesterday = "2014-07-02 00:00:00";

		$arrayTmp = new stdClass();
		$arrayTmp->action = $action;
		$actionArray[] = $arrayTmp;
		
		//所有action的数据集合
		$chartInfoArray = array();
		//获取所有top行为统计信息
		foreach($actionArray as $key=>$value)
		{
			//根据不同option获取相应的统计信息
			switch($option)
			{
				case '0':
					$data = $this->log_statistic_model->GetStatisticalConsByNet($startTime, $endTime, $os, $sv, $value->action,$net);
					break;
				case '1':
					$data = $this->log_statistic_model->GetStatisticalConsByOv($startTime, $endTime, $sv, $value->action, $ov);
					break;
				case '2':
					$data = $this->log_statistic_model->GetStatisticalConsByMb($startTime, $endTime, $os, $sv, $value->action,$mb);
					break;
				default:
					break;
			}
			
			//处理单个action的所有数据
			$result_data = array();
			//画图时，需要统计值为int，需要转换一下
			foreach($data as $key=>$value){
				$obj = new stdClass();
				$obj->cnt = intval($data[$key]->value1);
				$obj->ctime = $data[$key]->ctime;
				$result_data[] = $obj;
			}

			$chartInfo = array(
				'name' => $value->action,	
				'info' => $result_data,
			);
			$chartInfoArray[] = $chartInfo;
		}
		
		
		
/*		$extAction = $recv->dims->extAction;
		$extMb = $recv->dims->extMb;
		$extOv = $recv->dims->extOv;
		$detailOption = $recv->dims->option;

		//加载数据库
		$this->load->model('log_statistic_model');
		//获取数据库中Top排行的action
		$yesterday = date('Y-m-d 12:00:00', time() - 86400);
//		$yesterday = "2014-07-02 00:00:00";
		switch ($action) 
		{		
			case '0':
				$actionArray = $this->log_statistic_model->GetTopActions($yesterday, $extOv, "7.2.0", 5);
				break;
			case '1':
				$actionArray = $this->log_statistic_model->GetTopActions($yesterday, $extOv, "7.2.0", 10);
				break;
			case '2':
				$actionArray = $this->log_statistic_model->GetTopActions($yesterday, $extOv, "7.2.0", 15);
				break;
			case '3':
				$arrayTmp = new stdClass();
				$arrayTmp->action = $extAction;
				$actionArray[] = $arrayTmp;
			default:
				break;
		}

		//所有action的数据集合
		$chartInfoArray = array();
		//获取所有top行为统计信息
		foreach($actionArray as $key=>$value)
		{
			//根据不同option获取相应的统计信息
//			echo $detailOption;
			switch($detailOption)
			{
				case '0':
					$data = $this->log_statistic_model->GetStatisticalContents($startTime, $endTime, $value->action);
					break;
				case '1':
					$data = $this->log_statistic_model->GetStatisticalConsByMb($startTime, $endTime, $value->action, $mb, $sv);
					break;
				case '2':
					$data = $this->log_statistic_model->GetStatisticalConsByOv($startTime, $endTime, $value->action, $extOv, "7.2.0");
					break;
				case '3':
					$data = $this->log_statistic_model->GetStatisticalConsByNet($startTime, $endTime, $value->action, $net);
					break;
				default:
					break;
			}
			
			//处理单个action的所有数据
			$result_data = array();
			//画图时，需要统计值为int，需要转换一下
			foreach($data as $key=>$value){
				$obj = new stdClass();
				$obj->cnt = intval($data[$key]->value1);
				$obj->ctime = $data[$key]->ctime;
				$result_data[] = $obj;
			}

			$chartInfo = array(
				'name' => $value->action,	
				'info' => $result_data,
			);
			$chartInfoArray[] = $chartInfo;
		}*/

		//返回结果需要被callback包围，为了与jsonp一致
		$res = array(
			'errno' => 0,
			'data'  => $chartInfoArray,
		);

		//获取callback参数
		$callback = $_GET['callback'];
		echo $callback . '(' . json_encode($res) . ')';
	}
}