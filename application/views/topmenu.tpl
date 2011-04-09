<div id="header">
<div id="banner_box">
<h1><a href="{$smarty.const.BASE_URL}" style="">{$smarty.const.SITE_TITLE}</a></h1>
<div id="siteDesc">{$smarty.const.SITE_DESC_MAIN}</div>
{if $smarty.const.IS_AUTH}
<div id="login_symbol"><img src="{$smarty.const.BASE_URL}img/uzura.gif" alt="Login!" /></div>
{else}
{if $smarty.const.UM_USE_GOOGLE_ADSENSE}{include file='ci:util/google_adsense_topmenu.tpl'}{/if}
{/if}
</div>
</div><!--header-->

<div id="top_menu">
{include file='ci:util/main_menu.tpl'}
</div><!--top_menu-->
