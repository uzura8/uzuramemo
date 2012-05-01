function uzura_modal(img_url, list_url){
	$("a[rel^='prettyPopin']").prettyPopin({
		//modal : false, /* true/false */
		width : 750, /* false/integer */
		//width : 800, /* false/integer */
		height: 600, /* false/integer */
		opacity: 0.5, /* value from 0 to 1 */
		animationSpeed: '0', /* slow/medium/fast/integer */
		followScroll: true, /* true/false */
		loader_path: img_url, /* path to your loading image */
		callback : function(){
			var wbs_id = $.cookie('wbs_id_modal');
			$.cookie('wbs_id_modal', 0);
			//console.log(wbs_id);
			//alert(wbs_id);
			ajax_restructure_activity_list(wbs_id, list_url);
		}
	});
}

function ajax_restructure_activity_list(id, url){
	var id_name  = 'activity_list_' + id;

	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id_name).show();

	$.get(url, { nochache:(new Date()).getTime(), 'wbs_id': id }, function(data){
		$("#loading").fadeOut(function() {
			$("#pics").show();
		});
		if (data.length>0){
			$("#" + id_name).html(data);
		}
	});
}
