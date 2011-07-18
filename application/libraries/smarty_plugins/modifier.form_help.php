<?php

// form の validation ルールより補足文言を取得する
function smarty_modifier_form_help($validate_rules, $default = '')
{
	$rules = explode('|', $validate_rules);

	$helps = array();
	foreach ($rules as $rule)
	{
		if ($rule == 'required')
		{
			$helps[] = '必須';
		}
		elseif (preg_match('/max_length\[([0-9]+)\]/', $rule, $matches))
		{
			$helps[] = sprintf('最大%d文字', $matches[1]);
		}
		elseif ($rule == 'alpha_dash')
		{
			$helps[] = '半角英数, アンダースコア';
		}
	}
	if (!$helps) return $default;

	return implode(', ', $helps);
}
