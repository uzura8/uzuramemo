<script type="text/javascript">
{literal}
// edit textarea autogrow
$(function(){
	$('textarea').autogrow();
});

$("#select_order").jQselectable({
	style: "simple",
	height: 150,
	opacity: .9,
	callback: function(){
		var order = $(this).val();
		if (order != '0' && order != '1' && order != '2') {
			var order = '0';
		}
		ajax_list(0, order);
	}
});
{/literal}
</script>
