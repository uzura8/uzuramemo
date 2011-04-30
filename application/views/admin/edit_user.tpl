{include file='ci:admin/header.tpl'}
{include file='ci:admin/topmenu.tpl'}
{assign var="admin_user" value="アカウント"}
{assign var="edit_button" value="新規追加"}
{assign var="sub_title" value="管理メンバーの新規追加"}
<div id="mainbody">
<p class="ttl_01">{$admin_user}管理</p>

{form_open action=admin/execute_create_user}
<div class="box_006 f_red">
{validation_errors}
{if flashdata}{flashdata}{/if}
</div>
<a name="frm"></a>
<div class="box_001">
<p class="ttl_02">{$sub_title}</p>
<div class="box_002">
{$sub_title}を行います。<br />
アカウント名には、半角英数字で重複のない値を入力してください。<br />
下記のフォームに内容を入力後、【{$edit_button}】ボタンをクリックしてください。<br />
</div>
<table border="1" cellspacing="1" cellpadding="0" width="100%" class="frm_form">
<tr>
	<th width="200">アカウント名</td>
	<td><input type="text" name="username" value="{set_value name=username}" size="32" maxlength="32" />
	<span class="f_exp_s box_no_spc">※5〜12文字</span></td>
</tr>
<tr>
	<th width="200">パスワード</td>
	<td><input type="password" name="password" value="{set_value name=password}" size="32" maxlength="32" />
	<span class="f_exp_s box_no_spc">※5〜12文字</span></td>
</tr>
<tr>
	<th width="200">パスワード(確認)</td>
	<td><input type="password" name="password_confirm" size="32" maxlength="32" />
	<span class="f_exp_s box_no_spc">※5〜12文字</span></td>
</tr>
<tr>
	<td colspan="3">
	<div class="box_005">
	<input type="submit" name="edit" value="{$edit_button}" class="btn" />
	</div>
	</td>
</tr>
</table>
</div>
{form_close}

{form_open action=admin/execute_delete_user}
<a name="lst_top"></a>
<div class="box_001">
<p class="ttl_02">登録済み{$admin_user}一覧</p>
{if !$main_list}
<div class="box_004">『{$page_name}』の登録がありません。</div>
{else}
<table border="1" cellspacing="1" cellpadding="0" class="frm_list" width="100%">
<tr>
	<th>削除</th>
	<th>No</th>
	<th>メンバー名</th>
</tr>

{foreach from=$main_list item=row}
<tr{$tr_css}>
	<td width="30" align="center">
	{if $auth_session.username != $row.username}<input type="submit" name="delete[{$row.id}]" value="&nbsp;X&nbsp;" class="btn" onclick="return confirm('削除しますか?');" />{/if}
	</td>
	<td width="25" align="center">{$row.id}</td>
	<td class="td_w_break">{$row.username}</td>
</tr>
{/foreach}
</table>
{/if}
</div>
{form_close}
</div>
{include file='ci:admin/sidemenu.tpl'}
{include file='ci:admin/footer.tpl'}
