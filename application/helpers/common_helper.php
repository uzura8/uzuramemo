<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * common helper
 * 
 * @author uzuranoie@gmail.com
 */

function get_current_page_id($delimitter = '_')
{
	return implode($delimitter, array(CURRENT_MODULE, CURRENT_CONTROLLER, CURRENT_ACTION));
}

function common_error($message = '')
{
	if (DEV_MODE) show_error($message);

	show_404();
}

function hsc($var)
{
	return htmlspecialchars($var, ENT_QUOTES, config_item('charset'));
}

/* End of file common_helper.php */
/* Location: ./application/helpers/common_helper.php */
