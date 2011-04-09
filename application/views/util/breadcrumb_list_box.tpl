<p id="pan">
{foreach from=$breadcrumbs key=uri item=value name=breadcrumbs}
{if !$smarty.foreach.breadcrumbs.first}&nbsp;&nbsp;&gt;&nbsp;&nbsp;{/if}
{if $uri}
<a href="{$smarty.const.BASE_URL}{$uri}">{$value}</a>
{else}
{$value}
{/if}
{/foreach}
</p>
