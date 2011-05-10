<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * common helper
 * 
 * @author uzuranoie@gmail.com
 */

function common_error($message = '')
{
	if (DEV_MODE) show_error($message);

	show_404();
}

/* End of file common_helper.php */
/* Location: ./application/helpers/common_helper.php */
