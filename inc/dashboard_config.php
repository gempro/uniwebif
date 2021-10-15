<?php 
//
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	// sql settings
	$sql_host = 'localhost';
	$sql_user = 'uniwebif';
	$sql_pass = 'uniwebif';
	$sql_db = 'uniwebif';
			
	// connection
	@$dbmysqli = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db);
	
	if (mysqli_connect_errno()) {
	printf("SQL connection error: %s\n", mysqli_connect_error());
	exit();
	}
	
	// load settings from db
	$query = mysqli_query($dbmysqli, "SELECT * FROM `settings` WHERE `id` = '0' ");
	$result = mysqli_fetch_assoc($query);
	
	$box_ip = $result['box_ip'];
	$box_user = $result['box_user'];
	$box_password = $result['box_password'];
	$url_format = $result['url_format'];
	$server_ip = $result['server_ip'];
	$script_folder = $result['script_folder'];
	$activate_cron = $result['activate_cron'];
	$epg_entries_per_channel = $result['epg_entries_per_channel'] - 1;
	$channel_entries = $result['channel_entries'] - 1;
	$time_format = $result['time_format'];
	$epg_crawler = $result['epg_crawler'];
	$epg_crawler_activ = $result['epg_crawler_activ'];
	$crawler_timestamp = $result['crawler_timestamp'];
	$crawler_hour = $result['crawler_hour'];
	$crawler_minute = $result['crawler_minute'];
	$crawler_start = $result['crawler_start'];
	$crawler_end = $result['crawler_end'];
	$last_epg_crawl = $result['last_epg_crawl'];
	$last_epg = $result['last_epg'];
	$start_epg_crawler = $result['start_epg_crawler'];
	$after_crawl_action = $result['after_crawl_action'];
	$search_crawler = $result['search_crawler'];
	$last_search_crawl = $result['last_search_crawl'];
	$display_old_epg = $result['display_old_epg'];
	$streaming_symbol = $result['streaming_symbol'];
	$imdb_symbol = $result['imdb_symbol'];
	$timer_ticker = $result['timer_ticker'];
	$show_hidden_ticker = $result['show_hidden_ticker'];
	$ticker_time = $result['ticker_time'];
	$mark_searchterm = $result['mark_searchterm'];
	$send_timer = $result['send_timer'];
	$hide_old_timer = $result['hide_old_timer'];
	$delete_old_timer = $result['delete_old_timer'];
	$delete_receiver_timer = $result['delete_receiver_timer'];
	$delete_further_receiver_timer = $result['delete_further_receiver_timer'];
	$dummy_timer = $result['dummy_timer'];
	$dummy_timer_time = $result['dummy_timer_time'];
	$dummy_timer_current = $result['dummy_timer_current'];
	$delete_old_epg = $result['delete_old_epg'];
	$del_time = $result['del_time'];
	$reload_progressbar = $result['reload_progressbar'];
	$search_list_sort = $result['search_list_sort'];
	$extra_rec_time = $result['extra_rec_time'];
	$highlight_term = $result['highlight_term'];
	$cz_activate = $result['cz_activate'];
	$cz_wait_time = $result['cz_wait_time'];
	$cz_repeat = $result['cz_repeat'];
	$cz_hour = $result['cz_hour'];
	$cz_minute = $result['cz_minute'];
	$cz_am_pm = $result['cz_am_pm'];
	$cz_start_channel = $result['cz_start_channel'];
	$cz_timestamp = $result['cz_timestamp'];
	$cz_worktime = $result['cz_worktime'];
	$dur_down_broadcast = $result['dur_down_broadcast'];
	$dur_up_broadcast = $result['dur_up_broadcast'];
	$primetime = $result['primetime'];
	$dur_down_primetime = $result['dur_down_primetime'];
	$dur_up_primetime = $result['dur_up_primetime'];
	$del_m3u = $result['del_m3u'];
	$del_m3u_time = $result['del_m3u_time'];
	$sort_quickpanel = $result['sort_quickpanel'];
	//$current_git_push = $result['current_git_push'];

	// Webrequest
	$webrequest = stream_context_create(array (
	'http' => array (
	'method' => 'POST',
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	
	// time date
	$time = time();
	
	// check if token session is required
	$xmlfile = $url_format.'://'.$box_ip.'/web/session?sessionid=0';
	$session_info = @file_get_contents($xmlfile, false, $webrequest);
	$e2sessionid = simplexml_load_string($session_info);
	
	if($e2sessionid != '')
	{ 
	$session_part = '?sessionid='.$e2sessionid; 
	$session_part_2 = '&sessionid='.$e2sessionid; 
	} else { 
	$session_part = ''; 
	$session_part_2 = ''; 
	}
	
?>