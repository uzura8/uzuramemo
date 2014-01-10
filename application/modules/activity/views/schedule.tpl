{assign var="current_module" value=$smarty.const.CURRENT_MODULE}
{include file='ci:hybrid/header.tpl'}
{include file='ci:hybrid/topmenu.tpl'}

<div id="mainbody">
{*{include file='ci:hybrid/subtitle.tpl'}*}

<h4 id="main_form_title" class="box_01 clearfix">
{*
<span class="f_11 space_right pull-left"><a id="new_form_switch" href="javaScript:void(0);" onclick="ajax_activity_list_date_all(1);">All</a></span>
<span class="f_11 space_right pull-left"><a id="new_form_switch" href="javaScript:void(0);" onclick="ajax_activity_list_date_all(0);">Active</a></span>
<span class="f_11 space_right pull-left"><a id="new_form_switch" href="javaScript:void(0);" onclick="ajax_activity_list_date_all(2);">Priority</a></span>
*}
<span class="f_11 space_right pull-left"><a href="{site_url}activity/schedule">Reset</a></span>
<span class="f_11 space_right pull-left"><a href="{site_url}activity/schedule?mode=0&period={$period}&from_date={$from_date}&to_date={$to_date}">Active</a></span>
<span class="f_11 space_right pull-left"><a href="{site_url}activity/schedule?mode=1&period={$period}&from_date={$from_date}&to_date={$to_date}">All</a></span>
<span class="f_11 space_right pull-left"><a href="{site_url}activity/schedule?mode={$mode}&period=14">2weeks</a></span>
<span class="f_11 space_right pull-left"><a href="{site_url}activity/schedule?mode={$mode}&period=30">1month</a></span>

<div class="btn-group space_right pull-left">
	<a class="btn btn-mini" href="{site_url}activity/schedule?mode={$mode}&period={$period}">today</a>
	<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
	<span class="caret"></span>
	</button>
	<ul class="dropdown-menu">
		<li><a href="{site_url}activity/schedule?mode={$mode}&period={$period}&from_date={$smarty.now-60*60*24*1|date_format:"%Y-%m-%d"}">-1day</a></li>
		<li><a href="{site_url}activity/schedule?mode={$mode}&period={$period}&from_date={$smarty.now-60*60*24*3|date_format:"%Y-%m-%d"}">-3day</a></li>
		<li><a href="{site_url}activity/schedule?mode={$mode}&period={$period}&from_date={$smarty.now-60*60*24*5|date_format:"%Y-%m-%d"}">-5day</a></li>
		<li><a href="{site_url}activity/schedule?mode={$mode}&period={$period}&from_date={$smarty.now-60*60*24*7|date_format:"%Y-%m-%d"}">-1week</a></li>
		<li><a href="{site_url}activity/schedule?mode={$mode}&period={$period}&from_date={$smarty.now-60*60*24*14|date_format:"%Y-%m-%d"}">-2week</a></li>
		<li><a href="{site_url}activity/schedule?mode={$mode}&period={$period}&from_date={$smarty.now-60*60*24*30|date_format:"%Y-%m-%d"}">-1month</a></li>
	</ul>
</div>
<input type="hidden" name="list_mode" id="list_mode"  value="{$mode}">
</h4>

{*{include file='ci:hybrid/main_form.tpl'}*}
<div id="list">

<div id="list_top"></div>

{if !$list}
<div style="padding:30px 10px 30px 5px;">日付の指定に誤りがあります。</div>
{else}

<!-- main_list -->
<div>
<div class="content" id="date_list">

{if !$is_set_from_date}
<!-- past -->
<div{if !$order} class="date_each"{/if} id="date_past">
<a name="id_past"></a>
<h2 class="box_01 subtitle" id="article_title_schedule_past">
<div>
<span id="date_namepast">超過分</span>
</div>
</h2>

<div id="loading_activity_list_past"><img src="{site_url}img/loading.gif"></div>
<div id="pics_activity_list_past"></div>
<div id="activity_list_past"></div>

</div>
<!-- past -->
{/if}

{foreach from=$list key=date item=row name=list}
<div{if !$order} class="date_each st15"{/if} id="date_{$date}">
<a name="id_{$date}"></a>
<h2 class="box_01 subtitle article_title_schedule" id="article_title_schedule_{$date}">
<div class="clearfix">
<div class="date pull-left">
	<span id="date_name{$date}"><a href="{site_url}activity/schedule?mode={$mode}&from_date={$date}&to_date={$date}">{$date}({$row.week_name})</a></span>
	<span class="due_date sub_info2" id="due_date_{$row.id}" style="{$date|convert_due_date:'style'}">{$date|convert_due_date:'rest_days'}</span>
</div>
<div class="times article_meta_top pull-right">
	<span class="title sl5">工数:</span>
	<span class="label">見積</span><span id="estimated_time_{$date}" class="sl2"> - </span><span class="sl2">h</span>
	<span class="label sl5">実績</span><span id="spent_time_{$date}" class="sl2"> - </span><span class="sl2">h</span>
{*
	<button class="btn btn-mini" type="button" onclick="ajax_get_total_times('{$date}')"><i class="icon-refresh"></i></button>
*}
</div>
</h2>

<div id="loading_activity_list_{$date}"><img src="{site_url}img/loading.gif"></div>
<div id="pics_activity_list_{$date}"></div>
<div id="activity_list_{$date}"></div>

</div>
{/foreach}

{if !$is_detail}
<!-- undefined -->
<div{if !$order} class="date_each"{/if} id="date_undefined">
<a name="id_undefined"></a>
<h2 class="box_01 subtitle" id="article_title_schedule_undefined">
<div>
<span id="date_nameundefined"><a href="javaScript:void(0);" onclick="ajax_activity_list_undefined();">予定日未設定</a></span>
</div>
</h2>

<div id="pics_activity_list_undefined"></div>
<div id="activity_list_undefined"></div>

</div>
<!-- undefined -->
{/if}

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
{if !$is_detail}{literal}
//uzura_sortable("{/literal}{site_url}{literal}wbs/ajax_execute_update_sort_move/wbs_");
{/literal}{/if}
{literal}
$(document).ready(function(){
	var mode = $("#list_mode").val();
	ajax_activity_list_date_all(mode);

	// activity の更新
	$(".autogrow").live("click", function(){
		var id = $(this).attr("id");
		var item_name = $(this).attr("id").replace(/[0-9]+/g, "");
		var parent_date = $(this).data('parent_date') ? $(this).data('parent_date') : '';

		var text_box_width = '250px';
		if (item_name == 'key_name') {
			var text_box_width = '50px';
		} else if (item_name == 'estimated_time' || item_name == 'spent_time') {
			var text_box_width = '30px';
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
			//tooltip   : "Click to edit...",
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
			event     : "dblclick",
			style     : "inherit",
			//submit    : 'OK',
			//submit    : '<input type="submit" value="OK" class="button">',
			submitdata: { "csrf_test_name": csrf_token },
			//cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=activity/ajax_activity_detail}{literal}/' + id + '/' + item_name,
			//tooltip   : "Click to edit...",
			onblur    : "ignore",
			cssclass : "editable",
			select : true,
			callback : function(result){
				if (item_name == 'spent_time') {
					var spent_time = parseInt(result);
					if (spent_time > 0) {
						var id_num = id.replace(/spent_time/g, "");
						var closed_date = $('#input_closed_date_' + id_num).val();

						if (closed_date.length == 0) {
							var today = get_today_for_sql_format();
							$('#input_closed_date_' + id_num).val(today);
							$('#hidden_del_flg_' + id_num).val('1');
						}
						var btn_val = "{/literal}{1|site_get_symbols_for_display}{literal}";
						$("#btn_delFlg_" + id_num).val(btn_val);
						reset_article_color(id_num);
					}
				}
				if (item_name == 'estimated_time' || item_name == 'spent_time') {
					if (parent_date.length) ajax_get_total_times(parent_date);
				}
			}
		})
	});

	// article の無効化
	$(".btn_delFlg").live("click", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_delFlg_/g, "");
		var csrf_token = $.cookie('csrf_test_name');
		var closed_date = $('#input_closed_date_' + id).val();
		var parent_date = $(this).data('parent_date');

		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_execute_update_del_flg",
			dataType : "text",
			data : {"id": id, "closed_date" : closed_date, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(result){
				var obj = $.parseJSON(result);
				var spent_time = parseFloat(obj.spent_time);
				var spent_time_before = parseFloat(obj.spent_time_before);
				var estimated_time = parseFloat(obj.estimated_time);
				var del_flg_after = obj.del_flg;
				if (del_flg_after == "1") {
					if (closed_date.length == 0) {
						var today = get_today_for_sql_format();
						$('#input_closed_date_' + id).val(today);
						$('#hidden_del_flg_' + id).val('1');
					}
					if (estimated_time > 0 && spent_time_before == 0) {
						$('#spent_time'+id).html(estimated_time);
						ajax_get_total_times(parent_date);
					}
					var btn_val = "{/literal}{1|site_get_symbols_for_display}{literal}";
				} else {
					$('#input_closed_date_' + id).val('');
					$('#hidden_del_flg_' + id).val('');
					var btn_val = "{/literal}{0|site_get_symbols_for_display}{literal}";
				}
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
			//'estimated_time'   : {'name' : '見積工数', 'format' : 'numeric', 'error_message' : '数値を入力してください。'},
			//'spent_time'       : {'name' : '実績工数', 'format' : 'numeric', 'error_message' : '数値を入力してください。'},
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

	// copy
	$(".btn_copy").live("click", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_copy_/g, "");
		var csrf_token = $.cookie('csrf_test_name');
		//var closed_date = $('#input_closed_date_' + id).val();
		var mode = $("#list_mode").val();

		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_copy_activity",
			dataType : "text",
			data : {"id": id, "is_schedule": "1", "csrf_test_name": csrf_token},
			type : "POST",
			success: function(data){
				$.jGrowl('No.' + id + ' のコピーを実行しました。');
				var obj = $.parseJSON(data);
				var mode = $('#list_mode').val();
				ajax_activity_list_date(obj.scheduled_date_before, mode);
			},
			error: function(data){
				$.jGrowl('No.' + id + ' のコピーに失敗しました。');
			}
		});
	});

	// del&copy
	$(".btn_del_copy").live("click", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_del_copy_/g, "");
		var csrf_token = $.cookie('csrf_test_name');
		var mode = $("#list_mode").val();

		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_copy_activity",
			dataType : 'text',
			data : {'id': id, 'is_schedule': '1', 'is_delete': '1', 'csrf_test_name': csrf_token},
			type : 'POST',
			success: function(data){
				$.jGrowl('No.' + id + ' の削除&コピーを実行しました。');
				var obj = $.parseJSON(data);
				var mode = $('#list_mode').val();
				ajax_activity_list_date(obj.scheduled_date_before, mode);
			},
			error: function(data){
				$.jGrowl('No.' + id + ' のコピーに失敗しました。');
			}
		});
	});

	// del&copy today
	$(".btn_del_copy_today").live("click", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_del_copy_today_/g, "");
		var csrf_token = $.cookie('csrf_test_name');
		var mode = $("#list_mode").val();

		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_copy_activity",
			dataType : 'text',
			data : {'id': id, 'is_schedule': '1', 'is_delete': '1', 'copy_date': 'today', 'csrf_test_name': csrf_token},
			type : 'POST',
			success: function(data){
				$.jGrowl('No.' + id + ' を削除し、今日にコピーしました。');
				var obj = $.parseJSON(data);
				var mode = $('#list_mode').val();
				ajax_activity_list_date(obj.scheduled_date_before, mode);
				ajax_activity_list_date(obj.scheduled_date_after, mode);
			},
			error: function(data){
				$.jGrowl('No.' + id + ' のコピーに失敗しました。');
			}
		});
	});

	// update_scheduled_date
	$(".btn_update_scheduled_date").live("click", function(){
		var id_value = $(this).attr("id");
		var value_str = id_value.replace(/btn_update_scheduled_date_/g, "");
		var parts = value_str.split('_');
		var type = parts[0];
		var unit = parts[1];
		var value = parts[2];
		var id = parts[3];

		var csrf_token = $.cookie('csrf_test_name');
		var mode = $("#list_mode").val();

		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_update_scheduled_date",
			dataType : "text",
			data : {"id": id, "type": type, "unit": unit, "value": value, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(data){
				if (data.length > 0) {
					$.jGrowl('No.' + id + ' の予定日を変更しました。');
					var obj = $.parseJSON(data);
					var mode = $("#list_mode").val();
					ajax_activity_list_date(obj.scheduled_date, mode);
					ajax_activity_list_date(obj.scheduled_date_before, mode);
				}
			},
			error: function(data){
				$.jGrowl('No.' + id + ' の予定日の変更に失敗しました。');
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
				//reset_article_color(id);
				$.jGrowl('No.' + id + 'の日付を変更しました。');

				var mode = $("#list_mode").val();
				if (value_scheduled_date.length > 0) {
				}
				if (data.length > 0) {
					var obj = $.parseJSON(data);
					if (obj.scheduled_date_before.length > 0) {
						ajax_activity_list_date(obj.scheduled_date_before, mode);
					}
				}

				var scheduled_date_int = value_scheduled_date.replace(/-/g, '');
				var today_int = get_date_int_format();
				if (value_scheduled_date.length == 0) {
					value_scheduled_date = 'undefined';
				} else if (scheduled_date_int < today_int) {
					value_scheduled_date = 'past';
				}
				ajax_activity_list_date(value_scheduled_date, mode);
			},
			error: function(data){
				$.jGrowl('No.' + id + 'の日付を変更できませんでした。');
			}
		});
	});
});

function ajax_activity_list_date_all(mode) {
	$("#list_mode").val(mode);

	ajax_activity_list_date('past', 0);

{/literal}
{foreach from=$list key=date item=row}{literal}
	ajax_activity_list_date({/literal}'{$date}'{literal}, mode);
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


	if (del_flg == 1) {
		$('#article_title_'+id).css('background-color', {/literal}'{$config_site_styles.backgroundcolor.display_none}'{literal});
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

function ajax_activity_list_undefined() {
	var mode = $("#list_mode").val();
	ajax_activity_list_date('undefined', mode);
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
