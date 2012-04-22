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

	public function set_common_column_value($values, $use_del_flg = true, $use_datetime = true)
	{
		if ($use_del_flg) $values['del_flg'] = 0;
		$values['created_at'] = date('Y-m-d H:i:s');
		$values['updated_at'] = date('Y-m-d H:i:s');

		return $values;
	}

	public function get_result_array($table, $params = array(), $columns = array(), $order = '', $model_path = '', $model_prefix = '', $model_file_name = '')
	{
		$CI =& get_instance();
		$CI->load->model($this->_get_model_file($table, $model_path, $model_prefix, $model_file_name));

		if ($columns) $CI->db->select($columns);

		if ($params)
		{
			$row = each($params);
			if (count($params) === 1 && is_array($row['value']))
			{
				$row = each($params);
				$CI->db->where_in($row['key'], $row['value']);
			}
			else
			{
				$CI->db->where($params);
			}
		}


		if ($order)   $CI->db->order_by($order);
		$query = $CI->db->get($table);
		if (!$query->num_rows()) return array();

		return $query->result_array();
	}

	public function get_cols($table, $params = array(), $column = array(), $order = '', $model_path = '', $model_prefix = '', $model_file_name = '')
	{
		$return = array();
		$rows = $this->get_result_array($table, $params, $column, $order, $model_path, $model_prefix, $model_file_name);
		foreach ($rows as $row)
		{
			$return[] = $row[$column];
		}

		return $return;
	}

	public function get_assoc($table, $params = array(), $columns = array(), $order = '', $model_path = '', $model_prefix = '', $model_file_name = '')
	{
		$return = array();
		if (count($columns) < 2) return $return;
		$key = $columns[0];
		$value = $columns[1];
		$rows = $this->get_result_array($table, $params, array($key, $value), $order, $model_path, $model_prefix, $model_file_name);
		foreach ($rows as $row)
		{
			$return[$row[$key]] = $row[$value];
		}

		return $return;
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

	public function get_col4id($table, $id, $column, $model_path = '', $model_prefix = '', $model_file_name = '')
	{
		$row = $this->get_row4id($table, $id, array($column), $model_path, $model_prefix, $model_file_name);
		if (empty($row[$column])) return false;

		return $row[$column];
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

	public function convert2assoc($rows, $key_col = '', $value_col = '')
	{
		$return = array();
		foreach ($rows as $row)
		{
			if ($key_col && $value_col)
			{
				$return[$row[$key_col]] = $row[$value_col];
			}
			else
			{
				$key = array_shift($row);
				$value = array_shift($row);
				$return[$key] = $value;
			}
		}

		return $return;
	}

	public function get_where_clauses($key, $values, $is_ignore_empty_value = true)
	{
		$sql = array();
		$params = array();

		if (is_array($values))
		{
			$prepares = array();
			foreach ($values as $value)
			{
				if ($is_ignore_empty_value && empty($value)) continue;

				$prepares[] = '?';
				$params[]   = $value;
			}
			if ($prepares) $sql[] = sprintf('%s IN (%s)', $key, implode(',', $prepares));
		}
		else
		{
			if (!$is_ignore_empty_value || ($is_ignore_empty_value && !empty($values)))
			$sql[]    = $key.' = ?';
			$params[] = $values;
		}

		return array($sql, $params);
	}

	public function update($table, $values, $wheres, $update_datetime = true, $model_path = '', $model_prefix = '', $model_file_name = '')
	{
		$CI =& get_instance();
		$CI->load->model($this->_get_model_file($table, $model_path, $model_prefix, $model_file_name));

		if ($update_datetime) $values['updated_at'] = date('Y-m-d H:i:s');
		$CI->db->update($table, $values, $wheres);

		return $CI->db->update($table, $values, $wheres);
	}
}
