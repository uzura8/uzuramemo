<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>

<body id="{get_current_page_id}">
<div id="list_top"></div>

{if !$list}
<div style="padding: 10px 5px 0;">No Activities.</div>
{else}

<!-- main_list -->
<div class="content activity_list" id="jquery-ui-sortable_wbs_{$wbs_id}">
{foreach from=$list item=row}
<div{if !$order} class="jquery-ui-sortable-item_wbs_{$wbs_id}"{/if} id="{$row.id}">
<a name="id_{$row.id}"></a>
<h2 class="box_01 article_title" id="article_title_{$row.id}" style="background-color:{'background-color'|site_get_activity_style:$row.del_flg:$row.closed_date:$row.scheduled_date:$row.status};">
<div class="title_row">
<a class="importance_star" id="importance_star_{$row.id}" href="javaScript:void(0);" onclick="toggle_importance_star({$row.id}, '{site_url}activity/ajax_execute_update_importance', 'importance_star_')" style="{$row.importance|site_display_importance:'style'}">{$row.importance|site_display_importance:'symbol'}</a>
<span id="name{$row.id}" class="autogrow">{$row.name}</span>
{if $row.due_date && $row.due_date != '0000-00-00'}<span class="due_date" id="due_date_{$row.id}" style="{$row.due_date|convert_due_date:'style'}">{$row.due_date|convert_due_date:'rest_days'}</span>{else}<span id="due_date_pre_{$row.id}"></span>{/if}

	<div class="btn-group update_plus">
		<button class="btn_update_scheduled_date btn btn-mini" id="btn_update_scheduled_date_plus_day_1_{$row.id}">+1day</button>
		<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_day_2_{$row.id}" class="btn_update_scheduled_date">+2day</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_day_3_{$row.id}" class="btn_update_scheduled_date">+3day</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_day_4_{$row.id}" class="btn_update_scheduled_date">+4day</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_day_5_{$row.id}" class="btn_update_scheduled_date">+5day</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_week_1_{$row.id}" class="btn_update_scheduled_date">+1week</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_week_2_{$row.id}" class="btn_update_scheduled_date">+2week</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_week_3_{$row.id}" class="btn_update_scheduled_date">+3week</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_month_1_{$row.id}" class="btn_update_scheduled_date">+1month</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_month_2_{$row.id}" class="btn_update_scheduled_date">+2month</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_plus_month_3_{$row.id}" class="btn_update_scheduled_date">+3month</a></li>
		</ul>
	</div>

	<div class="btn-group update_minus">
		<button class="btn btn-mini btn_update_scheduled_date" id="btn_update_scheduled_date_minus_day_0_{$row.id}">today</button>
		<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_minus_day_1_{$row.id}" class="btn_update_scheduled_date">-1day</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_minus_day_2_{$row.id}" class="btn_update_scheduled_date">-2day</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_minus_day_3_{$row.id}" class="btn_update_scheduled_date">-3day</a></li>
			<li><a href="javaScript:void(0);" id="btn_update_scheduled_date_minus_day_4_{$row.id}" class="btn_update_scheduled_date">-4day</a></li>
		</ul>
	</div>

	<div class="btn-group copy_action">
		<button class="btn btn-mini btn_copy" id="btn_copy_{$row.id}">copy</button>
		<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a  href="javaScript:void(0);" id="btn_del_copy_{$row.id}" class="btn_del_copy">finish&amp;copy</a></li>
			<li><a  href="javaScript:void(0);" id="btn_del_copy_today_{$row.id}" class="btn_del_copy_today">finish&amp;copy&nbsp;today</a></li>
		</ul>
	</div>

	<input type="button" name="update_del_flg_{$row.id}" value="{$row.del_flg|site_get_symbols_for_display}" id="btn_delFlg_{$row.id}" class="btn btn-mini btn_delFlg wider">
	<button type="button" class="list_util_btn wider btn btn-mini" id="title_btn_{$row.id}" data-toggle="button" onclick="$('#article_{$row.id}').slideToggle();">▼</button>
</div>

<div class="article_meta_top">
<div class="banner">
<span class="sl3"><a href="{site_url}project/index/{$row.program_key_name}">{$row.program_name}</a></span>
<span class="sl3">&gt;&nbsp;<a href="{site_url}wbs/index/{$row.project_key_name}">{$row.project_name}</a></span>
<span class="sl3">&gt;&nbsp;<a href="{site_url}activity/wbs/{$row.wbs_id}">{$row.wbs_name}</a></span>
</div>
<div class="meta_info">
<span>No.{$row.id}</span>
{if $row.project_key_name}<span id="project_key_name{$row.id}" class="sub_info2"{if $row.color || $row.background_color} style="{if $row.color}color:{$row.color};{/if}{if $row.background_color}background-color:{$row.background_color};{/if}"{/if}>{$row.project_key_name}</span>{/if}
{*
<span class="space_left_5">update: {$row.updated_at|date_format:"%Y/%m/%d %H:%M"}</span>
*}
</div>
<div style="clear: both"></div>
</div>

</h2>

<article class="box_01" id="article_{$row.id}" style="display:none;background-color:{'background-color'|site_get_activity_style:$row.del_flg:$row.closed_date:$row.scheduled_date:$row.status};">
<div class="article_box">
<h4>description</h4>
<p class="autogrow" id="body{$row.id}" style="width: 300px">{if $row.body}{$row.body|nl2br|auto_link}{else}&nbsp;&nbsp;{/if}</p>

<div class="form_box_each">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td>
	<span class="label">予定日</span><input name="input_scheduled_date_{$row.id}" id="input_scheduled_date_{$row.id}" class="width_15 input_date" type="text" value="{$row.scheduled_date}">
	<span class="label sl2">期日</span><input name="input_due_date_{$row.id}" id="input_due_date_{$row.id}" class="width_15 input_date" type="text" value="{$row.due_date}">
	<input type="button" name="btn_date_{$row.id}" value="更新" id="btn_date_{$row.id}" class="btn_date btn btn-mini wider sl5">
	<span class="title sl5">工数:</span>
	<span class="label">見積</span><span id="estimated_time{$row.id}" class="autogrow sl2">{if $row.estimated_time}{$row.estimated_time}{else}-{/if}</span><span class="sl2">h</span>
	<span class="label sl5">実績</span><span id="spent_time{$row.id}" class="autogrow sl2">{if $row.spent_time}{$row.spent_time}{else}-{/if}</span><span class="sl2">h</span>
	<div style="display: none;">
		<input type="hidden" name="hidden_status_{$row.id}" id="hidden_status_{$row.id}" class="hidden_status" value="{$row.status}">
		<input type="hidden" name="hidden_del_flg_{$row.id}" id="hidden_del_flg_{$row.id}" class="hidden_del_flg" value="{$row.del_flg}">
	</div>
</td></tr>
</table>
</div>

</div>
<div class="clearfloat"><hr></div>

<aside class="article_footer">
{if $row.explanation}
<div class="quote_box">
<span class="title">引用元:</span><span class="space_left_5">{$row.explanation}</span>
</div>
{/if}
<div class="article_aside_button">
<span class="label sl2 space_left_5">終了日</span><input name="input_closed_date_{$row.id}" id="input_closed_date_{$row.id}" class="width_10 input_date" type="text" value="{$row.closed_date}">
<input type="button" name="delete_{$row.id}" value="削除" id="btn_delete_{$row.id}" class="btn_delete btn btn-mini wider sl5">
{if $order}
<span class="btnSpan space_left">
<label for="input_sort_{$row.id}">並び順:</label>
<input type="text" name="input_sort_{$row.id}" value="{$row.sort}" id="input_sort_{$row.id}" class="InputMini input_sort" maxlength="6" placeholder="並び順"></span>
{/if}
<span class="btnTop space_left_5"><a href="#top">▲</a></span>
<span class="btnTop space_left_5"><a href="{site_url uri=wbs}">{$smarty.const.UM_TOPPAGE_NAME}</a></span>
<span class="btnTop"><a href="{site_url}">サイト{$smarty.const.UM_TOPPAGE_NAME}</a></span>
</div>
</aside>
</article>

</div>

<script type="text/javascript" charset="utf-8">
{literal}
uzura_sortable("{/literal}{site_url}{literal}activity/ajax_execute_update_sort_move/{/literal}{$wbs_id}{literal}", '#jquery-ui-sortable_wbs_{/literal}{$wbs_id}{literal}', '.jquery-ui-sortable-item_wbs_{/literal}{$wbs_id}{literal}');
$(function(){
	uzura_datepicker(".input_date", "{/literal}{site_url}{literal}/css/images/calendar.gif");
});
{/literal}
</script>

{/foreach}
</div>

{/if}
</div>
</body>
<script type="text/javascript" charset="utf-8">
{literal}
{/literal}
</script>
</html>
