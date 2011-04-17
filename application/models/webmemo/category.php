<?php
class Category extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	private function get_query_list($sub_id = 0, $columns = array('mc_id', 'mc_name', 'mc_key', 'is_private'), $isCheckAuth = true)
	{
		if ($columns && is_array($columns)) $columns = implode(',', $columns);
		if (!$columns) $columns = '*';
		$select = sprintf("SELECT %s FROM T_mn_cate", $columns);

		$where = ' WHERE mc_del_flg  = 0'
					 . ' AND mc_sub_id = ?';
		if ($isCheckAuth && !IS_AUTH) $where .= ' AND is_private = 0';
		$order = ' ORDER BY mc_turn';

		$sql = $select.$where.$order;

		return $this->db->query($sql, array($sub_id));
	}

	function get_list_all()
	{
		$cate_list = array();
		foreach ($this->get_query_list()->result_array() as $row)
		{
			$sub_row = $this->get_list_sub($row['mc_id']);
			$row['cnt_sc_ary'] = count($sub_row);
			$row['sc_ary'] = $sub_row;

			$cate_list[] = $row;
		}

		return $cate_list;
	}

	function get_list_sub($sub_id)
	{
		return $this->get_query_list($sub_id)->result_array();
	}

	function get_id_list($sub_id = 0, $isCheckAuth = true)
	{
		$id_list = array();
		foreach ($this->get_query_list($sub_id, 'mc_id', $isCheckAuth)->result_array() as $row)
		{
			$id_list[] = (int)$row['mc_id'];
		}

		return $id_list;
	}

	function get_row4id($id, $columns = array('mc_name', 'mc_id', 'mc_sub_id'))
	{
		$this->db->select($columns);
		$this->db->where(array('mc_id' => (int)$id));
		$query = $this->db->get('T_mn_cate');

		if (!$query->num_rows()) return array();

		return $query->row_array(0);
	}

	function get_name4id($id)
	{
		$row = $this->category->get_row4id($id, array('mc_name'));
		if (empty($row)) return '';

		return $row['mc_name'];
	}

	function get_private_category_id_list()
	{
		$private_parent_category_id_list = array();
		$sql = 'SELECT mc_id FROM T_mn_cate'
				 . ' WHERE mc_del_flg  = 0'
				 . ' AND mc_sub_id = 0'
				 . ' AND is_private = 1';
		foreach ($this->db->query($sql)->result_array() as $row)
		{
			$private_parent_category_id_list[] = (int)$row['mc_id'];
		}

		$private_category_id_list = array();
		$sql = 'SELECT mc_id FROM T_mn_cate'
				 . ' WHERE mc_del_flg  = 0'
				 . sprintf(' AND (is_private = 1 OR mc_sub_id IN (%s))', implode(',', $private_parent_category_id_list));
		foreach ($this->db->query($sql)->result_array() as $row)
		{
			$private_category_id_list[] = $row['mc_id'];
		}

		return $private_category_id_list;
	}

	function category_id_list4name($name)
	{
		$name = $this->db->escape_like_str($name);
		$params = array('%'.$name.'%');

		$category_id_list = array();
		$sql = 'SELECT mc_id FROM T_mn_cate'
				 . ' WHERE mc_name LIKE ?';
		foreach ($this->db->query($sql, $params)->result_array() as $row)
		{
			$category_id_list[] = (int)$row['mc_id'];
		}

		return $category_id_list;
	}

	function get_id4key($key)
	{
		$this->db->select('mc_id');
		$this->db->where(array('mc_key' => $key));
		$query = $this->db->get('T_mn_cate');
		if (!$query->num_rows()) return 0;

		$row = $query->row_array(0);

		return $row['mc_id'];
	}
}
