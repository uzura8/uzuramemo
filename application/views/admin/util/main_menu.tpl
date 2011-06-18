<li><a href="{admin_url}">{get_config_value key=admin_site_name index=admin}トップ</a></li>
<li><a href="{admin_url uri=webmemo}">{$smarty.const.UM_WEBMEMO_NAME}</a></li>
<li><a href="{admin_url uri=webmemo/category}" target="memo_category" onClick="window.open(this.href,this.target).focus();return false;">{$smarty.const.UM_WEBMEMO_NAME}カテゴリ</a></li>
{if !$smarty.const.UM_LOCAL_MODE}
<li><a href="{admin_url uri=edit_user}">アカウント管理</a></li>
<li><a href="{admin_url uri=edit_password}">パスワード変更</a></li>
<li><a href="{admin_url uri=execute_logout}">ログアウト</a></li>
{/if}
<li><a href="{site_url}" target="sitetop" onClick="window.open(this.href,this.target).focus();return false;">サイト{$smarty.const.UM_TOPPAGE_NAME}</a></li>
