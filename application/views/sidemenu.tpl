<div id="sidebody">
{if $smarty.const.UM_USE_SEARCH_MEMO}{include file='ci:util/search_box.tpl'}{/if}
{if $cate_list}
<div id="acc_menu">
{foreach from=$cate_list item=row}
{if ((!$row.is_private || $smarty.const.IS_AUTH && $row.is_private) && $row.sc_ary)}
<p class="lb_01{if $row.id == $now_category_id} bg_red{/if}">
<a href="{site_url uri=category}/{$row.id}">{$row.name}</a>
{if $smarty.const.IS_AUTH && $row.key_name}<span class="f_light">({$row.key_name})</span>{/if}
</p>
<div id="menu_box{$row.id}" class="menu_box">
{foreach from=$row.sc_ary item=sub_row}
{if $sub_row.id == $now_category_id}
<p class="lb_04 bg_red">
<a href="{site_url uri=category}/{$sub_row.id}">{$sub_row.name}</a>
{if $smarty.const.IS_AUTH && $sub_row.key_name}<span class="f_light">({$sub_row.key_name})</span>{/if}
</p>
{else}
<p class="lb_04">
<a href="{site_url uri=list/category}/{$sub_row.id}">{$sub_row.name}</a>
{if $smarty.const.IS_AUTH && $sub_row.key_name}<span class="f_light">({$sub_row.key_name})</span>{/if}
</p>
{/if}
{/foreach}
</div>
{/if}
{/foreach}
</div>
{/if}
<p class="lb_01"><a href="{site_url uri=sitemap}">サイトマップ</a></p>
{if $smarty.const.DEV_MODE}
<p class="lb_01"><a href="{site_url uri=user_guide_ja}" target="user_guide" onClick="window.open(this.href,this.target).focus();return false;">開発者向けマニュアル</a></p>
{/if}
{if $smarty.const.IS_AUTH}
<p class="lb_01"><a href="{site_url uri=admin}" target="admin" onClick="window.open(this.href,this.target).focus();return false;">管理画面</a></p>
{if $cate_list_important_articles}
<p class="lb_01"><a href="{site_url uri=list}?from=0&amp;order=2">ブックマーク記事</a></p>
<div id="menu_boxSP001">
{foreach from=$cate_list_important_articles item=item}
<p class="lb_04" style="font-weight:normal;"><a href="{site_url uri=article}/{$item.id}">{$item.title}</a></p>
{/foreach}
</div>
{/if}
{/if}
{if $smarty.const.UM_USE_RSS_FEED}
<div class="rss_btn"><a href="{site_url uri=feed}" target="_blank">{img src=img/rss02.gif alt=rss2.0}</a></div>
{/if}
{if !$smarty.const.IS_AUTH}
{if $smarty.const.UM_USE_GOOGLE_ADSENSE}{include file='ci:util/google_adsense_sidemenu.tpl'}{/if}
{if $smarty.const.UM_USE_AFFILIATE_OTHER}{include file='ci:util/affiliate_other.tpl'}{/if}
{/if}
</div><!--sidebody-->
