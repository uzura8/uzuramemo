<h4 id="main_form_title" class="box_01">
<span class="space_right">{$page_title}</span>
{foreach from=$page_subtitle_parts item=row}
{if $row.url}
<span class="f_12 space_left_5">&raquo;&nbsp;<a href="{$row.url}">{$row.subtitle}</a></span>
{else}
<span class="f_12 space_left_5">&raquo;&nbsp;{$row.subtitle}</span>
{/if}
{/foreach}
<span class="f_11">
{form_dropdown name=select_order options=`$form_dropdown_list.order.options` selected=`$selected_select_order` extra='class="form_parts_right space_left" id="select_order"'}
</span>
<span class="link_right"><a id="new_form_switch" href="javaScript:void(0);">&raquo;&nbsp;{$page_name}の作成</a></span>
</h4>
