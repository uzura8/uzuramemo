	<br class="clearfloat">
	<div id="foot_menu">
		<ul>
			<li><a href="{$smarty.const.BASE_URL}">トップページ</a></li>
			<li><a href="http://torilife.net/" target="_blank">とりらいふ</a></li>
			<li class="bone"><a href="{$smarty.const.BASE_URL}sitemap.php">サイトマップ</a></li>
		</ul>
	</div><!--foot_menu-->

	<div id="footer">
		Copyright : {$smarty.const.COPYRIGHT_SINCE} - {$smarty.now|date_format:"%Y"} {$smarty.const.COPYRIGHT_URL}
	</div><!--footer-->

</div><!--container-->

<!-- syntaxhighlighter -->
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shCore.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushBash.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushCpp.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushCss.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushDelphi.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushDiff.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushJScript.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushPhp.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushPlain.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushPython.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushRuby.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushScala.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushSql.js"></script>
<script type="text/javascript" src="{$smarty.const.BASE_URL}syntaxhighlighter/scripts/shBrushXml.js"></script>
<link type="text/css" rel="stylesheet" href="{$smarty.const.BASE_URL}syntaxhighlighter/styles/shCore.css"/>
<link type="text/css" rel="stylesheet" href="{$smarty.const.BASE_URL}syntaxhighlighter/styles/shThemeDefault.css"/>
<script type="text/javascript">
SyntaxHighlighter.config.clipboardSwf = '{$smarty.const.BASE_URL}syntaxhighlighter/scripts/clipboard.swf';
SyntaxHighlighter.all();
</script>
<!-- syntaxhighlighter -->

{$foot_info}

<!-- GoogleAnalytics -->
{literal}
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-4490132-2");
pageTracker._trackPageview();
} catch(err) {}</script>
{/literal}
<!-- GoogleAnalytics -->

</body>
</html>
