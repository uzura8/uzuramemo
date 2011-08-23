<?php

/** 
 * @param date
 * @param row
 * @param pre_space
 */
function smarty_function_get_gantt_date_class($params, &$smarty)
{
	$date = '';
	if (!empty($params['date'])) $date = $params['date'];

	$row = array();
	if (!empty($params['row'])) $row = $params['row'];

	$pre_space = false;
	if (!empty($params['pre_space'])) $pre_space = $params['pre_space'];

	$holidays = array();
	if (!empty($params['holidays'])) $holidays = $params['holidays'];

	$week_num = null;
	if (!empty($params['week_num'])) $week_num = $params['week_num'];

	return get_gantt_date_class($date, $row, $pre_space, $holidays, $week_num);
}
