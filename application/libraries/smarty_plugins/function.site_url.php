<?php

function smarty_function_site_url($params, &$smarty)
{
	return site_url($params['uri']);
}
