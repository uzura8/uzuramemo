<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>

<body>

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
<h2 class="box_01">
<div>{$row.name}{if $row.key_name}<span class="sub_info">( {$row.key_name} )</span>{/if}</div>
<div class="article_meta_top">
<div class="banner"></div>
<div class="meta_info">
<span>No.{$row.id}</span>
<span class="space_left_5">update: {$row.updated_at|date_format:"%Y/%m/%d %H:%M"}</span>
</div>
<div style="clear: both"></div>
</div>
</h2>
<article class="box_01">
<div class="article_box">
<p class="autogrow" id="{$row.id}" style="width: 300px">{$row.body|nl2br|auto_link}</p>
</div>

<aside class="article_footer">
{if $row.explanation}
<div class="quote_box">
<span class="title">引用元:</span><span class="space_left_5">{$row.explanation}</span>
</div>
{/if}
<div class="article_aside_button">
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

{*<div id="test_pager"><a href="javascript:void(0);" rel="next" onclick="load_next_list('{site_url uri=project/ajax_project_list}')">次のページ / Next</a></div>
{if $pagination.next_url}<nav id="next"><a href="{$pagination.next_url}" rel="next">もっと見る</a></nav>{/if}*}

{/if}
</div>
</body>
<script type="text/javascript" src="{site_url}js/jquery.lazyload.js"></script>
<script type="text/javascript" charset="utf-8">
{literal}
// <![CDATA[
$(document).ready(function() {
	$('h2').click(function() {
		$(this).next().slideToggle();
	});
	$("h2").next().hide('fast');
});
// ]]>
{/literal}
</script>

</html>
