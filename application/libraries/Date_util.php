<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Date_util
{

	function __construct()
	{
	}

	public function check_date_format($date)
	{
		if (!preg_match('/2[0-9]{3}\-[0-9]{2}\-[0-9]{2}/', $date)) return false;
		$dates = explode('-', $date);

		return checkdate(intval($dates[1]), intval($dates[2]), intval($dates[0]));
	}
}
