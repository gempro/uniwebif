<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['command']) or $_REQUEST['command'] == "") { $_REQUEST['command'] = ""; }
	$command = $_REQUEST['command'];
	
	if($command != "")
	{
	$rc_command = $url_format.'://'.$box_ip.'/web/remotecontrol?command='.$command.$session_part_2;
	$send_rc_command = @file_get_contents($rc_command, false, $webrequest);
	$xml = simplexml_load_string($send_rc_command);
	$e2result = $xml->e2result;
	sleep(1);
	echo $e2result;
	exit;
	}

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div align="center">
<button type="button" class="btn btn-default-3 btn-xs" style="background-color:#FF0000;" onclick="remote_control('398')">&nbsp;</button>
  <button type="button" class="btn btn-default-3 btn-xs" style="background-color:#3EA937;" onclick="remote_control('399')">&nbsp;</button>
  <button type="button" class="btn btn-default-3 btn-xs" style="background-color:#FFFF00;" onclick="remote_control('400')">&nbsp;</button>
  <button type="button" class="btn btn-default-3 btn-xs" style="background-color:#0000FF;" onclick="remote_control('401')">&nbsp;</button>
  <div class="spacer_5"></div>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('2')">1</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('3')">2</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('4')">3</button>
  <div class="spacer_5"></div>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('5')">4</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('6')">5</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('7')">6</button>
  <div class="spacer_5"></div>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('8')">7</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('9')">8</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('10')">9</button>
  <div class="spacer_5"></div>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('412')" title="Backward"><</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('11')">0</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('407')" title="Forward">></button>
  <div class="spacer_5"></div>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('358')" title="Info">I</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('103')" title="Up"><i class="glyphicon glyphicon-arrow-up"></i></button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('139')" title="Menue">M</button>
  <div class="spacer_5"></div>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('105')" title="Left"><i class="glyphicon glyphicon-arrow-left"></i></button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('352')" title="OK">OK</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('106')" title="Right"><i class="glyphicon glyphicon-arrow-right"></i></button>
  <div class="spacer_5"></div>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('388')" title="Teletext">Text</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('108')" title="Down"><i class="glyphicon glyphicon-arrow-down"></i></button>
  <button type="button" class="btn btn-danger btn-sm" onclick="remote_control('174')" title="Exit">Exit</button>
  <div class="spacer_5"></div>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('114')" title="Volume down">-</button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('113')" title="Volume mute/unmute"><i class="glyphicon glyphicon-volume-up"></i></button>
  <button type="button" class="btn btn-default btn-sm" onclick="remote_control('115')" title="Volume up">+</button>
</div>
</body>
</html>
