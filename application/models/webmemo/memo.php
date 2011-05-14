<?php
class Memo extends CI_Model
{
	const FORMAT_HTML    = 1;
	const FORMAT_TEXTILE = 2;

	function __construct()
	{
		parent::__construct();
	}

	function get_important_list($is_private = false)
	{
		$this->db->select('id, title');
		$this->db->where(array('important_level >=' => 5));
		if (!$is_private)
		{
			$this->db->where('private_flg', 0);
			$CI =& get_instance();
			$CI->load->model('webmemo/category');
			if ($private_category_id_list = $CI->category->get_private_category_id_list())
			{
				$this->db->where(sprintf("memo_category_id NOT IN (%s)", implode(',', $private_category_id_list)));
			}
		}
		$this->db->order_by('sort');

		return $this->db->get('memo')->result_array();
	}

	function get_count($params)
	{
		$this->db->where($params);
		$this->db->from('memo');

		return (int)$this->db->count_all_results();
	}

	function get_each_article($id, $is_private = false)
	{
		if (!$id) return array();

		$sql  = $this->get_main_query($is_private);
		$sql .= " AND A.id = ?";

		return $this->db->query($sql, array((int)$id))->result_array();
	}

	function get_main_list($is_private = false, $search = '', $category_id_list = array(), $order = 'updated_at desc', $offset = 0, $limit = 10, $with_logical_deleted = false, $columns = 'A.*, B.name, B.sub_id')
	{
		$sql  = $this->get_main_query($is_private, $search, $category_id_list, false, $with_logical_deleted, $columns);
		$sql .= sprintf(" ORDER BY A.%s", $order);
		if ($limit) $sql .= sprintf(" LIMIT %d, %d", $offset, $limit);

		return $this->db->query($sql)->result_array();
	}

	function get_count_all($is_private = false, $search = '', $category_id_list = array())
	{
		$sql  = $this->get_main_query($is_private, $search, $category_id_list, true);
		$row = $this->db->query($sql)->first_row('array');

		return (int)$row['count'];
	}

	private static function get_main_query($is_private = false, $search = '', $category_id_list = array(), $is_count = false, $with_logical_deleted = false, $columns = 'A.*, B.name, B.sub_id')
	{
		if (is_array($columns)) $columns = implode(',', $columns);
		if (!$columns) $columns = 'A.*, B.*';

		$select = sprintf("SELECT %s FROM memo A", $columns);
		if ($is_count) $select = "SELECT COUNT(A.id) as count FROM memo A";

		$sql = $select." LEFT JOIN memo_category B ON A.memo_category_id = B.id";

		$where  = '';
		$wheres = array();
		if (!$with_logical_deleted) $wheres[] = "A.del_flg = 0";
		if (!$is_private)
		{
			$wheres[] = "A.private_flg = 0";
			$CI =& get_instance();
			$CI->load->model('webmemo/category');
			if ($private_category_id_list = $CI->category->get_private_category_id_list())
			{
				$wheres[] = sprintf("B.id NOT IN (%s)", implode(',', $private_category_id_list));
			}
		}
		if ($add_where = self::get_like_where_clause($search))
		{
			$wheres[] = $add_where;
			unset($add_where);
		}
		if ($category_id_list)
		{
			$wheres[] = sprintf("B.id IN (%s)", implode(',', $category_id_list));
		}
		if ($wheres) $where = ' WHERE '.implode(' AND ', $wheres);

		return $sql.$where;
	}

	private static function get_like_where_clause($search)
	{
		if (!$search) return '';

		$search_target_columns = array(
			'id',
			'title',
			'explain',
			'body',
			'keyword',
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
			if ($category_id_list = $CI->category->category_id_list4name($word))
			{
				$where_clause_category_id = sprintf('B.id IN (%s)', implode(',', $category_id_list));
			}
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

	public function get_sort_max_next()
	{
		$this->db->select_max('sort', 'max');
		$query = $this->db->get('memo');
		if (!$query->num_rows()) return 1;

		$row = $query->row_array(0);
		$sort = $row['max'] + 1;
		if ($sort > 999999) $sort = 999999;

		return $sort;
	}

	function get_del_flg4id($id)
	{
		$CI =& get_instance();
		$row = $CI->db_util->get_row4id('memo', $id, array('del_flg'), 'webmemo');
		if (empty($row)) return 0;

		return (int)$row['del_flg'];
	}

	public function update($values, $wheres, $update_datetime = true)
	{
		if (!$values || !$wheres) return false;
		if ($update_datetime) $values['updated_at'] = date('Y-m-d H:i:s');

		return $this->db->update('memo', $values, $wheres);
	}

	public function update4id($values, $id, $update_datetime = true)
	{
		return $this->update($values, array('id' => $id), $update_datetime);
	}

	public function insert($values)
	{
		$values['sort'] = $this->get_sort_max_next();
		$values['del_flg'] = 0;
		$values['created_at'] = date('Y-m-d H:i:s');
		$values['updated_at'] = date('Y-m-d H:i:s');

		return $this->db->insert('memo', $values);
	}

	public function delete4id($id)
	{
		if (!$id) return false;
//		if (!$row = $this->get_row4id($id)) return false;

		$this->db->where('id', $id);
		$this->db->delete('memo');

		return $this->db->affected_rows();
	}
}
