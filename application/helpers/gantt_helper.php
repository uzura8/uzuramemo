<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
/**
 * gantt helper
 * 
 * @author uzuranoie@gmail.com
 */


/** 
 * @param date
 * @param row
 * @param pre_space
 */
function get_gantt_date_class($date = '', $row = array(), $pre_space = false, $holidays = array(), $week_num = null)
{
	// 必須項目確認
	if (!$date || !$row) return '';

	if (!$row['start_date'] || !$row['finish_date'] || !$row['work_class_id']) return '';

	$prefix = '';
	if ($pre_space) $prefix = ' ';

	// due_date
	if ($date == $row['due_date']) return $prefix.'gantt_due_date';

	// hodiday
	if (!empty($week_num))
	{
		$w = $week_num;
	}
	else
	{
		$w = date('w', strtotime($date));
	}

	if ($w == 0 || $w == 6 || !empty($holidays[$date])) return '';

	$d  = (int)str_replace('-', '', $date);
	$sd = (int)str_replace('-', '', $row['start_date']);
	$fd = (int)str_replace('-', '', $row['finish_date']);
	if ($d >= $sd && $d <= $fd) return $prefix.'gantt_active_'.$row['work_class_id'];

	return '';
}

/* End of file gantt_helper.php */
/* Location: ./application/helpers/gantt_helper.php */
