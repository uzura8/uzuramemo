{include file='ci:mobile/header.tpl'}
{include file='ci:mobile/topmenu.tpl'}

<div id="mainbody">
{include file='ci:mobile/util/memo_topmenu.tpl'}

{if $breadcrumbs}<div id="list_top_box">{include file='ci:util/breadcrumb_list.tpl'}</div>{/if}

<h2 class="title">{$page_title}</h2>
{include file='ci:mobile/util/category_list_mobile.tpl'}

</div><!-- mainbody -->

{include file='ci:mobile/footer.tpl'}
