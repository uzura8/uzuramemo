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

	ajax_get_total_times(date);
}

function ajax_get_total_times(date){
	if (date == 'past') return false;

	var get_url = util_get_base_url() + 'activity/ajax_activity_get_total_times/' + date;
	var get_data = {};
	get_data['nochache']  = (new Date()).getTime();

	$.ajax({
		url : get_url,
		type : 'GET',
		dataType : 'json',
		data : get_data,
		timeout: 10000,
		beforeSend: function(xhr, settings) {
		},
		complete: function(xhr, textStatus) {
		},
		success: function(result) {
			//{"estimated_time":"1","spent_time":null}
			//var resData = $.parseJSON(result);
			var estimated_time = result.estimated_time;
			if (estimated_time == null) estimated_time = 0;
			var spent_time = result.spent_time;
			if (spent_time == null) spent_time = 0;

			$('#estimated_time_' + date).html(estimated_time);
			$('#spent_time_' + date).html(spent_time);
		},
		error: function(result) {
		}
	});

	return false;
}
