<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Project extends MY_Controller
{
	private $limit  = 10;
	private $offset = 0;
	private $order  = 0;
	private $search = '';
	private $search_option  = false;
	private $program_id = 0;
	private $edit = 0;
	private $next_url;

	function __construct()
	{
		parent::__construct();

		// load models
		$this->load->model('project/model_project');
		$this->load->model('program/model_program');

		// load config
		$this->config->load('program', true);

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
		$this->program_id = $this->_get_post_params('program_id', 0, 'intval');
		$this->edit   = $this->_get_post_params('edit', 0, 'intval|is_natural|less_than[2]');

		$options = $this->_get_form_dropdown_options_order();// select:order
		$this->order  = $this->_get_post_params('order', 0, sprintf('intval|less_than[%d]', count($options)));
	}

	private function _get_default_view_data()
	{
		return array(
			'page_name' => $this->private_config['site_title'],
			'selected_select_order' => 4,
		);
	}

	public function index($program_key = '')
	{
		// template
		$view_data = $this->_get_default_view_data();
		$view_data['page_title'] = $this->private_config['site_title'].'一覧';

		// request parameter
		$view_data['search'] = $this->search;
		$view_data['order']  = $this->order;
		$view_data['opt']    = $this->search_option;
		$view_data['from']   = $this->offset;
		$view_data['limit']  = $this->limit;
		$view_data['edit']   = $this->edit;

		$validation_rules = $this->_validation_rules();// form
		$options = $this->_get_form_dropdown_options_order();// select:order

		// program 指定時
		if ($program_key)
		{
			$program = $this->model_program->get_row_common(array('key_name' => $program_key));
			$this->program_id = (int)$program['id'];
			$view_data['page_subtitle'] = $program['name'];

			$validation_rules['program_id']['type'] = 'hidden';
			$validation_rules['program_id']['value'] = $this->program_id;
			$validation_rules['program_id']['options'] = array();
			$validation_rules['key_name']['value'] = $program_key.'_';

			$view_data['selected_select_order'] = 0;
			array_pop($options);
		}
		$view_data['program_key'] = $program_key;
		$view_data['program_id'] = $this->program_id;

		// form
		$view_data['form'] = $validation_rules;

		// select:order
		$view_data['form_dropdown_list'] = array();
		$view_data['form_dropdown_list']['order'] = array('options' => $options);

		$this->smarty_parser->parse('ci:project/index.tpl', $view_data);
	}

	public function ajax_project_list()
	{
		// template
		$view_data = $this->_get_default_view_data();
		$view_data['list'] =  $this->model_project->get_main_list($this->offset, $this->limit, $this->_get_order_sql_clause(), '', $this->program_id, true, 'A.*, B.name as program_name');

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
			$this->output->set_output('false');
			return;
		}

		$this->output->set_output('true');
	}

	public function ajax_check_project_key_name()
	{
		$this->input->check_is_post();
		$key_name = $this->_get_post_params('key_name');

		$validate_rules = $this->_validation_rules();
		$this->form_validation->set_rules('key_name', $validate_rules['key_name']['label'], $validate_rules['key_name']['rules']);
		$this->form_validation->set_error_delimiters('', '');

		$result = $this->form_validation->run();
		if (!$result)
		{
			$this->output->set_output(trim(validation_errors()));
			return;
		}

		$this->output->set_output('true');
	}

	public function ajax_execute_update_del_flg()
	{
		$this->input->check_is_post();
		$id = (int)$this->_get_post_params('id');
		if (!$id)
		{
			$this->output->set_status_header('403');
			return;
		}

		$del_flg_after = 1;
		if ($this->model_project->get_del_flg4id($id)) $del_flg_after = 0;

		$this->model_project->update4id(array('del_flg' => $del_flg_after), $id, false);

		$this->output->set_output($del_flg_after);
	}

	public function ajax_execute_update_sort()
	{
		$this->input->check_is_post();
		$id = (int)$this->_get_post_params('id');

		if (!$id)
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$this->form_validation->set_rules('value', '並び順', 'trim|integer');
		$result = $this->form_validation->run();

		// 値に変更がない場合はそのまま
		$row = $this->model_project->get_row_common(array('id' => $id));
		if ($row['sort'] == set_value('value'))
		{
			return;
		}

		if (!$result)
		{
			$this->output->set_ajax_output_error();
			return;
		}

		// 登録
		$values = array('sort' => set_value('value'));
		if (!$this->model_project->update4id($values, $id))
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$this->set_output('true');
	}

	public function ajax_execute_delete()
	{
		$this->input->check_is_post();
		$id = (int)$this->_get_post_params('id');
		if (!$id)
		{
			$this->output->set_status_header('403');
			return;
		}

		if (!$this->model_project->delete4id($id))
		{
			$this->output->set_status_header('403');
			return;
		}

		$this->output->set_output('true');
	}

	public function ajax_get_project_key_name()
	{
		$this->input->check_is_post();
		$program_id = (int)$this->_get_post_params('id');
		if (!$program_id)
		{
			$this->output->set_status_header('403');
			return;
		}

		$row = $this->model_program->get_row_common(array('id' => $program_id));
		if (!$key_name = $row['key_name'])
		{
			$this->output->set_status_header('403');
			return;
		}

		$this->output->set_output($key_name);
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

	private function _get_order_sql_clause()
	{
		$value = $this->order;
		$dropdown_options = $this->_get_form_dropdown_options_order(true);
		if (empty($dropdown_options[$value]['sql_clause_order']))
		{
			$value = '0';
		}

		return $dropdown_options[$value]['sql_clause_order'];
	}

	private function _check_edit_form_item($item)
	{
		$allow_items = array('body', 'name', 'key_name', 'due_date');
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
		$this->form_validation->set_rules('value', $validate_rules[$item]['label'], $validate_rules[$item]['rules']);

		$result = $this->form_validation->run();

		// 値に変更がない場合はそのまま
		$row = $this->model_project->get_row_common(array('id' => $id));
		if ($row[$item] == set_value('value'))
		{
			$this->output->set_output(set_value('value'));
			return;
		}

		if (!$result)
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

	protected function _get_form_dropdown_options_order($is_full_settings = false)
	{
		$settings = array(
			'0' => array(
				'display' => '並び順',
				'sql_clause_order' => 'A.sort',
			),
			'1' => array(
				'display' => '新着順',
				'sql_clause_order' => 'A.updated_at DESC',
			),
			'2' => array(
				'display' => '登録順',
				'sql_clause_order' => 'A.id',
			),
			'3' => array(
				'display' => '期限順',
				'sql_clause_order' => 'A.due_date',
			),
			'4' => array(
				'display' => 'プログラム順',
				'sql_clause_order' => 'B.name, A.sort',
			),
		);
		if ($is_full_settings) return $settings;

		$return = array();
		foreach ($settings as $value => $setting)
		{
			$return[$value] = $setting['display'];
		}
		unset($settings);

		return $return;
	}

	protected function _validation_rules()
	{
		return array(
			'program_id' => array(
				'label' => 'プログラム',
				'type'  => 'select',
				'rules' => 'trim|required|is_natural_no_zero|callback__is_registered_program_id',
				'error_messages'  => array('min' => ''),
				'width'  => 30,
				'options' => $this->_get_dropdown_options_program_id(),
			),
			'name' => array(
				'label' => 'プロジェクト名',
				'type'  => 'text',
				'rules' => 'trim|required|max_length[140]|callback__unique_check_name',
				'width'  => 30,
				'children' => array('key_name'),
				'realtime_validation'  => true,
			),
			'key_name' => array(
				'label' => 'key',
				'type'  => 'text',
				'rules' => 'trim|required|alpha_dash|max_length[20]|callback__unique_check_key_name',
				'custom_rules' => 'key_name_child',
				'width'  => 10,
				'realtime_validation'  => true,
			),
			'due_date' => array(
				'label' => '期日',
				'type'  => 'text',
				'rules' => 'trim|date_format',
				'width'  => 10,
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

	function _get_dropdown_options_program_id()
	{
		$return = array();
		$return['0'] = '選択してください';
		$rows = $this->model_program->get_main_list(0, 0, 'sort', '', array(), false, array('id', 'name'));
		$return += $this->db_util->convert2assoc($rows);

		return $return;
	}

	function _unique_check_name($str)
	{
		return $this->_validate_unique_check('project', 'name', $str);
	}

	function _unique_check_key_name($str)
	{
		if (!strlen($str)) return true;

		return $this->_validate_unique_check_key_name_project($str);
	}

	protected function _validate_unique_check_key_name_project($value)
	{
		$validation_name = '_unique_check_key_name';
		$error_message = '正しくありません';

		// 形式確認
		if (!preg_match('/^([0-9a-zA-Z]+)_[0-9a-zA-Z]{1}[0-9a-zA-Z_]+[0-9a-zA-Z]{1}$/', $value, $matches))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}
		$parent_key = $matches[1];

		// 親側のkey確認
		if (!$this->model_program->get_row_common(array('key_name' => $parent_key)))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		$error_message = 'その %s は既に登録されています';
		if ($this->model_project->get_row_common(array('key_name' => $value)))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		return true;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
