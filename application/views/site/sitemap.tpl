{include file='ci:header.tpl'}
{include file='ci:topmenu.tpl'}
{if !$smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}
<div id="mainbody">
{if $breadcrumbs}{include file='ci:util/breadcrumb_list_box.tpl'}{/if}
<h2 class="main_h2">サイトマップ</h2>
<div id="cate_list">
<h3 class="main_h3"><a href="{site_url}">{$smarty.const.UM_WEBMEMO_NAME}</a></h3>
{include file='ci:util/category_list.tpl'}

{*{if $smarty.const.UM_USE_CONTACT}
<h3 class="main_h3"><a href="{site_url uri=contact}">お問い合わせ</a></h3>
{/if}*}
<h3 class="main_h3"><a href="{site_url uri=sitemap}">サイトマップ</a></h3>
{if $smarty.const.DEV_MODE}
<h3 class="main_h3"><a href="{site_url uri=user_guide_ja}" target="user_guide" onClick="window.open(this.href,this.target).focus();return false;">開発者向けマニュアル</a></h3>
{/if}
{if $smarty.const.IS_AUTH}
<h3 class="main_h3"><a href="{admin_url}" target="admin" onClick="window.open(this.href,this.target).focus();return false;">管理画面</a></h3>
{/if}
<h3 class="main_h3"><a href="{site_url}">{$smarty.const.UM_TOPPAGE_NAME}</a></h3>

{if !$smarty.const.IS_AUTH && $smarty.const.UM_USE_GOOGLE_ADSENSE}{include file='ci:util/google_adsense_mainbody.tpl'}{/if}
</div>
</div><!-- mainbody -->

{if $smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}
{include file='ci:footer.tpl'}
