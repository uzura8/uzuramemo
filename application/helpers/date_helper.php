<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * date helper
 * 
 * @author uzuranoie@gmail.com
 */

function get_week_name($num)
{
	$CI =& get_instance();
	return $CI->date_util->get_week_name($num);
}

/* End of file date_helper.php */
/* Location: ./application/helpers/date_helper.php */
