<script language="JavaScript" type="text/javascript" src="{site_url}js/focus.js"></script>

<script language="JavaScript" type="text/javascript">
{literal}function selectjump(obj) {{/literal}
	var text = obj.options[obj.selectedIndex].value;
	var url = "{$list_url|smarty:nodefaults}&order=" + text;
	location.href = url;
{literal}}{/literal}
</script>

{include file='ci:util/category_menu_script.tpl'}

<script type="text/javascript" src="{site_url}js/lib/jquery.js"></script>
<script type="text/javascript">
{literal}var j$ = jQuery.noConflict();{/literal}
</script>
<!-- search word heighlighter -->

<!-- auto_pager -->
<script src="{site_url}js/jquery.autopager2.js" type="text/javascript"></script>
<script type="text/javascript">
{literal}
j$(function() {
	j$.autopager();
});
{/literal}
</script>
<!-- auto_pager -->
