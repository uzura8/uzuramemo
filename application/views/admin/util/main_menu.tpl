<li><a href="{admin_url}">{get_config_value key=admin_site_name index=admin}トップ</a></li>
<li><a href="{admin_url uri=webmemo}">{$smarty.const.UM_WEBMEMO_NAME}</a></li>
<li><a href="{admin_url uri=webmemo/category}" target="_blank">{$smarty.const.UM_WEBMEMO_NAME}カテゴリ</a></li>
<li><a href="{admin_url uri=edit_user}">アカウント管理</a></li>
<li><a href="{admin_url uri=edit_password}">パスワード変更</a></li>
<li><a href="{admin_url uri=execute_logout}">ログアウト</a></li>
<li><a href="{site_url}" target="_blank">サイト{$smarty.const.UM_TOPPAGE_NAME}</a></li>
