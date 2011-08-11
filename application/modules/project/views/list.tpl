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
<div class="content">
{foreach from=$list item=row}
<a name="id_{$row.id}"></a>
<h2 class="box_01" id="article_title_{$row.id}" style="background-color:{'background-color'|site_get_style:$row.del_flg};">
<div>
<span id="name{$row.id}" class="autogrow">{$row.name}</span>{if $row.key_name}<span id="key_name{$row.id}" class="autogrow sub_info2">{$row.key_name}</span>{/if}
<span class="btnTop list_util_btn wider space_left" id="title_btn_{$row.id}"><a href="javaScript:void(0);" onclick="$('#article_{$row.id}').slideToggle();">▼</a></span>
<span class="link_right_each line_height_15 space_left"><a id="new_form_switch" href="{site_url}wbs/index/{$row.key_name}?edit=1">&raquo;&nbsp;作成</a></span>
<span class="link_right_each line_height_15"><a id="new_form_switch" href="{site_url}wbs/index/{$row.key_name}">&raquo;&nbsp;{get_config_value key=site_title index=wbs}一覧</a></span>
</div>
<div class="article_meta_top">
<div class="banner">{$row.program_name}</div>
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

<div class="form_box_each">
	<label for="due_date">期日</label>
	<div class="input">
		<input name="due_date_{$row.id}" id="input_due_date_{$row.id}" class="width_10 input_date" type="text" value="{$row.due_date}">
		<span class="btnSpan"><input type="button" name="btn_due_date_{$row.id}" value="更新" id="btn_due_date_{$row.id}" class="btn_due_date"></span>
	</div>
</div>

</div>
<div class="clearfloat"><hr></div>

<aside class="article_footer">
{if $row.explanation}
<div class="quote_box">
<span class="title">引用元:</span><span class="space_left_5">{$row.explanation}</span>
</div>
{/if}
<div class="article_aside_button">
<span class="btnSpan"><input type="button" name="update_del_flg_{$row.id}" value="{$row.del_flg|site_get_symbols_for_display}" id="btn_delFlg_{$row.id}" class="btn_delFlg"></span>
<span class="btnSpan space_left_5"><input type="button" name="delete_{$row.id}" value="削除" id="btn_delete_{$row.id}" class="btn_delete"></span>
<span class="btnSpan space_left">
<label for="input_sort_{$row.id}">並び順:</label>
<input type="text" name="input_sort_{$row.id}" value="{$row.sort}" id="input_sort_{$row.id}" class="InputMini input_sort" maxlength="6" placeholder="並び順"></span>
<span class="btnTop space_left_5"><a href="#top">▲</a></span>
<span class="btnTop space_left_5"><a href="{site_url uri=project}">{$smarty.const.UM_TOPPAGE_NAME}</a></span>
<span class="btnTop"><a href="{site_url}">サイト{$smarty.const.UM_TOPPAGE_NAME}</a></span>
</div>
</aside>
</article>

{/foreach}
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

<script type="text/javascript" src="{site_url}js/jquery.autopager.js"></script>
<script type="text/javascript" src="{site_url}js/jquery.lazyload.js"></script>
<script type="text/javascript" src="{site_url}js/jquery.slidescroll.js"></script>
<script type="text/javascript" charset="utf-8">
{literal}
$(function() {
	$.autopager({
		autoLoad: false
	});
	$('a[rel=next]').click(function() {
		$.autopager('load');
		return false;
	});
});

$(function(){
  $("a[href*='#']").slideScroll();
});
{/literal}
</script>

<!-- カレンダー対応 -->
<script src="{site_url}js/lib/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
<script src="{site_url}js/lib/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
{*<script src="{site_url}js/lib/gcalendar-holidays.js" type="text/javascript"></script>*}
<script type="text/javascript" charset="utf-8">
{literal}
$(function() {
	//テキストボックスにカレンダーをバインドする（パラメータは必要に応じて）
//	$("#due_date_27").datepicker({
	$(".input_date").datepicker({
		showButtonPanel: true,//「今日」「閉じる」ボタンを表示する
		firstDay: 1,//週の先頭を月曜日にする（デフォルトは日曜日）

		showOn: 'button',
		buttonImage: '{/literal}{site_url}{literal}/css/images/calendar.gif',
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
});
{/literal}
</script>

</html>
