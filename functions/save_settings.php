<?php 
//
include("../inc/dashboard_config.php");
	
	//recieve data
	$server_ip = $_REQUEST['server_ip'];
	$script_folder = $_REQUEST['script_folder'];
	$activate_cron = $_REQUEST['activate_cron'];
	$epg_entries_per_channel = $_REQUEST['epg_entries_per_channel'];
	$channel_entries = $_REQUEST['channel_entries'];
	$dur_down_broadcast = $_REQUEST['dur_down_broadcast'];
	$dur_up_broadcast = $_REQUEST['dur_up_broadcast'];
	$dur_down_primetime = $_REQUEST['dur_down_primetime'];
	$dur_up_primetime = $_REQUEST['dur_up_primetime'];
	$time_format = $_REQUEST['time_format'];
	$epg_crawler = $_REQUEST['epg_crawler'];
	$crawler_hour = $_REQUEST['crawler_hour'];
	$crawler_minute = $_REQUEST['crawler_minute'];
	$crawler_am_pm = $_REQUEST['crawler_am_pm'];
	$search_crawler = $_REQUEST['search_crawler'];
	$start_epg_crawler = $_REQUEST['start_epg_crawler'];
	$display_old_epg = $_REQUEST['display_old_epg'];
	$streaming_symbol = $_REQUEST['streaming_symbol'];
	$imdb_symbol = $_REQUEST['imdb_symbol'];
	$timer_ticker = $_REQUEST['timer_ticker'];
	$show_hidden_ticker = $_REQUEST['show_hidden_ticker'];
	$ticker_time = $_REQUEST['ticker_time'];
	$mark_searchterm = $_REQUEST['mark_searchterm'];
	$send_timer = $_REQUEST['send_timer'];
	$hide_old_timer = $_REQUEST['hide_old_timer'];
	$delete_old_timer = $_REQUEST['delete_old_timer'];
	$delete_receiver_timer = $_REQUEST['delete_receiver_timer'];
	$delete_further_receiver_timer = $_REQUEST['delete_further_receiver_timer'];
	$dummy_timer = $_REQUEST['dummy_timer'];
	$after_crawl_action = $_REQUEST['after_crawl_action'];
	$delete_old_epg = $_REQUEST['delete_old_epg'];
	$url_format = $_REQUEST['url_format'];
	$del_m3u = $_REQUEST['del_m3u'];
	$sort_quickpanel = $_REQUEST['sort_quickpanel'];
	$del_time = $_REQUEST['del_time'];
	$reload_progressbar = $_REQUEST['reload_progressbar'];
	$extra_rec_time = $_REQUEST['extra_rec_time'];
	$cz_activate = $_REQUEST['cz_activate'];
	$cz_wait_time = $_REQUEST['cz_wait_time'];
	$cz_hour = $_REQUEST['cz_hour'];
	$cz_minute = $_REQUEST['cz_minute'];
	$cz_repeat = $_REQUEST['cz_repeat'];
	$cz_am_pm = $_REQUEST['cz_am_pm'];
	$cz_start_channel = $_REQUEST['cz_start_channel'];
	
	
	if($crawler_am_pm == '0'){ $crawler_am_pm = ''; }
	
	if(!is_numeric($cz_wait_time)){ $cz_wait_time = '30'; }
	if(!is_numeric($cz_hour) or $cz_hour > 23){ $cz_hour = '12'; }
	if(!is_numeric($cz_minute) or $cz_minute > 59){ $cz_minute = '00'; }

	if($cz_am_pm == '0'){ $cz_am_pm = ''; }
	
	$date_for_cz = date("d.m.Y ");
	$cz_start = $date_for_cz.$cz_hour.':'.$cz_minute.$cz_am_pm;
	if(strtotime($cz_start) > $time){ $cz_timestamp = strtotime($cz_start); } else { $cz_timestamp = strtotime($cz_start) + 86400; }

	if(!is_numeric($crawler_hour) or $crawler_hour > 23) { $crawler_hour = '12'; }
	if(!is_numeric($crawler_minute) or $crawler_minute > 59) { $crawler_minute = '00'; }

	$date_for_crawler = date("d.m.Y ");
	$crawler_start = $date_for_crawler.$crawler_hour.':'.$crawler_minute.$crawler_am_pm;
	if(strtotime($crawler_start) > $time){ $crawler_timestamp = strtotime($crawler_start); } else { $crawler_timestamp = strtotime($crawler_start) + 86400; }
	
	if(!isset($epg_entries_per_channel) or $epg_entries_per_channel == "" or !isset($channel_entries) or $channel_entries == "") 
	{ 
	echo "data missed"; 
	
	} else {
	
	$sql = mysqli_query($dbmysqli, "
	UPDATE `settings` SET 
	server_ip = '$server_ip', 
	script_folder = '$script_folder', 
	activate_cron = '$activate_cron', 
	epg_entries_per_channel = '$epg_entries_per_channel', 
	channel_entries = '$channel_entries', 
	dur_down_broadcast = '$dur_down_broadcast', 
	dur_up_broadcast = '$dur_up_broadcast', 
	dur_down_primetime = '$dur_down_primetime', 
	dur_up_primetime = '$dur_up_primetime', 
	time_format = '$time_format', 
	epg_crawler = '$epg_crawler', 
	crawler_timestamp = '$crawler_timestamp', 
	crawler_hour = '$crawler_hour', 
	crawler_minute = '$crawler_minute', 
	search_crawler = '$search_crawler', 
	start_epg_crawler = '$start_epg_crawler', 
	display_old_epg = '$display_old_epg', 
	streaming_symbol = '$streaming_symbol', 
	imdb_symbol = '$imdb_symbol', 
	timer_ticker = '$timer_ticker', 
	show_hidden_ticker = '$show_hidden_ticker', 
	ticker_time = '$ticker_time', 
	mark_searchterm = '$mark_searchterm', 
	send_timer = '$send_timer', 
	hide_old_timer = '$hide_old_timer', 
	delete_old_timer = '$delete_old_timer', 
	delete_receiver_timer = '$delete_receiver_timer', 
	delete_further_receiver_timer = '$delete_further_receiver_timer', 
	dummy_timer = '$dummy_timer', 
	after_crawl_action = '$after_crawl_action', 
	delete_old_epg = '$delete_old_epg', 
	url_format = '$url_format', 
	del_m3u = '$del_m3u', 
	sort_quickpanel = '$sort_quickpanel', 
	del_time = '$del_time', 
	reload_progressbar = '$reload_progressbar', 
	extra_rec_time = '$extra_rec_time', 
	cz_activate = '$cz_activate', 
	cz_wait_time = '$cz_wait_time', 
	cz_repeat = '$cz_repeat', 
	cz_hour = '$cz_hour', 
	cz_minute = '$cz_minute', 
	cz_am_pm = '$cz_am_pm', 
	cz_start_channel = '$cz_start_channel',
	cz_timestamp = '$cz_timestamp' WHERE `id` = '0' ");
	
	$sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `zap_start` = '0' ");
	$sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `zap_start` = '1' WHERE `e2servicereference` = '$cz_start_channel' ");
	
	if($hide_old_timer == '0')
	{
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `expired` = '0' WHERE `e2eventend` < '$time' AND `expired` = '1' ");
	}
	if($hide_old_timer == '1')
	{
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `expired` = '1' WHERE `e2eventend` < '$time' AND `expired` NOT LIKE '1' ");
	}
	
	sleep(1);
	
	echo "ok";

	}

?>