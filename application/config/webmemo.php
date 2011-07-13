<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$site_title = '';
if (defined('SITE_TITLE')) $site_title = SITE_TITLE;
if (defined('SITE_TITLE_WEBMEMO')) $site_title = SITE_TITLE_WEBMEMO;
$config['site_title'] = $site_title;

$config['article_nums'] = array();
$config['article_nums']['default'] = 10;
$config['article_nums']['mobile']  = 20;
