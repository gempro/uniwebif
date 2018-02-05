<?php 
//
include("../inc/dashboard_config.php");

	// check power status
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/powerstate';

	$power_command = file_get_contents($xmlfile, false, $webrequest);

	$xml = simplexml_load_string($power_command);

	if(!isset($xml->e2instandby) or $xml->e2instandby == ""){ $xml->e2instandby = ""; }
	
	$power_status = $xml->e2instandby;
	
	$power_status = preg_replace('/\s+/', '', $power_status);
	
	if ($power_status == 'true')
	{
	// turn on Receiver
	$turn_on_request = "$url_format://$box_ip/web/powerstate?newstate=0";
	$turn_on = file_get_contents($turn_on_request, false, $webrequest);
	//
	}
	
	sleep(10);
	
	$sql = "SELECT * FROM `channel_list` WHERE `crawl` = '1' ORDER BY `e2servicename` ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	{
	
	//count entries
	$stmt = $dbmysqli->prepare("SELECT COUNT(*) AS sum_entries FROM `epg_data` WHERE `channel_hash` = '".$obj->channel_hash."' ");
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
		
	$stmt->execute();
	$stmt->bind_result($sum_entries);
	$stmt->fetch();
	$stmt->close();
	
	if ($sum_entries < $start_epg_crawler)
	{
	
	// send zap request
	$zapp_request = "$url_format://$box_ip/web/zap?sRef=$obj->e2servicereference";
	$request = file_get_contents($zapp_request, false, $webrequest);
	
	$sql = mysqli_query($dbmysqli, "DELETE FROM `epg_data` WHERE `channel_hash` = '".$obj->channel_hash."'");
	
	sleep($cz_sleeptime);
	
	//save provider from channel in db
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/getcurrent';
	$request = file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($request);
	
if($xml->e2service->e2providername != "")
	{ 
	$e2providername = $xml->e2service->e2providername;
	$sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `e2providername` = '$e2providername' WHERE `e2servicereference` = '".$obj->e2servicereference."' ");
	}
	
	// crawl channel
	$start_crawl_request = "$url_format://$server_ip/$script_folder/functions/channel_crawler_complete.php?channel_id=$obj->e2servicereference";
	$start_crawl = file_get_contents($start_crawl_request);
	}
	}
	}
    }
	// zap to start channel
	$sql2 = mysqli_query($dbmysqli, "SELECT `e2servicereference` FROM `channel_list` WHERE `zap_start` = '1' ");
	$result2 = mysqli_fetch_assoc($sql2);
	$start_channel = $result2['e2servicereference'];
	
	$zap_request = "$url_format://$box_ip/web/zap?sRef=$start_channel";
	$zap_start_channel = file_get_contents($zap_request, false, $webrequest);
	
	sleep(10);
	
	// delete dummy timer
	if ($dummy_timer == '1')
	{
	$dummy_timer_start = $dummy_timer_current;
	$dummy_timer_time_end = $dummy_timer_start + 1;
	
	$dummy_timer_request = ''.$url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$cz_start_channel.'&begin='.$dummy_timer_start.'&end='.$dummy_timer_time_end.'';
	$delete_dummy_timer = file_get_contents($dummy_timer_request, false, $webrequest);
	
	sleep(3);
	
	// send dummy timer
	$next_dummy_timer_start = $dummy_timer_start + 86400;
	$next_dummy_timer_end = $next_dummy_timer_start + 1;
	
	$dummy_timer_request = ''.$url_format.'://'.$box_ip.'/web/timeradd?sRef='.$cz_start_channel.'&begin='.$next_dummy_timer_start.'&end='.$next_dummy_timer_end.'&name=Dummy%20Timer&description=Turn%20on%20Receiver%20from%20Deep%20Standby&tags=&afterevent=0&eit=0&disabled=0&justplay=1&repeated=0';
	$send_dummy_timer = file_get_contents($dummy_timer_request, false, $webrequest);
	}
	
	sleep(3);
	
	// powerstate after crawling
	if ($after_crawl_action == '9'){$powerstate = ''; } else { $powerstate = $after_crawl_action; }
	
	$powerstate_request = "$url_format://$box_ip/web/powerstate?newstate=$powerstate";
	$send_powerstate = file_get_contents($powerstate_request, false, $webrequest);
	
	if(!isset($next_dummy_timer_start) or $next_dummy_timer_start == "") { $next_dummy_timer_start = ""; }
	
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `dummy_timer_time` = '".$next_dummy_timer_start."' ");
	
	// reset saved search
	$sql = mysqli_query($dbmysqli, "UPDATE `saved_search` SET `crawled` = '0' WHERE `activ` = 'yes' ");
	
	$sql = mysqli_query($dbmysqli, "REPAIR TABLE `epg_data`");
	
	// set epg crawler not working
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `epg_crawler_activ` = '0' ");

?>