<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>{$smarty.const.SITE_TITLE}</title>
<link href="{site_url}css/filter.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
{literal}
div.menu_box {
  display:none
}
{/literal}
-->
</style>

<script type="text/javascript" src="{site_url}js/lib/jquery.js"></script>

{*
<script type="text/javascript">
{literal}var j$ = jQuery.noConflict();{/literal}
</script>
<!-- search word heighlighter -->

<!-- auto_pager -->
<script src="{site_url}js/jquery.autopager2.js" type="text/javascript"></script>
<script type="text/javascript">
{literal}
j$(function() {
	j$.autopager();
});
{/literal}
$(document).ready(function(){
	$('h2').click(function() {
		$(this).next().slideToggle('fast');
	});
});
</script>
<script type="text/javascript" src="{site_url}js/jquery.slidescroll.js"></script>
<script type="text/javascript" src="{site_url}js/jquery.lazyload.js"></script>
*}

<script type="text/javascript">
{literal}
$(document).ready(function(){
	$('#lb_1').click(function() {
		$('#menu_box_1').slideToggle('fast');
		$('#menu_box_2').hide('fast');
		$('#menu_box_3').hide('fast');
	});
	$('#lb_2').click(function() {
		$('#menu_box_2').slideToggle('fast');
		$('#menu_box_1').hide('fast');
		$('#menu_box_3').hide('fast');
	});
	$('#lb_3').click(function() {
		$('#menu_box_3').slideToggle('fast');
		$('#menu_box_1').hide('fast');
		$('#menu_box_2').hide('fast');
	});
});
{/literal}
</script>

</head>

<body id="{get_current_page_id}">
<div id="container"><!--container START-->


<p id="lb_1" class="lb_01">
<a href="http://webmemo.localhost/category/10">Mac</a>
</p>
<div id="menu_box_1" class="menu_box">
<p class="lb_04">
<a href="http://webmemo.localhost/list/category/378">基本知識</a>
</p>
<p class="lb_04">
<a href="http://webmemo.localhost/list/category/379">設定・セットアップ</a>
</p>
<p class="lb_04">
<a href="http://webmemo.localhost/list/category/381">ターミナル関連</a>
</p>
</div>

<p id="lb_2" class="lb_01">
<a href="http://webmemo.localhost/category/10">PHP</a>
<span class="f_light">(php)</span></p>
<div id="menu_box_2" class="menu_box">
<p class="lb_04">
<a href="http://webmemo.localhost/list/category/7">関数</a>
<span class="f_light">(php_func)</span></p>
<p class="lb_04">
<a href="http://webmemo.localhost/list/category/127">処理・表現</a>
</p>
</div>

<p id="lb_3" class="lb_01">
<a href="http://webmemo.localhost/category/4">MySQL</a>
<span class="f_light">(mysql)</span></p>
<div id="menu_box_3" class="menu_box">
<p class="lb_04">
<a href="http://webmemo.localhost/list/category/5">環境・設定</a>
</p>
<p class="lb_04">
<a href="http://webmemo.localhost/list/category/9">SQL文</a>
</p>
<p class="lb_04">
<a href="http://webmemo.localhost/list/category/31">知識.ユーティリティ</a>
</p>
<p class="lb_04">
<a href="http://webmemo.localhost/list/category/102">エラーメッセージ</a>
</p>
</div>



<h1>CodeIgniter へようこそ!</h1>

<p>今ご覧のこのページは、CodeIgniter によって動的に生成されました。</p>

<p>このページを編集したい場合は、次の場所にあります:</p>
<code>application/views/welcome/index.tpl</code>

<p>このページのコントローラは次の場所にあります:</p>
<code>application/controllers/welcome.php</code>

<p>CodeIgniterを使うのが初めてなら、<a href="user_guide_ja/">ユーザガイド</a>を読むことから始めてください。</p>


</body>

</html>
