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

	public function convert_string2array($str, $delimitter = ',', $cast_function = '')
	{
		$tmp_list = explode($delimitter, $str);
		$list = array();
		foreach ($tmp_list as $each)
		{
			if ($cast_function && function_exists($cast_function)) $each = $cast_function($each);
			$list[] = $each;
		}
		unset($tmp_list);

		array_unique($list);

		return $list;
	}
}
