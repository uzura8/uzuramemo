<ul>
<li><a href="{$smarty.const.BASE_URL}list/">{$smarty.const.UM_WEBMEMO_NAME}</a></li>
{if $smarty.const.UM_USE_CONTACT}
<li><a href="{$smarty.const.BASE_URL}contact/">お問い合わせ</a></li>
{/if}
<li><a href="{$smarty.const.BASE_URL}sitemap.php">サイトマップ</a></li>
{if $smarty.const.USE_TASK_MANAGER && $smarty.const.IS_AUTH}
<li><a href="{$smarty.const.BASE_URL}admin/task.php" target="_blank">{$smarty.const.TASK_MANAGER_SITE_NAME}</a></li>
{/if}
{if $smarty.const.DEV_MODE}
<li><a href="{$smarty.const.BASE_URL}user_guide_ja/" target="_blank">ユーザガイド</a></li>
{/if}
{if $smarty.const.IS_AUTH}
<li><a href="{$smarty.const.BASE_URL}admin/manual.php" target="_blank">管理画面</a></li>
{/if}
<li class="bone"><a href="{$smarty.const.BASE_URL}">トップページ</a></li>
</ul>
