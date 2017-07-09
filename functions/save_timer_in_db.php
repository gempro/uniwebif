<?php 
sleep(1);
//
include("../inc/dashboard_config.php");

	$sql = "SELECT * FROM saved_search ORDER BY `save_date` DESC";
	
	if ($result1 = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result1)) {
	{
	$raw_term = $obj->searchterm;
	$search_option = $obj->search_option;
	$e2location = $obj->e2location;
	$e2eventservicereference = $obj->e2eventservicereference;
	$activ = $obj->activ;

	if ($activ == 'no' ){ echo ''; } else {
	
	// search only in selected channel
	if ($e2eventservicereference !== 'NULL'){ 
	$search_include = 'WHERE e2eventservicereference = "'.$e2eventservicereference.'" AND'; 
	$search_include2 = 'OR e2eventservicereference = "'.$e2eventservicereference.'" AND';
	
	} else { 
	
	$search_include = 'WHERE'; 
	$search_include2 = 'OR';
	}
	
	// search all
	if ($search_option == 'all' or $search_option == '')
	{
	$sql = 'SELECT * FROM epg_data '.$search_include.' MATCH (title_enc, e2eventservicename, description_enc, descriptionextended_enc) AGAINST ("%'.$raw_term.'%") AND e2eventend > '.$time.' '.$search_include2.' e2eventtitle LIKE "%'.$raw_term.'%" AND e2eventend > '.$time.' '.$search_include2.' e2eventservicename LIKE "%'.$raw_term.'%" AND e2eventend > '.$time.' '.$search_include2.' e2eventdescription LIKE "%'.$raw_term.'%" AND e2eventend > '.$time.' '.$search_include2.' e2eventdescriptionextended LIKE "%'.$raw_term.'%" AND e2eventend > '.$time.' '; 
	}
	
	// search title
	if ($search_option == 'title')
	{
	$sql = 'SELECT * FROM epg_data '.$search_include.' title_enc LIKE "%'.$raw_term.'%" AND e2eventend > '.$time.' ';
	}
	
	// search description
	if ($search_option == 'description')
	{
	$sql = 'SELECT * FROM epg_data '.$search_include.' description_enc LIKE "%'.$raw_term.'%" AND e2eventend > '.$time.' ';
	}
	
	// search extended description
	if ($search_option == 'extdescription')
	{
	$sql = 'SELECT * FROM epg_data '.$search_include.' descriptionextended_enc LIKE "%'.$raw_term.'%" AND e2eventend > '.$time.' ';
	}

	if ($result2 = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result2)) {	
	{
	$e2eventtitle = $obj->e2eventtitle;
	$title_enc = $obj->title_enc;
	$e2eventservicename = $obj->e2eventservicename;
	$servicename_enc = $obj->servicename_enc;
	$e2eventdescription = $obj->e2eventdescription;
	$description_enc = $obj->description_enc;
	$e2eventdescriptionextended = $obj->e2eventdescriptionextended;
	$descriptionextended_enc = $obj->descriptionextended_enc;
	$e2eventid = $obj->e2eventid;
	$e2eventstart = $obj->e2eventstart;
	$e2eventend = $obj->e2eventend;
	$e2eventservicereference = $obj->e2eventservicereference;
	$hash = $obj->hash;
	$channel_hash = $obj->channel_hash;
	
	// additional record time
	$e2eventend = $e2eventend + $extra_rec_time;
	
	$timer_request = "http://$box_ip/web/timeradd?sRef=".$e2eventservicereference."&begin=".$e2eventstart."&end=".$e2eventend."&name=".$title_enc."&description=".$description_enc."&dirname=".$e2location."&afterevent=3&channelOld=".$e2eventservicereference."&endOld=".$e2eventend."&deleteOldOnSave=0";
	
	// request with eventid
	//$timer_request = "http://$box_ip/web/timeraddbyeventid?sRef=".$e2eventservicereference."&eventid=".$e2eventid."&dirname=".$e2location."";
	
	// remove " and ' from request
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO timer (e2eventtitle,title_enc,e2eventdescription,description_enc,e2eventdescriptionextended,descriptionextended_enc,e2eventservicename,servicename_enc,e2eventservicereference,search_term,search_option,record_location,e2eventstart,e2eventend,timer_request,hash,channel_hash,status)
	values ('$e2eventtitle','$title_enc','$e2eventdescription','$description_enc','$e2eventdescriptionextended','$descriptionextended_enc','$e2eventservicename','$servicename_enc','$e2eventservicereference','$raw_term','$search_option','$e2location','$e2eventstart','$e2eventend','$timer_request','$hash','$channel_hash','waiting')"); 
	}
	}}
	}}
	}}
	// check record status
	$sql = "SELECT * FROM timer";
	
	if ($result3 = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result3)) {
	{
	$id = $obj->id;
	
	if ($time > $obj->e2eventstart and $time < $obj->e2eventend )
	{
	$record_status = 'a_recording'; }
	
	if ($time < $obj->e2eventstart and $time < $obj->e2eventend)
	{
	$record_status = 'b_incoming'; }
	
	if ($time > $obj->e2eventend)
	{
	$record_status = 'c_expired'; }

	$sql = mysqli_query($dbmysqli, "UPDATE timer set record_status = '".$record_status."' WHERE `id` = '$id'"); }
	}
	}
	// delete timer duplicates
	$sql = mysqli_query($dbmysqli, "DELETE FROM timer USING timer, timer as Dup WHERE NOT timer.id = Dup.id AND timer.id > Dup.id AND timer.hash = Dup.hash");
	$sql = mysqli_query($dbmysqli, "OPTIMIZE TABLE `timer`");
	
	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data: Timer from saved search, written in database!\n\n";
	
//close db
mysqli_close($dbmysqli);
?>