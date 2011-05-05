<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db_util
{

	function __construct()
	{
	}

	public function get_row4id($table, $id, $columns, $model_path = '')
	{
		$model_file = $table;
		if ($model_path) $model_file = trim($model_path, '/').'/'.$table;

		$CI =& get_instance();
		$CI->load->model($model_file);
		$CI->db->select($columns);
		$CI->db->where(array('id' => (int)$id));
		$query = $CI->db->get($table);
		if (!$query->num_rows()) return array();

		return $query->row_array(0);
	}
}
