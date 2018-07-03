<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['command']) or $_REQUEST['command'] == "") { $_REQUEST['command'] = ""; }
	
	//recieve data	
	$command = $_REQUEST['command'];
	
	if(!isset($command) or $command == "") 
	{ 
	echo "data:error"; 
	
	} else {
	
	$remote_request = $url_format.'://'.$box_ip.'/web/remotecontrol?command='.$command.'';
	$switch_power = @file_get_contents($remote_request, false, $webrequest);
	
	$xmlfile = $url_format.'://'.$box_ip.'/web/powerstate';
	
	$power_status = @file_get_contents($xmlfile, false, $webrequest);

	$xml = simplexml_load_string($power_status);

	if(!isset($xml->e2instandby) or $xml->e2instandby == ""){ $xml->e2instandby = "";
	// error
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data:error";
	
	} else { 
	
	// define line
	$current_status = $xml->e2instandby;
	
	// remove spaces in xml answer
	$current_status = preg_replace('/\s+/', '', $current_status);
	
	// answer for ajax
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data:$current_status";
	}
}
?>