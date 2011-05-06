{include file='ci:header.tpl'}
{include file='ci:topmenu.tpl'}
{if !$smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}
<div id="mainbody">
{if $breadcrumbs}{include file='ci:util/breadcrumb_list_box.tpl'}{/if}
<h2 class="main_h2">サイトマップ</h2>
<div id="cate_list">
<h3 class="main_h3"><a href="{site_url}">{$smarty.const.UM_WEBMEMO_NAME}</a></h3>
{if $cate_list}
{foreach from=$cate_list item=parent_category}
{if (!$parent_category.is_private || ($smarty.const.IS_AUTH && $parent_category.is_private)) && $parent_category.sc_ary}
<h4 class="main_h4"><a href="{site_url uri=list/category}/{$parent_category.id}">{$parent_category.name}</a></h4>
<ul>
{foreach from=$parent_category.sc_ary item=category}
<li><a href="{site_url uri=list/category}/{$category.id}">{$category.name}</a></li>
{/foreach}
</ul>
{/if}
{/foreach}
{/if}
{*{if $smarty.const.UM_USE_CONTACT}
<h3 class="main_h3"><a href="{site_url uri=contact}">お問い合わせ</a></h3>
{/if}*}
<h3 class="main_h3"><a href="{site_url uri=sitemap}">サイトマップ</a></h3>
{if $smarty.const.DEV_MODE}
<h3 class="main_h3"><a href="{site_url uri=user_guide_ja}" target="_blank">開発者向けマニュアル</a></h3>
{/if}
{if $smarty.const.IS_AUTH}
<h3 class="main_h3"><a href="{admin_url}" target="_blank">管理画面</a></h3>
{/if}
<h3 class="main_h3"><a href="{site_url}">{$smarty.const.UM_TOPPAGE_NAME}</a></h3>

{if !$smarty.const.IS_AUTH && $smarty.const.UM_USE_GOOGLE_ADSENSE}{include file='ci:util/google_adsense_mainbody.tpl'}{/if}
</div>
</div><!-- mainbody -->

{if $smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}
{include file='ci:footer.tpl'}
