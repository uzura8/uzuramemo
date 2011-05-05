{if $session.memo_category_id}
<select name="memo_category_id" size="10" style="width:20em;">
{if !$select_category_list}
<option value="">登録がありません</option>
{else}
<option value="">選択してください</option>
{foreach from=$select_category_list item=row_parent}
{foreach from=$row_parent.sc_ary item=row}
<option value="{$row.id}"{if $row.id == $session.memo_category_id} selected="selected"{/if}>({$row_parent.name})-{$row.name}</option>
{/foreach}
{/foreach}
{/if}
</select>
{else}
<script type="text/javascript">
<!--
var menuItem = {literal}{{/literal}
	0: [
		{literal}{{/literal}"code": 0, "name": "------"{literal}}{/literal},
	],
{foreach from=$select_category_list item=row_parent}
	{$row_parent.id}: [
{foreach from=$row_parent.sc_ary item=row}
		{literal}{{/literal}"code": {$row.id}, "name": "{$row.name}"{literal}}{/literal},
{/foreach}
	],
{/foreach}
{literal}}{/literal};
addEvent(window,'load',init,false);
{literal}
function init()
{
  addEvent($("frm").memo_category_id_parent, 'change', setMenuItem, false);
  setMenuItem();
}

function setMenuItem()
{
  //初期化
  var option_cnt = $("frm").memo_category_id.options.length;
  for(var i=0; i<option_cnt; i++)
  {
    $("frm").memo_category_id.remove(0);
  }

  var n = $F("memo_category_id_parent");

  for(var i=0; i<menuItem[n].length; i++)
  {
    $("frm").memo_category_id.options[i] = new Option(menuItem[n][i]["name"],menuItem[n][i]["code"]);
  }
}

function next_forcus(){
  if (document.getElementById){
    document.getElementById("memo_category_id_parent").blur();
    document.getElementById("memo_category_id").focus();
  }
}
{/literal}
-->
</script>
<select name="memo_category_id_parent" size="10" id="memo_category_id_parent" onchange="next_forcus()" style="width:10em;">
{foreach from=$select_category_list item=row_parent}
<option value="{$row_parent.id}">{$row_parent.name}</option>
{/foreach}
</select>
<select name="memo_category_id" id="memo_category_id" size="10" style="width:10em;">
</select>
{/if}
