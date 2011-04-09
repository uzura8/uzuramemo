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

		return $this->db->get('T_manual')->result_array();
	}

	function get_main_list($is_private = false, $search = '', $order = 'lastdate', $offset = 0, $limit = 10)
	{
		if (!$order) $order = 'lastdate';
		$this->db->order_by('T_manual.'.$order, 'desc');

		$sql = "SELECT A.*, B.mc_name FROM T_manual A"
				 . " LEFT JOIN T_mn_cate B ON A.mc_id = B.mc_id"
				 . " WHERE A.mn_del_flg=0"
				 . sprintf(" ORDER BY A.%s desc", $order)
				 . sprintf(" LIMIT %d, %d", $offset, $limit);

		return $this->db->query($sql)->result_array();
	}

	function get_main_list_old($is_private = false, $search = '', $order = 'lastdate', $offset = 0, $limit = 10)
	{
		$this->db->select('T_manual.*, T_mn_cate.mc_name');
		$this->db->from('T_manual');
		$this->db->join('T_mn_cate', 'T_mn_cate.mc_id = T_manual.mc_id');
		$this->db->where(array('T_manual.mn_del_flg' => 0, 'T_manual.private_flg' => (int)$is_private));
		if (!$order) $order = 'lastdate';
		$this->db->order_by('T_manual.'.$order, 'desc');
		$this->db->limit($offset, $limit);

		return $this->db->get()->result_array();
/*
		foreach ( as $row)
		{
			$mn_exp_v = _getBodyHtml($mn_exp_v, 1);
			$mn_exp_v = nl2br($mn_exp_v);//HTMLタグの除去
			$row['mn_exp_v'] = $mn_exp_v;
			if (strlen($mn_exp_v)) {
					$row['exp_flg'] = 1;
			} else {
					$row['exp_flg'] = 0;
			}
			$sql_list[] = $row;
		}
*/
	}

	function get_count_all($is_private = false, $search = '')
	{
		$sql = "SELECT count(mn_id) as count FROM T_manual"
				 . " WHERE mn_del_flg=0";
		$row = $this->db->query($sql)->first_row('array');

		return (int)$row['count'];
	}

	function get_main_list_query($limit, $offset = 0, $order = 'lastdate', $is_private = false, $search = '')
	{
		$sql = "SELECT A.*, B.mc_name FROM T_manual A"
				 . " LEFT JOIN T_mn_cate B ON A.mc_id = B.mc_id"
				 . " WHERE A.mn_del_flg=0"
				 . sprintf(" ORDER BY A.%s desc", $order)
				 . sprintf(" LIMIT %d, %d", $offset, $limit);
	}
}
