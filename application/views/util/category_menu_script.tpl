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
