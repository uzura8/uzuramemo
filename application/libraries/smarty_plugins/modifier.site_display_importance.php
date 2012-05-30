<?php

function smarty_modifier_site_display_importance($importance, $type = 'symbol')
{
	$config = get_config_value('importance_styles', 'site');
	$key = 0;
	if ($importance) $key = 1;

	return $config[$key][$type];
}
