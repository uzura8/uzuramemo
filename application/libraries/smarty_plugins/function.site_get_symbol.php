<?php

function smarty_function_site_get_symbol($params, &$smarty)
{
	if (empty($params['key'])) return '';

	return site_get_symbol($params['key']);
}
