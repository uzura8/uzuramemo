<?php
class Model_wbs extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_count($params)
	{
		$this->db->where($params);
		$this->db->from('wbs');

		return (int)$this->db->count_all_results();
	}

	function get_row($id)
	{
		if (!$id) return array();

		$params = array('sql' => array(), 'values' => array());
		$params['sql'][]    = 'A.id = ?';
		$params['values'][] = $id;
		list($sql, $params) = $this->get_main_query('', false, false, 'A.*', $params);

		return $this->db->query($sql, $params)->result_array();
	}

//	function get_main_list($offset = 0, $limit = 10, $order = 'id desc', $search = '', $category_id_list = array(), $with_logical_deleted = false, $columns = 'A.*, B.name, B.sub_id')
	function get_main_list($offset = 0, $limit = 10, $order = 'A.sort', $search = '', $with_logical_deleted = false, $columns = 'A.*', $params = array())
	{
		list($sql, $params)  = $this->get_main_query($search, false, $with_logical_deleted, $columns, $params);
		$sql .= sprintf(" ORDER BY %s", $order);
		if ($limit) $sql .= sprintf(" LIMIT %d, %d", $offset, $limit);

		return $this->db->query($sql, $params)->result_array();
	}

	function get_count_all($search = '', $with_logical_deleted = false, $params = array())
	{
		list($sql, $params) = $this->get_main_query($search, true, $with_logical_deleted, '', $params);
		$row = $this->db->query($sql, $params)->first_row('array');

		return (int)$row['count'];
	}

	private static function get_main_query($search = '', $is_count = false, $with_logical_deleted = false, $columns = 'A.*', $params = array())
	{
		if (is_array($columns)) $columns = implode(',', $columns);
		if (!$columns) $columns = 'A.*, B.*';

		$select = sprintf("SELECT %s FROM wbs A", $columns);
		if ($is_count) $select = "SELECT COUNT(A.id) as count FROM wbs A";
		$sql  = $select;
		$sql .= " LEFT JOIN project B ON A.project_id = B.id";
		$sql .= " LEFT JOIN program C ON B.program_id = C.id";
		$sql .= " LEFT JOIN work_class D ON A.work_class_id = D.id";

		$where  = '';
		$wheres = array();
		$wheres[] = "B.del_flg = 0";
		$wheres[] = "C.del_flg = 0";
		if (!$with_logical_deleted) $wheres[] = "A.del_flg = 0";
		if ($add_where = self::get_like_where_clause($search))
		{
			$wheres[] = $add_where;
			unset($add_where);
		}
		$param_values = array();
		if ($params && !empty($params['sql']))
		{
			$params_sql = $params['sql'];
			if (is_array($params['sql'])) $params_sql = implode(' AND ', $params['sql']);
			$wheres[] = $params_sql;
			if (!empty($params['values'])) $param_values = $params['values'];
		}
/*
		if ($category_id_list)
		{
			$wheres[] = sprintf("B.id IN (%s)", implode(',', $category_id_list));
		}
*/

		if ($wheres) $where = ' WHERE '.implode(' AND ', $wheres);

		return array($sql.$where, $param_values);
	}

	private static function get_like_where_clause($search)
	{
		if (!$search) return '';

		$search_target_columns = array(
			'id',
			'name',
			'explaination',
			'body',
		);

		//全角空白を半角に統一
		$search = str_replace('　', ' ', $search);

		$add_where_list = array();
		$keyword_list = explode(' ', $search);

		// 検索ワード数を制限
		if (count($keyword_list) > 20) return '';

		$CI =& get_instance();
		foreach ($keyword_list as $word)
		{
			$like_list = array();
			foreach ($search_target_columns as $column)
			{
				$like_list[] = 'A.'.$column." LIKE '%".$CI->db->escape_like_str($word)."%'";
			}

			// category
			$where_clause_category_id = '';
/*
			if ($category_id_list = $CI->category->category_id_list4name($word))
			{
				$where_clause_category_id = sprintf('B.id IN (%s)', implode(',', $category_id_list));
			}
*/
			$where_clause = '('.implode(' OR ', $like_list);
			if ($where_clause_category_id) $where_clause .= ' OR '.$where_clause_category_id;
			$where_clause .= ')';
			$add_where_list[] = $where_clause;
		}
		$add_where = sprintf('(%s)', implode(' AND ', $add_where_list));
		unset($like_list);
		unset($add_where_list);

		return $add_where;
	}

	function get_del_flg4id($id)
	{
		$CI =& get_instance();
		$row = $CI->db_util->get_row4id('wbs', $id, array('del_flg'), 'wbs', 'model');
		if (empty($row)) return 0;

		return (int)$row['del_flg'];
	}

	function get_row_common($params)
	{
		$CI =& get_instance();
		$row = $CI->db_util->get_row('wbs', $params, array(), 'wbs', 'model');
		if (empty($row)) return array();

		return $row;
	}

	public function get_row_full($params, $columns = 'A.*', $with_logical_deleted = false)
	{
		if (is_array($columns)) $columns = implode(',', $columns);
		if (!$columns) $columns = 'A.*';

		$select = sprintf("SELECT %s FROM wbs A", $columns);

		$sql  = $select;
		$sql .= " LEFT JOIN project B ON A.project_id = B.id";
		$sql .= " LEFT JOIN program C ON B.program_id = C.id";

		$where  = '';
		$wheres = array();
		if (!$with_logical_deleted) $wheres[] = "A.del_flg = 0";
		$wheres[] = "B.del_flg = 0";
		$wheres[] = "C.del_flg = 0";
		$values = array();
		foreach ($params as $key => $value)
		{
			$wheres[] = sprintf("%s = ?", $key);
			$values[] = $value;
		}
		if ($wheres) $where = ' WHERE '.implode(' AND ', $wheres);
		$sql .= $where;

		return $this->db->query($sql, $values)->row_array(0);
	}

	public function update($values, $wheres, $update_datetime = true)
	{
		if (!$values || !$wheres) return false;
		if ($update_datetime) $values['updated_at'] = date('Y-m-d H:i:s');

		return $this->db->update('wbs', $values, $wheres);
	}

	public function update4id($values, $id, $update_datetime = true)
	{
		return $this->update($values, array('id' => $id), $update_datetime);
	}

	public function insert($values)
	{
		$CI =& get_instance();
		$values = $CI->db_util->set_common_column_value($values);
		$values['sort'] = $CI->db_util->get_sort_max_next('wbs', 'wbs', 'model');

		return $this->db->insert('wbs', $values);
	}

	public function delete4id($id)
	{
		if (!$id) return false;

		$this->db->where('id', $id);
		$this->db->delete('wbs');

		return $this->db->affected_rows();
	}

	public function delete4project_id($project_id)
	{
		if (!$project_id) return false;

		$this->db->where('project_id', $project_id);
		$this->db->delete('wbs');

		return $this->db->affected_rows();
	}
}
