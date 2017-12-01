<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['hash']) or $_REQUEST['hash'] == "") { $_REQUEST['hash'] = ""; }
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == "") { $_REQUEST['record_location'] = ""; }
	if(!isset($_REQUEST['location']) or $_REQUEST['location'] == "") { $_REQUEST['location'] = ""; }
	
	$hash = $_REQUEST["hash"];
	$record_location = $_REQUEST["record_location"];
	$location = $_REQUEST["location"];
	
	if(!isset($hash) or $hash == "") 
	{ 
	echo "data: data missed\n\n"; 
	
	} else {

	$sql = mysqli_query($dbmysqli, "SELECT * FROM epg_data WHERE hash = '".$hash."' ");
	$result = mysqli_fetch_assoc($sql);
	
	$sql2 = mysqli_query($dbmysqli, "UPDATE epg_data SET timer = '1' WHERE hash = '".$hash."' ");
	
	$e2eventtitle = $result['e2eventtitle'];
	$title_enc = $result['title_enc'];
	$e2eventservicename = $result['e2eventservicename'];
	$servicename_enc = $result['servicename_enc'];
	$e2eventdescription = $result['e2eventdescription'];
	$description_enc = $result['description_enc'];
	$e2eventdescriptionextended = $result['e2eventdescriptionextended'];
	$descriptionextended_enc = $result['descriptionextended_enc'];
	$e2eventid = $result['e2eventid'];
	$e2eventstart = $result['e2eventstart'];
	$e2eventend = $result['e2eventend'];
	$e2eventservicereference = $result['e2eventservicereference'];
	$channel_hash = $result['channel_hash'];
	
	// get record location
	$sql3 = mysqli_query($dbmysqli, "SELECT e2location FROM `record_locations` WHERE id = '".$record_location."'");
	$result3 = mysqli_fetch_assoc($sql3);
	$e2location = $result3['e2location'];
	
	// additional record time
	$e2eventend = $e2eventend + $extra_rec_time;
	
	$timer_request = "$url_format://$box_ip/web/timeradd?sRef=".$e2eventservicereference."&begin=".$e2eventstart."&end=".$e2eventend."&name=".$title_enc."&description=".$description_enc."&dirname=".$e2location."&afterevent=3";
	
	// request with eventid
	//$timer_request = "http://$box_ip/web/timeraddbyeventid?sRef=".$e2eventservicereference."&eventid=".$e2eventid."&dirname=".$e2location."";
	
	sleep(1);
	
	// remove " and ' from request
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	
	$send_timer_request = file_get_contents($timer_request, false, $webrequest);
	
	if ($time > $e2eventstart and $time < $e2eventend )
	{
	$record_status = 'a_recording'; }
	
	if ($time < $e2eventstart and $time < $e2eventend)
	{
	$record_status = 'b_incoming'; }
	
	if ($time > $e2eventend)
	{
	$record_status = 'c_expired'; }

	// save timer in db
	if ($location == 'timerlist' ){ 
	
	$sql = mysqli_query($dbmysqli, "UPDATE timer SET status = 'manual' WHERE hash = '$hash'");
	
	} else {
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO timer (e2eventtitle,title_enc,e2eventdescription,description_enc,e2eventdescriptionextended,descriptionextended_enc,e2eventservicename,servicename_enc,e2eventservicereference,record_location,e2eventstart,e2eventend,timer_request,hash,channel_hash,status,record_status)
	values ('$e2eventtitle','$title_enc','$e2eventdescription','$description_enc','$e2eventdescriptionextended','$descriptionextended_enc','$e2eventservicename','$servicename_enc','$e2eventservicereference','$e2location','$e2eventstart','$e2eventend','$timer_request','$hash','$channel_hash','manual','$record_status')");
	}
	// count timer within period
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as same_timer_time FROM `timer` WHERE "'.$e2eventstart.'" BETWEEN e2eventstart AND e2eventend OR "'.$e2eventend.'" BETWEEN e2eventstart AND e2eventend ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($same_timer_time);
	$stmt->fetch();
	$stmt->close();
	
	$same_timer_time = $same_timer_time - 1;
	if ($same_timer_time > 0){ $same_timer_msg = '(<strong>'.$same_timer_time.'</strong> within the period)'; } else { $same_timer_msg = ""; }
	
	//close db
	mysqli_close($dbmysqli);

	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data: <i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Timer sent $same_timer_msg\n\n";
	// 
	
}
?>
