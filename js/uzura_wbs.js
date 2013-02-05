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
