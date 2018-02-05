<?php 
//
include("../inc/dashboard_config.php");

	$record_file = $_REQUEST['record_file'];
	$record_id = $_REQUEST['record_id'];
	$raw_file = rawurlencode($record_file);
	
	$stream_adress = ''.$url_format.'://'.$box_user.':'.$box_password.'@'.$box_ip.'/file?file='.$raw_file.'';
		
	$content = '#EXTM3U
	'.$stream_adress;
	
	$handle = fopen ("../tmp/stream-".$record_id.".m3u", "w");
	fwrite ($handle, $content);
	fclose ($handle);
	
	sleep(1);
	
	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data: ok\n\n";
	
?>