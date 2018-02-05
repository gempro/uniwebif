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
	$query = mysqli_query($dbmysqli, "SELECT * FROM `settings` WHERE id = '0' ");
	$result = mysqli_fetch_assoc($query);
	
	$box_ip = $result['box_ip'];
	$box_user = $result['box_user'];
	$box_password = $result['box_password'];
	$url_format = $result['url_format'];
	$server_ip = $result['server_ip'];
	$script_folder = $result['script_folder'];
	$epg_entries_per_channel = $result['epg_entries_per_channel'] - 1;
	$channel_entries = $result['channel_entries'] - 1;
	$time_format = $result['time_format'];
	$start_epg_crawler = $result['start_epg_crawler'];
	$after_crawl_action = $result['after_crawl_action'];
	$delete_old_timer = $result['delete_old_timer'];
	$delete_receiver_timer = $result['delete_receiver_timer'];
	$dummy_timer = $result['dummy_timer'];
	$dummy_timer_time = $result['dummy_timer_time'];
	$dummy_timer_current = $result['dummy_timer_current'];
	$display_old_epg = $result['display_old_epg'];
	$streaming_symbol = $result['streaming_symbol'];
	$imdb_symbol = $result['imdb_symbol'];
	$timer_ticker = $result['timer_ticker'];
	$show_hidden_ticker = $result['show_hidden_ticker'];
	$ticker_time = $result['ticker_time'];
	$del_time = $result['del_time'];
	$reload_progressbar = $result['reload_progressbar'];
	$search_list_sort = $result['search_list_sort'];
	$extra_rec_time = $result['extra_rec_time'];
	$cz_sleeptime = $result['cz_wait_time'];
	$mark_searchterm = $result['mark_searchterm'];
	$dur_down_broadcast = $result['dur_down_broadcast'];
	$dur_up_broadcast = $result['dur_up_broadcast'];
	$dur_down_primetime = $result['dur_down_primetime'];
	$dur_up_primetime = $result['dur_up_primetime'];
	$cz_start_channel = $result['cz_start_channel'];
	$cz_timestamp = $result['cz_timestamp'];
	$crawler_timestamp = $result['crawler_timestamp'];
	$primetime = $result['primetime'];

	// Webrequest
	$webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	
	// time date
	$time = time();
	$thedate = date("d.m.Y");
	$thetime = date("H:i:s");
	
?>