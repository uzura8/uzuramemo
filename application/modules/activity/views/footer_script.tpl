<script type="text/javascript">
{literal}
$('#main_form_activity_submit').button();
$('#main_form_activity').validate({
	rules : { {/literal}{convert2jquery_validate_rules form_items=$form}{literal} },
	messages : { {/literal}{convert2jquery_validate_messages form_items=$form}{literal} },
	errorClass: "validate_error",
	errorElement: "span",
{/literal}{if !$is_modal}{literal}
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
				$('#name').focus();
				$('select#project_id').focus();
				// $('#main_form_box').hide('fast');
				//ajax_list(0, 1);
				//$('select#select_order').val('1');
				$.jGrowl('{/literal}{$page_name}{literal}を作成しました。');
			},
			error: function(){
				$.jGrowl('{/literal}{$page_name}{literal}を作成できませんでした。');
			},
			complete: function(){
				$(form).loading(false);
			}
		});
		//return false;
	}
{/literal}{/if}{literal}
});
$("button").click(function() {
	if ($("#main_form_activity").valid() == false) {
		return false;
	}
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
	$("#due_date_activity").datepicker({
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
	$("#scheduled_date").datepicker({
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

	//テキストボックスにカレンダーをバインドする（パラメータは必要に応じて）
	$("#closed_date").datepicker({
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

$('#select_order').change(function() {
	var order = parseInt($(this).val());
	ajax_list(0, order);
});
{/literal}
</script>

<!-- module専用CSSの読み込み -->
<link rel="stylesheet" href="{site_url}css/activity/main.css">
