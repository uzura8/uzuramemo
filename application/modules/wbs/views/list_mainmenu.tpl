<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>
<body id="{get_current_page_id}">
{if $list}
{foreach from=$list item=row}
	<h6 class="title">
		<a href="{site_url}activity/wbs/{$row.id}/?mode=1">{$row.name}</a>
	</h6>
{/foreach}
{/if}
</body>
</html>
