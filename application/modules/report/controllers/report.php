<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends MY_Controller
{
	private $limit  = 10;
	private $offset = 0;
	private $order  = 0;
	private $search = '';
	private $search_option  = false;
	private $program_id = 0;
	private $project_id = 0;
	private $next_url;
	private $date_from = '';
	private $range = 60;
	private $work_class_ids = 0;

	function __construct()
	{
		parent::__construct();

		// load models
		//$this->load->model('wbs/model_holiday');
		$this->load->model('program/model_program');
		$this->load->model('project/model_project');
		$this->load->model('wbs/model_work_class');
		$this->load->model('activity/model_activity');

		// load config
		$this->config->load('program', true);
		$this->config->load('project', true);
		$this->config->load('wbs', true);

		// helper
		//$this->load->helper('gantt');

		$this->_configure();
	}

	private function _configure()
	{
		//$this->private_config = $this->config->item('gantt');
		$this->_set_params();
	}

	private function _set_params()
	{
		$this->load->library('form_validation');

		//$this->limit  = $this->private_config['article_nums']['default'];
		if (IS_MOBILE) $this->limit = $this->private_config['article_nums']['mobile'];
		$this->search = $this->_get_post_params('search', '', 'trim|max_length[301]');
		$this->offset = $this->_get_post_params('from', 0, 'intval|less_than[10000000]');
		$this->program_id = $this->_get_post_params('program_id', 0, 'intval');

		$validate_rules = $this->_validation_rules();
		$this->range = $this->_get_post_params('range', $validate_rules['range']['default_value'], $validate_rules['range']['rules']);
		$this->date_from = $this->_get_post_params('date_from', $validate_rules['date_from']['default_value'], $validate_rules['date_from']['rules']);
		if (!$this->date_from) $this->date_from = date('Y-m-d');
		$this->work_class_ids = $this->_get_post_params('work_class_id', $validate_rules['work_class_id']['default_value'], $validate_rules['work_class_id']['rules']);
		$this->program_id = $this->_get_post_params('program_id', $validate_rules['program_id']['default_value'], $validate_rules['program_id']['rules']);
		$this->project_id = $this->_get_post_params('project_id', $validate_rules['project_id']['default_value'], $validate_rules['project_id']['rules']);

		$options = $this->_get_form_dropdown_options_order();// select:order
		$this->order  = $this->_get_post_params('order', 0, sprintf('intval|less_than[%d]', count($options)));
	}

	private function _get_default_view_data()
	{
		return array(
//			'page_name' => $this->private_config['site_title'],
			'selected_select_order' => 0,
		);
	}

	public function index()
	{
		$period = (int)$this->_get_post_params('period', 14);
		$from_date = $this->_get_post_params('from_date', null, 'date_format');
		$to_date = $this->_get_post_params('to_date', null, 'date_format');
		if (!$from_date) $from_date = date('Y-m-d', strtotime('- 14 days'));
		if (!$to_date) $to_date = date('Y-m-d', strtotime(sprintf('%s +%d days', $from_date, $period)));

		$params = array('sql' => array(), 'values' => array());
		$params['sql'][] = 'A.scheduled_date >= ?';
		$params['values'][] = $from_date;
		$params['sql'][] = 'A.scheduled_date <= ?';
		$params['values'][] = $to_date;

		$list = $this->model_activity->get_main_list(0, 0, 'D.sort, C.sort, B.sort, A.scheduled_date', '', true,
			'A.*, B.name as wbs_name, C.name as project_name, C.key_name as project_key_name, C.id as project_id, D.name as program_name, D.key_name as program_key_name, D.color, D.background_color, D.id as program_id', $params);
		$res = array();
		$program_names = array();
		$project_names = array();
		$wbs_names = array();
		$project_estimated_times = array();
		$project_spent_times = array();
		foreach ($list as $row)
		{
			$program_id = $row['program_id'];
			$project_id = $row['project_id'];
			$wbs_id = $row['wbs_id'];
			$id = $row['id'];
			if (!isset($program_id_before) || $program_id_before != $program_id)
			{
				$res[$program_id] = array();
				$program_names[$program_id] = $row['program_name'];
			}
			if (!isset($project_id_before) || $project_id_before != $project_id)
			{
				$res[$program_id][$project_id] = array();
				$project_names[$project_id] = $row['project_name'];
				$project_estimated_times[$project_id] = 0;
				$project_spent_times[$project_id] = 0;
			}
			if (!isset($wbs_id_before) || $wbs_id_before != $wbs_id)
			{
				$res[$program_id][$project_id][$wbs_id] = array();
				$wbs_names[$wbs_id] = $row['wbs_name'];
			}
			$res[$program_id][$project_id][$wbs_id][$id] = $row;
			$project_estimated_times[$project_id] += $row['estimated_time'];
			$project_spent_times[$project_id] += $row['spent_time'];

			$wbs_id_before = $wbs_id;
			$project_id_before = $project_id;
			$program_id_before = $program_id;
		}
		arsort($project_spent_times);
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$isActive = 0;
$isExit   = 1;
$isEcho   = 1;
$is_html  = 1;
$isAdd    = 1;
$a = '';
if ($isActive) {$fhoge = "/tmp/test.log";$_type = 'wb';if ($isAdd) $_type = 'a';$fp = fopen($fhoge, $_type);ob_start();if ($is_html) echo '<pre>';
//if () var_dump(__LINE__, $a);// !!!!!!!
//var_dump(__LINE__, $e->getMessage());// !!!!!!!
//var_dump(__LINE__, $res, $program_names, $project_names, $wbs_names);// !!!!!!!
var_dump(__LINE__, $project_spent_times, $project_spent_times);// !!!!!!!
if ($is_html) echo '</pre>';$out=ob_get_contents();fwrite( $fp, $out . "\n" );ob_end_clean();fclose( $fp );if ($isEcho) echo $out;if ($isExit) exit;}
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

		// template
		$view_data = $this->_get_default_view_data();
		$view_data['from_date'] = $from_date;
		$view_data['to_date'] = $to_date;
		$view_data['project_estimated_times'] = $project_estimated_times;
		$view_data['project_spent_times'] = $project_spent_times;
		$view_data['project_estimated_times_sum'] = array_sum($project_estimated_times);
		$view_data['project_spent_times_sum'] = array_sum($project_spent_times);
		$view_data['list'] = $res;
		$view_data['program_names'] = $program_names;
		$view_data['project_names'] = $project_names;
		$view_data['wbs_names'] = $wbs_names;
		////$view_data['page_title'] = $this->private_config['site_title'];
		////$view_data['head_info'] = $this->_get_work_class_style();

		//if ($project_key && $project = $this->model_project->get_row_full(array('A.key_name' => $project_key), 'A.id, A.name, B.name as program_name, B.key_name as program_key'))
		//{
		//	$view_data['page_subtitle'] = $project['name'];
		//	$view_data['page_subtitle_parts'] = array(
		//		array('url' => site_url('project/index/'.$project['program_key']), 'subtitle' => $project['program_name']),
		//		array('url' => '', 'subtitle' => $project['name']),
		//	);
		//	$this->project_id = (int)$project['id'];
		//	$view_data['project_id'] = $this->project_id;
		//	$view_data['project_key'] = $project_key;
		//}

		//// request parameter
		//$view_data['search'] = $this->search;
		//$view_data['order']  = $this->order;
		//$view_data['opt']    = $this->search_option;
		//$view_data['from']   = $this->offset;
		//$view_data['limit']  = $this->limit;

		//// form
		//$validation_rules = $this->_validation_rules();// form
		//$validation_rules['project_id']['value'] = $this->project_id;
		//$view_data['form'] = $validation_rules;

		//// select:order
		//$options = $this->_get_form_dropdown_options_order();// select:order
		//$view_data['form_dropdown_list'] = array();
		//$view_data['form_dropdown_list']['order'] = array('options' => $options);

		////$this->smarty_parser->parse('ci:gantt/index.tpl', $view_data);
		$this->load->view('report/index', $view_data);
	}

	public function ajax_gantt_list()
	{
		// template
		$view_data = $this->_get_default_view_data();

		// 休日情報
		$holidays = $this->date_util->get_holidays_from_range($this->date_from, $this->range);

		$day_list = array();
		$month = '';
		$month_before = '';
		$today = date('Y-m-d');
		for ($i = 0; $i < $this->range; $i++)
		{
			$key = date('Y-m-d', strtotime(sprintf('+%d days %s', $i, $this->date_from)));
			$time = strtotime($key);
			$values = array();

			// month
			$values['month'] = '';
			$month = date('n', $time);
			if (!$month || $month != $month_before) $values['month'] = $month;
			$month_before = $month;
			// day
			$values['day'] = date('j', $time);
			// week
			$values['week'] = date('w', $time);

			// today
			$values['is_today'] = false;
			if ($key == $today) $values['is_today'] = true;

			// holiday
			$values['holiday'] = '';
			if (!empty($holidays[$key])) $values['holiday'] = $holidays[$key];

			$day_list[$key] = $values;
		}
		$view_data['day_list']  = $day_list;
		$view_data['holidays']  = $holidays;

		$params = array('sql' => array(), 'values' => array());
		$params['sql'][]    = 'C.private_flg = ?';
		$params['values'][] = 0;
		$params['sql'][]    = 'A.del_flg = ?';
		$params['values'][] = 0;
		$params['sql'][]    = 'B.del_flg = ?';
		$params['values'][] = 0;
		$params['sql'][]    = 'C.del_flg = ?';
		$params['values'][] = 0;
		//$params['sql'][]    = 'A.due_date is not NULL';
		if ($this->project_id)
		{
			$params['sql'][]    = 'A.project_id = ?';
			$params['values'][] = $this->project_id;
		}
		if (!$this->project_id && $this->program_id)
		{
			$params['sql'][]    = 'B.program_id = ?';
			$params['values'][] = $this->program_id;
		}
		if ($this->work_class_ids)
		{
			list($sql, $values) = $this->db_util->get_where_clauses('A.work_class_id', $this->work_class_ids);
			if ($sql)
			{
				$params['sql'] = array_merge($params['sql'], $sql);
				$params['values'] = array_merge($params['values'], $values);
			}
		}
		$list = $this->model_wbs->get_main_list($this->offset,
																						$this->limit,
																						'C.sort, B.sort, A.due_date, A.sort',
																						//$this->_get_order_sql_clause(),
																						'',
																						true,
																						'B.name as project_name, B.key_name as project_key_name, '
																					. 'C.name as program_name, C.key_name as program_key_name, D.name as work_class_name, A.*',
																						$params);
		$view_data['list'] = array();
		foreach ($list as $row)
		{
			$row['finish_date'] = $this->date_util->get_finish_date($row['start_date'], ceil($row['estimated_time']), $holidays);
			$view_data['list'][] = $row;
		}
		unset($list);
/*
		$gantt_list = array();
		foreach ($view_data['list'] as $row)
		{
			$key = 'wbs_'.$row['id'];
			$values = array(
				'estimated_time' => $row['estimated_time'],
				'spent_time' => $row['spent_time'],
				'percent_complete' => $row['percent_complete'],
				'start_date' => $row['start_date'],
				'due_date' => $row['due_date'],
			);
			$gantt_list[$key] = $values;
		}
		$view_data['gantt_list'] =  $gantt_list;
*/
		// 記事件数を取得
		$count_all = $this->model_wbs->get_count_all($this->search, $this->project_id, true, $params);
		$view_data['pagination'] = $this->_get_pagination_simple($count_all, 'gantt/ajax_gantt_list');
		$view_data['count_all']  = $count_all;

		$view_data['head_info'] = '';

		//$this->smarty_parser->parse('ci:gantt/list.tpl', $view_data);
		$this->load->view('gantt/list', $view_data);
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
		if (!$uri) $uri = 'gantt';
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
			'program_id' => array(
				'label' => get_config_value('site_title', 'program'),
				'type'  => 'select',
				'rules' => 'trim|is_natural_no_zero',
				'error_messages'  => array('min' => ''),
				'width'  => 30,
				'options' => $this->_get_dropdown_options_program_id(),
				'default_value' => 0,
				//'children' => array('key_name'),
			),
			'project_id' => array(
				'label' => get_config_value('site_title', 'project'),
				'type'  => 'select',
				'rules' => 'trim|is_natural_no_zero',
				'error_messages'  => array('min' => ''),
				'width'  => 30,
				'options' => $this->_get_dropdown_options_project_id(),
				'default_value' => 0,
			),
			'work_class_id' => array(
				'label' => '作業分類',
				'type'  => 'multiselect',
				'rules' => 'is_natural_no_zero',
				'error_messages'  => array('min' => ''),
				'width'  => 30,
				'options' => $this->_get_dropdown_options_work_class_id(),
				'default_value' => 0,
				'size'  => 3,
			),
			'date_from' => array(
				'label' => '始点',
				'type'  => 'text',
				'rules' => 'trim|date_format',
				'width'  => 10,
				'children' => array('range'),
				'after_label' => 'より',
				'default_value' => date('Y-m-d'),
			),
			'range' => array(
				'label' => '範囲',
				'type'  => 'text',
				'rules' => 'trim|is_natural_no_zero|max_num[200]',
				'width'  => 5,
				'after_label' => '日間',
				'default_value' => 45,
			),
		);
	}

	function _get_dropdown_options_program_id()
	{
		$return = array();
		$return['0'] = '選択してください';
		$rows = $this->model_program->get_main_list(0, 0);
		$return += $this->db_util->convert2assoc($rows);

		return $return;
	}

	function _get_dropdown_options_project_id()
	{
		$return = array();
		$return['0'] = array('name' => '選択してください', 'extra' => 'id="option_program_id_0"');
		$rows = $this->model_project->get_main_list(0, 0, 'B.sort, A.sort');
		foreach ($rows as $row)
		{
			$return[$row['id']] = array('name' => $row['name'], 'extra' => sprintf('id="option_program_id_%d"', $row['program_id']));
		}

		return $return;
	}

	function _get_dropdown_options_work_class_id()
	{
		$return = array();
		$return['0'] = '選択してください';
		$rows = $this->model_work_class->get_main_list();
		$return += $this->db_util->convert2assoc($rows);

		return $return;
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
		if (!$this->db_util->get_row4id('work_class', $value, array('id'), 'gantt', 'model'))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		return true;
	}

	private function _get_work_class_style()
	{
		$list = $this->db_util->get_result_array('work_class', array(), array('id', 'color'), '', 'gantt', 'model');
		$styles = array();
		foreach ($list as $row)
		{
			$styles[] = sprintf('table#gantt_chart td.gantt_active_%d { background-color: %s; }', $row['id'], $row['color']);
		}
		$style = implode(PHP_EOL, $styles);

		return head_style($style);
	}
}

/* End of file report.php */
/* Location: ./application/controllers/gantt.php */
