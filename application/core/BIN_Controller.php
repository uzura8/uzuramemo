<?php defined('BASEPATH') or exit('No direct script access allowed');

class BIN_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if (!$this->input->is_cli_request()) show_404();
	}

	public function error($message = '')
	{
		if ($message) echo $message.PHP_EOL;
		exit;
	}
}
