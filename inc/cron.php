<?php 
//
	include_once("dashboard_config.php");
	
	// get settings
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `settings` WHERE `id` = '0' ");
	$settings = mysqli_fetch_assoc($sql);
	
	// check if cron is active / epg crawler is current working
	if($settings["activate_cron"] == '0' or $settings["epg_crawler_activ"] == '1')
	{
	exit;
	}
	
	// start search crawler / save timer in db
	$last_crawl_time = $settings["last_search_crawl"];
	//$time_to_crawl = $last_crawl_time + 0;
	if($settings["search_crawler"] == '1')
	{
	if($last_crawl_time < $time)
	{
	$save_timer_in_db = file_get_contents(''.$url_format.'://'.$server_ip.'/'.$script_folder.'/functions/save_timer_in_db.php');
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `last_search_crawl` = '$time'");
	}
	}
	
	// hide expired timer from database
	if($settings["hide_old_timer"] == '1')
	{
	$hide_old_timer = mysqli_query($dbmysqli, "UPDATE `timer` SET `expired` = '1' WHERE `e2eventend` < '$time' AND `expired` NOT LIKE '1' ");
	}
	
	// delete expired timer from database
	if($settings["delete_old_timer"] == '1')
	{
	$del_period = $time - 604800;
	$delete_old_timer = mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `e2eventend` < '$del_period' ");
	}
	
	// delete expired timer from receiver
	if($settings["delete_receiver_timer"] == '1')
	{
	$delete_timer_request = $url_format.'://'.$box_ip.'/web/timercleanup?cleanup=true';
	$delete_receiver_timer = file_get_contents($delete_timer_request, false, $webrequest);
	}
	
	// delete expired timer from additional receiver
	if($settings["delete_further_receiver_timer"] == "1")
	{
	$sql = "SELECT * FROM `device_list`";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){	
	{
	$device_ip = $obj->device_ip;
	$device_user = $obj->device_user;
	$device_password = $obj->device_password;
	$device_url_format = $obj->url_format;
	$device_webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$device_user:$device_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$delete_device_timer_request = $device_url_format.'://'.$device_ip.'/web/timercleanup?cleanup=true';
	$delete_device_receiver_timer = file_get_contents($delete_device_timer_request, false, $device_webrequest);
	sleep(1);
	}
    }
	}
	}
	
	// delete duplicate timer
	//$sql = mysqli_query($dbmysqli, "DELETE FROM timer USING timer, timer AS Dup WHERE NOT timer.id = Dup.id AND timer.id > Dup.id AND timer.timer_request = Dup.timer_request");
	
	$sql = mysqli_query($dbmysqli, "OPTIMIZE TABLE `timer`");
	$sql = mysqli_query($dbmysqli, "OPTIMIZE TABLE `saved_search`");
	
	// delete old epg
	if($settings["delete_old_epg"] == '1')
	{
	$time_to_delete = $time - $del_time;
	$delete_epg = mysqli_query($dbmysqli, "DELETE FROM `epg_data` WHERE `e2eventstart` < '$time_to_delete'");
	}
	
	// update record status if search crawler is deactivated
	if($settings["search_crawler"] == '0')
	{
	$check_timer = "SELECT * FROM `timer` WHERE `record_status` NOT LIKE 'c_expired' ";
	
	if($result = mysqli_query($dbmysqli,$check_timer))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	$id = $obj->id;
	
	if($time > $obj->e2eventend)
	{
	$record_status = 'c_expired'; }
	
	if($time > $obj->e2eventstart and $time < $obj->e2eventend)
	{
	$record_status = 'a_recording'; }
	
	if($time < $obj->e2eventstart and $time < $obj->e2eventend)
	{
	$record_status = 'b_incoming'; }

	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `record_status` = '".$record_status."' WHERE `id` = '$id' "); }
	}
	}
	}
	
	// send timer to receiver
	if($settings["send_timer"] == '1')
	{
	$timer_request = $url_format.'://'.$server_ip.'/'.$script_folder.'/functions/send_timer_to_box.php';
	$send_timer = file_get_contents($timer_request);
	}
	
	// start epg crawler
	if($settings["epg_crawler"] == '1' and $settings["crawler_timestamp"] < $time)
	{
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `epg_crawler_activ` = '1'");
	
	// dummy timer
	$crawler_timestamp = $settings["crawler_timestamp"] - 300;
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `dummy_timer_current` = '$crawler_timestamp' WHERE `id` = '0' ");
	
	$next_crawl = $settings["crawler_timestamp"] + 86400;
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `crawler_timestamp` = '$next_crawl' WHERE `id` = '0' ");
	
	sleep(1);
	
	$epg_crawler = $url_format.'://'.$server_ip.'/'.$script_folder.'/functions/crawler_cron.php';
	$start_crawling = file_get_contents($epg_crawler);
	}

	// start channel zapper
	if($settings["cz_activate"] == '1')
	{				
	$cz_timestamp = $settings["cz_timestamp"];
	if($cz_timestamp < $time)
	{
	$channel_zapper = $url_format.'://'.$server_ip.'/'.$script_folder.'/functions/channelzapper.php';
	$start_zapping = file_get_contents($channel_zapper);
	}
	}
	
	// delete m3u
	if($settings["del_m3u"] == '1' and $settings["del_m3u_time"] < $time)
	{
	$delete_m3u = $url_format.'://'.$server_ip.'/'.$script_folder.'/functions/create_m3u.php?action=delete';
	$delete_request = file_get_contents($delete_m3u);
	}
?>
