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
	private $order  = 0;
	private $search_category_id  = 0;
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
		$this->load->helper('webmemo');

		$this->_configure();
	}

	private function _configure()
	{
		$this->private_config = $this->config->item('webmemo');
		$this->_set_params();

		$this->breadcrumbs[] = array('uri' => '/', 'name' =>  UM_TOPPAGE_NAME);

		$this->site_keywords    = $GLOBALS['SITE_KEYWORDS'];
		$this->site_description = SITE_DESCRIPTION;

		$this->is_private = false;
		if (IS_AUTH) $this->is_private = true;
	}

	public function index()
	{
		$this->memolist();
	}

	public function memolist()
	{
		$page_title = 'メモ一覧';

		// 検索語対応
		$search = '';
		if ($this->uri->segment(2) == 'search')
		{
			$search = $this->uri->segment(3, '');
		}
		if ($search) $this->search = $search;
		$this->_format_search_param();

		// page description
		$this->site_keywords[]   = $page_title;
		$this->site_description .= sprintf('このページは「%s」のページです。', $page_title);

		$now_category_id = 0;
		$now_category = array();
		if ($now_category_id) $now_category = $this->category->get_row4id($now_category_id);

		$this->_decode_search_params();
		$count_all = $this->memo->get_count_all($this->is_private, $this->search);

		$this->breadcrumbs[] = array('uri' => '', 'name' =>  $page_title);
		if ($this->search)
		{
			$this->breadcrumbs[] = array('uri' => '', 'name' => sprintf('「%s」の検索結果: %d件', $this->search, $count_all));
		}

		$cate_list_all = $this->category->get_list_all();
		$view_data = array(
			'site_keywords'    => $this->site_keywords,
			'site_description' => $this->site_description,
			'breadcrumbs' => $this->breadcrumbs,
			'article_id' => 0,
			'pagination' => $this->_get_pagination($count_all),
			'count_all' => $count_all,
			'cate_list' => $cate_list_all,
			'memo_list' => $this->memo->get_main_list($this->is_private, $this->search, $this->_get_order_column_name($this->order), $this->offset, $this->limit),
			'cate_id_list' => $this->_get_category_id_list($cate_list_all),
			'cate_name_list' => $this->_get_category_name_list($cate_list_all),
			'search' => $this->search,
			'order' => $this->order,
			'order_list' => $this->_get_order_list(),
			'now_category' => $now_category,
			'now_category_id' => $now_category_id,
			'cate_list_important_articles' => $this->memo->get_important_list(),
			'foot_info' => $this->_set_footer_info(),
			'search' => $this->search,
			'opt' => $this->search_option,
			'current_url' => current_url(),
			'next_url' => $this->next_url,
		);

		$this->smarty_parser->parse('ci:webmemo/list.tpl', $view_data);
	}

	public function article()
	{
		$id = (int)$this->uri->segment(2, 0);

		$count_all = 0;
		$now_category_id = 0;
		$memo_list = $this->memo->get_each_article($id, $this->is_private);
		if ($memo_list)
		{
			$count_all = 1;
			$title = $memo_list[0]['mn_title'];
			$cate_sub_id = $memo_list[0]['mc_sub_id'];
			$now_category_name = $memo_list[0]['mc_name'];
			$now_category_id = (int)$memo_list[0]['mc_id'];
		}

		$now_category = array();
		$cate_name_list = array();
		if ($now_category_id)
		{
			$now_category = $this->category->get_row4id($now_category_id);
			$cate_name_list[$cate_sub_id] = $this->category->get_name4id($cate_sub_id);
		}

		if ($memo_list)
		{
			$page_title = sprintf('記事: %s', $title);

			// page description
			$this->site_keywords[]   = $page_title;
			$this->site_description .= sprintf('このページは「%s」についての記事です。', $title);

			// パンくずリスト
			$cate_url = sprintf('category/%d', $cate_sub_id);
			$this->breadcrumbs[] = array('uri' => $cate_url, 'name' => $cate_name_list[$cate_sub_id]);

			$cate_url = sprintf('category/%d', $now_category_id);
			$this->breadcrumbs[] = array('uri' => $cate_url, 'name' => $now_category_name);

			$this->breadcrumbs[] = array('uri' => '', 'name' => '記事');
		}

		$cate_list_all = $this->category->get_list_all();
		$view_data = array(
			'site_keywords'    => $this->site_keywords,
			'site_description' => $this->site_description,
			'breadcrumbs' => $this->breadcrumbs,
			'article_id' => $id,
			'search' => '',
			'pagination' => array(),
			'opt' => 0,
			'count_all' => $count_all,
			'cate_list' => $cate_list_all,
			'memo_list' => $memo_list,
			'cate_id_list' => $this->_get_category_id_list($cate_list_all),
			'cate_name_list' => $cate_name_list,
			'now_category' => $now_category,
			'now_category_id' => $now_category_id,
			'cate_list_important_articles' => $this->memo->get_important_list(),
			'foot_info' => $this->_set_footer_info(),
			'current_url' => current_url(),
		);

		$this->smarty_parser->parse('ci:webmemo/list.tpl', $view_data);
	}

	public function search()
	{
		if ($this->input->server('REQUEST_METHOD') != 'POST')
		{
			redirect();
		}

		$article_id  = $this->_get_params('article_id', 0);
		$category_id = $this->_get_params('category_id', 0);

		$redirect_url = site_url('list');
		if ($article_id)
		{
			$redirect_url = site_url('article/'.$article_id);
		}
		elseif ($category_id)
		{
			$redirect_url = site_url('category/'.$category_id);
		}

		if ($this->search)
		{
			$this->_format_search_param(true);
			$redirect_url = $this->_get_list_url(true);
		}	

		redirect($redirect_url);
	}

	private function _get_category_id_list($cate_list)
	{
		$cate_ids = array();
		foreach ($cate_list as $row) $cate_ids[] = (int)$row['mc_id'];

		return $cate_ids;
	}

	private function _get_category_name_list($cate_list)
	{
		$cate_name_list = array();
		foreach ($cate_list as $row)
		{
			$id = $row['mc_id'];
			$cate_name_list[$id] = $row['mc_name'];
		}

		return $cate_name_list;
	}

	private function _get_pagination($count_all)
	{
		$config = array();

		$config['base_url'] = $this->_get_list_url();
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

	public function _format_search_param($urlencode = false)
	{
		if (!$this->search) return;

		$this->search = trim(preg_replace('/[ 　]+/u', ' ', $this->search));
		if ($urlencode) $this->search = urlencode($this->search);
		list($this->search, $this->search_category_id) = get_category_from_search_word($this->search);
		if ($this->search_category_id) $this->search_option = true;
	}

	public function _decode_search_params()
	{
		if (!$this->search) return;

		$this->search = preg_replace('/[+]+/', ' ', $this->search);
		$this->search = urldecode($this->search);
	}

	public function _get_list_url($is_search_path = false)
	{
		$uri = 'list';
		$params = array(
			'opt'    => (int)$this->search_option,
			'order'  => $this->order,
		);

		if ($is_search_path)
		{
			$uri = 'list/search/'.$this->search;
		}
		else
		{
			$params['search'] = $this->search;
		}

		return  sprintf('%s%s?%s', base_url(), $uri, http_build_query($params));
	}

	private function _get_order_column_name()
	{
		$order_list = $this->_get_order_list();
		if (empty($order_list[$this->order]['column'])) return '';

		return $order_list[$this->order]['column'];
	}

	private function _get_order_list()
	{
		return array(
			'0' => array('column' => 'lastdate DESC', 'ja_name' => '更新日順'),
			'1' => array('column' => 'mc_id', 'ja_name' => '登録順'),
			'2' => array('column' => 'inportant_level', 'ja_name' => '重要度順'),
		);
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
		if ($this->input->get_post($key, $xss_clean)) return $this->input->get_post($key);
		if (!is_null($default)) return $default;

		return false;
	}
}

/* End of file webmemo.php */
/* Location: ./application/controllers/webmemo.php */
