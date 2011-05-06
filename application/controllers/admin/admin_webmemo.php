<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Admin_webmemo extends MY_Controller
{
	private $private_config   = array();
	private $admin_username   = '';
	private $validation_rules = array();

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
		$limit = 1;
		if ($this->input->get_post('result')) $limit = 10;

		$category_list_all = $this->category->get_list_all(0, array('id', 'name', 'sub_id'), false);
		$this->_set_default_form_session_data('memo');
		$view_data = $this->_get_default_view_data();
		$view_data['session'] = $this->session->get(null, 'memo');
		$view_data['select_category_list'] = $category_list_all;
		$view_data['main_list'] = $this->memo->get_main_list(true, '', array(), 'updated_at desc', 0, $limit, true);
		$view_data['cate_name_list'] = $this->site_util->convert_category_name_list($category_list_all, true);
		$view_data['form_textarea_fckeditor'] = $this->_get_form_textarea_fckeditor($this->session->get('body', 'memo'));
		$view_data['set_js_libraries'] = true;
		$this->smarty_parser->parse('ci:admin/webmemo.tpl', $view_data);
	}

	public function execute_edit_memo()
	{
		$this->input->check_is_post();
		$this->_setup_validation('memo');

		// reset button
		if ($this->input->post('reset'))
		{
			$this->_remove_validation_sessions('memo');
			admin_redirect('webmemo/index#frm');
		}
	
		// cancel
		if ($this->input->post('cancel'))
		{
			$redirect_to = '';
			if ($this->session->get('redirect_to', 'memo')) $redirect_to = $this->session->get('redirect_to', 'memo');

			$this->session->remove(null, 'memo');

			if ($redirect_to) redirect($redirect_to);
			admin_redirect('webmemo/index#lst_top');
		}

		if (!$this->input->post('preview') && !$this->input->post('edit')) show_404();
		// 以下、preview か edit 処理


		// 入力補助機能
		$this->_input_support4memo();

		// validate and set sessions
		if (!$this->_set_validation_and_sessions('memo'))
		{
			admin_redirect('webmemo/index', validation_errors());
		}

		// preview button
		if ($this->input->post('preview'))
		{
			admin_redirect('webmemo/index#frm');
		}

		// 以下、edit 処理
		$edit_id = (int)$this->session->get('target_id', 'memo');
		$row_old = array();
		if ($edit_id) $row_old = $this->db_util->get_row4id('memo', $edit_id, $this->_get_save_target_colums('memo'));

		// 重複登録チェック
		if ($this->_check_duplicate_registration_memo((bool)$edit_id))
		{
			admin_redirect('webmemo/index#frm', '登録済みです');
		}

		$values = $this->_convert_values_to_save_memo($this->_get_edit_data_from_sessions('memo'));
		if ($edit_id)
		{
			// 変更時
			$this->memo->update4id($values, $edit_id);
			$message = '変更しました';
		}
		else
		{
			// 新規登録時
			$this->memo->insert($values);
			$message = '登録しました';
		}

		$redirect_to = '';
		if ($this->session->get('redirect_to', 'memo')) $redirect_to = $this->session->get('redirect_to', 'memo');

		$this->session->remove(null, 'memo');

		if ($redirect_to) redirect($redirect_to);
		admin_redirect('webmemo/index#frm', $message);
	}

	private function _validation_rules_memo()
	{
		return array(
			'title' => array(
				'label' => 'タイトル',
				'rules' => 'trim|required|max_length[140]',
			),
			'memo_category_id' => array(
				'label' => 'カテゴリ',
				'rules' => 'trim|required|is_natural|callback__memo_category_id_check',
			),
			'important_level' => array(
				'label' => '重要度',
				'rules' => 'trim|required|is_natural|less_than[6]',
				'default' => 2,
			),
			'private_quote_flg' => array(
				'label' => '非公開フラグ',
				'rules' => 'trim|required|is_natural|less_than[3]',
				'default' => 0,
			),
			'body' => array(
				'label' => '本文',
				'rules' => 'trim',
			),
			'explain' => array(
				'label' => '備考',
				'rules' => 'trim',
			),
			'keyword' => array(
				'label' => 'キーワード',
				'rules' => 'trim',
			),
		);
	}

	public function execute_edit_memo_list()
	{
		$this->input->check_is_post();
		$this->_setup_validation('memo');

		// 管理画面外からのPOSTの場合は、戻りURLを確認しておく
		if ($redirect_to = $this->input->post('redirect_to'))
		{
			if (!is_site_url($redirect_to)) show_404();
		}

		// submit choose
		if ($this->input->post('choose'))
		{
			$id = $this->_get_post_data_from_submit_key('choose', 'id');
			$this->session->remove(null, 'memo');
			$this->session->set('target_id', $id, 'memo');

			// 管理画面以外へのリダイレクトURLをsessionに保存
			if ($redirect_to) $this->session->set('redirect_to', $redirect_to, 'memo');

			$values = $this->_convert_values_to_form_memo($this->db_util->get_row4id('memo', $id, $this->_get_save_target_colums('memo'), 'webmemo'));
			$this->_set_validation_sessions('memo', $values);

			admin_redirect('webmemo/index#frm');
		}

		// submit change_display
		if ($this->input->post('change_display'))
		{
			$id = $this->_get_post_data_from_submit_key('change_display', 'id');
			$this->session->remove(null, 'memo');
			$del_flg_after = 1;
			if ($this->memo->get_del_flg4id($id)) $del_flg_after = 0;
			$this->memo->update4id(array('del_flg' => $del_flg_after), $id, false);

			//$message = sprintf('表示状態を変更しました (ID: %d)', $id);
			admin_redirect('webmemo/index#lst_top');
		}

		// submit change_sort
		if ($this->input->post('change_sort'))
		{
			$id = $this->site_util->set_post_data_from_submit_key('change_sort', 'id', true);
			$this->form_validation->set_rules('id', 'id', 'required|max_length[12]|is_natural_no_zero');
			$this->form_validation->set_rules('sort_'.$id, sprintf('並び順(id:%d)', $id), 'trim|is_natural');
			if (!$this->form_validation->run()) show_404();

			$id = set_value('id');
			$sort = set_value('sort_'.$id);
			if ($sort > 999999) $sort = 999999;

			$this->session->remove(null, 'memo');
			$this->memo->update4id(array('sort' => $sort), $id, false);

			//$message = sprintf('表示順を変更しました (ID: %d)', $id);
			admin_redirect('webmemo/index#lst_top');
		}

		// submit delete
		if ($this->input->post('delete'))
		{
			$id = $this->_get_post_data_from_submit_key('delete', 'id');
			$this->session->remove(null, 'memo');
			if (!$this->memo->delete4id($id))
			{
				$message = sprintf('削除に失敗しました (ID: %d)', $id);
				admin_redirect('webmemo/index#frm', $message);
			}

			$message = sprintf('削除しました (ID: %d)', $id);
			admin_redirect('webmemo/index#frm', $message);
		}

		// submit change_private_quote_flg
		if ($this->input->post('change_private_quote_flg'))
		{
			// 公開範囲変更処理
			$id = $this->_get_post_data_from_submit_key('change_private_quote_flg', 'id');
			$this->session->remove(null, 'memo');
			list($private_flg, $quote_flg) = $this->_get_new_private_flg_and_quote_flg($id);
			$this->memo->update4id(array('private_flg' => $private_flg, 'quote_flg' => $quote_flg), $id, false);

			// 管理画面以外へリダイレクト
			if ($redirect_to) redirect($redirect_to);

			// 管理画面内のリダイレクト
			//$message = sprintf('表示状態を変更しました (ID: %d)', $id);
			admin_redirect('webmemo/index#lst_top');
		}

		show_404();
	}

	public function _convert_values_to_save_memo($session_values)
	{
		$save_values = $session_values;
		$save_values['private_flg'] = 1;
		$save_values['quote_flg']   = 0;

		$divided_private_quote_flgs = site_divide2private_quote_flg($session_values['private_quote_flg']);
		unset($save_values['private_quote_flg']);

		if (isset($divided_private_quote_flgs['private_flg'])) $save_values['private_flg'] = $divided_private_quote_flgs['private_flg'];
		if (isset($divided_private_quote_flgs['quote_flg']))   $save_values['quote_flg']   = $divided_private_quote_flgs['quote_flg'];

		return $save_values;
	}

	public function _convert_values_to_form_memo($saved_values)
	{
		$form_value = $saved_values;
		$form_value['private_quote_flg'] = site_synthesize2private_quote_flg($saved_values['private_flg'], $saved_values['quote_flg']);
		unset($form_value['private_flg'], $form_value['quote_flg']);

		return $form_value;
	}

	public function category()
	{
		$sub_id = 0;

		$view_data = $this->_get_default_view_data();
		$view_data['session_category'] = $this->session->get(null, 'category');
		$view_data['select_category_list'] = $this->category->get_list(0, array('id', 'name', 'sub_id'), false);
		$view_data['main_list'] = $this->category->get_list_all(0, array(), false, true);
		$view_data['cate_id_list'] = $this->_get_category_id_list($view_data['main_list']);
		$this->smarty_parser->parse('ci:admin/webmemo_category.tpl', $view_data);
	}

	public function execute_edit_category()
	{
		$this->input->check_is_post();
		$this->_setup_validation('category');

		// reset button
		if ($this->input->post('reset'))
		{
			$this->_remove_validation_sessions('category');
			admin_redirect('webmemo/category#frm');
		}
	
		// cancel
		if ($this->input->post('cancel'))
		{
			$this->session->remove(null, 'category');
			admin_redirect('webmemo/category#lst_top');
		}

		if (!$this->input->post('edit')) show_404();
		// 以下、edit 処理

		if (!$this->_set_validation_and_sessions('category'))
		{
			admin_redirect('webmemo/category', validation_errors());
		}

		$edit_id = (int)$this->session->get('target_id', 'category');
		$row_old = array();
		if ($edit_id) $row_old = $this->category->get_row4id($edit_id, '');

		// key名の重複チェック
		if (!$edit_id || ($edit_id && set_value('key_name') != $row_old['key_name']))
		{
			if (!$this->_key_name_duplicate_check(set_value('key_name')))
			{
				admin_redirect('webmemo/category#frm', 'その key名 は登録済みです');
			}
		}

		// 重複登録チェック
		if ($this->_check_duplicate_registration_category(set_value('name'), set_value('sub_id'), (bool)$edit_id))
		{
			admin_redirect('webmemo/category#frm', '登録済みです');
		}

		if ($edit_id)
		{
			// 変更時
			$this->category->update4id($this->_get_edit_data_from_sessions('category'), $edit_id);
			$message = '変更しました';
		}
		else
		{
			// 新規登録時
			$this->category->insert($this->_get_edit_data_from_sessions('category'));
			$message = '登録しました';
		}

		$this->session->remove(null, 'category');
		admin_redirect('webmemo/category', $message);
	}

	public function execute_edit_category_list()
	{
		$this->input->check_is_post();
		$this->_setup_validation('category');

		// submit choose
		if ($this->input->post('choose'))
		{
			$id = $this->_get_post_data_from_submit_key('choose', 'id');
			$this->session->remove(null, 'category');
			$this->_set_validation_sessions('category', $this->category->get_row4id($id, array('name', 'sub_id', 'key_name', 'is_private', 'explain')));
			$this->session->set('target_id', $id, 'category');

			admin_redirect('webmemo/category#frm');
		}

		// submit change_display
		if ($this->input->post('change_display'))
		{
			$id = $this->_get_post_data_from_submit_key('change_display', 'id');
			$this->session->remove(null, 'category');
			$del_flg_after = 1;
			if ($this->category->get_del_flg4id($id)) $del_flg_after = 0;
			$this->category->update4id(array('del_flg' => $del_flg_after), $id, false);

			$message = sprintf('表示状態を変更しました (ID: %d)', $id);
			admin_redirect('webmemo/category#frm');
		}

		// submit change_sort
		if ($this->input->post('change_sort_list'))
		{
			$category_id_list = $this->category->get_id_list(false, false, true);
			if ($id = $this->_check_invalid_sort_posted($category_id_list))
			{
				$message = sprintf('入力された数字が不正です (ID: %d)', $id);
				admin_redirect('webmemo/category#frm', $message);
			}

			// update
			$this->_update_category_sort_posted($category_id_list);
			admin_redirect('webmemo/category#lst_top', '表示順を変更しました');
		}

		// submit delete_list
		if ($this->input->post('delete_list'))
		{
			$target_ids = $this->category->get_id_list(false, false, true);
			if (!$target_ids) admin_redirect('webmemo/category#lst_top', '削除するカテゴリが選択されていません');

			$this->_delete_category_posted($target_ids);
			admin_redirect('webmemo/category#lst_top', '削除しました');
		}
	}

	private function _validation_rules_category()
	{
		return array(
			'name' => array(
				'label' => 'カテゴリ名',
				'rules' => 'trim|required|max_length[100]',
			),
			'key_name' => array(
				'label' => 'key名',
				'rules' => 'trim|max_length[20]|alpha_dash',
			),
			'sub_id' => array(
				'label' => '親カテゴリ',
				'rules' => 'trim|required|is_natural|callback__sub_id_check',
			),
			'is_private' => array(
				'label' => '非公開フラグ',
				'rules' => 'is_int_bool',
				'default' => 0,
			),
			'explain' => array(
				'label' => '備考',
				'rules' => 'trim|max_length[500]',
			),
		);
	}

	function _key_name_duplicate_check($str)
	{
		if ($this->category->get_id4key($str))
		{
			$this->form_validation->set_message('_key_name_duplicate_check', 'その %s は既に登録されています');
			return false;
		}

		return true;
	}

	function _sub_id_check($int)
	{
		if (!$int) return true;
		if (!$row = $this->category->get_row4id($int)) return true;
		if (!$row['sub_id']) return true;

		$this->form_validation->set_message('_sub_id_check', '子カテゴリは指定できません');
		return false;
	}

	private function _forword($action, $message = '')
	{
		if (!$action) return;

		$this->session->set_flashdata('message', $message, true);
		$method = sprintf('_view_%s', $action);
		$this->$method();
	}

	private function _delete_category_posted($category_id_list)
	{
		$target_ids = array();
		foreach ($category_id_list as $id)
		{
			$is_target = (bool)$this->input->post(sprintf('check_%s', $id));
			if ($is_target) $target_ids[] = $id;
		}
		unset($category_id_list);

		foreach ($target_ids as $id)
		{
			$this->category->delete4id($id);
		}
	}

	private function _update_category_sort_posted($category_id_list)
	{
		foreach ($category_id_list as $id)
		{
			$sort = (int)$this->input->post(sprintf('sort_%s', $id));
			$this->category->update4id(array('sort' => $sort), $id);
		}
	}

	private function _check_invalid_sort_posted($category_id_list)
	{
		foreach ($category_id_list as $id)
		{
			$sort = (int)$this->input->post(sprintf('sort_%s', $id));
			if ($sort < 0 || $sort > 999) return $id;
		}

		return 0;
	}

	private function _check_duplicate_registration_memo($is_edit)
	{
		$params = array(
			'title'            => $this->session->get('title', 'memo'),
			'memo_category_id' => $this->session->get('memo_category_id', 'memo'),
			'body'             => $this->session->get('body', 'memo'),
		);
		$count = $this->memo->get_count($params);
		if (!$count) return false;
		if ($is_edit && $count == 1) return false;

		return true;
	}

	private function _check_duplicate_registration_category($name, $sub_id, $is_edit)
	{
		$count = $this->category->get_count4name_and_sub_id($name, $sub_id);
		if (!$count) return false;
		if ($is_edit && $count == 1) return false;

		return true;
	}

	private function _get_category_id_list($cate_list)
	{
		$cate_ids = array();
		foreach ($cate_list as $row) $cate_ids[] = (int)$row['id'];

		return $cate_ids;
	}

	private function _get_validation_rules($action, $field = '')
	{
		if (!$action) return false;
		$method = '_validation_rules_'.$action;
		if (!$list = $this->$method()) return false;

		if ($field)
		{
			if (empty($list[$field])) return false;

			return $list[$field];
		}

		return $list;
	}

	private function _set_validation_rules()
	{
		foreach ($this->validation_rules as $field => $row)
		{
			$this->form_validation->set_rules($field, $row['label'], $row['rules']);
		}
	}

	private function _set_validation_sessions($action, $values = array())
	{
		foreach ($this->validation_rules as $field => $row)
		{
			if ($values)
			{
				$value = $values[$field];
			}
			else
			{
				$value = set_value($field);
			}
			if (is_null($value) && isset($row['default'])) $value = $row['default'];

			$this->session->set($field, $value, $action);
		}
	}

	private function _remove_validation_sessions($action)
	{
		foreach ($this->validation_rules as $field => $row)
		{
			$this->session->remove($field, $action);
		}
	}

	private function _setup_validation($action)
	{
		$this->load->library('form_validation');
		$this->validation_rules = $this->_get_validation_rules($action);
	}

	private function _set_default_form_session_data($action)
	{
		$validation_rules = $this->_get_validation_rules($action);
		foreach ($validation_rules as $field => $row)
		{
			if (!is_null($this->session->get($field, $action))) continue;
			if (!isset($row['default'])) continue;

			$this->session->set($field, $row['default'], $action);
		}
	}

	private function _set_validation_and_sessions($action)
	{
		$this->_set_validation_rules();
		$validation_result = $this->form_validation->run();
		$this->_set_validation_sessions($action);

		return $validation_result;
	}

	private function _get_edit_data_from_sessions($action)
	{
		$values = array();
		foreach ($this->validation_rules as $field => $row)
		{
			$values[$field] = $this->session->get($field, $action);
		}

		return $values;
	}

	private function _get_form_textarea_fckeditor($body)
	{
		require_once(UM_PUBLIB_DIR.'/fckeditor/fckeditor.php') ;//FckEditor読み込み
		$oFCKeditor = new FCKeditor('body') ;//name属性
		$oFCKeditor->Config['CustomConfigurationsPath'] = '/lib/fckeditor/myconfig.js';
		$oFCKeditor->BasePath = '/lib/fckeditor/';
		//$oFCKeditor->Width = '520';//幅
		$oFCKeditor->Height = '100%';//高さ

		//$oFCKeditor->ToolbarSet = 'Default' ; // ツールバーのモード（Basic/Default）
		$oFCKeditor->ToolbarSet = 'MyToolbar';

		//テキストエリアに表示する値
		$oFCKeditor->Value = $body;

		$oFCKeditor->Config['EnterMode'] = 'br';//改行を<p>にしない
		//$oFCKeditor->Value = '<p>This is some <strong>sample text</strong>. You are using <a href="http://www.fckeditor.net/">FCKeditor</a>.</p>' ;
		//$oFCKeditor->Create();

		return $oFCKeditor->CreateHtml();
	}

	private function _get_save_target_colums($target_table)
	{
		$columns = $this->validation_rules;

		if ($target_table == 'memo')
		{
			$columns['private_flg'] = '';
			$columns['quote_flg']   = '';
			unset($columns['private_quote_flg']);
		}

		return array_keys($columns);
	}

	private function _get_post_data_from_submit_key($submit_key, $post_key)
	{
		$this->site_util->set_post_data_from_submit_key($submit_key, $post_key);
		$this->form_validation->set_rules('id', 'id', 'required|max_length[12]|is_natural_no_zero');
		if (!$this->form_validation->run()) common_error(validation_errors());

		return set_value('id');
	}

	private function _get_new_private_flg_and_quote_flg($id)
	{
		$row = $this->db_util->get_row4id('memo', $id, array('private_flg', 'quote_flg'), 'webmemo');
		$private_quote_flg_now = site_synthesize2private_quote_flg($row['private_flg'], $row['quote_flg']);
		$private_quote_flg_new = $private_quote_flg_now + 1;
		if (!UM_USE_QUOTE_ARTICLE_VIEW && $private_quote_flg_new == 1) $private_quote_flg_new = 2;
		if ($private_quote_flg_new > 2) $private_quote_flg_new = 0;
		$values = site_divide2private_quote_flg($private_quote_flg_new);

		return array($values['private_flg'], $values['quote_flg']);
	}

	// 入力補助機能
	private function _input_support4memo()
	{
		// タイトルを本文から補う処理
		if (!$this->input->post('title') && $this->input->post('body'))
		{
			$title = $this->_get_title_from_body($this->input->post('body'));
			if ($title) $this->input->set_post('title', $title);
		}

		// タイトルよりカテゴリを抜きだす
		if ($this->input->post('title') && !$this->input->post('memo_category_id'))
		{
			list($title, $memo_category_id) = get_category_from_search_word($this->input->post('title'));
			if ($memo_category_id)
			{
				$this->input->set_post('title', $title);
				$this->input->set_post('memo_category_id', $memo_category_id);
			}
		}

		// 本文よりURLのみ抜き出す
		if(!$this->input->post('explain'))
		{
			$explain = $this->site_util->get_url_from_body($this->input->post('body'));
			if ($explain) $this->input->set_post('explain', $explain);
		}
	}

	// タイトルを本文から補う処理
	private function _get_title_from_body($body)
	{
		$title = '';
		$pre_title = $body;
		$pre_title = strip_tags($pre_title);
		$lines = explode("\n", $pre_title);
		foreach ($lines as $line)
		{
			$line = str_replace(array('&nbsp;', ' ', '　', "'"), '', $line);
			if (strlen($line) > 1)
			{
				if (mb_strlen($line) > 140) $line = mb_strimwidth($line, 0, 135, "...");
				$title = $line;
				break;
			}
		}

		return $title;
	}
}

/* End of file admin_webmemo.php */
/* Location: ./application/controllers/admin/admin_webmemo.php */
