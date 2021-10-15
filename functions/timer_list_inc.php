<?php 
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ''){ $_REQUEST['action'] = ''; }
	$action = $_REQUEST['action'];
	
	if(!isset($_REQUEST['timer_id']) or $_REQUEST['timer_id'] == ''){ $_REQUEST['timer_id'] = ''; }
	$timer_id = $_REQUEST['timer_id'];
	
	if($timer_id != '')
	{
	// hide single timer
	if($action == 'hide')
	{
	// track keywords
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$timer_id."' ");
	$result = mysqli_fetch_assoc($sql);
	$title = strtolower($result['e2eventtitle']);
	$description = strtolower($result['e2eventdescription']);
	$descriptionextended = strtolower($result['e2eventdescriptionextended']);
	$search_term = strtolower($result['search_term']);
	
	$total_string = ($title.$description.$descriptionextended);

	$total_string = explode(' ',$total_string);
	foreach ($total_string as $key => $word){
	if(strlen($word) == 4 or strlen($word) > 4){ $words[] = $word; }
	}
	$count = array_count_values($words);
	array_multisort($count,SORT_DESC);
	
	$i = 1;
	foreach ($count as $keyword => $value){
	if($value == 3 or $value > 3){
	$i++;
	$summary_title = substr_count($title, $keyword);
	$summary_description = substr_count($description, $keyword);
	$summary_extdescription = substr_count($descriptionextended, $keyword);
	$summary_total = $summary_title + $summary_description + $summary_extdescription;
	
	$keyword_lower = strtolower($keyword);

	$string_hash = hash('md4',$keyword_lower.$search_term.$summary_title.$summary_description.$summary_extdescription);

	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `keywords` WHERE `hash` = '".$string_hash."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($summary < 1)
	{
	mysqli_query($dbmysqli, "INSERT INTO `keywords` 
	(
	`searchterm`, 
	`word`, 
	`sum_total`, 
	`sum_title`, 
	`sum_description`, 
	`sum_extdescription`, 
	`hash`, 
	`timestamp`
	) VALUES (
	'".$search_term."', 
	'".$keyword_lower."', 
	'".$summary_total."', 
	'".$summary_title."', 
	'".$summary_description."', 
	'".$summary_extdescription."', 
	'".$string_hash."', 
	'".$time."' 
	)"
	);
	
	} else {
	
	mysqli_query($dbmysqli, "UPDATE `keywords` SET `counter` = `counter` + 1 WHERE `hash` = '".$string_hash."' ");
	}
	}
	}
	
	mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '1' WHERE `id` = '".$timer_id."' ");
	echo "data:hided";
	exit;
	} // hide single timer
	
	// unhide single timer
	if($action == 'unhide')
	{
	sleep(1);
	$timer_id = $_REQUEST['timer_id'];
	mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '0' WHERE `id` = '".$timer_id."' ");
	echo "data:unhided";
	exit;
	} // unhide single timer
	
	// add broadcast to ignore list
	if($action == "ignore")
	{
	sleep(1);
	$timer_id = $_REQUEST['timer_id'];
	$sql_0 = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$timer_id."' ");
	$result_0 = mysqli_fetch_assoc($sql_0);
	$e2eventtitle = $result_0['e2eventtitle'];
	$e2eventdescription = $result_0['e2eventdescription'];
	$search_term = $result_0['search_term'];
	$hash = hash('md4',$e2eventtitle.$e2eventdescription);
	
	$sql_1 = mysqli_query($dbmysqli, "SELECT COUNT(hash) FROM `ignore_list` WHERE `hash` = '".$hash."' ");
	$result_1 = mysqli_fetch_row($sql_1);
	$summary = $result_1[0];
	
	if($summary == 0)
	{
	mysqli_query($dbmysqli, "INSERT INTO `ignore_list` 
	(
	e2eventtitle, 
	e2eventdescription, 
	search_term, 
	timestamp, 
	hash, 
	activ 
	) VALUES (
	'".$e2eventtitle."', 
	'".$e2eventdescription."', 
	'".$search_term."', 
	'".time()."', 
	'".$hash."', 
	'1'
	)
	");
	echo 'data:ignored';
	}
	
	else { echo 'data:already_ignored'; }
	
	exit;
	} // add broadcast to ignore list


	// delete timer
	if($action == 'delete')
	{
	sleep(1);
	if(!isset($_REQUEST['timer_id']) or $_REQUEST['timer_id'] == ''){ $_REQUEST['timer_id'] = ''; }
	if(!isset($_REQUEST['hash']) or $_REQUEST['hash'] == ''){ $_REQUEST['hash'] = ''; }
	if(!isset($_REQUEST['delete_from_box']) or $_REQUEST['delete_from_box'] == ''){ $_REQUEST['delete_from_box'] = ''; }
	if(!isset($_REQUEST['delete_from_db']) or $_REQUEST['delete_from_db'] == ''){ $_REQUEST['delete_from_db'] = ''; }
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == ''){ $_REQUEST['device'] = ''; }
	
	$timer_id = $_REQUEST['timer_id'];
	$hash = $_REQUEST['hash'];
	$delete_from_db = $_REQUEST['delete_from_db'];
	$device = $_REQUEST['device'];
	
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `timer` WHERE `id` = '".$timer_id."' AND `device` = '".$device."' ");
	$result = mysqli_fetch_row($sql);
	
	if($result[0] == 0){ echo 'data:incorrect'; exit; }
	
	$sql_2 = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$timer_id."' ");
	$result_2 = mysqli_fetch_assoc($sql_2);
	$e2eventservicereference = $result_2['e2eventservicereference'];
	$e2eventstart = $result_2['e2eventstart'];
	$e2eventend = $result_2['e2eventend'];
	$record_location = $result_2['record_location'];
	$status = $result_2['status'];
	
	if($status == 'manual' or $status == 'sent')
	{
	// unmark epg entry
	mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '0' WHERE `hash` = '".$hash."' ");
	}
	
	// delete timer from different device
	if($device != '0')
	{
	$sql_3 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result_3 = mysqli_fetch_assoc($sql_3);
	$device_description = $result_3['device_description'];
	$box_ip = $result_3['device_ip'];
	$box_user = $result_3['device_user'];
	$box_password = $result_3['device_user'];
	
	$webrequest = stream_context_create(array (
	'http' => array (
	'method' => 'POST',
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$deleteTimer = $url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.$session_part_2;
	$deleteTimer_request = @file_get_contents($deleteTimer, false, $webrequest);
	// delete timer from different device
	
	} else {
	
	$deleteTimer = $url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.$session_part_2;
	$deleteTimer_request = @file_get_contents($deleteTimer, false, $webrequest);
	}
	
	mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'waiting' WHERE `id` = '".$timer_id."' ");
	
	} // delete from default receiver
	
	// delete timer from database
	if($delete_from_db == '1')
	{
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `timer` WHERE `id` = '".$timer_id."' AND `device` = '".$device."' ");
	$result = mysqli_fetch_row($sql);
	
	if($result[0] == 0){ echo 'data:incorrect'; exit; }
	
	mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `id` = '".$timer_id."' AND `device` = '".$device."' ");
	
	echo "data:deleted_db";
	exit;
	
	} else {
	
	echo "data:deleted";
	exit;
	}
	} // delete timer
	//
	
	// device dropdown
	$sql_4 = "SELECT * FROM `device_list` ORDER BY `id` ASC";
	if ($result_4 = mysqli_query($dbmysqli,$sql_4))
	{
	while ($obj_4 = mysqli_fetch_object($result_4)){
	{
	$id = $obj_4->id;
	$device_description = rawurldecode($obj_4->device_description);
	if(!isset($device_dropdown) or $device_dropdown == ''){ $device_dropdown = ''; }
	$device_dropdown .= '<option name="'.$device_description.'" value="'.$id.'">'.$device_description.'</option>'; 
	}
	}
	}
	
	// unhide
	if($action == 'unhide')
	{
	$sql_5 = "SELECT * FROM `timer` WHERE `expired` = '0' ORDER BY `e2eventstart` ASC, `record_status` ASC"; 
	
	} else {
	
	$sql_5 = "SELECT * FROM `timer` WHERE `expired` = '0' AND `hide` = '0' ORDER BY `e2eventstart` ASC, `record_status` ASC";
	}

	if($result_5 = mysqli_query($dbmysqli,$sql_5))
	{
	while ($obj = mysqli_fetch_object($result_5)) {
	
	if(!isset($timerlist) or $timerlist == ''){ $timerlist = ''; }

	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	$search_term = rawurldecode($obj->search_term);
	$exclude_channel = rawurldecode($obj->exclude_channel);
	$exclude_title = rawurldecode($obj->exclude_title);
	$exclude_description = rawurldecode($obj->exclude_description);
	$exclude_extdescription = rawurldecode($obj->exclude_extdescription);
	$e2eventstart = $obj->e2eventstart;
	$e2eventend = $obj->e2eventend;
	$record_status = $obj->record_status;
	$rec_replay = $obj->rec_replay;
	$is_replay = $obj->is_replay;
	$hidden = $obj->hide;
	$device_no = $obj->device;
	$search_id = $obj->search_id;
	$timer_conflict = $obj->conflict;
	$timer_repeat = $obj->timer_repeat;
	$timer_repeat_d = $obj->timer_repeat_d;
	
	if(!isset($exclude_channel) or $exclude_channel == ''){ $exclude_channel = ''; }
	if(!isset($exclude_title) or $exclude_title == ''){ $exclude_title = ''; }
	if(!isset($exclude_description) or $exclude_description == ''){ $exclude_description = ''; }
	if(!isset($exclude_extdescription) or $exclude_extdescription == ''){ $exclude_extdescription = ''; }

	$exclude_channel = str_replace(';', '; ', $exclude_channel);
	$exclude_title = str_replace(';', '; ', $exclude_title);
	$exclude_description = str_replace(';', '; ', $exclude_description);
	$exclude_extdescription = str_replace(';', '; ', $exclude_extdescription);
	
	if($time_format == '1')
	{
	// time format 1	
	$date_start = date('l, d.m.Y', $e2eventstart);
	$broadcast_time = date('H:i', $e2eventstart).' - '.date('H:i', $e2eventend);
	$broadcast_date = date('d.m', $e2eventstart);
	$time_class = 'cnt_time';
	}
	
	if($time_format == '2')
	{
	// time format 2
	$date_start = date('l n/d/Y', $e2eventstart);
	$broadcast_time = date('g:i A', $e2eventstart).' - '.date('g:i A', $e2eventend);
	$broadcast_date = date('n/d', $e2eventstart);
	$time_class = 'cnt_time_2';
	}
	//

if($obj->status == 'waiting')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#D9534F" title="not sent"></i>
	';
	}
		
if($obj->status == 'rec_deleted')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-hand-o-up fa-1x" style="color:#000" title="manual"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#D9534F" title="not sent"></i>
	';
	}
		
if($obj->status == 'sent')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#5CB85C" title="sent"></i>
	';
	if($timer_conflict == '1')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#F0AD4E" title="Conflict on Receiver"></i>
	';
	}
	}
				
if($obj->status == 'manual')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-hand-o-up fa-1x" style="color:#000" title="manual"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#5CB85C" title="sent"></i>
	';
	if($timer_conflict == '1')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#F0AD4E" title="Conflict on Receiver"></i>
	';
	}
	}
				
if($obj->record_status == 'a_recording')
	{
	$record_status = '
	<i class="glyphicon glyphicon-transfer" style="color:#428BCA" title="now running"></i>
	';
	}
				
if($obj->record_status == 'b_incoming')
	{
	$record_status = '
	<i class="glyphicon glyphicon-time" style="color:#428BCA" title="incoming event"></i>
	';
	}
				
if($obj->record_status == 'c_expired')
	{
	$record_status = '
	<i class="glyphicon glyphicon-time" style="color:#F0AD4E" title="expired event"></i>
	';
	}
	
if($obj->timer_repeat != '0')
	{
	$timer_repeat_d = '';
	$days = explode(';' , $obj->timer_repeat_d);
	foreach($days as $i => $key){ $i > 0; 
	if($key == '1'){ $timer_repeat_d .= 'Monday; '; }
	if($key == '2'){ $timer_repeat_d .= 'Tuesday; '; }
	if($key == '4'){ $timer_repeat_d .= 'Wednesday; '; }
	if($key == '8'){ $timer_repeat_d .= 'Thursday; '; }
	if($key == '16'){ $timer_repeat_d .= 'Friday; '; }
	if($key == '32'){ $timer_repeat_d .= 'Saturday; '; }
	if($key == '64'){ $timer_repeat_d .= 'Sunday; '; }
	if($key == '31'){ $timer_repeat_d .= 'Monday - Friday; '; }
	if($key == '127'){ $timer_repeat_d .= 'Monday - Sunday; '; }
	}
	
	$record_status = '
	<i class="glyphicon glyphicon-time" style="color:#CCC" title="repeating event"></i>
	';
	$repeat_timer = ' | <i class="fa fa-repeat"></i> '.$timer_repeat_d;
	} else { $repeat_timer = ''; }
	
if($obj->record_location == 'zap_timer' and $obj->status == 'manual')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-arrow-up fa-1x" style="color:#000" title="Zap timer"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#5CB85C" title="sent"></i>
	';
	if($timer_conflict == '1')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-arrow-up fa-1x" style="color:#000" title="Zap timer"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#F0AD4E" title="Conflict on Receiver"></i>
	';
	}
	}
	
if($obj->record_location == 'zap_timer' and $obj->status == 'waiting')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-arrow-up fa-1x" style="color:#000" title="Zap timer"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#D9534F" title="sent"></i>
	';
	if($timer_conflict == '1')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-arrow-up fa-1x" style="color:#000" title="Zap timer"></i>  
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#F0AD4E" title="Conflict on Receiver"></i>
	';
	}
	}
	
if($imdb_symbol == '1')
	{
	$imdb_broadcast = '<a href="https://www.imdb.com/find?ref_=nv_sr_fn&q='.$obj->e2eventtitle.'" target="_blank" title="Info on IMDb">
	<i class="fa fa-info-circle fa-1x"></i></a>'; 
	} else { 
	$imdb_broadcast = ''; 
	}
		
	if($obj->exclude_channel != ''){ $channel_status = 'Excluded in <strong>channel</strong>: '.$exclude_channel.'<br>'; } else { $channel_status = ''; }
	if($obj->exclude_title != ''){ $title_status = 'Excluded in <strong>title</strong>: '.$exclude_title.'<br>'; } else { $title_status = ''; }
	if($obj->exclude_description != ''){ $description_status = 'Excluded in <strong>description</strong>: '.$exclude_description.'<br>'; } else { $description_status = ''; }
	if($obj->exclude_extdescription != ''){ $extdescription_status = 'Excluded in <strong>extended description</strong>: '.$exclude_extdescription.'<br>'; } else { $extdescription_status = ''; }
	if($channel_status != '' or $title_status != '' or $description_status != '' or $extdescription_status != ''){
	$show_exclude_text = '<div class="spacer_5"></div><a id="'.$obj->id.'" style="cursor:pointer;" onclick="timerlist_show_exclude(this.id)">Show excluded term(s)</a>'; } else { $show_exclude_text = ''; }
	
	if($obj->rec_replay == 'on'){ $replay_status = '| Timer for replays: '.$rec_replay.''; } else { $replay_status = ''; }
	$spacer = '';
	
	// get record location id
	$sql_6 = mysqli_query($dbmysqli, "SELECT id FROM `record_locations` WHERE `e2location` = '".$obj->record_location."' ");
	$result_6 = mysqli_fetch_assoc($sql_6);
	$rec_location_id = $result_6['id'];
	
	// device
	if($obj->device != '0')
	{ 
	$sql_7 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$obj->device."' ");
	$result_7 = mysqli_fetch_assoc($sql_7);
	$device_name = rawurldecode($result_7['device_description']);
	$device_color = $result_7['device_color'];
	} else {
	$device_name = 'default'; 
	$device_color = "#DDDDDD";
	}
		
	if($is_replay == '1'){ $replay_sign = '<i class="fa fa-repeat"></i>'; } else { $replay_sign = ''; }
	
	if($e2eventend < time() and $timer_repeat_d != '0'){ $tr_disabled = 'disabled'; $tr_checked = 'checked'; } else { $tr_disabled = ''; $tr_checked = ''; }
	
	if($hidden == '1')
	{ 
	$hidden_class = 'class="opac_70"'; 
	$hide_button = "<input id=\"timerlist_unhide_timer_btn_$obj->id\" name=\"null\" type=\"submit\" onClick=\"timerlist_unhide_timer(this.id,this.name)\" value=\"UNHIDE\" class=\"btn btn-primary btn-xs\" title=\"Unhide timer from list\"/>"; 
	
	} else { 
	
	$hidden_class = ''; 
	$hide_button = "<input id=\"timerlist_hide_timer_btn_$obj->id\" name=\"hide_$obj->hash\" type=\"submit\" onClick=\"timerlist_hide_timer(this.id,this.name)\" value=\"HIDE\" class=\"btn btn-primary btn-xs\" title=\"Hide timer from list\"/>";
	}
	
	if(!isset($device_dropdown) or $device_dropdown == ''){ $device_dropdown = ''; }
	
	if($search_term != '')
	{
	$scroll_search = "<strong>$search_term</strong> <i id=\"sid_$search_id\" onclick=\"scroll_saved_search(this.id,'timer_scroll_$obj->id')\" class=\"fa fa-edit fa-1x\" style=\"cursor:pointer\" title=\"Edit saved search\"></i>";
	} else { $scroll_search = ''; }
	
	$searcharea = '';
	if($obj->search_option == 'title'){ $searcharea = 'Title'; }
	if($obj->search_option == 'description'){ $searcharea = 'Description'; }
	if($obj->search_option == 'extdescription'){ $searcharea = 'Extended description'; }
	
	if($search_term == '' and $searcharea == ''){ $search_info = ''; } else { $search_info = 'Searchterm: '.$scroll_search.' | Searcharea: '.$searcharea.' | '; }
	
	// broadcast length
	if($descriptionextended_enc == ''){ $spacer_d = ''; } else { $spacer_d = '<br>'; }
	$event_duration = $obj->e2eventend - $obj->e2eventstart;
	$event_duration = $event_duration / 60;
	$event_duration = $spacer_d.round($event_duration, 0).' min.';
	
	if($obj->record_location == 'zap_timer')
	{
	$sql_8 = mysqli_query($dbmysqli, "SELECT e2eventstart, e2eventend FROM `epg_data` WHERE `hash` LIKE '".$obj->hash."' ");
	$result_8 = mysqli_fetch_assoc($sql_8);
	$event_duration = $result_8['e2eventend'] - $result_8['e2eventstart'];
	$event_duration = $event_duration / 60;
	
	if($event_duration == 0)
	{
	$event_duration = $obj->e2eventend - $obj->e2eventstart;
	$event_duration = $event_duration / 60;
	}
	$event_duration = $spacer_d.round($event_duration, 0).' min.';
	
	$obj->record_location = 'Zap timer'; 
	$action = 'zap'; 
	$btn_status = 'disabled'; 
	} else { 
	$action = 'record'; 
	$btn_status = ''; 
	}
	
	// highlight term
	if($highlight_term !== '')
	{
	$terms = explode(rawurldecode(';'), rawurldecode($highlight_term));
	foreach($terms as $i =>$key) { $i > 0;
	$descriptionextended_enc = str_replace($key, '<strong>'.$key.'</strong>', $descriptionextended_enc);
	}
	}
	
	$timerlist = $timerlist."<div id=\"timerlist_div_outer_$obj->id\" $hidden_class>
	<div id=\"timerlist_inner_$obj->id\" style=\"border: 1px solid $device_color !important;\">
	<div id=\"cnt_checkbox\"><input id=\"box_$obj->id\" type=\"checkbox\" name=\"timerlist_checkbox[]\" value=\"$obj->id\" onclick=\"count_selected()\" $tr_disabled/>
	</div>
	<div id=\"timer_$obj->id\" style=\"cursor: pointer;\" onclick=\"timerlist_desc(this.id);\">
	<div id=\"$time_class\"> $status  $record_status | $broadcast_time | $broadcast_date
	</div>
	<div id=\"cnt_title\">
	  $replay_sign $title_enc
	</div>
	<div id=\"cnt_channel\">
	  $servicename_enc
	</div>
	<div style=\"clear:both\"></div>
	</div>
	  <div id=\"timerlist_cnt_$obj->id\" style=\"display:none;\">
	  <div class=\"spacer_5\"></div>
	  <strong>$date_start</strong>
	  <div class=\"spacer_5\"></div>
	  <div id=\"timerlist_div_$obj->id\"><div class=\"spacer_5\"></div>
	  $description_enc<div class=\"spacer_5\"></div>
	  $descriptionextended_enc $event_duration
	  <div class=\"spacer_5\"></div>
	  </div>
	  $imdb_broadcast
	  <a href=\"search.php?searchterm=$title_enc&option=title\" target=\"_blank\" title=\"Search title\"><i class=\"fa fa-search fa-1x\"></i></a>
	  $search_info Record location: $obj->record_location | Receiver: <span id=\"device_name_$obj->id\">$device_name</span> $replay_status $show_exclude_text $repeat_timer
	  <input id=\"timerlist_device_no_$obj->id\" type=\"hidden\" value=\"$device_no\">
	  <span id=\"timerlist_rec_location_$obj->hash\" style=\"display:none;\">$rec_location_id</span>  
	<div class=\"spacer_5\"></div>
	<span>Receiver: </span>
	<select id=\"timerlist_device_dropdown_$obj->id\" onChange=\"change_timerlist_device(this.id,'$action')\">
	<option selected disabled>$device_name</option>
	<option name=\"default\" value=\"0\">default</option>
	$device_dropdown
	</select>
	<span id=\"rec_location_device_$obj->id\"></span>
	<div class=\"spacer_5\"></div>
	  <div id=\"timerlist_excluded_terms_$obj->id\" style=\"display:none;\">
	  $channel_status $spacer $title_status $spacer $description_status $spacer $extdescription_status
	  <div class=\"spacer_5\"></div>
	  </div>
	  <div class=\"spacer_5\"></div>
	  <input id=\"timerlist_send_timer_btn_$obj->hash\" name=\"$obj->id\" type=\"submit\" onClick=\"timerlist_send_timer(this.id,this.name,'record')\" value=\"SEND\" class=\"btn btn-success btn-xs\" title=\"Send timer to Receiver\" $btn_status $tr_disabled/>
	  <input id=\"timerlist_zap_timer_btn_$obj->hash\" name=\"$obj->id\" type=\"submit\" onClick=\"timerlist_send_timer(this.id,this.name,'zap')\" value=\"ZAP TIMER\" class=\"btn btn-warning btn-xs\" title=\"Send zap timer to Receiver\" $tr_disabled/>
	  $hide_button
	  <input id=\"ignore_timer_btn_$obj->id\" type=\"submit\" onclick=\"timerlist_ignore(this.id);\" value=\"IGNORE\" class=\"btn btn-default btn-xs\" title=\"Add broadcast to ignore list\">
	  <input id=\"delete_timer_btn_$obj->id\" type=\"submit\" onClick=\"timerlist_delete_timer(this.id,'$obj->hash')\" value=\"DELETE\" class=\"btn btn-danger btn-xs\" title=\"Delete timer from Receiver\"/>
	  <label style=\"font-weight: normal;\"><span class=\"del_checkbox\"><input id=\"timerlist_delete_db_$obj->id\" type=\"checkbox\" $tr_checked $tr_disabled> Delete also from database</span></label>
	  <span id=\"timerlist_status_$obj->id\"></span>
	  </div>
	</div>
	<div style=\"background-color:#FFF\" class=\"spacer_10\"></div>
	</div>
	";
	}
	}
	if(!isset($timerlist) or $timerlist == '') { $timerlist = 'No timer present..'; } 
	
	echo $timerlist;

?>