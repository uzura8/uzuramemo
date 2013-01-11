<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$site_title = '';
if (defined('SITE_TITLE')) $site_title = SITE_TITLE;
if (defined('SITE_TITLE_WEBMEMO')) $site_title = SITE_TITLE_WEBMEMO;
$config['site_title'] = $site_title;

$config['admin_path'] = 'admin';
$config['admin_inseccure_actions'] = array('login', 'execute_login');

// 記号類
$config['symbols'] = array(
	'display' => '○',
	'display_none' => '×',
	'edit' => '⇔'
);

// style
$config['styles'] = array(
	'backgroundcolor' => array(
		'display' => '#fff',
		'display_none' => '#c0c0c0',
		'edit' => '#f7d9d9',
		'scheduled_today' => '#FFDDC9',
		'scheduled_passed' => '#FFCB5C',
		'scheduled_tomorrow' => '#CEFF85',
		'scheduled_this_week' => '#E3FFB9',
		'active' => '#FFBDDF',
	),
);

$config['due_date_styles'] = array(
	'half_year' => array(
		'color' => '#919191',
		'background-color' => '#C9F0FF',
		'range_to' => date('Ymd', strtotime('+6 month')),
	),
	'three_month' => array(
		'color' => '#424242',
		'background-color' => '#ACE7FF',
		'range_to' => date('Ymd', strtotime('+3 month')),
	),
	'this_month' => array(
		'color' => '#FFF',
		'background-color' => '#BAB8FF',
		'range_to' => date('Ymd', strtotime('+1 month')),
	),
	'this_week' => array(
		'color' => '#FFF',
		'background-color' => '#8480FF',
		'range_to' => date('Ymd', strtotime('next Friday')),
	),
	'tomorrow' => array(
		'color' => '#FFF',
		'background-color' => '#615CFA',
		'range_to' => date('Ymd', strtotime('tomorrow')),
	),
	'today' => array(
		'color' => '#FFF',
		'background-color' => '#FD91C5',
		'range_to' => date('Ymd', strtotime('today')),
	),
	'past' => array(
		'color' => '#FFF',
		'background-color' => '#FF57A8',
	),
);

$config['importance_styles'] = array(
	array('style' => 'color:#DDD;', 'symbol' => '☆'),
	array('style' => 'color:#FC0000;font-size:110%;', 'symbol' => '★'),
);
