<?php 
//
include("../inc/dashboard_config.php");
	
	$sql = mysqli_query($dbmysqli, "TRUNCATE `epg_data`");

	$sql = "SELECT e2servicename, e2servicereference FROM channel_list WHERE crawl = '1' ORDER BY `e2servicename` DESC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	{
	$e2servicereference = $obj->e2servicereference;
	
	$start_crawl_request = "http://$script_location/uniwebif/functions/channel_crawler_complete.php?channel_id=$e2servicereference";
	
	$start_crawl = file_get_contents($start_crawl_request, false, $webrequest); 
	sleep(1);	
	}
    }
	// Free result set
	mysqli_free_result($result);
	}
	
	$sql = mysqli_query($dbmysqli, "UPDATE settings SET last_epg_crawl = '$time' ");
	
	// close db
	mysqli_close($dbmysqli);
	
	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data: Channel EPG crawling - done!\n\n";

?>