<script language="JavaScript" type="text/javascript" src="{site_url}js/focus.js"></script>

<script language="JavaScript" type="text/javascript">
{literal}function selectjump(obj) {{/literal}
	var text = obj.options[obj.selectedIndex].value;
	var url = "{$list_url_without_order|smarty:nodefaults}&order=" + text;
	location.href = url;
{literal}}{/literal}
</script>
{*{include file='ci:util/category_menu_script.tpl'}*}

<script type="text/javascript" src="{site_url}js/lib/jquery.js"></script>
{include file='ci:util/syntaxhighlighter.tpl'}
<script type="text/javascript">
{literal}var j$ = jQuery.noConflict();{/literal}
</script>

<script type="text/javascript">
{literal}
j$(document).ready(function(){
  j$("#acc_menu").each(function(){
    j$("p.lb_01", this).each(function(index){
      var $this = j$(this);
      if($this.next().attr("id") != 'menu_boxSP001'{/literal}{if $now_category.sub_id}{literal} && $this.next().attr("id") != 'menu_box'+{/literal}{$now_category.sub_id}{/if}{literal}) $this.next().hide('fast');

      $this.click(function(){
        j$("p.lb_01").next().hide('fast');
        j$(this).next().slideToggle('fast');
      });
    });
  });
});
{/literal}
</script>

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
