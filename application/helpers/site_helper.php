<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * site helper
 * 
 * @author uzuranoie@gmail.com
 */

function get_config_value($item, $index = '')
{
	$CI =& get_instance();

	return  $CI->config->item($item, $index);
}

function site_redirect($uri = '', $message = '')
{
	$CI =& get_instance();
	if ($message) $CI->session->set_flashdata('message', $message);
	if (!$uri) $uri = site_url();

	redirect($uri);
}

function admin_url($uri = '')
{
	return site_url(get_config_value('admin_path', 'site').'/'.$uri);
}

function site_get_symbol($key)
{
	if (!$symbols = get_config_value('symbols', 'site')) return '';
	if (empty($symbols[$key])) return '';

	return $symbols[$key];
}

function site_get_symbols_for_display($del_flg = 0)
{
	if ($del_flg) return site_get_symbol('display_none');

	return site_get_symbol('display');
}

function site_get_style($property, $type)
{
	if (!$styles = get_config_value('styles', 'site')) return '';

	$property = str_replace('-', '', $property);

	if ($type == 1)
	{
		$type = 'display_none';
	}
	elseif ($type == 0)
	{
		$type = 'display';
	}

	if (empty($styles[$property][$type])) return '';

	return $styles[$property][$type];
}

function site_get_activity_style($property, $del_flg, $closed_date, $scheduled_date, $status)
{
	if (!$styles = get_config_value('styles', 'site')) return '';

	$property = str_replace('-', '', $property);

	if ($del_flg == 1)
	{
		$type = 'display_none';
	}
	elseif ($closed_date && $closed_date != '0000-00-00')
	{
		$type = 'display_none';
	}
	elseif ($status)
	{
		$type = 'active';
	}
	elseif ($scheduled_date && $scheduled_date != '0000-00-00' && $scheduled_date < date('Y-m-d'))
	{
		$type = 'scheduled_passed';
	}
	elseif ($scheduled_date == date('Y-m-d'))
	{
		$type = 'scheduled_today';
	}
	elseif ($scheduled_date == date('Y-m-d', strtotime('+1 day')))
	{
		$type = 'scheduled_tomorrow';
	}
	elseif ($scheduled_date && $scheduled_date <= date('Y-m-d', strtotime('+7 day')))
	{
		$type = 'scheduled_this_week';
	}
	else
	{
		$type = 'display';
	}

	if (empty($styles[$property][$type])) return '';

	return $styles[$property][$type];
}

function site_output_private_quote_flg_views($private_flg = 0, $quote_flg = 0)
{
	$private_quote_flg = site_synthesize2private_quote_flg($private_flg, $quote_flg);

	$return = array();
	$return['label'] = site_output_label4private_quote_flg($private_quote_flg);
	$return['style'] = site_output_style4private_quote_flg($private_quote_flg);

	return $return;
}

function site_output_style4private_quote_flg($private_quote_flg)
{
	switch ($private_quote_flg)
	{
		case 1:
			return 'f_bl';
			break;
		case 2:
			return '';
			break;
		default :
			return 'f_red f_bld';
			break;
	}
}

function site_output_label4private_quote_flg($private_quote_flg)
{
	switch ($private_quote_flg)
	{
		case 1:
			return '引用公開';
			break;
		case 2:
			return '公開';
			break;
		default :
			return '非公開';
			break;
	}
}

function site_synthesize2private_quote_flg($private_flg, $quote_flg)
{
	$return = 2;
	if ($private_flg)
	{
		$return = 0;
	}
	elseif ($quote_flg)
	{
		$return = 1;
	}
	if (!UM_USE_QUOTE_ARTICLE_VIEW && $return == 1) $return = 0;

	return $return;
}

function site_divide2private_quote_flg($private_quote_flg)
{
	if (!UM_USE_QUOTE_ARTICLE_VIEW && $private_quote_flg == 1) $private_quote_flg = 0;
	$return = array();
	switch ($private_quote_flg)
	{
		case 1:
			$return['private_flg'] = 0;
			$return['quote_flg']   = 1;
			break;
		case 2:
			$return['private_flg'] = 0;
			$return['quote_flg']   = 0;
			break;
		default :
			$return['private_flg'] = 1;
			$return['quote_flg']   = 0;
			break;
	}

	return $return;
}

function site_convert_due_date($due_date, $type = 'rest_days')
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

function site_get_beginning_week_date($ymd)
{
	$w = date('w',strtotime($ymd)) - 1;
	$beginning_week_date =
			date('Y-m-d', strtotime("-{$w} day", strtotime($ymd)));
	return $beginning_week_date;
}

/* End of file site_helper.php */
/* Location: ./application/helpers/site_helper.php */
