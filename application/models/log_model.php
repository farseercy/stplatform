<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_model extends CI_Model {

	//告警策略名称
	var $id = '';
	//产品线编号
	var $productid = '';
	//产品线
	var $qt = '';
	//父层次
	var $ctime = '';
	//子层次
	var $cnt = '';
	
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
    function getItemCnt($productid,$day,$item){
    	//先从数据库读取
    	$date=date('Y-m-d',time() + $day * 86400);
    	$ret = $this->get_data_from_DB($productid, $date, $item);
    	
    	//数据库中没有相应记录，则从log平台获取
    	if (($ret != null)&&($ret[0]->cnt != 0)){
    		var_dump("db");
    		$ret = $ret[0]->cnt;
    	}else {
    		var_dump("log");
    		$ret = $this->getLogByDay($productid, $day, $item);
    	}
    	
    	return $ret;
    }
    
	/**
	 * @param {number} productid:产品线id
	 * @param {number} day：指定获取统计的时间，-1：当前，-2，昨天，以此类推
	 * @param {string} item：统计项的名称
	 * @return {array} 统计请求的结果
	*/
	function getLogByDay($productid,$day,$item){						
		
		$proInfo=$this->load->config('proEffectInfo');
		$proInfo=$this->config->item('pro_effectlist');		
		$baseInfo = $proInfo[$productid]['baseInfo'];
		
		//获取产品线
		$product = $baseInfo['product'];
		$date=date('Y-m-d',time() + $day * 86400);
		
		$post_data = array (
				'm'=>'Data',
				'a'=>'GetData',				
				'date'=>$date,
				'item'=>$item,
				'product'=>$product,
		);
		
		if($baseInfo['type']==='token'){
			$token = $baseInfo['token'];			
			$post_data = array_merge(
				array(
				'token'=>$token,			
				),
				$post_data);
			
		}else{
			$uname = $baseInfo['uname'];
			$pass = $baseInfo['pass'];
			$post_data = array_merge(
				array(
					'username'=>$uname,
					'password'=>$pass,
				),
				$post_data);
		}
		
		//var_dump($post_data);die();

		$url = $baseInfo['url'];
		$data = $this->getAllLog($url,$post_data);	
		$num=5;	
		while ((!$data)&&$num>0){
			$data=$this->getAllLog($url,$post_data);
			$num--;
		}
		
		return intval($data);
	}
	
	/**
	* @param {string} $url 请求的host
	* @param {JSON} $post_data 请求的内容
	* @return {array} 统计请求的结果
	*/
	function getAllLog($url,$post_data){
		//用get方式获取数据
		$querystr=$url.'?'.http_build_query($post_data);
		//var_dump($querystr);die();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $querystr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
		$ret = curl_exec($ch);

		if (curl_errno($ch) || !is_string($ret) || !strlen($ret)) {
             $ret = '';
        }
		curl_close($ch);
		return $ret;
	}
	
	/**
	* @case case数据
	*/
	function add_data_to_DB($cases){
		$this->productid = $cases['productid'];
		$this->qt = $cases['qt'];
		$this->ctime = $cases['ctime'];
		$this->cnt = $cases['cnt'];
		
		$this->db->where("qt", $this->qt);
		$this->db->where("ctime", $this->ctime);
		$query = $this->db->get('tb_dataFromLog');

		$ret=$query->result();
		if (empty($query) || $query->num_rows() == 0)
		{
        	$res=$this->db->insert('tb_dataFromLog', $this);
        	//var_dump($res);      	
		}
		else{
			var_dump($ret[0]->id);
			$res=$this->db->update('tb_dataFromLog', $cases, array('id' => $ret[0]->id));
			var_dump($res);
		}
		
	}
	
	/**
	* @param {number} productid:产品线id
	* @param {number} date：指定获取统计的时间
	* @param {string} item：统计项的名称
	* @return {array} 统计请求的结果
	*/
	function get_data_from_DB($productid,$date,$item){
		$this->db->where("productid", $productid);
		$this->db->where("qt", $item);
		$this->db->where("ctime", $date);
		$query = $this->db->get('tb_dataFromLog');
		//var_dump($query->result());
		return $query->result();		
	}
	
	function get_itemCnt_byTime($start,$end,$item){
		$this->db->where("qt", $item);
		$this->db->where("ctime >", $start);
		$this->db->where("ctime <", $end);
		$query = $this->db->get('tb_dataFromLog');
		//var_dump($query);
		return $query->result_array();		
	}		
}

?>