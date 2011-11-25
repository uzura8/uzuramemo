{include file='ci:admin/header.tpl'}
{include file='ci:admin/topmenu.tpl'}
{assign var="title" value=`$smarty.const.UM_WEBMEMO_NAME`}
{if $session.target_id}
{assign var="edit_button" value="変更"}
{assign var="sub_title" value="`$title`の変更"}
{else}
{assign var="edit_button" value="新規追加"}
{assign var="sub_title" value="`$title`の新規追加"}
{/if}

<div id="mainbody">

{form_open action=admin/webmemo/execute_edit_memo name=frm id=frm}
<p class="ttl_01">{$title} 編集画面</p>

<div class="box_001">
<p class="ttl_02 td_w_break">{$sub_title}</p>
{if !$smarty.const.UM_LOCAL_MODE}
<div class="box_002">
{$sub_title}を行います。<br />
下記のフォームに内容を入力後、【{$edit_button}】ボタンをクリックしてください。
</div>
{/if}
{include file='ci:admin/util/message_box.tpl'}

<a name="frm_top"></a>
<table border="1" cellspacing="1" cellpadding="0" width="688" class="frm_form">
<tr>
	<th width="70" class="f_11">{$form.title.label}</th>
	<td colspan="3"><input type="text" id="title" name="title" value="{$session.title}" size="72" maxlength="100" />
	<span class="f_exp_s box_no_spc">※140文字以内</span></td>
</tr>
<tr>
	<th width="70" rowspan="3" class="f_11">{$form.memo_category_id.label}</th>
	<td rowspan="3">
{include file='ci:admin/util/form_select_category.tpl'}
		<span class="f_exp_s box_no_spc">&gt;&gt;&nbsp;<a href="{admin_url uri=webmemo/category}" target="memo_category" onClick="window.open(this.href,this.target).focus();return false;">カテゴリ管理</a></span>
	</td>
	<th width="60" class="f_11">{$form.important_level.label}</th>
	<td style="width:100px;">
	<select name="important_level">
{section name=i start=1 loop=6}
<option value="{$smarty.section.i.index}"{if $session.important_level == $smarty.section.i.index} selected="selected"{/if}>{"★"|str_repeat:$smarty.section.i.index}</option>
{/section}
	</select>
	</td>
</tr>
<tr>
	<th width="60" class="f_11">{$form.private_quote_flg.label}</th>
	<td>
		<ul class="simple_list">
			<li><input type="radio" name="private_quote_flg" value="0" id="pf_0"{if !$session.private_quote_flg} checked="checked"{/if} /><label for="pf_0" class="space_left_5{0|site_output_views4private_quote_flg:"style":true}">{0|site_output_views4private_quote_flg}</label></li>
{if $smarty.const.UM_USE_QUOTE_ARTICLE_VIEW}
			<li><input type="radio" name="private_quote_flg" value="1" id="pf_1"{if $session.private_quote_flg == 1} checked="checked"{/if} /><label for="pf_1" class="space_left_5{1|site_output_views4private_quote_flg:"style":true}">{1|site_output_views4private_quote_flg}</label></li>
{/if}
			<li><input type="radio" name="private_quote_flg" value="2" id="pf_2"{if $session.private_quote_flg == 2} checked="checked"{/if} /><label class="space_left_5{2|site_output_views4private_quote_flg:"style":true}" for="pf_2">{2|site_output_views4private_quote_flg}</label></li>
		</ul>
	</td>
</td>
</tr>
<tr>
	<th width="60" class="f_7">{$form.format.label}</th>
	<td>
		<ul class="simple_list">
			<li><input type="checkbox" name="format" value="2" id="format_2"{if $session.format == 2} checked="checked"{/if} /><label for="format_2" class="space_left_5">textile</label></li>
		</ul>
	</td>
</td>
</tr>
{capture name="form_button_row"}
<tr>
<td colspan="4"><div class="box_005">
<input type="submit" name="edit" value="{$edit_button}" class="btn" />
<input type="submit" name="preview" value="プレビュー" class="btn space_left" />
<input type="submit" name="reset" value="リセット" class="btn space_left" />
</div></td>
</tr>
{/capture}
{$smarty.capture.form_button_row|smarty:nodefaults}
<tr>
	<td colspan="4" align="center" height="340">
{if $session.format == 2}
	<textarea name="body" id="textarea" cols="108" rows="25" wrap="off" style="font-size:0.9em;">{$session.body}</textarea>
{else}
	{$form_textarea_fckeditor|smarty:nodefaults}
{/if}
	</td>
</tr>
{$smarty.capture.form_button_row|smarty:nodefaults}
<tr>
	<th width="70">{$form.explain.label}</th>
	<td colspan="3">
	<textarea name="explain" id="textarea" cols="95" rows="3" wrap="off" style="font-size:0.8em;">{$session.explain}</textarea>
	</td>
</tr>
<tr>
	<th width="70" class="f_11">{$form.keyword.label}</th>
	<td colspan="3"><input type="text" name="keyword" value="{$session.keyword}" size="72" maxlength="100" id="keyword" />
	<span class="f_exp_s box_no_spc">※500文字以内</span></td>
</tr>
</table>
{if $session.target_id}
<div class="box_btn"><input type="submit" name="cancel" value="変更キャンセル" class="btn" /></div>
{/if}
{if $session.body}
<div class="box_preview">
<p class="ttl_012">プレビュー</p>
{if $session.format == 2}{$session.body|textile|smarty:nodefaults}{else}{$session.body|smarty:nodefaults}{/if}
</div>
{/if}
</div>
<input type="hidden" name="ct" value="{$ct}">
<input type="hidden" name="order" value="{$order}">
<input type="hidden" name="search_word" value="{$view_search_word}">
<input type="hidden" name="from" value="{$from}">
{form_close}

<a name="lst_top"></a>
<div class="box_001">
<p class="ttl_02"><a href="{admin_url uri="webmemo?result=1#lst_top"}">登録済み{$title}一覧</a></p>
{if !$main_list}
<div class="box_004">{$title}の登録がありません。</div>
{else}
<!--
{form_open action=admin/webmemo}
<div class="box_00" style="text-align: right;">
<table  cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>カテゴリ</td>
	<td>
		<select NAME="ct" style="width: 15em; margin:0 10px 0 10px;">
{$mc_sel_list}
		</select>
	</td>
	<td>検索</td>
	<td><input type="text" name="search_word" value="{$view_search_word}" style="width: 8em; margin:0 10px 0 10px;" ></td>
	<td>表示順</td>
	<td>
		<select NAME="order" style="width: 9em; margin:0 10px 0 10px;">
		<option value="0"{$sel_flg0}>更新日順</option>
		<option value="1"{$sel_flg1}>表示No順</option>
		<option value="2"{$sel_flg2}>登録順</option>
		<option value="3"{$sel_flg3}>カテゴリ順</option>
		</select>
	</td>
	<td>
		<input type="submit" name="order_sub" value="実行" class="btn f_11" />
		<input type="submit" name="cansel_sub" value="リセット" class="btn f_11" />
	</td>
</tr>
</table>
</div>
<input type="hidden" name="from" value="{$in_disp_from}">
{form_close}
{$paging_info}
-->
{include file='ci:admin/util/main_list_explain_box.tpl'}

{foreach from=$main_list item=row}
{form_open action=admin/webmemo/execute_edit_memo_list}
<a name="lst_{$row.id}"></a>
<table width="688" border="1" cellspacing="1" class="frm_list"{if $row.del_flg} bgcolor="#c0c0c0"{/if} style="margin-bottom:10px;">
<tr>
	<th style="width:60px;">No</th>
	<td style="width:30px;">{$row.id}</td>
	<th style="width:50px;">タイトル</th>
	<td class="td_w_break">{$row.title}</td>
	<th style="width:50px;">カテゴリ</th>
<!--
	<td class="td_w_break" style="width:120px;">{$row.memo_category_id}</td>
-->
	<td class="td_w_break" style="width:120px;">
		<span class="link_parts"><a href="{site_url uri=category}/{$row.sub_id}" target="webmemo" onClick="window.open(this.href,this.target).focus();return false;">{$cate_name_list[$row.sub_id]}</a>
		&nbsp;&gt;&nbsp;<a href="{site_url uri=category}/{$row.memo_category_id}" target="webmemo" onClick="window.open(this.href,this.target).focus();return false;">{$row.name}</a></span>
	</td>
</tr>
<tr>
	<th>内容</th>
	<td class="td_w_break" colspan="5" style="padding:5px;">{if $row.format == 2}{$row.body|textile|smarty:nodefaults}{else}{$row.body|smarty:nodefaults}{/if}</td>
</tr>
<tr>
<th>引用元</th>
	<td class="td_w_break" colspan="5" style="padding:5px;">{$row.explain|nl2br|auto_link}</td>
</tr>
<tr>
	<th style="width:60px;">重要度</th>
	<td style="width:60px;">{"★"|str_repeat:$row.important_level}</td>
	<th style="width:60px;">キーワード</th>
	<td class="td_w_break">{$row.keyword}</td>
	<th style="width:50px;">公開</th>
	<td class="td_w_break" style="width:90px;"><input type="submit" name="change_private_quote_flg[{$row.id}]" value="{$row.private_flg|site_output_private_quote_flg_views:$row.quote_flg}" class="btn f_10{$row.private_flg|site_output_private_quote_flg_views:$row.quote_flg:"style":true}"{if $row.private_flg} onclick="return confirm('ID:{$row.id}の記事を公開しますか?');"{/if} /></td>
</tr>
<tr>
	<td colspan="10" style="padding:0;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl_inr f_10"{$tr_css} style="border-width:0;">
		<tr>
			<th>修正：</th>
			<td><input type="submit" name="choose[{$row.id}]" value="{site_get_symbol key="edit"}" class="btn f_10"></td>
			<th>表示/非表示：</th>
			<td><input type="submit" name="change_display[{$row.id}]" value="{$row.del_flg|site_get_symbols_for_display}" class="btn f_10"></td>
			<th>表示順：</th>
			<td>
				<input type="text" name="sort_{$row.id}" value="{$row.sort}" size="3" maxlength="8" />
				<input type="submit" name="change_sort[{$row.id}]" value="変更" class="btn f_10" />
			<th>削除：</th>
			<td><input type="submit" name="delete[{$row.id}]" value="削除" class="btn f_10" onclick="return confirm('ID:{$row.id}を削除しますか?');"></td>
			<th>投稿日：</th>
			<td>{$row.created_at|date_format:"%Y/%m/%d %H:%M"}</td>
			<th>更新日：</th>
			<td>{$row.updated_at|date_format:"%Y/%m/%d %H:%M"}</td>
		</tr>
		</table>
</td>
</tr>
</table>

{/foreach}
<input type="hidden" name="ct" value="{$ct}">
<input type="hidden" name="order" value="{$order}">
<input type="hidden" name="search_word" value="{$view_search_word}">
<input type="hidden" name="from" value="{$from}">
<input type="hidden" name="bfr_{$id}" value="{$bfr_id}">
{form_close}
{/if}
</div>

{include file='ci:admin/util/sub_footmenu.tpl'}
</div>
{include file='ci:admin/sidemenu.tpl'}
{include file='ci:admin/footer.tpl'}
