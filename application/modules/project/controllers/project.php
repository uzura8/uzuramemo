<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Project extends MY_Controller
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
		$this->load->model('project/model_project');

		$this->_configure();
	}

	private function _configure()
	{
		$this->private_config = $this->config->item('project');
		$this->_set_params();
	}

	private function _set_params()
	{
		$this->load->library('form_validation');

		$this->limit  = $this->private_config['article_nums']['default'];
		if (IS_MOBILE) $this->limit = $this->private_config['article_nums']['mobile'];
		$this->search = $this->_get_post_params('search', '', 'trim|max_length[301]');
		$this->offset = $this->_get_post_params('from', 0, 'intval|less_than[10000000]');
		$this->order  = $this->_get_post_params('order', 0, 'intval|less_than[3]');
	}

	private function _get_default_view_data()
	{
		return array(
			'page_name' => $this->private_config['site_title'],
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

		// form
		$view_data['form'] = $this->_validation_rules();

		$this->smarty_parser->parse('ci:project/index.tpl', $view_data);
	}

	public function ajax_project_list()
	{
		// template
		$view_data = $this->_get_default_view_data();
		$view_data['list'] =  $this->model_project->get_main_list($this->offset, $this->limit);

		// 記事件数を取得
		$count_all = $this->model_project->get_count_all($this->search);
		$view_data['pagination'] = $this->_get_pagination_simple($count_all, 'project/ajax_project_list');
		$view_data['count_all']  = $count_all;

		$this->smarty_parser->parse('ci:project/list.tpl', $view_data);
	}

	public function ajax_project_detail($id, $item)
	{
		$id = (int)str_replace($item, '', $id);
		if (!$id) show_error('need id');

		if (!$this->_check_edit_form_item($item)) show_error('item is invalid');

		$row = $this->model_project->get_row($id);
		echo $row[0][$item];
	}

	public function ajax_check_project_name()
	{
		$this->input->check_is_post();
		$key_name = $this->_get_post_params('name');
		if (!$this->_unique_check_name($key_name))
		{
			$return =  '<span class="validate_error">重複しています</span>';
			$this->output->set_output($return);
			return;
		}

		$return =  '<span class="validate_success">登録可能</span>';
		$this->output->set_output($return);
	}

	public function ajax_check_project_key_name()
	{
		$this->input->check_is_post();
		$key_name = $this->_get_post_params('key_name');
		if (!$this->_unique_check_key_name($key_name))
		{
			$return =  '<span class="validate_error">重複しています</span>';
			$this->output->set_output($return);
			return;
		}

		$return =  '<span class="validate_success">登録可能</span>';
		$this->output->set_output($return);
	}

	public function ajax_check_project_key_name_old()
	{
		$this->input->check_is_post();
		$key_name = $this->_get_post_params('key_name');
		if (!$this->_unique_check_key_name($key_name))
		{
			$this->output->set_output('false');
			return;
		}

		$this->output->set_output('true');
	}

	private function _get_pagination_simple($count_all, $uri = '')
	{
		$config = $this->_get_pagination_config($count_all, $uri);
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

	private function _get_pagination_config($count_all, $uri = '')
	{
		$config = array();
		$config['base_url'] = $this->_get_list_url(array('search', 'opt', 'order', 'from'), $uri);
		$config['offset']   = (int)$this->offset;
		$config['query_string_segment'] = 'from';
		$config['total_rows'] = $count_all;
		$config['per_page']   = $this->limit;

		return $config;
	}

	private function _get_list_url($keys = array('search', 'opt', 'order', 'from'), $uri = '')
	{
		if (!$uri) $uri = 'project';
		$params = array();
		if (in_array('search', $keys)) $params['search'] = $this->search;
		if (in_array('opt', $keys))    $params['opt']    = (int)$this->search_option;
		if (in_array('order', $keys))  $params['order']  = $this->order;
		if (in_array('from', $keys))  $params['from']  = $this->offset;

		return  sprintf('%s%s?%s', base_url(), $uri, http_build_query($params));
	}

	private function _check_edit_form_item($item)
	{
		$allow_items = array('body', 'name', 'key_name');
		if (!$item || !in_array($item, $allow_items)) return false;

		return true;
	}

	public function execute_insert()
	{
		$this->input->check_is_post();
		$this->_setup_validation();

		if (!$this->form_validation->run())
		{
			$this->output->set_status_header('403');
			$this->output->set_output('NG');
			return;
		}

		// 登録
		$values = $this->_get_form_data();
		$this->model_project->insert($values);

		$this->output->set_output('OK');
	}

	public function execute_update($item)
	{
		$this->input->check_is_post();
		$id = $this->_get_post_params('id');
		$id = (int)str_replace($item, '', $id);
		if (!$id || !$this->_check_edit_form_item($item)) return;

		$validate_rules = $this->_validation_rules();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('value', $validate_rules[$item]['label'], $validate_rules[$item]['rules']);

		if (!$this->form_validation->run())
		{
			$data = sprintf('%s<span class="validate_error">%s</span>', hsc(set_value('value')), validation_errors());
			$this->output->set_output($data);
			return;
		}

		// 登録
		$values = array($item => set_value('value'));
		$this->model_project->update4id($values, $id);

		$this->output->set_output(nl2br(hsc(set_value('value'))));
	}

	protected function _validation_rules()
	{
		return array(
			'name' => array(
				'label' => 'プロジェクト名',
				'type'  => 'input',
				'rules' => 'trim|required|max_length[140]|callback__unique_check_name',
				'size'  => 30,
				'children' => array('key_name'),
			),
			'key_name' => array(
				'label' => 'key',
				'type'  => 'input',
				'rules' => 'trim|alpha_dash|max_length[20]|callback__unique_check_key_name',
				'size'  => 8,
			),
			'customer' => array(
				'label' => '顧客名',
				'type'  => 'input',
				'rules' => 'trim|required|max_length[140]',
				'size'  => 30,
			),
			'body' => array(
				'label' => '本文',
				'type'  => 'textarea',
				'rules' => 'trim',
				'cols'  => 60,
				'rows'  => 2,
			),
			'explanation' => array(
				'label' => '補足',
				'type'  => 'hidden',
				'rules' => 'trim',
				'cols'  => 60,
				'rows'  => 2,
			),
		);
	}

	function _unique_check_name($str)
	{
		return $this->_validate_unique_check('project', 'name', $str);
	}

	function _unique_check_key_name($str)
	{
		if (!strlen($str)) return true;

		return $this->_validate_unique_check('project', 'key_name', $str);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
