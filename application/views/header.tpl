<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript">
<title>{$smarty.const.SITE_TITLE}</title>
<meta NAME="ROBOTS" CONTENT="{if $smarty.const.DEV_MODE}noindex,nofollow{else}index,follow{/if}">
<meta name="description" content="{$site_dsc}">
<meta name="keywords" content="{$site_kwd}">
{if $smarty.const.GOOGLE_SITE_VERIFICATION_KEY}
<meta name="google-site-verification" content="{$smarty.const.GOOGLE_SITE_VERIFICATION_KEY}" />
{/if}
<link rel="shortcut icon" href="/favicon.ico">
<link title="RSS" href="{$smarty.const.BASE_URL}rss.xml" type="application/rss+xml" rel="alternate" />
<link href="{$smarty.const.BASE_URL}css/filter.css" rel="stylesheet" type="text/css" />

<link rel="openid.server" href="http://www.openid.ne.jp/index.php/serve">
<link rel="openid.delegate" href="http://uzura8.openid.ne.jp/">

{$head_info}
</head>

<body>
<div id="container"><!--コンテナー・STR-->
