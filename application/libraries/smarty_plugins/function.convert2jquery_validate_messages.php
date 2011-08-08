<?php

function smarty_function_convert2jquery_validate_messages($params, &$smarty)
{
	$return = array();
	foreach ($params['form_items'] as $key => $items)
	{
		if (empty($items['error_messages'])) continue;

		$error_messages = $items['error_messages'];
		if (!is_array($error_messages))
		{
			$return[] = sprintf('%s: "%s"', $key, $each_messages);
			continue;
		}

		$each_messages = array();
		foreach ($error_messages as $rule => $error_message)
		{
			$each_messages[] = sprintf('%s: "%s"', $rule, $error_message);
		}
		if (!$each_messages) continue;

		$return[] = sprintf('%s: { %s }', $key, implode(', ', $each_messages));
	}

	return implode(', ', $return);
}
