<?php

function smarty_modifier_site_get_symbols_for_display($del_flg)
{
	if (empty($del_flg))
	{
		$del_flg = 0;
	}
	else
	{
		$del_flg = 1;
	}

	return site_get_symbols_for_display($del_flg);
}
