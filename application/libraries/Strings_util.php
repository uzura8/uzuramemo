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

	public function convert_style2array($style_str, $is_remove_hyphen_in_key = false)
	{
		$styles = explode(';', $style_str);
		$list = array();
		foreach ($styles as $each)
		{
			$items = explode(':', $each);
			if (count($items) != 2) continue;
			$key   = ($is_remove_hyphen_in_key) ? str_replace('-', '', $items[0]) : $items[0];
			$value = $items[1];
			$list[$key] = $value;
		}

		return $list;
	}

	public function check_is_hex_color_format($value)
	{
		return (bool)preg_match('/^#[0-9a-f]{6}$/i', $value);
	}
}
