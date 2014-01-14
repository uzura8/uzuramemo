<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bootstrap, from Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
		<link rel="stylesheet" href="<?php echo site_url('css/bootstrap.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('css/bootstrap-responsive.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('css/jquery-ui-1.8.14.custom.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('css/jquery-ui-calendar.custom.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('css/ui.theme.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('css/jquery.jqplot.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('css/base.css'); ?>">
<?php /*
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
*/ ?>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
<!--
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
-->
  </head>

  <body>

    <div class="navbar navbar-inverse">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Project name</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logged in as <a href="#" class="navbar-link">Username</a>
            </p>
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="<?php echo site_url('activity/schedule'); ?>">Tasks</a></li>
              <li><a href="<?php echo site_url('report'); ?>">Report</a></li>
              <li><a href="<?php echo site_url('report/plan'); ?>">Plan</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span12">

<form class="form-inline" method="get" action="<?php echo site_url('report'); ?>">
	<input type="text" class="input-small" name="from_date" id="from_date" placeholder="from_date" value="<?php echo $from_date; ?>">
<?php
$options = array(
	'7'  => '1week',
	'14' => '2week',
	'28' => '3week',
	'30' => '1month',
	'60' => '2month',
	'3'  => '3day',
	'5'  => '5day',
);
echo form_dropdown('period', $options, $period);
?>
	<button type="submit" class="btn">update</button>

	<div class="btn-group pull-right">
		<a class="btn" href="<?php echo site_url('report'); ?>?from_date=<?php echo date('Y-m-d', strtotime(sprintf('%s - %d day', $from_date, $period))); ?>&period=<?php echo $period; ?>"><i class=" icon-step-backward"></i></a>
		<a class="btn" href="<?php echo site_url('report'); ?>"><i class=" icon-stop"></i> reset</a>
		<a class="btn" href="<?php echo site_url('report'); ?>?from_date=<?php echo date('Y-m-d', strtotime(sprintf('%s + %d day', $from_date, $period))); ?>&period=<?php echo $period; ?>"><i class=" icon-step-forward"></i></a>
	</div>
</form>

<h1>実績工数</h1>
<h2><?php echo $from_date; ?> 〜 <?php echo $to_date; ?></h2>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span5">

<table class="table table-striped">
<tr>
	<th>project</th>
	<th>見積(h)</th>
	<th>実績(h)</th>
	<th>実績(%)</th>
</tr>
<?php foreach ($project_spent_times as $project_id => $projects): ?>
<?php if (!$project_estimated_times[$project_id] && !$project_spent_times[$project_id]) continue; ?>
<tr>
	<td><a href="#project_<?php echo $project_id; ?>"><?php echo $program_project_names[$project_id]; ?></a></td>
	<td><?php echo (float)$project_estimated_times[$project_id]; ?></td>
	<td><?php echo (float)$project_spent_times[$project_id]; ?></td>
	<td><?php echo round($project_spent_times[$project_id]/$project_spent_times_sum*100); ?></td>
</tr>
<?php endforeach; ?>
<tr>
	<th>合計</th>
	<th><?php echo $project_estimated_times_sum; ?> h</th>
	<th><?php echo $project_spent_times_sum; ?> h</th>
	<th>100 %</th>
</tr>
</table>
<div class="clearfix">
<span class="pull-right normal">稼働日数: <?php echo count($scheduled_dates); ?></span>
</div>

		</div>
		<div class="span7">

<div id="jqPlot-sample"></div>

		</div>
	</div>
</div>

<h3>実施項目一覧</h3>
<table class="table table-striped">
<tr>
	<th>project</th>
	<th>wbs</th>
	<th>タスク</th>
	<th>予定日</th>
	<th>実施日</th>
	<th>見積(h)</th>
	<th>実績(h)</th>
	<th>完了</th>
</tr>
<?php foreach ($list as $program_id => $programs): ?>
<?php foreach ($programs as $project_id => $projects): ?>
<?php $is_new_project = true; ?>
<?php foreach ($projects as $wbs_id => $wbses): ?>
<?php foreach ($wbses as $id => $row): ?>
<tr<?php if ($is_new_project): ?> id="project_<?php echo $project_id; ?>"<?php endif; ?>>
	<td>
		<a href="<?php echo site_url('wbs/index/'.$row['project_key_name']); ?>"><?php echo sprintf('%s %s',$program_names[$program_id], $project_names[$project_id]); ?></a>
	</td>
	<td><a href="<?php echo site_url('activity/wbs/'.$wbs_id); ?>"><?php echo mb_substr($wbs_names[$wbs_id], 0, 20); ?></a></td>
	<td><?php echo mb_substr($row['name'], 0, 35); ?></td>
	<td><?php echo substr($row['scheduled_date'], 5); ?></td>
	<td><?php echo substr($row['closed_date'], 5); ?></td>
	<td><?php echo (float)$row['estimated_time']; ?></td>
	<td><?php echo (float)$row['spent_time']; ?></td>
	<td><?php if ($row['del_flg']): ?>完了<?php else: ?>未完了<?php endif; ?></td>
</tr>
<?php $is_new_project = false; ?>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endforeach; ?>
<tr>
	<th>project</th>
	<th>wbs</th>
	<th>タスク</th>
	<th>予定日</th>
	<th>完了日</th>
	<th>見積(h)</th>
	<th>実績(h)</th>
	<th>完了</th>
</tr>
</table>

            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->
<?php /*
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Sidebar</li>
              <li class="active"><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li class="nav-header">Sidebar</li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li class="nav-header">Sidebar</li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
*/ ?>
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; Company 2013</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
		<script type="text/javascript" src="<?php echo site_url('js/lib/jquery.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('js/lib/jquery-ui-1.8.14.custom.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('js/lib/jquery.ui.datepicker-ja.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('js/jquery.jqplot.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('js/plugins/jqplot.pieRenderer.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('js/uzura_util.js'); ?>"></script>
<!--
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>
-->
<script>

$(function () {
	uzura_datepicker("#from_date");
	$ . jqplot(
		'jqPlot-sample',
		[
			[
<?php foreach ($project_spent_times as $project_id => $projects): ?>
<?php if (!$project_spent_times[$project_id]) continue; ?>
				['<?php echo $program_project_names[$project_id]; ?>',<?php echo (float)$project_spent_times[$project_id]; ?>],
<?php endforeach; ?>
			]
		],
		{
			seriesDefaults: {
				renderer: jQuery . jqplot . PieRenderer,
				rendererOptions: {
					padding: 5,
					showDataLabels: true,
					startAngle: -90
				},
			},
			legend: {
				show: true,
				location: 'e',
				//rendererOptions: {
				//	numberRows: 1
				//},
			}
		}
	);
});
</script>
  </body>
</html>

