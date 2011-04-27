<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Admin extends MY_Controller
{
	const T_ADMINUSER = 'adminuser';

	private $site_keywords = array();
	private $site_description = '';
	private $menu_list = array();

	private $private_config = array();
	private $breadcrumbs    = array();
	private $is_private     = false;
	private $limit  = 10;
	private $search = '';
	private $offset = 0;
	private $order  = 0;
	private $category_id  = 0;
	private $search_option  = false;
	private $next_url;
	private $category_list_all = array();

	function __construct()
	{
		parent::__construct();

		// load models
		$this->load->model('webmemo/category');
		$this->load->model('webmemo/memo');

		// load helpers
		$this->load->helper('admin');

		$this->config->load('admin', true);
		$this->private_config = $this->config->item('admin');
//		$this->_configure();
	}

	private function _get_default_view_data()
	{
		return array();
	}

	public function index()
	{
		$view_data = $this->_get_default_view_data();
		$this->smarty_parser->parse('ci:admin/index.tpl', $view_data);
	}

	public function webmemo()
	{
		$view_data = $this->_get_default_view_data();
		$this->smarty_parser->parse('ci:admin/webmemo.tpl', $view_data);
	}

	function create()
	{
		//Load
		$this->load->helper('url');
		$this->load->library('validation');
		
		//Check incoming variables
		$rules['create_username']	= "required|min_length[4]|max_length[32]|alpha_dash";
		$rules['create_password']	= "required|min_length[4]|max_length[32]|alpha_dash";		

		$this->validation->set_rules($rules);

		$fields['create_username'] = 'Username';
		$fields['create_password'] = 'Password';
		
		$this->validation->set_fields($fields);
				
		if ($this->validation->run() == false) {
			/*
			//If you are using OBSession you can uncomment these lines
			$flashdata = array('error' => true, 'error_text' => $this->validation->error_string);
			$this->session->set_flashdata($flashdata); 
			$this->session->set_flashdata($_POST);
			*/
			redirect('/example/');			
		} else {
			//Create account
			if($this->simplelogin->create($this->input->post('create_username'), $this->input->post('create_password'))) {
				/*
				//If you are using OBSession you can uncomment these lines
				$flashdata = array('success' => true, 'success_text' => 'Account Creation Successful!');
				$this->session->set_flashdata($flashdata);
				*/
				redirect('/example/');	
			} else {
				/*
				//If you are using OBSession you can uncomment these lines
				$flashdata = array('error' => true, 'error_text' => 'There was a problem creating the account.');
				$this->session->set_flashdata($flashdata); 
				$this->session->set_flashdata($_POST);
				*/
				redirect('/example/');			
			}			
		}
	}

	function delete($user_id)
	{
		/* This method can delete your current user account
		 * and you will still be logged in until you click
		 * the logout button (then you won't be able to login again')
		 */
		
		//Load
		$this->load->helper('url');

		if($this->simplelogin->delete($user_id)) {
			/*
			//If you are using OBSession you can uncomment these lines
			$flashdata = array('success' => true, 'success_text' => 'Deletion Successful!');
			$this->session->set_flashdata($flashdata);
			*/
			redirect('/example/');	
		} else {
			/*
			//If you are using OBSession you can uncomment these lines
			$flashdata = array('error' => true, 'error_text' => 'There was a problem creating the account.');
			$this->session->set_flashdata($flashdata); 
			$this->session->set_flashdata($_POST);
			*/
			redirect('/example/');			
		}			
		
	}

	function login()
	{
		$view_data = $this->_get_default_view_data();
		$this->smarty_parser->parse('ci:admin/login.tpl', $view_data);
	}

	function execute_login()
	{
		$rules['login_username']	= "required|min_length[4]|max_length[32]|alpha_dash";
		$rules['login_password']	= "required|min_length[4]|max_length[32]|alpha_dash";		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'アカウント名', 'trim|required|min_length[5]|max_length[12]|alpha_numeric|xss_clean');
		$this->form_validation->set_rules('password', 'パスワード', 'trim|required|min_length[5]|max_length[12]');

		if ($this->form_validation->run())
		{
			if ($this->simplelogin->login(set_value('username'), set_value('password')))
			{
				// ログイン成功
				redirect(admin_url());
			}

			$message = 'アカウント名、パスワードが正しくありません';
			$this->session->set_flashdata('message', $message, true);
		}

		$view_data = $this->_get_default_view_data();
		$this->smarty_parser->parse('ci:admin/login.tpl', $view_data);
	}

	function execute_logout()
	{
		$this->simplelogin->logout();
		redirect(admin_url('login'));
	}
}

/* End of file webmemo.php */
/* Location: ./application/controllers/webmemo.php */
