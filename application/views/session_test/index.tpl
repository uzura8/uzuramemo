{include file='ci:header.tpl'}

<h1>CodeIgniter へようこそ!</h1>

<p>今ご覧のこのページは、CodeIgniter によって動的に生成されました。</p>

<p>このページを編集したい場合は、次の場所にあります:</p>
<code>application/views/welcome/index.tpl</code>

<p>このページのコントローラは次の場所にあります:</p>
<code>application/controllers/welcome.php</code>

<p>CodeIgniterを使うのが初めてなら、<a href="/user_guide_ja/">ユーザガイド</a>を読むことから始めてください。</p>

<hr>
{form_open action=session_test/execute_test}
<input type="text" name="test" /><br />
raw:<input type="text" name="raw" value="{$raw}" /><br />
input:<input type="text" name="input" value="{$input}" /><br />
<input type="submit" name="submit" value="submit">
{form_close}
<hr>

{include file='ci:footer.tpl'}
