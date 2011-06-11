{include file='ci:mobile/header.tpl'}
{include file='ci:mobile/topmenu.tpl'}

<div id="mainbody">
{include file='ci:mobile/util/memo_topmenu.tpl'}

{if !$memo_list}
<div style="padding:30px 10px 30px 5px;">{if $search}「{$search}」に一致する{elseif $now_category_id}このカテゴリの{else}指定した記事の{/if}登録はありません。</div>
{if $search}
<div style="margin-bottom:20px;">
「<a href="http://www.google.co.jp/search?q={$search}" target="_blank" style="font-weight:bold;">{$search}</a>」をGoogle検索
<span style="margin-left:20px;"><a href="http://www.google.co.jp/search?q={$search}&as_qdr=m6" target="_blank">6ヶ月以内</a></span>
</div>
{/if}
{else}

{if $breadcrumbs}
<div id="list_top_box">
{include file='ci:util/breadcrumb_list.tpl'}
{if $pagination}
<span id="list_util_btn" class="btnTop"><a href="#">▼</a></span>
<div id="list_util"{if !$order} class="hide"{/if}>
<form id="order" name="order" action="">
<select name="order_sel" id="order_sel" onchange="selectjump(this)">
{foreach from=$order_list key=key item=values}
<option value="{$key}"{if $order == $key} selected="selected"{/if}>{$values.ja_name}</option>
{/foreach}
</select>
</form>
</div>
{/if}
</div>
{/if}

<!-- main_list -->
{foreach from=$memo_list item=row}
{assign var="is_quote" value=false}
{if $row.quote_flg && $row.explain && $row.explain|is_url}
{assign var="is_quote" value=true}
{/if}
<a name="id_{$row.id}"></a>
<div class="content">
<h2 class="box_01{if $row.private_flg} bg_red{elseif $smarty.const.IS_AUTH && $row.quote_flg} bg_bl{/if}">
<div>{$row.title}</div>
<div class="article_meta_top">
<div class="banner">
{if $is_quote}<span class="quote_symbol">【引用】</span>{/if}
</div>
<div class="meta_info">
<span>No.{$row.id}</span>
<span class="space_left_5">update: {$row.updated_at|date_format:"%Y/%m/%d %H:%M"}</span>
</div>
<div style="clear: both"></div>
</div>
</h2>
<article class="box_01">
<div class="article_box">
{if $article_id}
{if $is_quote}
{$row.body|strip_tags|trim|mb_strimwidth:0:$smarty.const.UM_QUOTE_TRIM_WIDTH:"..."|nl2br|smarty:nodefaults}</div>
{if $row.body|mb_strlen > $smarty.const.UM_QUOTE_TRIM_WIDTH}
<div class="iPhoneButton"><a href="{if $is_quote}{$row.explain}{else}{site_url uri=article}/{$row.id}{/if}" target="popup">続きを見る</a></div>
{/if}
{else}
{if $row.format == 2}{$row.body|textile|smarty:nodefaults}{else}{$row.body|smarty:nodefaults}{/if}
{/if}
{else}
{* list view -start- *}
{if $row.format == 2}
{$row.body|textile|trim|strip_tags|mb_strimwidth:0:$smarty.const.UM_QUOTE_TRIM_WIDTH:"..."|nl2br|smarty:nodefaults}
{else}
{$row.body|trim|strip_tags|mb_strimwidth:0:$smarty.const.UM_QUOTE_TRIM_WIDTH:"..."|nl2br|smarty:nodefaults}
{/if}
<div class="iPhoneButton"><a href="{if $is_quote}{$row.explain}{else}{site_url uri=article}/{$row.id}{/if}">続きを見る</a></div>
{* list view -end- *}
{/if}
</div>

<aside>
{if $row.explain}
<div class="quote_box">
<span class="title">引用元:</span><span class="space_left_5">{$row.explain|nl2br|auto_link:"popup":45}</span>
</div>
{/if}
<div class="article_aside_category">
<span class="title">カテゴリ:</span>
<span class="link_parts space_left_5"><a href="{site_url uri=category}/{$row.sub_id}">{$cate_name_list[$row.sub_id]}</a>
&nbsp;&gt;&nbsp;<a href="{site_url uri=category}/{$row.memo_category_id}">{$row.name}</a></span>
<span class="btnTop space_left_5"><a href="#top">▲</a></span>
<span class="btnTop"><a href="{site_url}">{$smarty.const.UM_TOPPAGE_NAME}</a></span>
</div>
</aside>
</article>
</div>
{/foreach}
{if $search}
<section>
「<a href="http://www.google.co.jp/search?q={$search}" target="_blank" style="font-weight:bold;">{$search}</a>」をGoogle検索
<span style="margin-left:20px;"><a href="http://www.google.co.jp/search?q={$search}&as_qdr=m6" target="_blank">6ヶ月以内</a></span>
</section>
{/if}
{if $pagination.next_url}<nav id="next"><a href="{$pagination.next_url}" rel="next">もっと見る</a></nav>{/if}
{/if}
</div>
{if !$smarty.const.IS_AUTH && $smarty.const.UM_USE_GOOGLE_ADSENSE}{include file='ci:mobile/util/google_adsense_mainbody.tpl'}{/if}

{include file='ci:mobile/footer.tpl'}
