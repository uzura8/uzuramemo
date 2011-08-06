<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['admin_path'] = 'admin';
$config['admin_inseccure_actions'] = array('login', 'execute_login');

// 記号類
$config['symbols'] = array(
	'display' => '◯',
	'display_none' => '×',
	'edit' => '⇔'
);

// style
$config['styles'] = array(
	'background-color' => array(
		'display' => '#fff',
		'display_none' => '#c0c0c0',
		'edit' => '#f7d9d9'
	),
);
