<?php

// URLをリンクに変更する
function smarty_modifier_auto_link($body, $is_blank = true, $trimwidth = 0)
{
	return auto_link($body, 'both', $is_blank, $trimwidth);
}
