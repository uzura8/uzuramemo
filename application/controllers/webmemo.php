<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Webmemo extends MY_Controller
{
	private $site_keywords = array();
	private $site_description = '';

	private $private_config = array();
	private $breadcrumbs    = array();
	private $is_private     = false;
	private $limit  = 10;
	private $search = '';
	private $offset = 0;
	private $order  = 'lastdate';
	private $search_option  = false;
	private $next_url;

	function __construct()
	{
		parent::__construct();

		// load configs
		$this->config->load('webmemo', TRUE);

		// load models
		$this->load->model('webmemo/category');
		$this->load->model('webmemo/memo');

		// load helpers
		$this->load->helper('url');

		$this->_configure();
	}

	private function _configure()
	{
		$this->private_config = $this->config->item('webmemo');
		$this->breadcrumbs['/'] = UM_TOPPAGE_NAME;

		$this->site_keywords    = $GLOBALS['SITE_KEYWORDS'];
		$this->site_description = SITE_DESCRIPTION;
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$page_title = 'メモ一覧';

		// page description
		$this->site_keywords[]   = $page_title;
		$this->site_description .= sprintf('このページは「%s」のページです。', $page_title);
		$this->breadcrumbs[] = $page_title;

		$now_category_id = 0;

		$is_private = true;
		$this->_set_params();

		$now_category = array();
		if ($now_category_id) $now_category = $this->category->get_row4id($now_category_id);

		$count_all = $this->memo->get_count_all($this->is_private, $this->search);

		$cate_list_all = $this->category->get_list_all();
		$view_data = array(
			'site_keywords'    => $this->site_keywords,
			'site_description' => $this->site_description,
			'breadcrumbs' => $this->breadcrumbs,
			'pagination' => $this->_get_pagination($count_all),
			'count_all' => $count_all,
			'cate_list' => $cate_list_all,
			'memo_list' => $this->memo->get_main_list($this->is_private, $this->search, $this->order, $this->offset, $this->limit),
			'cate_id_list' => $this->_get_category_id_list($cate_list_all),
			'search' => $this->search,
			'now_category' => $now_category,
			'now_category_id' => $now_category_id,
			'cate_list_important_articles' => $this->memo->get_important_list(),
			'foot_info' => $this->_set_footer_info(),
			'search' => $this->search,
			'opt' => $this->search_option,
			'current_url' => current_url(),
			'next_url' => $this->next_url,
		);

		$this->smarty_parser->parse('ci:webmemo/index.tpl', $view_data);
	}

	public function article()
	{
		$now_category_id = 0;
		$this->breadcrumbs[] = 'メモ一覧';

		$is_private = true;
		$this->_set_params();

		$now_category = array();
		if ($now_category_id) $now_category = $this->category->get_row4id($now_category_id);

		$count_all = $this->memo->get_count_all($this->is_private, $this->search);

		$cate_list_all = $this->category->get_list_all();
		$view_data = array(
			'breadcrumbs' => $this->breadcrumbs,
			'pagination' => $this->_get_pagination($count_all),
			'count_all' => $count_all,
			'cate_list' => $cate_list_all,
			'memo_list' => $this->memo->get_main_list($this->is_private, $this->search, $this->order, $this->offset, $this->limit),
			'cate_id_list' => $this->_get_category_id_list($cate_list_all),
			'search' => $this->search,
			'now_category' => $now_category,
			'now_category_id' => $now_category_id,
			'cate_list_important_articles' => $this->memo->get_important_list(),
			'foot_info' => $this->_set_footer_info(),
			'search' => $this->search,
			'opt' => $this->search_option,
			'current_url' => current_url(),
			'next_url' => $this->next_url,
		);

		$this->smarty_parser->parse('ci:webmemo/index.tpl', $view_data);
	}

	private function _get_category_id_list($cate_list)
	{
		$cate_ids = array();
		foreach ($cate_list as $row) $cate_ids[] = (int)$row['mc_id'];

		return $cate_ids;
	}

	private function _get_pagination($count_all)
	{
		$config = array();
		$params = array(
			'search' => $this->search,
			'opt'    => (int)$this->search_option,
			'order'  => $this->order,
		);
		$config['base_url'] = sprintf('%s?%s', $this->config->site_url(), http_build_query($params));
		$config['offset']   = (int)$this->offset;
		$config['query_string_segment'] = 'from';
		$config['total_rows'] = $count_all;
		$config['per_page']   = $this->limit;
		$config['num_links']  = 3;
		$config['first_link'] = '&laquo;最初';
		$config['last_link'] = '最後&raquo;';

		$this->load->library('my_pager', $config);
		$this->my_pager->set_params('prev_link', sprintf('&lt;前の%d件', $this->my_pager->get_prev_page_rows()));
		$this->my_pager->set_params('next_link', sprintf('次の%d件&gt;', $this->my_pager->get_next_page_rows()));
		$config['next_link'] = sprintf('', $this->my_pager->get_next_page_rows());

		$this->next_url = $this->my_pager->get_next_url();

		return $this->my_pager->create_links();
	}

	private function _set_footer_info()
	{
		return '';
	}

	private function _set_params()
	{
		$this->limit  = $this->private_config['article_nums'];
		$this->search = $this->_get_params('search');
		$this->offset = $this->_get_params('from');
		$this->order  = $this->_get_params('order');
		$this->search_option = $this->_get_params('opt');
	}

	private function _get_params($key, $default = NULL, $xss_clean = FALSE)
	{
		if ($this->input->get($key, $xss_clean)) return $this->input->get($key);
		if (!is_null($default)) return $default;

		return false;
	}
}

/* End of file webmemo.php */
/* Location: ./application/controllers/webmemo.php */
