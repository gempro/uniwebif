<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	if(!isset($_REQUEST['hash']) or $_REQUEST['hash'] == "") { $_REQUEST['hash'] = ""; } else { $_REQUEST['hash'] = $_REQUEST['hash']; }
	
	//recieve data	
	$hash = $_REQUEST['hash'];
	
	if(!isset($hash) or $hash == "") 
	{ 
	echo "data: data missed\n\n"; 
	
	} else {
	
	$sql = mysqli_query($dbmysqli, "SELECT e2eventservicereference FROM epg_data WHERE hash = '".$hash."'");
	$result = mysqli_fetch_assoc($sql);
	$e2eventservicereference = $result['e2eventservicereference'];
	
	$zapp_request = "http://$box_ip/web/zap?sRef=$e2eventservicereference";
	$request = file_get_contents($zapp_request, false, $webrequest);
	
	// close db
	mysqli_close($dbmysqli);
	
	sleep(1);
	
	// answer for ajax
	echo "data: ok\n\n";

	}
	
?>