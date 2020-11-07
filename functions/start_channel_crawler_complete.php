<?php 
//
include("../inc/dashboard_config.php");

	mysqli_query($dbmysqli, "UPDATE `settings` SET `epg_crawler_activ` = '1' ");
	
	mysqli_query($dbmysqli, "TRUNCATE `epg_data`");

	$sql = "SELECT `e2servicename`, `e2servicereference` FROM `channel_list` WHERE `crawl` = '1' ORDER BY `e2servicename` DESC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	$e2servicereference = $obj->e2servicereference;
	
	$start_crawl_request = $url_format.'://'.$server_ip.'/'.$script_folder.'/functions/channel_crawler_complete.php?channel_id='.$e2servicereference;
	$start_crawl = @file_get_contents($start_crawl_request); 
	
	sleep(1);
	
	}
    }
	}
	
	// save time from crawling
	mysqli_query($dbmysqli, "UPDATE `settings` SET `last_epg_crawl` = '$time' ");
	
	// reset saved search
	mysqli_query($dbmysqli, "UPDATE `saved_search` SET `crawled` = '0' WHERE `activ` = 'yes' ");
	
	// set epg crawler as current working
	mysqli_query($dbmysqli, "UPDATE `settings` SET `epg_crawler_activ` = '0' ");
	
	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data:done";

?>