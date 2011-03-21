<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category
 * @package    Session Class
 * @author     KUNIHARU Tsujioka <kunitsuji@gmail.com>
 * @copyright  Copyright (c) 2008 KUNIHARU Tsujioka <kunitsuji@gmail.com>
 * @copyright  Copyright (c) 2006-2008 Usagi Project (URL:http://usagi.mynets.jp)
 * @license    New BSD License
 */

class Session {

	// the ONE TIME Ticket NAME
	private $_ticket = 'OneTimeTicket';

	// constructor
	function __construct()
	{
	}

	/**
	 * 値を返却
	 *
	 * @param   string  key名
	 * @param   string  namespace default='default'
	 * @return  string  値
	 * @access  public
	 */
	public function get($key = null, $namespace = 'default')
	{
		if(isset($key))
		{
			return isset($_SESSION[$namespace][$key]) ? $_SESSION[$namespace][$key] : null;
		}
		else
		{
			return isset($_SESSION[$namespace]) ? $_SESSION[$namespace] : null;
		}
	}

	/**
	 * 値をセット
	 *
	 * @param   string  key名
	 * @param   string  値
	 * @param   string  namespace default='default'
	 * @access  public
	 */
	public function set($key, $value, $namespace = 'default')
	{
		if (!$key)
		{
			$_SESSION[$namespace] = $value;
		}
		else
		{
			$_SESSION[$namespace][$key] = $value;
		}
	}

	/**
	 * Keyの値を削除する
	 * パラメータを渡さない場合は、現在のセッション情報をすべてクリアする
	 *
	 * @param   string  key名
	 * @param   string  namespace default='default'
	 * @access  public
	 */
	public function remove($key = null, $namespace = 'default')
	{
		if(isset($key) && ($key !== null))
		{
			unset($_SESSION[$namespace][$key]);
		}
		else
		{
			unset($_SESSION[$namespace]);
		}
	}

	/**
	 * セッション開始
	 *
	 * @access  public
	 */
	public function start()
	{
		$CI   =& get_instance();
		$path = $CI->config->item('session');
		if ($this->setPath() != $path['save_path'])
		{
			$this->setPath($path['save_path']);
		}
		@session_start();
	}

	/**
	 * セッション終了
	 *
	 * @access  public
	 */
	public function close()
	{
		$_SESSION = array();
		session_destroy();
	}

	/**
	 * セッション名を返却
	 *
	 * @return  string  セッション名
	 * @access  public
	 */
	public function getName()
	{
		return session_name();
	}

	/**
	 * セッションIDをセット
	 *
	 * @param   string  セッションID
	 * @access  public
	 */
	public function setID($id = '')
	{
		if ($id) {
			session_id($id);
		}
	}

	/**
	 * セッションIDを返す
	 *
	 * @return  string  セッションID
	 * @access  public
	 */
	public function getID()
	{
		return session_id();
	}

	/**
	 * save_pathをセット
	 *
	 * @param   string  path
	 * @access  public
	 */
	public function setPath($path = NULL)
	{
		if(isset($path))
		{
			session_save_path($path);
		}
		else if ($path == null)
		{
			return session_save_path();
		}
		else
		{
			return ;
		}
	}

	/**
	 * use_cookies をセット
	 *
	 * @param   bool  use_cookies default true
	 * @access  public
	 */
	public function setUseCookies($useCookies = TRUE)
	{
		if ($useCookies)
		{
			ini_set('session.use_cookies', 1);
		}
		else
		{
			ini_set('session.use_cookies', 0);
		}
	}

	/**
	 * session.gc_maxlifetime をセット
	 *
	 * @param   int	 maxlifetime default 432000(5days) 秒で指定
	 * @access  public
	 */
	public function setSessionMaxLifetime($time = 432000)
	{
		ini_set('session.gc_maxlifetime', $time);
	}

	/**
	 * COOKIEをセット
	 *
	 * @param   string  name
	 * @param   string  value
	 * @param   string  namespace
	 * @access  public
	 */
	public function setCookie($name, $value, $namespace = 'default')
	{
		ini_set('session.gc_maxlifetime', $time);
	}

	/**
	 * ONE TIME Ticketの名前を返す
	 *
	 * @return  string  One Time Ticketの名前
	 * @access  public
	 */
	public function getTicketName()
	{
		return $this->_ticket;
	}

	/**
	 * ONE TIME Ticketの名前を設定
	 *
	 * @param   string  One Time Ticketの名前
	 * @access  public
	 */
	public function setTicketName($ticket)
	{
		$this->_ticket = $ticket;
	}

	/**
	 * ONE TIME Ticketの値を返却
	 *
	 * @return  string  One Time Ticketの値を返却
	 * @access  public
	 */
	public function getTicket()
	{
		return $this->get($this->getTicketName());
	}

	/**
	 * ONE TIME Ticketの値を生成
	 *
	 * @access  public
	 */
	public function buildTicket()
	{
		$this->set($this->getTicketName(), md5(uniqid(rand(),1)));
	}

	/**
	 * ONE TIME Ticketの値を比較
	 *
	 * @param   string  One Time Ticketの値
	 * @return  bool	TRUE or FALSE
	 * @access  public
	 */
	public function checkTicket($value)
	{
		if ($this->getTicket() !== $value)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/**
	 * ONE TIME Ticketの初期化
	 *
	 * @access  public
	 */
	public function initTicket()
	{
		$this->remove($this->getTicketName());
	}

}
