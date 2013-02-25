<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends MY_Controller
{
	private $limit  = 10;
	private $limit_wbs = 1000;
	private $offset = 0;
	private $order  = 0;
	private $search = '';
	private $search_option  = false;
	private $wbs_id = 0;
	private $mode = 0;
	private $edit = 0;
	private $next_url;
	private $sdate = '';
	private $edate = '';

	function __construct()
	{
		parent::__construct();

		// load models
		$this->load->model('activity/model_activity');
		$this->load->model('wbs/model_work_class');
		$this->load->model('program/model_program');
		$this->load->model('project/model_project');

		// load config
		$this->config->load('activity', true);
		$this->config->load('program', true);
		$this->config->load('project', true);
		$this->config->load('gantt', true);

		$this->_configure();
	}

	private function _configure()
	{
		$this->private_config = $this->config->item('activity');
		$this->_set_params();
	}

	private function _set_params()
	{
		$this->load->library('form_validation');

		$this->wbs_id = $this->_get_post_params('wbs_id', 0, 'intval');
		$this->mode   = $this->_get_post_params('mode', 0, 'intval');
		$this->sdate = $this->_get_post_params('sdate', '');
		$this->edate = $this->_get_post_params('edate', '');
/*
		$this->limit  = $this->private_config['article_nums']['default'];
		if (IS_MOBILE) $this->limit = $this->private_config['article_nums']['mobile'];
		$this->search = $this->_get_post_params('search', '', 'trim|max_length[301]');
		$this->offset = $this->_get_post_params('from', 0, 'intval|less_than[10000000]');
		$this->project_id = $this->_get_post_params('project_id', 0, 'intval');
		$this->edit   = $this->_get_post_params('edit', 0, 'intval|is_natural|less_than[2]');

		$options = $this->_get_form_dropdown_options_order();// select:order
		$this->order  = $this->_get_post_params('order', 0, sprintf('intval|less_than[%d]', count($options)));
*/
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

	public function create($wbs_id = 0, $mode = 0, $activity_id = 0)
	{
		if (!$wbs_id || !$row = $this->model_wbs->get_row($wbs_id))
		{
			show_404();
		}
		$wbs = $row[0];

		if ($activity_id && !$activity = $this->model_activity->get_row4id($activity_id)) $activity_id = 0;

		// template
		$view_data = $this->_get_default_view_data();
		$view_data['page_title'] = $this->private_config['site_title'].'新規作成';
		$view_data['page_subtitle'] = $wbs['name'];
		$view_data['page_subtitle_parts'] = array(
//			array('url' => site_url('project/index/'.$project['program_key']), 'subtitle' => $project['program_name']),
//			array('url' => '', 'subtitle' => $wbs['name']),
		);

		$this->wbs_id = (int)$wbs['id'];
		$view_data['wbs_id'] = $this->wbs_id;

		// request parameter
		$view_data['form_id_suffix'] = 'activity';
		$view_data['form_id'] = $this->wbs_id;

		$mode = (int)$mode;
		if ($mode) $view_data['is_modal'] = true;
		if ($wbs) $view_data['main_form_title'] = sprintf('%s : %s', $wbs['name'], $view_data['page_title']);
//		$view_data['search'] = $this->search;
//		$view_data['order']  = $this->order;
//		$view_data['opt']    = $this->search_option;
//		$view_data['from']   = $this->offset;
//		$view_data['limit']  = $this->limit;
//		$view_data['edit']   = $this->edit;

		// form
		$validation_rules = $this->_validation_rules();// form
		$validation_rules['wbs_id']['value'] = $this->wbs_id;
		$validation_rules['mode']['value'] = $mode;
		if ($activity)
		{
			$validation_rules['name']['value']           = $activity['name'];
			$validation_rules['scheduled_date']['value'] = $activity['scheduled_date'];
			$validation_rules['due_date']['value']       = $activity['due_date'];
			$validation_rules['closed_date']['value']    = $activity['closed_date'];
			$validation_rules['body']['value']           = $activity['body'];

			$view_data['form_action'] = site_url('activity/execute_insert/'.$activity_id);
		}
		$view_data['form'] = $validation_rules;

		// select:order
//		$options = $this->_get_form_dropdown_options_order();// select:order
////		$view_data['form_dropdown_list'] = array();
		$view_data['form_dropdown_list']['order'] = array('options' => $options);

		$this->smarty_parser->parse('ci:activity/create.tpl', $view_data);
	}

	public function index($project_key = '')
	{
		if (!$project_key || !$project = $this->model_project->get_row_full(array('A.key_name' => $project_key), 'A.id, A.name, B.name as program_name, B.key_name as program_key'))
		{
			show_404();
		}
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

	public function ajax_activity_list_simple()
	{
		$view_data = $this->_get_default_view_data();

		$params = array();
		if ($this->wbs_id)
		{
			$params['wbs_id'] = $this->wbs_id;
			$view_data['wbs_id'] = $this->wbs_id;
		}
		$view_data['list'] =  $this->model_activity->get_rows($params, array(), 'sort');

		$this->smarty_parser->parse('ci:activity/list_simple.tpl', $view_data);
	}

	public function ajax_activity_list($wbs_id = 0)
	{
		if (!$wbs_id || !$row = $this->model_wbs->get_row($wbs_id))
		{
			show_404();
		}
		$wbs = $row[0];

		$view_data = $this->_get_default_view_data();

		$params = array();
		$params['wbs_id'] = $wbs_id;


		$mode = (int)$this->_get_post_params('mode', '');
		switch ($mode)
		{
			case 1: // all
				break;
			case 2: //priority
				$params['del_flg'] = 0;
				$params['scheduled_date < '] = date('Y-m-d', strtotime('+ 1day'));
				break;
			default :
				$params['del_flg'] = 0;
				break;
		}
		$view_data['list'] =  $this->model_activity->get_rows($params, array(), 'sort');

		$view_data['wbs_id'] = $wbs_id;
		$view_data['wbs'] = $wbs;

		if ($view_data['list'])
		{
			$this->smarty_parser->parse('ci:activity/list.tpl', $view_data);
		}
		else
		{
			echo '';
		}
	}

	public function ajax_activity_list_date($date = '')
	{
		$params = array('sql' => array(), 'values' => array());
		switch ($date)
		{
			case 'past':
				$params['sql'][] = 'A.scheduled_date < ?';
				$params['values'][] = date('Y-m-d');
				break;
			case 'undefined':
				$params['sql'][] = 'A.scheduled_date is NULL';
				break;
			default :
				$date = $this->site_util->simple_validation($date, '', 'date_format');
				if (!$date) show_404();

				$params['sql'][] = 'A.scheduled_date = ?';
				$params['values'][] = $date;
				break;
		}

		// mode
		$mode = (int)$this->_get_post_params('mode', '');
		switch ($mode)
		{
			case 1: // all
				$with_logical_deleted = true;
				break;
			case 2: //priority
				break;
			default :
				$with_logical_deleted = false;
				break;
		}

		$private = $this->_get_post_params('private', 0, 'trim|is_natural|max_num[1]');
		$params['sql'][] = 'D.private_flg = ?';
		$params['values'][] = (int)$private;

		$view_data = $this->_get_default_view_data();
		$view_data['list'] = $this->model_activity->get_main_list(0, 0, 'A.sort, B.sort', '', $with_logical_deleted,
			'A.*, B.name as wbs_name, C.name as project_name, C.key_name as project_key_name, D.name as program_name, D.key_name as program_key_name, D.color, D.background_color', $params);

		$this->smarty_parser->parse('ci:activity/list_date.tpl', $view_data);
	}

	public function ajax_activity_list_date_bcup($date = '')
	{
		$date = $this->site_util->simple_validation($date, '', 'date_format');
		if (!$date) show_404();

		$view_data = $this->_get_default_view_data();

		$params = array();
		$params['scheduled_date'] = $date;
		$mode = (int)$this->_get_post_params('mode', '');
		switch ($mode)
		{
			case 1: // all
				$params['private_flg'] = 0;
				break;
			case 2: //priority
/*
				$params['del_flg'] = 0;
				$params['scheduled_date < '] = date('Y-m-d', strtotime('+ 1day'));
*/
				break;
			default :
				$params['del_flg'] = 0;
				$params['private_flg'] = 0;
				break;
		}
		$view_data['list'] = $this->model_activity->get_rows($params, array(), 'sort');

		$this->smarty_parser->parse('ci:activity/list_date.tpl', $view_data);
	}

	public function schedule($from_date = '', $to_date = '')
	{
		if ($from_date)
		{
			$from_date = $this->site_util->simple_validation($from_date, '', 'date_format');
			if (!$from_date) show_404();
		}

		$view_data = $this->_get_default_view_data();
		$view_data['config_site_styles'] = get_config_value('styles', 'site');

		$view_data['is_detail'] = false;
		if (($from_date && !$to_date) || ($from_date == $to_date))
		{
			$view_data['is_detail'] = true;
		}

		if (!$from_date) $from_date = date('Y-m-d');
		$default_period = 14;
		if (!$to_date) $to_date = date('Y-m-d', strtotime(sprintf('%s +%d days', $from_date, $default_period)));

		$mode = (int)$this->_get_post_params('mode', '');

		$date_list = array();
		$i = 0;
		while ($time = strtotime(sprintf('%s +%d days', $from_date, $i)))
		{
			$date = date('Y-m-d', $time);
			if ($date > $to_date) break;

			$row = array();
			$row['week_name'] = $this->date_util->get_week_name(date('w', $time));

			$params = array();
			$params['scheduled_date'] = $date;
			switch ($mode)
			{
				case 1: // active
					//$params['del_flg'] = 0;
					break;
				case 2: //priority
					//$params['del_flg'] = 0;
					$params['scheduled_date < '] = date('Y-m-d', strtotime('+ 1day'));
					break;
				default :
					break;
			}
			$view_data['list'] =  $this->model_activity->get_rows($params, array(), 'sort');

			$row['list'] =  $this->model_activity->get_rows($params, array(), 'sort');
			$date_list[$date] = $row;

			$i++;
		}
		$view_data['list'] = $date_list;

		$view_data['mode'] = (int)$this->_get_post_params('mode', '');

		$this->smarty_parser->parse('ci:activity/schedule.tpl', $view_data);
	}

/*
	public function activity_list_schedule($date = '')
	{
		$view_data = $this->_get_default_view_data();
		$view_data['config_site_styles'] = get_config_value('styles', 'site');

		if (($from_date && !$to_date) || ($from_date == $to_date))
		{
			$view_data['is_detail'] = true;
		}

		if (!$from_date) $from_date = date('Y-m-d');
		$default_period = 14;
		if (!$to_date) $to_date = date('Y-m-d', strtotime(sprintf('%s +%d days', $from_date, $default_period)));

		$mode = (int)$this->_get_post_params('mode', '');

		$date_list = array();
		$i = 0;
		while ($time = strtotime(sprintf('%s +%d days', $from_date, $i)))
		{
			$date = date('Y-m-d', $time);
			if ($date > $to_date) break;

			$row = array();
			$row['week_name'] = $this->date_util->get_week_name(date('w', $time));

			$params = array();
			$params['scheduled_date'] = $date;
			switch ($mode)
			{
				case 1: // active
					$params['del_flg'] = 0;
					break;
				case 2: //priority
					$params['del_flg'] = 0;
					$params['scheduled_date < '] = date('Y-m-d', strtotime('+ 1day'));
					break;
				default :
					break;
			}
			$view_data['list'] =  $this->model_activity->get_rows($params, array(), 'sort');

			$row['list'] =  $this->model_activity->get_rows($params, array(), 'sort');
			$date_list[$date] = $row;

			$i++;
		}
		$view_data['list'] = $date_list;

		$view_data['mode'] = (int)$this->_get_post_params('mode', '');

		$this->smarty_parser->parse('ci:activity/schedule.tpl', $view_data);
	}
*/
	public function wbs($wbs_ids_string = '')
	{
		// template
		$view_data = $this->_get_default_view_data();

		$wbs_ids = array();
		if ($wbs_ids_string)
		{
			$wbs_ids = $this->strings_util->convert_string2array($wbs_ids_string, '-', 'intval');
		}
		$view_data['is_detail'] = (count($wbs_ids) === 1)? true : false;
		$view_data['config_site_styles'] = get_config_value('styles', 'site');

		$params = array('sql' => array(), 'values' => array());
		$params['sql'][] = 'A.del_flg = 0';
		if ($wbs_ids)
		{
			$params['sql'][]    = sprintf('A.id in (%s)', implode(',', $wbs_ids));
		}
/*
		if ($this->sdate)
		{
			$params['sql'][]   = 'A.start_date >= ?';
			$params['values'][] = $this->sdate;
		}
		if ($this->edate)
		{
			$params['sql'][]   = 'A.due_date <= ?';
			$params['values'][] = $this->edate;
		}
*/
		$view_data['mode'] = (int)$this->_get_post_params('mode', '');

		$private = $this->_get_post_params('private', 0, 'trim|is_natural|max_num[1]');
		$params['sql'][] = 'C.private_flg = ?';
		$params['values'][] = (int)$private;
/*
		$is_prv_dev = (int)$this->_get_post_params('pdev', '');
		if ($is_prv_dev)
		{
			$params['sql'][] = 'C.id = 5';
		}
		else
		{
			$params['sql'][] = 'C.id <> 5';
		}
*/
		$view_data['list'] = $this->model_wbs->get_main_list(0, $this->limit_wbs, 'A.sort, C.sort', '', true, 'A.*, B.name as project_name, B.key_name as project_key_name, C.name as program_name, C.key_name as program_key_name, C.color, C.background_color', $params);
/*
		// 記事件数を取得
		$count_all = $this->model_wbs->get_count_all('', true, $params);
		$uri = 'wbs/ajax_wbs_list';
		if ($this->project_id) $uri .= '?project_id='.$this->project_id;
		$view_data['pagination'] = $this->_get_pagination_simple($count_all, $uri);
		$view_data['count_all']  = $count_all;
		$view_data['max_page'] = ceil($count_all / $this->limit);
*/
		$this->smarty_parser->parse('ci:activity/wbs.tpl', $view_data);
	}

	public function ajax_activity_detail($id, $item)
	{
		$id = (int)str_replace($item, '', $id);
		if (!$id) show_error('need id');

		if (!$this->_check_edit_form_item($item)) show_error('item is invalid');

		$row = $this->model_activity->get_row4id($id);
		echo $row[$item];
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
		if ($this->model_activity->get_del_flg4id($id)) $del_flg_after = 0;

		$closed_date = $this->_get_post_params('closed_date');
		if ($del_flg_after && !$closed_date) $closed_date = date('Y-m-d');
		if (!$del_flg_after) $closed_date = null;

		$params = array('del_flg' => $del_flg_after);
		$params['closed_date'] = $closed_date;
		$this->model_activity->update4id($params, $id, false);

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
		if ($this->db_util->get_col4id('activity', $id, 'importance', 'activity', 'model')) $importance_after = 0;

		$values = array('importance' => $importance_after);
		$this->model_activity->update4id($values, $id, false);

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
		$row = $this->model_activity->get_row_common(array('id' => $id));
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
		if (!$this->model_activity->update4id($values, $id))
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$this->output->set_output('true');
	}

	public function ajax_execute_update_sort_move($wbs_id = 0)
	{
		$this->input->check_is_post();
		$ids_pre = explode(',', $this->_get_post_params('values'));
		$ids = array();
		$sorts = array();
		foreach ($ids_pre as $id_pre)
		{
			$id = (int)$id_pre;
			if (!$id) continue;
			$ids[] = $id;
		}
		unset($ids_pre);

		$sorts = $this->db_util->get_cols('activity', array('id' => $ids), 'sort', 'sort', 'activity', 'model');
		$values = array_combine($ids, $sorts);

		// まとめて更新
		$result_count = 0;
		$error = false;
		$this->db->trans_begin();
		foreach ($values as $id => $sort)
		{
			$result = $this->db_util->update('activity', array('sort' => $sort), array('id' => $id), false, 'activity', 'model');
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

	public function ajax_execute_update_sort_top()
	{
		$this->input->check_is_post();
		$id = (int)$this->_get_post_params('id');

		$params = array();
		$params['del_flg'] = 0;
		$rows = $this->db_util->get_assoc('wbs', $params, array('id', 'sort'), 'sort', 'wbs', 'model');

		$wbs_ids = array($id);
		$sorts = array();
		foreach ($rows as $wbs_id => $sort)
		{
			if ($id != $wbs_id) $wbs_ids[] = $wbs_id;
			$sorts[] = $sort;
		}
		$values = array_combine($wbs_ids, $sorts);

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

	public function ajax_copy_activity()
	{
		$this->input->check_is_post();
		$id = (int)$this->_get_post_params('id');
		$is_schedule = (int)$this->_get_post_params('is_schedule');

		// 値に変更がない場合はそのまま
		$row = $this->model_activity->get_row_common(array('id' => $id));
		$unsets = array(
			'id' => null,
			'scheduled_date' => null,
			'closed_date' => null,
			//'sort' => null,
			'del_flg' => 0,
			'created_at' => null,
			'updated_at' => null,
		);
		if ($is_schedule) unset($unsets['scheduled_date']);

		foreach ($unsets as $key => $value) $row[$key] = $value;
		$result = $this->model_activity->insert($row);
		if (!$result)
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$return = $row['wbs_id'];
		if ($is_schedule)
		{
			$return = $row['scheduled_date'];
			if ($this->date_util->conv2int($return) < date('Ymd')) $return = 'past';
		}

		$this->output->set_output($return);
	}

	public function ajax_update_scheduled_date()
	{
		$this->input->check_is_post();
		$id = (int)$this->_get_post_params('id');
		if (!$row = $this->model_activity->get_row_common(array('id' => $id))) $this->output->set_ajax_output_error();

		$type = $this->_get_post_params('type');
		$accept_params_type = array('plus', 'minus');
		if (!in_array($type, $accept_params_type)) $this->output->set_ajax_output_error();
		$type = ($type == 'minus')? '-' : '+';

		$unit = $this->_get_post_params('unit');
		$accept_params_unit = array('day', 'week', 'month');
		if (!in_array($unit, $accept_params_unit)) $this->output->set_ajax_output_error();

		$value = (int)$this->_get_post_params('value');
		if ($value < 0 || $value > 5) $this->output->set_ajax_output_error();

		$date_option = sprintf('%s%s %s', $type, $value, $unit);

		$scheduled_date_before = $row['scheduled_date'];
		$is_past = ($this->date_util->conv2int($scheduled_date_before) < date('Ymd'))? true : false;
		if ($is_past || empty($row['scheduled_date']) || $row['scheduled_date'] == '0000-00-00' || $value == 0)
		{
			// 未登録の場合は明日
			$scheduled_date_after = date('Y-m-d', strtotime($date_option));
		}
		else
		{
			$scheduled_date_after = date('Y-m-d', strtotime(sprintf('%s %s', $row['scheduled_date'], $date_option)));
		}

		// 登録
		$values = array('scheduled_date' => $scheduled_date_after);
		if (!$this->model_activity->update4id($values, $id))
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$return = array();
		$return['wbs_id'] = $row['wbs_id'];
		$return['scheduled_date'] = $scheduled_date_after;
		$return['scheduled_date_before'] = $scheduled_date_before;
		if (empty($scheduled_date_before))
		{
			$return['scheduled_date_before'] = 'undefined';
		}
		elseif ($this->date_util->conv2int($scheduled_date_before) < date('Ymd'))
		{
			$return['scheduled_date_before'] = 'past';
		}

		if (empty($return))
		{
			$return = '';
		}
		else
		{
			$return = json_encode($return);
		}

		$this->output->set_output($return);
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
		$this->form_validation->set_rules('scheduled_date', $validate_rules['scheduled_date']['label'], $validate_rules['scheduled_date']['rules']);
		$this->form_validation->set_rules('due_date', $validate_rules['due_date']['label'], $validate_rules['due_date']['rules']);
		$result = $this->form_validation->run();

		// 値に変更がない場合はそのまま
		$row = $this->model_activity->get_row_common(array('id' => $id));
		$scheduled_date_before = $row['scheduled_date'];
		if ($scheduled_date_before == set_value('scheduled_date') && $row['due_date'] == set_value('due_date'))
		{
			return;
		}

		if (!$result)
		{
			$this->output->set_ajax_output_error();
			return;
		}

		// 登録
		$values = array('scheduled_date' => set_value('scheduled_date'), 'due_date' => set_value('due_date'));
		if (!$this->model_activity->update4id($values, $id))
		{
			$this->output->set_ajax_output_error();
			return;
		}

		$return = array();
		if ($values['due_date'])
		{
			$return['rest_days'] = site_convert_due_date($values['due_date'], 'rest_days');
			$styles = site_convert_due_date($values['due_date'], 'style');
			$return['styles'] = $this->strings_util->convert_style2array($styles, true);
		}

		$return['scheduled_date_before'] = $scheduled_date_before;
		if (empty($scheduled_date_before))
		{
			$return['scheduled_date_before'] = 'undefined';
		}
		elseif ($this->date_util->conv2int($scheduled_date_before) < date('Ymd'))
		{
			$return['scheduled_date_before'] = 'past';
		}

		if (empty($return))
		{
			$return = '';
		}
		else
		{
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

		if (!$this->model_activity->delete4id($id))
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
		$allow_items = array('body', 'name', 'scheduled_date', 'due_date', 'closed_date', 'estimated_time', 'spent_time');
		if (!$item || !in_array($item, $allow_items)) return false;

		return true;
	}

	public function execute_insert($activity_id = 0)
	{
		$this->input->check_is_post();
		if ($activity_id && !$activity = $this->model_activity->get_row4id($activity_id))
		{
			$this->output->set_status_header('403');
			return;
		}

		$this->_setup_validation();

		if (!$this->form_validation->run())
		{
			$this->output->set_status_header('403');
			return;
		}

		// 登録
		$values = $this->_get_form_data();
		if ($activity_id)
		{
			$this->model_activity->update($values, array('id' => $activity_id));
		}
		else
		{
			$this->model_activity->insert($values);
		}

		if ($values['scheduled_date'])
		{
			$posted_date = $this->input->cookie('scheduled_date_modal_activity_wbs');
			if ($posted_date) $posted_date .= '_';
			$posted_date .= $values['scheduled_date'];
			setcookie('scheduled_date_modal_activity_wbs', $posted_date);
		}

		if ($activity_id)
		{
			$this->edit_complete($activity_id);
		}
		elseif ($this->mode)
		{
			$this->create($this->wbs_id, $this->mode);
		}
		else
		{
			$this->output->set_output('OK');
		}
	}

	public function edit_complete($id = 0)
	{
		$view_data = $this->_get_default_view_data();
		$this->smarty_parser->parse('ci:activity/edit_complete.tpl', $view_data);
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
		$this->model_activity->update4id($values, $id);

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
				'label' => 'name',
				'type'  => 'text',
				'rules' => 'trim|required|max_length[140]',
				'width'  => 30,
			),
			'scheduled_date' => array(
				'label' => '実施予定日',
				'type'  => 'text',
				'rules' => 'trim|date_format',
				'width'  => 10,
				'children' => array('due_date'),
				'input_support' => array('easy_date_picker' => true),
				'default_value' => date('Y-m-d'),
			),
			'due_date' => array(
				'label' => '期日',
				'type'  => 'text',
				'rules' => 'trim|date_format',
				'width'  => 10,
				'id_name'  => 'due_date_activity',
			),
			'closed_date' => array(
				'label' => '終了日',
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
				'disabled_for_insert'  => true,
			),
			'wbs_id' => array(
				'label' => 'wbs_id',
				'type'  => 'hidden',
				'rules' => 'trim|is_natural_no_zero|callback__is_registered_wbs_id',
				'error_messages'  => array('min' => ''),
				'width'  => 30,
			),
			'mode' => array(
				'label' => 'mode',
				'type'  => 'hidden',
				'rules' => 'trim|is_natural|max_num[1]',
				'error_messages'  => array('min' => ''),
				'width'  => 30,
				'disabled_item_in_sql'  => true,
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

	public function _is_registered_wbs_id($value)
	{
		$validation_name = '_is_registered_wbs_id';
		$error_message = 'WBS ID が正しくありません';

		if (!intval($value))
		{
			$this->form_validation->set_message($validation_name, $error_message);
			return false;
		}

		// idの存在確認
		$result = $this->db_util->get_row4id('wbs', $value, array('id'), 'wbs', 'model');
		if (!$result)
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

/* End of file activity.php */
/* Location: ./application/modules/activity/controllers/activity.php */
