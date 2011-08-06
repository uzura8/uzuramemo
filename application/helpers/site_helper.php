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

/* End of file site_helper.php */
/* Location: ./application/helpers/site_helper.php */
