<?php 
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['panel_action']) or $_REQUEST['panel_action'] == ''){ $_REQUEST['panel_action'] = ''; }
	if(!isset($_REQUEST['timer_id']) or $_REQUEST['timer_id'] == ''){ $_REQUEST['timer_id'] = ''; }
	
	$panel_action = $_REQUEST['panel_action'];
	$timer_id = $_REQUEST['timer_id'];
		
	// delete from db
	if($panel_action == 'delete_db')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i => $key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ''){ $key = ''; }
	
	$sql_0 = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$key."' ");
	$result_0 = mysqli_fetch_assoc($sql_0);
	$hash = $result_0['hash'];
	$status = $result_0['status'];
	
	if($status == 'manual' or $status == 'sent')
	{
	mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '0' WHERE `hash` = '".$hash."' ");
	}
	
	mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `id` = '".$key."' ");
	}
	sleep(1);
	echo 'delete_db_done';
	}
	
	// delete from receiver
	if ($panel_action == 'delete_rec')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i => $key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ''){ $key = ''; }
	
	$sql_1 = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$key."' ");
	$result_1 = mysqli_fetch_assoc($sql_1);
	$e2eventservicereference = $result_1['e2eventservicereference'];
	$record_location = $result_1['record_location'];
	$e2eventstart = $result_1['e2eventstart'];
	$e2eventend = $result_1['e2eventend'];
	$hash = $result_1['hash'];
	$status = $result_1['status'];
	$device = $result_1['device'];
	
	// delete timer from different device
	if($device != '0')
	{
	$sql_2 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result_2 = mysqli_fetch_assoc($sql_2);
	$device_box_ip = $result_2['device_ip'];
	$device_box_user = $result_2['device_user'];
	$device_box_password = $result_2['device_password'];
	$device_url_format = $result_2['url_format'];
	$device_webrequest = stream_context_create(array (
	'http' => array (
	'method' => 'POST',
	'header' => 'Authorization: Basic ' . base64_encode("$device_box_user:$device_box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$device_deleteTimer = $device_url_format.'://'.$device_box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.$session_part_2;
	$device_deleteTimer_request = @file_get_contents($device_deleteTimer, false, $device_webrequest);
	} // different device
	
	else {
	
	$deleteTimer = $url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.$session_part_2;
	$deleteTimer_request = @file_get_contents($deleteTimer, false, $webrequest);
	} //default receiver
	
	if($record_location == 'zap_timer')
	{ 
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'waiting', `conflict` = '0' WHERE `id` = '".$key."' "); 
	} else {
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'rec_deleted', `conflict` = '0' WHERE `id` = '".$key."' ");
	}
	
	if($status == 'manual' or $status == 'sent')
	{
	mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '0' WHERE `hash` = '".$hash."' ");
	}
	}
	sleep(1);
	echo 'delete_rec_done';
	}
	
	// delete both
	if($panel_action == 'delete_both')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i => $key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ''){ $key = ''; }
	
	$sql_3 = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$key."' ");
	$result_3 = mysqli_fetch_assoc($sql_3);
	$e2eventservicereference = $result_3['e2eventservicereference'];
	$e2eventstart = $result_3['e2eventstart'];
	$e2eventend = $result_3['e2eventend'];
	$hash = $result_3['hash'];
	$status = $result_3['status'];
	$device = $result_3['device'];
	
	// delete from different device
	if($device != '0')
	{
	$sql_4 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result_4 = mysqli_fetch_assoc($sql_4);
	$device_box_ip = $result_4['device_ip'];
	$device_box_user = $result_4['device_user'];
	$device_box_password = $result_4['device_password'];
	$device_url_format = $result_4['url_format'];
	$device_webrequest = stream_context_create(array (
	'http' => array (
	'method' => 'POST',
	'header' => 'Authorization: Basic ' . base64_encode("$device_box_user:$device_box_password"),
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
	
	$device_deleteTimer = $device_url_format.'://'.$device_box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.$session_part_2;
	$device_deleteTimer_request = @file_get_contents($device_deleteTimer, false, $device_webrequest);
	} // different device
	
	else {
	
	$deleteTimer = $url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.$session_part_2;
	$deleteTimer_request = @file_get_contents($deleteTimer, false, $webrequest);
	} // default receiver
	
	if($status == 'manual' or $status == 'sent')
	{
	mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '0' WHERE `hash` = '".$hash."' ");
	}
	
	mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `id` = '".$key."' ");
	}
	sleep(1);
	echo 'delete_both_done';
	}
	
	// send
	if($panel_action == 'send')
	{
	$timer_repeat = 0;
	$tags = explode(';' , $timer_id);
	foreach($tags as $i => $key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ''){ $key = ''; }
	
	$sql_5 = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$key."' ");
	$result_5 = mysqli_fetch_assoc($sql_5);
	$e2eventservicereference = $result_5['e2eventservicereference'];
	$e2eventstart = $result_5['e2eventstart'];
	$e2eventend = $result_5['e2eventend'];
	$title_enc = $result_5['title_enc'];
	$description_enc = $result_5['description_enc'];
	$e2location = $result_5['record_location'];
	$hash = $result_5['hash'];
	$device = $result_5['device'];
	$timer_repeat = $result_5['timer_repeat'];
	
	// send timer to different device
	if($device != '0')
	{
	$sql_6 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result_6 = mysqli_fetch_assoc($sql_6);
	$device_box_ip = $result_6['device_ip'];
	$device_box_user = $result_6['device_user'];
	$device_box_password = $result_6['device_password'];
	$device_url_format = $result_6['url_format'];
	$device_webrequest = stream_context_create(array (
	'http' => array (
	'method' => 'POST',
	'header' => 'Authorization: Basic ' . base64_encode("$device_box_user:$device_box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	
	// check if token session is required
	$xmlfile = $device_url_format.'://'.$device_box_ip.'/web/session?sessionid=0';
	$session_info = @file_get_contents($xmlfile, false, $device_webrequest);
	$e2sessionid = simplexml_load_string($session_info);
	
	if($e2sessionid != ''){ $session_part_2 = '&sessionid='.$e2sessionid; } else { $session_part_2 = ''; }
	
	$device_timer_request = $device_url_format.'://'.$device_box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$e2location.'&afterevent=3&repeated='.$timer_repeat.$session_part_2;
	
	$device_timer_request = str_replace('%22', '%60', $device_timer_request);
	$device_timer_request = str_replace('%27', '%60', $device_timer_request);	
	
	// request
	$device_send_timer_request = @file_get_contents($device_timer_request, false, $device_webrequest);
	$xml = simplexml_load_string($device_send_timer_request);
	$device_timer_status = $xml->e2state;
	
	if(preg_match('/\btrue\b/i', $device_timer_status))
	{ 
	$timer_conflict = '0'; 
	} else { 
	$timer_conflict = '1'; 
	}
	
	} // different device
	
	else { // send timer to default receiver
	
	if($e2location == 'zap_timer')
	{
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&justplay=1&repeated='.$timer_repeat.$session_part_2;
	} else {
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$e2location.'&afterevent=3&repeated='.$timer_repeat.$session_part_2;
	}
	
	// remove " and ' from request
	$timer_request = str_replace('%22', '%60', $timer_request);
	$timer_request = str_replace('%27', '%60', $timer_request);
	
	// request
	$get_timer_status = @file_get_contents($timer_request, false, $webrequest);
	$xml = simplexml_load_string($get_timer_status);
	$timer_status = $xml->e2state;
	
	if(preg_match('/\btrue\b/i', $timer_status)){ $timer_conflict = '0'; } else { $timer_conflict = '1'; }
	} // default receiver
	
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'manual', `conflict` = '".$timer_conflict."' WHERE `id` = '".$key."' ");
	mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '1' WHERE `hash` = '".$hash."' ");
	
	$str1 = ''; $str2 = ''; $str3 = '';
	$obj[$i] = array('id' => $key, 'conflict' => $timer_conflict);
	if($i == 0){ $str1 = '['; }
	if($i == count($tags)-1){ $str3 = ']'; } else { $str2 = ','; }
	$json = json_encode($obj[$i]);
	echo $str1.$json.$str2.$str3;
	}	
	sleep(1);
	}
	
	// hide
	if($panel_action == 'hide')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i => $key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ''){ $key = ''; }
	mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '1' WHERE `id` = '".$key."' ");
	}
	sleep(1);
	echo 'hide_done';
	}
	
	// unhide
	if($panel_action == 'panel_unhide')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i => $key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ''){ $key = ''; }
	mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '0' WHERE `id` = '".$key."' ");
	}
	sleep(1);
	echo 'unhide_done';
	}

?>