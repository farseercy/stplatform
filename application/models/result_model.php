<?php
/**
 * 
 * Case运行结果类
 * @author liuxiaochun03
 *
 */
class Result_model extends CI_Model {
	//关联case的id
	var $caseid = '';
	//运行的详细结果
    var $detail = '';
    //运行结果 0为失败 1为成功
    var $exerst = 1;
    //运行结束时间
    var $exetime = '';
    //是否已经生成过告警(0-未生成 1-已生成email告警 2-已生成hi告警 3-已生成sms告警)
    var $alert = 0;

    function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }
    
    function get_allresult()
    {
        $query = $this->db->get('tb_results');
        return $query->result();
    }
    
    function get_result_bycase($caseid)
    {
        $query = $this->db->get_where('tb_results',array('caseid' => $caseid));
        return $query->result();
    }

    function get_result_failcnt($caseid, $alert_type, $begin_time)
    {
    	$this->db->where('caseid', $caseid); 
    	$this->db->where('exerst', 0); 
    	$this->db->where('exetime >', $begin_time);
    	$this->db->where('alert <', $alert_type);
        $this->db->from('tb_results');
        return $this->db->count_all_results();
    }
    
    function update_resultalert($caseid, $alert_type)
    {    	
		$data = array("alert" => $alert_type);
		$this->db->where("caseid", $caseid);
		$this->db->where("alert <", $alert_type);
        $this->db->update('tb_results', $data);
    }

    function add_result($result)
    {
		$this->caseid = $result["caseid"];
		$this->detail = $result["detail"];
		$this->exerst = $result["exerst"];
		$this->exetime = date('Y-m-d H:i:s', time());
		$this->alert = 0;
        $this->db->insert('tb_results', $this);
    }
    
    function del_results_bytime($time)
    {
    	$this->db->where('exetime <', $time);
    	$this->db->delete('tb_results');
    }
}