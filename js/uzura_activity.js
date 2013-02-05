function ajax_activity_list_date(date, url){
	var mode = (arguments.length > 2) ? arguments[2] : 0;
	var id_name  = 'activity_list_' + date;

	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id_name).show();

	$.get(url, { nochache:(new Date()).getTime() }, function(data){
		$("#loading_" + id_name).fadeOut(function() {
			$("#pics_" + id_name).show();
		});
		if (data.length>0){
			$("#" + id_name).html(data);
		}
	});
}
