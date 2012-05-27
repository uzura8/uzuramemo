<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>
<body id="{get_current_page_id}">
{if $list}
{foreach from=$list item=row}
	<h5 class="title">
		<a href="{site_url}wbs/index/{$row.id}">{$row.name}</a>
		<span class="btnTop" id="mainmenu_wbs_{$row.id}"><a href="javaScript:void(0);" onclick="mainmanu_get_wbs_list({$row.id});">▼</a></span>
	</h5>
	<div id="mainmenu_wbs_loading_{$row.id}" style="display:none;"><img src="{site_url}img/loading.gif"></div>
	<div id="mainmenu_wbs_list_{$row.id}"></div>
{/foreach}
{/if}
</body>
<script type="text/javascript" charset="utf-8">
{literal}
function mainmanu_get_wbs_list(project_id) {
	var id_loading  = 'mainmenu_wbs_list_loading_' + project_id;
	$("#" + id_loading).show();

	var id  = 'mainmenu_wbs_list_' + project_id;
	var url = '{/literal}{site_url uri=wbs/ajax_wbs_list_mainenu}{literal}/' + project_id;
	var csrf_token = $.cookie('csrf_test_name');

	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id).show();

	$.get(url, {nocache : (new Date()).getTime()}, function(data){
//		$("#id_loading").fadeOut(function() {
//			$("#pics").show();
//		});
		$("#" + id_loading).fadeOut();
		if (data.length>0){
			$("#" + id).html(data);
		}
	})
}
{/literal}
</script>
</html>
