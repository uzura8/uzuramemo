<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	function __construct()
	{
			parent::__construct();
	}

	public function is_int_bool($str)
	{
		return (!in_array((int)$str, array(0, 1))) ? FALSE : TRUE;
	}
}
