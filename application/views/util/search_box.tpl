<div class="box_02">
<form method="post" action="">
<table border="0" cellspacing="0" cellpadding="0" class="search">
<tr>
<td><input type="text" name="search_word" value="{$view_search_word}" size="17" id="focus" /></td>
</tr>
<tr>
<td><input type="submit" name="submit" value="検索" class="btn" />
<input type="checkbox" name="opt" value="1"{if $opt==1} checked="checked"{/if} />カテゴリ</td>
</tr>
</table>
<input type="hidden" name="now_cate" value="{$now_cate}" />
<input type="hidden" name="now_id" value="{$now_id}" />
</form>
</div>
