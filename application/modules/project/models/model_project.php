<?php
class Model_project extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_count($params)
	{
		$this->db->where($params);
		$this->db->from('project');

		return (int)$this->db->count_all_results();
	}

	function get_row($id, $is_private = false)
	{
		if (!$id) return array();

		$sql  = $this->get_main_query($is_private);
		$sql .= " AND A.id = ?";

		return $this->db->query($sql, array((int)$id))->result_array();
	}

//	function get_main_list($offset = 0, $limit = 10, $order = 'id desc', $search = '', $category_id_list = array(), $with_logical_deleted = false, $columns = 'A.*, B.name, B.sub_id')
	function get_main_list($offset = 0, $limit = 10, $order = 'A.sort', $search = '', $program_id = 0, $with_logical_deleted = false, $columns = 'A.*')
	{
		$sql  = $this->get_main_query($search, $program_id, false, $with_logical_deleted, $columns);
		$sql .= sprintf(" ORDER BY %s", $order);
		if ($limit) $sql .= sprintf(" LIMIT %d, %d", $offset, $limit);

		return $this->db->query($sql)->result_array();
	}

	function get_count_all($search = '', $program_id = 0, $with_logical_deleted = false)
	{
		$sql  = $this->get_main_query($search, $program_id, $with_logical_deleted);
		$row = $this->db->query($sql)->first_row('array');

		return (int)$row['count'];
	}

	private static function get_main_query($search = '', $program_id = 0, $is_count = false, $with_logical_deleted = false, $columns = 'A.*')
	{
		if (is_array($columns)) $columns = implode(',', $columns);
		if (!$columns) $columns = 'A.*, B.*';

		$select = sprintf("SELECT %s FROM project A", $columns);
		if ($is_count) $select = "SELECT COUNT(A.id) as count FROM project A";
		$sql = $select." LEFT JOIN program B ON A.program_id = B.id";

		$where  = '';
		$wheres = array();
		$wheres[] = "B.del_flg = 0";
		if (!$with_logical_deleted) $wheres[] = "A.del_flg = 0";
		if ($add_where = self::get_like_where_clause($search))
		{
			$wheres[] = $add_where;
			unset($add_where);
		}
		if ($program_id)
		{
			$wheres[] = sprintf("program_id = %d", $program_id);
		}
/*
		if ($category_id_list)
		{
			$wheres[] = sprintf("B.id IN (%s)", implode(',', $category_id_list));
		}
*/

		if ($wheres) $where = ' WHERE '.implode(' AND ', $wheres);

		return $sql.$where;
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
		$row = $CI->db_util->get_row4id('project', $id, array('del_flg'), 'project', 'model');
		if (empty($row)) return 0;

		return (int)$row['del_flg'];
	}

	function get_row_common($params)
	{
		$CI =& get_instance();
		$row = $CI->db_util->get_row('project', $params, array(), 'project', 'model');
		if (empty($row)) return array();

		return $row;
	}

	public function get_ids4program_id($program_id)
	{
		if (!$program_id) return false;

		$CI =& get_instance();
		return $CI->db_util->get_cols('project', array('program_id' => $program_id), 'id', 'project', 'model');
	}

	public function update($values, $wheres, $update_datetime = true)
	{
		if (!$values || !$wheres) return false;
		if ($update_datetime) $values['updated_at'] = date('Y-m-d H:i:s');

		return $this->db->update('project', $values, $wheres);
	}

	public function update4id($values, $id, $update_datetime = true)
	{
		return $this->update($values, array('id' => $id), $update_datetime);
	}

	public function insert($values)
	{
		$CI =& get_instance();
		$values = $CI->db_util->set_common_column_value($values);
		$values['sort'] = $CI->db_util->get_sort_max_next('project', 'project', 'model');

		return $this->db->insert('project', $values);
	}

	public function delete4id($id)
	{
		if (!$id) return false;

		// transaction したいね
		$CI =& get_instance();
		$CI->load->model('wbs/model_wbs');
		$CI->model_wbs->delete4project_id($id);

		$this->db->where('id', $id);
		$this->db->delete('project');

		return $this->db->affected_rows();
	}

	public function delete4program_id($program_id)
	{
		if (!$program_id) return false;

		$ids = $this->get_ids4program_id($program_id);
		$count = 0;
		foreach ($ids as $id)
		{
			$count += $this->delete4id($id);
		}

		return $count;
	}
}
