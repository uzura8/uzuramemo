<ul>
<li><a href="{site_url uri=list}">{$smarty.const.UM_WEBMEMO_NAME}</a></li>
{*{if $smarty.const.UM_USE_CONTACT}
<li><a href="{site_url uri=contact}">お問い合わせ</a></li>
{/if}*}
<li><a href="{site_url uri=sitemap}">サイトマップ</a></li>
{if $smarty.const.DEV_MODE}
<li><a href="{site_url uri=user_guide_ja}" target="user_guide" onClick="window.open(this.href,this.target).focus();return false;">開発者向けマニュアル</a></li>
{/if}
{if $smarty.const.IS_AUTH}
<li><a href="{admin_url}" target="admin" target="user_guide" onClick="window.open(this.href,this.target).focus();return false;">管理画面</a></li>
{/if}
<li class="bone"><a href="{site_url}">{$smarty.const.UM_TOPPAGE_NAME}</a></li>
</ul>
