<?php

function smarty_function_convert2jquery_validate_rules($params, &$smarty)
{
	$return = array();
	foreach ($params['form_items'] as $key => $items)
	{
		$rules = explode('|', $items['rules']);
		$each_rules = array();
		foreach ($rules as $rule)
		{
			if (!$each_rule = _smarty_function_convert2jquery_validate_each_rules($rule)) continue;
			$each_rules[] = $each_rule;
		}
		if (!$each_rules) continue;

		$return[] = sprintf('%s: { %s }', $key, implode(', ', $each_rules));
	}

	return implode(', ', $return);
}

function _smarty_function_convert2jquery_validate_each_rules($rule)
{
	if ($rule == 'required')
	{
		return 'required: true';
	}
	elseif (preg_match('/max_length\[([0-9]+)\]/', $rule, $matches))
	{
		return sprintf('maxlength: %d', $matches[1]);
	}
	elseif ($rule == 'alpha_dash')
	{
		return 'key_name: true';
	}
}
