<?php

function smarty_modifier_site_output_views4private_quote_flg($private_quote_flg, $type = 'label', $is_add_space = false)
{
	if ($type == 'style')
	{
		$prefix = '';
		if ($is_add_space) $prefix = ' ';

		return $prefix.site_output_style4private_quote_flg($private_quote_flg);
	}

	return site_output_label4private_quote_flg($private_quote_flg);
}
