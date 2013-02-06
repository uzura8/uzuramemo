<script type="text/javascript" src="{site_url}js/util.js"></script>

{*
<script type="text/javascript" src="{site_url}js/lib/jquery.js"></script>
<script type="text/javascript" src="{site_url}js/lib/jquery-ui.min.js"></script>
*}
<script type="text/javascript" src="{site_url}js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="{site_url}js/bootstrap.min.js"></script>
<script type="text/javascript" src="{site_url}js/bootstrap-button.js"></script>

<script type="text/javascript" src="{site_url}js/jquery-ui-1.8.24.custom.min.js"></script>

<script src="{site_url}js/lib/jeditable/jquery.jeditable-callback.js" type="text/javascript"></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.autogrow.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/js/jquery.autogrow.js" type="text/javascript" ></script>

<script src="{site_url}js/jquery.form.js" type="text/javascript" ></script>
<script src="{site_url}js/jquery.validate.js" type="text/javascript" ></script>
<script src="{site_url}js/jquery.validate.japlugin.js" type="text/javascript" ></script>

<script src="{site_url}js/jquery.loading.js" type="text/javascript"></script>
<script src="{site_url}js/lib/jgrowl/jquery.jgrowl.js" type="text/javascript"></script>
<link rel="stylesheet" href="{site_url}js/lib/jgrowl/jquery.jgrowl.css">

<script src="{site_url}js/jquery.alphanumeric.js" type="text/javascript"></script>

<script src="{site_url}js/lib/jquery.alerts/jquery.alerts.js" type="text/javascript"></script>
<link rel="stylesheet" href="{site_url}js/lib/jquery.alerts/jquery.alerts.css">

<script src="{site_url}js/lib/jQselectable/jQselectable.js" type="text/javascript"></script>
<link rel="stylesheet" href="{site_url}js/lib/jQselectable/css/style.css">
<link rel="stylesheet" href="{site_url}js/lib/jQselectable/skin/selectable/style.css">

<script src="{site_url}js/jquery.cookie.js" type="text/javascript"></script>
<script src="{site_url}js/uzura_util.js" type="text/javascript"></script>

<link rel="stylesheet" href="{site_url}css/prettyPopin.css" type="text/css" media="screen" charset="utf-8" />
<script src="{site_url}js/jquery.prettyPopin.js" type="text/javascript" charset="utf-8"></script>

<script src="{site_url}js/jquery.slidescroll.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
{literal}
$(function(){
	$("a[href*='#']").slideScroll();
	$('textarea').autogrow();
});

$("a[rel^='prettyPopin']").bind("click", function(){
	var id_value = $(this).attr("id");
	var wbs_id = id_value.replace(/pp_link_wbs_/g, "");
	var mode = $("#list_mode").val();

	$.cookie('wbs_id_modal_activity_wbs', wbs_id);
	$.cookie('mode_modal_activity_wbs', mode);
});

$(document).ready(function(){
	uzura_modal('{/literal}{site_url}{literal}img/loader.gif', '{/literal}{site_url uri=activity/ajax_activity_list}{literal}', {/literal}'{$smarty.const.CURRENT_CONTROLLER}_{$smarty.const.CURRENT_ACTION}', '{$segment_3}'{literal});
});

function mainmanu_get_project_list(program_id) {
	var id_loading  = 'mainmenu_project_list_loading_' + program_id;
	$("#" + id_loading).show();

	var id  = 'mainmenu_project_list_' + program_id;
	var url = '{/literal}{site_url uri=project/ajax_project_list_mainenu}{literal}/' + program_id;
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
