{capture name="message"}{validation_errors}{if flashdata}{flashdata}{/if}{/capture}
{if $smarty.capture.message}
<div id="message_box">
{$smarty.capture.message|smarty:nodefaults}
</div>
{/if}
