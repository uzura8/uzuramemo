<?php

function smarty_modifier_get_gantt_date_class($date = '', $start_date = '', $estimated_time = 0, $work_class_id = 0, $due_date = '', $percent_complete = 0, $pre_space = true)
{
	$prefix = '';
	if ($pre_space) $prefix = ' ';

	// 必須項目確認
	if (!$date || !$start_date || !$work_class_id || !$estimated_time) return '';

	if ($date == $due_date) return $prefix.'gantt_due_date';

	$d  = (int)str_replace('-', '', $date);
	$sd = (int)str_replace('-', '', $start_date);
	$ed = (int)date('Ymd', strtotime(sprintf('+%d days %s', ceil($estimated_time - 1), $start_date)));
	if ($d >= $sd && $d <= $ed) return $prefix.'gantt_active_'.$work_class_id;

	return '';
}
