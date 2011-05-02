<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Input extends CI_Input
{
	function __construct()
	{
		parent::__construct();
	}

	public function check_is_post()
	{
		if ($this->server('REQUEST_METHOD') != 'POST') show_404();
	}
}
