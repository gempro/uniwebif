<?php 
session_start();
//
	if(!isset($_REQUEST['setting']) or $_REQUEST['setting'] == "") { $_REQUEST['setting'] = ""; }
	
	$setting = $_REQUEST['setting'];
	$url_format = "http";
	
	if(!isset($_REQUEST['sql_host']) or $_REQUEST['sql_host'] == "") { $_REQUEST['sql_host'] = ""; }
	if(!isset($_REQUEST['sql_user']) or $_REQUEST['sql_user'] == "") { $_REQUEST['sql_user'] = ""; }
	if(!isset($_REQUEST['sql_pass']) or $_REQUEST['sql_pass'] == "") { $_REQUEST['sql_pass'] = ""; }
	
	$sql_host = $_REQUEST['sql_host'];
	$sql_user = $_REQUEST['sql_user'];
	$sql_pass = $_REQUEST['sql_pass'];
	
	if(!isset($_REQUEST['receiver_ip']) or $_REQUEST['receiver_ip'] == "") { $_REQUEST['receiver_ip'] = ""; }
	if(!isset($_REQUEST['receiver_user']) or $_REQUEST['receiver_user'] == "") { $_REQUEST['receiver_user'] = ""; }
	if(!isset($_REQUEST['receiver_pass']) or $_REQUEST['receiver_pass'] == "") { $_REQUEST['receiver_pass'] = ""; }
	
	if(!isset($_REQUEST['server_ip']) or $_REQUEST['server_ip'] == "") { $_REQUEST['server_ip'] = ""; }
	if(!isset($_REQUEST['script_folder']) or $_REQUEST['script_folder'] == "") { $_REQUEST['script_folder'] = ""; }
	
	sleep(1);
	
	if($setting == 'sql'){
	
	if($sql_host == ''){ echo 'SQL Host is missing<br>'; }
	if($sql_user == ''){ echo 'SQL User is missing<br>'; }
	
	if($sql_host != '' and $sql_user != '')
	{ 
	// check sql connection
	@$dbmysqli = mysqli_connect($sql_host, $sql_user, $sql_pass);
	
	if (mysqli_connect_errno())
	{
	printf("%s\n", mysqli_connect_error());
	exit();
	
	} else {
	
	echo 'Connection OK!';
	
	$_SESSION["sql_host"] = $sql_host;
	$_SESSION["sql_user"] = $sql_user;
	$_SESSION["sql_pass"] = $sql_pass; 
	}
	}
	}
	
	// receiver
	if($setting == 'receiver'){
	$receiver_ip = $_REQUEST['receiver_ip'];
	$receiver_user = $_REQUEST['receiver_user'];
	$receiver_pass = $_REQUEST['receiver_pass'];
	
	if($receiver_ip == ''){ echo 'Receiver IP missing<br>'; }
	if($receiver_user == ''){ echo 'Receiver User missing<br>'; }
	if($receiver_pass == ''){ echo 'Receiver Password missing<br>'; }
	
	if($receiver_ip != '' and $receiver_user != '' and $receiver_pass != '')
	{
	$webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$receiver_user:$receiver_pass"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$request = $url_format.'://'.$receiver_ip.'/web/powerstate';
	$status = @file_get_contents($request, false, $webrequest);
	
	if($status == TRUE){
	
	echo 'Connection OK!';
	
	$_SESSION["receiver_ip"] = $receiver_ip;
	$_SESSION["receiver_user"] = $receiver_user;
	$_SESSION["receiver_pass"] = $receiver_pass;
	
	} else { 
	
	echo 'Connection Error!'; }
	}
	}
	

	if($setting == 'install'){
	
	$sql_host = $_SESSION["sql_host"];
	$sql_user = $_SESSION["sql_user"];
	$sql_pass = $_SESSION["sql_pass"];
	$sql_db = 'uniwebif';
	
	$receiver_ip = $_SESSION["receiver_ip"];
	$receiver_user = $_SESSION["receiver_user"];
	$receiver_pass = $_SESSION["receiver_pass"];
	
	$server_ip = $_REQUEST['server_ip'];
	$script_folder = $_REQUEST['script_folder'];
	
	// install tables
	@$dbmysqli = mysqli_connect($sql_host, $sql_user, $sql_pass);
	
	$query = mysqli_query($dbmysqli, "CREATE USER 'uniwebif'@'localhost' IDENTIFIED BY 'uniwebif'");
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`");
	$query = mysqli_query($dbmysqli, "CREATE DATABASE IF NOT EXISTS `uniwebif` DEFAULT CHARACTER SET utf8");
	$query = mysqli_query($dbmysqli, "GRANT ALL PRIVILEGES ON `uniwebif` . * TO 'uniwebif'@'localhost'");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`all_services`");
	$query = mysqli_query($dbmysqli, "
	
	--
	-- Database: `uniwebif`
	--
	-- --------------------------------------------------------
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`all_services` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `e2servicename` varchar(255) NOT NULL,
	  `servicename_enc` varchar(255) NOT NULL,
	  `e2servicereference` varchar(255) NOT NULL,
	  `service` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE = MYISAM  DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`bouquet_list`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`bouquet_list` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `e2servicereference` varchar(255) NOT NULL,
	  `e2servicename` varchar(255) NOT NULL,
	  `selected` int(1) NOT NULL DEFAULT '0',
	  `crawl` int(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE = MYISAM  DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`box_info`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`box_info` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `e2enigmaversion` varchar(255) NOT NULL,
	  `e2imageversion` varchar(255) NOT NULL,
	  `e2webifversion` varchar(255) NOT NULL,
	  `e2model` varchar(255) NOT NULL,
	  KEY `id` (`id`)
	) ENGINE = MYISAM  DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`channel_list`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`channel_list` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `e2servicename` varchar(255) NOT NULL,
	  `servicename_enc` varchar(255) NOT NULL,
	  `e2servicereference` varchar(255) NOT NULL,
	  `e2providername` varchar(255) NOT NULL,
	  `selected` int(1) NOT NULL DEFAULT '0',
	  `crawl` int(1) NOT NULL DEFAULT '1',
	  `zap` int(1) NOT NULL DEFAULT '0',
	  `zap_start` int(1) NOT NULL DEFAULT '0',
	  `cb_selected` int(1) NOT NULL,
	  `qp_selected` int(1) DEFAULT '1' NOT NULL,
	  `channel_hash` varchar(100) NOT NULL,
	  `last_crawl` int(12) NOT NULL,
	  `last_epg` int(12) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE = MYISAM  DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`device_list`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`device_list` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`device_description` varchar(255) NOT NULL,
	`device_ip` varchar(255) NOT NULL,
	`device_user` varchar(255) NOT NULL,
	`device_password` varchar(255) NOT NULL,
	`device_record_location` varchar(255) NOT NULL,
	`device_color` varchar(255) NOT NULL,
	`url_format` varchar(255) NOT NULL,
	`rec_location0` varchar(255) NOT NULL,
	`rec_location1` varchar(255) NOT NULL,
	`rec_location2` varchar(255) NOT NULL,
	`rec_location3` varchar(255) NOT NULL,
	`rec_location4` varchar(255) NOT NULL,
	`rec_location5` varchar(255) NOT NULL,
	`rec_location6` varchar(255) NOT NULL,
	`rec_location7` varchar(255) NOT NULL,
	`rec_location8` varchar(255) NOT NULL,
	`rec_location9` varchar(255) NOT NULL,
	KEY `id` (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`epg_data`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`epg_data` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `e2eventtitle` varchar(255) NOT NULL,
	  `title_enc` varchar(255) NOT NULL,
	  `e2eventservicename` varchar(255) NOT NULL,
	  `servicename_enc` varchar(255) NOT NULL,
	  `e2eventdescription` varchar(255) NOT NULL,
	  `description_enc` text NOT NULL,
	  `e2eventdescriptionextended` text NOT NULL,
	  `descriptionextended_enc` text NOT NULL,
	  `e2eventid` varchar(10) NOT NULL,
	  `start_date` varchar(255) NOT NULL,
	  `us_start_date` varchar(255) NOT NULL,
	  `start_day` varchar(2) NOT NULL,
	  `start_month` varchar(2) NOT NULL,
	  `start_year` varchar(4) NOT NULL,
	  `start_hour` varchar(2) NOT NULL,
	  `start_minute` varchar(2) NOT NULL,
	  `start_weekday` varchar(255) NOT NULL,
	  `end_date` varchar(255) NOT NULL,
	  `us_end_date` varchar(255) NOT NULL,
	  `end_day` varchar(2) NOT NULL,
	  `end_month` varchar(2) NOT NULL,
	  `end_year` varchar(4) NOT NULL,
	  `end_hour` varchar(2) NOT NULL,
	  `end_minute` varchar(2) NOT NULL,
	  `end_weekday` varchar(255) NOT NULL,
	  `total_min` varchar(3) NOT NULL,
	  `e2eventstart` varchar(12) NOT NULL,
	  `e2eventend` varchar(12) NOT NULL,
	  `e2eventduration` varchar(5) NOT NULL,
	  `e2eventcurrenttime` varchar(10) NOT NULL,
	  `e2eventservicereference` text NOT NULL,
	  `hd_channel` varchar(3) NOT NULL,
	  `crawler_time` int(12) NOT NULL,
	  `hash` varchar(50) NOT NULL,
	  `channel_hash` varchar(100) NOT NULL,
	  `timer` int(1) NOT NULL,
	  `timer_device` int(4) NOT NULL,
	  PRIMARY KEY (`id`),
	  FULLTEXT KEY `title_enc` (`title_enc`),
	  FULLTEXT KEY `description_enc` (`description_enc`),
	  FULLTEXT KEY `descriptionextended_enc` (`descriptionextended_enc`),
	  FULLTEXT KEY `epgsearch_enc` (`title_enc`,`description_enc`,`descriptionextended_enc`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`ignore_list`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`ignore_list` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`e2eventtitle` varchar(255) NOT NULL,
	`e2eventdescription` varchar(255) NOT NULL,
	`search_term` varchar(255) NOT NULL,
	`timestamp` int(12) NOT NULL, 
	`hash` varchar(255) NOT NULL,
	`activ` int(1) NOT NULL, 
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`keywords`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`keywords` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`searchterm` varchar(255) NOT NULL,
	`word` varchar(255) NOT NULL,
	`sum_total` int(4) NOT NULL,
	`sum_title` int(4) NOT NULL,
	`sum_description` int(4) NOT NULL,
	`sum_extdescription` int(4) NOT NULL,
	`hash` varchar(255) NOT NULL,
	`timestamp` int(11) NOT NULL DEFAULT '1',
	`activ` int(1) NOT NULL DEFAULT '1',
	`counter` int(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	");

	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`record_locations`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`record_locations` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `e2location` varchar(255) NOT NULL,
	  `selected` int(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`saved_search`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`saved_search` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `searchterm` varchar(255) NOT NULL,
	  `search_option` varchar(255) NOT NULL,
	  `exclude_channel` text NOT NULL,
	  `exclude_title` text NOT NULL,
	  `exclude_description` text NOT NULL,
	  `exclude_extdescription` text NOT NULL,
	  `e2location` varchar(255) NOT NULL,
	  `save_date` int(12) NOT NULL,
	  `last_change` int(12) NOT NULL,
	  `last_crawl` int(12) NOT NULL,
	  `crawled` int(1) NOT NULL DEFAULT '0',
	  `e2eventservicereference` varchar(255) NOT NULL,
	  `e2eventservicename` varchar(255) NOT NULL,
	  `servicename_enc` varchar(255) NOT NULL,
	  `activ` varchar(255) NOT NULL,
	  `action` varchar(255) NOT NULL,
	  `rec_replay` varchar(255) NOT NULL DEFAULT 'off',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`settings`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`settings` (
	  `id` int(1) NOT NULL,
	  `box_ip` varchar(255) NOT NULL,
	  `box_user` varchar(255) NOT NULL,
	  `box_password` varchar(255) NOT NULL,
	  `url_format` varchar(5) NOT NULL DEFAULT 'http',
	  `server_ip` varchar(255) NOT NULL,
	  `script_folder` varchar(255) NOT NULL DEFAULT 'uniwebif',
	  `activate_cron` int(1) NOT NULL DEFAULT '0',
	  `epg_entries_per_channel` int(6) NOT NULL DEFAULT '250',
	  `channel_entries` int(6) NOT NULL DEFAULT '100',
	  `time_format` int(1) NOT NULL DEFAULT '2',
	  `epg_crawler` int(1) NOT NULL DEFAULT '0',
	  `epg_crawler_activ` int(1) NOT NULL DEFAULT '0',
	  `crawler_timestamp` int(12) NOT NULL,
	  `crawler_hour` varchar(2) NOT NULL,
	  `crawler_minute` varchar(2) NOT NULL,
	  `crawler_start` int(12) NOT NULL,
	  `crawler_end` int(12) NOT NULL,
	  `last_epg_crawl` int(12) NOT NULL,
	  `last_epg` int(12) NOT NULL,
	  `start_epg_crawler` int(4) NOT NULL DEFAULT '50',
	  `after_crawl_action` int(1) NOT NULL DEFAULT '0',
	  `search_crawler` int(1) NOT NULL DEFAULT '0',
	  `last_search_crawl` int(12) NOT NULL,
	  `display_old_epg` int(1) NOT NULL DEFAULT '0',
	  `streaming_symbol` int(1) NOT NULL DEFAULT '1',
	  `imdb_symbol` int(1) NOT NULL DEFAULT '1',
	  `timer_ticker` int(1) NOT NULL DEFAULT '1',
	  `show_hidden_ticker` int(1) NOT NULL DEFAULT '0',
	  `ticker_time` int(6) NOT NULL DEFAULT '604800',
	  `mark_searchterm` int(1) NOT NULL DEFAULT '1',
	  `send_timer` int(1) NOT NULL DEFAULT '0',
	  `hide_old_timer` int(1) NOT NULL DEFAULT '1',
	  `delete_old_timer` int(1) NOT NULL DEFAULT '1',
	  `delete_receiver_timer` int(1) NOT NULL DEFAULT '0',
	  `delete_further_receiver_timer` int(1) NOT NULL DEFAULT '0',
	  `dummy_timer` int(1) NOT NULL DEFAULT '0',
	  `dummy_timer_time` int(12) NOT NULL,
	  `dummy_timer_current` int(12) NOT NULL,
	  `delete_old_epg` int(1) NOT NULL DEFAULT '1',
	  `del_time` int(5) NOT NULL DEFAULT '86400',
	  `reload_progressbar` int(1) NOT NULL DEFAULT '0',
	  `search_list_sort` varchar(255) NOT NULL DEFAULT 'id',
	  `extra_rec_time` int(4) NOT NULL DEFAULT '0',
	  `cz_activate` int(1) NOT NULL DEFAULT '0',
	  `cz_wait_time` int(2) NOT NULL DEFAULT '15',
	  `cz_repeat` varchar(10) NOT NULL,
	  `cz_hour` varchar(2) NOT NULL,
	  `cz_minute` varchar(2) NOT NULL,
	  `cz_am_pm` varchar(2) NOT NULL NOT NULL,
	  `cz_start_channel` varchar(50) NOT NULL,
	  `cz_timestamp` varchar(12) NOT NULL DEFAULT '0',
	  `cz_worktime` varchar(12) NOT NULL DEFAULT '0',
	  `dur_down_broadcast` int(4) NOT NULL DEFAULT '300',
	  `dur_up_broadcast` int(4) NOT NULL DEFAULT '1800',
	  `primetime` int(12) NOT NULL DEFAULT '0',
	  `dur_down_primetime` int(4) NOT NULL DEFAULT '0',
	  `dur_up_primetime` int(5) NOT NULL DEFAULT '7200',
	  `del_m3u` int(1) NOT NULL DEFAULT '0',
	  `del_m3u_time` int(12) NOT NULL DEFAULT '0',
	  `sort_quickpanel` varchar(255) NOT NULL DEFAULT 'e2servicename',
	  `current_git_push` int(12) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	");
	
	$query = mysqli_query($dbmysqli, "INSERT INTO `uniwebif`.`settings` (`box_ip`, `box_user`, `box_password`, `server_ip`, `script_folder`) VALUES
	('".$receiver_ip."', '".$receiver_user."', '".$receiver_pass."', '".$server_ip."', '".$script_folder."')");
	
	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `uniwebif`.`timer`");
	$query = mysqli_query($dbmysqli, "
	
	CREATE TABLE IF NOT EXISTS `uniwebif`.`timer` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `e2eventtitle` varchar(255) NOT NULL,
	  `title_enc` varchar(255) NOT NULL,
	  `e2eventdescription` varchar(255) NOT NULL,
	  `description_enc` varchar(255) NOT NULL,
	  `e2eventdescriptionextended` text NOT NULL,
	  `descriptionextended_enc` text NOT NULL,
	  `e2eventservicename` varchar(255) NOT NULL,
	  `servicename_enc` varchar(255) NOT NULL,
	  `e2eventservicereference` varchar(255) NOT NULL,
	  `search_term` varchar(255) NOT NULL,
	  `search_option` varchar(255) NOT NULL,
	  `exclude_channel` text NOT NULL,
	  `exclude_title` text NOT NULL,
	  `exclude_description` text NOT NULL,
	  `exclude_extdescription` text NOT NULL,
	  `record_location` varchar(255) NOT NULL,
	  `e2eventstart` varchar(12) NOT NULL,
	  `e2eventend` varchar(12) NOT NULL,
	  `timer_request` text NOT NULL,
	  `hash` varchar(50) NOT NULL,
	  `channel_hash` varchar(100) NOT NULL,
	  `status` varchar(255) NOT NULL,
	  `record_status` varchar(255) NOT NULL,
	  `show_ticker` int(1) NOT NULL DEFAULT '1',
	  `rec_replay` varchar(255) NOT NULL,
	  `is_replay` int(1) NOT NULL,
	  `expired` int(1) NOT NULL DEFAULT '0',
	  `hide` int(1) NOT NULL DEFAULT '0',
	  `device` int(4) NOT NULL,
	  `search_id` int(4) NOT NULL,
	  `conflict` int(1) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	");
	
	echo 'SQL Installation OK!';
	
	// get bouquets from receiver
	$crawl_bouquet = $url_format.'://'.$server_ip.'/'.$script_folder.'/functions/bouquet_crawler.php';
	$start_crawl = @file_get_contents($crawl_bouquet);
	
	// get record locations from receiver
	$crawl_rec_locations = $url_format.'://'.$server_ip.'/'.$script_folder.'/functions/save_rec_locations.php';
	$start_crawl = @file_get_contents($crawl_rec_locations);
	
	// get receiver info
	$crawl_receiver_info = $url_format.'://'.$server_ip.'/'.$script_folder.'/functions/save_box_settings.php';
	$start_crawl = @file_get_contents($crawl_receiver_info);
	
	}
?>