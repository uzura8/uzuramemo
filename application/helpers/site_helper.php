<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * site helper
 * 
 * @author uzuranoie@gmail.com
 */

function get_config_value($item, $index = '')
{
	$CI =& get_instance();
	if (!$index) return  $CI->config->item($item);

	$values = $CI->config->item($index);
	return $values[$item];
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
	$CI =& get_instance();
	return site_url(get_config_value('admin_path').'/'.$uri);
}

/* End of file site_helper.php */
/* Location: ./application/helpers/site_helper.php */
