<?php

function smarty_function_flashdata($params, &$smarty)
{
	$CI =& get_instance();

	$key = 'message';	
	if (!empty($params['key'])) $key = $params['key'];

	return $CI->session->flashdata($key);
}
