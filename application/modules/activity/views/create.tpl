{assign var="current_module" value=$smarty.const.CURRENT_MODULE}
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>{$smarty.const.SITE_TITLE_MODULE} - {$smarty.const.SITE_TITLE}</title>
<meta name="robots" content="{if $smarty.const.IS_ACCEPT_ROBOTS}index,follow{else}noindex,nofollow{/if}">
{if $site_keywords}<meta name="keywords" content="{','|implode:$site_keywords}">{/if}
<meta name="description" content="{$site_description}">
{$head_info|smarty:nodefaults}
<link rel="stylesheet" href="{site_url}css/{$current_module}/filter.css">
</head>
<body id="{get_current_page_id}" class="base">
<div id="mainbody">
<div id="modal">
{include file='ci:hybrid/main_form.tpl'}
</div>
</div>
</body>
{if !$is_modal}{include file='ci:hybrid/footer_script.tpl'}{/if}
{include file='ci:activity/footer_script.tpl'}

<script type="text/javascript" charset="utf-8">
{literal}
$(document).ready(function() {
	$('#name').focus();
});

$('#select_scheduled_date').change(function() {
	var value = $(this).val();
	var parts = value.split('-');
	var num = parts[0];
	var unit = parts[1];
	var is_before = (parts[2].length > 0 && parts[2] == 'before') ? true : false;

	if (is_before) num = -1 * num;
	var date_obj = util_get_add_date(num, unit);
	var date_str = util_convert_date_format(date_obj);

	$('#scheduled_date').val(date_str);
});
{/literal}
</script>

</html>
