<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webmemo extends MY_Controller
{
	private $site_keywords = array();
	private $site_description = '';

	private $private_config = array();
	private $breadcrumbs    = array();
	private $validation_rules = array();
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

		// load configs
		$this->config->load('webmemo', TRUE);

		// load models
		$this->load->model('webmemo/category');
		$this->load->model('webmemo/memo');

		// load libraries
		$this->load->library('textile');

		// load helpers
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

		$this->is_private = IS_AUTH;

		// side_menu用カテゴリリスト
		$this->category_list_all = $this->category->get_list_all();
	}

	public function index()
	{
		$this->memolist();
	}

	public function memolist()
	{
		$page_title = 'メモ一覧';

		// uriからparamsを取得
		if ($this->uri->segment(2) == 'category') $this->category_id = $this->uri->segment(3, 0);
		if ($this->search)
		{
			$this->_format_search_param();
			$this->_decode_search_param();
		}
		list($now_category, $search_category_id_list) = $this->_get_category_lists($this->category_id);
		if ($this->category_id && !$search_category_id_list) redirect('webmemo');

		// 記事件数を取得
		$count_all = $this->memo->get_count_all($this->is_private, $this->search, $search_category_id_list);

		// SEO用 metaデータをセット
		$this->_set_site_keywords_and_description($page_title, $this->search, $now_category);

		// パンくずリスト用のデータをセット
		$this->_set_breadcrumbs($page_title, $this->search, $now_category, $count_all);

		// template
		$view_data = $this->_get_default_view_data();
		$view_data['pagination'] = $this->_get_pagination_simple($count_all);
		$view_data['count_all'] = $count_all;
		$view_data['memo_list'] =  $this->memo->get_main_list($this->is_private,
																													$this->search,
																													$search_category_id_list,
																													$this->_get_order_column_name($this->order),
																													$this->offset,
																													$this->limit);
		$view_data['cate_name_list'] = $this->site_util->convert_category_name_list($this->category_list_all);
		$view_data['search'] = $this->search;
		$view_data['order'] = $this->order;
		$view_data['order_list'] = $this->_get_order_list();
		$view_data['now_category'] = $now_category;
		$view_data['now_category_id'] = $this->category_id;
		$view_data['search_category_id'] = $this->category_id;
		$view_data['opt'] = $this->search_option;

		$this->smarty_parser->parse('ci:webmemo/'.$this->_get_template_name('list'), $view_data);
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
			$title = $memo_list[0]['title'];
			$cate_sub_id = $memo_list[0]['sub_id'];
			$now_category_name = $memo_list[0]['name'];
			$now_category_id = (int)$memo_list[0]['memo_category_id'];
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

		// template
		$view_data = $this->_get_default_view_data();
		$view_data['article_id'] = $id;
		$view_data['count_all']  = $count_all;
		$view_data['memo_list']  = $memo_list;
		$view_data['cate_name_list']  = $cate_name_list;
		$view_data['now_category'] = $now_category;
		$view_data['now_category_id'] = $now_category_id;
		$view_data['list_url'] = site_url('article/'.$id);
		$view_data['list_url_without_order'] = site_url('article/'.$id);

		$this->smarty_parser->parse('ci:webmemo/list.tpl', $view_data);
	}

	public function category()
	{
		$id = (int)$this->uri->segment(2, 0);
		$main_list = array();
		if ($id && $this->category_list_all)
		{
			list($main_list, $id) = $this->_get_now_category_and_id($this->category_list_all, $id);
		}
		if (!$main_list)
		{
			if ($id) redirect('list/category/'.$id);
			redirect();
		}

		$now_category_name = $main_list[0]['name'];

		// page description
		$this->site_keywords[]   = $now_category_name;
		$this->site_description .= sprintf('このページはカテゴリ「%s」についての記事一覧です。', $now_category_name);

		// パンくずリスト
		$this->breadcrumbs[] = array('uri' => '', 'name' => $now_category_name);

		// template
		$view_data = $this->_get_default_view_data();
		$view_data['now_category_id'] = $id;
		$view_data['main_list'] = $main_list;
		$this->smarty_parser->parse('ci:webmemo/category.tpl', $view_data);
	}

	public function search()
	{
		$this->input->check_is_post();

		$article_id  = $this->_get_params('article', 0, 'intval|less_than[999999]');

		$redirect_url = site_url('list');
		if ($article_id)
		{
			$redirect_url = site_url('article/'.$article_id);
		}
		elseif ($this->category_id)
		{
			$redirect_url = site_url('category/'.$this->category_id);
		}

		if ($this->search)
		{
			$this->_format_search_param(true);
			$redirect_url = $this->_get_list_url();

			if ($this->category_id && $this->search_option)
			{
				$redirect_url = $this->_get_list_url(true);
			}
		}	

		redirect($redirect_url);
	}

	public function feed()
	{
		$page_title = 'メモ一覧';
		$this->_set_site_keywords_and_description($page_title, '', array(), true);

		// template
		$view_data = array();
		$view_data['site_description'] = $this->site_description;
		$view_data['memo_list'] = $this->memo->get_main_list(false, '', array(), 'updated_at desc', 0, 20);
		$this->smarty_parser->parse('ci:webmemo/feed.tpl', $view_data);
	}

	private function _get_default_view_data()
	{
		return array(
			'site_keywords'    => $this->site_keywords,
			'site_description' => $this->site_description,
			'breadcrumbs' => $this->breadcrumbs,
			'search' => '',
			'pagination' => array(),
			'opt' => 0,
			'cate_list' => $this->category_list_all,
			'cate_id_list' => $this->site_util->convert_to_category_id_list($this->category_list_all),
			'cate_list_important_articles' => $this->memo->get_important_list($this->is_private),
			'foot_info' => $this->_set_footer_info(),
			'current_url' => current_url(),
			'list_url' => $this->_get_list_url($this->uri->segment(2) == 'category' ? true : false),
			'list_url_without_order' => $this->_get_list_url($this->uri->segment(2) == 'category' ? true : false, array('search', 'opt')),
		);
	}

	private function _get_template_name($filename)
	{
		if (IS_MOBILE) $filename .= '_mobile';
		$filename .= '.tpl';

		return $filename;
	}

	private function _get_now_category_and_id($all_list, $id)
	{
		$ret = array();
		if (!$all_list) return array($ret, $id);
		if (!$id) return array($all_list, 0);

		foreach ($all_list as $row)
		{
			if ($row['id'] != $id) continue;
			$sub_category_list = array();
			foreach ($row['sc_ary'] as $sub_row)
			{
				$sub_row['each_ary'] = $this->memo->get_main_list($this->is_private, '', array($sub_row['id']), 'id', 0, 0, 'A.id, A.title');
				$sub_category_list[] = $sub_row;
			}
			$row['sc_ary'] = $sub_category_list;
			$ret[] = $row;
			break;
		}
		if ($ret) return array($ret, $id);

		return array(array(), $id);
	}

	private function _get_category_lists($category_id)
	{
		$category = array();
		$category_id_list = array();
		if (!$category_id) return array($category, $category_id_list);

		$category = $this->category->get_row4id($category_id);
		if (!$category) return array($category, $category_id_list);
		$cate_sub_id = $category['sub_id'];
		$category_id_list = array($category_id);
		if ($cate_sub_id)
		{
			$category['sub_category_name'] = $this->category->get_name4id($cate_sub_id);
		}
		else
		{
			$category_id_list = $this->category->get_id_list($category_id);
		}

		return array($category, $category_id_list);
	}

	private function _set_site_keywords_and_description($page_title, $search = '', $category = array(), $is_rss = false)
	{
		$this->site_keywords[]   = $page_title;
		if (!empty($category['sub_category_name'])) $this->site_keywords[] = $category['sub_category_name'];
		if (!empty($category['name']))     $this->site_keywords[] = $category['name'];

		$site_description = sprintf('このページは「%s」のページです。', $page_title);
		if ($is_rss) $site_description = sprintf('このRSSファイルは「%s」のフィードです。', $page_title);

		if ($category && $search)
		{
			$site_description = sprintf('このページは、カテゴリ「%s」の「%s」検索結果の%sです。', $category['name'], $search, $page_title);
		}
		elseif ($search)
		{
			$site_description = sprintf('このページは「%s」検索結果の%sです。', $search, $page_title);
		}
		elseif ($category)
		{
			$site_description = sprintf('このページはカテゴリ「%s」の%sです。', $category['name'], $page_title);
		}
		$this->site_description .= $site_description;
	}

	private function _set_breadcrumbs($page_title, $search, $category, $count_all)
	{
		$this->breadcrumbs[] = array('uri' => '', 'name' =>  $page_title);
		if ($category && $search)
		{
			$this->breadcrumbs[] = array('uri' => 'list/category/'.$category['id'], 'name' => sprintf('カテゴリ「%s」', $category['name']));
			$this->breadcrumbs[] = array('uri' => '', 'name' => sprintf('「%s」の検索結果: %d件', $this->search, $count_all));
		}
		elseif ($search)
		{
			$this->breadcrumbs[] = array('uri' => '', 'name' => sprintf('「%s」の検索結果: %d件', $this->search, $count_all));
		}
		elseif ($category)
		{
			$this->breadcrumbs[] = array('uri' => '', 'name' => sprintf('カテゴリ「%s」の絞り込み結果: %d件', $category['name'], $count_all));
		}
	}

	private function _get_pagination_config($count_all)
	{
		$config = array();
		$config['base_url'] = $this->_get_list_url($this->uri->segment(2) == 'category' ? true : false, array('search', 'opt', 'order'));
		$config['offset']   = (int)$this->offset;
		$config['query_string_segment'] = 'from';
		$config['total_rows'] = $count_all;
		$config['per_page']   = $this->limit;

		return $config;
	}

	private function _get_pagination($count_all)
	{
		$config = $this->_get_pagination_config($count_all);
		$config['num_links']  = 3;
		$config['first_link'] = '&laquo;最初';
		$config['last_link'] = '最後&raquo;';

		$this->load->library('my_pager', $config);
		$this->my_pager->set_params('prev_link', sprintf('&lt;前の%d件', $this->my_pager->get_prev_page_rows()));
		$this->my_pager->set_params('next_link', sprintf('次の%d件&gt;', $this->my_pager->get_next_page_rows()));

		$this->next_url = $this->my_pager->get_next_url();

		return $this->my_pager->create_links();
	}

	private function _get_pagination_simple($count_all)
	{
		$config = $this->_get_pagination_config($count_all);
		$this->load->library('my_pager', $config);

		return $this->my_pager->get_pagination_simple_urls();
	}

	public function _format_search_param($urlencode = false)
	{
		if (!$this->search) return;

		$this->search = trim(preg_replace('/[ 　]+/u', ' ', $this->search));
		list($this->search, $category_id) = get_category_from_search_word($this->search);
		if ($category_id) $this->category_id = $category_id;
		if ($category_id) $this->search_option = true;

		if ($urlencode) $this->search = urlencode($this->search);
	}

	public function _decode_search_param()
	{
		if (!$this->search) return;

		$this->search = urldecode($this->search);
	}

	public function _get_list_url($is_category = false, $keys = array('search', 'opt', 'order', 'from'))
	{
		$uri = 'list';
		$params = array();
		if (in_array('search', $keys)) $params['search'] = $this->search;
		if (in_array('opt', $keys))    $params['opt']    = (int)$this->search_option;
		if (in_array('order', $keys))  $params['order']  = $this->order;
		if (in_array('from', $keys))  $params['from']  = $this->offset;

		if ($is_category)
		{
			$uri .= sprintf('/category/%d', $this->category_id);
		}

		return  sprintf('%s%s?%s', base_url(), $uri, http_build_query($params));
	}

	private function _get_order_column_name()
	{
		$order_list = $this->_get_order_list();
		if (empty($order_list[$this->order]['column'])) return 'updated_at DESC';

		return $order_list[$this->order]['column'];
	}

	private function _get_order_list()
	{
		return array(
			'0' => array('column' => 'updated_at DESC', 'ja_name' => '更新日順'),
			'1' => array('column' => 'id', 'ja_name' => '登録順'),
			'2' => array('column' => 'important_level', 'ja_name' => '重要度順'),
		);
	}

	private function _set_footer_info()
	{
		return '';
	}

	private function _set_params()
	{
		$this->load->library('form_validation');

		$this->limit  = $this->private_config['article_nums'];
		$this->search = $this->_get_params('search', '', 'trim|max_length[301]');
		$this->offset = $this->_get_params('from', 0, 'intval|less_than[10000000]');
		$this->order  = $this->_get_params('order', 0, 'intval|less_than[3]');
		$this->search_option = $this->_get_params('opt', 0, 'intval|less_than[2]');
		$this->category_id = $this->_get_params('category', 0, 'intval|less_than[1000000]');
	}

	private function _get_params($key, $default = NULL, $rules, $xss_clean = FALSE)
	{
		$value = $this->input->get_post($key, $xss_clean);
		if ($value === false) return $default;

		return $valid_value = $this->site_util->simple_validation($value, $default, $rules);
	}
}

/* End of file webmemo.php */
/* Location: ./application/controllers/webmemo.php */
