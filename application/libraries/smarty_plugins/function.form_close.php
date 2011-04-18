<?php

function smarty_function_form_close($params, &$smarty)
{
	$key = 'extra';
	$$key = '';
	if (!empty($params[$key]))
	{
		$$key = $params[$key];
	}

	return form_close($$key);
}
