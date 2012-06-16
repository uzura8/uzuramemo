<?php

// URLをリンクに変更する
function smarty_modifier_uznl2br($body)
{
	$body = str_replace("\r\n", "\n", $body);
	$body = str_replace("\r", "\n", $body);
	$body = preg_replace("#([\s]*<br[^>]*>[\n]*){2,}#u", "\n\n", $body);
	$body = preg_replace("/([\s]*[\n]+){2,}/u", "\n\n", $body);

	return nl2br($body);
}
