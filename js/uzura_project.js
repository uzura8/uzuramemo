function uzura_sortable(url){
	$('#jquery-ui-sortable').sortable({
		items: '.jquery-ui-sortable-item',
		handle: 'span.btnTop',
		update: function(event, ui) {
			var updateArray = $('#jquery-ui-sortable').sortable('toArray').join(',');

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
					ajax_list(0);
					$.jGrowl('並び順を変更できませんでした。');
				}
			});
		}
	});
}

function uzura_datepicker(button_image_url){
	//テキストボックスにカレンダーをバインドする（パラメータは必要に応じて）
//	$("#due_date_27").datepicker({
	$(".input_date").datepicker({
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
}
