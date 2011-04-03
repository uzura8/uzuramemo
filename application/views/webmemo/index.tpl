{include file='ci:header.tpl'}
{include file='ci:topmenu.tpl'}
{if !$smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}
<div id="mainbody">
<h1>CodeIgniter へようこそ!!</h1>

<p>今ご覧のこのページは、CodeIgniter によって動的に生成されました。</p>

<p>このページを編集したい場合は、次の場所にあります:</p>
<code>application/views/welcome/index.tpl</code>

<p>このページのコントローラは次の場所にあります:</p>
<code>application/controllers/welcome.php</code>

<p>CodeIgniterを使うのが初めてなら、<a href="user_guide_ja/">ユーザガイド</a>を読むことから始めてください。</p>

</div><!-- mainbody -->
{if $smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}
{include file='ci:footer.tpl'}
