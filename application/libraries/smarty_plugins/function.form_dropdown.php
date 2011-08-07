<?php

function smarty_function_form_dropdown($params, &$smarty)
{
	$name     = (empty($params['name']))     ? ''      : $params['name'];
	$options  = (empty($params['options']))  ? array() : $params['options'];
	$selected = (empty($params['selected'])) ? array() : $params['selected'];
	$extra    = (empty($params['extra']))    ? ''      : $params['extra'];

	return form_dropdown($name, $options, $selected, $extra);
}
