<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PHP標準Session機構を利用できる
 * 
 * @author Tatsuya Fukata
 *
 */
class XC_Session extends CI_Session {
	/** PHP標準のSession機能を使用するか */
	private $sess_use_php_session = false;

	/** 初期化時に読み込む設定ファイルのキー一覧 */
	private static $INIT_CONFIG_KEYS = array(
		'sess_use_php_session',
		'sess_encrypt_cookie',
		'sess_use_database',
		'sess_table_name',
		'sess_expiration',
		'sess_match_ip',
		'sess_match_useragent',
		'sess_cookie_name',
		'cookie_path',
		'cookie_domain',
		'sess_time_to_update',
		'time_reference',
		'cookie_prefix',
		'encryption_key',
	);

	/**
	 * コンストラクタ
	 * @param Array $params
	 */
	public function __construct($params = array()) {
		$this->CI =& get_instance();
		foreach (self::$INIT_CONFIG_KEYS as $key) {
			$this->$key = (isset($params[$key])) ? $params[$key] : $this->CI->config->item($key);
		}

		if ($this->use_not_php_session()) {
			parent::__construct($params);
		} else {
			$this->initialize($params);
		}
	}

	/**
	 * PHP標準Session利用時の初期化
	 * @param Array $params
	 * @return void
	 */
	protected function initialize($params) {
		session_start();

		// XXX スーパクラスのprivateメソッドのため、個別定義
		// Set the "now" time.  Can either be GMT or server time, based on the
		// config prefs.  We use this to set the "last activity" time
		$this->now = $this->_get_time();

		// Set the session length. If the session expiration is
		// set to zero we'll set the expiration two years from now.
		if ($this->sess_expiration == 0) {
			$this->sess_expiration = (60*60*24*365*2);
		}

		// セッションが既に存在すれば更新、未作成の場合はデフォルト値を作成する
		if (!$this->sess_read()) {
			$this->sess_create();
		} else {
			$this->sess_update();
		}

		/*
		 * XXX スーパクラスのprivateメソッドのため、個別定義
		 * メソッド「_flashdata_sweep」「_flashdata_mark」「_sess_gc」は
		 * CI_Sessionにプライベートメソッドを意識して定義されているため、
		 * 本来であれば、XC_Sessionで再定義したいところ。
		 */
		// Delete 'old' flashdata (from last request)
		$this->_flashdata_sweep();

		// Mark all new flashdata as old (data will be deleted before next request)
		$this->_flashdata_mark();

		// Delete expired sessions if necessary
		$this->_sess_gc();


	}

	/**
	 * Override
	 */
	public function sess_read() {
		if ($this->use_not_php_session()) {
			return parent::sess_read();
		}

		if (count($_SESSION) == 0) {
			return false;
		}

		// Is the session current?
		if (($_SESSION['last_activity'] + $this->sess_expiration) < $this->now) {
			$this->sess_destroy();
			return FALSE;
		}

		// Does the IP Match?
		if ($this->sess_match_ip == TRUE AND $_SESSION['ip_address'] != $this->CI->input->ip_address()) {
			$this->sess_destroy();
			return FALSE;
		}

		// Does the User Agent Match?
		if ($this->sess_match_useragent == TRUE AND trim($_SESSION['user_agent']) != trim(substr($this->CI->input->user_agent(), 0, 50))) {
			$this->sess_destroy();
			return FALSE;
		}

		$this->userdata = $_SESSION;
		return true;
	}

	/**
	 * Override
	 */
	public function sess_create() {
		if ($this->use_not_php_session()) {
			parent::sess_create();
			return;
		}

		// 初期データを設定
		$_SESSION = array(
			'session_id' => session_id(),
			'ip_address' => $this->CI->input->ip_address(),
			'user_agent' => substr($this->CI->input->user_agent(), 0, 50),
			'last_activity' => $this->now
		);

		$this->userdata = $_SESSION;
		$this->sess_write();
	}

	/**
	 * Override
	 */
	public function sess_write() {
		if ($this->use_not_php_session()) {
			parent::sess_write();
			return;
		}

		$_SESSION = $this->userdata;
	}

	/**
	 * Override
	 */
	public function sess_update() {
		if ($this->use_not_php_session()) {
			parent::sess_update();
			return;
		}

		// We only update the session every five minutes by default
		if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now) {
			return;
		}

		// 新しいセッションIDの生成し、設定
		session_regenerate_id(true);
		$this->userdata['session_id'] = session_id();
		$this->userdata['last_activity'] = $this->now;

		$this->sess_write();
	}

	/**
	 * Override
	 */
	public function sess_destroy() {
		if ($this->use_not_php_session()) {
			parent::sess_destroy();
			return;
		}

		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}

		$this->userdata = array();
		$this->sess_write();

		session_destroy();
	}

	/**
	 * PHP標準Sessionを利用するかを返す。
	 * @return bool
	 */
	protected function use_php_session() {
		return $this->sess_use_php_session === true;
	}

	/**
	 * PHP標準Sessionを利用しないかを返す。
	 * @return bool
	 */
	protected function use_not_php_session() {
		return !$this->use_php_session();
	}

	/**
	 * Fetch a specific item from the session array
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function userdata($item)
	{
		return ( ! isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
	}

	/**
	 * Add or change data in the "userdata" array
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function set_userdata($newdata = array(), $newval = '')
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => $newval);
		}

		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				$this->userdata[$key] = $val;
			}
		}

		$this->sess_write();
	}
}
