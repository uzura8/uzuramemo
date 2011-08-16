<script type="text/javascript" charset="utf-8">
{literal}
// <![CDATA[
$(document).ready(function() {
	$('#new_form_switch').click(function() {
		$('#main_form_box').slideToggle();
	});
	//$('#main_form_box').hide('fast');

	$(".autogrow").live("click", function(){
		var id = $(this).attr("id");
		var item_name = $(this).attr("id").replace(/[0-9]+/g, "");
		var text_box_width = '250px';
		if (item_name == 'estimated_time') {
			var text_box_width = '30px';
		}

		$("span#" + id).editable("{/literal}{site_url uri=wbs/execute_update}/{literal}" + item_name, {
			indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
			type      : "text",
			width     : 'width: ' + text_box_width + ';',// js/lib/jeditable/jquery.jeditable.js : 455 を修正し style で指定できるように対応
			submit    : 'OK',
			//submit    : '<input type="submit" value="OK" class="button">',
			cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=wbs/ajax_wbs_detail}{literal}/' + id + '/' + item_name,
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
		$.ajax({
			url : "{/literal}{site_url}{literal}wbs/ajax_execute_update_del_flg",
			dataType : "text",
			data : {"id": id,},
			type : "POST",
			success: function(status_after){
				if (status_after == "1") {
					var btn_val = "{/literal}{1|site_get_symbols_for_display}{literal}";
					var bgcolor = "{/literal}{'background-color'|site_get_style:'1'}{literal}";
				} else {
					var btn_val = "{/literal}{0|site_get_symbols_for_display}{literal}";
					var bgcolor = "{/literal}{'background-color'|site_get_style:'0'}{literal}";
				}
				$("#article_title_" + id).css({"background" : bgcolor});
				$("#article_" + id).css({"background" : bgcolor});
				$("#btn_delFlg_" + id).val(btn_val);
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
				$.ajax({
					url : "{/literal}{site_url}{literal}wbs/ajax_execute_delete",
					dataType : "text",
					data : {"id": id,},
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

	$("td.gantt_cel").live("dblclick", function(){
		var id_value = $(this).attr("id");
		var matches =id_value.match(/^wbs_([0-9]+)_(2[0-9]{3}\-[0-9]{2}\-[0-9]{2})$/);
		if (!matches) $.jGrowl('エラー');

		var id = matches[1];
		var value = matches[2];
		var key = 'start_date';
		jConfirm('No.' + id + ' の開始日を ' + value + ' に変更しますか?', '変更確認', function(r) {
			if (r == true) {
				// 更新
				$.ajax({
					url : "{/literal}{site_url}{literal}wbs/ajax_execute_update_common",
					dataType : "text",
					data : {"id": id, "key": key, "value": value},
					type : "POST",
					success: function(data){
						ajax_get_list();
						$.jGrowl('No.' + id + ' の' + '開始日' + 'を ' + value + ' に変更しました。');
					},
					error: function(data){
						$.jGrowl('No.' + id + ' の' + '開始日' + 'を変更できませんでした。');
					}
				});
			}
		});
	});

//	$("td.gantt_cel").live("dblclick", function(){
//		var id_value = $(this).attr("id");
//		var matches =id_value.match(/^wbs_([0-9]+)_(2[0-9]{3}\-[0-9]{2}\-[0-9]{2})$/);
//		if (!matches) {
//			$.jGrowl('エラー');
//			exit;
//		}
//
//		var id = matches[1];
//		var value = matches[2];
//		var key = 'due_date';
//		jConfirm('No.' + id + ' の期日を ' + value + ' に変更しますか?', '変更確認', function(r) {
//			if (r == true) {
//				// 更新
//				$.ajax({
//					url : "{/literal}{site_url}{literal}wbs/ajax_execute_update_common",
//					dataType : "text",
//					data : {"id": id, "key": key, "value": value},
//					type : "POST",
//					success: function(data){
//						ajax_get_list();
//						$.jGrowl('No.' + id + ' の' + '期日' + 'を ' + value + ' に変更しました。');
//					},
//					error: function(data){
//						$.jGrowl('No.' + id + ' の' + '期日' + 'を変更できませんでした。');
//					}
//				});
//			}
//		});
//	});

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

		// 入力値確認
		funcname = "return util_check_" + conf[key]['format'] + "(arg)";
		f = new Function('arg', funcname);
		var ret = f(value);
		console.log(ret);
		if (!ret) {
			$.jGrowl(conf[key]['error_message']);
			return;
		}

		// 更新
		$.ajax({
			url : "{/literal}{site_url}{literal}wbs/ajax_execute_update_common",
			dataType : "text",
			data : {"id": id, "key": key, "value": value},
			type : "POST",
			success: function(data){
				ajax_get_list();
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
		// 入力値が数値であるかどうかの確認
		if (value != parseInt(value)) {
			$.jGrowl('整数を入力してください。');
			return;
		}

		// 更新
		$.ajax({
			url : "{/literal}{site_url}{literal}wbs/ajax_execute_update_sort",
			dataType : "text",
			data : {"id": id, "value": value},
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
		var value_start_date = $("#input_start_date_" + id).val();
		var value_due_date = $("#input_due_date_" + id).val();

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
			data : {"id": id, "due_date": value_due_date, "start_date": value_start_date},
			type : "POST",
			success: function(data){
				$.jGrowl('No.' + id + 'の日付を変更しました。');
			},
			error: function(data){
				$.jGrowl('No.' + id + 'の日付を変更できませんでした。');
			}
		});
	});
});
function set_gantt_row(key, values){
	var sdate = values.start_date;
	var max = Math.ceil(values.estimated_time);
	for (var i = 0; i < max; i++)
	{
		var date = add_date(sdate, i);
		var id = key + '_' + date;
		$('td#' + id).addClass('gant_active');
	}
}
// ]]>
{/literal}
</script>

<script type="text/javascript">
{literal}
$('#main_form_submit').button();
$('#main_form').validate({
	rules : { {/literal}{convert2jquery_validate_rules form_items=$form}{literal} },
	messages : { {/literal}{convert2jquery_validate_messages form_items=$form}{literal} },
	errorClass: "validate_error",
	errorElement: "span",
//	errorLabelContainer: "#main_form_errorList",
//	wrapper: "li",
	submitHandler: function(form){
		$(form).loading({
			img: '{/literal}{site_url}{literal}img/loading.gif',
			align: 'center'
		});
		$(form).ajaxSubmit({
			success: function(data){
				// メッセージ入力欄をクリア
{/literal}
{foreach from=$form key=key item=items}
{if $items.type == 'text'}$( 'input#{$key}' ).val( '' );
{elseif $items.type == 'textarea'}$( '{$items.type}#{$key}' ).val( '' );
{elseif $items.type == 'select'}$( '{$items.type}#{$key}' ).val(0);
{/if}
{/foreach}
{literal}
				$('span').removeClass('validate_success');
				$('span').removeClass('validate_error');
				$('#name_result').fadeOut();
				$('#key_name_result').fadeOut();
				//$('#name').focus();
				$('select#project_id').focus();
				// $('#main_form_box').hide('fast');
				ajax_list(0, 1);
				$('select#select_order').val('1');
				$.jGrowl('{/literal}{$page_name}{literal}を作成しました。');
			},
			error: function(){
				$.jGrowl('{/literal}{$page_name}{literal}を作成できませんでした。');
			},
			complete: function(){
				$(form).loading(false);
			}
		});
	}
});
{/literal}
</script>

<script type="text/javascript" src="{site_url}js/lib/exdate.js"></script>
<script type="text/javascript">
{literal}
$(function(){
	$('#range').change(function() {
		ajax_get_list();
	});
	$('#date_from').change(function() {
		ajax_get_list();
	});
	$('#gntt_now').click(function() {
		var date = $.exDate();
		var date_from = date.toChar('yyyy-mm-dd');
		$("#date_from").val(date_from);
		ajax_get_list();
	});
	$('#gntt_next').click(function() {       get_added_date($("#date_from").val(), 7); });
	$('#gntt_next_lerge').click(function() { get_added_date($("#date_from").val(), 28); });
	$('#gntt_prev').click(function() {       get_added_date($("#date_from").val(), -7); });
	$('#gntt_prev_lerge').click(function() { get_added_date($("#date_from").val(), -28); });
});

function get_added_date(date_from, add_days)
{
	var date_from = add_date(date_from, add_days);
	$("#date_from").val(date_from);

	var range = $("#range").val();
	var order = $("#select_order").val();
	ajax_list({/literal}{$from}{literal}, order, date_from, range);
}

$(function(){
	var order = $("#select_order").val();
	var date_from = '';
	var range = 0;
	ajax_list({/literal}{$from}{literal}, order, date_from, range);
});

function ajax_list(offset, order, date_from, range){
	var id  = 'list';
	var url = '{/literal}{site_url uri=gantt/ajax_gantt_list}{literal}';

	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id).show();

	$.get(url, { nochache:(new Date()).getTime(), 'project_id':{/literal}'{$project_id}'{literal}, 'search':{/literal}'{$search}'{literal}, 'order':order, 'from':offset, 'date_from':date_from, 'range':range }, function(data){
		if (data.length>0){
			$("#" + id).html(data);
		}
	})
}

function ajax_get_list() {
	var range     = $("#range").val();
	var date_from = $("#date_from").val();
	var order     = $("#select_order").val();
	ajax_list({/literal}{$from}{literal}, order, date_from, range);
}
{/literal}
</script>

<!-- カレンダー対応 -->
<script src="{site_url}js/lib/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
<script src="{site_url}js/lib/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
{*<script src="{site_url}js/lib/gcalendar-holidays.js" type="text/javascript"></script>*}
<link rel="stylesheet" href="{site_url}css/jquery-ui-1.8.14.custom.css">
<link rel="stylesheet" href="{site_url}css/jquery-ui-calendar.custom.css">
<link rel="stylesheet" href="{site_url}css/ui.theme.css">
<script type="text/javascript" charset="utf-8">
{literal}
$(function() {
	//テキストボックスにカレンダーをバインドする（パラメータは必要に応じて）
	$("#due_date").datepicker({
		showButtonPanel: true,//「今日」「閉じる」ボタンを表示する
		firstDay: 1,//週の先頭を月曜日にする（デフォルトは日曜日）

		//年月をドロップダウンリストから選択できるようにする場合
		//changeYear: true,
		changeMonth: true,

		prevText: '&#x3c;前',
		nextText: '次&#x3e;',

		// 選択可能な日付の範囲を限定する場合（月は0～11）
		// minDate: new Date(2010, 6 - 1, 16),
		// maxDate: new Date(2010, 8 - 1, 15)
	});

	//テキストボックスにカレンダーをバインドする（パラメータは必要に応じて）
	$("#start_date").datepicker({
		showButtonPanel: true,//「今日」「閉じる」ボタンを表示する
		firstDay: 1,//週の先頭を月曜日にする（デフォルトは日曜日）

		//年月をドロップダウンリストから選択できるようにする場合
		changeYear: true,
		changeMonth: true,

		prevText: '&#x3c;前',
		nextText: '次&#x3e;',

		// 選択可能な日付の範囲を限定する場合（月は0～11）
		// minDate: new Date(2010, 6 - 1, 16),
		// maxDate: new Date(2010, 8 - 1, 15)
	});
});
{/literal}
</script>

<!-- module専用CSSの読み込み -->
<link rel="stylesheet" href="{site_url}css/{$current_module}/main.css">
