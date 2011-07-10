<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Task extends MY_Controller
{
	private $limit  = 10;
	private $offset = 0;
	private $order  = 0;
	private $search = '';
	private $search_option  = false;
	private $next_url;

	function __construct()
	{
		parent::__construct();

		// load models
		$this->load->model('task/model_task');

		$this->_configure();
	}

	private function _configure()
	{
		$this->private_config = $this->config->item('task');
		$this->_set_params();
	}

	private function _set_params()
	{
		$this->load->library('form_validation');

		$this->limit  = $this->private_config['article_nums']['default'];
		if (IS_MOBILE) $this->limit = $this->private_config['article_nums']['mobile'];
		$this->search = $this->_get_params('search', '', 'trim|max_length[301]');
		$this->offset = $this->_get_params('from', 0, 'intval|less_than[10000000]');
		$this->order  = $this->_get_params('order', 0, 'intval|less_than[3]');
	}

	private function _get_params($key, $default = NULL, $rules, $xss_clean = FALSE)
	{
		$value = $this->input->get_post($key, $xss_clean);
		if ($value === false) return $default;

		return $valid_value = $this->site_util->simple_validation($value, $default, $rules);
	}

	private function _get_default_view_data()
	{
		return array(
//			'breadcrumbs' => $this->breadcrumbs,
		);
	}

	public function index()
	{
		// template
		$view_data = $this->_get_default_view_data();
		$view_data['search'] = $this->search;
		$view_data['order']  = $this->order;
		$view_data['opt']    = $this->search_option;
		$view_data['from']   = $this->offset;
		$view_data['limit']  = $this->limit;

		// 記事件数を取得
		$count_all = $this->model_task->get_count_all($this->search);
		$view_data['pagination'] = $this->_get_pagination_simple($count_all);
		$view_data['count_all']  = $count_all;

		$this->smarty_parser->parse('ci:task/index.tpl', $view_data);
	}

	public function ajax_task_list()
	{
		// template
		$view_data = $this->_get_default_view_data();
		$view_data['list'] =  $this->model_task->get_main_list($this->offset, $this->limit);

		$this->smarty_parser->parse('ci:task/list.tpl', $view_data);
	}

	public function ajax_task_detail()
	{
		$id = (int)$this->uri->segment(3, 0);
		if (!$id) show_error('need id');

		$row =  $this->model_task->get_row($id);

		echo $row[0]['body'];
	}

	private function _get_pagination_simple($count_all)
	{
		$config = $this->_get_pagination_config($count_all);
		$this->load->library('my_pager', $config);

		return $this->my_pager->get_pagination_simple_urls();
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

	private function _get_pagination_config($count_all)
	{
		$config = array();
		$config['base_url'] = $this->_get_list_url(array('search', 'opt', 'order', 'from'));
		$config['offset']   = (int)$this->offset;
		$config['query_string_segment'] = 'from';
		$config['total_rows'] = $count_all;
		$config['per_page']   = $this->limit;

		return $config;
	}

	public function _get_list_url($keys = array('search', 'opt', 'order', 'from'))
	{
		$uri = 'task';
		$params = array();
		if (in_array('search', $keys)) $params['search'] = $this->search;
		if (in_array('opt', $keys))    $params['opt']    = (int)$this->search_option;
		if (in_array('order', $keys))  $params['order']  = $this->order;
		if (in_array('from', $keys))  $params['from']  = $this->offset;

		return  sprintf('%s%s?%s', base_url(), $uri, http_build_query($params));
	}

	public function execute_insert()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('body', '内容', 'trim|required');
		if (!$this->form_validation->run()) return;

		// 登録
		$this->model_task->insert(array('body' => set_value('body')));
	}

	public function execute_update()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id番号', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('value', '内容', 'trim|required');
		if (!$this->form_validation->run()) return;

		// 登録
		$this->model_task->update4id(array('body' => set_value('value')), set_value('id'));

		echo nl2br(set_value('value'));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
