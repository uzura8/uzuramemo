{literal}
<script language="JavaScript" src="{$smarty.const.BASE_URL}js/focus.js"></script>
<script language="JavaScript" type="text/javascript">
function selectjump(obj) {
	var text = obj.options[obj.selectedIndex].value;
	var url = "{$nowurl_noqry}?from=0&order=" + text + "&search={$view_search_word}&opt={$opt}";
	location.href = url;
}
</script>
{/literal}

<!-- prototype.js -->
<script type="text/javascript" src="{$smarty.const.BASE_URL}js/prototype.js"></script>
<script type="text/javascript">
window.onload = function hideText(){literal}{{/literal}
{foreach from=$cate_id_list item=value}
{* {if ($now_category && $now_category['mc_sub_id'] != $value) && $now_now_category_id != $value} *}
{if $now_now_category_id != $value}
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

<!-- search word heighlighter -->
{literal}
<script src="{$smarty.const.BASE_URL}js/se_hilite.js"></script>
<style type="text/css">
.hilite1, .hilite4, .hilite7 { background-color: #ffa; }
.hilite2, .hilite5, .hilite8 { background-color: #faf; }
.hilite3, .hilite6, .hilite9 { background-color: #aff; }
</style>

<script src="{$smarty.const.BASE_URL}js/lib/jquery.js" type="text/javascript"></script>
<script>
var j$ = jQuery.noConflict();
</script>
{/literal}
<!-- search word heighlighter -->

<!-- auto_pager -->
{literal}
<script src="{$smarty.const.BASE_URL}js/jquery.autopager2.js" type="text/javascript"></script>
<script type="text/javascript">
  j$(function() {
      j$.autopager();
  });
</script>
{/literal}
<!-- auto_pager -->
