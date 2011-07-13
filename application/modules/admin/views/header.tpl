<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex,nofollow">
<title>{get_config_value key=admin_site_name index=admin}（{$smarty.const.SITE_TITLE}）</title>
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link href="{site_url uri=css/admin/format.css}" rel="stylesheet" type="text/css" />
<link href="{site_url uri=css/util.css}" rel="stylesheet" type="text/css" />
<link href="{site_url uri=img/admin/favicon.ico}" rel="shortcut icon" type="image/ico" />
{if $set_js_libraries}
<script type="text/javascript" src="{site_url}js/common.js"></script>
<script type="text/javascript" src="{site_url}js/prototype.js"></script>
{/if}
{$head}
</head>
<body id="{get_current_page_id}">
<div id="container">
