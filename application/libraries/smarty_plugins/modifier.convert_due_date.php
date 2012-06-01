<?php

function smarty_modifier_convert_due_date($due_date, $type = 'rest_days')
{
	return site_convert_due_date($due_date, $type);
}
