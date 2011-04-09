<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>{$smarty.const.SITE_TITLE}</title>
<meta name="robots" content="{if $smarty.const.DEV_MODE}noindex,nofollow{else}index,follow{/if}" />
<meta name="description" content="{$site_description}" />
<meta name="keywords" content="{','|implode:$site_keywords}" />
{if $smarty.const.GOOGLE_SITE_VERIFICATION_KEY}
<meta name="google-site-verification" content="{$smarty.const.GOOGLE_SITE_VERIFICATION_KEY}" />
{/if}
<link rel="shortcut icon" href="/favicon.ico" />
{if $smarty.const.UM_USE_RSS_FEED}
<link title="RSS" href="{$smarty.const.BASE_URL}rss.xml" type="application/rss+xml" rel="alternate" />
{/if}
<link href="{$smarty.const.BASE_URL}css/filter.css" rel="stylesheet" type="text/css" />

<!-- search word heighlighter -->
<script type="text/javascript" src="{$smarty.const.BASE_URL}js/se_hilite.js"></script>
<style type="text/css">
{literal}
.hilite1, .hilite4, .hilite7 { background-color: #ffa; }
.hilite2, .hilite5, .hilite8 { background-color: #faf; }
.hilite3, .hilite6, .hilite9 { background-color: #aff; }
{/literal}
</style>

<!-- syntaxhighlighter -->
<link type="text/css" rel="stylesheet" href="{$smarty.const.BASE_URL}js/syntaxhighlighter/styles/shCore.css" />
<link type="text/css" rel="stylesheet" href="{$smarty.const.BASE_URL}js/syntaxhighlighter/styles/shThemeDefault.css" />

{$head_info}
</head>

<body>
<div id="container"><!--container START-->
