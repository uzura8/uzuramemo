<?php
class Category extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	private function get_query_list_old($sub_id = 0, $colmns = array('mc_id', 'mc_name', 'mc_key', 'is_private'), $isCheckAuth = false)
	{
		$this->db->select($colmns);
		$this->db->where(array('mc_sub_id' => (int)$sub_id, 'mc_del_flg' => 0));
		if ($isCheckAuth && !IS_AUTH) $this->db->where(array('is_private' =>  0));
		$this->db->order_by('mc_turn');

		return $this->db->get('T_mn_cate');
	}

	private function get_query_list($sub_id = 0, $isCheckAuth = true)
	{

		$select = "SELECT mc_id, mc_name, mc_key, is_private FROM T_mn_cate";
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

	function get_id_list($isCheckAuth = false)
	{
		$id_list = array();
		foreach ($this->get_query_list(0, 'mc_id', $isCheckAuth)->result_array() as $row)
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

		return $query->row();
	}
}
