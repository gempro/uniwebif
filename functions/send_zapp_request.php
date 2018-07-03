<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(!isset($_REQUEST['e2servicereference']) or $_REQUEST['e2servicereference'] == "") { $_REQUEST['e2servicereference'] = ""; }
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == "") { $_REQUEST['device'] = ""; }

	$e2servicereference = $_REQUEST['e2servicereference'];
	$device = $_REQUEST['device'];
	
	if(!isset($e2servicereference) or $e2servicereference == "") 
	{ 
	echo "data:error"; 
	exit;
	}
	
	sleep(1);
	
	// send to different device
	if($device != "0"){
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result = mysqli_fetch_assoc($sql);
	$box_ip = $result['device_ip'];
	$box_user = $result['device_user'];
	$box_password = $result['device_password'];
	
	// Webrequest
	$webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	
	$zapp_request = $url_format.'://'.$box_ip.'/web/zap?sRef='.$e2servicereference.'';
	$request = @file_get_contents($zapp_request, false, $webrequest);
	
	// answer for ajax
	echo "data:done";
	
	} else {
	
	$zapp_request = $url_format.'://'.$box_ip.'/web/zap?sRef='.$e2servicereference.'';
	$request = @file_get_contents($zapp_request, false, $webrequest);
	
	// answer for ajax
	echo "data:done";
	}
?>