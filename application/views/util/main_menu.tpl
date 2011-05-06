<ul>
<li><a href="{site_url uri=list}">{$smarty.const.UM_WEBMEMO_NAME}</a></li>
{*{if $smarty.const.UM_USE_CONTACT}
<li><a href="{site_url uri=contact}">お問い合わせ</a></li>
{/if}*}
<li><a href="{site_url uri=sitemap}">サイトマップ</a></li>
{if $smarty.const.DEV_MODE}
<li><a href="{site_url uri=user_guide_ja}" target="_blank">開発者向けマニュアル</a></li>
{/if}
{if $smarty.const.IS_AUTH}
<li><a href="{admin_url}" target="_blank">管理画面</a></li>
{/if}
<li class="bone"><a href="{site_url}">{$smarty.const.UM_TOPPAGE_NAME}</a></li>
</ul>
