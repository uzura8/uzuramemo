<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>

<body id="{get_current_page_id}">
<div id="list_top"></div>

<div class="content">
{if $wbs_id}
<div>
<span class="link_right"><a rel="prettyPopin" class="new_form_switch_{$wbs_id}" href="{site_url}activity/create/{$wbs_id}/1">&raquo;&nbsp;新規作成</a></span>
</div>
{/if}

{if !$list}
<div style="padding:5px;">No {get_config_value key=site_title index=activity}.</div>
{else}
<!-- main_list -->
<ul>
{foreach from=$list item=row}
	<li><a rel="prettyPopin" class="new_form_switch_{$row.wbs_id}" href="{site_url}activity/create/{$row.wbs_id}/1/{$row.id}">{$row.name}</a></li>
{/foreach}
</ul>
</div>

{/if}
</div>
</body>

<script type="text/javascript" charset="utf-8">
{literal}
$("a[rel^='prettyPopin']").bind("click", function(){
	var id_value = $(this).attr("class");
	var wbs_id = id_value.replace(/new_form_switch_/g, "");

	$.cookie('wbs_id_modal', wbs_id);
});

$(document).ready(function(){
	uzura_modal('{/literal}{site_url}{literal}img/loader.gif', '{/literal}{site_url uri=activity/ajax_activity_list_simple}{literal}');
});
{/literal}
</script>
</html>
