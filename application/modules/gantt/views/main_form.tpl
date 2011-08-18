<div class="form_box box_01" id="main_form_box">
{form_open action="`$current_module`/execute_insert" id=main_form}
{foreach from=$form key=key item=items}
{if $ignore_keys}{if $ignore_keys|strpos:$key !== false}{php}continue;{/php}{/if}{/if}
{if $items.disabled_for_insert}{php}continue;{/php}{/if}
<div class="form_row{if $items.type == 'hidden'} display_none{/if}">
{if $items.type == 'text' || $items.type == 'textarea' || $items.type == 'select'}
	<label for="{$key}">{$items.label}</label>
{/if}
	<div class="input">
{capture name="item_class"} class="{if $items.children || $items.width}{if $items.width}width_{$items.width}{elseif $items.children}normal{/if}{else}width_75{/if}"{/capture}
{if $items.type == 'text' || $items.type == 'textarea'}{capture name="form_help"}{$items.rules|form_help:$smarty.capture.help_default}{/capture}{/if}
{if $items.type == 'text'}
	<input type="text" name="{$key}" value="{if $items.value}{$items.value}{else}{$items.default_value}{/if}" id="{$key}"{$smarty.capture.item_class|smarty:nodefaults}{if $smarty.capture.form_help} placeholder="{$smarty.capture.form_help}"{/if}>{if $items.after_label}<span class="after_label">{$items.after_label}</span>{/if}
{if $items.realtime_validation}
	<span id="{$key}_loading"><img src="{site_url}img/loading.gif" alt="Ajax Indicator"></span>
	<span id="{$key}_result"></span>
{/if}
{elseif $items.type == 'textarea'}
	<textarea name="{$key}" id="{$key}"{if $items.cols} cols="{$items.cols}"{/if}{if $items.rows} rows="{$items.rows}"{/if}{$smarty.capture.item_class|smarty:nodefaults}{$smarty.capture.item_class|smarty:nodefaults}{if $smarty.capture.form_help} placeholder="{$smarty.capture.form_help}"{/if}>{if $items.value}{$items.value}{/if}</textarea>
{elseif $items.type == 'select'}
{form_dropdown name=`$key` options=`$items.options` selected='' extra=" id=\"$key\""}{if $items.after_label}<span class="after_label">{$items.after_label}</span>{/if}
{elseif $items.type == 'hidden'}
	<input type="hidden" name="{$key}" id="{$key}"{if $items.value} value="{$items.value}"{/if}>
{/if}
{if $smarty.capture.form_help && $items.rules_view == 'backword'}<span class="form_help">{$smarty.capture.form_help}</span>{/if}
{if $items.children}
{foreach from=$items.children item=child_key}
{assign var=ignore_keys value="`$ignore_keys`,`$child_key`"}
{if $form.$child_key.type == 'text' || $form.$child_key.type == 'textarea'}{capture name="form_help"}{$form.$child_key.rules|form_help}{/capture}{/if}
	<span class="sublabel" for="{$child_key}">{$form.$child_key.label}</span>
	<input type="text" name="{$child_key}" value="{if $form.$child_key.value}{$form.$child_key.value}{else}{$form.$child_key.default_value}{/if}" id="{$child_key}" class="narrow"{$smarty.capture.item_class|smarty:nodefaults}{if $smarty.capture.form_help} placeholder="{$smarty.capture.form_help}"{/if}>{if $form.$child_key.after_label}<span class="after_label">{$form.$child_key.after_label}</span>{/if}
{if $form.$child_key.realtime_validation}
	<span id="{$child_key}_loading"><img src="{site_url}img/loading.gif" alt="Ajax Indicator"></span>
	<span id="{$child_key}_result"></span>
{/if}
{if $smarty.capture.form_help && $items.rules_view == 'backword'}<span class="form_help">{$smarty.capture.form_help}</span>{/if}
{/foreach}
{/if}
	</div>
</div>
{/foreach}
{form_close}
</div><!-- main_form_box END -->
