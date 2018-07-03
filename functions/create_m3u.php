<?php 
//
include("../inc/dashboard_config.php");

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	$record_file = $_REQUEST['record_file'];
	$record_id = $_REQUEST['record_id'];
	$raw_file = rawurlencode($record_file);
	$device = $_REQUEST['device'];
	
	if($device != "0"){
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result = mysqli_fetch_assoc($sql);
	$box_ip = $result['device_ip'];
	$box_user = $result['device_user'];
	$box_password = $result['device_password'];
	$url_format = "http";
	}
	
	$stream_adress = ''.$url_format.'://'.$box_user.':'.$box_password.'@'.$box_ip.'/file?file='.$raw_file.'';
		
	$content = '#EXTM3U
	'.$stream_adress;
	
	$handle = fopen ("../tmp/stream-".$record_id.".m3u", "w");
	fwrite ($handle, $content);
	fclose ($handle);
	
	sleep(1);
	
	// answer for ajax
	echo "data:ok";
	
?>