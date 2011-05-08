{include file='ci:admin/header.tpl'}
<div id="mainbody_login">
<p class="ttl_01">ログイン画面</p>
<div class="box_002">
<p class="box_006 f_bl">アカウント名、パスワードを入力して【ログイン】ボタンを押してください。</p>
<div class="box_006 f_red">
{validation_errors}
{if flashdata}{flashdata}{/if}
</div>
{form_open action=admin/execute_login}
<table border="0" cellspacing="1" cellpadding="0" class="tbl_00">
<tr>
<td style="width:90px;">アカウント名</td>
<td>
<input type="text" name="username" value="{set_value name=username}" id="username" style="width: 12em; height: 1.2em;" />
</td>
</tr>
<td style="width:90px;">パスワード</td>
<td>
<input type="password" name="password" id="password" style="width: 12em; height: 1.2em;" />
</td>
</tr>
<td colspan="2"><input type="submit" name="submit" value="ログイン" class="btn" /></td>
</tr>
</table>
{form_close}
</div>
</div>
{include file='ci:admin/footer.tpl'}
