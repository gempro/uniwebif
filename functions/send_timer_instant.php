<?php 
//
	include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ""){ $_REQUEST['action'] = ""; }
	if(!isset($_REQUEST['hash']) or $_REQUEST['hash'] == ""){ $_REQUEST['hash'] = ""; }
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == ""){ $_REQUEST['record_location'] = ""; }
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == ""){ $_REQUEST['device'] = ""; }
	if(!isset($_REQUEST['location']) or $_REQUEST['location'] == ""){ $_REQUEST['location'] = ""; }
	
	$action = $_REQUEST["action"];
	$hash = $_REQUEST["hash"];
	$record_location = $_REQUEST["record_location"];
	$device = $_REQUEST["device"];
	$location = $_REQUEST["location"];
	
	if(!isset($hash) or $hash == "") 
	{ 
	echo "data:error"; 

	} else { 
	
	if($location == 'timerlist')
	{
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `hash` = '".$hash."' ");
	
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
	
	if($location == "timerlist")
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
	}
	
	if($device == "0")
	{
	// get record location
	$sql = mysqli_query($dbmysqli, "SELECT `e2location` FROM `record_locations` WHERE `id` = '".$record_location."' ");
	$result2 = mysqli_fetch_assoc($sql);
	$e2location = $result2['e2location'];
	// mark epg entry
	mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '1' WHERE `hash` = '".$hash."' ");
	}
	
	// record location different device
	if($device != "0")
	{
	$e2location = $record_location;
	}
	
	// additional record time
	$e2eventend = $e2eventend + $extra_rec_time;
	
	if($location == 'timerlist'){ $e2eventend = $result['e2eventend']; }
	
	# send to different device
	if($device != "0")
	{
	$sql3 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result3 = mysqli_fetch_assoc($sql3);
	$box_ip = $result3['device_ip'];
	$box_user = $result3['device_user'];
	$box_password = $result3['device_password'];
	$url_format = $result3['url_format'];
	//
	
	if($action == "record")
	{
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$e2location.'&afterevent=3';
	}
	
	if($action == "zap")
	{
	$e2location = "zap_timer";
	$e2eventend = $e2eventstart + 1;
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&justplay=1';
	}
	
	// timer conflict
	$get_timer_status = @file_get_contents($timer_request, false, $webrequest);
	$xml = simplexml_load_string($get_timer_status);
	$timer_status = $xml->e2state;
	if(preg_match("/\btrue\b/i", $timer_status))
	{
	$timer_status = ""; 
	$timer_conflict = "0";
	
	} else { 
	
	$timer_status = " - <span class=\"timer_conflict\">Conflict on Receiver</span>";
	$timer_conflict = "1";
	}
	
	// remove " and ' from request
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	
	// Webrequest
	$webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$send_timer_request = @file_get_contents($timer_request, false, $webrequest);
	$device = $result3['id'];
	// send to different device
	
	} else {
	
	# send to default device
	if($action == "record")
	{
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$e2location.'&afterevent=3';
	}
	
	if($action == "zap")
	{
	$e2location = "zap_timer";
	$e2eventend = $e2eventstart + 1;
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&justplay=1';
	}
	
	// request with eventid
	//$timer_request = "http://$box_ip/web/timeraddbyeventid?sRef=".$e2eventservicereference."&eventid=".$e2eventid."&dirname=".$e2location."";
	
	// timer conflict
	$get_timer_status = @file_get_contents($timer_request, false, $webrequest);
	$xml = simplexml_load_string($get_timer_status);
	$timer_status = $xml->e2state;
	
	if($timer_status == "TRUE" || $timer_status == "True" || $timer_status == "true")
	{ 
	$timer_status = ""; 
	$timer_conflict = "0";
	} else { 
	$timer_status = " - <span class=\"timer_conflict\">Conflict on Receiver</span>"; 
	$timer_conflict = "1";
	}
	
	// remove " and ' from request
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	$send_timer_request = @file_get_contents($timer_request, false, $webrequest);
	
	$device = '0';
	
	} // send to default receiver
	
	if($time > $e2eventstart and $time < $e2eventend)
	{
	$record_status = 'a_recording'; }
	
	if($time < $e2eventstart and $time < $e2eventend)
	{
	$record_status = 'b_incoming'; }
	
	if($time > $e2eventend)
	{
	$record_status = 'c_expired'; }

	// set timer status
	if($location == 'timerlist' and $device == $current_device)
	{
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'manual', `conflict` = '".$timer_conflict."' WHERE `hash` = '".$hash."' ");
	} 
	
	// create copy if device is different
	if($location == "timerlist" and $device != $current_device)
	{
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(*) FROM `timer` WHERE `hash` = '".$hash."' AND `device` = '".$device."' ");
	$summary = mysqli_fetch_row($sql);
	
	if($summary[0] < 1){
	mysqli_query($dbmysqli, "INSERT INTO `timer` (
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
	conflict
	) VALUES (
	'$e2eventtitle', 
	'$title_enc', 
	'$e2eventdescription', 
	'$description_enc', 
	'$e2eventdescriptionextended', 
	'$descriptionextended_enc', 
	'$e2eventservicename', 
	'$servicename_enc', 
	'$e2eventservicereference', 
	'$search_term', 
	'$search_option', 
	'$exclude_channel', 
	'$exclude_title', 
	'$exclude_description', 
	'$exclude_extdescription',
	'$e2location', 
	'$e2eventstart', 
	'$e2eventend', 
	'$timer_request', 
	'$hash', 
	'$channel_hash', 
	'manual', 
	'$record_status', 
	'$rec_replay', 
	'$device',
	'$search_id',
	'$timer_conflict')");
	}
	}
	
	if($location == "")
	{
	mysqli_query($dbmysqli, "INSERT INTO `timer` (
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
	'$e2eventtitle', 
	'$title_enc', 
	'$e2eventdescription', 
	'$description_enc', 
	'$e2eventdescriptionextended', 
	'$descriptionextended_enc', 
	'$e2eventservicename', 
	'$servicename_enc', 
	'$e2eventservicereference', 
	'$e2location', 
	'$e2eventstart', 
	'$e2eventend', 
	'$timer_request', 
	'$hash', 
	'$channel_hash', 
	'manual', 
	'$record_status', 
	'$device',
	'$timer_conflict')");
	}
	
	$same_timer_msg = "";
	if($device == "0")
	{
	// count timer within period
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `timer` WHERE "'.$e2eventstart.'" BETWEEN `e2eventstart` AND `e2eventend` OR "'.$e2eventend.'" BETWEEN `e2eventstart` AND `e2eventend` ');
	$result = mysqli_fetch_row($sql);
	$same_timer_time = $result[0];
	//
	
	$same_timer_time = $same_timer_time - 1;
	if($same_timer_time > 0){ $same_timer_msg = '(<strong>'.$same_timer_time.'</strong> within the period)'; } else { $same_timer_msg = ""; }
	}

	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	if($location == 'ticker')
	{ 
	echo "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>\n\n";
	
	} else {
	
	if($location == 'timerlist')
	{
	if($device == "0" and $same_timer_time > 0){ $same_timer_msg = "(<strong>".$same_timer_time."</strong> within the period)"; } else { $same_timer_msg = ""; }
	if($timer_conflict == "1"){ $timer_status = ' - <span class=\"timer_conflict\">Conflict on Receiver</span>'; } else { $timer_status = ""; }
	echo '
	[{"conflict":"'.$timer_conflict.'",
	"timer_html":"<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Timer sent '.$same_timer_msg.' '.$timer_status.'\r"}]';
	exit;
	}
	
	echo "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Timer sent $same_timer_msg $timer_status\n\n";
	}
}

?>
