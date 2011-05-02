{include file='ci:admin/header.tpl'}
{include file='ci:admin/topmenu.tpl'}
{assign var="title" value=`$smarty.const.UM_WEBMEMO_NAME`}
{if $session_category.target_id}
{assign var="edit_button" value="変更"}
{assign var="sub_title" value="`$title`の変更"}
{else}
{assign var="edit_button" value="新規追加"}
{assign var="sub_title" value="`$title`の新規追加"}
{/if}

<div id="mainbody">

{form_open action=admin/webmemo/execute_edit_memo name=frm id=frm}
<p class="ttl_01">『{$title}』編集画面</p>

<div class="box_001">
<p class="ttl_02 td_w_break">{$sub_title}</p>
<div class="box_002">
{$sub_title}を行います。<br />
下記のフォームに内容を入力後、【{$edit_button}】ボタンをクリックしてください。<br />
<span class="f_ora f_10 f_bld">※</span>』は必須入力項目です。
</div>

<a name="frm_top"></a>
<table border="1" cellspacing="1" cellpadding="0" width="688" class="frm_form">
<tr>
	<th width="70" style="font-size:11px;">タイトル<span class="f_ora f_10">※</span></th>
	<td colspan="3"><input type="text" id="mn_title" name="mn_title" value="{$session_memo.title}" size="72" maxlength="100" />
	<span class="f_exp_s box_no_spc">※140文字以内</span></td>
</tr>
<tr>
	<th width="70" rowspan="2" style="font-size:11px;">カテゴリ<span class="f_ora f_10">※</span></th>
	<td rowspan="2">
{include file='ci:admin/util/form_select_category.tpl'}
		<span class="f_exp_s box_no_spc">&gt;&gt;&nbsp;<a href="{admin_url uri=webmemo/category}" target="_blank">カテゴリ管理</a></span>
	</td>
	<th width="60" style="font-size:11px;">重要度</th>
	<td style="width:100px;">
	<select name="important_level">
{section name=i start=1 loop=6}
<option value="{$smarty.section.i.index}"{if $session_memo.important_level == $smarty.section.i.index} checked="checked"{/if}>{"★"|str_repeat:$smarty.section.i.index}</option>
{/section}
	</select>
	</td>
</tr>
<tr>
	<th width="60" style="font-size:11px;">非公開</th>
	<td>
		<input type="radio" name="private_flg" value="1" style="vertical-align:middle;" id="pf_1"{if $session_memo.private_flg} checked="checked"{/if} /><label for="pf_1" class="f_red f_bld left_space_5">非公開</label><br />
		<input type="radio" name="private_flg" value="2" style="vertical-align:middle;" id="pf_2"{if !$session_memo.private_flg && $session_memo.quote_flg} checked="checked"{/if} /><label for="pf_2" class="f_bl">引用公開</label><br />
		<input type="radio" name="private_flg" value="0" style="vertical-align:middle;" id="pf_0"{if !$session_memo.private_flg && !$session_memo.quote_flg} checked="checked"{/if} /><label for="pf_0">公開</label>
	</td>
</td>
</tr>
{capture name="form_button_row"}
<tr>
<td colspan="4"><div class="box_005">
<input type="submit" name="edit" value="{$edit_button}" class="btn" />
<input type="submit" name="pre_sub" value="プレビュー" class="btn space_left" />
<input type="submit" name="reset_sub" value="リセット" class="btn space_left" />
</div></td>
</tr>
{/capture}
{$smarty.capture.form_button_row|smarty:nodefaults}
<tr>
	<td colspan="4" align="center" height="340">
	{$form_textarea_fckeditor|smarty:nodefaults}
	<input type="hidden" name="html_flg" value="1">
	</td>
</tr>
{$smarty.capture.form_button_row|smarty:nodefaults}
<tr>
	<th width="70">備考</th>
	<td colspan="3">
	<textarea name="mn_exp" id="textarea" cols="110" rows="3" wrap="off" style="font-size:0.8em;">{$session_memo.explain}</textarea>
	</td>
</tr>
<tr>
	<th width="70" style="font-size:11px;">キーワード</th>
	<td colspan="3"><input type="text" name="keyword" value="{$session_memo.keyword}" size="72" maxlength="100" id="keyword" />
	<span class="f_exp_s box_no_spc">※500文字以内</span></td>
</tr>
</table>
{if $session_category.target_id}
<div class="box_btn"><input type="submit" name="cancel" value="変更キャンセル" class="btn" /></div>
{/if}
</div>
<input type="hidden" name="ct" value="{$ct}">
<input type="hidden" name="order" value="{$order}">
<input type="hidden" name="search_word" value="{$view_search_word}">
<input type="hidden" name="from" value="{$from}">
{form_close}

<a name="lst_top"></a>
<div class="box_001">
<p class="ttl_02"><a href="{admin_url}/manual.php?result=1#lst_top">登録済み{$title}一覧</a></p>
{$sql_out}
</div>

<input type="hidden" name="from" value="{$in_disp_from}">
{include file='ci:admin/util/sub_footmenu.tpl'}
</div>
{include file='ci:admin/sidemenu.tpl'}
{include file='ci:admin/footer.tpl'}
