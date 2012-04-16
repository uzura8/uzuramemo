<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Simplelogin Class
 *
 * Makes authentication simple
 *
 * Simplelogin is released to the public domain
 * (use it however you want to)
 * 
 * Simplelogin expects this database setup
 * (if you are not using this setup you may
 * need to do some tweaking)
 * 

	#This is for a MySQL table
	CREATE TABLE `users` (
	`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`username` VARCHAR( 64 ) NOT NULL ,
	`password` VARCHAR( 64 ) NOT NULL ,
	UNIQUE (
	`username`
	)
	);

 * 
 */
class Simplelogin
{
	var $CI;
	var $user_table = 'admin_user';

	function Simplelogin()
	{
		// get_instance does not work well in PHP 4
		// you end up with two instances
		// of the CI object and missing data
		// when you call get_instance in the constructor
		//$this->CI =& get_instance();
	}

	/**
	 * Create a user account
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function create($user = '', $password = '', $auto_login = true) {
		//Put here for PHP 4 users
		$this->CI =& get_instance();		

		//Make sure account info was sent
		if($user == '' OR $password == '') {
			return false;
		}
		
		//Check against user table
		$this->CI->db->where('username', $user); 
		$query = $this->CI->db->getwhere($this->user_table);
		
		if ($query->num_rows() > 0) {
			//username already exists
			return false;
			
		} else {
			//Encrypt password
			$password = md5($password);
			
			//Insert account into the database
			$data = array(
						'username' => $user,
						'password' => $password
					);
			$this->CI->db->set($data); 
			if(!$this->CI->db->insert($this->user_table)) {
				//There was a problem!
				return false;						
			}
			$user_id = $this->CI->db->insert_id();
			
			//Automatically login to created account
			if($auto_login) {		
				//Destroy old session
				$this->CI->session->sess_destroy();
				
				//Create a fresh, brand new session
				$this->CI->session->sess_create();
				
				//Set session data
				$this->CI->session->set_userdata(array('id' => $user_id,'username' => $user));
				
				//Set logged_in to true
				$this->CI->session->set_userdata(array('logged_in' => true));			
			
			}
			
			//Login was successful			
			return true;
		}

	}

	/**
	 * Delete user
	 *
	 * @access	public
	 * @param integer
	 * @return	bool
	 */
	function delete($user_id) {
		//Put here for PHP 4 users
		$this->CI =& get_instance();
		
		if(!is_numeric($user_id)) {
			//There was a problem
			return false;			
		}

		if($this->CI->db->delete($this->user_table, array('id' => $user_id))) {
			//Database call was successful, user is deleted
			return true;
		} else {
			//There was a problem
			return false;
		}
	}


	/**
	 * Login and sets session variables
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function login($user = '', $password = '', $is_save = false) {
		//Put here for PHP 4 users
		$this->CI =& get_instance();		

		//Make sure login info was sent
		if($user == '' OR $password == '') {
			return false;
		}

		//Check if already logged in
		if($this->CI->session->get('username', 'admin_user') == $user) {
			//User is already logged in.
			return false;
		}

		//Check against user table

		$this->CI->db->where(array('username' => $user));
		$query = $this->CI->db->get($this->user_table);

		if (!$query->num_rows()) return false;
		$row = $query->row_array(0);

		//Check against password
		if (md5($password) != $row['password']) return false;

		//Destroy old session
		$this->CI->session->close();
		
		//Create a fresh, brand new session
		$this->CI->session->start();

		//Remove the password field
		unset($row['password']);

		//Set session data
		$this->CI->session->set('username', $row['username'], 'admin_user');

		//Set logged_in to true
		$this->CI->session->set('logged_in', true, 'admin_user');			

		if ($is_save) $this->set_cookie4auto_login();

		//Login was successful			
		return true;
	}

	private function set_cookie4auto_login()
	{
		$expire = time() + 2592000; // 30 days
		setcookie($this->CI->session->getName(), $this->CI->session->getID(), $expire, $this->get_cookie_path());
	}

	/**
	 * cookie_path を OPENPNE_URLから抜き出す
	 */
	public function get_cookie_path()
	{
		$url = parse_url(BASE_URL);
		if (substr($url['path'], -1) != '/')
		{
			$url['path'] .= '/';
		}

		return $url['path'];
	}

	/**
	 * Logout user
	 *
	 * @access	public
	 * @return	void
	 */
	function logout() {
		//Put here for PHP 4 users
		$this->CI =& get_instance();		

		// 自動ログインcookie削除
		$session_name = $this->CI->session->getName();
		if (isset($_COOKIE[$session_name]))
		{
			setcookie($session_name, '', time() - 3600, $this->get_cookie_path());
		}

		//Destroy session
		$this->CI->session->remove(null, 'admin_user');
	}
}
?>
