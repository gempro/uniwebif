<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	if(!isset($_REQUEST['e2servicereference']) or $_REQUEST['e2servicereference'] == "") { $_REQUEST['e2servicereference'] = ""; } else { $_REQUEST['e2servicereference'] = $_REQUEST['e2servicereference']; }
	
	//recieve data	
	$e2servicereference = $_REQUEST['e2servicereference'];
	
	if(!isset($e2servicereference) or $e2servicereference == "") 
	{ 
	echo "data: data missed\n\n"; 
	
	} else {
	
	$sql = mysqli_query($dbmysqli, "SELECT e2servicereference FROM tv_services WHERE e2servicereference = '".$e2servicereference."'");
	$result = mysqli_fetch_assoc($sql);
	$e2servicereference = $result['e2servicereference'];
	
	$zapp_request = "$url_format://$box_ip/web/zap?sRef=$e2servicereference";
	$request = file_get_contents($zapp_request, false, $webrequest);
	
	// close db
	mysqli_close($dbmysqli);
	
	sleep(1);
	
	// answer for ajax
	echo "data: ok\n\n";

	}
	
?>