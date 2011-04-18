<script language="JavaScript" type="text/javascript" src="{site_url}js/focus.js"></script>

<script language="JavaScript" type="text/javascript">
{literal}function selectjump(obj) {{/literal}
	var text = obj.options[obj.selectedIndex].value;
	var url = "{$list_url|smarty:nodefaults}&order=" + text;
	location.href = url;
{literal}}{/literal}
</script>

<!-- prototype.js -->
<script type="text/javascript" src="{site_url}js/prototype.js"></script>
<script type="text/javascript">
window.onload = function hideText(){literal}{{/literal}
{foreach from=$cate_id_list item=value}
{if $now_category.sub_id != $value}
	Element.hide("menu_box{$value}");
{/if}
{/foreach}
{literal}}{/literal}

{foreach from=$cate_id_list item=value}
function toggleBox{$value}(){literal}{{/literal}
	Element.toggle("menu_box{$value}");
{foreach from=$cate_id_list item=value2}
{if $value2 != $value}
	Element.hide("menu_box{$value2}");
{/if}
{/foreach}
{literal}}{/literal}
{/foreach}
</script>
<!-- prototype.js -->

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
