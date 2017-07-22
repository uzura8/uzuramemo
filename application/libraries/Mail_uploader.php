<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail_uploader
{
	private $CI;
	private $decoder;
	private $from;
	private $to;

	public function configure(&$decoder)
	{
		$this->CI =& get_instance();
		$this->decoder =& $decoder;
		$this->from = $decoder->get_from();
		$this->to = $decoder->get_to();
	}

	public function execute()
	{
		// メンテナンスモード
		if (UM_UNDER_MAINTENANCE) {
			$this->error_mail('現在メンテナンス中のため、メール投稿はおこなえません。しばらく時間を空けて再度送信してください。');
			$this->_m_debug_log('Mail_uploader::execute() maintenance mode');

			return false;
		}

		if ($this->to == UM_MAILCLIP_ADDRESS)
		{
			$this->_m_debug_log('Mail_uploader::execute() ERROR code 1', 'debug');
			return $this->add_memo();
		}
		elseif ($this->to == UM_MAILCLIP4LINK_ADDRESS)
		{
			$this->_m_debug_log('Mail_uploader::execute() ERROR code 2', 'debug');
			return $this->add_link();
		}
		elseif ($this->to == UM_MAILCLIP4TASK_ADDRESS)
		{
			$this->_m_debug_log('Mail_uploader::execute() ERROR code 2', 'debug');
			return $this->add_task($matches[1], $this->from);
		}
		elseif (preg_match('/([0-9a-z]+)@uzuralife.com/', $this->to, $matches))
		{
			$this->_m_debug_log('Mail_uploader::execute() ERROR code 2', 'debug');
			return $this->update_pal_user_pre($matches[1], $this->from);
		}

		$this->_m_debug_log('Mail_uploader::execute() unmach mail address for clip');
		return false;
	}

	private function add_memo()
	{
		$this->insert4memo($this->get_urls('add_memo'));

		return true;
	}

	private function add_link()
	{
		$this->insert4memo($this->get_urls('add_link'), true);

		return true;
	}

	private function get_urls($method_name)
	{
		if (!$urls = $this->CI->site_util->get_url_from_body($this->decoder->get_text_body(), true, true))
		{
			$this->error_mail('URLが取得できなかったため、投稿できませんでした。');
			$this->_m_debug_log('Mail_uploader::'.$method_name.'() url is empty');

			return false;
		}
		if (!is_array($urls)) $urls = (array)$urls;

		return $urls;
	}

	private function insert4memo($urls, $is_link = false)
	{
		$this->CI->load->model('webmemo/memo');
		foreach ($urls as $url)
		{
			if (in_array($url, $GLOBALS['UM_MAILCLIP_IGNORE_URL'])) continue;

			$parsed_data = $this->CI->site_util->parse_url($url);
			if (empty($parsed_data)) $parsed_data = '';

			// DBに追加
			$this->CI->memo->insert($this->get_insert_values4memo($url, $parsed_data, $is_link));
		}
	}

	private function get_insert_values4memo($url, $parsed_data, $is_link = false)
	{
		$values = array();
		$values['title']       = (!empty($parsed_data['title']))? $parsed_data['title'] : $url;
		$values['body']        = $this->get_body($url, $parsed_data['body'], $is_link);
		$values['private_flg'] = 1;
		$values['format']      = ($is_link)? 0 : 1;
		$values['quote_flg']   = ($is_link)? 0 : 1;
		$values['explain']     = ($is_link)? '' : $url;
		$values['memo_category_id'] = ($is_link)? UM_MAILCLIP4LINK_CATEGORY_ID : 0;

		return $values;
	}

	private function get_body($url, $parsed_body, $is_link = false)
	{
		if ($is_link) return $url;

		return  mb_convert_encoding($parsed_body, 'UTF-8', 'auto');
	}

	/**
	 * エラーメールをメール送信者へ返信
	 */
	private function error_mail($body)
	{
		$this->CI->load->library('email_util');

		$subject = '['.SITE_TITLE_WEBMEMO.']メール投稿エラー';
		$this->CI->email_util->send($this->from, $subject, $body);
	}

	/**
	 * デバッグ用ログ保存
	 */
	public function _m_debug_log($msg, $priority = 'error')
	{
		if (!UM_MAIL_DEBUG_LOG) return;

		mb_convert_encoding($msg, 'JIS', 'auto');
		//$file->log($msg, $priority);
		log_message($priority, $msg);
	}

	private function add_task()
	{
    $subject = str_replace('Fwd: ', '', $this->decoder->get_subject());
    $body = trim($this->decoder->get_text_body());
    list($spent_time, $scheduled_date, $subject_add_info) = self::get_task_info_from_body($body);
    $del_flg = ($spent_time > 0) ? 1 : 0;
    if ($subject_add_info) $subject .= $subject_add_info;

    $user = 'root';
    $pass = 'sy7008';
    try {
       $dbh = new PDO('mysql:host=localhost;dbname=task_uzuralife_com;charset=utf8', $user, $pass);
    } catch (PDOException $e) {
       print "エラー!: " . $e->getMessage() . "
    ";
       die();
    }
    //$sql = "update pal_user_pre set email = ? where token = ? and tel = ?";
    $sql = "select MAX(sort) from activity";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $sort = (int)$sth->fetchColumn();

    $sql = "insert into activity values (NULL, 278, ?, ?, '', 0, ?, NULL, NULL, 0.25, ?, 0, 0, 0, ?, ?, NOW(), NOW())";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($subject, $body, $scheduled_date, $spent_time, $sort, $del_flg));
	}

	private static function get_task_info_from_body($body)
	{
    $spent_time = 0;
    $scheduled_date = date('Y-m-d');
    $subject_add_info = '';

    $body = str_replace("\r\n", "\n", $body);
    $body = str_replace("\r", "\n", $body);
    $body = str_replace("\n\n", "\n", $body);
    $lines = explode("\n", $body);
    $i = 0;
    foreach ($lines as $line)
    {
      $i++;
      $line = trim($line);
      if ($i == 1 && (is_numeric($line) || in_array($line, array('def', 'd', 'short', 's'))))
      {
        if (in_array($line, array('def', 'd', 'short', 's'))) $line = 0.25;
        $spent_time = (float)$line;
      }
      elseif ($i == 2 && preg_match('/([\+\-]{1})?([0-9]+)/i', $line, $matches))
      {
        $prefix = $matches[1] ?: '+';
        $add_date = $prefix.intval($matches[2]).' day';
        $scheduled_date = date('Y-m-d', strtotime($add_date));
      }
      elseif ($i == 3 && strlen($line))
      {
        $subject_add_info = trim($line);
        if ($subject_add_info) $subject_add_info = ' ->'.$subject_add_info;
      }

      if ($i > 3) break;
    }

    return array($spent_time, $scheduled_date, $subject_add_info);
  }

	private function update_pal_user_pre($token, $email)
	{
    $body = $this->decoder->get_text_body();
    if (!preg_match('/([0-9]{10,11})/', $body, $matches)) return false;
    $tel = $matches[1];

    $user = 'root';
    $pass = 'sy7008';
    try {
       $dbh = new PDO('mysql:host=localhost;dbname=smstest_uzuralife_com', $user, $pass);
    } catch (PDOException $e) {
       print "エラー!: " . $e->getMessage() . "
    ";
       die();
    }
    $sql = "update pal_user_pre set email = ? where token = ? and tel = ?";
    $sth = $dbh->prepare($sql);
    $sth->execute(array($email, $token, $tel));
	}
}
