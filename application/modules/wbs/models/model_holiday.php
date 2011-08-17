<?php
class Model_holiday extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_count($params)
	{
		$this->db->where($params);
		$this->db->from('holiday');

		return (int)$this->db->count_all_results();
	}

	function get_row($id, $is_private = false)
	{
		if (!$id) return array();

		$sql  = $this->get_main_query($is_private);
		$sql .= " AND A.id = ?";

		return $this->db->query($sql, array((int)$id))->result_array();
	}

	function get_main_list($order = 'sort', $with_logical_deleted = false, $columns = '*')
	{
		$sql  = $this->get_main_query(false, $with_logical_deleted, $columns);
		$sql .= sprintf(" ORDER BY %s", $order);

		return $this->db->query($sql)->result_array();
	}

	function get_count_all()
	{
		$sql  = $this->get_main_query(true);
		$row = $this->db->query($sql)->first_row('array');

		return (int)$row['count'];
	}

	private static function get_main_query($is_count = false, $with_logical_deleted = false, $columns = '*')
	{
		if (is_array($columns)) $columns = implode(',', $columns);
		if (!$columns) $columns = '*';

		$select = sprintf("SELECT %s FROM holiday", $columns);
		if ($is_count) $select = "SELECT COUNT(id) as count FROM holiday";

		$where  = '';
		$wheres = array();
		if (!$with_logical_deleted) $wheres[] = "del_flg = 0";
		if ($wheres) $where = ' WHERE '.implode(' AND ', $wheres);

		return $select.$where;
	}

	function get_del_flg4id($id)
	{
		$CI =& get_instance();
		$row = $CI->db_util->get_row4id('holiday', $id, array('del_flg'), 'holiday', 'model');
		if (empty($row)) return 0;

		return (int)$row['del_flg'];
	}

	function get_row_common($params)
	{
		$CI =& get_instance();
		$row = $CI->db_util->get_row('holiday', $params, array(), 'holiday', 'model');
		if (empty($row)) return array();

		return $row;
	}

	public function update($values, $wheres)
	{
		if (!$values || !$wheres) return false;
		return $this->db->update('holiday', $values, $wheres);
	}

	public function update4id($values, $id, $update_datetime = true)
	{
		return $this->update($values, array('id' => $id), $update_datetime);
	}

	public function insert($values)
	{
		$CI =& get_instance();
		$values = $CI->db_util->set_common_column_value($values, true, false);
		$values['sort'] = $CI->db_util->get_sort_max_next('holiday', 'holiday', 'model');

		return $this->db->insert('holiday', $values);
	}

	public function delete4id($id)
	{
		if (!$id) return false;

		$this->db->where('id', $id);
		$this->db->delete('holiday');

		return $this->db->affected_rows();
	}
}
