<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$site_title = '';
if (defined('SITE_TITLE')) $site_title = SITE_TITLE;
if (defined('SITE_TITLE_WEBMEMO')) $site_title = SITE_TITLE_WEBMEMO;
$config['site_title'] = $site_title;

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
