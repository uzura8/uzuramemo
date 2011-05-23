{include file='ci:mobile/header.tpl'}
{include file='ci:mobile/topmenu.tpl'}

<div id="mainbody">
{include file='ci:mobile/util/memo_topmenu.tpl'}

{if $breadcrumbs}<div id="list_top_box">{include file='ci:util/breadcrumb_list.tpl'}</div>{/if}

<h2 class="title">{$page_title}</h2>

<h3 class="title"><a href="{site_url}">{$smarty.const.UM_WEBMEMO_NAME}</a></h3>
{include file='ci:mobile/util/category_list_mobile.tpl'}

{*{if $smarty.const.UM_USE_CONTACT}
<h3 class="title"><a href="{site_url uri=contact}">お問い合わせ</a></h3>
{/if}*}
<h3 class="title"><a href="{site_url uri=sitemap}">サイトマップ</a></h3>
{if $smarty.const.DEV_MODE}
<h3 class="title"><a href="{site_url uri=user_guide_ja}" target="_blank">開発者向けマニュアル</a></h3>
{/if}
{if $smarty.const.IS_AUTH}
<h3 class="title"><a href="{admin_url}" target="_blank">管理画面</a></h3>
{/if}
<h3 class="title"><a href="{site_url}">{$smarty.const.UM_TOPPAGE_NAME}</a></h3>

</div><!-- mainbody -->

{include file='ci:mobile/footer.tpl'}
