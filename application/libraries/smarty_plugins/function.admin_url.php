<?php

function smarty_function_admin_url($params, &$smarty)
{
	return admin_url($params['uri']);
}
