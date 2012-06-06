function ajax_activity_list(wbs_id, url){
	var mode = (arguments.length > 2) ? arguments[2] : 0;
	var id_name  = 'activity_list_' + wbs_id;

	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id_name).show();

	$.get(url, { 'nochache':(new Date()).getTime() }, function(data){
		$("#loading_" + id_name).fadeOut(function() {
			$("#pics_" + id_name).show();
		});

		if (data.length>0) {
			$("#" + id_name).html(data);
		} else {
			if (mode == 2){
				$("#wbs_" + wbs_id).hide('fast', function(){ $(this).remove(); });
			} else {
				$("#" + id_name).html('<div style="margin-top:5px;">No Activities.</div>');
			}
		}
	});
}

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
			var wbs_id = $.cookie('wbs_id_modal_activity_wbs');
			var mode = $.cookie('mode_modal_activity_wbs');

			$.cookie('wbs_id_modal_activity_wbs', null);
			$.cookie('mode_modal_activity_wbs', null);
			ajax_activity_list(wbs_id, list_url + '/' + wbs_id + '?mode=' + mode);
		}
	});
}

function toggle_importance_star(id, url, id_prefix){
	var csrf_token = $.cookie('csrf_test_name');
	var item_id = '#' + id_prefix + id;
	$.ajax({
		url : url,
		dataType : "text",
		data : {"id": id, "csrf_test_name": csrf_token},
		type : "POST",
		success: function(status_after){
			if (status_after == "1") {
				$(item_id).text('★');
				$(item_id).css('color', '#FC0000');
				//$(item_id).css('font-size', '110%');
				$.jGrowl('No.' + id + 'に Star をつけました。');
			} else {
				$(item_id).text('☆');
				$(item_id).css('color', '#DDD');
				$//(item_id).css('font-size', '100%');
				$.jGrowl('No.' + id + 'の Star を外しました。');
			}
		},
		error: function(){
			$.jGrowl('No.' + id + 'の Star の更新に失敗しました。');
		}
	});
}
