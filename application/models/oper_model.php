<?php
/**
 * 
 * 运维Case类
 * @author liuxiaochun03
 *
 */
class Oper_model extends CI_Model {
	//case id
	var $id = '';
	//case编号
	var $num = '';
	//case名称
    var $title = '';
    //case类型
    var $ctype = '';
    //case对应的产品线id
    var $productid = '';
    //父层次
	var $parentlayer = '';
	//子层次
	var $childlayer = '';
    //case对应告警策略的id
    var $alertid = '';
    //case内容
    var $content = '';
    //case级别
    var $clevel = '';
    //case有效时间
    var $validtime = '';
    //case执行周期
    var $frequency = '';
    //case创建时间
    var $createtime = '';
    //case执行时间
    var $starttime = '';
    //case更新时间
    var $updatetime = '';
    //case是否启动
    var $startflag = 0;

    function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }
    
    function get_allentrys()
    {
        $query = $this->db->get('tb_cases');
        return $query->result();
    }
   
	function get_allcases(){
		$query = $this->db->get('tb_cases');
		return $query->result();		
	}
	
	function get_all_modules($table,$key){
		$sql_str = "select ".$key." from ".$table;
		$query = $this->db->query($sql_str);
		return $query->result();
	}
	
	function get_total_rows($table) {
		$query = $this->db->get($table);
		return $query->num_rows;
	}
	
    function get_part_rows($table,$key,$value) {
    	$sql_str = "select id from ".$table." where ".$key." = '" .$value. "';";
		$query = $this->db->query($sql_str);
		return $query->num_rows;
	}
	
	function get_part_cases($table, $key, $value, $num, $offset) {  	
    	$sql_str = "select * from ".$table." where ".$key." = '" .$value. "' order by caseid desc;";
		$query = $this->db->query($sql_str);
		//$this->db->order_by("caseid", "desc");
		//$query = $this->db->get($table, $num, $offset);
		return $query->result();
	}
	
	function get_cases($table, $num, $offset) {
    	$this->db->order_by("caseid", "desc");
		$query = $this->db->get($table, $num, $offset);
		return $query->result();
	}
	
	private function http_get($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	
	function get_groups($key){
		$group_list = $this->config->item($key);	
		return $group_list;		
	}
	
	function get_hostinfos_bygroup($group){
		$query_str = "fields=id,name,idc&" . "nodepaths=" . $group;
		$url = $this->config->item("op_url_bypath") . $query_str;
		$data = $this->http_get($url);
		$json_data = json_decode($data);
		return $json_data->data;
	}
	
	function get_hostinfos_bymcroom($mcroom) {
		$query_str = "fields=id,name,idc&" . "nodepaths=BAIDU_NS_CBD_MAP_mobile-map_wpng-phpui";
		$url = $this->config->item("op_url_bypath") . $query_str;
		$data = $this->http_get($url);
		$json_data = json_decode($data);
		return $json_data->data;		
	}

    function add_case($case)
    {
		$this->num = $case['num'];
		$this->title = $case['title'];
		$this->alertid = $case['alertid'];
		$this->ctype = $case['ctype'];
		$this->productid = $case['productid'];
		$this->parentlayer = $case['parentlayer'];
		$this->childlayer = $case['childlayer'];
		$this->content = $case['content'];
		$this->clevel = $case['clevel'];
		$this->validtime = $case['validtime'];
		$this->frequency = $case['frequency'];
		$this->createtime = date('Y-m-d H:i:s', time());
		$this->updatetime = date('Y-m-d H:i:s', time());
		$this->startflag = $case['startflag'];
		$sql_str = "select id from tb_cases where num = '" . $this->num . "';";
		$query = $this->db->query($sql_str);
		if (empty($query) || $query->num_rows() == 0)
		{
        	$this->db->insert('tb_cases', $this);
		}
    }

    function update_case()
    {
		$this->num = $this->input->post('num');
		$this->title = $this->input->post('title');
		$this->alertid = $this->input->post('alertid');
		$this->ctype = $this->input->post('ctype');
		$this->productid = $this->input->post('productid');
		$this->parentlayer = $case['parentlayer'];
		$this->childlayer = $case['childlayer'];
		$this->content = $this->input->post('content');
		$this->clevel = $this->input->post('clevel');
		$this->validtime = $this->input->post('validtime');
		$this->frequency = $this->input->post('frequency');
		$this->startflag = $this->input->post('startflag');
		$this->updatetime = date('Y-m-d H:i:s', time());

        $this->db->update('tb_cases', $this, array('id' => $this->input->post('id')));
    }

}