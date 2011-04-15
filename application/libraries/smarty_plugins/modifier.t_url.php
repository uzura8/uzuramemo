<?php

// URLをリンクに変更する
function smarty_modifier_t_url($body, $is_blank = true)
{
	return auto_link($body, 'both', true);
}
