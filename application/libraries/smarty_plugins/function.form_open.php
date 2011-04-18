<?php

function smarty_function_form_open($params, &$smarty)
{
	$action = '';
	if (!empty($params['action']))
	{
		$action = $params['action'];
		unset($params['action']);
	}
	if (empty($params['method'])) $params['method'] = 'post';

	return form_open($action, $params);
}
