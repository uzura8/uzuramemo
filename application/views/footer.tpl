<br class="clearfloat" />
<div id="foot_menu">
{include file='ci:util/main_menu.tpl'}
</div><!--foot_menu-->
<div id="footer">
Copyright (C) {$smarty.const.COPYRIGHT_SINCE} - {$smarty.now|date_format:"%Y"} {$smarty.const.COPYRIGHT_URL}
</div><!--footer-->
</div><!--container-->
{include file='ci:util/footer_script.tpl'}
{$foot_info|smarty:nodefaults}
{if $smarty.const.UM_USE_GOOGLE_ANALYTICS}{include file='ci:util/google_analytics.tpl'}{/if}
</body>
</html>
