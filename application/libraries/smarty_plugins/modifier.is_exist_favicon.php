<?php

function smarty_modifier_is_exist_favicon($module)
{
	return file_exists(UM_BASE_DIR.'/img/favicon/'.$module.'.ico');
}
