<?php

function smarty_function_set_value($params, &$smarty)
{
	return set_value($params['name']);
}
