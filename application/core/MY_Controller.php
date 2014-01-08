<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public $validation_rules = array();
	protected $default_view_data = array();

	function __construct()
	{
		parent::__construct();

		$this->config->load('site', true);
		$this->load->model('wbs/model_wbs');
		$this->_set_current_controller_action();
		$this->_check_user_agent();
		$this->_check_client_ip();
		$this->_check_module_enabled();
		$this->_check_admin();
		$this->_set_current_module();
		$this->_setup();
		$this->_set_default_view_data();
	}

	private function _setup()
	{
		$this->config->load(CURRENT_MODULE, true);// load configs
		if (!defined('SITE_TITLE')) define('SITE_TITLE', $this->config->item('site_title', 'site'));
		if (!defined('SITE_TITLE_MODULE')) define('SITE_TITLE_MODULE', $this->config->item('site_title', CURRENT_MODULE));

		if (!defined('IS_ACCEPT_ROBOTS'))
		{
			$is_accept_robots = true;
			if (DEV_MODE || UM_CLOSED_MODE || !$this->config->item('is_accept_robots', CURRENT_MODULE))
			{
				$is_accept_robots = false;
			}
			define('IS_ACCEPT_ROBOTS', $is_accept_robots);
		}

		if (!defined('SITE_TITLE')) define('SITE_TITLE', SITE_TITLE_WEBMEMO);
	}

	private function _set_default_view_data()
	{
		$this->default_view_data['program_list_mainmenu'] = $this->db_util->get_rows('program', array('del_flg' => 0), array('id', 'name', 'key_name'), 'sort', 'program', 'model');
		if (!$this->site_util->is_ajax_action(CURRENT_ACTION));
		{
			$this->default_view_data['important_wbs_list_mainmenu'] = $this->model_wbs->get_main_list(0, 0, 'C.sort, B.sort, A.sort', '', false,
																																																'A.id, A.name, B.name as project_name, C.name as program_name',
																																																array('sql' => 'importance = 1'));
		}
		$this->default_view_data['segment_3'] = $this->uri->rsegment(3, 0);
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
		elseif ($this->uri->rsegment(1, false))
		{
			$current_module = $this->uri->rsegment(1, false);
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
			if (!in_array($this->input->server('REMOTE_ADDR'), $GLOBALS['ALLOW_IP_LIST'])) common_error();
		}
	}

	private function _check_module_enabled()
	{
		if (!$this->uri->rsegment(1, false)) return;
		if (empty($GLOBALS['DISABLED_MODULES'])) return;
		if (!in_array($this->uri->rsegment(1, false), $GLOBALS['DISABLED_MODULES'])) return;

		show_error('this module is disabled.');
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
		$admin_path = $this->config->item('admin_path', 'site');

		if (!$this->uri->segment(1, false) || $this->uri->segment(1, false) != $admin_path)
		{
			define('IS_ADMIN', false);
			return;
		}

		// 以下、管理画面の処理
		define('IS_ADMIN', true);

		if (UM_SLAVE_DB_MODE) show_error('admin module is disabled.');
		if (!empty($GLOBALS['ADMIN_ALLOW_IP_LIST']))
		{
			if (!in_array($this->input->server('REMOTE_ADDR'), $GLOBALS['ADMIN_ALLOW_IP_LIST'])) common_error();
		}

		if (CURRENT_ACTION)
		{
			if (in_array(CURRENT_ACTION, $this->config->item('admin_inseccure_actions', 'site')))
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

	protected function _setup_validation($action = '', $is_set_rules = true)
	{
		$this->load->library('form_validation');
		$this->validation_rules = $this->_get_validation_rules($action);
		if ($is_set_rules) $this->_set_validation_rules();
	}

	protected function _get_validation_rules($action, $field = '')
	{
		$method = '_validation_rules';
		if ($action) $method .= '_'.$action;
		if (!$list = $this->$method()) return false;

		if ($field)
		{
			if (empty($list[$field])) return false;

			return $list[$field];
		}

		return $list;
	}

	protected function _set_validation_rules()
	{
		foreach ($this->validation_rules as $field => $row)
		{
			$this->form_validation->set_rules($field, $row['label'], $row['rules']);
		}
	}

	protected function _get_form_data()
	{
		$values = array();
		foreach ($this->validation_rules as $field => $row)
		{
			if (!empty($row['disabled_item_in_sql'])) continue;

			$values[$field] = set_value($field);
		}

		return $values;
	}

	protected function _get_post_params($key, $default = NULL, $rules = '', $xss_clean = FALSE)
	{
		$value = $this->input->get_post($key, $xss_clean);
		if ($value === false) return $default;

		$valid_value = '';
		if (is_array($value))
		{
			$valid_value = array();
			foreach ($value as $each)
			{
				$valid_value[] = $this->site_util->simple_validation($each, $default, $rules);
			}
		}
		else
		{
			$valid_value = $this->site_util->simple_validation($value, $default, $rules);
		}

		return $valid_value;
	}

	protected function _validate_unique_check($table, $key, $value, $error_message = 'その %s は既に登録されています')
	{
		$class_name = 'model_'.$table;
		if ($this->$class_name->get_row_common(array($key => $value)))
		{
			$validation_name = '_unique_check_'.$key;
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		return true;
	}
}
