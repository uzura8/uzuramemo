<?php

function smarty_modifier_convert_due_date($due_date, $type = 'rest_days')
{
	$config = get_config_value('due_date_styles', 'site');
	$CI =& get_instance();
	if ($type == 'rest_days')
	{
		$rest_days = $CI->date_util->calc_rest_days($due_date);
		if ($rest_days == 0) return 'today';
		if ($rest_days < 0) return 'exc '.abs($rest_days).'days';
		return $rest_days.'days';
	}

	$check_date = $CI->date_util->conv2int($due_date);
	$key = '';
	if ($check_date < $config['today']['range_to'])
	{
		$key = 'past';
	}
	elseif ($check_date == $config['today']['range_to'])
	{
		$key = 'today';
	}
	elseif ($check_date == $config['tomorrow']['range_to'])
	{
		$key = 'tomorrow';
	}
	elseif ($check_date > $config['tomorrow']['range_to'] && $check_date <= $config['this_week']['range_to'])
	{
		$key = 'this_week';
	}
	elseif ($check_date > $config['this_week']['range_to'] && $check_date <= $config['this_month']['range_to'])
	{
		$key = 'this_month';
	}
	elseif ($check_date > $config['this_month']['range_to'] && $check_date <= $config['three_month']['range_to'])
	{
		$key = 'three_month';
	}
	elseif ($check_date > $config['three_month']['range_to'] && $check_date <= $config['half_year']['range_to'])
	{
		$key = 'half_year';
	}

	return sprintf("color:%s;background-color:%s;", $config[$key]['color'], $config[$key]['background-color']);
}
