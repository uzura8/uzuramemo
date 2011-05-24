{include file='ci:mobile/header.tpl'}
{include file='ci:mobile/topmenu.tpl'}

<div id="mainbody">
{include file='ci:mobile/util/memo_topmenu.tpl'}

{if $breadcrumbs}<div id="list_top_box">{include file='ci:util/breadcrumb_list.tpl'}</div>{/if}

{if !$main_list}
<div style="padding:0 10px 0 5px;">{$smarty.const.UM_WEBMEMO_NAME}カテゴリの登録がありません。</div>
{else}
{foreach from=$main_list item=parent_category}
<h2 class="title"><a href="{site_url uri=list/category}/{$parent_category.id}">{$parent_category.name}</a></h2>
{foreach from=$parent_category.sc_ary item=category}
<h4 class="box_01"><a href="{site_url uri=list/category}/{$category.id}">{$category.name}</a></h4>
<ul class="box_01">
{foreach from=$category.each_ary item=article}
<li><a href="{site_url uri=article}/{$article.id}">{$article.title}</a></li>
{/foreach}
</ul>
{/foreach}
{/foreach}
{/if}

</div><!-- mainbody -->
{include file='ci:mobile/footer.tpl'}
