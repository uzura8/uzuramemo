{assign var="current_module" value=$smarty.const.CURRENT_MODULE}
{include file='ci:gantt/header.tpl'}
{include file='ci:hybrid/topmenu.tpl'}

<div id="mainbody">
{include file='ci:gantt/subtitle.tpl'}
{include file='ci:gantt/main_form.tpl'}

<div id="gantt_link">
	<a href="javaScript:void(0);" id="gntt_prev_lerge">[&lt;&lt;]</a>
	<a href="javaScript:void(0);" id="gntt_prev">[&lt;]</a>
	<a href="javaScript:void(0);" id="gntt_now">now</a>
	<a href="javaScript:void(0);" id="gntt_next">[&gt;]</a>
	<a href="javaScript:void(0);" id="gntt_next_lerge">[&gt;&gt;]</a>
</div>
<div id="list"></div><!-- main contents -->

</div><!-- mainbody END -->

{include file='ci:hybrid/mainmenu.tpl'}
<div class="clearfloat"><hr></div>

{include file='ci:hybrid/footer.tpl'}
{include file='ci:hybrid/footer_script.tpl'}
{include file='ci:gantt/footer_script.tpl'}
</html>
