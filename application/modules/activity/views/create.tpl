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
{$head_info}
<link rel="stylesheet" href="{site_url}css/{$current_module}/filter.css">
</head>
<body id="{get_current_page_id}" class="base">
<div id="mainbody">
<div id="modal">
{include file='ci:hybrid/main_form.tpl'}
{*{include file='ci:activity/main_form.tpl'}*}
</div>
</div>
{if !$is_modal}{include file='ci:hybrid/footer_script.tpl'}{/if}
{include file='ci:activity/footer_script.tpl'}
</html>
