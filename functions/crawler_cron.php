<?php 
//
include("../inc/dashboard_config.php");

	// check power status
	$xmlfile = 'http://'.$box_ip.'/web/powerstate';

	$power_command = file_get_contents($xmlfile, false, $webrequest);

	$xml = simplexml_load_string($power_command);

	if(!isset($xml->e2instandby) or $xml->e2instandby == ""){ $xml->e2instandby = ""; }
	
	$power_status = $xml->e2instandby;
	
	$power_status = preg_replace('/\s+/', '', $power_status);
	
	if ($power_status == 'true')
	{
	// turn on Receiver
	$turn_on_request = "http://$box_ip/web/powerstate?newstate=0";
	$turn_on = file_get_contents($turn_on_request, false, $webrequest);
	//
	}
	
	sleep(10);
	
	$sql = "SELECT * FROM channel_list WHERE `crawl` = 1 ORDER BY e2servicename ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	{
	
	//count entries
	$stmt = $dbmysqli->prepare("SELECT COUNT(*) AS sum_entries FROM `epg_data` WHERE channel_hash = '".$obj->channel_hash."' ");
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
		
	$stmt->execute();
	$stmt->bind_result($sum_entries);
	$stmt->fetch();
	$stmt->close();
	
	if ($sum_entries < $start_epg_crawler)
	{
	
	// send zapp request
	$zapp_request = "http://$box_ip/web/zap?sRef=$obj->e2servicereference";
	$request = file_get_contents($zapp_request, false, $webrequest);
	
	$sql = mysqli_query($dbmysqli, "DELETE FROM epg_data WHERE channel_hash = '".$obj->channel_hash."'");
	
	sleep($cz_sleeptime);
	
	// crawl channel
	$start_crawl_request = "http://$script_location/uniwebif/functions/channel_crawler_complete.php?channel_id=$obj->e2servicereference";
	$start_crawl = file_get_contents($start_crawl_request); }
	}
	}
    }
	// zap to start channel
	$sql2 = mysqli_query($dbmysqli, "SELECT e2servicereference FROM channel_list WHERE zap_start = 1");
	$result2 = mysqli_fetch_assoc($sql2);
	$start_channel = $result2['e2servicereference'];
	
	$zap_request = "http://$box_ip/web/zap?sRef=$start_channel";
	$zap_start_channel = file_get_contents($zap_request, false, $webrequest);
	
	sleep(10);
	
	// turn off Receiver
	//$turn_off = file_get_contents($turn_on_request, false, $webrequest);
	
	// check power status
	$xmlfile = 'http://'.$box_ip.'/web/powerstate';

	$power_command = file_get_contents($xmlfile, false, $webrequest);

	$xml = simplexml_load_string($power_command);

	if(!isset($xml->e2instandby) or $xml->e2instandby == ""){ $xml->e2instandby = ""; }
	
	$power_status = $xml->e2instandby;
	
	$power_status = preg_replace('/\s+/', '', $power_status);
	
	if ($power_status == 'false')
	{
	// turn off Receiver
	$turn_off_request = "http://$box_ip/web/powerstate?newstate=0";
	$turn_off = file_get_contents($turn_off_request, false, $webrequest);
	//
	}
	
	$sql = mysqli_query($dbmysqli, "OPTIMIZE TABLE `epg_data`");
	
?>
