<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Date_util
{

	function __construct()
	{
	}

	public function check_date_format($date)
	{
		if (!preg_match('/2[0-9]{3}\-[0-9]{2}\-[0-9]{2}/', $date)) return false;
		$dates = explode('-', $date);

		return checkdate(intval($dates[1]), intval($dates[2]), intval($dates[0]));
	}

	public function get_week($w)
	{
		$week = array('日', '月', '火', '水', '木', '金', '土');
		return $week[$w];
	}

	public function get_holidays($date_from, $date_to)
	{
		$CI =& get_instance();
		return $CI->db_util->get_assoc('holiday', array('date >=' => $date_from, 'date <=' => $date_to), array('date', 'name'), 'date asc', 'wbs', 'model');
	}
}
