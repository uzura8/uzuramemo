<h4 id="main_form_title" class="box_01">
{$page_title}
{if $page_subtitle}<span class="f_13 space_left_10">&raquo;&nbsp;{$page_subtitle}</span>{/if}
<span class="f_11">
{form_dropdown name=select_order options=`$form_dropdown_list.order.options` selected=`$selected_select_order` extra='class="form_parts_right space_left" id="select_order"'}
</span>
<span class="link_right"><a id="new_form_switch" href="javaScript:void(0);">&raquo;&nbsp;{$page_name}の作成</a></span>
</h4>
