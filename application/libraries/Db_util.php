<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db_util
{
	function __construct()
	{
	}

	private function _get_model_file($table, $model_path = '', $model_prefix = '', $model_file_name = '')
	{
		$model_file = $model_file_name;
		if (!$model_file) $model_file = $table;
		if ($model_prefix) $model_prefix = trim($model_prefix, '_').'_';
		if ($model_path)   $model_file   = trim($model_path, '/').'/'.$model_prefix.$model_file;

		return $model_file;
	}

	public function set_common_column_value($values, $use_del_flg = true)
	{
		if ($use_del_flg) $values['del_flg'] = 0;
		$values['created_at'] = date('Y-m-d H:i:s');
		$values['updated_at'] = date('Y-m-d H:i:s');

		return $values;
	}

	public function get_row($table, $params = array(), $columns = array(), $model_path = '', $model_prefix = '', $model_file_name = '')
	{
		$CI =& get_instance();
		$CI->load->model($this->_get_model_file($table, $model_path, $model_prefix, $model_file_name));

		if ($columns) $CI->db->select($columns);
		if ($params)  $CI->db->where($params);
		$query = $CI->db->get($table);
		if (!$query->num_rows()) return array();

		return $query->row_array(0);
	}

	public function get_row4id($table, $id, $columns = array(), $model_path = '', $model_prefix = '', $model_file_name = '')
	{
		return $this->get_row($table, array('id' => (int)$id), $columns, $model_path, $model_prefix, $model_file_name);
	}

	public function get_sort_max_next($table, $model_path = '', $model_prefix = '', $params = array(), $sort_column = 'sort', $max_value = 999999, $model_file_name = '')
	{
		$CI =& get_instance();
		$CI->load->model($this->_get_model_file($table, $model_path, $model_prefix, $model_file_name));

		$CI->db->select_max($sort_column, 'max');
		if ($params) $CI->db->where($params);
		$query = $CI->db->get($table);
		if (!$query->num_rows()) return 1;

		$row = $query->row_array(0);
		$sort = $row['max'] + 1;
		if ($sort > $max_value) $sort = $max_value;

		return $sort;
	}
}
