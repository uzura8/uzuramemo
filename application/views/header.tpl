<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>{$smarty.const.SITE_TITLE}</title>
<meta name="robots" content="{if $smarty.const.DEV_MODE || $smarty.const.UM_CLOSED_MODE}noindex,nofollow{else}index,follow{/if}" />
<meta name="description" content="{$site_description}" />
{if $site_keywords}<meta name="keywords" content="{','|implode:$site_keywords}" />{/if}
{if $smarty.const.GOOGLE_SITE_VERIFICATION_KEY}
<meta name="google-site-verification" content="{$smarty.const.GOOGLE_SITE_VERIFICATION_KEY}" />
{/if}
<link rel="shortcut icon" href="{site_url uri=img/favicon.ico}" />
{if $smarty.const.UM_USE_RSS_FEED}
<link title="RSS" href="{site_url uri=feed}" type="application/rss+xml" rel="alternate" />
{/if}
<link href="{site_url}css/filter.css" rel="stylesheet" type="text/css" />

{$head_info}

</head>

<body id="{get_current_page_id}">
<div id="container"><!--container START-->
