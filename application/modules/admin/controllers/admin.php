<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Admin extends MY_Controller
{
	private $private_config = array();
	private $admin_username = '';

	function __construct()
	{
		parent::__construct();

		// load models
		$this->load->model('webmemo/category');
		$this->load->model('webmemo/memo');
		$this->load->model('admin/admin_user');

		// load helpers
		$this->load->helper('admin');

		$this->_configure();
	}

	private function _configure()
	{
		$this->private_config = $this->config->item('admin');
		$this->admin_username = $this->session->get('username', 'admin_user');
	}

	private function _get_default_view_data()
	{
		return array('auth_session' => $this->session->get_all('admin_user'));
	}

	public function index()
	{
		if (UM_LOCAL_MODE) admin_redirect('webmemo');

		$view_data = $this->_get_default_view_data();
		$this->smarty_parser->parse('ci:admin/index.tpl', $view_data);
	}

	function edit_user()
	{
		$this->_view_edit_user();
	}

	private function _view_edit_user()
	{
		$view_data = $this->_get_default_view_data();
		$view_data['main_list'] = $this->admin_user->get_list_all();
		$this->smarty_parser->parse('ci:admin/edit_user.tpl', $view_data);
	}

	function edit_password()
	{
		$this->_view_edit_password();
	}

	private function _view_edit_password()
	{
		$view_data = $this->_get_default_view_data();
		$this->smarty_parser->parse('ci:admin/edit_password.tpl', $view_data);
	}

	function login()
	{
		$this->_view_login();
	}

	private function _view_login()
	{
		$view_data = $this->_get_default_view_data();
		$this->smarty_parser->parse('ci:admin/login.tpl', $view_data);
	}

	function execute_login()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'アカウント名', 'trim|required|min_length[5]|max_length[12]|alpha_numeric');
		$this->form_validation->set_rules('password', 'パスワード', 'trim|required|min_length[5]|max_length[12]');
		$this->form_validation->set_rules('is_save', '自動ログイン', 'trim|is_natural_no_zero|max_length[1]');

		$message = '';
		if ($this->form_validation->run())
		{
			if ($this->simplelogin->login(set_value('username'), set_value('password'), set_value('is_save')))
			{
				// ログイン成功
				admin_redirect();
			}

			$message = 'アカウント名、パスワードが正しくありません';
		}

		$this->_forword('login', $message);
	}

	function execute_logout()
	{
		$this->simplelogin->logout();
		admin_redirect('login');
	}

	function execute_create_user()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'アカウント名', 'trim|required|min_length[5]|max_length[12]|alpha_numeric|callback__username_check');
		$this->form_validation->set_rules('password', 'パスワード', 'trim|required|min_length[5]|max_length[12]');
		$this->form_validation->set_rules('password_confirm', 'パスワード(確認用)', 'required');

		$message = '';
		if ($this->form_validation->run())
		{
			if (set_value('password') == set_value('password_confirm'))
			{
				// 登録
				$this->admin_user->insert(set_value('username'), set_value('password'));
				admin_redirect('edit_user', '登録しました');
			}

			$message = 'パスワードが一致しません';
		}

		$this->_forword('edit_user', $message);
	}

	function execute_edit_password()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password_old', '現在のパスワード', 'trim|required|min_length[5]|max_length[12]');
		$this->form_validation->set_rules('password', '新しいパスワード', 'trim|required|min_length[5]|max_length[12]');
		$this->form_validation->set_rules('password_confirm', '新しいパスワード(確認用)', 'required');

		$message = '';
		if ($this->form_validation->run())
		{
			if (!$password = $this->admin_user->get_password4username($this->admin_username)) show_404();
			if ($password != md5(set_value('password_old'))) $message = '現在のパスワードが正しくありません';
			if (!$message && set_value('password') != set_value('password_confirm')) $message = 'パスワードが一致しません';

			if (!$message)
			{
				// 登録
				$this->admin_user->update($this->admin_username, set_value('password'));
				$this->simplelogin->logout();
				admin_redirect('login', 'パスワードを変更しました');
			}
		}

		$this->_forword('edit_password', $message);
	}

	function execute_delete_user()
	{
		$this->site_util->set_post_data_from_submit_key('delete', 'id');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|max_length[12]|is_natural_no_zero');

		if (!$this->form_validation->run()) show_404();
		if (!$row = $this->admin_user->get_row4id(set_value('id'))) show_404();
		if ($row['username'] == $this->admin_username) show_404();

		// 登録
		$this->admin_user->delete4id(set_value('id'));
		admin_redirect('edit_user', '削除しました');
	}

	private function _forword($action, $message = '')
	{
		if (!$action) return;

		$this->session->set_flashdata('message', $message, true);
		$method = sprintf('_view_%s', $action);
		$this->$method();
	}

	function _username_check($str)
	{
		if ($this->admin_user->get_row4username($str))
		{
			$this->form_validation->set_message('_username_check', 'その %s は既に登録されています');
			return false;
		}

		return true;
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */
