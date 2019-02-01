<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['command']) or $_REQUEST['command'] == "") { $_REQUEST['command'] = ""; }
	$command = $_REQUEST['command'];
	
	if($command != "")
	{
	$rc_command = $url_format.'://'.$box_ip.'/web/remotecontrol?command='.$command.'';
	$send_rc_command = @file_get_contents($rc_command, false, $webrequest);
	$xml = simplexml_load_string($send_rc_command);
	$e2result = $xml->e2result;
	echo $e2result;
	exit;
	}

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
<div align="center">
  <input class="btn btn-default" type="button" onclick="remote_control('2')" value="1">
  <input class="btn btn-default" type="button" onclick="remote_control('3')" value="2">
  <input class="btn btn-default" type="button" onclick="remote_control('4')" value="3">
  <div class="spacer_5"></div>
  <input class="btn btn-default" type="button" onclick="remote_control('5')" value="4">
  <input class="btn btn-default" type="button" onclick="remote_control('6')" value="5">
  <input class="btn btn-default" type="button" onclick="remote_control('7')" value="6">
  <div class="spacer_5"></div>
  <input class="btn btn-default" type="button" onclick="remote_control('8')" value="7">
  <input class="btn btn-default" type="button" onclick="remote_control('9')" value="8">
  <input class="btn btn-default" type="button" onclick="remote_control('10')" value="9">
  <div class="spacer_5"></div>
  <span class="btn btn-default" onclick="remote_control('105')" title="Channel down"><i class="glyphicon glyphicon-arrow-down"></i></span>
  <input class="btn btn-default" type="button" onclick="remote_control('11')" value="0">
  <span class="btn btn-default" onclick="remote_control('106')" title="Channel up"><i class="glyphicon glyphicon-arrow-up"></i></span>
  <div class="spacer_5"></div>
  <input class="btn btn-default" type="button" onclick="remote_control('114')" value="-" title="Volume down">
  <span class="btn btn-default" onclick="remote_control('113')" title="Volume mute/unmute"><i class="glyphicon glyphicon-volume-up"></i></span>
  <input class="btn btn-default" type="button" onclick="remote_control('115')" value="+" title="Volume up">
  
</div>
</body>
</html>
