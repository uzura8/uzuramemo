<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>

<body id="{get_current_page_id}">
<div id="list_top"></div>

{if !$list}
<div style="padding:30px 10px 30px 5px;">{if $search}「{$search}」に一致する{elseif $now_category_id}このカテゴリの{else}指定した記事の{/if}登録はありません。</div>
{if $search}
<div style="margin-bottom:20px;">
「<a href="http://www.google.co.jp/search?q={$search}" target="_blank" style="font-weight:bold;">{$search}</a>」をGoogle検索
<span style="margin-left:20px;"><a href="http://www.google.co.jp/search?q={$search}&as_qdr=m6" target="_blank">6ヶ月以内</a></span>
</div>
{/if}
{else}

<!-- main_list -->
<div id="jquery-ui-sortable">
<div class="content">
{foreach from=$list item=row}
<div{if !$order} class="jquery-ui-sortable-item"{/if} id="{$row.id}">
<a name="id_{$row.id}"></a>
<h2 class="box_01" id="article_title_{$row.id}" style="background-color:{'background-color'|site_get_style:$row.del_flg};">
<div>
<span id="name{$row.id}" class="autogrow">{$row.name}</span>
{if $row.key_name}<span id="key_name{$row.id}" class="autogrow sub_info2"{if $row.color || $row.background_color} style="{if $row.color}color:{$row.color};{/if}{if $row.background_color}background-color:{$row.background_color};{/if}"{/if}>{$row.key_name}</span>{/if}
<span class="btnTop list_util_btn wider space_left" id="title_btn_{$row.id}"><a href="javaScript:void(0);" onclick="$('#article_{$row.id}').slideToggle();">▼</a></span>
<span class="link_right_each line_height_15 space_left"><a id="new_form_switch_register_{$row.id}" href="{site_url}project/index/{$row.id}?edit=1">&raquo;&nbsp;作成</a></span>
<span class="link_right_each line_height_15"><a id="new_form_switch_list_{$row.id}" href="{site_url}project/index/{$row.id}">&raquo;&nbsp;{get_config_value key=site_title index=project}一覧</a></span>
</div>
<div class="article_meta_top">
<div class="banner"></div>
<div class="meta_info">
<span>No.{$row.id}</span>
<span class="space_left_5">update: {$row.updated_at|date_format:"%Y/%m/%d %H:%M"}</span>
</div>
<div style="clear: both"></div>
</div>
</h2>
<article class="box_01" id="article_{$row.id}" style="display:none;background-color:{'background-color'|site_get_style:$row.del_flg};">
<div class="article_box">
<p class="autogrow" id="body{$row.id}" style="width: 300px">{if $row.body}{$row.body|nl2br|auto_link}{else}&nbsp;&nbsp;{/if}</p>
</div>
{*
<span class="link_right_each f_bld"><a id="new_form_switch" href="{site_url}project/index/{$row.key_name}/?edit=1">&raquo;&nbsp;{get_config_value key=site_title index=project}作成</a></span>
*}
<div class="clearfloat"><hr></div>

<span class="title sl5">Color settings:</span>
<span class="label">背景色</span>
<input name="background_color" id="input_background_color_{$row.id}" class="width_8 input_each simple_color_picker" type="text" value="{$row.background_color}">
<span class="label sl2">文字色</span>
<input name="color" id="input_color_{$row.id}" class="width_8 input_each simple_color_picker_mono" type="text" value="{$row.color}">

<aside class="article_footer">
{if $row.explanation}
<div class="quote_box">
<span class="title">引用元:</span><span class="space_left_5">{$row.explanation}</span>
</div>
{/if}
<div class="article_aside_button">
<span class="btnSpan"><input type="button" name="update_del_flg_{$row.id}" value="{$row.del_flg|site_get_symbols_for_display}" id="btn_delFlg_{$row.id}" class="btn_delFlg"></span>
<span class="btnSpan space_left_5"><input type="button" name="delete_{$row.id}" value="削除" id="btn_delete_{$row.id}" class="btn_delete"></span>
{if $order}
<span class="btnSpan space_left">
<label for="input_sort_{$row.id}">並び順:</label>
<input type="text" name="input_sort_{$row.id}" value="{$row.sort}" id="input_sort_{$row.id}" class="InputMini input_sort" maxlength="6" placeholder="並び順"></span>
{/if}
<span class="btnSpan space_left">
<label for="input_sort_{$row.id}">private:</label>
<input type="checkbox" name="private_flg" value="1" id="input_private_flg_{$row.id}" class="input_private_flg"{if $row.private_flg}checked="checked"{/if}></span>

<span class="btnTop space_left_5"><a href="#top">▲</a></span>
<span class="btnTop space_left_5"><a href="{site_url uri=program}">{$smarty.const.UM_TOPPAGE_NAME}</a></span>
<span class="btnTop"><a href="{site_url}">サイト{$smarty.const.UM_TOPPAGE_NAME}</a></span>
</div>
</aside>
</article>
</div>
{/foreach}
</div>
</div>
{if $search}
<section>
「<a href="http://www.google.co.jp/search?q={$search}" target="_blank" style="font-weight:bold;">{$search}</a>」をGoogle検索
<span style="margin-left:20px;"><a href="http://www.google.co.jp/search?q={$search}&as_qdr=m6" target="_blank">6ヶ月以内</a></span>
</section>
{/if}

{if $pagination.next_url}<nav id="next"><a href="{$pagination.next_url}" rel="next">もっと見る</a></nav>{/if}

{/if}
</div>
</body>

<script type="text/javascript" src="{site_url}js/jquery.simple-color-picker.js"></script>
<script type="text/javascript" charset="utf-8">
{literal}
$(document).ready(function() {
	var simple_colors = ['#000000', '#666666', '#999999', '#cccccc', '#eeeeee', '#ffffff'];
	$('input.simple_color_picker_mono').simpleColorPicker({
		colors: simple_colors,
		onChangeColor: function(color) {
			$(this).val(color);
			var id_value = $(this).attr("id");
			var id = id_value.replace(/input_color_/g, "");

			var url = "{/literal}{site_url}{literal}program/ajax_execute_update_color";
			uzura_program_update_color(id, color, url, 'color');
			$('span#key_name' + id).css('color', color);
		},
	});
	$('input.simple_color_picker').simpleColorPicker({
		onChangeColor: function(color) {
			$(this).val(color);
			var id_value = $(this).attr("id");
			var id = id_value.replace(/input_background_color_/g, "");

			var url = "{/literal}{site_url}{literal}program/ajax_execute_update_color";
			uzura_program_update_color(id, color, url, 'background_color');
			$('span#key_name' + id).css('background-color', color);
		},
	});
});

function uzura_program_update_color(id, color, url, type) {
	var csrf_token = $.cookie('csrf_test_name');
	var color_name = '色';
	if (type == 'background_color') {
		color_name = '背景色';
	} else if (type == 'color') {
		color_name = '文字色';
	}

	// 更新
	$.ajax({
		url : url,
		dataType : "text",
		data : {"id": id, "value": color, "type": type, "csrf_test_name": csrf_token},
		type : "POST",
		success: function(data){
			$.jGrowl('No.' + id + 'の' + color_name + 'を変更しました。');
		},
		error: function(data){
			$.jGrowl('No.' + id + 'の' + color_name + 'を変更できませんでした。');
		}
	});
}
{/literal}
</script>

<script type="text/javascript" src="{site_url}js/jquery.autopager.js"></script>
<script type="text/javascript" src="{site_url}js/jquery.lazyload.js"></script>
<script type="text/javascript" src="{site_url}js/jquery.slidescroll.js"></script>
<script type="text/javascript" charset="utf-8">
{literal}
$(function() {
	$.autopager({
		autoLoad: false,
		content: '.content',
		load: function(current, next) {
			if (current.page == {/literal}{$max_page}{literal}) {
				$('nav#next').remove();
			}
			uzura_sortable("{/literal}{site_url}{literal}program/ajax_execute_update_sort_move");
		},
	});
	$('a[rel=next]').click(function() {
		$.autopager('load');
		return false;
	});
});

$(function(){
	uzura_sortable("{/literal}{site_url}{literal}program/ajax_execute_update_sort_move");
});
{/literal}
</script>

</html>