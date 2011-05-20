<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->_configure();
		$this->_check_user_agent();
		$this->_check_client_ip();
		$this->_check_admin();
	}

	private function _configure()
	{
		define('CURRENT_MODULE', $this->uri->segment(1, false));
		define('CURRENT_ACTION', $this->uri->rsegment(2, false));
	}

	private function _check_user_agent()
	{
		define('IS_MOBILE', $this->agent->is_mobile());
	}

	private function _check_client_ip()
	{
		if (!empty($GLOBALS['ALLOW_IP_LIST']))
		{
			if (!in_array($this->input->server('REMOTE_ADDR'), $GLOBALS['AUTO_LOGIN_ACCEPT_IP_LIST'])) common_error();
		}
	}

	private function _check_auth()
	{
		$is_auth = false;

		if ($this->session->get('logged_in', 'admin_user') === true) $is_auth = true;
		if (defined('UM_LOCAL_MODE') && UM_LOCAL_MODE) $is_auth = true;
		if (!empty($GLOBALS['AUTO_LOGIN_ACCEPT_IP_LIST']))
		{
			if (in_array($this->input->server('REMOTE_ADDR'), $GLOBALS['AUTO_LOGIN_ACCEPT_IP_LIST'])) $is_auth = true;
		}


		define('IS_AUTH', $is_auth);
	}

	private function _check_admin()
	{
		$this->_check_auth();

		if (!CURRENT_MODULE) return;

		$admin_path = $this->config->item('admin_path');
		if (CURRENT_MODULE != $admin_path) return;

		// 以下、管理画面の処理
		if (UM_SLAVE_DB_MODE) show_error('admin module is disabled.');

		if (CURRENT_ACTION)
		{
			if (in_array(CURRENT_ACTION, $this->config->item('admin_inseccure_actions')))
			{
				if (IS_AUTH) redirect($admin_path);
				return;
			}
		}
		if (IS_AUTH) return;

		redirect($admin_path.'/login');
	}	
}
