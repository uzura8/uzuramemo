{include file='ci:admin/header.tpl'}
{include file='ci:admin/topmenu.tpl'}
{assign var="title" value="カテゴリ"}
{if $session}
{assign var="edit_button" value="変更"}
{assign var="sub_title" value="カテゴリの変更"}
{else}
{assign var="edit_button" value="新規追加"}
{assign var="sub_title" value="カテゴリの新規追加"}
{/if}

<div id="mainbody">
<p class="ttl_01">{$title} 編集画面</p>

{* form start *}
{form_open action=admin/execute_edit_webmemo_category}
<div class="box_006 f_red">
{validation_errors}
{if flashdata}{flashdata}{/if}
</div>
<a name="frm"></a>
<div class="box_001">
<p class="ttl_02">{$sub_title}</p>
<div class="box_002">
{$sub_title}を行います。<br />
下記のフォームに内容を入力後、【{$edit_button}】ボタンをクリックしてください。<br />
</div>

<table border="1" cellspacing="1" cellpadding="0" width="100%" class="frm_form">
<tr>
	<th width="80">カテゴリ名</td>
	<td>
	<input type="text" name="name" value="{$session_category.name}" size="30" maxlength="50" />
	<span class="f_bld space_left">key名</span>
	<input type="text" name="mc_key" value="{$session_category.key_name}" size="10" maxlength="20" />
	<span class="f_exp_s space_left">※100文字以内</span>
	</td>
</tr>
<tr>
	<th width="80">親カテゴリ</td>
	<td>
		<select name="mc_sub_id">
			<option value="0">親カテゴリ</option>
{foreach from=$select_category_list item=row}
			<option value="{$row.id}"{if $row.id == $session_category.sub_id} selected="selected"{/if}>{$row.name}</option>
{/foreach}
		</select>
		<input type="checkbox" name="is_private" value="1" id="cid_1" class="space_left_20"{if $session_category.is_private} checked="checked"{/if} /><label for="cid_1" class="space_left">非公開</label>
	</td>
</tr>
<tr>
	<th width="80">備考</td>
	<td>
	<textarea name="mc_exp" id="textarea" cols="44" rows="1" wrap="off">{$session_category.explain}</textarea><span class="f_exp_s space_left">※500文字以内</span>
	</td>
</tr>
<tr>
	<td colspan="3">
	<div class="box_005">
		<input type="submit" name="edit" value="{$edit_button}" class="btn" />
		<input type="submit" name="reset" value="リセット" class="btn space_left" />
	</div>
	</td>
</tr>
</table>
{if $session_category.fix_id}
<div class="box_btn"><input type="submit" name="cancel" value="変更キャンセル" class="btn" /></div>
{/if}
{form_close}
</div>
{* form end *}

<a name="lst_top"></a>
<div class="box_001">
<p class="ttl_02">登録済み{$title}一覧</p>
{if !$main_list}
<div class="box_004">{$title}の登録がありません。</div>
{else}
{form_open action=admin/execute_edit_webmemo_category_list}
{capture name="util_form_parts"}
<div class="box_btn">
<input type="button" value="閉じる" class="btn" onclick="window.close();" />
<select name="mc_parent_id" id="mc_parent_id" class="space_left" onchange="location.href=this.options[this.selectedIndex].value">
{foreach from=$main_list item=row}
<option value="{admin_url uri=webmemo/category#list_}{$row.id}">{$row.name}</option>
{/foreach}
</select>
</div>
{/capture}
{$smarty.capture.util_form_parts|smarty:nodefaults}
<div class="box_003">
	<table border="0" cellspacing="0" cellpadding="0" style="margin-top:11px; ">
	<tr>
	<td>リストの色分けについて</td>
	<td width="10">&nbsp;</td>
	<td width="50" align="center" class="del bd">非表示</td>
	<td width="10">&nbsp;</td>
	<td width="50" align="center" class="edit bd">編集中</td>
	</tr>
	</table>
</div>

<table border="1" cellspacing="1" cellpadding="0" class="frm_list" width="100%">
{capture name="main_table_title"}
<tr>
	<th>属性</th>
	<th><input type="submit" name="sakujo_sub" value="削除" class="btn_s" onclick="return confirm('削除しますか?');" /></th>
	<th>表示</th>
	<th>編集</th>
	<th>No</th>
	<th>カテゴリ名</th>
	<th>公開</th>
	<th>表示順<input type="submit" name="turn_sub" value="変更" class="btn_s" ></th>
</tr>
{/capture}
{$smarty.capture.main_table_title|smarty:nodefaults}

{foreach from=$main_list item=row}
<tr{if $session.fix_id == $row.id} bgcolor="#F7D9D9"{elseif $row.del_flg} bgcolor="#C0C0C0"{else} bgcolor="#E1F1FF"{/if}>
	<td width="30" align="center"><a name="list_{$row.id}"></a>{if !$row.sub_id}親{else}-{/if}</td>
	<td width="30" align="center">
		<a name="lst_{$row.id}"></a>
		<input type="checkbox" name="chk_{$row.id}" value="1">
	</td>
	<td width="30" align="center">
		<input type="submit" name="del_sub[{$row.id}]" value="{if $row.del_flg}×{else}◯{/if}" class="btn" />
	</td>
	<td width="30" align="center">
		<input type="submit" name="fix_sub[{$row.id}]" value="⇔" class="btn">
	</td>
	<td width="25" align="center">{$row.id}</td>
	<td class="td_w_break f_bld" onclick="toggleBox{$row.id}()">{$row.name}{if $row_sub.key_name}<span class="f_nm space_left_5">( {$row.key_name} ){/if}</span></td>
	<td width="50" class="f_10{if $row.is_private} f_bld f_red{/if}">{if $row.is_private}非公開{else}-{/if}</td>
	<td width="70" align="center"><input type="text" name="turn_{$row.id}" value="{$sort}" size="4" maxlength="3" /></td>
</tr>
<tr>
<td colspan="8">

<div id="menu_box{$row.id}">
{if $row.sc_ary}{* 子カテゴリ -start- *}
<table border="1" cellspacing="1" cellpadding="0" class="frm_list" width="100%">
{foreach from=$row.sc_ary item=row_sub}
<tr{if $session_category.fix_id == $row_sub.id} bgcolor="#F7D9D9"{elseif $row.del_flg || $row.del_flg} bgcolor="#C0C0C0"{else}{/if}>
	<td width="30" align="center">-</td>
	<td width="30" align="center">
		<input type="checkbox" name="chk_{$row_sub.id}" value="1" />
	</td>
	<td width="30" align="center">
		<input type="submit" name="del_sub[{$row_sub.id}]" value="{if $row_sub.del_flg}×{else}◯{/if}" class="btn" />
	</td>
	<td width="30" align="center">
		<input type="submit" name="fix_sub[{$row_sub.id}]" value="⇔" class="btn" />
	</td>
	<td width="25" align="center">{$row_sub.id}</td>
	<td class="td_w_break">{$row_sub.name}{if $row_sub.key_name}<span class="f_nm space_left_5">( {$row_sub.key_name} )</span>{/if}</td>
	<td width="50" class="f_10">{$row_sub.updated_at|date_format:"%Y/%m/%d %H:%M"}</td>
	<td width="50" class="f_10{if $row_sub.is_private} f_bld f_red{/if}">{if $row_sub.is_private}非公開{else}-{/if}</td>
	<td width="70" align="right"><input type="text" name="turn_{$row_sub.id}" value="{$row_sub.sort}" size="4" maxlength="3" /></td>
</tr>
{/foreach}
</table>
{/if}{* 子カテゴリ -end- *}
</div>

</td>
</tr>
<tr><td colspan="9" height="5"></td></tr>
{/foreach}
{$smarty.capture.main_table_title|smarty:nodefaults}
</table>

{$smarty.capture.util_form_parts|smarty:nodefaults}
{form_close}
{/if}
</div>
</div>
{include file='ci:admin/sidemenu.tpl'}
{include file='ci:admin/footer.tpl'}
