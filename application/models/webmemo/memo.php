<?php
class Memo extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_important_list()
	{
		$this->db->select('mn_id, mn_title');
		$this->db->where(array('inportant_level >=' => 5));
		$this->db->order_by('mn_turn');

		return $this->db->get('memo')->result_array();
	}

	function get_each_article($id, $is_private = false)
	{
		if (!$id) return array();

		$sql  = $this->get_main_query($is_private);
		$sql .= " AND A.mn_id = ?";

		return $this->db->query($sql, array((int)$id))->result_array();
	}

	function get_main_list($is_private = false, $search = '', $category_id_list = array(), $order = 'lastdate', $offset = 0, $limit = 10, $columns = 'A.*, B.name, B.sub_id')
	{
		if (!$order) $order = 'lastdate desc';

		$sql  = $this->get_main_query($is_private, $search, $category_id_list, false, $columns);
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

	private static function get_main_query($is_private = false, $search = '', $category_id_list = array(), $is_count = false, $columns = 'A.*, B.name, B.sub_id')
	{
		if (is_array($columns)) $columns = implode(',', $columns);
		if (!$columns) $columns = 'A.*, B.*';

		$select = sprintf("SELECT %s FROM memo A", $columns);
		if ($is_count) $select = "SELECT COUNT(A.mn_id) as count FROM memo A";

		$sql = $select
				 . " LEFT JOIN memo_category B ON A.memo_category_id = B.id"
				 . " WHERE A.mn_del_flg = 0";
		if (!$is_private)
		{
			$CI =& get_instance();
			$CI->load->model('webmemo/category');

			$sql .= " AND A.private_flg = 0"
					 .  sprintf(" AND B.id NOT IN (%s)", implode(',', $CI->category->get_private_category_id_list()));
		}

		if ($add_where = self::get_like_where_clause($search))
		{
			$sql .= sprintf(' AND %s', $add_where);
			unset($add_where);
		}

		if ($category_id_list)
		{
			$sql .= sprintf(" AND B.id IN (%s)", implode(',', $category_id_list));
		}

		return $sql;
	}

	private static function get_like_where_clause($search)
	{
		if (!$search) return '';

		$search_target_columns = array(
			'mn_id',
			'mn_title',
			'mn_exp',
			'mn_value',
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
}
