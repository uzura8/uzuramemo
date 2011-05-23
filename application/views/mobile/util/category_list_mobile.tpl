{if $cate_list}
{foreach from=$cate_list item=parent_category}
{if ((!$parent_category.is_private || $smarty.const.IS_AUTH && $parent_category.is_private) && $parent_category.sc_ary)}
<h4 class="box_01"><a href="{site_url uri=list/category}/{$parent_category.id}">{$parent_category.name}</a></h4>
<ul class="box_01">
{foreach from=$parent_category.sc_ary item=category}
<li><a href="{site_url uri=list/category}/{$category.id}">{$category.name}</a></li>
{/foreach}
</ul>
{/if}
{/foreach}
{/if}
