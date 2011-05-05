{capture name="message"}{validation_errors}{if flashdata}{flashdata}{/if}{/capture}
{if $smarty.capture.message}
<div class="box_006 f_red">
{$smarty.capture.message|smarty:nodefaults}
</div>
{/if}
