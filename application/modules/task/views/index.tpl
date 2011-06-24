<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>{$smarty.const.SITE_TITLE}</title>
<link rel="stylesheet" href="{site_url}css/mobile/reset.css">
<link rel="stylesheet" href="{site_url}css/mobile/base.css">

<meta name="robots" content="{if $smarty.const.DEV_MODE}noindex,nofollow{else}index,follow{/if}" />
<meta name="description" content="{$site_description}" />
{if $site_keywords}<meta name="keywords" content="{','|implode:$site_keywords}" />{/if}
<link rel="shortcut icon" href="{site_url uri=img/favicon_task.ico}" />

{$head_info}
</head>

{*<body id="{get_current_page_id}" onload="jQueryLoad('list', '{site_url uri=task/task_list}')">*}
<body id="{get_current_page_id}" onload="ajax_list_load('list', '{site_url uri=task/ajax_task_list}')">

{*
{include file='ci:mobile/header.tpl'}
{include file='ci:mobile/topmenu.tpl'}
*}

<div id="mainbody">
{*
{include file='ci:mobile/util/memo_topmenu.tpl'}
*}

<h1>タスク</h1>

<div>
<textarea name="body" id="body" cols="60" rows="3"></textarea>
<button id="send" onclick="send('{site_url uri=task/execute_insert}', 'list', '{site_url uri=task/ajax_task_list}')">投稿</button><br>
</div>

<div id="list"></div>


{*
{include file='ci:mobile/footer.tpl'}
*}
</div>
<footer>
Copyright : {$smarty.const.COPYRIGHT_SINCE} - {$smarty.now|date_format:"%Y"} {$smarty.const.COPYRIGHT_URL}
</footer>
</body>
{$foot_info}

<script type="text/javascript" src="{site_url}js/lib/jquery.js"></script>
{*<script type="text/javascript" src="{site_url}js/task.js"></script>*}

<script src="{site_url}js/lib/jeditable/jquery.jeditable.js" type="text/javascript"></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.autogrow.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.ajaxupload.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.masked.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.time.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.timepicker.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.datepicker.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.charcounter.js" type="text/javascript" ></script>
<script type="text/javascript" src="{site_url}js/lib/jeditable/js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="{site_url}js/lib/jeditable/js/jquery.timepicker.js"></script>
<script type="text/javascript" src="{site_url}js/lib/jeditable/js/jquery.autogrow.js"></script>
<script type="text/javascript" src="{site_url}js/lib/jeditable/js/jquery.charcounter.js"></script>
<script type="text/javascript" src="{site_url}js/lib/jeditable/js/date.js"></script>
<script type="text/javascript" src="{site_url}js/lib/jeditable/js/jquery.dimensions.js"></script>
<script type="text/javascript" src="{site_url}js/lib/jeditable/js/jquery.datePicker.js"></script>
<script type="text/javascript" src="{site_url}js/lib/jeditable/js/jquery.ajaxfileupload.js"></script>

<script type="text/javascript" charset="utf-8">
{literal}
// <![CDATA[
$(document).ready(function() {
	$(".autogrow").live("click", function(){
		$(".autogrow").editable("{/literal}{site_url uri=task/execute_update}{literal}", { 
				indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
				type      : "autogrow",
				submit    : 'OK',
//				submit    : '<input type="submit" value="OK" class="button">',
				cancel    : 'cancel',
				tooltip   : "Click to edit...",
				onblur    : "ignore",
				cssclass : "editable",
				select : true,
				autogrow : {
					 lineHeight : 16,
					 minHeight  : 32
				}
		})
	});
//	$(".button").live("click", function(){
//			$('#list').load({/literal}'{site_url uri=task/ajax_task_list}'{literal});
//alert('11111');
//	});
	$(".charcounter").editable("http://www.appelsiini.net/projects/jeditable/php/save.php", { 
			indicator : "<img src='img/indicator.gif'>",
			type      : "charcounter",
			submit    : 'OK',
			tooltip   : "Click to edit...",
			onblur    : "ignore",
			charcounter : {
				 characters : 60
			}
	});
	$(".masked").editable("http://www.appelsiini.net/projects/jeditable/php/save.php", { 
			indicator : "<img src='img/indicator.gif'>",
			type      : "masked",
			mask      : "99/99/9999",
			submit    : 'OK',
			tooltip   : "Click to edit..."
	});
	$(".datepicker").editable("http://www.appelsiini.net/projects/jeditable/php/save.php", { 
			indicator : "<img src='img/indicator.gif'>",
			type      : 'datepicker',
			tooltip   : "Click to edit..."
	});
	$(".timepicker").editable("http://www.appelsiini.net/projects/jeditable/php/save.php", { 
			indicator : "<img src='img/indicator.gif'>",
			type      : 'timepicker',
			submit    : 'OK',
			tooltip   : "Click to edit..."
	});
	$(".time").editable("http://www.appelsiini.net/projects/jeditable/php/save.php", { 
			indicator : "<img src='img/indicator.gif'>",
			type      : 'time',
			submit    : 'OK',
			tooltip   : "Click to edit..."
	});
	$(".ajaxupload").editable("http://www.appelsiini.net/projects/jeditable/php/upload.php", { 
			indicator : "<img src='img/indicator.gif'>",
			type      : 'ajaxupload',
			submit    : 'Upload',
			cancel    : 'Cancel',
			tooltip   : "Click to upload..."
	});
});
// ]]>
{/literal}
</script>

<script type="text/javascript">
{literal}
function ajax_list_load(id, url){
	ajax_list(id, url);
}
//function load_list(){
//alert('111111');
//	$('#list').load({/literal}'{site_url uri=task/ajax_task_list}'{literal});
//	$('#list').delay(5000000000000).load({/literal}'{site_url uri=task/ajax_task_list}'{literal});
//}
function ajax_list(id, url){
	$("#" + id).show();
	$.get(url, {page : 1}, function(data){
	 if (data.length>0){
		 $("#" + id).html(data);
	 }
	})
}
function send(post_url, id, get_url)  {
	// サーバに投稿メッセージを送信
	$.post( post_url, { "body" : $( 'textarea#body' ).val() } );
	// メッセージ入力欄をクリア
	$( 'textarea#body' ).val( '' ).focus();
	// ajax_list(id, get_url);
	$('#' + id).load(get_url);
}
// edit textarea autogrow
$(function(){
	$('textarea').autogrow();
});
{/literal}
</script>

</html>
