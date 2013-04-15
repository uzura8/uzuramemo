<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bin extends BIN_Controller
{
	public function webclip()
	{
		$url = $_SERVER['argv'][3];
		if (!$url) $this->error('No url.');
		$url = prep_url($url);
		if (!is_url($url)) $this->error('No url format.');

		$this->load->library('simple_html_dom');
		$dom = file_get_html($url);
		$title = '';
		foreach($dom->find('h2') as $element) {
			$title = $element->plaintext;
			$find = $element->outertext;
			break;
		}
		$body = $dom->find('body', 0)->innertext;

		$remove_tags = array('script', 'iframe', 'object', 'form');
		foreach($remove_tags as $remove_tag) {
			foreach($dom->find($remove_tag) as $element) {
				$tag = $element->outertext;
				$body = str_replace($tag, '', $body);
			}
		}
		$dom->clear();
		var_dump($title, $url, $body);
	}

	public function mail_clip()
	{
		if (!$this->_check_use_mail_clip())
		{
			$this->_m_debug_log('メールClip機能は使用出来ません');
		}

		/**
		 * ライブラリ読み込み
		 */
		$this->load->library('mail_uploader');
		require_once 'OpenPNE/KtaiMail.php';

		// エラー出力を抑制
		ini_set('display_errors', false);
		@ob_start();

		// 標準入力からメールデータの読み込み
		$stdin = fopen('php://stdin', 'rb');
		$raw_mail = '';
		do {
			$data = fread($stdin, 8192);
			if (strlen($data) == 0) {
				break;
			}
			$raw_mail .= $data;
		} while(true);
		fclose($stdin);

		// メールの処理
		$this->_m_process_mail($raw_mail);

		// デバッグ用ログ保存
		$this->mail_uploader->_m_debug_log(ob_get_contents(), 'debug');

		while (@ob_end_clean());
	}

	private function _check_use_mail_clip()
	{
		if (!UM_USE_MAILCLIP)    return false;
		if (!ADMIN_MAIL_ADDRESS) return false;

		return true;
	}

	/**
	 * メール処理
	 */
	private function _m_process_mail($raw_mail)
	{
		$options['from_encoding'] = UM_MAIL_FROM_ENCODING;
		$options['to_encoding']   = 'UTF-8';
		$options['trim_doublebyte_space'] = UM_TRIM_DOUBLEBYTE_SPACE;

		$decoder = new OpenPNE_KtaiMail($options);
		$decoder->decode($raw_mail);

		$from = $decoder->get_from();
		$to   = $decoder->get_to();

		if (!$this->site_util->is_mailaddress($from) || !$this->site_util->is_mailaddress($to))
		{
			$this->mail_uploader->_m_debug_log('bin::_m_process_mail() ERROR code 3');
			return false;
		}

		if (!$this->_check_is_mailclip_address($to, $from))
		{
			$this->mail_uploader->_m_debug_log('bin::_m_process_mail() ERROR code 4');
			return false;
		}

		$this->mail_uploader->configure($decoder);

		if (!$this->mail_uploader->execute()) {
			$this->mail_uploader->_m_debug_log('bin::_m_process_mail() ERROR code 1');
			return false;
		}

		return true;
	}

	private function _check_is_mailclip_address($to_address, $from_address)
	{
		if (!in_array($from_address, $GLOBALS['UM_MAILCLIP_ACCEPT_ADDRESS'])) return false;
		if ($to_address != UM_MAILCLIP_ADDRESS) return false;

		return true;
	}
}

/* End of file site.php */
/* Location: ./application/controllers/bin.php */
