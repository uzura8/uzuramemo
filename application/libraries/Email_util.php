<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_util
{
	function __construct()
	{
	}

	public function send($to, $subject, $message, $from = ADMIN_MAIL_ADDRESS)
	{
		$CI =& get_instance();
		if ($CI->site_util->is_mailaddress($to)) return false;
		if ($from != ADMIN_MAIL_ADDRESS && $CI->site_util->is_mailaddress($from)) return false;

		$this->email->from($from);
		$this->email->to($to);
		//$this->email->cc('another@another-example.com');
		//$this->email->bcc('them@their-example.com');

		$this->email->subject($subject);
		$this->email->message($message);

		$this->email->send();

		// echo $this->email->print_debugger();
	}
}
