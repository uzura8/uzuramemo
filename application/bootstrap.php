<?php

// バージョン情報
require_once(UM_DATA_DIR.'/version.php');

$include_paths = array(
	APPPATH.'/libraries/vender',
	APPPATH.'/libraries/vender/PEAR',
	ini_get('include_path'),
);
ini_set('include_path', implode(PATH_SEPARATOR, $include_paths));
