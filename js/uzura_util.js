function uzura_sortable(url){
	var parent_name = (arguments.length > 1) ? arguments[1] : '#jquery-ui-sortable';
	var child_name  = (arguments.length > 2) ? arguments[2] : '.jquery-ui-sortable-item';
	var button_name = (arguments.length > 3) ? arguments[3] : 'span.btnTop';

	$(parent_name).sortable({
		items: child_name,
		handle: button_name,
		update: function(event, ui) {
			var updateArray = $(parent_name).sortable('toArray').join(',');

			// 更新
			var csrf_token = $.cookie('csrf_test_name');
			$.ajax({
				url : url,
				dataType : "text",
				data : {"values": updateArray, "csrf_test_name": csrf_token},
				type : "POST",
				success: function(data){
					//ajax_list(0);
					$('#select_order').val('0');
					$.jGrowl('並び順を変更しました。');
				},
				error: function(data){
					//ajax_list(0);
					$.jGrowl('並び順を変更できませんでした。');
				}
			});
		}
	});
}

function uzura_datepicker(item_name){
	var button_image_url = (arguments.length > 1) ? arguments[1] : '';
	if (button_image_url) {
		//テキストボックスにカレンダーをバインドする（パラメータは必要に応じて）
		$(item_name).datepicker({
			showButtonPanel: true,//「今日」「閉じる」ボタンを表示する
			firstDay: 1,//週の先頭を月曜日にする（デフォルトは日曜日）

			showOn: 'button',
			buttonImage: button_image_url,
			buttonImageOnly: true,

			//年月をドロップダウンリストから選択できるようにする場合
	//		changeYear: true,
			changeMonth: true,

			prevText: '&#x3c;前',
			nextText: '次&#x3e;',

			// 選択可能な日付の範囲を限定する場合（月は0～11）
			// minDate: new Date(2010, 6 - 1, 16),
			// maxDate: new Date(2010, 8 - 1, 15)
		});
	} else {
		//テキストボックスにカレンダーをバインドする（パラメータは必要に応じて）
		$(item_name).datepicker({
			showButtonPanel: true,//「今日」「閉じる」ボタンを表示する
			firstDay: 1,//週の先頭を月曜日にする（デフォルトは日曜日）
			//年月をドロップダウンリストから選択できるようにする場合
	//		changeYear: true,
			changeMonth: true,

			prevText: '&#x3c;前',
			nextText: '次&#x3e;',

			// 選択可能な日付の範囲を限定する場合（月は0～11）
			// minDate: new Date(2010, 6 - 1, 16),
			// maxDate: new Date(2010, 8 - 1, 15)
		});
	}
}

function uzura_form_switch(){
	$('#new_form_switch').click(function() {
		$('#main_form_box').slideToggle();
		$('input#name').focus();
	});
}

function uzura_modal(img_url, list_url, action, segment_3){
	$("a[rel^='prettyPopin']").prettyPopin({
		//modal : false, /* true/false */
		width : 750, /* false/integer */
		//width : 800, /* false/integer */
		height: 600, /* false/integer */
		opacity: 0.5, /* value from 0 to 1 */
		animationSpeed: '0', /* slow/medium/fast/integer */
		//theme: dark_rounded, /* テーマ light_rounded / dark_rounded / light_square / dark_square */
		followScroll: true, /* true/false */
		loader_path: img_url, /* path to your loading image */
		callback : function(){
			var wbs_id = $.cookie('wbs_id_modal_activity_wbs');
			var mode = $.cookie('mode_modal_activity_wbs');

			$.cookie('wbs_id_modal_activity_wbs', null);
			$.cookie('mode_modal_activity_wbs', null);

			switch (action){
				case 'activity_wbs':
					if (wbs_id == segment_3) {
						ajax_activity_list(wbs_id, list_url + '/' + wbs_id + '?mode=' + mode);
					}
					break;
				case 'activity_wbs':
					ajax_activity_list_date_all();
					break;
			}
		}
	});
}

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
