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

<h1>WBS 未設定 Activities</h1>

<form class="form-inline" method="post" action="<?php echo site_url('activity/execute_register_wbs_id'); ?>">
<p><button type="submit" class="btn">update</button></p>
<table class="table table-striped">
<tr>
	<th>タスク</th>
	<th>wbs</th>
	<th>spent_time</th>
	<th>date</th>
</tr>
<?php
$options_add_date = array(
	''  => '変更しない',
	'+0'  => 'today',
	'-1'  => '-1day',
	'+1'  => '+1day',
	'+2'  => '+2day',
	'+3'  => '+3day',
	'+4'  => '+4day',
	'+5'  => '+5day',
	'-2'  => '-2day',
	'-3'  => '-3day',
	'+7'  => '+1week',
	'+14' => '+2week',
	'+28' => '+3week',
	'+30' => '+1month',
	'+60' => '+2month',
);
$options_spent_time = array(
	''  => '変更しない',
	'0.25'  => '0.25',
	'0.50'  => '0.50',
	'0.75'  => '0.75',
	'1.00'  => '1.00',
	'1.25'  => '1.25',
	'1.50'  => '1.50',
	'1.75'  => '1.75',
	'2.00'  => '2.00',
	'2.25'  => '2.25',
	'2.50'  => '2.50',
	'2.75'  => '2.75',
	'3.00'  => '3.00',
	'3.25'  => '3.25',
	'3.50'  => '3.50',
	'3.75'  => '3.75',
	'4.00'  => '4.00',
	'4.25'  => '4.25',
	'4.50'  => '4.50',
	'4.75'  => '4.75',
	'5.00'  => '5.00',
	'6.25'  => '6.25',
	'6.50'  => '6.50',
	'6.75'  => '6.75',
	'7.00'  => '7.00',
);
?>
<?php foreach ($list as $id => $activity): ?>
<tr class="activity_<?php echo $activity['id']; ?>">
	<td><?php echo mb_substr($activity['name'], 0, 70); ?></td>
	<td><?php echo form_dropdown('wbs_id['.$activity['id'].']', $options_wbs, null, ' class="input-xlarge"'); ?></td>
	<td><?php echo form_dropdown('spent_time['.$activity['id'].']', $options_spent_time, $activity['spent_time'], ' class="input-small"'); ?></td>
	<td><?php echo form_dropdown('add_date['.$activity['id'].']', $options_add_date, null, ' class="input-small"'); ?></td>
</tr>
<?php endforeach; ?>
<tr>
	<th>タスク</th>
	<th>wbs</th>
	<th>date</th>
</tr>
</table>
<p><button type="submit" class="btn">update</button></p>
</form>

            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; Company 2013</p>
      </footer>

    </div><!--/.fluid-container-->

		<script type="text/javascript" src="<?php echo site_url('js/lib/jquery.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('js/bootstrap.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('js/lib/jquery-ui-1.8.14.custom.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('js/uzura_util.js'); ?>"></script>
<script>

$(function () {
});
</script>
  </body>
</html>

