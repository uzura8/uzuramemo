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
		if (!$urls = $this->CI->site_util->get_url_from_body($this->decoder->get_text_body(), true))
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
			$parsed_data = $this->CI->site_util->perse_url($url);
			if (empty($parsed_data)) continue;

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
		$values['quote_flg']   = 1;
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
}
