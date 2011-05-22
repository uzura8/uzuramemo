<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>{$smarty.const.SITE_TITLE}</title>
<link rel="stylesheet" href="{site_url}css/mobile/reset.css">
<link rel="stylesheet" href="{site_url}css/mobile/base.css">

<meta name="robots" content="{if $smarty.const.DEV_MODE}noindex,nofollow{else}index,follow{/if}" />
<meta name="description" content="{$site_description}" />
{if $site_keywords}<meta name="keywords" content="{','|implode:$site_keywords}" />{/if}
<link rel="shortcut icon" href="{site_url uri=img/favicon.ico}" />
{if $smarty.const.UM_USE_RSS_FEED}
<link title="RSS" href="{site_url}rss.xml" type="application/rss+xml" rel="alternate" />
{/if}

{$head_info}
</head>

<body id="{get_current_page_id}">
