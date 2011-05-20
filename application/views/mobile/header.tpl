<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>{$smarty.const.SITE_TITLE}</title>
<link rel="stylesheet" href="{site_url}css/mobile/reset.css">
<link rel="stylesheet" href="{site_url}css/mobile/base.css">
{*
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="robots" content="{if $smarty.const.DEV_MODE}noindex,nofollow{else}index,follow{/if}" />
<meta name="description" content="{$site_description}" />
{if $site_keywords}<meta name="keywords" content="{','|implode:$site_keywords}" />{/if}
{if $smarty.const.GOOGLE_SITE_VERIFICATION_KEY}
<meta name="google-site-verification" content="{$smarty.const.GOOGLE_SITE_VERIFICATION_KEY}" />
{/if}
<link rel="shortcut icon" href="{site_url uri=img/favicon.ico}" />
{if $smarty.const.UM_USE_RSS_FEED}
<link title="RSS" href="{site_url}rss.xml" type="application/rss+xml" rel="alternate" />
{/if}

<!-- search word heighlighter -->
<script type="text/javascript" src="{site_url}js/se_hilite.js"></script>
<style type="text/css">
{literal}
.hilite1, .hilite4, .hilite7 { background-color: #ffa; }
.hilite2, .hilite5, .hilite8 { background-color: #faf; }
.hilite3, .hilite6, .hilite9 { background-color: #aff; }
{/literal}
</style>
{include file='ci:util/syntaxhighlighter.tpl'}
*}

{$head_info}
</head>

<body>
