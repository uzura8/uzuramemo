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
<script type="text/javascript" charset="utf-8">
{literal}
$(function() {
	//テキストボックスにカレンダーをバインドする（パラメータは必要に応じて）
	uzura_datepicker("#due_date_activity");
	uzura_datepicker("#scheduled_date");
	uzura_datepicker("#closed_date");
});

$('#select_order').change(function() {
	var order = parseInt($(this).val());
	ajax_list(0, order);
});
{/literal}
</script>
