<?php
/**
 *
 * 产品线类
 * @author liuxiaochun03
 *
 */
class Product_model extends CI_Model {
	//产品线编号
	var $num = '';
	//产品线名称
    var $name = '';
    //产品线描述
    var $desp = '';
    //创建时间
    var $createtime = '';
    //更新时间
    var $updatetime = '';

    function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
    }
    
	function get_allproducts(){
		$query = $this->db->get('tb_products');
		return $query->result();		
	}

	function get_product_byid($id){
		$query = $this->db->get('tb_products', array("id" => $id));
		return $query->result();		
	}
	
    function add_product()
    {
		$this->num = $this->input->post('num');
		$this->name = $this->input->post('name');
		$this->desp = $this->input->post('desp');
		$this->createtime = date('Y-m-d H:i:s', time());
		$this->updatetime = date('Y-m-d H:i:s', time());
        $this->db->insert('tb_products', $this);
    }

    function update_product()
    {
		$this->num = $this->input->post('num');
		$this->name = $this->input->post('name');
		$this->desp = $this->input->post('desp');
		$this->updatetime = date('Y-m-d H:i:s', time());
        $this->db->update('tb_products', $this, array('id' => $this->input->post('id')));
    }
    
    function delete_product($id)
    {
		$this->db->delete('tb_products', array('id' => $id)); 	   	
    }

}