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

{if $current_module|is_exist_favicon}<link rel="shortcut icon" href="{img_url}/favicon/{$current_module}.ico">{else}<link rel="shortcut icon" href="{img_url}/favicon/main.ico">{/if}
</head>
<body id="{get_current_page_id}">
