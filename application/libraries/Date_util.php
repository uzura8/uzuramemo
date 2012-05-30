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

	public function get_week_name($w)
	{
		$week = array('日', '月', '火', '水', '木', '金', '土');
		return $week[$w];
	}

	public function get_holidays($date_from, $date_to)
	{
		$CI =& get_instance();
		return $CI->db_util->get_assoc('holiday', array('date >=' => $date_from, 'date <=' => $date_to), array('date', 'name'), 'date asc', 'wbs', 'model');
	}

	public function conv2int($date, $delimitter = '-')
	{
		return (int)str_replace($delimitter, '', $date);
	}

	public function get_holidays_from_range($date_from, $range)
	{
		return $this->get_holidays($date_from, date('Y-m-d', strtotime(sprintf('+%d days %s', $range - 1, $date_from))));
	}

	public function get_finish_date($start_date, $range, $holidays)
	{
		$day = '';
		$i = 0;
		while (1)
		{
			if (!$range) break;

			$day = date('Y-m-d', strtotime(sprintf('+ %d days %s', $i, $start_date)));

			if (!isset($w))
			{
				$w = date('w', strtotime($day));
			}
			else
			{
				$w++;
				$w = $this->correct_week_num($w);
			}

			if ($w != 0 && $w != 6 && empty($holidays[$day]))
			{
				$range--;
			}

			$i++;
		}
		if (!$day) return $start_date;

		return $day;
	}

	public function correct_week_num($week_num)
	{
		if ($week_num < 0)
		{
			return $week_num + 7;
		}
		if ($week_num > 6)
		{
			return $week_num - 7;
		}

		return $week_num;
	}

	public function calc_rest_days($date, $base_date = '')
	{
		if (!$base_date) $base_date = date('Y-m-d');

		$time = strtotime($date);
		$base_time = strtotime($base_date);

		return (int)floor(($time - $base_time) / 86400);
	}
}
