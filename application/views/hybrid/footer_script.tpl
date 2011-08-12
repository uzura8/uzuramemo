<script type="text/javascript" src="{site_url}js/util.js"></script>

<script type="text/javascript" src="{site_url}js/lib/jquery.js"></script>
<script type="text/javascript" src="{site_url}js/lib/jquery-ui.min.js"></script>

<script src="{site_url}js/lib/jeditable/jquery.jeditable.js" type="text/javascript"></script>
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

<script type="text/javascript">
{literal}
// edit textarea autogrow
$(function(){
	$('textarea').autogrow();
});

$('#select_order').change(function() {
	var order = parseInt($(this).val());
	ajax_list(0, order);
});
//$("#select_order").jQselectable({
//	style: "simple",
//	height: 150,
//	opacity: .9,
//	callback: function(){
//		var order = $(this).val();
//		if (order != '0' && order != '1' && order != '2') {
//			var order = '0';
//		}
//		ajax_list(0, order);
//	}
//});
{/literal}
</script>
