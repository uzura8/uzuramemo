<?php
class Model_calendar extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_row_common($params)
	{
		$CI =& get_instance();
		$row = $CI->db_util->get_row('calendar', $params, array(), 'gantt', 'model');
		if (empty($row)) return array();

		return $row;
	}

	public function update($values, $wheres, $update_datetime = true)
	{
		if (!$values || !$wheres) return false;
		if ($update_datetime) $values['updated_at'] = date('Y-m-d H:i:s');

		return $this->db->update('calendar', $values, $wheres);
	}

	public function update4id($values, $id, $update_datetime = true)
	{
		return $this->update($values, array('id' => $id), $update_datetime);
	}

	public function insert($values)
	{
		$CI =& get_instance();
		$values = $CI->db_util->set_common_column_value($values);

		return $this->db->insert('calendar', $values);
	}

	public function delete4id($id)
	{
		if (!$id) return false;

		$this->db->where('id', $id);
		$this->db->delete('calendar');

		return $this->db->affected_rows();
	}
}
