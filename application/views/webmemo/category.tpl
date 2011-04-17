{include file='ci:header.tpl'}
{include file='ci:topmenu.tpl'}
{if !$smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}
<div id="mainbody">
{if $breadcrumbs}{include file='ci:util/breadcrumb_list_box.tpl'}{/if}
<h2 class="main_h2">{$pagename}トップ</h2>
<div id="cate_list">
{if !$main_list}
<div style="padding:0 10px 0 5px;">{$pagename}カテゴリの登録がありません。</div>
{else}
{foreach from=$main_list item=parent_category}
<h3 class="main_h3"><a href="{site_url uri=list/category}/{$parent_category.mc_id}">{$parent_category.mc_name}</a></h3>
{foreach from=$parent_category.sc_ary item=category}
<h4><a href="{site_url uri=list/category}/{$category.mc_id}">{$category.mc_name}</a></h4>
<ul>
{foreach from=$category.each_ary item=article}
<li><a href="{site_url uri=article}/{$article.mn_id}">{$article.mn_title}</a></li>
{/foreach}
</ul>
{/foreach}
{/foreach}
{/if}
{if !$smarty.const.IS_AUTH && $smarty.const.UM_USE_GOOGLE_ADSENSE}{include file='ci:util/google_adsense_mainbody.tpl'}{/if}
</div>
</div><!-- mainbody -->

{if $smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}
{include file='ci:footer.tpl'}
