<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * site helper
 * 
 * @author uzuranoie@gmail.com
 */

function get_config_value($item, $index = '')
{
	$CI =& get_instance();
//	$CI->config->load('admin', true);
	if (!$index) return  $CI->config->item($item);

	$values = $CI->config->item($index);
	return $values[$item];
}

/* End of file site_helper.php */
/* Location: ./application/helpers/site_helper.php */
