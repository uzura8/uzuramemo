{include file='ci:admin/header.tpl'}
{include file='ci:admin/topmenu.tpl'}
{assign var="title" value="パスワード変更"}
{assign var="edit_button" value="変更"}
<div id="mainbody">
{form_open action=admin/execute_edit_password}
<p class="ttl_01">{$title}</p>

<div class="box_006 f_red">
{validation_errors}
{if flashdata}{flashdata}{/if}
</div>
<a name="frm"></a>
<div class="box_001">
<div class="box_002">
{$title}を行います。<br />
下記のフォームに内容を入力後、【{$edit_button}】ボタンをクリックしてください。<br>
</div>
<table border="1" cellspacing="1" cellpadding="0" width="100%" class="frm_form">
<tr>
	<th width="200">現在のパスワード</td>
	<td><input type="password" name="password_old" value="{set_value name=password_old}" size="32" maxlength="32" />
	<span class="f_exp_s box_no_spc">※5〜12文字</span></td>
</tr>
<tr>
	<th width="200">新しいパスワード</td>
	<td><input type="password" name="password" value="{set_value name=password}" size="32" maxlength="32" />
	<span class="f_exp_s box_no_spc">※5〜12文字</span></td>
</tr>
<tr>
	<th width="200">新しいパスワード(確認)</td>
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
</div>
{include file='ci:admin/sidemenu.tpl'}
{include file='ci:admin/footer.tpl'}
