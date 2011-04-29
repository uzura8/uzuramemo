<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * admin helper
 * 
 * @author uzuranoie@gmail.com
 */

function admin_redirect($uri = '', $message = '')
{
	site_redirect(admin_url($uri), $message);
}

/* End of file admin_helper.php */
/* Location: ./application/helpers/admin_helper.php */
