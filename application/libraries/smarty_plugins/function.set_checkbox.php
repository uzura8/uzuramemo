<?php

function smarty_function_set_checkbox($params, &$smarty)
{
	if (!isset($params['name']) || !isset($params['value'])) return '';
	$default = (isset($params['default'])) ? $params['default'] : false;

	return set_checkbox($params['name'], $params['value'], $default);
}
