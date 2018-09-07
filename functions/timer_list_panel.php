<?php 
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['panel_action']) or $_REQUEST['panel_action'] == "") { $_REQUEST['panel_action'] = ""; }
	if(!isset($_REQUEST['timer_id']) or $_REQUEST['timer_id'] == "") { $_REQUEST['timer_id'] = ""; }
	
	$panel_action = $_REQUEST['panel_action'];
	$timer_id = $_REQUEST['timer_id'];
		
	// delete from db
	if($panel_action == 'delete_db')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i =>$key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ""){ $key = ""; }
	
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$key."' ");
	$result = mysqli_fetch_assoc($sql);
	$hash = $result['hash'];
	$status = $result['status'];
	
	if($status == 'manual' or $status == 'sent'){
	$sql2 = mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '0' WHERE `hash` = '".$hash."' ");
	}
	
	$sql3 = mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `id` = '".$key."' ");
	}
	sleep(1);
	echo 'delete_db_done';
	}
	
	// delete from receiver
	if ($panel_action == 'delete_rec')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i =>$key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ""){ $key = ""; }
	
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$key."' ");
	$result = mysqli_fetch_assoc($sql);
	
	$e2eventservicereference = $result['e2eventservicereference'];
	$e2eventstart = $result['e2eventstart'];
	$e2eventend = $result['e2eventend'];
	$hash = $result['hash'];
	$status = $result['status'];
	$device = $result['device'];
	
	// delete timer from different device
	if($device != "0"){
	$sql2 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result2 = mysqli_fetch_assoc($sql2);
	$device_box_ip = $result2['device_ip'];
	$device_box_user = $result2['device_user'];
	$device_box_password = $result2['device_password'];
	$device_url_format = $result2['url_format'];
	$device_webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$device_box_user:$device_box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$device_deleteTimer = $device_url_format.'://'.$device_box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'';
	$device_deleteTimer_request = @file_get_contents($device_deleteTimer, false, $device_webrequest);
	} // delete timer from different device
	
	else {
	
	$deleteTimer = $url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'';
	$deleteTimer_request = @file_get_contents($deleteTimer, false, $webrequest);
	} //default receiver
	
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'rec_deleted', `conflict` = '0' WHERE `id` = '".$key."' ");
	
	if($status == 'manual' or $status == 'sent'){
	$sql2 = mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '0' WHERE `hash` = '".$hash."' ");
	}
	}
	sleep(1);
	echo 'delete_rec_done';
	}
	
	// delete both
	if($panel_action == 'delete_both')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i =>$key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ""){ $key = ""; }
	
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$key."' ");
	$result = mysqli_fetch_assoc($sql);
	
	$e2eventservicereference = $result['e2eventservicereference'];
	$e2eventstart = $result['e2eventstart'];
	$e2eventend = $result['e2eventend'];
	$hash = $result['hash'];
	$status = $result['status'];
	$device = $result['device'];
	
	// delete timer from different device
	if($device != "0"){
	$sql3 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result3 = mysqli_fetch_assoc($sql3);
	$device_box_ip = $result3['device_ip'];
	$device_box_user = $result3['device_user'];
	$device_box_password = $result3['device_password'];
	$device_url_format = $result3['url_format'];
	$device_webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$device_box_user:$device_box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$device_deleteTimer = $device_url_format.'://'.$device_box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'';
	$device_deleteTimer_request = @file_get_contents($device_deleteTimer, false, $device_webrequest);
	} // delete timer from different device
	
	else {
	
	$deleteTimer = $url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'';
	$deleteTimer_request = @file_get_contents($deleteTimer, false, $webrequest);
	} // default receiver
	
	if($status == 'manual' or $status == 'sent'){
	$sql4 = mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '0' WHERE `hash` = '".$hash."' ");
	}
	
	$sql5 = mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `id` = '".$key."' ");
	}
	sleep(1);
	echo 'delete_both_done';
	}
	
	// send
	if($panel_action == 'send')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i =>$key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ""){ $key = ""; }
	
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$key."' ");
	$result = mysqli_fetch_assoc($sql);
	
	$e2eventservicereference = $result['e2eventservicereference'];
	$e2eventstart = $result['e2eventstart'];
	$e2eventend = $result['e2eventend'];
	$title_enc = $result['title_enc'];
	$description_enc = $result['description_enc'];
	$e2location = $result['record_location'];
	$hash = $result['hash'];
	$device = $result['device'];
	
	// send timer to different device
	if($device != "0"){
	$sql6 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result6 = mysqli_fetch_assoc($sql6);
	$device_box_ip = $result6['device_ip'];
	$device_box_user = $result6['device_user'];
	$device_box_password = $result6['device_password'];
	$device_url_format = $result6['url_format'];
	$device_webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$device_box_user:$device_box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$device_timer_request = $device_url_format.'://'.$device_box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$e2location.'&afterevent=3';
	$device_timer_request = str_replace("%22", "%60", $device_timer_request);
	$device_timer_request = str_replace("%27", "%60", $device_timer_request);	
	$device_send_timer_request = @file_get_contents($device_timer_request, false, $device_webrequest);
	
	// detect conflict
	$xml = simplexml_load_string($device_send_timer_request);
	$device_timer_status = $xml->e2state;
	if(preg_match("/\btrue\b/i", $device_timer_status)){ $timer_conflict = "0"; } else { $timer_conflict = "1"; }
	
	} // send timer to different device
	
	else {
	
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$e2location.'&afterevent=3';
	
	// remove " and ' from request
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	
	$send_timer_request = @file_get_contents($timer_request, false, $webrequest);
	
	// detect conflict
	$xml = simplexml_load_string($send_timer_request);
	$timer_status = $xml->e2state;
	if(preg_match("/\btrue\b/i", $timer_status)){ $timer_conflict = "0"; } else { $timer_conflict = "1"; }
	
	} // send timer to default receiver
	
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'manual', `conflict` = '".$timer_conflict."' WHERE `id` = '".$key."' ");
	$sql = mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '1' WHERE `hash` = '".$hash."' ");
	
	$str1 = ""; $str2 = ""; $str3 = "";
	$obj[$i] = array("id" => $key, "conflict" => $timer_conflict);
	if($i == 0){ $str1 = "["; }
	if($i == count($tags)-1){ $str3 = "]"; } else { $str2 = ","; }
	$json = json_encode($obj[$i]);
	echo $str1.$json.$str2.$str3;
	}	
	sleep(1);
	}
	
	// hide
	if($panel_action == 'hide')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i =>$key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ""){ $key = ""; }
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '1' WHERE `id` = '".$key."' ");
	}
	sleep(1);
	echo 'hide_done';
	}
	
	// unhide
	if($panel_action == 'panel_unhide')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i =>$key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ""){ $key = ""; }
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '0' WHERE `id` = '".$key."' ");
	}
	sleep(1);
	echo 'unhide_done';
	}
?>