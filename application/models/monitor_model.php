<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitor_model extends CI_Model {

	function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }
    
    /**
	 * @param {number} productid:产品线id
	 * @param {number} day：指定获取统计的时间，-1：当前，-2，昨天，以此类推
	 * @param {string} item：统计项的名称
	 * @return {array} 统计请求的结果
	*/
    function getMonitorAlarmByDay($productid,$st,$end,$item,$pageNum=1){
//    	$url = "http://db-testing-mp326.db01.baidu.com:8088/monitor/apiproxy/";
//    	$uname = "lbs";
//		$sk = "lbssk";
		
		$url = "http://monitor.baidu.com:8088/monitor/apiproxy/";
		if($productid==2){
			$uname = "map-webapp";
			$sk = "ugchewebapplbsmiyao";
			$proid='84';
			$groupid='147';
		}elseif ($productid==3){
			$uname = "lbsugc";
			$sk = "ugchewebapplbsmiyao";
			$proid='91';
			$groupid='161';
		}elseif ($productid==1) {
			$uname = "map-mo-server";
			$sk = "skmapmoserver";
			$proid='0';
			$groupid='1908';			
		}
		
		$timeStamp = time();
		$post_data = array (
			    'uname' => $uname,
				'timeStamp'=> $timeStamp,
				'accessToken'=>md5($uname.$timeStamp.$sk),
				'monitorType'=>101,
				'op'=>3,
				//'productId'=>$proid,
		     	'groupId'=>$groupid,
		     	'dateFrom'=>$st,
		     	'dateTo'=>$end,
		     	'alarmPageType'=>2,
		     	'alarmType'=>-1,
				'pageSize'=>30,
				'pageNum'=>$pageNum,
		     );
		     
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 我们在POST数据哦！

		curl_setopt($ch, CURLOPT_POST, 1);
    	
		// 把post的变量加上
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		//$error = curl_error ($ch);
		curl_close($ch);
    	
    	return json_decode($output);
    }

    function addReport($productid,$st,$end,$item){    	   	
    	
    	$res=$this->getMonitorAlarmByDay($productid,$st,$end,$item);
    	
    	$totalPage=$res->retData->retData->pageInfo->totalPage;
    	for($i=0; $i<$totalPage;$i++){
    		$res=$this->getMonitorAlarmByDay($productid,$st,$end,$item,$i+1);
    		$list=$res->retData->retData->listInfo;
    		
    		foreach ($list as $ket=>$value){
	    		$report['caseid']=$value->alarmId;
	    		$report['productid']=$productid;
	    		$report['parentlayer']=2;
	    		$report['childlayer']=$value->monitorType-3;
	    		$report["content"]=$value->alarmMessage;
	    		$report["title"]=$value->monitorName;
	    		$report["reptime"]=$value->createTime;
	    		
	    		$needle = "短信";//判断是否包含短信这个字符   
  				$tmparray = explode($needle,$value->inforUserWay);   
  				if(count($tmparray)>1){   
  					$report["reptype"]=3;
  				}else{
  					$report["reptype"]=1;
  				}
	    			    		
	    		$report["feedback"]=$value->feedbackTypeName;
	    		
	    		if($value->alarmStatus==0||$value->alarmStatus==1){
	    			$report["status"]=0;
	    		}else{
	    			$report["status"]=$value->alarmStatus-1;
	    		}	    		
	    		
	    		var_dump($report);
	    		$this->db->where("caseid", $report['caseid']);
				$query = $this->db->get('tb_reports');
		
				$ret=$query->result();
				if (empty($query) || $query->num_rows() == 0)
				{
		        	$res=$this->db->insert('tb_reports', $report);
		        	var_dump($res);      	
				}
				else{
					var_dump($ret[0]->id);
					$res=$this->db->update('tb_reports', $report, array('id' => $ret[0]->id));
					var_dump($res);
				}   		
    		}
    	}
    }
    
	function getNoahAlarmByTime($productid,$st,$end,$item,$pageNum=1){

		$url = "http://api.mt.noah.baidu.com:8557/falcon/alert/query";

		var_dump($item);//die();
		$post_data = array (
			    'start_time' => $st,
				'end_time'=> $end,
				'per_page'=>10,
				'cur_page'=>$pageNum,
				'namespace'=>$item,				
		     );
		     
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 我们在POST数据哦！

		curl_setopt($ch, CURLOPT_POST, 1);
    	
		// 把post的变量加上
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		//$error = curl_error ($ch);
		curl_close($ch);
    	
    	return json_decode($output);
    }
    
    function addNoahReport($productid,$st,$end,$item){ 
    	for($m=0; $m<count($item); $m++){
    		$res=$this->getNoahAlarmByTime($productid,$st,$end,$item[$m]);
    		if($res->data==null){
    			return ;
    		}
    		var_dump($res->data->total);
    		$total = $res->data->total;
    		//$totalpage = $total/10+1;
    	
    		for($i=0; $i*10<$total; $i++){
	    	//根据服务列表，分别获取服务的报警列表
    		
	    		$res=$this->getNoahAlarmByTime($productid,$st,$end,$item[$m],$i+1);
	    		var_dump($res);
	    		
	    		//-----------------------------------处理返回的数据----------------------------------//
	    		$datas=$res->data->datas;
	    		//var_dump($datas);
	    		var_dump(count($datas));
	    		//die();
	    		//获取服务的每条报警的详情
	    		for($j=0; $j<count($datas); $j++){	    		
	    			$data=$datas[$j];
	    			//var_dump($data);
	    			$report['caseid']=$data->alert_id;
		    		$report['productid']=$productid;
		    		$report['parentlayer']=3;
		    		$report['childlayer']=0;
		    		$report["content"]=$data->mail_title;
		    		
		    		$report["machine"]='';
		    		//一条报警中，可能会合并了多条报警，需要分别处理
		    		for($n=0;$n<count($data->warning_list);$n++){
		    			$report["machine"].=$data->warning_list[$n]->namespace.';';
	
		    			if(!isset($report['item'])){
		    				$tempitem=$data->warning_list[$n]->judge_expression;
			    			var_dump($tempitem);
			    			$s=strpos($tempitem,'{');//寻找位置
			    			$e=strpos($tempitem, '}');
			    			$report["item"]=substr($tempitem, $s+1, $e-$s-1);
			    			
			    			var_dump($s.$e);
			    			var_dump($report['item']);
		    			}	    				    			
		    		}
		    		$report["machine"]=rtrim($report["machine"], ';'); 
		    		$report["reptime"]=$data->alert_time;
		    		//短信
		    		if(array_key_exists("sms_receiver",$data)){
		    			$report["reptype"]=3;
		    		}
		    		//邮件
		    		else{
		    			$report["reptype"]=1;
		    		}
		    		$report["status"]=0;
		    		var_dump($report);
		    		$this->db->where("caseid", $report['caseid']);
					
		    		//-----------------------------------将结果插入数据库----------------------------------//
		    		//数据表名
		    		$talbe = 'tb_noah_reports';
		    		
		    		$query = $this->db->get($talbe);
			
					$ret=$query->result();
					if (empty($query) || $query->num_rows() == 0)
					{
			        	$res=$this->db->insert($talbe, $report);
			        	var_dump($res);      	
					}
					else{
						var_dump($ret[0]->id);
						$res=$this->db->update($talbe, $report, array('id' => $ret[0]->id));
						var_dump($res);
					} 
	    		}
			}
    		   		
    	}
    	
    	var_dump('111');
    }
}

?>