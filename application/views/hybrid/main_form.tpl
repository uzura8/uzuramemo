<div class="form_box box_01" id="main_form_box">
{if $main_form_title}<h4>{$main_form_title}</h4>{/if}
{if $form_id_suffix}{assign var=form_id value="main_form_`$form_id_suffix`"}{else}{assign var=form_id value="main_form"}{/if}
{if $form_action}{assign var=form_action_uri value=`$form_action`}{else}{assign var=form_action_uri value="`$current_module`/execute_insert"}{/if}
{form_open action=`$form_action_uri` id=`$form_id`}
{foreach from=$form key=key item=items}
{if $ignore_keys}{if $ignore_keys|strpos:$key !== false}{php}continue;{/php}{/if}{/if}
{if $items.disabled_for_insert}{php}continue;{/php}{/if}
{if $items.id_name}{assign var=id_name value=`$items.id_name`}{else}{assign var=id_name value=`$key`}{/if}
<div class="form_row{if $items.type == 'hidden'} display_none{/if}">
{if $items.type == 'text' || $items.type == 'textarea' || $items.type == 'select'}
	<label for="{$key}">{$items.label}</label>
{/if}
	<div class="input">
{capture name="item_class"} class="{if $items.children || $items.width}{if $items.width}width_{$items.width}{elseif $items.children}normal{/if}{else}width_75{/if}"{/capture}
{if $items.type == 'text' || $items.type == 'textarea'}{capture name="form_help"}{$items.rules|form_help:$smarty.capture.help_default}{/capture}{/if}
{if $items.type == 'text'}
	<input type="text" name="{$key}"{if $items.value} value="{$items.value}"{/if} id="{$id_name}"{$smarty.capture.item_class|smarty:nodefaults}{if $smarty.capture.form_help} placeholder="{$smarty.capture.form_help}"{/if}>{if $items.after_label}<span class="after_label">{$items.after_label}</span>{/if}
{if $items.realtime_validation}
	<span id="{$key}_loading"><img src="{site_url}img/loading.gif" alt="Ajax Indicator"></span>
	<span id="{$key}_result"></span>
{/if}
{elseif $items.type == 'textarea'}
	<textarea name="{$key}" id="{$id_name}"{if $items.cols} cols="{$items.cols}"{/if}{if $items.rows} rows="{$items.rows}"{/if}{$smarty.capture.item_class|smarty:nodefaults}{$smarty.capture.item_class|smarty:nodefaults}{if $smarty.capture.form_help} placeholder="{$smarty.capture.form_help}"{/if}>{if $items.value}{$items.value}{/if}</textarea>
{elseif $items.type == 'select'}
{form_dropdown name=`$key` options=`$items.options` selected='' extra=" id=\"$id_name\""}{if $items.after_label}<span class="after_label">{$items.after_label}</span>{/if}
{elseif $items.type == 'hidden'}
	<input type="hidden" name="{$key}" id="{$id_name}"{if $items.value} value="{$items.value}"{/if}>
{/if}
{if $smarty.capture.form_help && $items.rules_view == 'backword'}<span class="form_help">{$smarty.capture.form_help}</span>{/if}
{if $items.children}
{foreach from=$items.children item=child_key}
{assign var=ignore_keys value="`$ignore_keys`,`$child_key`"}
{if $form.$child_key.type == 'text' || $form.$child_key.type == 'textarea'}{capture name="form_help"}{$form.$child_key.rules|form_help}{/capture}{/if}
	<span class="sublabel" for="{$child_key}">{$form.$child_key.label}</span>
	<input type="text" name="{$child_key}"{if $form.$child_key.value} value="{$form.$child_key.value}"{/if} id="{if $form.$child_key.id_name}{$form.$child_key.id_name}{else}{$child_key}{/if}" class="narrow"{$smarty.capture.item_class|smarty:nodefaults}{if $smarty.capture.form_help} placeholder="{$smarty.capture.form_help}"{/if}>{if $form.$child_key.after_label}<span class="after_label">{$form.$child_key.after_label}</span>{/if}
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
<button id="{$form_id}_submit" class="iPhoneButton">送信</button>
{form_close}
{if $is_modal}<div class="btnBox"><span class="btnTop"><a href="#" rel="close">close</a></span></div>{/if}
</div><!-- main_form_box END -->
