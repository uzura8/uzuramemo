<?php

// URLをリンクに変更する
function smarty_modifier_auto_link($body, $type = 'url', $is_blank = true, $trimwidth = 0)
{
	return auto_link($body, $type, $is_blank, $trimwidth);
}
