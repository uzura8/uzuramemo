{assign var="current_module" value=$smarty.const.CURRENT_MODULE}
{include file='ci:hybrid/header.tpl'}
{include file='ci:hybrid/topmenu.tpl'}

<div id="mainbody">
{include file='ci:hybrid/subtitle.tpl'}
{include file='ci:program/main_form.tpl'}
<div id="list"></div><!-- main contents -->
</div><!-- mainbody END -->

{include file='ci:hybrid/mainmenu.tpl'}
<div class="clearfloat"><hr></div>

{include file='ci:hybrid/footer.tpl'}
{include file='ci:program/footer_script.tpl'}
</html>