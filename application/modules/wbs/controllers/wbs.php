<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Wbs extends MY_Controller
{
	private $limit  = 10;
	private $offset = 0;
	private $order  = 0;
	private $search = '';
	private $search_option  = false;
	private $project_id = 0;
	private $edit = 0;
	private $next_url;

	function __construct()
	{
		parent::__construct();

		// load models
		$this->load->model('program/model_program');
		$this->load->model('project/model_project');
		$this->load->model('wbs/model_work_class');

		// load config
		$this->config->load('program', true);
		$this->config->load('project', true);
		$this->config->load('gantt', true);

		$this->_configure();
	}

	private function _configure()
	{
		$this->private_config = $this->config->item('wbs');
		$this->_set_params();
	}

	private function _set_params()
	{
		$this->load->library('form_validation');

		$this->limit  = $this->private_config['article_nums']['default'];
		if (IS_MOBILE) $this->limit = $this->private_config['article_nums']['mobile'];
		$this->search = $this->_get_post_params('search', '', 'trim|max_length[301]');
		$this->offset = $this->_get_post_params('from', 0, 'intval|less_than[10000000]');
		$this->project_id = $this->_get_post_params('project_id', 0, 'intval');
		$this->edit   = $this->_get_post_params('edit', 0, 'intval|is_natural|less_than[2]');

		$options = $this->_get_form_dropdown_options_order();// select:order
		$this->order  = $this->_get_post_params('order', 0, sprintf('intval|less_than[%d]', count($options)));
	}

	private function _get_default_view_data()
	{
		$view_data = $this->default_view_data;
		$view_data += array(
			'page_name' => $this->private_config['site_title'],
			'selected_select_order' => 0,
		);

		$site_url = site_url();
		$view_data['head_info'] = <<<EOL
<link rel="stylesheet" href="{$site_url}css/jquery-ui-1.8.14.custom.css">
<link rel="stylesheet" href="{$site_url}css/jquery-ui-calendar.custom.css">
<link rel="stylesheet" href="{$site_url}css/ui.theme.css">

EOL;

		return $view_data;
	}

	public function index($project_key = '')
	{
		if (!$project_key) show_404();
		if (preg_match('/[0-9]+/', $project_key))
		{
			$project_id = (int)$project_key;
			$project = $this->model_project->get_row_full(array('A.id' => $project_id), 'A.id, A.name, B.name as program_name, B.key_name as program_key');
		}
		else
		{
			$project = $this->model_project->get_row_full(array('A.key_name' => $project_key), 'A.id, A.name, B.name as program_name, B.key_name as program_key');
		}
		if (!$project) show_404();

		// template
		$view_data = $this->_get_default_view_data();
		$view_data['page_title'] = $this->private_config['site_title'].'一覧';
		$view_data['page_subtitle'] = $project['name'];
		$view_data['page_subtitle_parts'] = array(
			array('url' => site_url('project/index/'.$project['program_key']), 'subtitle' => $project['program_name']),
			array('url' => '', 'subtitle' => $project['name']),
		);

		$this->project_id = (int)$project['id'];
		$view_data['project_id'] = $this->project_id;
		$view_data['project_key'] = $project_key;

		// request parameter
		$view_data['search'] = $this->search;
		$view_data['order']  = $this->order;
		$view_data['opt']    = $this->search_option;
		$view_data['from']   = $this->offset;
		$view_data['limit']  = $this->limit;
		$view_data['edit']   = $this->edit;

		// form
		$validation_rules = $this->_validation_rules();// form
		$validation_rules['project_id']['value'] = $this->project_id;
		$view_data['form'] = $validation_rules;

		// select:order
		$options = $this->_get_form_dropdown_options_order();// select:order
		$view_data['form_dropdown_list'] = array();
		$view_data['form_dropdown_list']['order'] = array('options' => $options);

		$this->smarty_parser->parse('ci:wbs/index.tpl', $view_data);
	}

	public function ajax_wbs_list()
	{
		// template
		$view_data = $this->_get_default_view_data();

		$params = array('sql' => array(), 'values' => array());
		if ($this->project_id)
		{
			$params['sql'][]    = 'A.project_id = ?';
			$params['values'][] = $this->project_id;
		}
		$view_data['list'] =  $this->model_wbs->get_main_list($this->offset, $this->limit, $this->_get_order_sql_clause(), '', true, 'A.*, B.name as project_name, C.name as program_name', $params);

		// 記事件数を取得
		$count_all = $this->model_wbs->get_count_all($this->search, true, $params);
		$uri = 'wbs/ajax_wbs_list';
		if ($this->project_id) $uri .= '?project_id='.$this->project_id;
		$view_data['pagination'] = $this->_get_pagination_simple($count_all, $uri);
		$view_data['count_all']  = $count_all;
		$view_data['max_page'] = ceil($count_all / $this->limit);

		$this->smarty_parser->parse('ci:wbs/list.tpl', $view_data);
	}

	public function ajax_wbs_list_mainenu($project_id = '')
	{
		if (!$project_id = (int)$project_id)
		{
			$this->output->set_ajax_output_error();
			return;
		}
		// template
		$view_data = array();
		$view_data['list'] = $this->db_util->get_rows('wbs', array('project_id' => $project_id), array('id', 'name'), 'sort', 'wbs', 'model');

		$this->smarty_parser->parse('ci:wbs/list_mainmenu.tpl', $view_data);
	}

	public function ajax_wbs_detail($id, $item)
	{
		$id = (int)str_replace($item, '', $id);
		if (!$id) show_error('need id');

		if (!$this->_check_edit_form_item($item)) show_error('item is invalid');

		$row = $this->model_wbs->get_row($id);
		echo $row[0][$item];
	}

	public function ajax_check_wbs_name()
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

	public function ajax_check_wbs_key_name()
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
		if ($this->model_wbs->get_del_flg4id($id)) $del_flg_after = 0;

		$this->model_wbs->update4id(array('del_flg' => $del_flg_after), $id, false);

		$this->output->set_output($del_flg_after);
	}

	public function ajax_execute_update_importance()
	{
		$this->input->check_is_post();
		$id = (int)$this->_get_post_params('id');
		if (!$id)
		{
			$this->output->set_status_header('403');
			return;
		}

		$importance_after = 1;
		if ($this->db_util->get_col4id('wbs', $id, 'importance', 'wbs', 'model')) $importance_after = 0;

		$values = array('importance' => $importance_after);
		$this->model_wbs->update4id($values, $id, false);

		$this->output->set_output($importance_after);
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
		$row = $this->model_wbs->get_row_common(array('id' => $id));
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
		if (!$this->model_wbs->update4id($values, $id))
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$this->output->set_output('true');
	}

	public function ajax_execute_update_sort_move($id_string = '')
	{
		$this->input->check_is_post();
		$ids_pre = explode(',', $this->_get_post_params('values'));
		$ids = array();
		$sorts = array();
		foreach ($ids_pre as $id_pre)
		{
			if ($id_string) $id_pre = str_replace($id_string, '', $id_pre);
			$id = (int)$id_pre;
			if (!$id) continue;
			$ids[] = $id;
		}
		unset($ids_pre);

		$sorts = $this->db_util->get_cols('wbs', array('id' => $ids), 'sort', 'sort', 'wbs', 'model');
		$values = array_combine($ids, $sorts);

		// まとめて更新
		$result_count = 0;
		$error = false;
		$this->db->trans_begin();
		foreach ($values as $id => $sort)
		{
			$result = $this->db_util->update('wbs', array('sort' => $sort), array('id' => $id), false, 'wbs', 'model');
			if (!$result)
			{
				$error = true;
				break;
			}
			$result_count++;
		}

		if ($this->db->trans_status() === FALSE || $error)
		{
			$this->db->trans_rollback();
			$result_count = 0;
		}
		else
		{
			$this->db->trans_commit();
		}

		if (!$result_count)
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$this->output->set_output('true');
	}

	public function ajax_execute_update_common()
	{
		$this->input->check_is_post();
		$id = (int)$this->_get_post_params('id');

		$key = $this->_get_post_params('key');
		$allow_keys = array('sort', 'start_date', 'due_date', 'estimated_time', 'spent_time', 'percent_complete');
		if (!$id || !in_array($key, $allow_keys))
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$validate_rules = $this->_validation_rules();
		$this->form_validation->set_rules('value', $validate_rules[$key]['label'], $validate_rules[$key]['rules']);
		$result = $this->form_validation->run();

		// 値に変更がない場合はそのまま
		$row = $this->model_wbs->get_row_common(array('id' => $id));
		if ($row[$key] == set_value('value'))
		{
			return;
		}

		if (!$result)
		{
			$this->output->set_ajax_output_error();
			return;
		}
/*
		// 期日内に収まっているか
		if ($key == 'start_date' || $key == 'estimated_time')
		{
			if ($key == 'start_date')
			{
				$start_date     = set_value('value');
				$estimated_time = $this->db_util->get_col4id('wbs', $id, 'estimated_time', 'wbs', 'model');
			}
			elseif ($key == 'estimated_time')
			{
				$start_date     = $this->db_util->get_col4id('wbs', $id, 'start_date', 'wbs', 'model');
				$estimated_time = set_value('value');
			}

			$due_date    = $this->db_util->get_col4id('wbs', $id, 'due_date', 'wbs', 'model');
			$holidays    = $this->date_util->get_holidays_from_range($start_date, $estimated_time + 30);
			$finish_date = $this->date_util->get_finish_date($start_date, ceil($estimated_time), $holidays);
			if ($this->date_util->conv2int($finish_date) > $this->date_util->conv2int($due_date))
			{
				$this->output->set_output('完了日が期日を超えています');
				return;
			}
		}
*/
		// 登録
		$values = array($key => set_value('value'));
		if (!$this->model_wbs->update4id($values, $id))
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$this->output->set_output('true');
	}

	public function ajax_execute_update_date()
	{
		$this->input->check_is_post();
		$id  = (int)$this->_get_post_params('id');
		if (!$id)
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$validate_rules = $this->_validation_rules();
		$this->form_validation->set_rules('start_date', $validate_rules['start_date']['label'], $validate_rules['start_date']['rules']);
		$this->form_validation->set_rules('due_date', $validate_rules['due_date']['label'], $validate_rules['due_date']['rules']);
		$result = $this->form_validation->run();

		// 値に変更がない場合はそのまま
		$row = $this->model_wbs->get_row_common(array('id' => $id));
		if ($row['start_date'] == set_value('start_date') && $row['due_date'] == set_value('due_date'))
		{
			return;
		}

		if (!$result)
		{
			$this->output->set_ajax_output_error();
			return;
		}

		// 登録
		$values = array('start_date' => set_value('start_date'), 'due_date' => set_value('due_date'));
		if (!$ret = $this->model_wbs->update4id($values, $id))
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$return = '';
		if ($values['due_date'])
		{
			$return = array();
			$return['rest_days'] = site_convert_due_date($values['due_date'], 'rest_days');
			$styles = site_convert_due_date($values['due_date'], 'style');
			$return['styles'] = $this->strings_util->convert_style2array($styles, true);
			$return = json_encode($return);
		}

		$this->output->set_output($return);
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

		if (!$this->model_wbs->delete4id($id))
		{
			$this->output->set_status_header('403');
			return;
		}

		$this->output->set_output('true');
	}

	public function ajax_get_wbs_key_name()
	{
		$this->input->check_is_post();
		$project_id = (int)$this->_get_post_params('id');
		if (!$project_id)
		{
			$this->output->set_status_header('403');
			return;
		}

		$row = $this->model_project->get_row_common(array('id' => $project_id));
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
		if (!$uri) $uri = 'wbs';
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
		$allow_items = array('body', 'name', 'key_name', 'due_date', 'estimated_time');
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
		$this->model_wbs->insert($values);

		$this->output->set_output('OK');
	}

	public function execute_update($item)
	{
		$this->input->check_is_post();
		$id = $this->_get_post_params('id');
		$prefix = $this->_get_post_params('prefix', '');
		if ($prefix) $id = str_replace($prefix, '', $id);
		$id = (int)str_replace($item, '', $id);
		if (!$id || !$this->_check_edit_form_item($item)) return;

		$validate_rules = $this->_validation_rules();
		$this->form_validation->set_rules('value', $validate_rules[$item]['label'], $validate_rules[$item]['rules']);

		$result = $this->form_validation->run();

		// 値に変更がない場合はそのまま
		$row = $this->model_wbs->get_row_common(array('id' => $id));
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
		$this->model_wbs->update4id($values, $id);

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
			'name' => array(
				'label' => 'WBS名',
				'type'  => 'text',
				'rules' => 'trim|required|max_length[140]',
				'width'  => 30,
			),
			'work_class_id' => array(
				'label' => '作業分類',
				'type'  => 'select',
				'rules' => 'trim|is_natural_no_zero|callback__is_registered_work_class_id',
				'error_messages'  => array('min' => ''),
				'width'  => 30,
				'options' => $this->_get_dropdown_options_work_class_id(),
			),
			'start_date' => array(
				'label' => '開始日',
				'type'  => 'text',
				'rules' => 'trim|date_format',
				'width'  => 10,
				'children' => array('due_date'),
			),
			'due_date' => array(
				'label' => '期日',
				'type'  => 'text',
				'rules' => 'trim|date_format',
				'width'  => 10,
			),
			'estimated_time' => array(
				'label' => '見積工数',
				'type'  => 'text',
				'rules' => 'trim|numeric',
				'width'  => 10,
				'after_label' => '人日',
			),
			'spent_time' => array(
				'label' => '実績工数',
				'type'  => 'text',
				'rules' => 'trim|numeric',
				'width'  => 10,
				'disabled_for_insert'  => true,
				'after_label' => '人日',
			),
			'percent_complete' => array(
				'label' => '進捗率',
				'type'  => 'text',
				'rules' => 'trim|is_natural|max_num[100]',
				'width'  => 10,
				'disabled_for_insert'  => true,
				'after_label' => '%',
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
				'disabled_for_insert'  => true,
			),
			'project_id' => array(
				'label' => 'プロジェクト',
				'type'  => 'hidden',
				'rules' => 'trim|required|is_natural_no_zero|callback__is_registered_project_id',
				'error_messages'  => array('min' => ''),
				'width'  => 30,
			),
		);
	}

	function _get_dropdown_options_work_class_id()
	{
		$return = array();
		$return['0'] = '選択してください';
		$rows = $this->model_work_class->get_main_list();
		$return += $this->db_util->convert2assoc($rows);

		return $return;
	}

	function _unique_check_name($str)
	{
		return $this->_validate_unique_check('wbs', 'name', $str);
	}

	function _unique_check_key_name($str)
	{
		if (!strlen($str)) return true;

		return $this->_validate_unique_check_key_name_wbs($str);
	}

	protected function _validate_unique_check_key_name_wbs($value)
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
		if (!$this->model_project->get_row_common(array('key_name' => $parent_key)))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		$error_message = 'その %s は既に登録されています';
		if ($this->model_wbs->get_row_common(array('key_name' => $value)))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		return true;
	}

	public function _is_registered_project_id($value)
	{
		$validation_name = '_is_registered_project_id';
		$error_message = 'プロジェクトIDが正しくありません';

		if (!intval($value))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		// idの存在確認
		if (!$this->db_util->get_row4id('project', $value, array('id'), 'project', 'model'))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		return true;
	}

	public function _is_registered_work_class_id($value)
	{
		$validation_name = '_is_registered_work_class_id';
		$error_message = '作業分類が正しくありません';

		if (!intval($value))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		// idの存在確認
		if (!$this->db_util->get_row4id('work_class', $value, array('id'), 'wbs', 'model'))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		return true;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
