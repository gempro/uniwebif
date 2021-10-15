<?php 
//
	include('../inc/dashboard_config.php');

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(!isset($_REQUEST['e2servicereference']) or $_REQUEST['e2servicereference'] == ''){ $_REQUEST['e2servicereference'] = ''; }
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == ''){ $_REQUEST['device'] = ''; }

	$e2servicereference = $_REQUEST['e2servicereference'];
	$device = $_REQUEST['device'];
	$channel_info = '';
	
	if(!isset($e2servicereference) or $e2servicereference == '') 
	{ 
	echo 'data:error'; 
	exit;
	}
	
	sleep(1);
	
	// iptv channel
	if(preg_match('/\b4097:0:1:0:0:0:0:0:0:0:\b/i', $e2servicereference))
	{
	$sql = mysqli_query($dbmysqli, "SELECT `servicename_enc` FROM `channel_list` WHERE `e2servicereference` LIKE '".$e2servicereference."' ");
	$result = mysqli_fetch_assoc($sql);
	$servicename_enc = $result['servicename_enc'];
	$servicename_enc = str_replace('%20', '+', $servicename_enc);
	$channel_info = ':'.$servicename_enc;
	}
	
	// send to different device
	if($device != '0')
	{
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result = mysqli_fetch_assoc($sql);
	$box_ip = $result['device_ip'];
	$box_user = $result['device_user'];
	$box_password = $result['device_password'];
	
	// zap request
	$zapp_request = $url_format.'://'.$box_ip.'/web/zap?sRef='.$e2servicereference.$channel_info.$session_part_2;
	$request = @file_get_contents($zapp_request, false, $webrequest);
	
	// answer for ajax
	echo 'data:done';
	
	} else {
	
	// zap request
	$zapp_request = $url_format.'://'.$box_ip.'/web/zap?sRef='.$e2servicereference.$channel_info.$session_part_2;
	$request = @file_get_contents($zapp_request, false, $webrequest);
	
	// answer for ajax
	echo 'data:done';
	
	}
	
?>