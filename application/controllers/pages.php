<?php
class Pages extends CI_Controller {
	public function view($page = 'home') {
		if ( ! file_exists('application/views/pages/'.$page.'.php'))
		{
			// 页面不存在
			show_404();
		}
		$getter = array();
		foreach ($_GET as $key => $value) {
			$getter[$key] = $value;
		}
		$data["getter"] = $getter;
		$this->load->view('pages/'.$page, $data);

	}
}