<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	if(!isset($_REQUEST['channel_hash']) or $_REQUEST['channel_hash'] == "") { $_REQUEST['channel_hash'] = ""; } else { $_REQUEST['channel_hash'] = $_REQUEST['channel_hash']; }
	
	//recieve data	
	$channel_hash = $_REQUEST['channel_hash'];
	
	if(!isset($channel_hash) or $channel_hash == "") 
	{ 
	echo "data: data missed\n\n"; 
	
	} else {
	
	$sql = mysqli_query($dbmysqli, "SELECT e2servicereference FROM channel_list WHERE channel_hash = '".$channel_hash."'");
	$result = mysqli_fetch_assoc($sql);
	$e2servicereference = $result['e2servicereference'];
	
	$zapp_request = "http://$box_ip/web/zap?sRef=$e2servicereference";
	$request = file_get_contents($zapp_request, false, $webrequest);
	
	// close db
	mysqli_close($dbmysqli);
	
	sleep(1);
	
	// answer for ajax
	echo "data: ok\n\n";

	}
	
?>