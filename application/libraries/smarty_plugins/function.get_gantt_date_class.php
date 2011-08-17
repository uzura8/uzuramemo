<?php

/** 
 * @param date
 * @param row
 * @param pre_space
 */
function smarty_function_get_gantt_date_class($params, &$smarty)
{
	// 必須項目確認
	if (empty($params['date']) || empty($params['row'])) return '';
	$row = $params['row'];
	if (!$row['start_date'] || !$row['finish_date'] || !$row['work_class_id']) return '';
	$date = $params['date'];

	$prefix = '';
	if (!empty($params['pre_space']) && $params['pre_space']) $prefix = ' ';

	// due_date
	if ($date == $row['due_date']) return $prefix.'gantt_due_date';

	// hodiday
	$w = date('w', strtotime($date));
	$holidays = $params['holidays'];
	if ($w == 0 || $w == 6 || !empty($holidays[$date])) return '';

	$d  = (int)str_replace('-', '', $date);
	$sd = (int)str_replace('-', '', $row['start_date']);
	$fd = (int)str_replace('-', '', $row['finish_date']);
	if ($d >= $sd && $d <= $fd) return $prefix.'gantt_active_'.$row['work_class_id'];

	return '';
}
