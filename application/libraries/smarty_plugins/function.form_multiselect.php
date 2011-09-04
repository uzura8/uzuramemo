<?php

function smarty_function_form_multiselect($params, &$smarty)
{
	$name     = (empty($params['name']))     ? ''      : $params['name'];
	$options  = (empty($params['options']))  ? array() : $params['options'];
	$selected = (empty($params['selected'])) ? array() : $params['selected'];
	$extra    = (empty($params['extra']))    ? ''      : $params['extra'];

	$name .= '[]';

	return form_multiselect($name, $options, $selected, $extra);
}
