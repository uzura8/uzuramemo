<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db_util
{

	function __construct()
	{
	}

	public function get_row($table, $params = array(), $columns = array(), $model_path = '', $model_prefix = '')
	{
		$model_file = $table;
		if ($model_prefix) $model_prefix = trim($model_prefix, '_').'_';
		if ($model_path)   $model_file   = trim($model_path, '/').'/'.$model_prefix.$table;

		$CI =& get_instance();
		$CI->load->model($model_file);
		if ($columns) $CI->db->select($columns);
		if ($params)  $CI->db->where($params);
		$query = $CI->db->get($table);
		if (!$query->num_rows()) return array();

		return $query->row_array(0);
	}

	public function get_row4id($table, $id, $columns = array(), $model_path = '', $model_prefix = '')
	{
		return $this->get_row($table, array('id' => (int)$id), $columns, $model_path, $model_prefix);
	}
}
