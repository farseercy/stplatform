<?php if (!defined('BASEPATH')) exit('No direct access allowed.');
class MY_Controller extends CI_Controller {
	private $currUser;
	private $currDbInfo;
	private $currProductId;
	
    public function __construct() {
		parent::__construct();
	
//		$this->load->helper('url');
//		$this->cismarty->assign("baseurl", base_url());
		$this->cismarty->assign("curruser", "");
//		$this->cismarty->assign("module", "manage");
		//return;
		require_once 'lib/phpcas/CAS.php';
		phpCAS::setDebug();		
		
/*		phpCAS::client(CAS_VERSION_2_0, $this->config->item('cas_host'), 
						$this->config->item('cas_port'), 
						$this->config->item('cas_context'));
		$this->load->library("session");
 */
		//phpCAS::client(CAS_VERSION_2_0,'uuap.baidu.com',80,'');
		phpCAS::client(CAS_VERSION_2_0,'itebeta.baidu.com',443,'');

        $_SESSION['CAS_USER_LEVEL'] = 2;
		phpCAS::setNoCasServerValidation();
		phpCAS::forceAuthentication();
		if (isset($_REQUEST['logout'])) {
	        phpCAS::logout();
		}
		$this->cismarty->assign("curruser", phpCAS::getUser());
		$this->currUser = phpCAS::getUser();
/*
		$sessionPid = $this->session->userdata('productid');
		if (isset($_REQUEST['productid'])) {
			$this->session->set_userdata('productid', $_REQUEST['productid']);	
		}
		$this->currProductId = $this->session->userdata('productid');
		if (empty($this->currProductId)) {
			$this->currProductId = 1;	
		}
		$this->cismarty->assign('productid', $this->currProductId);
		$this->cismarty->assign("module", strtolower(get_class($this)));	
		$this->load->model('product_model');
		$products = $this->product_model->getProducts();
		$currProduct = $this->product_model->getProduct($this->currProductId);
		$this->currDbInfo = $currProduct->dbinfo;
		$this->cismarty->assign("products", $products);	
		$this->cismarty->assign('product', $this->product_model->getProduct($this->currProductId));
	 */
	}
    
    protected function recordPage($page) {
    	$this->load->model('pv_model');
    	$this->pv_model->addUser($this->currUser, $page, 1);
    }
 
    protected function getCurrUser() {
    	return $this->currUser;
    }
    
    public function logout(){
    	$curruser = $this->session->userdata("curruser");
    	if($curruser){
    		$this->session->sess_destroy();
    	}
    	phpCAS::logout();
    }
	 
    protected function getCurrProductId() {
    	return $this->currProductId;
    }
    
    protected function getCurrDbInfo() {
    	return $this->currDbInfo;
    }

    public function assign($key,$val) {
        $this->cismarty->assign($key,$val);
    }

    public function display($html) {
        $this->cismarty->display($html);
    }
}
