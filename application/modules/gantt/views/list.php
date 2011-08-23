<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>

<body id="{get_current_page_id}">
<div id="list_top"></div>

<?php if (!$list): ?>
<div style="padding:30px 10px 30px 5px;">{if $search}「{$search}」に一致する{elseif $now_category_id}このカテゴリの{else}指定した記事の{/if}登録はありません。</div>
<?php if ($search): ?>
<div style="margin-bottom:20px;">
「<a href="http://www.google.co.jp/search?q={$search}" target="_blank" style="font-weight:bold;">{$search}</a>」をGoogle検索
<span style="margin-left:20px;"><a href="http://www.google.co.jp/search?q={$search}&as_qdr=m6" target="_blank">6ヶ月以内</a></span>
</div>
<?php endif; ?>
<?php else: ?>

<!-- main_list -->
<div id="gantt">
<table id="gantt_chart" border="0" cellpadding="0" cellspacing="0">
<tr>
<td rowspan="3" class="title"><?php echo get_config_value('site_title', 'program') ?></td>
<td rowspan="3" class="title"><?php echo get_config_value('site_title', 'project') ?></td>
<td rowspan="3" class="title"><?php echo get_config_value('site_title', 'wbs') ?></td>
<td rowspan="3" class="title">作業分類</td>
<td rowspan="3" class="title" id="estimated_time">見積工数<br>(人日)</td>
<?php foreach ($day_list as $date => $item): ?>
<td class="<?php echo ($item['month']) ? 'month_top' : 'month' ?>"><?php echo $item['month'] ?></td>
<?php endforeach; ?>
</tr>
<tr>
<?php foreach ($day_list as $date => $item): ?>
<td id="day_<?php echo $date ?>" class="day<?php if ($item['is_today']): ?> today<?php elseif ($item['holiday'] || $item['week'] == 0 || $item['week'] == 6): ?> holiday_title<?php endif; ?>"><?php echo $item['day'] ?></td>
<?php endforeach; ?>
</tr>
<tr>
<?php foreach ($day_list as $date => $item): ?>
<td id="week_<?php echo $date ?>" class="week<?php if ($item['is_today']): ?> today<?php elseif ($item['holiday'] || $item['week'] == 0 || $item['week'] == 6): ?> holiday_title<?php endif; ?>"><?php echo get_week_name($item['week']) ?></td>
<?php endforeach; ?>
</tr>

<?php foreach ($list as $row): ?>
<tr>
<td class="row_title"><a href="<?php echo site_url('project/index').'/'.$row['program_key_name'] ?>"><?php echo mb_strimwidth($row['program_name'], 0, 18, '...', 'UTF-8') ?></a></td>
<td class="row_title"><a href="<?php echo site_url('wbs/index').'/'.$row['project_key_name'] ?>"><?php echo mb_strimwidth($row['project_name'], 0, 18, '...', 'UTF-8') ?></a></td>
<td class="row_title ta_l"><?php echo mb_strimwidth($row['name'], 0, 25, '...', 'UTF-8') ?></td>
<td class="row_title gantt_active_<?php echo $row['work_class_id'] ?>"><?php echo $row['work_class_name'] ?></td>
<td>
<span id="estimated_time<?php echo $row['id'] ?>" class="autogrow"><?php echo $row['estimated_time'] ?></span>
<input type="hidden" id="input_start_date_<?php echo $row['id'] ?>" class="input_each" name="start_date" value="<?php echo $row['start_date'] ?>">
</td>
<?php foreach ($day_list as $date => $item): ?>
<td id="wbs_<?php echo $row['id'].'_'.$date ?>" class="gantt_cel wbs_<?php echo $row['id'] ?><?php echo get_gantt_date_class($date, $row, true, $holidays, $item['week']) ?><?php if ($item['is_today']): ?> today<?php elseif ($item['holiday'] || $item['week'] == 0 || $item['week'] == 6): ?> holiday<?php endif; ?>">&nbsp;</td>
<?php endforeach; ?>
</tr>

<?php endforeach; ?>
<table>
</div>

<?php endif; ?>
</div>
</body>

<script type="text/javascript" src="<?php echo site_url() ?>js/jquery.autopager.js"></script>
<script type="text/javascript" src="<?php echo site_url() ?>js/jquery.lazyload.js"></script>
<script type="text/javascript" src="<?php echo site_url() ?>js/jquery.slidescroll.js"></script>
<script type="text/javascript" charset="utf-8">
$(function() {
	$.autopager({
		autoLoad: false
	});
	$('a[rel=next]').click(function() {
		$.autopager('load');
		return false;
	});
});

$(function(){
  $("a[href*='#']").slideScroll();
});
</script>

<!-- カレンダー対応 -->
<script type="text/javascript" charset="utf-8">
$(function() {
	//テキストボックスにカレンダーをバインドする（パラメータは必要に応じて）
	$(".input_date").datepicker({
		showButtonPanel: true,//「今日」「閉じる」ボタンを表示する
		firstDay: 1,//週の先頭を月曜日にする（デフォルトは日曜日）

		showOn: 'button',
		buttonImage: '<?php echo site_url() ?>/css/images/calendar.gif',
		buttonImageOnly: true,

		//年月をドロップダウンリストから選択できるようにする場合
		changeMonth: true,

		prevText: '&#x3c;前',
		nextText: '次&#x3e;',

		// 選択可能な日付の範囲を限定する場合（月は0～11）
		// minDate: new Date(2010, 6 - 1, 16),
		// maxDate: new Date(2010, 8 - 1, 15)
	});
});
</script>

</html>
