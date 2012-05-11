<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>{$smarty.const.SITE_TITLE}</title>
<link rel="stylesheet" href="{site_url}css/task.css">
<link rel="stylesheet" href="{site_url}css/mobile/reset.css">
<link rel="stylesheet" href="{site_url}css/mobile/base.css">

<meta name="robots" content="{if $smarty.const.DEV_MODE}noindex,nofollow{else}index,follow{/if}" />
<meta name="description" content="{$site_description}" />
{if $site_keywords}<meta name="keywords" content="{','|implode:$site_keywords}" />{/if}
<link rel="shortcut icon" href="{site_url uri=img/favicon_task.ico}" />

{$head_info}
</head>

<body id="{get_current_page_id}" onload="ajax_list_load('list', '{site_url uri=task/ajax_task_list}')">
{*
{include file='ci:mobile/header.tpl'}
{include file='ci:mobile/topmenu.tpl'}
*}
<header id="top">
<h1><a href="{site_url uri=task}" style="">{$smarty.const.SITE_TITLE}</a></h1>
</header>

<div id="mainbody">

<div class="form_box">
<textarea name="body" id="body" cols="60" rows="3"></textarea>
<nav id="send" onclick="send('{site_url uri=task/execute_insert}', 'list', '{site_url uri=task/ajax_task_list}')"><a href="">投稿</a></nav>
</div>

<div id="list"></div>
<div id="auto_pager"></div>
{*<div id="auto_pager"><a href="{$pagination.next_url}" rel="next">次のページ / Next</a></div>*}
{*
<div id="test_pager"><a href="javascript:void(0);" rel="next" onclick="load_next_list('{site_url uri=task/ajax_task_list}')">次のページ / Next</a></div>
*}

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

<script src="{site_url}js/jquery.autopager.js" type="text/javascript"></script>

<script type="text/javascript" charset="utf-8">
{literal}
// <![CDATA[
$(document).ready(function() {
	$(".autogrow").live("click", function(){
		var task_id = $(this).attr("id");
//		$(".autogrow").editable("{/literal}{site_url uri=task/execute_update}{literal}", { 
		$("p#" + task_id).editable("{/literal}{site_url uri=task/execute_update}{literal}", { 
				indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
				type      : "autogrow",
				submit    : 'OK',
//				submit    : '<input type="submit" value="OK" class="button">',
				cancel    : 'cancel',
				loadurl    : '{/literal}{site_url uri=task/ajax_task_detail}{literal}/' + task_id,
				tooltip   : "Click to edit...",
				onblur    : "ignore",
				cssclass : "editable",
				select : true,
//				autogrow : {
//					 lineHeight : 16,
//					 minHeight  : 32
//				}
		})
	});
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
	ajax_list(id, url, {/literal}{$from}{literal});
}

function ajax_list_load_next(id, url){
	var offset = {/literal}{$from}{literal} + {/literal}{$limit}{literal};

	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id).show();

	$.get(url, {nochache : (new Date()).getTime(), search : {/literal}'{$search}'{literal}, order : {/literal}'{$order}'{literal}, from : offset }, function(data){
		if (data.length>0){
			$("#" + id).html(data);
		}
	})
}

function ajax_list_get(id, url, offset){
	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id).show();

	$.get(url, {nochache : (new Date()).getTime(), search : {/literal}'{$search}'{literal}, order : {/literal}'{$order}'{literal}, from : offset }, function(data){
		if (data.length>0){
			$("#" + id).html(data);
		}
	})

//	var next_url = url + '?nochache=' + (new Date()).getTime() + '{/literal}&search={$search}&order={$order}&from={$from}{literal}';
//	var next_url = '{/literal}{$pagination.next_url}{literal}';
//	$("#auto_pager").html("<a href='" + next_url + "' rel='next'>次のページ / Next</a>");
}

function ajax_list(id, url, offset){
	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id).show();

	$.get(url, {nochache : (new Date()).getTime(), search : {/literal}'{$search}'{literal}, order : {/literal}'{$order}'{literal}, from : offset }, function(data){
		if (data.length>0){
			$("#" + id).html(data);
		}
	})

	var next = offset + {/literal}{$limit}{literal};
	var next_url = {/literal}'{site_url}task'{literal} + '?nochache=' + (new Date()).getTime() + '{/literal}&search={$search}&order={$order}{literal}&from=' + next;
	$("#auto_pager").html("<nav id='next'><a href='" + next_url + "' rel='next'>もっと見る</a></nav>");
}

//function load_next_list(url){
//	var offset = 0;
//
//	$.get(url, {nochache : (new Date()).getTime(), search : {/literal}'{$search}'{literal}, order : {/literal}'{$order}'{literal}, from : offset }, function(data){
//		if (data.length>0){
//			$("#test_pager").html(data);
//		}
//	})
//}

function send(post_url, id, get_url)  {
	// サーバに投稿メッセージを送信
	$.post( post_url, { "body" : $( 'textarea#body' ).val() } );
	ajax_list(id, get_url, 0);
	// メッセージ入力欄をクリア
	$( 'textarea#body' ).val( '' ).focus();

	return false;
}
{/literal}
</script>

<script type="text/javascript" src="{site_url}js/jquery.slidescroll.js"></script>

</html>
