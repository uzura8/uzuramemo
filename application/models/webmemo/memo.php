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
}
