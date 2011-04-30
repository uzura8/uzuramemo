<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Admin_webmemo extends MY_Controller
{
	private $private_config = array();
	private $admin_username = '';

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
		$this->load->model('admin/admin_user');

		// load helpers
		$this->load->helper('admin');

		$this->config->load('admin', true);
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
		$this->webmemo();
	}

	public function webmemo()
	{
		$view_data = $this->_get_default_view_data();
		$this->smarty_parser->parse('ci:admin/webmemo.tpl', $view_data);
	}

	public function category()
	{
		$sub_id = 0;

		$view_data = $this->_get_default_view_data();
		$view_data['select_category_list'] = $this->category->get_list(0, array('id', 'name', 'sub_id'), false);
		$view_data['main_list'] = $this->category->get_list_all();
		$view_data['cate_id_list'] = $this->_get_category_id_list($view_data['main_list']);
		$this->smarty_parser->parse('ci:admin/webmemo_category.tpl', $view_data);
	}

	private function _forword($action, $message = '')
	{
		if (!$action) return;

		$this->session->set_flashdata('message', $message, true);
		$method = sprintf('_view_%s', $action);
		$this->$method();
	}

	private function _get_category_id_list($cate_list)
	{
		$cate_ids = array();
		foreach ($cate_list as $row) $cate_ids[] = (int)$row['id'];

		return $cate_ids;
	}
}

/* End of file admin_webmemo.php */
/* Location: ./application/controllers/admin/admin_webmemo.php */
