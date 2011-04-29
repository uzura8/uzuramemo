{include file='ci:admin/header.tpl'}
{include file='ci:admin/topmenu.tpl'}
<div id="mainbody">
<p class="ttl_01">{get_config_value key=admin_site_name index=admin}{$smarty.const.UM_TOPPAGE_NAME}</p>
<div class="box_info">
<p>ヘッダーメニューより作業コンテンツを選んでください。<br />
各コンテンツのトップページに作業説明が記載してあります。</p>
</div>
<div class="box_info">
<p class="ttl"><a href="{admin_url uri=webmemo}">{$smarty.const.UM_WEBMEMO_NAME}</a></p>
<p>{$smarty.const.UM_WEBMEMO_NAME}の管理が行えます。</p>
<br />
<p class="ttl"><a href="{admin_url uri=webmemo_category}">{$smarty.const.UM_WEBMEMO_NAME}カテゴリ</a></p>
<p>{$smarty.const.UM_WEBMEMO_NAME}カテゴリの管理が行えます。</p>
<br />
<p class="ttl"><a href="{admin_url uri=edit_user}">アカウント管理</a></p>
<p>メンバーの管理が行えます。</p>
<br />
<p class="ttl"><a href="{admin_url uri=edit_password}">パスワード変更</a></p>
<p>メンバーの管理が行えます。</p>
<br />
<p class="ttl"><a href="{admin_url uri=execute_logout}">ログアウト</a></p>
<p>管理ページからログアウトします。再度、作業を行う際はログインする必要があります。</p>
<br />
<p class="ttl"><a href="{site_url}" target="_blank">サイト{$smarty.const.UM_TOPPAGE_NAME}</a></p>
<p>この管理ページで管理しているWEBサイトのトップページにジャンプします。</p>
</div>
</div>
{include file='ci:admin/sidemenu.tpl'}
{include file='ci:admin/footer.tpl'}
