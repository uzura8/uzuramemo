<?php

function smarty_modifier_mb_truncate($string, $length = 20, $end = '...', $enc = null)
{
	if(!$length) return '';
	if(empty($enc)) $enc = mb_detect_encoding($string);
	if (mb_strlen($string, $enc) <= $length) return $string;

	return mb_substr($string, 0, $length, $enc).$end;
}
