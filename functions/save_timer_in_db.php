<?php 
//
	sleep(5);
	
	include("../inc/dashboard_config.php");

	$sql_1 = "SELECT * FROM `saved_search` WHERE `crawled` = '0' and `activ` = 'yes' ORDER BY `searchterm` ASC";
	
	if($result_1 = mysqli_query($dbmysqli,$sql_1))
	{
	while ($obj = mysqli_fetch_object($result_1)) {
	{
	$id = $obj->id;
	$raw_term = $obj->searchterm;
	$search_option = $obj->search_option;
	$exclude_channel = $obj->exclude_channel;
	$exclude_title = $obj->exclude_title;
	$exclude_description = $obj->exclude_description;
	$exclude_extdescription = $obj->exclude_extdescription;
	$e2location = $obj->e2location;
	$e2eventservicereference = $obj->e2eventservicereference;
	$activ = $obj->activ;
	$rec_replay = $obj->rec_replay;
	$device = $obj->device;
	
	// search only in selected channel
	if($e2eventservicereference !== 'NULL')
	{
	$search_include = 'WHERE e2eventservicereference = "'.$e2eventservicereference.'" AND'; 
	$search_include2 = 'OR e2eventservicereference = "'.$e2eventservicereference.'" AND';
	
	} else { 
	
	$search_include = 'WHERE'; 
	$search_include2 = 'OR';
	}
	
	$exclude_time = 'AND `e2eventend` > '.$time.'';
	
	// exclude channel
	if($exclude_channel !== ''){ 
	$tags = explode(rawurlencode(';') , $exclude_channel);
	foreach($tags as $i =>$key) { $i > 0;
	if(!isset($exclude_channel_part) or $exclude_channel_part == '') { $exclude_channel_part = ''; }
	if(!isset($key) or $key == ''){ $search_string = ''; } else { $search_string = 'AND `servicename_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_channel_part = $exclude_channel_part.$search_string;
	}
	} else { $exclude_channel_part = ''; }
	
	// exclude title
	if($exclude_title !== ''){
	$tags = explode(rawurlencode(';') , $exclude_title);
	foreach($tags as $i =>$key) { $i > 0;
	if(!isset($exclude_title_part) or $exclude_title_part == '') { $exclude_title_part = ''; }
	if(!isset($key) or $key == ''){ $search_string = ''; } else { $search_string = 'AND `title_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_title_part = $exclude_title_part.$search_string;
	}
	} else { $exclude_title_part = ''; }
	
	// exclude description
	if($exclude_description !== ''){
	$tags = explode(rawurlencode(';') , $exclude_description);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_description_part) or $exclude_description_part == '') { $exclude_description_part = ''; }
	if(!isset($key) or $key == ''){ $search_string = ''; } else { $search_string = 'AND `description_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_description_part = $exclude_description_part.$search_string;
	}
	} else { $exclude_description_part = ''; }
	
	// exclude extended description
	if($exclude_extdescription !== ''){
	$tags = explode(rawurlencode(';') , $exclude_extdescription);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_extdescription_part) or $exclude_extdescription_part == '') { $exclude_extdescription_part = ''; }
	if(!isset($key) or $key == ''){ $search_string = ''; } else { $search_string = 'AND `descriptionextended_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_extdescription_part = $exclude_extdescription_part.$search_string;
	}
	} else { $exclude_extdescription_part = ''; }
	
	// search all
	if ($search_option == 'all' or $search_option == '')
	{
	$sql_2 = 'SELECT * FROM `epg_data` '.$search_include.' MATCH (title_enc, description_enc, descriptionextended_enc) AGAINST 
	("%'.$raw_term.'%") 
	'.$exclude_time.' 
	'.$search_include2.' `title_enc` LIKE "%'.$raw_term.'%" 
	'.$exclude_title_part.' 
	'.$exclude_description_part.' 
	'.$exclude_extdescription_part.' 
	'.$exclude_channel_part.' 
	'.$exclude_time.' 
	'.$search_include2.' `description_enc` LIKE "%'.$raw_term.'%" 
	'.$exclude_title_part.' 
	'.$exclude_description_part.' 
	'.$exclude_extdescription_part.' 
	'.$exclude_channel_part.' 
	'.$exclude_time.' 
	'.$search_include2.' `descriptionextended_enc` LIKE "%'.$raw_term.'%" 
	'.$exclude_title_part.' 
	'.$exclude_description_part.' 
	'.$exclude_extdescription_part.' 
	'.$exclude_channel_part.' 
	'.$exclude_time.' ORDER BY `e2eventstart` ASC'; 
	}
	
	// search title
	if($search_option == 'title')
	{
	$sql_2 = 'SELECT * FROM `epg_data` '.$search_include.' `title_enc` LIKE "%'.$raw_term.'%" '.$exclude_channel_part.' '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	}
	
	// search description
	if($search_option == 'description')
	{
	$sql_2 = 'SELECT * FROM `epg_data` '.$search_include.' `description_enc` LIKE "%'.$raw_term.'%" '.$exclude_channel_part.' '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	}
	
	// search extended description
	if($search_option == 'extdescription')
	{
	$sql_2 = 'SELECT * FROM `epg_data` '.$search_include.' `descriptionextended_enc` LIKE "%'.$raw_term.'%" '.$exclude_channel_part.' '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	}

	if($result_2 = mysqli_query($dbmysqli,$sql_2))
	{
	while ($obj = mysqli_fetch_object($result_2)) {	
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
	
	// record device
	if($device != 0)
	{
	$sql_3 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result_3 = mysqli_fetch_assoc($sql_3);
	$box_ip = $result_3['device_ip'];
	$url_format = $result_3['url_format'];
	}
	
	$timer_request = $url_format.'://'.$box_ip.'/web/timeradd?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'&name='.$title_enc.'&description='.$description_enc.'&dirname='.$e2location.'&afterevent=3';
	
	// request with eventid
	//$timer_request = "http://$box_ip/web/timeraddbyeventid?sRef=".$e2eventservicereference."&eventid=".$e2eventid."&dirname=".$e2location."";
	
	// remove " and ' from request
	$timer_request = str_replace('%22', '%60', $timer_request);
	$timer_request = str_replace('%27', '%60', $timer_request);
	
	// check if timer exist for replay record
	$sql_4 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `timer` WHERE `title_enc` = '".$title_enc."' AND `description_enc` = '".$description_enc."' AND `device` = '".$device."' ");
	$result_4 = mysqli_fetch_row($sql_4);
	$count_replay = $result_4[0];
	if($count_replay > 0){ $is_replay = '1'; } else { $is_replay = '0'; }
	if($rec_replay == 'on'){ $rec_replay = 'on'; } else { $rec_replay = 'off'; }
	if($rec_replay == 'off' and $is_replay == '1'){ $hide = '1'; } else { $hide = '0'; }
	
	// check if broadcast is in timer ignore list
	$ignore_hash = hash('md4',$e2eventtitle.$e2eventdescription);
	$sql_5 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `ignore_list` WHERE `hash` = '".$ignore_hash."' AND `activ` = '1' ");
	$result_5 = mysqli_fetch_row($sql_5);
	$count_ignore_hash = $result_5[0];

	if($count_ignore_hash == 0)
	{
	// dont write ident timer in db
	$sql_6 = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `timer` WHERE `hash` = '".$hash."' AND `device` LIKE '".$device."' ");
	$result_6 = mysqli_fetch_row($sql_6);
	$count_hash = $result_6[0];

	if($count_hash == 0)
	{
	mysqli_query($dbmysqli, "INSERT INTO timer (
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
	rec_replay, 
	is_replay, 
	hide, 
	device,  
	search_id 
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
	'$raw_term', 
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
	'waiting', 
	'$rec_replay', 
	'$is_replay', 
	'$hide', 
	'$device', 
	'$id'
	)
	");
	}
	}
	}
	}
	}

	// update last crawl
	sleep(1);
	mysqli_query($dbmysqli, "UPDATE `saved_search` SET `last_crawl` = '".time()."', `crawled` = '1' WHERE `id` = '".$id."' ");

	}
	}
	}
	
	// check record status
	$sql_7 = "SELECT * FROM `timer` WHERE `record_status` NOT LIKE 'c_expired' ";
	
	if($result_7 = mysqli_query($dbmysqli,$sql_7))
	{
	while ($obj = mysqli_fetch_object($result_7)) {
	{
	$id = $obj->id;
	$rec_replay = $obj->rec_replay;
	$is_replay = $obj->is_replay;
	
	if($time > $obj->e2eventstart and $time < $obj->e2eventend){ $record_status = 'a_recording'; }
	if($time < $obj->e2eventstart and $time < $obj->e2eventend){ $record_status = 'b_incoming'; }
	if($time > $obj->e2eventend){ $record_status = 'c_expired'; }

	mysqli_query($dbmysqli, "UPDATE `timer` SET `record_status` = '".$record_status."' WHERE `id` = '".$id."' "); 
	
	// delete replay timer
	if($rec_replay == 'off'){ mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `rec_replay` = 'off' AND `is_replay` = '1' AND `id` = '".$id."' "); }
	}
	}
	}
	// delete timer duplicates
	//$sql = mysqli_query($dbmysqli, "DELETE FROM timer USING timer, timer as Dup WHERE NOT timer.id = Dup.id AND timer.id > Dup.id AND timer.timer_request = Dup.timer_request");
	mysqli_query($dbmysqli, "OPTIMIZE TABLE `timer`");
	
	// answer for ajax
	echo "data:done";

?>