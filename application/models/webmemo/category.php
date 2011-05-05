<?php
class Category extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	private function get_query_list($sub_id = 0, $columns = array('id', 'name', 'key_name', 'is_private', 'sort'), $isCheckAuth = true, $with_logical_deleted = false)
	{
		if ($columns && is_array($columns)) $columns = implode(',', $columns);
		if (!$columns) $columns = '*';
		$select = sprintf("SELECT %s FROM memo_category", $columns);


		$where_list = array();
		if ($sub_id !== false)        $where_list[] = sprintf('sub_id = %d', $sub_id);
		if (!$with_logical_deleted)   $where_list[] = 'del_flg = 0';
		if ($isCheckAuth && !IS_AUTH) $where_list[] = 'is_private = 0';

		$where = '';
		if ($where_list) $where = sprintf(' WHERE %s', implode(' AND ', $where_list));

		$order = ' ORDER BY sort, id';

		$sql = $select.$where.$order;

		return $this->db->query($sql);
	}

	function get_list_all($sub_id = 0, $columns = array('id', 'name', 'key_name', 'is_private', 'sort'), $isCheckAuth = true, $with_logical_deleted = false)
	{
		$cate_list = array();
		$main_list = $this->get_query_list($sub_id, $columns, $isCheckAuth, $with_logical_deleted)->result_array();

		if ($sub_id !== 0) return $main_list;

		foreach ($main_list as $row)
		{
			$sub_row = $this->get_list($row['id'], $columns, $isCheckAuth, $with_logical_deleted);
			$row['cnt_sc_ary'] = count($sub_row);
			$row['sc_ary'] = $sub_row;

			$cate_list[] = $row;
		}
		unset($main_list);

		return $cate_list;
	}

	function get_list($sub_id = 0, $columns = array('id', 'name', 'key_name', 'is_private', 'updated_at', 'sort'), $isCheckAuth = true, $with_logical_deleted = false)
	{
		return $this->get_query_list($sub_id, $columns, $isCheckAuth, $with_logical_deleted)->result_array();
	}

	function get_id_list($sub_id = 0, $isCheckAuth = true, $with_logical_deleted = false)
	{
		$id_list = array();
		foreach ($this->get_query_list($sub_id, 'id', $isCheckAuth, $with_logical_deleted)->result_array() as $row)
		{
			$id_list[] = (int)$row['id'];
		}

		return $id_list;
	}

	function get_row4id($id, $columns = array('name', 'id', 'sub_id'))
	{
		$this->db->select($columns);
		$this->db->where(array('id' => (int)$id));
		$query = $this->db->get('memo_category');

		if (!$query->num_rows()) return array();

		return $query->row_array(0);
	}

	function get_name4id($id)
	{
		$row = $this->category->get_row4id($id, array('name'));
		if (empty($row)) return '';

		return $row['name'];
	}

	function get_del_flg4id($id)
	{
		$row = $this->category->get_row4id($id, array('del_flg'));
		if (empty($row)) return 0;

		return (int)$row['del_flg'];
	}

	function get_private_category_id_list()
	{
		$private_parent_category_id_list = array();
		$sql = 'SELECT id FROM memo_category'
				 . ' WHERE del_flg  = 0'
				 . ' AND sub_id = 0'
				 . ' AND is_private = 1';
		foreach ($this->db->query($sql)->result_array() as $row)
		{
			$private_parent_category_id_list[] = (int)$row['id'];
		}

		$private_category_id_list = array();
		$sql = 'SELECT id FROM memo_category'
				 . ' WHERE del_flg  = 0'
				 . sprintf(' AND (is_private = 1 OR sub_id IN (%s))', implode(',', $private_parent_category_id_list));
		foreach ($this->db->query($sql)->result_array() as $row)
		{
			$private_category_id_list[] = $row['id'];
		}

		return $private_category_id_list;
	}

	function category_id_list4name($name)
	{
		$name = $this->db->escape_like_str($name);
		$params = array('%'.$name.'%');

		$category_id_list = array();
		$sql = 'SELECT id FROM memo_category'
				 . ' WHERE name LIKE ?';
		foreach ($this->db->query($sql, $params)->result_array() as $row)
		{
			$category_id_list[] = (int)$row['id'];
		}

		return $category_id_list;
	}

	function get_id4key($key)
	{
		$this->db->select('id');
		$this->db->where(array('key_name' => $key));
		$query = $this->db->get('memo_category');
		if (!$query->num_rows()) return 0;

		$row = $query->row_array(0);

		return $row['id'];
	}

	function get_count4name_and_sub_id($name, $sub_id)
	{
		$this->db->where('name', $name);
		$this->db->where('sub_id', $sub_id);
		$this->db->from('memo_category');

		return (int)$this->db->count_all_results();
	}

	public function get_sort_max_next($sub_id)
	{
		$this->db->select_max('sort', 'max');
		$this->db->where('sub_id', (int)$sub_id);
		$query = $this->db->get('memo_category');
		if (!$query->num_rows()) return 1;

		$row = $query->row_array(0);
		$sort = $row['max'] + 1;
		if ($sort > 999) $sort = 999;

		return $sort;
	}

	public function insert($values)
	{
		$values['sort'] = $this->get_sort_max_next($values['sub_id']);
		$values['del_flg'] = 0;
		$values['created_at'] = date('Y-m-d H:i:s');
		$values['updated_at'] = date('Y-m-d H:i:s');

		return $this->db->insert('memo_category', $values);
	}

	public function update($values, $wheres, $update_datetime = true)
	{
		if ($update_datetime) $values['updated_at'] = date('Y-m-d H:i:s');

		$this->db->where($wheres);
		return $this->db->update('memo_category', $values);
	}

	public function update4id($values, $id, $update_datetime = true)
	{
		if (isset($values['sub_id']))
		{
			if (!$row = $this->get_row4id($id)) return false;

			// 子カテゴリ→親カテゴリへの変更時
			if ($row['sub_id'] && $values['sub_id'] == 0)
			{
				// 子カテゴリに分類されていた記事のカテゴリを「未登録」状態に変更
				$CI =& get_instance();
				$CI->load->model('webmemo/memo');
				$CI->memo->update(array('memo_category_id' => 0), array('memo_category_id' => $id), false);
			}

			// 親カテゴリ→子カテゴリへの変更時
			if (!$row['sub_id'] && $values['sub_id'])
			{
				// 子カテゴリの親カテゴリ化
				$this->change_category_children2parent($id);
			}
		}

		return $this->update($values, array('id' => $id), $update_datetime);
	}

	private function change_category_children2parent($parent_id)
	{
		if (!$parent_id) return false;

		// 親であることの確認は行わない
		// if (!$row = $this->get_row4id($parent_id)) return false;
		// if ($sub_id = $row['sub_id']) return false;// 親ではない

		$CI =& get_instance();
		$CI->load->model('webmemo/memo');

		$child_ids = $this->get_id_list($parent_id, false, true);
		foreach ($child_ids as $child_id)
		{
			// 子カテゴリに分類されていた記事のカテゴリを「未登録」状態に変更
			$CI->memo->update(array('memo_category_id' => 0), array('memo_category_id' => $child_id), false);

			// 子カテゴリの親カテゴリ化
			$this->update4id(array('sub_id' => 0), $child_id, true);
		}
	}

	public function delete4id($id)
	{
		if (!$id) return false;
		if (!$row = $this->get_row4id($id)) return false;

		// 親カテゴリ削除時
		if (!$row['sub_id']) $this->change_category_children2parent($id);

		$this->db->where('id', $id);
		return $this->db->delete('memo_category');
	}
}
