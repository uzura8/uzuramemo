<div id="header" class="clearfix">	
<p class="ttl_main">{get_config_value key=admin_site_name index=admin}（{$smarty.const.SITE_TITLE}）</p>
{if !$smarty.const.UM_LOCAL_MODE}
<ul>
<li class="ttl">メニュー</li>
{include file='ci:admin/util/main_menu.tpl'}
</ul>
{/if}
</div>
<br class="clearfloat" />
