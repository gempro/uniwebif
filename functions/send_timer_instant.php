<?php 
//
	include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ''){ $_REQUEST['action'] = ''; }
	if(!isset($_REQUEST['location']) or $_REQUEST['location'] == ''){ $_REQUEST['location'] = ''; }
	if(!isset($_REQUEST['hash']) or $_REQUEST['hash'] == ''){ $_REQUEST['hash'] = ''; }
	if(!isset($_REQUEST['timer_id']) or $_REQUEST['timer_id'] == ''){ $_REQUEST['timer_id'] = ''; }
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == ''){ $_REQUEST['record_location'] = ''; }
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == ''){ $_REQUEST['device'] = ''; }
	
	$action = $_REQUEST['action'];
	$location = $_REQUEST['location'];
	$hash = $_REQUEST['hash'];
	$timer_id = $_REQUEST['timer_id'];
	$record_location = $_REQUEST['record_location'];
	$device = $_REQUEST['device'];
	$timer_repeat = 0;
	
	// manual timer
	if($action == 'manual_timer')
	{
	sleep(1);
	
	if(!isset($_REQUEST['title']) or $_REQUEST['title'] == ''){ $_REQUEST['title'] = ''; }
	if(!isset($_REQUEST['description']) or $_REQUEST['description'] == ''){ $_REQUEST['description'] = ''; }
	if(!isset($_REQUEST['am_pm_start']) or $_REQUEST['am_pm_start'] == ''){ $_REQUEST['am_pm_start'] = ''; }
	if(!isset($_REQUEST['am_pm_end']) or $_REQUEST['am_pm_end'] == ''){ $_REQUEST['am_pm_end'] = ''; }
	if(!isset($_REQUEST['repeat_days']) or $_REQUEST['repeat_days'] == ''){ $_REQUEST['repeat_days'] = ''; }
	if(!isset($_REQUEST['weekday']) or $_REQUEST['weekday'] == ''){ $_REQUEST['weekday'] = ''; }
	
	$timer = $_REQUEST['timer'];
	$service_name = $_REQUEST['service_name'];
	$service_reference = $_REQUEST['service_reference'];
	$device = $_REQUEST['device'];
	$record_location = $_REQUEST['record_location'];
	$title = $_REQUEST['title'];
	$description = $_REQUEST['description'];
	$start_day = $_REQUEST['start_day'];
	$start_month = $_REQUEST['start_month'];
	$start_year = $_REQUEST['start_year'];
	$start_hour = $_REQUEST['start_hour'];
	$start_minute = $_REQUEST['start_minute'];
	$am_pm_start = $_REQUEST['am_pm_start'];
	$end_day = $_REQUEST['end_day'];
	$end_month = $_REQUEST['end_month'];
	$end_year = $_REQUEST['end_year'];
	$end_hour = $_REQUEST['end_hour'];
	$end_minute = $_REQUEST['end_minute'];
	$am_pm_end = $_REQUEST['am_pm_end'];
	$repeat_days = $_REQUEST['repeat_days'];
	$weekday = $_REQUEST['weekday'];
	
	$error_message = "<i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i> An error occured\n\n";
	
	if($am_pm_start and $am_pm_end != '')
	{
	if($am_pm_start == 'PM'){ $start_hour = $start_hour + 12; }
	if($am_pm_end == 'PM'){ $end_hour = $end_hour + 12; }
	}
	
	$title = str_replace('\'', '', $title);
	$description = str_replace('\'', '', $description);
	$title_enc = rawurlencode($title);
	$description_enc = rawurlencode($description);
	$servicename_enc = rawurlencode($service_name);
	
	$e2eventstart = strtotime($start_day.'.'.$start_month.'.'.$start_year.', '.$start_hour.':'.$start_minute.':00');
	$e2eventend = strtotime($end_day.'.'.$end_month.'.'.$end_year.', '.$end_hour.':'.$end_minute.':00');
	
	if($repeat_days != '')
	{
	$days = explode(';' , $repeat_days);
	foreach($days as $i => $key){ $i > 0; $timer_repeat = $timer_repeat + $key; }
	$next_weekday = strtotime('next '.$weekday);
	if(date('d.m.Y', $e2eventstart) == date('d.m.Y', time())){ $next_weekday = time(); }
	$e2eventstart = strtotime(date('d.m.Y, '.$start_hour.':'.$start_minute, $next_weekday));
	$e2eventend = strtotime(date('d.m.Y, '.$end_hour.':'.$end_minute, $next_weekday));
	}
	
	$hash = hash('md4',$servicename_enc.$e2eventstart.$e2eventend.$device);
	$channel_hash = hash('md4',$service_name);
	
	// different device
	if($device != '0')
	{
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result = mysqli_fetch_assoc($sql);
	$box_ip = $result['device_ip'];
	$box_user = $result['device_user'];
	$box_password = $result['device_password'];
	$url_format = $result['url_format'];
	
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
	
	// check if token session is required
	$xmlfile = $url_format.'://'.$box_ip.'/web/session?sessionid=0';
	$session_info = @file_get_contents($xmlfile, false, $webrequest);
	$e2sessionid = simplexml_load_string($session_info);
	
	if($e2sessionid != ''){ $session_part_2 = '&sessionid='.$e2sessionid; } else { $session_part_2 = ''; }
	} // different device
	
	// record location
	$sql = mysqli_query($dbmysqli, "SELECT `e2location` FROM `record_locations` WHERE `id` = '".$record_location."' ");
	$result = mysqli_fetch_assoc($sql);
	$record_location = $result['e2location'];
	
	if($timer == 'record')
	{
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$service_reference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$record_location.'&afterevent=3&repeated='.$timer_repeat.$session_part_2;
	}
	
	if($timer == 'zap')
	{
	$record_location = 'zap_timer';
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$service_reference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&justplay=1&repeated='.$timer_repeat.$session_part_2;
	}
	
	if($e2eventend < $e2eventstart){ echo $error_message; exit; }
	
	// timer conflict
	$get_timer_status = @file_get_contents($timer_request, false, $webrequest);
	$xml = simplexml_load_string($get_timer_status);
	if(!isset($xml) or $xml == ''){ echo $error_message; exit; }
	$timer_status = $xml->e2state;
	
	if(preg_match('/\btrue\b/i', $timer_status))
	{
	echo "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Timer sent\n\n"; 
	} else { 
	echo $error_message; 
	exit;
	}
	
	if($time > $e2eventstart and $time < $e2eventend){ $record_status = 'a_recording'; }
	if($time < $e2eventstart and $time < $e2eventend){ $record_status = 'b_incoming'; }	
	if($time > $e2eventend){ $record_status = 'c_expired'; }
	
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `timer` WHERE `hash` LIKE '".$hash."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($summary == 0)
	{
	mysqli_query($dbmysqli, "INSERT INTO `timer` 
	(
	e2eventtitle, 
	title_enc, 
	e2eventdescription, 
	description_enc, 
	e2eventservicename, 
	servicename_enc, 
	e2eventservicereference, 
	record_location, 
	e2eventstart, 
	e2eventend, 
	timer_request, 
	hash, 
	channel_hash, 
	status, 
	record_status, 
	device, 
	conflict, 
	timer_repeat, 
	timer_repeat_d 
	) VALUES (
	'".utf8_decode($title)."', 
	'".$title_enc."', 
	'".utf8_decode($description)."', 
	'".$description_enc."', 
	'".$service_name."', 
	'".$servicename_enc."', 
	'".$service_reference."', 
	'".$record_location."', 
	'".$e2eventstart."', 
	'".$e2eventend."', 
	'".$timer_request."', 
	'".$hash."', 
	'".$channel_hash."', 
	'manual', 
	'".$record_status."', 
	'".$device."', 
	'0', 
	'".$timer_repeat."', 
	'".$repeat_days."'
	)
	");
	}
	
	exit;

	} // manual timer
	
	# timerlist
	if($location == 'timerlist')
	{
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$timer_id."' ");
	} else {
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `epg_data` WHERE `hash` = '".$hash."' ");
	}
	$result = mysqli_fetch_assoc($sql);
	$e2eventtitle = $result['e2eventtitle'];
	$title_enc = $result['title_enc'];
	$e2eventservicename = $result['e2eventservicename'];
	$servicename_enc = $result['servicename_enc'];
	$e2eventdescription = $result['e2eventdescription'];
	$description_enc = $result['description_enc'];
	$e2eventdescriptionextended = $result['e2eventdescriptionextended'];
	$descriptionextended_enc = $result['descriptionextended_enc'];
	//$e2eventid = $result['e2eventid'];
	$e2eventstart = $result['e2eventstart'];
	$e2eventend = $result['e2eventend'];
	$e2eventservicereference = $result['e2eventservicereference'];
	$channel_hash = $result['channel_hash'];
	
	if($location == 'timerlist')
	{
	$current_device = $result['device']; 
	$search_term = $result['search_term'];
	$search_option = $result['search_option'];
	$search_id = $result['search_id'];
	$exclude_channel = $result['exclude_channel'];
	$exclude_title = $result['exclude_title'];
	$exclude_description = $result['exclude_description'];
	$exclude_extdescription = $result['exclude_extdescription'];
	$rec_replay = $result['rec_replay'];
	$timer_repeat = $result['timer_repeat'];
	$timer_repeat_d = $result['timer_repeat_d'];
	}
	
	// record location
	$sql_2 = mysqli_query($dbmysqli, "SELECT `e2location` FROM `record_locations` WHERE `id` = '".$record_location."' ");
	$result_2 = mysqli_fetch_assoc($sql_2);
	$e2location = $result_2['e2location'];
	
	// mark epg entry
	mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '1' WHERE `hash` = '".$hash."' ");
	
	// additional record time
	$e2eventend = $e2eventend + $extra_rec_time;
	
	if($location == 'timerlist'){ $e2eventend = $result['e2eventend']; }
	
	### different device
	if($device != '0')
	{
	$sql_3 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result_3 = mysqli_fetch_assoc($sql_3);
	$box_ip = $result_3['device_ip'];
	$box_user = $result_3['device_user'];
	$box_password = $result_3['device_password'];
	$url_format = $result_3['url_format'];
	
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
	
	// check if token session is required
	$xmlfile = $url_format.'://'.$box_ip.'/web/session?sessionid=0';
	$session_info = @file_get_contents($xmlfile, false, $webrequest);
	$e2sessionid = simplexml_load_string($session_info);
	
	if($e2sessionid != ''){ $session_part_2 = '&sessionid='.$e2sessionid; } else { $session_part_2 = ''; }
	
	if($action == 'record')
	{
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$e2location.'&afterevent=3&repeated='.$timer_repeat.$session_part_2;
	}
	
	if($action == 'zap')
	{
	$e2location = 'zap_timer';
	if($timer_repeat == '0')
	{
	$e2eventstart = $e2eventstart - 1;
	$e2eventend = $e2eventstart + 1;
	}
	
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&justplay=1&repeated='.$timer_repeat.$session_part_2;
	}
	
	// remove " and ' from request
	$timer_request = str_replace('%22', '%60', $timer_request);
	$timer_request = str_replace('%27', '%60', $timer_request);

	// request
	$get_timer_status = @file_get_contents($timer_request, false, $webrequest);
	$xml = simplexml_load_string($get_timer_status);
	if(!isset($xml) or $xml == ''){ $xml = ''; echo '<i class="glyphicon glyphicon-remove fa-1x" style="color:#D9534F"></i> An error occured'; exit; }
	
	$timer_status = $xml->e2state;
	
	if(preg_match('/\btrue\b/i', $timer_status))
	{
	$timer_status = ''; 
	$timer_conflict = '0';	
	} else { 
	$timer_status = ' - <span class="timer_conflict">Conflict on Receiver</span>';
	$timer_conflict = '1';
	}
	} // different device 
	
	else {
	
	### default device
	if($action == 'record')
	{
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$e2location.'&afterevent=3&repeated='.$timer_repeat.$session_part_2;
	}
	
	if($action == 'zap')
	{
	$e2location = 'zap_timer';
	if($timer_repeat == '0')
	{
	$e2eventstart = $e2eventstart - 1;
	$e2eventend = $e2eventstart + 1;
	}
	
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&justplay=1&repeated='.$timer_repeat.$session_part_2;
	}
	
	// request with eventid
	//$timer_request = "http://$box_ip/web/timeraddbyeventid?sRef=".$e2eventservicereference."&eventid=".$e2eventid."&dirname=".$e2location."";
	
	$timer_request = str_replace('%22', '%60', $timer_request);
	$timer_request = str_replace('%27', '%60', $timer_request);
	
	// request
	$get_timer_status = @file_get_contents($timer_request, false, $webrequest);
	$xml = simplexml_load_string($get_timer_status);
	$timer_status = $xml->e2state;
	
	if(preg_match('/\btrue\b/i', $timer_status))
	{ 
	$timer_status = ''; 
	$timer_conflict = '0';
	} else { 
	$timer_status = ' - <span class="timer_conflict">Conflict on Receiver</span>'; 
	$timer_conflict = '1';
	}
	
	} // default device
	
	if($time > $e2eventstart and $time < $e2eventend){ $record_status = 'a_recording'; }
	if($time < $e2eventstart and $time < $e2eventend){ $record_status = 'b_incoming'; }	
	if($time > $e2eventend){ $record_status = 'c_expired'; }

	// set timer status
	if($location == 'timerlist' and $device == $current_device and $action == 'record')
	{
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'manual', `record_location` = '".$e2location."', `conflict` = '".$timer_conflict."' WHERE `id` = '".$timer_id."' ");
	}
	
	// create zap timer
	if($action == 'zap')
	{
	$sql_6 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `timer` WHERE `id` = '".$timer_id."' AND `device` = '".$device."' AND `record_location` LIKE 'zap_timer' ");
	$result_6 = mysqli_fetch_row($sql_6);
	
	if($result_6[0] == 1)
	{ 
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'manual', `conflict` = '".$timer_conflict."' WHERE `id` = '".$timer_id."' AND `record_location` LIKE 'zap_timer' "); 
	
	} else {
	
	if($location == 'timerlist')
	{
	mysqli_query($dbmysqli, "INSERT INTO `timer` 
	(
	e2eventtitle, 
	title_enc, 
	e2eventdescription, 
	description_enc, 
	e2eventdescriptionextended, 
	descriptionextended_enc, 
	e2eventservicename, 
	servicename_enc, 
	e2eventservicereference, 
	search_term, 
	search_option,
	exclude_channel, 
	exclude_title, 
	exclude_description, 
	exclude_extdescription, 
	record_location, 
	e2eventstart, 
	e2eventend, 
	timer_request, 
	hash, 
	channel_hash, 
	status, 
	record_status, 
	rec_replay, 
	device, 
	search_id, 
	conflict,
	timer_repeat, 
	timer_repeat_d
	) VALUES (
	'".$e2eventtitle."', 
	'".$title_enc."', 
	'".$e2eventdescription."', 
	'".$description_enc."', 
	'".$e2eventdescriptionextended."', 
	'".$descriptionextended_enc."', 
	'".$e2eventservicename."', 
	'".$servicename_enc."', 
	'".$e2eventservicereference."', 
	'".$search_term."', 
	'".$search_option."', 
	'".$exclude_channel."', 
	'".$exclude_title."', 
	'".$exclude_description."', 
	'".$exclude_extdescription."',
	'".$e2location."', 
	'".$e2eventstart."', 
	'".$e2eventend."', 
	'".$timer_request."', 
	'".$hash."', 
	'".$channel_hash."', 
	'manual', 
	'".$record_status."', 
	'".$rec_replay."', 
	'".$device."', 
	'".$search_id."', 
	'".$timer_conflict."', 
	'".$timer_repeat."', 
	'".$timer_repeat_d."'
	)
	");
	
	} else {
	
	mysqli_query($dbmysqli, "INSERT INTO `timer` 
	(
	e2eventtitle, 
	title_enc, 
	e2eventdescription, 
	description_enc, 
	e2eventdescriptionextended, 
	descriptionextended_enc, 
	e2eventservicename, 
	servicename_enc, 
	e2eventservicereference, 
	record_location, 
	e2eventstart, 
	e2eventend, 
	timer_request, 
	hash, 
	channel_hash, 
	status, 
	record_status, 
	device, 
	conflict
	) VALUES (
	'".$e2eventtitle."', 
	'".$title_enc."', 
	'".$e2eventdescription."', 
	'".$description_enc."', 
	'".$e2eventdescriptionextended."', 
	'".$descriptionextended_enc."', 
	'".$e2eventservicename."', 
	'".$servicename_enc."', 
	'".$e2eventservicereference."', 
	'".$e2location."', 
	'".$e2eventstart."', 
	'".$e2eventend."', 
	'".$timer_request."', 
	'".$hash."', 
	'".$channel_hash."', 
	'manual', 
	'".$record_status."', 
	'".$device."', 
	'".$timer_conflict."'
	)
	");
	}
	}
	} // zap timer
	
	### timerlist different device
	if($location == 'timerlist' and $device != $current_device)
	{
	// set timer status
	if($location == 'timerlist' and $device != $current_device and $action == 'record')
	{
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'manual', `record_location` = '".$e2location."', `conflict` = '".$timer_conflict."' WHERE `hash` = '".$hash."' AND `device` = '".$device."' ");
	} 
	
	$sql_4 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `timer` WHERE `hash` = '".$hash."' AND `device` = '".$device."' ");
	$result_4 = mysqli_fetch_row($sql_4);
	
	if($result_4[0] == 0)
	{
	mysqli_query($dbmysqli, "INSERT INTO `timer` 
	(
	e2eventtitle, 
	title_enc, 
	e2eventdescription, 
	description_enc, 
	e2eventdescriptionextended, 
	descriptionextended_enc, 
	e2eventservicename, 
	servicename_enc, 
	e2eventservicereference, 
	search_term, 
	search_option,
	exclude_channel, 
	exclude_title, 
	exclude_description, 
	exclude_extdescription, 
	record_location, 
	e2eventstart, 
	e2eventend, 
	timer_request, 
	hash, 
	channel_hash, 
	status, 
	record_status, 
	rec_replay, 
	device, 
	search_id, 
	conflict,
	timer_repeat, 
	timer_repeat_d
	) VALUES (
	'".$e2eventtitle."', 
	'".$title_enc."', 
	'".$e2eventdescription."', 
	'".$description_enc."', 
	'".$e2eventdescriptionextended."', 
	'".$descriptionextended_enc."', 
	'".$e2eventservicename."', 
	'".$servicename_enc."', 
	'".$e2eventservicereference."', 
	'".$search_term."', 
	'".$search_option."', 
	'".$exclude_channel."', 
	'".$exclude_title."', 
	'".$exclude_description."', 
	'".$exclude_extdescription."',
	'".$e2location."', 
	'".$e2eventstart."', 
	'".$e2eventend."', 
	'".$timer_request."', 
	'".$hash."', 
	'".$channel_hash."', 
	'manual', 
	'".$record_status."', 
	'".$rec_replay."', 
	'".$device."', 
	'".$search_id."', 
	'".$timer_conflict."', 
	'".$timer_repeat."', 
	'".$timer_repeat_d."'
	)
	");
	}
	}
	
	//
	if($location == '' and $action == 'record')
	{
	$sql_5 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `timer` WHERE `hash` = '".$hash."' AND `device` = '".$device."' ");
	$result_5 = mysqli_fetch_row($sql_5);
	
	if($result_5[0] == 0)
	{
	mysqli_query($dbmysqli, "INSERT INTO `timer` 
	(
	e2eventtitle, 
	title_enc, 
	e2eventdescription, 
	description_enc, 
	e2eventdescriptionextended, 
	descriptionextended_enc, 
	e2eventservicename, 
	servicename_enc, 
	e2eventservicereference, 
	record_location, 
	e2eventstart, 
	e2eventend, 
	timer_request, 
	hash, 
	channel_hash, 
	status, 
	record_status, 
	device, 
	conflict
	) VALUES (
	'".$e2eventtitle."', 
	'".$title_enc."', 
	'".$e2eventdescription."', 
	'".$description_enc."', 
	'".$e2eventdescriptionextended."', 
	'".$descriptionextended_enc."', 
	'".$e2eventservicename."', 
	'".$servicename_enc."', 
	'".$e2eventservicereference."', 
	'".$e2location."', 
	'".$e2eventstart."', 
	'".$e2eventend."', 
	'".$timer_request."', 
	'".$hash."', 
	'".$channel_hash."', 
	'manual', 
	'".$record_status."', 
	'".$device."', 
	'".$timer_conflict."'
	)
	");
	}
	}
	
	$same_timer_msg = '';
	
	// default device
	if($device == '0')
	{
	// count timer within period
	$sql_6 = mysqli_query($dbmysqli, 'SELECT COUNT(id) FROM `timer` WHERE "'.$e2eventstart.'" BETWEEN `e2eventstart` AND `e2eventend` OR "'.$e2eventend.'" BETWEEN `e2eventstart` AND `e2eventend` ');
	$result_6 = mysqli_fetch_row($sql_6);
	$same_timer_time = $result_6[0];
	
	$same_timer_time = $same_timer_time - 1;
	if($same_timer_time > 0){ $same_timer_msg = '(<strong>'.$same_timer_time.'</strong> within the period)'; } else { $same_timer_msg = ''; }
	}

	// answer for ajax
	if($location == 'ticker')
	{ 
	echo '<i class="glyphicon glyphicon-ok fa-1x" style="color:#5CB85C"></i>\n\n';
	
	} else {
	
	if($location == 'timerlist')
	{ 
	if($device == '0' and $same_timer_time > 0){ $same_timer_msg = '(<strong>'.$same_timer_time.'</strong> within the period)'; } else { $same_timer_msg = ''; }
	
	if($timer_conflict == '1'){ $timer_status = ' - <span class=\"timer_conflict\">Conflict on Receiver</span>'; } else { $timer_status = ''; }
	
	if($device != '0')
	{
	$sql = mysqli_query($dbmysqli, "SELECT device_color FROM `device_list` WHERE `id` LIKE '".$device."' ");
	$result = mysqli_fetch_assoc($sql);
	$device_color = $result['device_color'];
	} else { 
	$device_color = ''; 
	}
	
	echo '
	[{
	"conflict":"'.$timer_conflict.'",
	"timer_status":"<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Timer sent '.$same_timer_msg.' '.$timer_status.'",
	"device_c":"'.$device_color.'"
	}]';
	exit;
	}
	
	echo "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Timer sent $same_timer_msg $timer_status\n\n";

	}

?>