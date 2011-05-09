{include file='ci:header.tpl'}
{include file='ci:topmenu.tpl'}
{if !$smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}

<div id="mainbody">
{if $breadcrumbs}{include file='ci:util/breadcrumb_list_box.tpl'}{/if}

{if !$article_id && $memo_list}
<div class="box_02">
<h3 style="margin:0;">{$child_cate_name}</h3>
<ul id="summary_list">
{foreach from=$memo_list item=row}
<li><a href="#id_{$row.id}">{$row.title|trim}</a></li>
{/foreach}
</ul>
</div>
{/if}

<div id="cate_list">
{if !$memo_list}
<div style="padding:30px 10px 30px 5px;">{if $search}「{$search}」に一致する{elseif $now_category_id}このカテゴリの{else}指定した記事の{/if}登録はありません。</div>
{if $search}
<div style="margin-bottom:20px;">
「<a href="http://www.google.co.jp/search?q={$search}" target="_blank" style="font-weight:bold;">{$search}</a>」をGoogle検索
<span style="margin-left:20px;"><a href="http://www.google.co.jp/search?q={$search}&as_qdr=m6" target="_blank">6ヶ月以内</a></span>
</div>
{/if}
{else}
{if $pagination}
{capture name="pager_parts"}
<!--paging str-->
<div id="pager_box" class="box_nbr">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="115" class="form_td">
<form id="order" name="order" action="">
<select name="order_sel" id="order_sel" onchange="selectjump(this)">
{foreach from=$order_list key=key item=values}
<option value="{$key}"{if $order == $key} selected="selected"{/if}>{$values.ja_name}</option>
{/foreach}
</select>
</form>
</td>
<td width="55" class="count">{$count_all}件中</td>
<td width="120" class="count">{$pagination.cur_page_first_num} 〜 {$pagination.cur_page_last_num} 表示</td>
<td width="38">{$pagination.first_link|smarty:nodefaults}</td>
<td width="64">{$pagination.prev_link|smarty:nodefaults}</td>
<td>{$pagination.pages_link|smarty:nodefaults}</td>
<td width="64">{$pagination.next_link|smarty:nodefaults}</td>
<td width="38">{$pagination.last_link|smarty:nodefaults}</td>
</tr>
</table>
</div>
<!--paging end-->
{/capture}
{$smarty.capture.pager_parts|smarty:nodefaults}
{/if}
<!-- main_list -->
{foreach from=$memo_list item=row}
{assign var="is_quote" value=false}
{if !$smarty.const.IS_AUTH && UM_USE_QUOTE_ARTICLE_VIEW && $row.quote_flg && $row.explain && $row.explain|is_url}
{assign var="is_quote" value=true}
{/if}
<a name="id_{$row.id}"></a>
<div class="content">
<h2 class="main_h2{if $row.private_flg} bg_red{elseif $smarty.const.IS_AUTH && $row.quote_flg} bg_bl{/if}"><span style="font-size:small; font-weight:normal;">No.{$row.id}</span>{if $is_quote}<span class="quote_symbol">【引用】</span>{else}&nbsp;{/if}<a href="{if $is_quote}{$row.explain}{else}{site_url uri=article}/{$row.id}{/if}">{$row.title}</a></h2>
<div class="box_01">
<!--<h3 class="main_h3">内容</h3>-->
{if $is_quote}
<div id="article_box">{$row.body|strip_tags|mb_strimwidth:0:$smarty.const.UM_QUOTE_TRIM_WIDTH:"..."|nl2br|smarty:nodefaults}</div>
{if $row.body|mb_strlen > $smarty.const.UM_QUOTE_TRIM_WIDTH}
<div class="f_bld">→&nbsp;<a href="{$row.explain}" target="_blank">続きを見る</a></div>
{/if}
{else}
<div id="article_box">{$row.body|smarty:nodefaults}</div>
{/if}
{if $row.explain}
<h3 class="main_h3">引用元</h3>
<div class="quote_box">{$row.explain|nl2br|auto_link}</div>
{/if}
<div id="article_footer">
{if $smarty.const.IS_AUTH}
{form_open action=admin/webmemo/execute_edit_memo_list class=memo_footer_form}
<input type="submit" name="choose[{$row.id}]" value=" edit " class="btn_small" />
<input type="submit" name="change_private_quote_flg[{$row.id}]" value="{$row.private_flg|site_output_private_quote_flg_views:$row.quote_flg}" class="btn_small{$row.private_flg|site_output_private_quote_flg_views:$row.quote_flg:"style":true}"{if $row.private_flg} onclick="return confirm('ID:{$row.id}の記事を公開しますか?');"{/if} />
<input type="hidden" name="redirect_to" value="{$list_url}#id_{$row.id}" />
{form_close}
{/if}
<span>更新：{$row.updated_at|date_format:"%Y/%m/%d %H:%M"}</span>
<span class="link_parts">カテゴリ：&nbsp;<a href="{site_url uri=category}/{$row.sub_id}">{$cate_name_list[$row.sub_id]}</a>
&nbsp;&gt;&nbsp;<a href="{site_url uri=category}/{$row.memo_category_id}">{$row.name}</a></span>
<span class="link_parts"><a href="#">▲{$smarty.const.UM_TOPPAGE_NAME}</a></span>
</div>
</div>
</div>
{/foreach}
{if $search}
<div style="margin-bottom:20px;">
「<a href="http://www.google.co.jp/search?q={$search}" target="_blank" style="font-weight:bold;">{$search}</a>」をGoogle検索
<span style="margin-left:20px;"><a href="http://www.google.co.jp/search?q={$search}&as_qdr=m6" target="_blank">6ヶ月以内</a></span>
</div>
{/if}
{if $smarty.capture.pager_parts|smarty:nodefaults}{$smarty.capture.pager_parts|smarty:nodefaults}{/if}
{if $next_url}
<p><a href="{$next_url}" rel="next">次のページ / Next</a></p>
<hr />
{/if}
{/if}
{if !$smarty.const.IS_AUTH && $smarty.const.UM_USE_GOOGLE_ADSENSE}{include file='ci:util/google_adsense_mainbody.tpl'}{/if}
</div>
</div><!-- mainbody -->

{if $smarty.const.UM_SIDEMENU_VIEW_MODE}{include file='ci:sidemenu.tpl'}{/if}
{include file='ci:footer.tpl'}
