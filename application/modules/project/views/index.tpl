<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>{$smarty.const.SITE_TITLE}</title>
<link rel="stylesheet" href="{site_url}css/project.css">
<link rel="stylesheet" href="{site_url}css/mobile/reset.css">
<link rel="stylesheet" href="{site_url}css/mobile/base.css">
<link rel="stylesheet" href="{site_url}css/util.css">

<meta name="robots" content="{if $smarty.const.DEV_MODE}noindex,nofollow{else}index,follow{/if}">
<meta name="description" content="{$site_description}">
{if $site_keywords}<meta name="keywords" content="{','|implode:$site_keywords}">{/if}
<link rel="shortcut icon" href="{site_url uri=img/favicon_task.ico}">

{$head_info}
</head>
<body id="{get_current_page_id}">

<header id="top">
<h1><a href="{site_url uri=project}" style="">{$smarty.const.SITE_TITLE}</a></h1>
</header>
<div id="mainbody">

<h4 id="main_form_title" class="box_01"><a href="javaScript:void(0);">{$page_name}の作成</a></h4>
<div class="form_box box_01" id="main_form_box">

{form_open action=project/execute_insert id=main_form}
{foreach from=$form key=key item=items}
{if $ignore_keys}{if $ignore_keys|strpos:$key !== false}{php}continue;{/php}{/if}{/if}
<div class="form_row">
{if $items.type == 'input' || $items.type == 'textarea'}
	<label for="{$key}">{$items.label}</label>
{/if}
	<div class="input">
{if $items.children}{capture name="item_class"} class="{if $items.children}normal{/if}"{/capture}{/if}
{if $items.type == 'input' || $items.type == 'textarea'}{capture name="form_help"}{$items.rules|form_help:$smarty.capture.help_default}{/capture}{/if}
{if $items.type == 'input'}
	<input type="input" name="{$key}" id="{$key}" size="{$items.size}"{$smarty.capture.item_class|smarty:nodefaults}>
{if $key == 'name'}
	<span id="{$key}_loading"><img src="{site_url}img/loading.gif" alt="Ajax Indicator"></span>
	<span id="{$key}_result"></span>
{/if}
{elseif $items.type == 'textarea'}
	<textarea name="{$key}" id="{$key}"{if $items.cols} cols="{$items.cols}"{/if}{if $items.rows} rows="{$items.rows}"{/if}{$smarty.capture.item_class|smarty:nodefaults}></textarea>
{/if}
{if $smarty.capture.form_help && $items.rules_view == 'backword'}<span class="form_help">{$smarty.capture.form_help}</span>{/if}
{if $items.children}
{foreach from=$items.children item=child_key}
{assign var=ignore_keys value="`$ignore_keys`,`$child_key`"}
{if $form.$child_key.type == 'input' || $form.$child_key.type == 'textarea'}{capture name="form_help"}{$form.$child_key.rules|form_help}{/capture}{/if}
	<span class="sublabel" for="{$child_key}">{$form.$child_key.label}</span>
	<input type="input" name="{$child_key}" id="{$child_key}" size="{$form.$child_key.size}" class="narrow">
{if $child_key == 'key_name'}
	<span id="{$child_key}_loading"><img src="{site_url}img/loading.gif" alt="Ajax Indicator"></span>
	<span id="{$child_key}_result"></span>
{/if}
{if $smarty.capture.form_help && $items.rules_view == 'backword'}<span class="form_help">{$smarty.capture.form_help}</span>{/if}
{/foreach}
{/if}
	</div>
</div>
{/foreach}

<button id="main_form_submit" class="iPhoneButton">送信</button>
{form_close}
</div>

<div id="list"></div>
<div id="auto_pager"></div>

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
<script type="text/javascript" src="{site_url}js/lib/jquery-ui.min.js"></script>

<script src="{site_url}js/lib/jeditable/jquery.jeditable.js" type="text/javascript"></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.autogrow.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.ajaxupload.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.masked.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.time.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.timepicker.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.datepicker.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/jquery.jeditable.charcounter.js" type="text/javascript" ></script>

<script src="{site_url}js/lib/jeditable/js/jquery.maskedinput.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/js/jquery.timepicker.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/js/jquery.autogrow.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/js/jquery.charcounter.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/js/date.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/js/jquery.dimensions.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/js/jquery.datePicker.js" type="text/javascript" ></script>
<script src="{site_url}js/lib/jeditable/js/jquery.ajaxfileupload.js" type="text/javascript" ></script>

<script src="{site_url}js/jquery.autopager.js" type="text/javascript"></script>

<script src="{site_url}js/jquery.form.js" type="text/javascript" ></script>
<script src="{site_url}js/jquery.validate.js" type="text/javascript" ></script>
<script src="{site_url}js/jquery.validate.japlugin.js" type="text/javascript" ></script>

<script src="{site_url}js/jquery.loading.js" type="text/javascript"></script>
<script src="{site_url}js/lib/jgrowl/jquery.jgrowl.js" type="text/javascript"></script>
<link rel="stylesheet" href="{site_url}js/lib/jgrowl/jquery.jgrowl.css">

<script src="{site_url}js/jquery.alphanumeric.js" type="text/javascript"></script>
{*
<script src="{site_url}js/jquery.autohelp.js" type="text/javascript"></script>
<script src="{site_url}js/jquery.hint.js" type="text/javascript"></script>
*}

<script type="text/javascript" src="{site_url}js/jquery.lazyload.js"></script>

<script type="text/javascript" charset="utf-8">
{literal}
// <![CDATA[
$(document).ready(function() {
	$('h4').click(function() {
		$(this).next().slideToggle();
	});
	$("h4#main_form_title").next().hide('fast');

	$(".autogrow").live("click", function(){
		var project_id = $(this).attr("id");
		var item_name = $(this).attr("id").replace(/[0-9]+/g, "");
		var text_box_width = '250px';
		if (item_name == 'key_name') {
			var text_box_width = '50px';
		}

		//$(".autogrow").editable("{/literal}{site_url uri=project/execute_update}{literal}", {
		$("p#" + project_id).editable("{/literal}{site_url uri=project/execute_update}/{literal}" + item_name, {
			indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
			type      : "autogrow",
			submit    : 'OK',
			//submit    : '<input type="submit" value="OK" class="button">',
			cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=project/ajax_project_detail}{literal}/' + project_id + '/' + item_name,
			tooltip   : "Click to edit...",
			onblur    : "ignore",
			cssclass : "editable",
			select : true,
			//autogrow : {
			//	 lineHeight : 16,
			//	 minHeight  : 32
			//}
		})
		$("span#" + project_id).editable("{/literal}{site_url uri=project/execute_update}/{literal}" + item_name, {
			indicator : "<img src='{/literal}{site_url uri=js/lib/jeditable/img/indicator.gif}{literal}'>",
			type      : "text",
			width     : 'width: ' + text_box_width + ';',// js/lib/jeditable/jquery.jeditable.js : 455 を修正し style で指定できるように対応
			submit    : 'OK',
			//submit    : '<input type="submit" value="OK" class="button">',
			cancel    : 'cancel',
			loadurl    : '{/literal}{site_url uri=project/ajax_project_detail}{literal}/' + project_id + '/' + item_name,
			tooltip   : "Click to edit...",
			onblur    : "ignore",
			cssclass : "editable",
			select : true,
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
$('#main_form_submit').button();
$('#main_form').validate({
	rules : { {/literal}{convert2jquery_validate_rules form_items=$form}{literal} },
	errorClass: "validate_error",
	errorElement: "span",
//	errorLabelContainer: "#main_form_errorList",
//	wrapper: "li",
	submitHandler: function(form){
		$(form).loading({
			img: '{/literal}{site_url}{literal}img/loading.gif',
			align: 'center'
		});
		$(form).ajaxSubmit({
			success: function(data){
				// メッセージ入力欄をクリア
{/literal}
{foreach from=$form key=key item=items}{if $items.type == 'input' || $items.type == 'textarea'}
				$( '{$items.type}#{$key}' ).val( '' ).focus();
{/if}{/foreach}
{literal}
				ajax_list(0);
//			console.log(data);
				$.jGrowl('{/literal}{$page_name}{literal}を作成しました。');
			},
			error: function(){
				$.jGrowl('{/literal}{$page_name}{literal}を作成できませんでした。');
			},
			complete: function(){
				$(form).loading(false);
			}
		});
	}
});

// 重複確認: name
$(function() {
	$('#name_loading').hide();
	$('#name').blur(function() {
		$('#name_loading').show();
		$.post("{/literal}{site_url}{literal}project/ajax_check_project_name", {
			name: $('#name').val()
			}, function(response){
				$('#name_result').fadeOut();
					setTimeout("finishAjax('name_result', '"+escape(response)+"')", 400);
			});
			return false;
	});
});
// 重複確認: key_name
$(function() {
	$('#key_name_loading').hide();
	$('#key_name').blur(function() {
		$('#key_name_loading').show();
		$.post("{/literal}{site_url}{literal}project/ajax_check_project_key_name", {
			key_name: $('#key_name').val()
			}, function(response){
				$('#key_name_result').fadeOut();
					setTimeout("finishAjax('key_name_result', '"+escape(response)+"')", 400);
			});
			return false;
	});
});
function finishAjax(id, response) {
	var loading_parts_id = id.replace(/_result/g, "_loading");
	$('#'+loading_parts_id).hide();
	$('#'+id).html(unescape(response));
	$('#'+id).fadeIn();
}

$(function(){
	$('#key_name').alphanumeric({allow:"_"});
});

$(function(){
	ajax_list({/literal}{$from}{literal});
});

function ajax_list(offset){
	var id  = 'list';
	var url = '{/literal}{site_url uri=project/ajax_project_list}{literal}';

	// Ajaxによるアクセスにキャッシュを利用しない(毎回サーバにアクセス)
	$.ajaxSetup( { cache : false } );
	$("#" + id).show();

	$.get(url, {nochache : (new Date()).getTime(), search : {/literal}'{$search}'{literal}, order : {/literal}'{$order}'{literal}, from : offset }, function(data){
		if (data.length>0){
			$("#" + id).html(data);
		}
	})
{/literal}{if $pagination.next_url}
	var next = offset + {$limit};
	var next_url = '{site_url}project' + '?nochache=' + (new Date()).getTime() + '&search={$search}&order={$order}&from=' + next;
	$("#auto_pager").html("<nav id='next'><a href='" + next_url + "' rel='next'>もっと見る</a></nav>");
{/if}{literal}
}

//function send(post_url, id, get_url)  {
//	$.post( post_url, { {/literal}{convert2ajax_post_string form_items=$form}{literal} } );
//	ajax_list(id, get_url, 0);
//	$( 'textarea#body' ).val( '' ).focus();
//	return false;
//}

// edit textarea autogrow
$(function(){
	$('textarea').autogrow();
});

// help view
//$(function() {
//	$("form *").autohelp("#autohelp");
//	$("#autohelp").css({"background":"#BED1D8","color":"#fff", "padding":"3px", "font-size":"9px"});
//});

// hint view
//$(function() {
//	// find all the input elements with title attributes
//	$('input[title!=""]').hint();
//	$('textarea[title!=""]').hint();
//});

{/literal}
</script>

<script type="text/javascript" src="{site_url}js/jquery.slidescroll.js"></script>
<script type="text/javascript">
{literal}
$(function(){
  $("a[href*='#']").slideScroll();
});
{/literal}
</script>

</html>
