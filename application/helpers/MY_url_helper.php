<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function is_url($str)
{
	if (preg_match("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str)) return true;

	return false;
}

function is_site_url($str)
{
	if (preg_match(sprintf("#^%s\w+[^\s\)\<]+$#i", site_url()), $str)) return true;

	return false;
}
