{foreach from=$breadcrumbs.list item=value name=breadcrumbs}
{if !$smarty.foreach.breadcrumbs.first}&nbsp;&gt;&nbsp;{/if}
{if $value.uri}
<a href="{$smarty.const.BASE_URL}{if $value.uri != '/'}{$value.uri}{/if}">{$value.name}</a>
{else}
{$value.name}
{/if}
{/foreach}
{if $breadcrumbs.count}
: {$breadcrumbs.count|number_format}件
{/if}
