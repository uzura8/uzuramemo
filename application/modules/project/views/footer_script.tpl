<script type="text/javascript" charset="utf-8">
{literal}
// <![CDATA[
$(document).ready(function() {
	uzura_form_switch();

{/literal}{if $edit}
	$('#main_form_box').show('fast');
	$('#name').focus();
{assign var=edit value=0}
{/if}{literal}

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

		$("p#" + id).editable("{/literal}{site_url uri=project/execute_update}/{literal}" + item_name, {
			indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
			type      : "autogrow",
			submitdata: { "csrf_test_name": csrf_token },
			submit    : 'OK',
			//submit    : '<input type="submit" value="OK" class="button">',
			cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=project/ajax_project_detail}{literal}/' + id + '/' + item_name,
			tooltip   : "Click to edit...",
			onblur    : "ignore",
			cssclass : "editable",
			select : true,
			//autogrow : {
			//	 lineHeight : 16,
			//	 minHeight  : 32
			//}
		})
		$("span#" + id).editable("{/literal}{site_url uri=project/execute_update}/{literal}" + item_name, {
			indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
			type      : "text",
			width     : 'width: ' + text_box_width + ';',// js/lib/jeditable/jquery.jeditable.js : 455 を修正し style で指定できるように対応
			submitdata: { "csrf_test_name": csrf_token },
			submit    : 'OK',
			//submit    : '<input type="submit" value="OK" class="button">',
			cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=project/ajax_project_detail}{literal}/' + id + '/' + item_name,
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
		$.ajax({
			url : "{/literal}{site_url}{literal}project/ajax_execute_update_del_flg",
			dataType : "text",
			data : {"id": id, "csrf_test_name": csrf_token},
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
		var csrf_token = $.cookie('csrf_test_name');
		jConfirm('削除しますか?', '削除確認', function(r) {
			if (r == true) {
				$.ajax({
					url : "{/literal}{site_url}{literal}project/ajax_execute_delete",
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

		var csrf_token = $.cookie('csrf_test_name');
		// 更新
		$.ajax({
			url : "{/literal}{site_url}{literal}project/ajax_execute_update_common",
			dataType : "text",
			data : {"id": id, "key": 'sort', "value": value, "csrf_test_name": csrf_token},
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
	$(".btn_due_date").live("click", function(){
		var id_value = $(this).attr("id");
		var id = id_value.replace(/btn_due_date_/g, "");
		var value = $("#input_due_date_" + id).val();

		// 入力値確認
		var ret = util_check_date_format(value);
		if (ret == false) {
			$.jGrowl('No.' + id + 'の日付形式が正しくありません');
			return;
		}
		var csrf_token = $.cookie('csrf_test_name');
		// 更新
		$.ajax({
			url : "{/literal}{site_url}{literal}project/ajax_execute_update_common",
			dataType : "text",
			data : {"id": id, "key": 'due_date', "value": value, "csrf_test_name": csrf_token},
			type : "POST",
			success: function(data){
				$.jGrowl('No.' + id + 'の期日を変更しました。');
			},
			error: function(data){
				$.jGrowl('No.' + id + 'の期日を変更できませんでした。');
			}
		});
	});
});
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
{/literal}
{if $program_key}
				$("input#key_name").val('{$program_key}_');
				$('#name').focus();
{else}
				$('select#program_id').focus();
{/if}
{literal}
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

//// 重複確認: name
//$(function() {
//	$('#name_loading').hide();
//	$('#name').change(function() {
//		if ($(this).val().length == 0)
//		{
//			$('#name_result').html('');
//			return;
//		}
//		$('#name_loading').show();
//		$.post("{/literal}{site_url}{literal}project/ajax_check_project_name", {
//			name: $('#name').val()
//			}, function(response){
//				$('#name_result').fadeOut();
//				if (response == 'true') {
//					var message = '<span class="validate_success">登録可能</span>';
//				} else {
//					var message = '<span class="validate_error">重複しています</span>';
//				}
//				setTimeout("finishAjax('name_result', '"+escape(message)+"')", 400);
//			});
//			return false;
//	});
//});
// 重複確認: key_name
$(function() {
	$('#key_name_loading').hide();
	$('#key_name').change(function() {
		if ($(this).val().length == 0)
		{
			$('#key_name_result').html('');
			return;
		}
		$('#key_name_loading').show();
		var csrf_token = $.cookie('csrf_test_name');
		$.post("{/literal}{site_url}{literal}project/ajax_check_project_key_name", {
			"key_name": $('#key_name').val(),
			"csrf_test_name": csrf_token
			}, function(response){
				$('#key_name_result').fadeOut();
				if (response == 'true') {
					var message = '<span class="validate_success">登録可能</span>';
				} else {
					var message = '<span class="validate_error">' + response + '</span>';
				}
				setTimeout("finishAjax('key_name_result', '"+escape(message)+"')", 400);
			});
			return false;
	});
});
function finishAjax(id, response) {
	var loading_parts_id = id.replace(/_result/g, "_loading");
	$('#'+loading_parts_id).hide();
	$('#'+id).html(unescape(response));
	$('#'+id).fadeIn();
}

$(function(){
	$('#key_name').alphanumeric({allow:"_"});
});

$(function(){
	var order = $("#select_order").val();
	ajax_list({/literal}{$from}{literal}, order);
});

function ajax_list(offset, order){
	var id  = 'list';
	var url = '{/literal}{site_url uri=project/ajax_project_list}{literal}';

	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id).show();

	$.get(url, { nochache:(new Date()).getTime(), 'program_id':{/literal}'{$program_id}'{literal}, 'search':{/literal}'{$search}'{literal}, 'order':order, 'from':offset }, function(data){
		$("#loading").fadeOut(function() {
			$("#pics").show();
		});
		if (data.length>0){
			$("#" + id).html(data);
		}
	})
}

// key名を補う
$("select#program_id").change(function(){
	var program_id = $(this).val();
	var csrf_token = $.cookie('csrf_test_name');

	// program のkey_nameを取得し、補う
	$.ajax({
		url : "{/literal}{site_url}{literal}project/ajax_get_project_key_name",
		dataType : "text",
		data : {"id": program_id, "csrf_test_name": csrf_token},
		type : "POST",
		success: function(data) {
			if (data.length > 0) {
				$("input#key_name").val(data + '_');
				$("#key_name_result").html('');
			}
		}
	});
});
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

		showOn: 'button',
		buttonImage: '{/literal}{site_url}{literal}/css/images/calendar.gif',
		buttonImageOnly: true,

		//年月をドロップダウンリストから選択できるようにする場合
//		changeYear: true,
		changeMonth: true,

		prevText: '&#x3c;前',
		nextText: '次&#x3e;',

		// 選択可能な日付の範囲を限定する場合（月は0～11）
		// minDate: new Date(2010, 6 - 1, 16),
		// maxDate: new Date(2010, 8 - 1, 15)
	});
});

$('#select_order').change(function() {
	var order = parseInt($(this).val());
	ajax_list(0, order);
});
{/literal}
</script>

<!-- module専用CSSの読み込み -->
<link rel="stylesheet" href="{site_url}css/project/main.css">
