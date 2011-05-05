<?php

function smarty_modifier_site_output_private_quote_flg_views($private_flg, $quote_flg, $type = 'label', $add_space = false)
{
	$data = site_output_private_quote_flg_views($private_flg, $quote_flg);
	if (!in_array($type, array('label', 'style'))) $type = 'label';

	$pre_fix = '';
	if ($type == 'style' && $add_space) $pre_fix = ' ';

	return $pre_fix.$data[$type];
}
