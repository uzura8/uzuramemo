<?php
class Admin_user extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	private function get_query_list_bcup($columns = array('id', 'username', 'password'), $username = '')
	{
		if ($columns && is_array($columns)) $columns = implode(',', $columns);
		if (!$columns) $columns = '*';
		$select = sprintf("SELECT %s FROM admin_user", $columns);

		$where = '';
		$params = array();
		if ($username)
		{
			$where = ' WHERE username = ?';
			$params = array($username);
		}

		$order = ' ORDER BY id';

		$sql = $select.$where.$order;

		return $this->db->query($sql, $params);
	}

	private function get_query_list($columns = array('id', 'username', 'password'), $where = array())
	{
		if ($columns && is_array($columns)) $columns = implode(',', $columns);
		if ($columns) $this->db->select($columns);

		if ($where) $this->db->where($where);
		$this->db->order_by('id');

		return $this->db->get('admin_user');
	}

	function get_list_all($columns = array('id', 'username', 'password'))
	{
		return $this->get_query_list($columns)->result_array();
	}

	function get_row4username($username, $columns = array('id', 'username'))
	{
		$query = $this->get_query_list($columns, array('username' => $username));
		if (!$query->num_rows()) return array();

		return $query->row_array();
	}

	function get_row4id($id, $columns = array('id', 'username'))
	{
		$query = $this->get_query_list($columns, array('id' => $id));
		if (!$query->num_rows()) return array();

		return $query->row_array();
	}

	function get_password4username($username)
	{
		$row = $this->get_row4username($username, 'password');
		if (!$row) return '';

		return $row['password'];
	}

	public function insert($username, $password)
	{
		return $this->db->insert('admin_user', array('username' => $username, 'password' => md5($password)));
	}

	public function update($username, $password)
	{
		$this->db->where('username', $username);
		return $this->db->update('admin_user', array('username' => $username, 'password' => md5($password)));
	}

	public function delete4id($id)
	{
		return $this->db->delete('admin_user', array('id' => (int)$id));
	}
}
