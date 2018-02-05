<?php 
sleep(1);
//
include("../inc/dashboard_config.php");

	$sql = "SELECT * FROM `saved_search` WHERE `crawled` = '0' and `activ` = 'yes' ";
	
	if ($result1 = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result1)) {
	{
	$id = $obj->id;
	$raw_term = $obj->searchterm;
	$search_option = $obj->search_option;
	$exclude_term = $obj->exclude_term;
	$exclude_area = $obj->exclude_area;
	$e2location = $obj->e2location;
	$e2eventservicereference = $obj->e2eventservicereference;
	$activ = $obj->activ;
	$rec_replay = $obj->rec_replay;

	//if ($activ == 'yes' )
	//{
	// update last crawl
	$sql = mysqli_query($dbmysqli, "UPDATE `saved_search` SET `last_crawl` = '".$time."', crawled = '1' WHERE `id` = ".$id." ");
	
	// search only in selected channel
	if ($e2eventservicereference !== 'NULL'){
	
	$search_include = 'WHERE e2eventservicereference = "'.$e2eventservicereference.'" AND'; 
	$search_include2 = 'OR e2eventservicereference = "'.$e2eventservicereference.'" AND';
	
	} else { 
	
	$search_include = 'WHERE'; 
	$search_include2 = 'OR';
	}
	//
	// exclude titel
	if ($exclude_term !== '' and $exclude_area == '1'){ 
	$tags = explode(rawurlencode(';') , $exclude_term);
	foreach($tags as $i =>$key) { $i > 0;
	if(!isset($exclude_part) or $exclude_part == "") { $exclude_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND title_enc NOT LIKE "%'.$key.'%" '; }
	$exclude_part = $exclude_part.$search_string;
	}
	}
	// exclude description
	if ($exclude_term !== '' and $exclude_area == '2'){ 
	$tags = explode(rawurlencode(';') , $exclude_term);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_part) or $exclude_part == "") { $exclude_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND description_enc NOT LIKE "%'.$key.'%" '; }
	$exclude_part = $exclude_part.$search_string;
	}
	}
	// exclude extended description
	if ($exclude_term !== '' and $exclude_area == '3'){ 
	//
	$tags = explode(rawurlencode(';') , $exclude_term);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_part) or $exclude_part == "") { $exclude_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND descriptionextended_enc NOT LIKE "%'.$key.'%" '; }
	$exclude_part = $exclude_part.$search_string;
	} //
	}
	
	if(!isset($exclude_part) or $exclude_part == "") { $exclude_part = ""; }
	
	// search all
	if ($search_option == 'all' or $search_option == '')
	{
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' MATCH (title_enc, e2eventservicename, description_enc, descriptionextended_enc) AGAINST ("%'.$raw_term.'%") AND e2eventend > '.$time.' '.$search_include2.' `e2eventtitle` LIKE "%'.$raw_term.'%" AND `e2eventend` > '.$time.' '.$search_include2.' `e2eventservicename` LIKE "%'.$raw_term.'%" AND `e2eventend` > '.$time.' '.$search_include2.' `e2eventdescription` LIKE "%'.$raw_term.'%" AND `e2eventend` > '.$time.' '.$search_include2.' `e2eventdescriptionextended` LIKE "%'.$raw_term.'%" AND `e2eventend` > '.$time.' ORDER BY `e2eventstart` ASC '; 
	}
	
	// search title
	if ($search_option == 'title')
	{
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' `title_enc` LIKE "%'.$raw_term.'%" '.$exclude_part.' AND `e2eventend` > '.$time.' ORDER BY `e2eventstart` ASC ';
	}
	
	// search description
	if ($search_option == 'description')
	{
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' `description_enc` LIKE "%'.$raw_term.'%" '.$exclude_part.' AND `e2eventend` > '.$time.' ORDER BY `e2eventstart` ASC ';
	}
	
	// search extended description
	if ($search_option == 'extdescription')
	{
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' `descriptionextended_enc` LIKE "%'.$raw_term.'%" '.$exclude_part.' AND `e2eventend` > '.$time.' ORDER BY `e2eventstart` ASC ';
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
	
	$timer_request = "$url_format://$box_ip/web/timeradd?sRef=".$e2eventservicereference."&begin=".$e2eventstart."&end=".$e2eventend."&name=".$title_enc."&description=".$description_enc."&dirname=".$e2location."&afterevent=3";
	
	// request with eventid
	//$timer_request = "http://$box_ip/web/timeraddbyeventid?sRef=".$e2eventservicereference."&eventid=".$e2eventid."&dirname=".$e2location."";
	
	// remove " and ' from request
	$timer_request = str_replace("%22", "%60", $timer_request);
	$timer_request = str_replace("%27", "%60", $timer_request);
	
	// check if timer exist for replay record
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) AS count_replay FROM `timer` WHERE `title_enc` = "'.$title_enc.'" AND `description_enc` = "'.$description_enc.'" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_replay);
	$stmt->fetch();
	$stmt->close();
	if($count_replay > 0){ $is_replay = '1'; } else { $is_replay = '0'; }
	if($rec_replay == 'on'){ $rec_replay = 'on'; } else { $rec_replay = 'off'; }
	if($rec_replay == 'off' and $is_replay == '1'){ $hide = '1'; } else { $hide = '0'; }
	//
	
	// dont write ident timer in db
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) AS count_hash FROM `timer` WHERE `hash` = "'.$hash.'" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_hash);
	$stmt->fetch();
	$stmt->close();
	//
	if($count_hash == 0){
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO timer (e2eventtitle, title_enc, e2eventdescription, description_enc, e2eventdescriptionextended, descriptionextended_enc, e2eventservicename, servicename_enc, e2eventservicereference, search_term, search_option, exclude_term, exclude_area, record_location, e2eventstart, e2eventend, timer_request, hash, channel_hash, status, rec_replay, is_replay, hide)
	values ('$e2eventtitle','$title_enc','$e2eventdescription','$description_enc','$e2eventdescriptionextended','$descriptionextended_enc','$e2eventservicename','$servicename_enc','$e2eventservicereference','$raw_term','$search_option','$exclude_term','$exclude_area','$e2location','$e2eventstart','$e2eventend','$timer_request','$hash','$channel_hash','waiting','$rec_replay','$is_replay','$hide')");
	} //
	}
	}
	}
//	}
	}
	}
	}
	// check record status
	$sql = "SELECT * FROM `timer`";
	
	if ($result3 = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result3)) {
	{
	$id = $obj->id;
	$rec_replay = $obj->rec_replay;
	$is_replay = $obj->is_replay;
	
	if ($time > $obj->e2eventstart and $time < $obj->e2eventend )
	{
	$record_status = 'a_recording'; }
	
	if ($time < $obj->e2eventstart and $time < $obj->e2eventend)
	{
	$record_status = 'b_incoming'; }
	
	if ($time > $obj->e2eventend)
	{
	$record_status = 'c_expired'; }

	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `record_status` = '".$record_status."' WHERE `id` = '$id' "); 
	
	// delete replay timer
	if($rec_replay == 'off'){ $sql = mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `rec_replay` = 'off' AND `is_replay` = '1' AND `id` = '$id' "); }
	}
	}
	}
	// delete timer duplicates
	//$sql = mysqli_query($dbmysqli, "DELETE FROM timer USING timer, timer as Dup WHERE NOT timer.id = Dup.id AND timer.id > Dup.id AND timer.timer_request = Dup.timer_request");
	$sql = mysqli_query($dbmysqli, "OPTIMIZE TABLE `timer`");
	
	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data: Timer from saved search, written in database!\n\n";
	
//close db
mysqli_close($dbmysqli);
?>