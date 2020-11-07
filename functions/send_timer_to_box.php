<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ""){ $_REQUEST['action'] = ""; }
	$action = $_REQUEST['action'];

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	$sql = "SELECT * FROM `timer` WHERE `status` = 'waiting' OR `status` = 'rec_deleted' ORDER BY `e2eventstart` ASC";
	
	if($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {	
	{
	$id = $obj->id;
	$timer_request = $obj->timer_request;
	$hash = $obj->hash;
	$status = $obj->status;
	$device = $obj->device;
	
	if($status != 'sent')
	{
	// send to different device
	if($device != "0" and $device != "")
	{
	$sql_2 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result_2 = mysqli_fetch_assoc($sql_2);
	$box_ip = $result_2['device_ip'];
	$box_user = $result_2['device_user'];
	$box_password = $result_2['device_password'];
	//$e2location = $result3['device_record_location'];
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	$webrequest = stream_context_create(array (
	'http' => array (
	'method' => 'POST',
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$send_timer_request = @file_get_contents($timer_request, false, $webrequest);
	sleep(1);
	} // send to different device
	
	else {
	
	// remove " and ' from request
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	$send_timer_request = @file_get_contents($timer_request, false, $webrequest);
	
	sleep(1);
	} // send to default receiver
	
	// mark as done
	if($action == "manual")
	{
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'manual' WHERE `id` = '$id' "); 
	
	} else {
	
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'sent' WHERE `id` = '$id' ");
	}
	
	mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '1' WHERE `hash` = '$hash' ");
	}
	}
	}
    }
	
	// answer for ajax
	echo "data:done";
	
?>
