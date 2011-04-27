<?php

function smarty_function_get_config_value($params, &$smarty)
{
	if (empty($params['key'])) return '';
	$key = $params['key'];

	if (!empty($params['index'])) $index = $params['index'];
	
	return get_config_value($key, $index);
}
