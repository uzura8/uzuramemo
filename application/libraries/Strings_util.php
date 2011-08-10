<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Strings_util
{

	function __construct()
	{
	}

	public function get_prefix($str, $delimitter = '_')
	{
		$pieces = explode($delimitter, $str);
		if (count($pieces) < 2) return '';

		return array_shift($pieces);
	}
}
