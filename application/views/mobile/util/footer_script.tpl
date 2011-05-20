<script type="text/javascript" src="{site_url}js/lib/jquery.js"></script>

<!-- auto_pager -->
<script src="{site_url}js/jquery.autopager.js" type="text/javascript"></script>
<script type="text/javascript">
{literal}
$(function() {
	$.autopager();
});

$(document).ready(function(){
	$('h2').click(function() {
		$(this).next().slideToggle();
	});
});
{/literal}
</script>
<!-- auto_pager -->
<script type="text/javascript" src="{site_url}js/smoothScroll.js"></script>
<script type="text/javascript" src="{site_url}js/jquery.lazyload.js"></script>
