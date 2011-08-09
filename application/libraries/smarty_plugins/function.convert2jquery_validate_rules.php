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
			if (is_array($each_rule))
			{
				$each_rules = array_merge($each_rules, $each_rule);
			}
			else
			{
				$each_rules[] = $each_rule;
			}
		}
		if (!empty($items['custom_rules']))
		{
			$custom_rules = explode('|', $items['custom_rules']);
			foreach ($custom_rules as $rule)
			{
				$each_rules[] = sprintf('%s: true', $rule);
			}
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
	elseif ($rule == 'is_natural_no_zero')
	{
		return array('digits: true', 'min: 1');
	}
	elseif ($rule == 'date_format')
	{
		return 'date_format: true';
	}
}
