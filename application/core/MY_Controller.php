<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->_set_current_controller_action();
		$this->_check_user_agent();
		$this->_check_client_ip();
		$this->_check_module_enabled();
		$this->_check_admin();
		$this->_set_current_module();
		$this->_setup();
	}

	private function _setup()
	{
		// load configs
		$this->config->load(CURRENT_MODULE, true);
		if (!defined('SITE_TITLE')) define('SITE_TITLE', $this->config->item('site_title', CURRENT_MODULE));
	}

	private function _set_current_controller_action()
	{
		define('CURRENT_CONTROLLER', $this->uri->rsegment(1));
		define('CURRENT_ACTION',     $this->uri->rsegment(2));
	}

	private function _set_current_module()
	{
		$current_module = 'webmemo';
		if (IS_ADMIN)
		{
			$current_module = 'admin';
		}
		elseif (IS_MOBILE)
		{
			$current_module = 'mobile';
		}
		elseif (IS_MOBILE)
		{
			$current_module = 'mobile';
		}
		elseif ($this->uri->segment(1, false))
		{
			$current_module = $this->uri->segment(1, false);
		}

		define('CURRENT_MODULE', $current_module);
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

	private function _check_module_enabled()
	{
		if ($this->uri->segment(1, false) && in_array($this->uri->segment(1, false), $GLOBALS['DISABLED_MODULES']))
		{
			show_error('this module is disabled.');
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

	private function _check_module_from_uri_segment($name)
	{
		if ($this->uri->segment(1, false) && $this->uri->segment(1, false) == $name)
		{
			return true;
		}

		return false;
	}

	private function _check_admin()
	{
		$this->_check_auth();
		$admin_path = $this->config->item('admin_path');

		if (!$this->_check_module_from_uri_segment($admin_path))
		{
			define('IS_ADMIN', false);
			return;
		}

		// 以下、管理画面の処理
		define('IS_ADMIN', true);
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

	protected function _get_template_name($filename)
	{
		if (IS_MOBILE) $filename .= '_mobile';
		$filename .= '.tpl';

		return $filename;
	}
}
