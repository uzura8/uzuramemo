<?php

// URLをリンクに変更する
function smarty_modifier_auto_link($body, $is_blank = true)
{
	return auto_link($body, 'both', true);
}
