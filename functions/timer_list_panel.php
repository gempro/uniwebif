<?php 
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['panel_action']) or $_REQUEST['panel_action'] == "") { $_REQUEST['panel_action'] = ""; }
	if(!isset($_REQUEST['timer_id']) or $_REQUEST['timer_id'] == "") { $_REQUEST['timer_id'] = ""; }
	
	$panel_action = $_REQUEST['panel_action'];
	$timer_id = $_REQUEST['timer_id'];
		
	// delete from db
	if ($panel_action == 'delete_db')
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
	
	if ($status == 'manual' or $status == 'sent'){
	$sql2 = mysqli_query($dbmysqli, "UPDATE `epg_data` SET timer = '0' WHERE `hash` = '".$hash."' ");
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
	
	$deleteTimer = "$url_format://$box_ip/web/timerdelete?sRef=".$e2eventservicereference."&begin=".$e2eventstart."&end=".$e2eventend."";
	$deleteTimer_request = file_get_contents($deleteTimer, false, $webrequest);
	
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET status = 'rec_deleted' WHERE `id` = '".$key."' ");
	
	if ($status == 'manual' or $status == 'sent'){
	$sql2 = mysqli_query($dbmysqli, "UPDATE `epg_data` SET timer = '0' WHERE `hash` = '".$hash."' ");
	}
	}
	sleep(1);
	echo 'delete_rec_done';
	}
	
	// delete both
	if ($panel_action == 'delete_both')
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
	
	$deleteTimer = "$url_format://$box_ip/web/timerdelete?sRef=".$e2eventservicereference."&begin=".$e2eventstart."&end=".$e2eventend."";
	$deleteTimer_request = file_get_contents($deleteTimer, false, $webrequest);
	
	if ($status == 'manual' or $status == 'sent'){
	$sql2 = mysqli_query($dbmysqli, "UPDATE `epg_data` SET timer = '0' WHERE `hash` = '".$hash."' ");
	}
	
	$sql3 = mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `id` = '".$key."' ");
	}
	sleep(1);
	echo 'delete_both_done';
	}
	
	// send
	if ($panel_action == 'send')
	{
	$tags = explode(';' , $timer_id);
	foreach($tags as $i =>$key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ""){ $key = ""; }
	$del_string = "OR `id` = '".$key."' ";
	
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$key."' ");
	$result = mysqli_fetch_assoc($sql);
	
	$e2eventservicereference = $result['e2eventservicereference'];
	$e2eventstart = $result['e2eventstart'];
	$e2eventend = $result['e2eventend'];
	$title_enc = $result['title_enc'];
	$description_enc = $result['description_enc'];
	$e2location = $result['record_location'];
	$hash = $result['hash'];
	
	$timer_request = "$url_format://$box_ip/web/timeradd?sRef=".$e2eventservicereference."&begin=".$e2eventstart."&end=".$e2eventend."&name=".$title_enc."&description=".$description_enc."&dirname=".$e2location."&afterevent=3";
	
	// remove " and ' from request
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	
	$send_timer_request = file_get_contents($timer_request, false, $webrequest);
	
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'manual' WHERE `id` = '".$key."' ");
	
	$sql = mysqli_query($dbmysqli, "UPDATE epg_data SET `timer` = '1' WHERE `hash` = '".$hash."' ");
	
	sleep(0.5);
	}
	echo 'send_done';
	}
	
	// hide
	if ($panel_action == 'hide')
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
	if ($panel_action == 'unhide')
	{
	// explode
	$tags = explode(';' , $timer_id);
	foreach($tags as $i =>$key)
	{ 
	$i > 0;
	if(!isset($key) or $key == ""){ $key = ""; }
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '0' WHERE `id` = '".$key."' ");
	} // explode for i
	sleep(1);
	echo 'unhide_done';
	}

?>