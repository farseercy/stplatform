<?php
/**
 * 
 * Case告警报告类
 * @author liuxiaochun03
 *
 */
class Noah_report_model extends CI_Model {
	//报告关联的caseid
	var $caseid = 0;
	//报告关联的产品线ID
	var $productid = 0;
	//父层次
	var $parentlayer = '';
	//子层次
	var $childlayer = '';
	//报告的标题 同case标题
	var $machine = '';
	//报告内容
    var $content = '';
    //监控项
    var $item = '';
	//报告的时间
    var $reptime = '';
    //报告的类型(0-email, 1-sms)
    var $reptype = '';
    //报告处理的状态
    var $status = 0;

    var $table = '';
    
    function __construct()
    {
    	$this->table = "tb_noah_reports";	
        $this->load->database();
        $this->load->helper('url');
    }
    
    function get_allreport($productid)
    {
        $query = $this->db->get($this->table, array("productid" => $productid));
        return $query->result();
    }
    
	function get_total_rows($productId,$parent,$child,$stTime,$edTime) {
		$this->db->where('productid',$productId);
		$this->db->where('parentlayer',$parent);
    	$this->db->where('childlayer',$child);
    	$this->db->where('reptime >',$stTime);
    	$this->db->where('reptime <',$edTime);
		$query = $this->db->get($this->table);
		return $query->num_rows;
	}
	
	function get_rows_byStatus($productId,$parent,$child,$status,$stTime,$edTime) {
		$this->db->where('productid',$productId);
		$this->db->where('parentlayer',$parent);
    	$this->db->where('childlayer',$child);
    	$this->db->where('status',$status);
    	$this->db->where('reptime >',$stTime);
    	$this->db->where('reptime <',$edTime);
    	$this->db->order_by("reptime","desc");

		$query = $this->db->get($this->table);
		if(!$query){
			return 0;
		}

		return $query->num_rows;
	}
	
    function get_report_bylayer($productid, $parent,$child,$num, $offset,$stTime,$edTime)
    {
    	$this->db->where("productid", $productid);
    	$this->db->where('parentlayer',$parent);
    	$this->db->where('childlayer',$child);
    	$this->db->where('reptime >',$stTime);
    	$this->db->where('reptime <',$edTime);
    	$this->db->order_by("reptime","desc");
        $query = $this->db->get($this->table, $num, $offset);
        return $query->result();
    }
    
	function get_rows_byDate($productid, $parent,$child,$stTime,$edTime)
    {
    	$this->db->where("productid", $productid);
    	$this->db->where('parentlayer',$parent);
    	$this->db->where('childlayer',$child);
    	$this->db->where('reptime >',$stTime);
    	$this->db->where('reptime <',$edTime);
    	$this->db->order_by("reptime","desc");
        $query = $this->db->get($this->table);
        
        //var_dump($query);
        if(!$query){
			return 0;
		}

		return $query->num_rows;
    }

    function add_report($report)
    {
    	//$num = 100000000 + $this->db->count_all('tb_reports') + 1;
		$this->caseid = $report["caseid"];
		$this->productid = $report["productid"];
		$this->parentlayer = $report["parentlayer"];
		$this->childlayer = $report["childlayer"];
		$this->content = $report["content"];
		$this->machine =  $report["machine"];
		$this->item = $report["item"];
		$this->reptime = date('Y-m-d H:i:s', time());
		$this->reptype = $report["reptype"];
		$this->status = 0;
		
        $this->db->insert($this->table, $this);
        return $this->db->insert_id();
    }
}