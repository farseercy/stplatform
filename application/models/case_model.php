<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * 
 * Case类
 *
 */
class Case_model extends CI_Model {

    function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }
    
	function get_allcases($table,$proname){
		$query = $this->db->get_where($table, array("proname" => $proname));
		return $query->result();		
	}

	function get_all($table){
		//$this->db->select('url','name','clientid');
		$query = $this->db->get($table);
		return $query->result();		
	}
	
	function get_all_modules($table,$key){
		$sql_str = "select ".$key." from ".$table;
		$query = $this->db->query($sql_str);
		return $query->result_array();
	}
	
	function get_total_byMod($table,$module) {
		$sql_str = "select * from ".$table." where module = '" .$module. "';";
		$query = $this->db->query($sql_str);
		return $query->num_rows;
	}
	
	function get_func_cases($table,$module,$num,$offset) {
		//$sql_str = "select * from ".$table." where module = '" .$module. "';";
		$this->db->where("module", $module);
		$query = $this->db->get($table, $num, $offset);
		return $query->result();	
	}
	
	function get_all_projects($table,$key){
		$sql_str = "select ".$key." from ".$table;
		$query = $this->db->query($sql_str);
		return $query->result_array();
	}
	
	function get_mods_by_proName($table,$name){
		$sql_str = "select * from ".$table." where name=\"".$name."\"";
		$query = $this->db->query($sql_str);
		//return $query->result_array();
		$row = $query->row_array();
		return $row['modid'];
	}
	
	function findOne($table,$skey,$sval,$fkey){
		$sql_str = "select * from ".$table." where ".$skey."=\"".$sval."\"";
		$query = $this->db->query($sql_str);
		//return $query->result_array();
		$row = $query->row_array();
		return $row[$fkey];
	}
	
	function get_modName_by_id($table,$id){
		$sql_str = "select * from ".$table." where id=".$id;
		$query = $this->db->query($sql_str);
		//return $query->result_array();
		$row = $query->row_array();
		return $row['name'];
	}
	
	function get_interfaces_by_modName($table,$name){
		$sql_str = "select * from ".$table." where name=\"".$name."\"";
		$query = $this->db->query($sql_str);
		//return $query->result_array();
		$row = $query->row_array();
		return $row['interface'];
	}
	
	function get_interfaces_by_funcID($table,$caseid){
		$sql_str = "select * from ".$table." where id=\"".$caseid."\"";
		$query = $this->db->query($sql_str);
		//return $query->result_array();
		$row = $query->row_array();
		return $row['interface'];
	}	
	function get_proid_by_name($table,$name){
	    //echo $name;
		$sql_str = "select id from ".$table." where name=\"".$name."\"";
		$query = $this->db->query($sql_str);
		$row = $query->row_array();
		return $row['id'];
		//return $query->row_array();
	}
	
	function get_cases_byModID($moduleid, $table){
		$this->db->where("moduleid", $moduleid);
		$query = $this->db->get($table);
		return $query->result();		
	}
	
	function get_cases_bylayer($table,$productId,$parent,$child){
		$this->db->where('productid',$productId);
		$this->db->where('parentlayer',$parent);
    	$this->db->where('childlayer',$child);
		$query = $this->db->get($table);
		return $query->result();		
	}

	function GetCasesById($table,$caseid){
		$this->db->where('caseid', $caseid);
		$query = $this->db->get($table);
		return $query->result();		
	}
	function GetCasesByKey($table,$key,$value){
		$this->db->where($key, $value);
		$query = $this->db->get($table);
		return $query->result();		
	}
    function AddCase($table,$case)
    {
   		$this->db->insert($table, $case);
    }
	
	function UpdateCaseOne($table,$case,$key)
    {
	    $this->db->update($table, $case, array($key => $case[$key]));
    }
    
    function UpdateCase($table,$case)
    {
	    $this->db->update($table, $case, array('caseid' => $case['caseid']));
    }
	

}