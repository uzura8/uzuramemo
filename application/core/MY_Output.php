<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Output extends CI_Output
{
	function __construct()
	{
		parent::__construct();
	}

	public function set_json_output($array)
	{
		$this->output->set_header('Content-Type: text/javascript; charset=utf-8');
		$this->output->set_output(json_encode($array));
	}

	public function set_ajax_output_error($message = '')
	{
		$this->output->set_status_header('403', $message);
	}
}
