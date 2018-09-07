<?php 
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ""){ $_REQUEST['action'] = ""; }
	
	$action = $_REQUEST['action'];
	
	if(!isset($_REQUEST['timer_id']) or $_REQUEST['timer_id'] == ""){ $_REQUEST['timer_id'] = ""; 
	
	} else {
	
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	// hide single timer
	if($action == 'hide'){
	$timer_id = $_REQUEST['timer_id'];
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

	$sql = mysqli_query($dbmysqli, "SELECT COUNT(*) FROM `keywords` WHERE `hash` = '".$string_hash."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($summary < 1){
	$sql = mysqli_query($dbmysqli, "INSERT INTO `keywords` (
	`searchterm`, 
	`word`, 
	`sum_total`, 
	`sum_title`, 
	`sum_description`, 
	`sum_extdescription`, 
	`hash`, 
	`timestamp`) VALUES (
	'".$search_term."', 
	'".$keyword_lower."', 
	'".$summary_total."', 
	'".$summary_title."', 
	'".$summary_description."', 
	'".$summary_extdescription."', 
	'".$string_hash."', 
	'".$time."' )");
	
	} else {
	
	$sql = mysqli_query($dbmysqli, "UPDATE `keywords` SET `counter` = `counter` + 1 WHERE `hash` = '".$string_hash."' ");
	}
	}
	}
	//
	// hide
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '1' WHERE `id` = '".$timer_id."' ");
	echo "data:hided";
	exit;
	}
	
	// unhide single timer
	if($action == 'unhide'){
	sleep(1);
	$timer_id = $_REQUEST['timer_id'];
	$sql =  mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '0' WHERE `id` = '".$timer_id."' ");
	echo "data:unhided";
	exit;
	}

	if($action == 'delete'){
	if(!isset($_REQUEST['delete_from_box']) or $_REQUEST['delete_from_box'] == "") { $_REQUEST['delete_from_box'] = ""; }
	if(!isset($_REQUEST['delete_from_db']) or $_REQUEST['delete_from_db'] == "") { $_REQUEST['delete_from_db'] = ""; }
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == "") { $_REQUEST['device'] = ""; }
	
	$timer_id = $_REQUEST['timer_id'];
	$delete_from_db = $_REQUEST['delete_from_db'];
	$device = $_REQUEST['device'];
	
	// unmark timer
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$timer_id."' ");
	$result = mysqli_fetch_assoc($sql);
	
	$e2eventservicereference = $result['e2eventservicereference'];
	$e2eventstart = $result['e2eventstart'];
	$e2eventend = $result['e2eventend'];
	$status = $result['status'];
	if($device == ""){ $device = $result['device']; }
	
	if($status == 'manual' or $status == 'sent'){
	$sql = mysqli_query($dbmysqli, "UPDATE `epg_data` SET `timer` = '0' WHERE `hash` = '".$result['hash']."' ");
	}

	sleep(1);
	
	// delete timer from different device
	if($device != "0"){
	$sql2 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result2 = mysqli_fetch_assoc($sql2);
	$device_description = $result2['device_description'];
	$box_ip = $result2['device_ip'];
	$box_user = $result2['device_user'];
	$box_password = $result2['device_user'];
	
	$webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$deleteTimer = $url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'';
	$deleteTimer_request = @file_get_contents($deleteTimer, false, $webrequest);
	} // delete timer from different device
	
	else {
	
	// delete from default receiver
	$deleteTimer = $url_format.'://'.$box_ip.'/web/timerdelete?sRef='.$e2eventservicereference.'&begin='.$e2eventstart.'&end='.$e2eventend.'';
	$deleteTimer_request = @file_get_contents($deleteTimer, false, $webrequest);
	}
	
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'waiting' WHERE `id` = '".$timer_id."' ");
	}
	
	// delete timer from database
	if($delete_from_db == '1')
	{
	$sql = mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `id` = '".$timer_id."' ");
	//
	echo "data:deleted_db";
	exit;
	
	} else {
	
	echo "data:deleted";
	exit;
	}
	}
	
	// unhide
	if($action == 'unhide'){
	
	$sql = "SELECT * FROM `timer` WHERE `expired` = '0' ORDER BY `e2eventstart` ASC, `record_status` ASC"; 
	
	} else {
	
	$sql = "SELECT * FROM `timer` WHERE `expired` = '0' AND `hide` = '0' ORDER BY `e2eventstart` ASC, `record_status` ASC";
	}
	
	// device dropdown
	$sql4 = "SELECT * FROM `device_list` ORDER BY `id` ASC";
	if ($result4 = mysqli_query($dbmysqli,$sql4))
	{
	while ($obj4 = mysqli_fetch_object($result4)){
	{
	$id = $obj4->id;
	$device_description = rawurldecode($obj4->device_description);
	if(!isset($device_dropdown) or $device_dropdown == ""){ $device_dropdown = ""; }
	$device_dropdown = $device_dropdown."<option name=\"$device_description\" value=\"$id\">$device_description</option>"; 
	}
	}
	} // device dropdown

	if($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	
	if(!isset($timerlist) or $timerlist == "") { $timerlist = ""; }
	
	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	$search_term = rawurldecode($obj->search_term);
	$exclude_channel = rawurldecode($obj->exclude_channel);
	$exclude_title = rawurldecode($obj->exclude_title);
	$exclude_description = rawurldecode($obj->exclude_description);
	$exclude_extdescription = rawurldecode($obj->exclude_extdescription);
	$record_status = $obj->record_status;
	$rec_replay = $obj->rec_replay;
	$is_replay = $obj->is_replay;
	$hidden = $obj->hide;
	$device_no = $obj->device;
	$search_id = $obj->search_id;
	$timer_conflict = $obj->conflict;
	
	if(!isset($exclude_channel) or $exclude_channel == "") { $exclude_channel = ""; }
	if(!isset($exclude_title) or $exclude_title == "") { $exclude_title = ""; }
	if(!isset($exclude_description) or $exclude_description == "") { $exclude_description = ""; }
	if(!isset($exclude_extdescription) or $exclude_extdescription == "") { $exclude_extdescription = ""; }

	$exclude_channel = str_replace(";", "; ", $exclude_channel);
	$exclude_title = str_replace(";", "; ", $exclude_title);
	$exclude_description = str_replace(";", "; ", $exclude_description);
	$exclude_extdescription = str_replace(";", "; ", $exclude_extdescription);
	
	if($time_format == '1')
	{
	// time format 1
	$e2eventstart = $obj->e2eventstart;
	$date_start = date("l, d.m.Y", $e2eventstart);
	
	$e2eventend = $obj->e2eventend;
	$broadcast_time = date("H:i", $e2eventstart).' - '.date("H:i", $e2eventend);
	$broadcast_date = date("d.m", $e2eventstart);
	$time_class = 'cnt_time';
	}
	
	if($time_format == '2')
	{
	// time format 2
	$e2eventstart = $obj->e2eventstart;
	$date_start = date("l n/d/Y", $e2eventstart);
	
	$e2eventend = $obj->e2eventend;
	$broadcast_time = date("g:i A", $e2eventstart).' - '.date("g:i A", $e2eventend);
	$broadcast_date = date("n/d", $e2eventstart);
	$time_class = 'cnt_time_2';
	}
	//

if($obj->status == 'waiting')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i> | 
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#D9534F" title="not sent"></i>
	';
	}
		
if($obj->status == 'rec_deleted')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-hand-o-up fa-1x" style="color:#000" title="manual"></i> | 
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#D9534F" title="not sent"></i>
	';
	}
		
if($obj->status == 'sent')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i> | 
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#5CB85C" title="sent"></i>
	';
	if($timer_conflict == "1")
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i> | 
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#F0AD4E" title="Conflict on Receiver"></i>
	';
	}
	}
				
if($obj->status == 'manual')
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-hand-o-up fa-1x" style="color:#000" title="manual"></i> | 
	<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#5CB85C" title="sent"></i>
	';
	if($timer_conflict == "1")
	{
	$status = '
	<i id="tl_glyphicon_status_m_'.$obj->id.'" class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i> | 
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
		
	if($obj->exclude_channel != ''){ $channel_status = 'Excluded in <strong>channel</strong>: '.$exclude_channel.'<br>'; } else { $channel_status = ''; }
	if($obj->exclude_title != ''){ $title_status = 'Excluded in <strong>title</strong>: '.$exclude_title.'<br>'; } else { $title_status = ''; }
	if($obj->exclude_description != ''){ $description_status = 'Excluded in <strong>description</strong>: '.$exclude_description.'<br>'; } else { $description_status = ''; }
	if($obj->exclude_extdescription != ''){ $extdescription_status = 'Excluded in <strong>extended description</strong>: '.$exclude_extdescription.'<br>'; } else { $extdescription_status = ''; }
	if($channel_status != '' or $title_status != '' or $description_status != '' or $extdescription_status != ''){
	$show_exclude_text = "<div class=\"spacer_5\"></div><a id=\"$obj->id\" style=\"cursor:pointer;\" onclick=\"timerlist_show_exclude(this.id)\">Show excluded term(s)</a>"; } else { $show_exclude_text = ""; }
	
	if($obj->rec_replay == 'on'){ $replay_status = '| Timer for Replays: '.$rec_replay.''; } else { $replay_status = ''; }
	$spacer = '';
	
	// get record location id
	$sql2 = mysqli_query($dbmysqli, "SELECT * FROM `record_locations` WHERE `e2location` = '".$obj->record_location."' LIMIT 0,1");
	$result2 = mysqli_fetch_assoc($sql2);
	$rec_location_id = $result2['id'];
	
	// get device
	if($obj->device != "0"){ 
	$sql3 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$obj->device."' ");
	$result3 = mysqli_fetch_assoc($sql3);
	$device_name = rawurldecode($result3['device_description']);
	$device_color = $result3['device_color'];
	} else {
	$device_name = "default"; 
	$device_color = "#DDDDDD";
	}
		
	if($is_replay == '1'){ $replay_sign = '<i class="fa fa-repeat"></i>'; } else { $replay_sign = ''; }
	
	if($hidden == '1'){ $hidden_class = 'class="opac_70"'; 
	$hide_button = "<input id=\"timerlist_unhide_timer_btn_$obj->id\" name=\"null\" type=\"submit\" onClick=\"timerlist_unhide_timer(this.id,this.name)\" value=\"UNHIDE\" class=\"btn btn-primary btn-sm\" title=\"unhide Timer from list\"/>"; 
	
	} else { 
	
	$hidden_class = ''; 
	$hide_button = "<input id=\"timerlist_hide_timer_btn_$obj->id\" name=\"hide_$obj->hash\" type=\"submit\" onClick=\"timerlist_hide_timer(this.id,this.name)\" value=\"HIDE\" class=\"btn btn-primary btn-sm\" title=\"hide Timer from list\"/>";
	}
	
	if(!isset($device_dropdown) or $device_dropdown == ""){ $device_dropdown = ""; }
	
	if($search_term != ""){
	$scroll_search = "<strong>$search_term</strong> <i id=\"sid_$search_id\" onclick=\"scroll_saved_search(this.id,'timer_scroll_$obj->id')\" class=\"fa fa-edit fa-1x\" style=\"cursor:pointer\" title=\"Edit saved search\"></i>";
	} else { $scroll_search = ""; }
	
	$searcharea = '';
	if($obj->search_option == 'title'){ $searcharea = 'Title'; }
	if($obj->search_option == 'description'){ $searcharea = 'Description'; }
	if($obj->search_option == 'extdescription'){ $searcharea = 'Extended description'; }
	
	$timerlist = $timerlist."<div id=\"timerlist_div_outer_$obj->id\" $hidden_class>
	<div id=\"timerlist\" style=\"border: 1px solid $device_color !important;\">
	<div id=\"cnt_checkbox\"><input id=\"box_$obj->id\" type=\"checkbox\" name=\"timerlist_checkbox[]\" value=\"$obj->id\" onclick=\"count_selected()\"/>
	</div>
	<div id=\"timer_$obj->id\" style=\"cursor: pointer;\" onclick=\"timerlist_desc(this.id);\">
	<div id=\"$time_class\">| $status | $record_status | $broadcast_time | $broadcast_date
	</div>
	<div id=\"cnt_title\">
	  $replay_sign $title_enc
	</div>
	<div id=\"cnt_channel\">
	  $servicename_enc
	</div>
	<div style=\"clear:both\"></div>
	</div>
	  <div id=\"timerlist_btn_$obj->id\" style=\"display:none;\">
	  <div class=\"spacer_5\"></div>
	  <strong>$date_start</strong>
	  <div class=\"spacer_5\"></div>
	  <div id=\"timerlist_div_$obj->id\"><div class=\"spacer_5\"></div>
	  $description_enc<div class=\"spacer_5\"></div>
	  $descriptionextended_enc<div class=\"spacer_5\"></div>
	  </div>
	  <a href=\"search.php?searchterm=$title_enc&option=title\" target=\"_blank\" title=\"Search title\"><i class=\"fa fa-search fa-1x\"></i></a>
	  Searchterm: $scroll_search | Searcharea: $searcharea | Record location: $obj->record_location | Receiver: <span id=\"device_name_$obj->id\">$device_name</span> $replay_status $show_exclude_text
	  <input id=\"timerlist_device_no_$obj->id\" type=\"hidden\" value=\"$device_no\">
	  <span id=\"timerlist_rec_location_$obj->hash\" style=\"display:none;\">$rec_location_id</span>  
	<div class=\"spacer_5\"></div>
	<span>Receiver: </span>
	<select id=\"timerlist_device_dropdown_$obj->id\" onChange=\"change_timerlist_device(this.id)\">
	<option selected disabled>$device_name</option>
	<option name=\"default\" value=\"0\">default</option>
	$device_dropdown
	</select>
	<div class=\"spacer_5\"></div>
	  <div id=\"timerlist_excluded_terms_$obj->id\" style=\"display:none;\">
	  $channel_status $spacer $title_status $spacer $description_status $spacer $extdescription_status
	  <div class=\"spacer_5\"></div>
	  </div>
	  <div class=\"spacer_5\"></div>
	  <input id=\"timerlist_send_timer_btn_$obj->hash\" name=\"$obj->id\" type=\"submit\" onClick=\"timerlist_send_timer(this.id,this.name)\" value=\"SEND\" class=\"btn btn-success btn-sm\" title=\"send Timer to Receiver\"/>
	  $hide_button
	  <input id=\"delete_timer_btn_$obj->id\" type=\"submit\" onClick=\"timerlist_delete_timer(this.id)\" value=\"DELETE\" class=\"btn btn-danger btn-sm\" title=\"delete Timer from Receiver\"/>
	  <span class=\"del_checkbox\"><input id=\"timerlist_delete_db_$obj->id\" type=\"checkbox\"> delete also from Database</span>
	  <span id=\"timerlist_status_$obj->id\"></span>
	  </div>
	</div><div class=\"spacer_10\"></div>
	</div>";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<script type="text/javascript">
// timerlist hover
$(document).ready(function(){
$("#timerlist*").hover(function(){
	$(this).css("background-color", "#FAFAFA");
	}, function(){
	$(this).css("background-color", "white");
});
});
</script>
</head>
<body>
<?php if(!isset($timerlist) or $timerlist == "") { $timerlist = "No timer present.."; } echo $timerlist;  ?>
</body>
</html>
