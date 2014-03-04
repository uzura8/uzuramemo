<div id="main_menu">
<h3 class="title">
	<a href="{site_url}program">プログラム</a>
	<span class="btnTop" id="title_btn_"><a href="javaScript:void(0);" onclick="$('#mainmenu_program_list').slideToggle();">▼</a></span>
</h3>
<div id="mainmenu_program_list">
{foreach from=$program_list_mainmenu item=row}
	<h4 class="title">
		<a href="{site_url}project/index/{$row.key_name}">{$row.name}</a>
		<span class="btnTop" id="mainmenu_program_{$row.id}"><a href="javaScript:void(0);" onclick="mainmanu_get_project_list({$row.id});">▼</a></span>
	</h4>
	<div id="mainmenu_project_list_loading_{$row.id}" style="display:none;"><img src="{site_url}img/loading.gif"></div>
	<div id="mainmenu_project_list_{$row.id}"></div>
{/foreach}
</div>
{*
<h3 class="title"><a href="{site_url}project">プロジェクト</a></h3>
*}
<h3 class="title"><a href="{site_url}gantt">{get_config_value key=site_title index=gantt}</a></h3>
<h3 class="title"><a href="{site_url}report">Report</a></h3>
<h3 class="title"><a href="{site_url}report/plan">Plan</a></h3>
<h3 class="title"><a href="{site_url}activity/schedule">Tasks(Date)</a></h3>
<h3 class="title"><a href="{site_url}activity/wbs">Tasks(Project)</a></h3>
{*
<h3 class="title"><a href="{site_url}activity/wbs?private=1">アクティビティ(個人)</a></h3>
*}
<h3 class="title"><a href="{site_url}">{$smarty.const.UM_TOPPAGE_NAME}</a></h3>
<h3 class="title"><a href="{site_url}/admin">管理画面</a></h3>

<h3 class="title">
	<a href="javaScript:void(0);">Links</a>
	<span class="btnTop"><a href="javaScript:void(0);" onclick="$('#shortcuts').slideToggle();">▼</a></span>
</h3>
<div id="shortcuts">
{foreach from=$important_wbs_list_mainmenu item=row}
	<h4 class="title">
		<div class="subtitle">{$row.program_name} {$row.project_name}</div>
		<div class="link">
			<a href="{site_url}activity/wbs/{$row.id}/1">{$row.name}</a>
			<a rel="prettyPopin" id="pp_link_wbs_{$row.id}" class="sl3" href="{site_url}activity/create/{$row.id}/1">&raquo;&nbsp;作成</a>
		</div>
	</h4>
{/foreach}
</div>

</div>
