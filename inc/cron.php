<?php 
// action for cron
	require_once("dashboard_config.php");
	
	// get settings
	$sql = mysqli_query($dbmysqli, "SELECT * FROM settings");
	$settings = mysqli_fetch_assoc($sql);
	
	// check if cron is active
	if ($settings["activate_cron"] == '0')
	{
	// close db
	mysqli_close($dbmysqli);
	exit;
	}
	
	// start search crawler / save timer in db
	$last_crawl_time = $settings["last_search_crawl"];
	$time_to_crawl = $last_crawl_time + 0;
	
	if ($settings["search_crawler"] == '1') {
	if ($time_to_crawl < $time) {
	
	$save_timer_in_db = file_get_contents('http://'.$script_location.'/uniwebif/functions/save_timer_in_db.php'); 
	$sql = mysqli_query($dbmysqli, "UPDATE settings SET last_search_crawl = '$time'");
	}
	}
	// delete old timer
	if ($settings["delete_old_timer"] == '1')
	{
	$delete_old_timer = mysqli_query($dbmysqli, "DELETE FROM timer WHERE e2eventend < '$time' ");
	}
	
	// delete duplicate timer
	$delete_duplicate_timer = mysqli_query($dbmysqli, "DELETE FROM timer USING timer, timer AS Dup WHERE NOT timer.id = Dup.id AND timer.id > Dup.id AND timer.timer_request = Dup.timer_request");
	
	$sql = mysqli_query($dbmysqli, "OPTIMIZE TABLE timer");
	$sql = mysqli_query($dbmysqli, "OPTIMIZE TABLE saved_search");
	
	// delete old epg
	if ($settings["delete_old_epg"] == '1')
	{
	$time_to_delete = $time - $del_time;
	$delete_epg = mysqli_query($dbmysqli, "DELETE FROM epg_data WHERE e2eventstart < '$time_to_delete'");
	}
	
	// check record status
	$check_timer="SELECT * FROM timer";
	
	if ($result=mysqli_query($dbmysqli,$check_timer))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	$id = $obj->id;
	
	if ($time > $obj->e2eventend)
	{
	$record_status = 'c_expired'; }
	
	if ($time > $obj->e2eventstart and $time < $obj->e2eventend )
	{
	$record_status = 'a_recording'; }
	
	if ($time < $obj->e2eventstart and $time < $obj->e2eventend)
	{
	$record_status = 'b_incoming'; }

	$sql = mysqli_query($dbmysqli, "UPDATE timer SET record_status = '".$record_status."' WHERE `id` = '$id'"); }
	}
	}
	
	// send timer to receiver
	if ($settings["send_timer"] == '1')
		{
		$timer_request = "http://$script_location/uniwebif/functions/send_timer_to_box.php";
		$send_timer = file_get_contents($timer_request);
		}
	
	// start auto crawler
	if ($settings["epg_crawler"] == '1' and $settings["crawler_timestamp"] < $time)
		{
		$next_crawl = $settings["crawler_timestamp"] + 86400;
		$sql = mysqli_query($dbmysqli, "UPDATE settings set crawler_timestamp = '$next_crawl' WHERE `id` = 0");
		
		$epg_crawler = "http://$script_location/uniwebif/functions/crawler_cron.php";
		$start_crawling = file_get_contents($epg_crawler, false, $webrequest);
		}

	// start auto channel zapper
	if ($settings["cz_activate"] == '1')
		{				
		$cz_timestamp = $settings["cz_timestamp"];
		
		if ($cz_timestamp < $time)
		{
		$channel_zapper = "http://$script_location/uniwebif/functions/channelzapper.php";
		$start_zapping = file_get_contents($channel_zapper, false, $webrequest);
		}
}	
// close db
mysqli_close($dbmysqli);
//
?>
