<div id="sidebody">
{if $smarty.const.UM_USE_SEARCH_MEMO}{include file='ci:util/search_box.tpl'}{/if}
{if $cate_list}
{foreach from=$cate_list item=row}
{if !$row.is_private || ($smarty.const.IS_AUTH && $row.is_private)}
<p class="lb_01" onclick="toggleBox{$row.mc_id}()">
<a href="{$smarty.const.BASE_URL}category/{$row.mc_id}">{$row.mc_name}</a>
{if $smarty.const.IS_AUTH && $row.mc_key}<span style="font-weight:lighter;">({$row.mc_key})</span>{/if}
</p>
<div id="menu_box{$row.mc_id}">
{foreach from=$row.sc_ary item=sub_row}
{if $sub_row.mc_id == $now_category_id}
<p class="lb_04" style="background-color:#fcd5e7;">
<a href="{$smarty.const.BASE_URL}category/{$sub_row.mc_id}">{$sub_row.mc_name}</a>
{if $smarty.const.IS_AUTH && $sub_row.mc_key}<span style="font-weight:lighter;">({$sub_row.mc_key})</span>{/if}
</p>
{else}
<p class="lb_04">
<a href="{$smarty.const.BASE_URL}category/{$sub_row.mc_id}">{$sub_row.mc_name}</a>
{if $smarty.const.IS_AUTH && $sub_row.mc_key}<span style="font-weight:lighter;">({$sub_row.mc_key})</span>{/if}
</p>
{/if}
{/foreach}
</div>
{/if}
{/foreach}
{/if}

{if $smarty.const.USE_TASK_MANAGER && $smarty.const.IS_AUTH}
<p class="lb_01"><a href="{$smarty.const.BASE_URL}admin/task.php" target="_blank">{$smarty.const.TASK_MANAGER_SITE_NAME}</a></p>
{/if}
<p class="lb_01"><a href="http://torilife.net/" target="_blank">とりらいふ</a></p>
{if $smarty.const.IS_AUTH}
<p class="lb_01"><a href="{$smarty.const.BASE_URL}admin/manual.php" target="_blank">管理画面</a></p>
<p class="lb_01" onclick="toggleBoxSP001()"><a href="{$smarty.const.BASE_URL}list/?from=0&amp;order=3">ブックマーク記事</a></p>
<div id="menu_boxSP001">
{foreach from=$cate_list_important_articles item=item}
<p class="lb_04" style="font-weight:normal;"><a href="{$smarty.const.BASE_URL}article/{$item.mn_id}">{$item.mn_title}</a></p>
{/foreach}
</div>
{/if}
{if !$smarty.const.UM_USE_RSS_FEED}
<p><a href="{$smarty.const.BASE_URL}rss.xml" target="_blank"><img src="{$smarty.const.BASE_URL}img/xml2_icon.gif" alt="rss2.0" /></a></p>
{/if}
{if !$smarty.const.IS_AUTH}
{if $smarty.const.UM_USE_GOOGLE_ADSENSE}{include file='ci:util/google_adsense_sidemenu.tpl'}{/if}
{if $smarty.const.UM_USE_AFFILIATE_OTHER}{include file='ci:util/affiliate_other.tpl'}{/if}
{/if}
</div><!--sidebody-->
