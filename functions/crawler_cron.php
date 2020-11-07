<?php 
//
include("../inc/dashboard_config.php");

	
	// crawler time start
	mysqli_query($dbmysqli, "UPDATE `settings` SET `crawler_start` = '".time()."' WHERE `id` = '0' ");

	// check power status
	$xmlfile = $url_format.'://'.$box_ip.'/web/powerstate'.$session_part;

	$power_command = @file_get_contents($xmlfile, false, $webrequest);

	$xml = simplexml_load_string($power_command);

	if(!isset($xml->e2instandby) or $xml->e2instandby == ""){ $xml->e2instandby = ""; }
	
	$power_status = $xml->e2instandby;
	
	$power_status = preg_replace('/\s+/', '', $power_status);
	
	if ($power_status == 'true')
	{
	// turn on Receiver
	$turn_on_request = $url_format.'://'.$box_ip.'/web/powerstate?newstate=0'.$session_part_2;
	$turn_on = @file_get_contents($turn_on_request, false, $webrequest);
	}
	
	sleep(10);
	
	$sql = "SELECT * FROM `channel_list` WHERE `crawl` = '1' ORDER BY `e2servicename` ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	//count entries
	$sql_2 = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `epg_data` WHERE `channel_hash` = "'.$obj->channel_hash.'" ');
	$result_2 = mysqli_fetch_row($sql_2);
	$sum_entries = $result_2[0];
		
	if($sum_entries < $start_epg_crawler)
	{
	
	// send zap request
	$zapp_request = $url_format.'://'.$box_ip.'/web/zap?sRef='.$obj->e2servicereference.$session_part_2;
	$request = @file_get_contents($zapp_request, false, $webrequest);
	
	sleep($cz_wait_time);
	
	// save provider from channel in db
	$xmlfile = $url_format.'://'.$box_ip.'/web/getcurrent'.$session_part;
	$request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($request);
	
	if(!isset($xml->e2service->e2providername) or $xml->e2service->e2providername == "") { $xml->e2service->e2providername = ""; }
	if($xml->e2service->e2providername != "")
	{ 
	$e2providername = $xml->e2service->e2providername;
	mysqli_query($dbmysqli, "UPDATE `channel_list` SET `e2providername` = '".$e2providername."' WHERE `e2servicereference` = '".$obj->e2servicereference."' ");
	}
	
	// crawl channel
	$start_crawl_request = $url_format.'://'.$server_ip.'/'.$script_folder.'/functions/channel_crawler_complete.php?channel_id='.$obj->e2servicereference.'';
	$start_crawl = @file_get_contents($start_crawl_request);
	}
	}
	}
    }
	// zap to start channel
	$sql_3 = mysqli_query($dbmysqli, "SELECT `e2servicereference` FROM `channel_list` WHERE `zap_start` = '1' ");
	$result_3 = mysqli_fetch_assoc($sql_3);
	$start_channel = $result_3['e2servicereference'];
	
	$zap_request = $url_format.'://'.$box_ip.'/web/zap?sRef='.$start_channel.$session_part_2;
	$zap_start_channel = @file_get_contents($zap_request, false, $webrequest);
	
	sleep(10);
	
	// delete dummy timer
	if($dummy_timer == '1')
	{
	$dummy_timer_start = $dummy_timer_current;
	$dummy_timer_time_end = $dummy_timer_start + 1;
	
	$dummy_timer_request = $url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$cz_start_channel.'&begin='.$dummy_timer_start.'&end='.$dummy_timer_time_end.$session_part_2;
	$delete_dummy_timer = @file_get_contents($dummy_timer_request, false, $webrequest);
	
	sleep(3);
	
	// send dummy timer
	$next_dummy_timer_start = $dummy_timer_start + 86400;
	$next_dummy_timer_end = $next_dummy_timer_start + 1;
	
	$dummy_timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$cz_start_channel.'&begin='.$next_dummy_timer_start.'&end='.$next_dummy_timer_end.'&name=Dummy%20Timer&description=Turn%20on%20Receiver%20from%20Deep%20Standby&tags=&afterevent=0&eit=0&disabled=0&justplay=1&repeated=0'.$session_part;
	$send_dummy_timer = @file_get_contents($dummy_timer_request, false, $webrequest);
	}
	
	sleep(3);
	
	// powerstate after crawling
	if($after_crawl_action == '9'){ $powerstate = ''; } else { $powerstate = $after_crawl_action; }
	
	$powerstate_request = $url_format.'://'.$box_ip.'/web/powerstate?newstate='.$powerstate.$session_part_2;
	$send_powerstate = @file_get_contents($powerstate_request, false, $webrequest);
	
	if(!isset($next_dummy_timer_start) or $next_dummy_timer_start == "") { $next_dummy_timer_start = ""; }
	
	mysqli_query($dbmysqli, "UPDATE `settings` SET `dummy_timer_time` = '".$next_dummy_timer_start."' ");
	
	// reset saved search
	mysqli_query($dbmysqli, "UPDATE `saved_search` SET `crawled` = '0' WHERE `activ` = 'yes' ");
	mysqli_query($dbmysqli, "REPAIR TABLE `epg_data`");
	
	// set epg crawler not working
	mysqli_query($dbmysqli, "UPDATE `settings` SET `epg_crawler_activ` = '0', `crawler_end` = '".time()."' WHERE `id` = '0' ");

?>