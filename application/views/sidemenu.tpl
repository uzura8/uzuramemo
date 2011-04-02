<div id="sidebody">
{if $smarty.const.UM_USE_SEARCH_MEMO}{include file='ci:util/search_box.tpl'}{/if}
{if $cate_list}
{section name=cate loop=$cate_list}
{if !$cate_list[cate].is_private || ($smarty.const.IS_AUTH && $cate_list[cate].is_private)}
<p class="lb_01" onclick="toggleBox{$cate_list[cate].mc_id}()">
<a href="{$top_url}/category/{$cate_list[cate].mc_id}">{$cate_list[cate].mc_name}</a>
{if $smarty.const.IS_AUTH && $cate_list[cate].mc_key}<span style="font-weight:lighter;">({$cate_list[cate].mc_key})</span>{/if}
</p>
<div id="menu_box{$cate_list[cate].mc_id}">
{section name=sc loop = $cate_list[cate].sc_ary}
{if $cate_list[cate].sc_ary[sc].mc_id == $side_chk_sub_cate}
<p class="lb_04" style="background-color:#fcd5e7;">
<a href="{$top_url}/category/{$cate_list[cate].sc_ary[sc].mc_id}">{$cate_list[cate].sc_ary[sc].mc_name}</a>
{if $smarty.const.IS_AUTH && $cate_list[cate].sc_ary[sc].mc_key}<span style="font-weight:lighter;">({$cate_list[cate].sc_ary[sc].mc_key})</span>{/if}
</p>
{else}
<p class="lb_04">
<a href="{$top_url}/category/{$cate_list[cate].sc_ary[sc].mc_id}">{$cate_list[cate].sc_ary[sc].mc_name}</a>
{if $smarty.const.IS_AUTH && $cate_list[cate].sc_ary[sc].mc_key}<span style="font-weight:lighter;">({$cate_list[cate].sc_ary[sc].mc_key})</span>{/if}
</p>
{/if}
{/section}
</div>
{/if}
{/section}
{/if}
{if $smarty.const.USE_TASK_MANAGER && $smarty.const.IS_AUTH}
<p class="lb_01"><a href="{$top_url}/admin/task.php" target="_blank">{$smarty.const.TASK_MANAGER_SITE_NAME}</a></p>
{/if}
<p class="lb_01"><a href="http://torilife.net/" target="_blank">とりらいふ</a></p>
{if $smarty.const.IS_AUTH}
<p class="lb_01"><a href="{$top_url}/admin/manual.php" target="_blank">管理画面</a></p>
<p class="lb_01" onclick="toggleBoxSP001()"><a href="{$top_url}/list/?from=0&order=3">ブックマーク記事</a></p>
<div id="menu_boxSP001">
{foreach from=$cate_list_important_articles item=item}
<p class="lb_04" style="font-weight:normal;"><a href="{$top_url}/article/{$item.mn_id}">{$item.mn_title}</a></p>
{/foreach}
</div>
{/if}
{if !$smarty.const.UM_USE_RSS_FEED}
<p><a href="{$top_url}/rss.xml" target="_blank"><img src="{$URL}/img/xml2_icon.gif"></a></p>
{/if}
{if !$smarty.const.IS_AUTH}
{if $smarty.const.UM_USE_GOOGLE_ADSENSE}{include file='ci:util/google_adsense_sidemenu.tpl'}{/if}
{if $smarty.const.UM_USE_AFFILIATE_OTHER}{include file='ci:util/affiliate_other.tpl'}{/if}
{/if}
</div><!--sidebody-->
