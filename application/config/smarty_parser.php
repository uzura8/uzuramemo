<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// Please see Smarty user guide for more info:
// http://smarty.php.net/manual/en/api.variables.php

// The name of the directory where templates are located.
$config['template_dir'] = dirname(FCPATH);

// The directory where compiled templates are located
//$config['compile_dir'] = BASEPATH.'cache/';
$config['compile_dir'] = FCPATH.APPPATH.'cache/templates_c/';

//This tells Smarty whether or not to cache the output of the templates to the $cache_dir.
$config['caching']        = 0;

// This forces Smarty to (re)compile templates on every invocation.
// When deploying, change this value to 0
$config['force_compile'] = 1;
$config['compile_check'] = TRUE;

// default escape setting
$config['default_modifiers'] = 'escape:"html":"UTF-8"';
$config['plugins_dir'] = array(
	FCPATH.APPPATH.'libraries/smarty/plugins/',
	FCPATH.APPPATH.'libraries/smarty_plugins/',
);
