{assign var="current_module" value=$smarty.const.CURRENT_MODULE}
{include file='ci:hybrid/header.tpl'}
{include file='ci:hybrid/topmenu.tpl'}

<div id="mainbody">
{*{include file='ci:hybrid/subtitle.tpl'}*}

<h4 id="main_form_title" class="box_01">
<span class="f_11 space_right"><a id="list_switch_1" href="javaScript:void(0);" onclick="ajax_get_child_list_all(1);">All</a></span>
<span class="f_11 space_right"><a id="list_switch_2" href="javaScript:void(0);" onclick="ajax_get_child_list_all(0);">Active</a></span>
<span class="f_11 space_right"><a id="list_switch_3" href="javaScript:void(0);" onclick="ajax_get_child_list_all(2);">Priority</a></span>
<input type="hidden" name="list_mode" id="list_mode"  value="{$mode}">
</h4>

{*{include file='ci:hybrid/main_form.tpl'}*}
<div id="list">


<div id="list_top"></div>

{if !$list}
<div style="padding:30px 10px 30px 5px;">{if $search}「{$search}」に一致する{elseif $now_category_id}このカテゴリの{else}指定した記事の{/if}登録はありません。</div>
{else}

<!-- main_list -->
<div id="jquery-ui-sortable">
<div class="content" id="wbs_list">
{foreach from=$list item=row name=list}
<div{if !$order} class="wbs_each jquery-ui-sortable-item{if !$smarty.foreach.list.first} st15{/if}"{/if} id="wbs_{$row.id}">
<a name="id_{$row.id}"></a>
<h2 class="box_01 subtitle" id="article_title_wbs_{$row.id}">
<div class="subtitle_main">
<a class="importance_star" id="importance_star_parent_{$row.id}" href="javaScript:void(0);" onclick="toggle_importance_star({$row.id}, '{site_url}wbs/ajax_execute_update_importance', 'importance_star_parent_')" style="{$row.importance|site_display_importance:'style'}">{$row.importance|site_display_importance:'symbol'}</a>
<span id="wbs_name{$row.id}" class="autogrow_parent">{$row.name}</span>{if $row.key_name}<span id="wbs_key_name{$row.id}" class="autogrow_parent sub_info2">{$row.key_name}</span>{/if}
{if $row.due_date && $row.due_date != '0000-00-00'}<span class="due_date" id="due_date_parent_{$row.id}" style="{$row.due_date|convert_due_date:'style'}">{$row.due_date|convert_due_date:'rest_days'}</span>{else}<span id="due_date_parent_pre_{$row.id}"></span>{/if}
<span class="btnTop list_util_btn wider space_left" id="title_btn_{$row.id}"><a href="javaScript:void(0);" onclick="$('#wbs_article_{$row.id}').slideToggle();">▼</a></span>
{if !$is_detail}
<span class="link_right_each line_height_15 space_left"><a href="{site_url}activity/wbs/{$row.id}/?mode={$mode}">&raquo;&nbsp;詳細</a></span>
{/if}
<span class="link_right_each line_height_15"><a rel="prettyPopin" class="pp_link_wbs_{$row.id}" href="{site_url}activity/create/{$row.id}/1">&raquo;&nbsp;Activity作成</a></span>
</div>
<div class="article_meta_top">
<div class="banner">
<span class="space_left_5"><a href="{site_url}project/index/{$row.program_key_name}">{$row.program_name}</a></span>
<span class="space_left_5"><a href="{site_url}wbs/index/{$row.project_key_name}">{$row.project_name}</a></span>

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

<article class="box_01" id="wbs_article_{$row.id}" style="display:none;background-color:{'background-color'|site_get_style:$row.del_flg};">
<div class="article_box">
<h4>description</h4>
<p class="autogrow_parent" id="wbs_body{$row.id}" style="width: 300px">{if $row.body}{$row.body|nl2br|auto_link}{else}&nbsp;&nbsp;{/if}</p>

<div class="form_box_each">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td>
	<span class="label">開始日</span><input name="input_start_date_{$row.id}" id="wbs_input_start_date_{$row.id}" class="width_10 input_date" type="text" value="{$row.start_date}">
	<span class="label sl2">期日</span><input name="input_due_date_{$row.id}" id="wbs_input_due_date_{$row.id}" class="width_10 input_date" type="text" value="{$row.due_date}">
	<span class="btnSpan"><input type="button" name="btn_date_{$row.id}" value="更新" id="wbs_btn_date_{$row.id}" class="wbs_btn_date"></span>

	<span class="title sl5">工数:</span>
	<span class="label">見積</span>
	<input name="estimated_time" id="input_estimated_time_{$row.id}" class="width_3 wbs_input_each" type="text" value="{$row.estimated_time}"><span class="after_label">人日</span>
	<span class="label sl2">実績</span>
	<input name="spent_time" id="input_spent_time_{$row.id}" class="width_3 wbs_input_each" type="text" value="{$row.spent_time}"><span class="after_label">人日</span>

	<span class="title sl5">進捗:</span>
	<input name="percent_complete" id="input_percent_complete_{$row.id}" class="width_3 wbs_input_each" type="text" value="{$row.percent_complete}"><span class="after_label">%</span>
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
<span class="btnSpan"><input type="button" name="btn_sort_top_{$row.id}" value="sort top" id="btn_sort_top_{$row.id}" class="btn_sort_top"></span>
<span class="btnSpan space_left_5"><input type="button" name="update_del_flg_{$row.id}" value="{$row.del_flg|site_get_symbols_for_display}" id="wbs_btn_delFlg_{$row.id}" class="wbs_btn_delFlg"></span>
<span class="btnSpan space_left_5"><input type="button" name="delete_{$row.id}" value="削除" id="wbs_btn_delete_{$row.id}" class="wbs_btn_delete"></span>
{if $order}
<span class="btnSpan space_left">
<label for="input_sort_{$row.id}">並び順:</label>
<input type="text" name="input_sort_{$row.id}" value="{$row.sort}" id="input_sort_{$row.id}" class="InputMini input_sort" maxlength="6" placeholder="並び順"></span>
{/if}
<span class="btnTop space_left_5"><a href="#top">▲</a></span>
<span class="btnTop space_left_5"><a href="{site_url uri=wbs}">{$smarty.const.UM_TOPPAGE_NAME}</a></span>
<span class="btnTop space_left_5"><a href="{site_url}">サイト{$smarty.const.UM_TOPPAGE_NAME}</a></span>
</div>
</aside>
</article>

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
{if !$is_detail}{literal}
uzura_sortable("{/literal}{site_url}{literal}wbs/ajax_execute_update_sort_move/wbs_");
{/literal}{/if}
{literal}
$("a[rel^='prettyPopin']").bind("click", function(){
	var id_value = $(this).attr("class");
	var wbs_id = id_value.replace(/pp_link_wbs_/g, "");
	var mode = $("#list_mode").val();

	$.cookie('wbs_id_modal_activity_wbs', wbs_id);
	$.cookie('mode_modal_activity_wbs', mode);
});


$(document).ready(function(){
	var mode = $("#list_mode").val();
	ajax_activity_list_all(mode);
	uzura_modal('{/literal}{site_url}{literal}img/loader.gif', '{/literal}{site_url uri=activity/ajax_activity_list}{literal}', mode);

	// wbs(親) の更新
	$(".autogrow_parent").click(function(){
		var id = $(this).attr("id");
		url_id = id.replace(/wbs_/g, "");
		var item_name = $(this).attr("id").replace(/[0-9]+/g, "");
		item_name = item_name.replace(/wbs_/g, "");
		var text_box_width = '250px';
		if (item_name == 'key_name') {
			var text_box_width = '50px';
		} else if (item_name == 'due_date') {
			var text_box_width = '100px';
		}
		var csrf_token = $.cookie('csrf_test_name');

		$("p#" + id).editable("{/literal}{site_url uri=wbs/execute_update}/{literal}" + item_name, {
			indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
			type      : "autogrow",
			submit    : 'OK',
			submitdata: { "csrf_test_name": csrf_token, "prefix": 'wbs_' },
			//submit    : '<input type="submit" value="OK" class="button">',
			cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=wbs/ajax_wbs_detail}{literal}/' + url_id + '/' + item_name,
			//tooltip   : "Click to edit...",
			onblur    : "ignore",
			cssclass : "editable",
			select : true,
			//autogrow : {
			//	 lineHeight : 16,
			//	 minHeight  : 32
			//}
		})
		$("span#" + id).editable("{/literal}{site_url uri=wbs/execute_update}/{literal}" + item_name, {
			indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
			type      : "text",
			width     : 'width: ' + text_box_width + ';',// js/lib/jeditable/jquery.jeditable.js : 455 を修正し style で指定できるように対応
			event     : "dblclick",
			style     : "inherit",
			//submit    : 'OK',
			//submit    : '<input type="submit" value="OK" class="button">',
			submitdata: { "csrf_test_name": csrf_token, "prefix": 'wbs_'},
			//cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=wbs/ajax_wbs_detail}{literal}/' + url_id + '/' + item_name,
			//tooltip   : "Click to edit...",
			onblur    : "ignore",
			cssclass : "editable",
			//select : true,
		})
	});

	// article の無効化
	$(".wbs_btn_delFlg").click(function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/wbs_btn_delFlg_/g, "");
		var csrf_token = $.cookie('csrf_test_name');
		$.ajax({
			url : "{/literal}{site_url}{literal}wbs/ajax_execute_update_del_flg",
			dataType : "text",
			data : {"id": id, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(status_after){
				if (status_after == "1") {
					var btn_val = "{/literal}{1|site_get_symbols_for_display}{literal}";
					var bgcolor = "{/literal}{'background-color'|site_get_style:'1'}{literal}";
					var bgcolor_title = bgcolor;
				} else {
					var btn_val = "{/literal}{0|site_get_symbols_for_display}{literal}";
					var bgcolor = "{/literal}{'background-color'|site_get_style:'0'}{literal}";
					var bgcolor_title = '#E7E7E7';
				}
				$("#article_title_wbs_" + id).css({"background-color" : bgcolor_title});
				$("#article_" + id).css({"background-color" : bgcolor});
				$("#wbs_btn_delFlg_" + id).val(btn_val);
			},
			error: function(){
				$.jGrowl('No.' + id + 'の{/literal}{$page_name}{literal}の状態を変更できませんでした。');
			}
		});
	});

	// article の削除
	$(".wbs_btn_delete").click(function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/wbs_btn_delete_/g, "");
		jConfirm('削除しますか?', '削除確認', function(r) {
			if (r == true) {
				var csrf_token = $.cookie('csrf_test_name');
				$.ajax({
					url : "{/literal}{site_url}{literal}wbs/ajax_execute_delete",
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
	$(".wbs_input_each").change(function(){
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
			url : "{/literal}{site_url}{literal}wbs/ajax_execute_update_common",
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

	// 日付の変更
	$(".wbs_btn_date").click(function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/wbs_btn_date_/g, "");
		var value_start_date = $("#wbs_input_start_date_" + id).val();
		var value_due_date = $("#wbs_input_due_date_" + id).val();
		var csrf_token = $.cookie('csrf_test_name');

		// 入力値確認
		var ret = util_check_date_format(value_start_date);
		if (value_start_date && ret == false) {
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
			url : "{/literal}{site_url}{literal}wbs/ajax_execute_update_date",
			dataType : "text",
			data : {"id": id, "due_date": value_due_date, "start_date": value_start_date, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(data){
				var selector = 'span#due_date_parent_' + id;
				if (value_due_date.length > 0) {
					if (data.length > 0) {
						var obj = $.parseJSON(data);
						if ($(selector).length) {
							$(selector).text(obj.rest_days);
							$(selector).css('color', obj.styles.color);
							$(selector).css('background-color', obj.styles.backgroundcolor);
						} else {
							style_value = 'color:' + obj.styles.color + ';background-color:' + obj.styles.backgroundcolor + ';';
							$('span#due_date_parent_pre_' + id).html('<span class="due_date" id="due_date_parent_' + id + '" style="' + style_value + '">' + obj.rest_days + '</span>');
						}
					}
				} else {
					if ($(selector).length) {
						$(selector).remove();
					}
				}
				$.jGrowl('No.' + id + 'の日付を変更しました。');
			},
			error: function(data){
				$.jGrowl('No.' + id + 'の日付を変更できませんでした。');
			}
		});
	});

	// sort_top
	$(".btn_sort_top").click(function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_sort_top_/g, "");
		var csrf_token = $.cookie('csrf_test_name');
		var mode = $("#list_mode").val();

		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_execute_update_sort_top",
			dataType : "text",
			data : {"id": id, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(wbs_id){
				var url = '{/literal}{site_url uri=activity/wbs}{literal}?mode=' + mode;
				location.href=url;
			},
			error: function(data){
				$.jGrowl('No.' + id + ' の移動に失敗しました。');
			}
		});
	});

	// activity の更新
	$(".autogrow").live("click", function(){
		var id = $(this).attr("id");
		var item_name = $(this).attr("id").replace(/[0-9]+/g, "");
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
			event     : "dblclick",
			style     : "inherit",
			//submit    : 'OK',
			//submit    : '<input type="submit" value="OK" class="button">',
			submitdata: { "csrf_test_name": csrf_token },
			//cancel    : 'cancel',
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
						$('#hidden_del_flg_' + id).val('1');
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
			data : {"id": id, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(wbs_id){
				$.jGrowl('No.' + id + ' をコピーしました。');
				ajax_activity_list(wbs_id, '{/literal}{site_url uri=activity/ajax_activity_list}{literal}/' + wbs_id + '?mode=' + mode);
			},
			error: function(data){
				$.jGrowl('No.' + id + ' のコピーに失敗しました。');
			}
		});
	});

	// update_scheduled_date_today
	$(".btn_update_scheduled_date_today").live("click", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_update_scheduled_date_today_/g, "");
		var csrf_token = $.cookie('csrf_test_name');

		$.ajax({
			url : "{/literal}{site_url}{literal}activity/ajax_update_scheduled_date_today",
			dataType : "text",
			data : {"id": id, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(data){
				$.jGrowl('No.' + id + ' の予定日を今日にしました。');

				var obj = $.parseJSON(data);
				var wbs_id = obj.wbs_id;
				var mode = $("#list_mode").val();
				ajax_activity_list(wbs_id, '{/literal}{site_url uri=activity/ajax_activity_list}{literal}/' + wbs_id + '?mode=' + mode);
			},
			error: function(data){
				$.jGrowl('No.' + id + ' の予定日の変更に失敗しました。');
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

				var selector = 'span#due_date_' + id;
				if (value_due_date.length > 0) {
					if (data.length > 0) {
						var obj = $.parseJSON(data);
						if ($(selector).length) {
							$(selector).text(obj.rest_days);
							$(selector).css('color', obj.styles.color);
							$(selector).css('background-color', obj.styles.backgroundcolor);
						} else {
							style_value = 'color:' + obj.styles.color + ';background-color:' + obj.styles.backgroundcolor + ';';
							$('span#due_date_pre_' + id).html('<span class="due_date" id="due_date_' + id + '" style="' + style_value + '">' + obj.rest_days + '</span>');
						}
					}
				} else {
					if ($(selector).length) {
						$(selector).remove();
					}
				}
				$.jGrowl('No.' + id + 'の日付を変更しました。');
			},
			error: function(data){
				$.jGrowl('No.' + id + 'の日付を変更できませんでした。');
			}
		});
	});
});

function ajax_get_child_list_all(mode) {
	if ($("#list_mode").val() == 2 && mode != 2) {
		$("#list_mode").val(mode);
		var url = '{/literal}{site_url uri=activity/wbs}{literal}?mode=' + mode;
		location.href=url;
	} else {
		$("#list_mode").val(mode);
		ajax_activity_list_all(mode);
	}
}

function ajax_activity_list_all(mode) {
{/literal}
{foreach from=$list item=row}{literal}
	ajax_activity_list({/literal}{$row.id}{literal}, '{/literal}{site_url uri=activity/ajax_activity_list}/{$row.id}?mode={literal}'+mode, mode);
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

//	console.log(scheduled_date_int, tomorrow_int, this_week_int);

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
