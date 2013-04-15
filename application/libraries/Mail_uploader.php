<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail_uploader
{
	var $decoder;
	var $from;
	var $to;

	//var $c_member_id;

	public function __construct()
	{
	}

	public function configure(&$decoder)
	{
		$this->decoder =& $decoder;
		$this->from = $decoder->get_from();
		$this->to = $decoder->get_to();
	}

	public function execute()
	{
//        $matches = array();
//        list($from_user, $from_host) = explode('@', $this->from, 2);
//        list($to_user, $to_host) = explode('@', $this->to, 2);

		$CI =& get_instance();

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

		$this->_m_debug_log('Mail_uploader::execute() action not found(member)');
		return false;
	}

	private function add_memo()
	{
		$CI =& get_instance();

		$urls = $CI->site_util->get_url_from_body($this->decoder->get_text_body(), true);
		if (empty($urls))
		{
			$this->error_mail('URLが取得できなかったため、投稿できませんでした。');
			$this->_m_debug_log('Mail_uploader::add_memo() url is empty');

			return false;
		}
		if (!is_array($urls)) $urls = (array)$urls;

		foreach ($urls as $url)
		{
			$persed_url = $CI->site_util->perse_url($url);
			if (empty($persed_url)) continue;

			$values = array();
			$values['title']   = (!empty($persed_url['title'])) ? $persed_url['title'] : '';
			$values['body']    = mb_convert_encoding($persed_url['body'], 'UTF-8', 'auto');
			$private_quote_flg = 0;// 要確認
			$values['explain'] = $url;
			$values['memo_category_id'] = UM_UNDEFINED_MEMO_CATEGORY_ID;

			// DBに追加
			$CI->load->model('webmemo/memo');
			$insert_id = $CI->memo->insert($values);
		}

		return true;
	}

	/**
	 * エラーメールをメール送信者へ返信
	 */
	private function error_mail($body)
	{
		$CI =& get_instance();
		$CI->load->library('email_util');

		$subject = '['.SITE_TITLE_WEBMEMO.']メール投稿エラー';
		$CI->email_util->send($this->from, $subject, $body);
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
