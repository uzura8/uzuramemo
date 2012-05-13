{assign var="current_module" value=$smarty.const.CURRENT_MODULE}
{include file='ci:hybrid/header.tpl'}
{include file='ci:hybrid/topmenu.tpl'}

<div id="mainbody">
{*{include file='ci:hybrid/subtitle.tpl'}*}

<h4 id="main_form_title" class="box_01">
<span class="f_11 space_right"><a id="new_form_switch" href="javaScript:void(0);" onclick="ajax_activity_list_all(0);">All</a></span>
<span class="f_11 space_right"><a id="new_form_switch" href="javaScript:void(0);" onclick="ajax_activity_list_all(1);">Active</a></span>
<span class="f_11 space_right"><a id="new_form_switch" href="javaScript:void(0);" onclick="ajax_activity_list_all(2);">Priority</a></span>
</h4>

{*{include file='ci:hybrid/main_form.tpl'}*}
<div id="list">


<div id="list_top"></div>

{if !$list}
<div style="padding:30px 10px 30px 5px;">{if $search}「{$search}」に一致する{elseif $now_category_id}このカテゴリの{else}指定した記事の{/if}登録はありません。</div>
{else}

<!-- main_list -->
<div id="jquery-ui-sortable">
<div class="content">
{foreach from=$list item=row name=list}
<div{if !$order} class="jquery-ui-sortable-item{if !$smarty.foreach.list.first} st20{/if}"{/if} id="wbs_{$row.id}">
<a name="id_{$row.id}"></a>
<h2 class="box_01 subtitle" id="article_title_wbs_{$row.id}">
<div>
<span id="name{$row.id}" class="autogrow">{$row.name}</span>{if $row.key_name}<span id="key_name{$row.id}" class="autogrow sub_info2">{$row.key_name}</span>{/if}
<span class="btnTop list_util_btn wider space_left" id="title_btn_{$row.id}"><a href="javaScript:void(0);">▼</a></span>
<span class="link_right_each line_height_15"><a rel="prettyPopin" class="pp_link_wbs_{$row.id}" href="{site_url}activity/create/{$row.id}/1">&raquo;&nbsp;作成</a></span>
</div>
<div class="article_meta_top">
<div class="banner">
<span class="space_left_5"><a href="{site_url}project/index/{$row.program_key_name}">{$row.program_name}</a></span>
<span class="space_left_5"><a href="{site_url}wbs/index/{$row.project_key_name}">{$row.project_name}</a></span>
</div>
<div class="meta_info">
<span>No.{$row.id}</span>
<span class="space_left_5">update: {$row.updated_at|date_format:"%Y/%m/%d %H:%M"}</span>
</div>
<div style="clear: both"></div>
</div>
</h2>

<div id="loading_activity_list_{$row.id}"><img src="{site_url}img/loading.gif"></div>
<div id="pics_activity_list_{$row.id}"></div>
<div id="activity_list_{$row.id}"></div>

</div>
{/foreach}
</div>
</div>
{/if}

</div><!-- main contents -->
</div><!-- mainbody END -->

{include file='ci:hybrid/mainmenu.tpl'}
<div class="clearfloat"><hr></div>

{include file='ci:hybrid/footer.tpl'}
{include file='ci:hybrid/footer_script.tpl'}
{include file='ci:activity/footer_script.tpl'}

<script src="{site_url}js/uzura_activity.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
{literal}
uzura_sortable("{/literal}{site_url}{literal}wbs/ajax_execute_update_sort_move/wbs_");

$("a[rel^='prettyPopin']").bind("click", function(){
	var id_value = $(this).attr("class");
	var wbs_id = id_value.replace(/pp_link_wbs_/g, "");

	console.log(id_value, wbs_id);

	$.cookie('wbs_id_modal_activity_wbs', wbs_id);
});

$(document).ready(function(){
	ajax_activity_list_all({/literal}{$mode}{literal});
	uzura_modal('{/literal}{site_url}{literal}img/loader.gif', '{/literal}{site_url uri=activity/ajax_activity_list}{literal}');

	$(".autogrow").live("click", function(){
		var id = $(this).attr("id");
		var item_name = $(this).attr("id").replace(/[0-9]+/g, "");
		var text_box_width = '250px';
		if (item_name == 'key_name') {
			var text_box_width = '50px';
		} else if (item_name == 'due_date') {
			var text_box_width = '100px';
		}
		var csrf_token = $.cookie('csrf_test_name');

		$("p#" + id).editable("{/literal}{site_url uri=activity/execute_update}/{literal}" + item_name, {
			indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
			type      : "autogrow",
			submit    : 'OK',
			submitdata: { "csrf_test_name": csrf_token },
			//submit    : '<input type="submit" value="OK" class="button">',
			cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=activity/ajax_activity_detail}{literal}/' + id + '/' + item_name,
			tooltip   : "Click to edit...",
			onblur    : "ignore",
			cssclass : "editable",
			select : true,
			//autogrow : {
			//	 lineHeight : 16,
			//	 minHeight  : 32
			//}
		})
		$("span#" + id).editable("{/literal}{site_url uri=activity/execute_update}/{literal}" + item_name, {
			indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
			type      : "text",
			width     : 'width: ' + text_box_width + ';',// js/lib/jeditable/jquery.jeditable.js : 455 を修正し style で指定できるように対応
			submit    : 'OK',
			//submit    : '<input type="submit" value="OK" class="button">',
			submitdata: { "csrf_test_name": csrf_token },
			cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=activity/ajax_activity_detail}{literal}/' + id + '/' + item_name,
			tooltip   : "Click to edit...",
			onblur    : "ignore",
			cssclass : "editable",
			select : true,
		})
	});

	// article の無効化
	$(".btn_delFlg").live("click", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_delFlg_/g, "");
		var csrf_token = $.cookie('csrf_test_name');
		var closed_date = $('#input_closed_date_' + id).val();

		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_execute_update_del_flg",
			dataType : "text",
			data : {"id": id, "closed_date" : closed_date, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(status_after){

				if (status_after == "1") {
					if (closed_date.length == 0) {
						var today = get_today_for_sql_format();
						$('#input_closed_date_' + id).val(today);
						$('#hidden_del_flg_' + id).val(today);
					}
				} else {
					$('#input_closed_date_' + id).val('');
					$('#hidden_del_flg_' + id).val('');
				}
				var btn_val = "{/literal}{1|site_get_symbols_for_display}{literal}";
				$("#btn_delFlg_" + id).val(btn_val);
				reset_article_color(id);
			},
			error: function(){
				$.jGrowl('No.' + id + 'の{/literal}{$page_name}{literal}の状態を変更できませんでした。');
			}
		});
	});

	// article の削除
	$(".btn_delete").live("click", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_delete_/g, "");
		jConfirm('削除しますか?', '削除確認', function(r) {
			if (r == true) {
				var csrf_token = $.cookie('csrf_test_name');
				$.ajax({
					url : "{/literal}{site_url}{literal}activity/ajax_execute_delete",
					dataType : "text",
					data : {"id": id, "csrf_test_name": csrf_token},
					type : "POST",
					success: function(status_after){
						$("#article_title_" + id).css({"display" : "none"});
						$("#article_" + id).css({"display" : "none"});
						$.jGrowl('No.' + id + 'の{/literal}{$page_name}{literal}を削除しました。');
					},
					error: function(){
						$.jGrowl('No.' + id + 'の{/literal}{$page_name}{literal}を削除できませんでした。');
					}
				});
			}
		});
	});

	// 各値の変更
	$(".input_each").live("change", function(){
		var conf = {
			'estimated_time'   : {'name' : '見積工数', 'format' : 'numeric', 'error_message' : '数値を入力してください。'},
			'spent_time'       : {'name' : '実績工数', 'format' : 'numeric', 'error_message' : '数値を入力してください。'},
			'percent_complete' : {'name' : '進捗率', 'format' : 'integer', 'error_message' : '整数を入力してください。'},
		}

		var key = $(this).attr("name");
		var id_value = $(this).attr("id");
		var pattern = 'input_' + key + '_';
		var id = id_value.replace(eval("/" + pattern + "/g"), "");
		var value = $(this).val();
		var csrf_token = $.cookie('csrf_test_name');

		// 入力値確認
		funcname = "return util_check_" + conf[key]['format'] + "(arg)";
		f = new Function('arg', funcname);
		var ret = f(value);
		if (!ret) {
			$.jGrowl(conf[key]['error_message']);
			return;
		}

		// 更新
		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_execute_update_common",
			dataType : "text",
			data : {"id": id, "key": key, "value": value, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(data){
				//ajax_list(0);
				$('#select_order').val('0');
				$.jGrowl('No.' + id + 'の' + conf[key]['name'] + 'を変更しました。');
			},
			error: function(data){
				$.jGrowl('No.' + id + 'の' + conf[key]['name'] + 'を変更できませんでした。');
			}
		});
	});

	// 並び順の変更
	$(".input_sort").live("change", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/input_sort_/g, "");
		var value = $(this).val();
		var csrf_token = $.cookie('csrf_test_name');
		// 入力値が数値であるかどうかの確認
		if (value != parseInt(value)) {
			$.jGrowl('整数を入力してください。');
			return;
		}

		// 更新
		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_execute_update_sort",
			dataType : "text",
			data : {"id": id, "value": value, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(data){
				ajax_list(0);
				$('#select_order').val('0');
				$.jGrowl('No.' + id + 'の並び順を変更しました。');
			},
			error: function(data){
				$.jGrowl('No.' + id + 'の並び順を変更できませんでした。');
			}
		});
	});

	// 日付の変更
	$(".btn_date").live("click", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_date_/g, "");
		var value_scheduled_date = $("#input_scheduled_date_" + id).val();
		var value_due_date = $("#input_due_date_" + id).val();
		var csrf_token = $.cookie('csrf_test_name');

		// 入力値確認
		var ret = util_check_date_format(value_scheduled_date);
		if (value_scheduled_date && ret == false) {
			$.jGrowl('No.' + id + ': 開始日の日付形式が正しくありません');
			return;
		}
		var ret = util_check_date_format(value_due_date);
		if (value_due_date && ret == false) {
			$.jGrowl('No.' + id + ': 期日の日付形式が正しくありません');
			return;
		}

		// 更新
		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_execute_update_date",
			dataType : "text",
			data : {"id": id, "due_date": value_due_date, "scheduled_date": value_scheduled_date, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(data){
				reset_article_color(id);
				$.jGrowl('No.' + id + 'の日付を変更しました。');
			},
			error: function(data){
				$.jGrowl('No.' + id + 'の日付を変更できませんでした。');
			}
		});
	});
});

function ajax_activity_list_all(mode) {
{/literal}
{foreach from=$list item=row}{literal}
	ajax_activity_list({/literal}{$row.id}{literal}, '{/literal}{site_url uri=activity/ajax_activity_list}/{$row.id}?mode={literal}'+mode);
{/literal}
{/foreach}
{literal}
}

function reset_article_color(id) {
	var del_flg = $('#hidden_del_flg_' + id).val();
	var status = $('#hidden_status_' + id).val();
	var closed_date = $('#input_closed_date_' + id).val();
	var scheduled_date = $('#input_scheduled_date_' + id).val();

	var closed_date_int = closed_date.replace(/-/g, '');
	var scheduled_date_int = scheduled_date.replace(/-/g, '');

	var today_int = get_date_int_format();
	var tomorrow_int = get_date_int_format(1);
	var this_week_int = get_date_int_format(7);

	console.log(scheduled_date_int, tomorrow_int, this_week_int);
	//console.log(currentDate);

	if (del_flg == 1) {
		$('#article_title_'+id).css('background-color', {/literal}'{`$config_site_styles.backgroundcolor.display_none`}'{literal});
		$('#article_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.display_none}'{literal});
	} else if (closed_date.length > 0 && closed_date != '0000-00-00') {
		$('#article_title_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.display_none}'{literal});
		$('#article_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.display_none}'{literal});
	} else if (status > 0) {
		$('#article_title_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.active}'{literal});
		$('#article_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.active}'{literal});
	} else if (scheduled_date.length > 0 && scheduled_date != '0000-00-00' && scheduled_date_int < today_int) {
		$('#article_title_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.scheduled_passed}'{literal});
		$('#article_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.scheduled_passed}'{literal});
	} else if (scheduled_date_int == today_int) {
		$('#article_title_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.scheduled_today}'{literal});
		$('#article_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.scheduled_today}'{literal});
	} else if (scheduled_date_int == tomorrow_int) {
		$('#article_title_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.scheduled_tomorrow}'{literal});
		$('#article_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.scheduled_tomorrow}'{literal});
	} else if (scheduled_date.length > 0 && scheduled_date != '0000-00-00' && scheduled_date_int <= this_week_int) {
		$('#article_title_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.scheduled_this_week}'{literal});
		$('#article_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.scheduled_this_week}'{literal});
	} else {
		$('#article_title_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.display}'{literal});
		$('#article_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.display}'{literal});
		$type = 'display';
	}
}
{/literal}
</script>

<!-- カレンダー対応 -->
<script src="{site_url}js/lib/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
<script src="{site_url}js/lib/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
{*<script src="{site_url}js/lib/gcalendar-holidays.js" type="text/javascript"></script>*}
<script type="text/javascript" charset="utf-8">
{literal}
$(function() {
	uzura_datepicker("#due_date");
	uzura_datepicker("#scheduled_date");
});
{/literal}
</script>
</html>
