<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function head_style($str = '')
{
	return <<<EOT
<style type="text/css">
<!--
{$str}
-->
</style>

EOT;

}
