<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ""){ $_REQUEST['action'] = ""; }
	if(!isset($_REQUEST['record_file']) or $_REQUEST['record_file'] == ""){ $_REQUEST['record_file'] = ""; }
	if(!isset($_REQUEST['record_id']) or $_REQUEST['record_id'] == ""){ $_REQUEST['record_id'] = ""; }
	if(!isset($_REQUEST['raw_file']) or $_REQUEST['raw_file'] == ""){ $_REQUEST['raw_file'] = ""; }
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == ""){ $_REQUEST['device'] = ""; }
	
	$action = $_REQUEST['action'];
	$record_file = $_REQUEST['record_file'];
	$record_id = $_REQUEST['record_id'];
	$raw_file = rawurlencode($record_file);
	$device = $_REQUEST['device'];
	
	// delete m3u
	if($action == "delete")
	{
	$m3u_folder = is_dir('../tmp/');
	if($m3u_folder == '1')
	{
	$next_del_time = $time + 86400;
	exec('rm -f ../tmp/stream-*');
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `del_m3u_time` = '".$next_del_time."' WHERE `id` = '0' ");
	}
	exit;
	}
	
	if($device != "0")
	{
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
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data:ok";
	
?>