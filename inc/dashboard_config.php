<?php 
//	
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
// ip from host
	$script_location = '10.0.0.84';
			
// mysqli
	@$dbmysqli = mysqli_connect("localhost", "uniwebif", "uniwebif", "uniwebif");
	
	if (mysqli_connect_errno()) {
	printf("SQL connection error: %s\n", mysqli_connect_error());
	exit();
	}
	
// load settings from db
	$query = mysqli_query($dbmysqli, "SELECT * FROM settings WHERE id = 0");
	$result = mysqli_fetch_assoc($query);
	
	$box_ip = $result['box_ip'];
	$box_user = $result['box_user'];
	$box_password = $result['box_password'];
	$epg_entries_per_channel = $result['epg_entries_per_channel'] - 1;
	$channel_entries = $result['channel_entries'] - 1;
	$time_format = $result['time_format'];
	if(!isset($time_format) or $time_format == "") { $time_format = "2"; } else { $time_format = $time_format; }
	$start_epg_crawler = $result['start_epg_crawler'];
	$after_crawl_action = $result['after_crawl_action'];
	$delete_old_timer = $result['delete_old_timer'];
	$delete_receiver_timer = $result['delete_receiver_timer'];
	$dummy_timer = $result['dummy_timer'];
	$dummy_timer_time = $result['dummy_timer_time'];
	$dummy_timer_current = $result['dummy_timer_current'];
	$url_format = $result['url_format'];
	$display_old_epg = $result['display_old_epg'];
	$streaming_symbol = $result['streaming_symbol'];
	$imdb_symbol = $result['imdb_symbol'];
	$timer_ticker = $result['timer_ticker'];
	$ticker_time = $result['ticker_time'];
	$del_time = $result['del_time'];
	$reload_progressbar1 = $result['reload_progressbar1'];
	$extra_rec_time = $result['extra_rec_time'];
	$start_epg_crawler = $result['start_epg_crawler'];
	$cz_sleeptime = $result['cz_wait_time'];
	$mark_searchterm = $result['mark_searchterm'];
	$dur_down_broadcast = $result['dur_down_broadcast'];
	$dur_up_broadcast = $result['dur_up_broadcast'];
	$dur_down_primetime = $result['dur_down_primetime'];
	$dur_up_primetime = $result['dur_up_primetime'];
	$cz_start_channel = $result['cz_start_channel'];
	$crawler_timestamp = $result['crawler_timestamp'];

// Webrequest
	$webrequest = stream_context_create(array (
		'http' => array (
			'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
			'ssl' =>array (
			'verify_peer' => false,
			'verify_peer_name' => false,
			)
		)
	));
	
	// time date
	$time = time();
	$thedate = date("d.m.Y");
	$thetime = date("H:i:s");

?>