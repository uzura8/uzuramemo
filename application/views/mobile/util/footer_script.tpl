<script language="JavaScript" type="text/javascript">
{literal}function selectjump(obj) {{/literal}
	var text = obj.options[obj.selectedIndex].value;
	var url = "{$list_url_without_order|smarty:nodefaults}&order=" + text;
	location.href = url;
{literal}}{/literal}
</script>

<script type="text/javascript" src="{site_url}js/lib/jquery.js"></script>

<script src="{site_url}js/jquery.autopager.js" type="text/javascript"></script>
<script type="text/javascript">
{literal}
$(function() {
	$.autopager();
});
{/literal}
</script>

<script type="text/javascript" src="{site_url}js/jquery.slidescroll.js"></script>
<script type="text/javascript">
{literal}
$(function(){
	$("a[href*='#']").slideScroll();
});
{/literal}
</script>

<script type="text/javascript" src="{site_url}js/jquery.lazyload.js"></script>
<script type="text/javascript">
{literal}
$(document).ready(function(){
	$('h2').click(function() {
		$(this).next().slideToggle();
	});
	$('h4').click(function() {
		$(this).next().slideToggle();
	});
	$('#list_util_btn').click(function() {
		$(this).next().slideToggle();
	});
});
{/literal}
</script>

