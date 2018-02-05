<?php 
//
include("../inc/dashboard_config.php");

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	$sql = "SELECT * FROM `timer` WHERE `status` = 'waiting' ORDER BY `e2eventstart` ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {	
	{
	$id = $obj->id;
	$timer_request = $obj->timer_request;
	$hash = $obj->hash;
	$status = $obj->status;
	
	if ($status !== 'sent' ) {
	
	// remove " and ' from request
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	
	$send_timer_request = file_get_contents($timer_request, false, $webrequest);
	
	sleep(0.5);
	
	// mark as done
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'sent' WHERE `id` = '$id' ");
	$sql2 = mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '1' WHERE `hash` = '$hash' ");
	}
	}
	// answer for ajax
	echo "data: Timer was sent from database to Receiver!\n\n";
	}
    }
	
	// if nothing to do
	// answer for ajax
	echo "data: Timer was sent from database to Receiver!\n\n";
	
  // Free result set
  mysqli_free_result($result);

//close db
mysqli_close($dbmysqli);
?>
