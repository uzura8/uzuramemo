<?php

function smarty_function_convert2ajax_post_string($params, &$smarty)
{
	$list = array();
	foreach ($params['form_items'] as $key => $items)
	{
		$list[] = sprintf('"%s" : $( \'%s#%s\' ).val()', $key, $items['type'], $key);
	}

	return implode(', ', $list);
}
