<?php

// URLをリンクに変更する
function smarty_modifier_t_url($body, $is_blank = true)
{
	$target = '';
	if ($is_blank) $target = ' target="_blank"';

	$body = preg_replace('/((?:https*|ftp):\\/\\/[a-zA-Z0-9:;~%#?&=\\-\\+_\\.\\/]+)/i', '<a href="\\1" rel="nofollow"'.$target.'>\\1</a>', $body);
	$body = preg_replace('/([a-z0-9]+@([a-z0-9]+\.)+[a-z]{2,})/', '<a href="mailto:$1">$1</a>', $body);

	return $body;
}
