<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0">
	<channel>
		<title>{$smarty.const.SITE_TITLE}</title>
		<link>{site_url}</link>
		<description>{$site_description}</description>
		<language>ja</language>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<copyright>Copyright : {$smarty.const.COPYRIGHT_SINCE} - {$smarty.now|date_format:"%Y"} {$smarty.const.COPYRIGHT_URL}</copyright>
		<managingEditor>{$smarty.const.ADMIN_MAIL_ADDRESS}</managingEditor>
		<webMaster>{$smarty.const.ADMIN_MAIL_ADDRESS}</webMaster>
{foreach from=$memo_list item=row}
{assign var="is_quote" value=false}
{if $smarty.const.UM_USE_QUOTE_ARTICLE_VIEW && $row.quote_flg && $row.explain && $row.explain|is_url}
{assign var="is_quote" value=true}
{/if}
		<item>
			<title>{if $is_quote}【引用記事】{/if}{$row.title}</title>
			<link>{site_url uri=article/`$row.id`}</link>
			<description><![CDATA[
{if $is_quote}
{$row.body|strip_tags|mb_strimwidth:0:$smarty.const.UM_QUOTE_TRIM_WIDTH:"..."|nl2br|smarty:nodefaults}
{if $row.body|mb_strlen > $smarty.const.UM_QUOTE_TRIM_WIDTH}
<div>→&nbsp;<a href="{$row.explain}" target="_blank">続きを見る</a></div>
{/if}
<h4>引用元</h4>
<div>{$row.explain|nl2br|auto_link}</div>
{else}
{if $row.format == 2}{$row.body|textile|smarty:nodefaults}{else}{$row.body|smarty:nodefaults}{/if}
{/if}
]]></description>
			<pubDate>{$row.updated_at|date_format:"%Y/%m/%d %H:%M"}</pubDate>
			<guid>{site_url uri=article/`$row.id`}</guid>
		</item>
{/foreach}
    </channel>
</rss>
