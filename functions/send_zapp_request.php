<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(!isset($_REQUEST['e2servicereference']) or $_REQUEST['e2servicereference'] == "") { $_REQUEST['e2servicereference'] = ""; }

	$e2servicereference = $_REQUEST['e2servicereference'];
	
	if(!isset($e2servicereference) or $e2servicereference == "") 
	{ 
	echo "data: data missed\n\n"; 
	
	} else {
	
	$zapp_request = ''.$url_format.'://'.$box_ip.'/web/zap?sRef='.$e2servicereference.'';
	$request = file_get_contents($zapp_request, false, $webrequest);
	
	sleep(1);
	
	// answer for ajax
	echo "data: ok\n\n";
	exit;
	}

?>