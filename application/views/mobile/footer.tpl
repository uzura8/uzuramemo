<footer>
Copyright : {$smarty.const.COPYRIGHT_SINCE} - {$smarty.now|date_format:"%Y"} {$smarty.const.COPYRIGHT_URL}
</footer>
</body>
{$foot_info}
{include file='ci:mobile/util/footer_script.tpl'}
{if $smarty.const.UM_USE_GOOGLE_ANALYTICS}{include file='ci:util/google_analytics.tpl'}{/if}
</html>
