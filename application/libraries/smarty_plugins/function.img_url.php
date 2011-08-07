<?php

function smarty_function_img_url($params, &$smarty)
{
	return site_url('img/'.$params['uri']);
}
