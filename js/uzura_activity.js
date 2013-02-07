function ajax_activity_list_date(date){
	var mode = (arguments.length > 1) ? arguments[1] : 0;
	var id_name  = 'activity_list_' + date;
	var url = util_get_base_url() + 'activity/ajax_activity_list_date/' + date + '?mode='+mode;

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
