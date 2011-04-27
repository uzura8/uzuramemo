<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->_configure();
		$this->_auth_check();
	}

	private function _configure()
	{
		define('CURRENT_MODULE', $this->uri->rsegment(1, false));
		define('CURRENT_ACTION', $this->uri->rsegment(2, false));
	}

	private function _auth_check()
	{
		if (!CURRENT_MODULE) return;

		$admin_path = $this->config->item('admin_path');
		if (CURRENT_MODULE != $admin_path) return;

		// 以下、管理画面の処理
		if ($this->session->get('logged_in', 'admin_user') === true) return;
		if (CURRENT_ACTION)
		{
			if (in_array(CURRENT_ACTION, $this->config->item('admin_inseccure_actions'))) return;
		}

		redirect(sprintf('/%s/login', $admin_path));
	}	
}
