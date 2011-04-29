<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_util
{

	function __construct()
	{
	}

	public function set_post_data_from_submit_key($submit_key, $post_key)
	{
		$_POST[$post_key] = (int)key($_POST[$submit_key]);
	}
}
