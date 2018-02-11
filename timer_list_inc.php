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
<?php 
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == "") { $_REQUEST['action'] = ""; }
	
	$action = $_REQUEST['action'];
	
	if(!isset($_REQUEST['timer_id']) or $_REQUEST['timer_id'] == "") { $_REQUEST['timer_id'] = ""; 
	
	} else {

	if ($action == 'delete'){
	if(!isset($_REQUEST['delete_from_box']) or $_REQUEST['delete_from_box'] == "") { $_REQUEST['delete_from_box'] = ""; }
	if(!isset($_REQUEST['delete_from_db']) or $_REQUEST['delete_from_db'] == "") { $_REQUEST['delete_from_db'] = ""; }
	
	$timer_id = $_REQUEST['timer_id'];
	$delete_from_db = $_REQUEST['delete_from_db'];
	
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	// unmark timer
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$timer_id."' ");
	$result = mysqli_fetch_assoc($sql);
	
	$e2eventservicereference = $result['e2eventservicereference'];
	$e2eventstart = $result['e2eventstart'];
	$e2eventend = $result['e2eventend'];
	$status = $result['status'];
	
	if ($status == 'manual' or $status == 'sent'){
	$sql = mysqli_query($dbmysqli, "UPDATE `epg_data` SET timer = '0' WHERE `hash` = '".$result['hash']."' ");
	}

	sleep(1);
	
	// delete timer from receiver
	$deleteTimer = "$url_format://$box_ip/web/timerdelete?sRef=".$e2eventservicereference."&begin=".$e2eventstart."&end=".$e2eventend."";
	
	$deleteTimer_request = file_get_contents($deleteTimer, false, $webrequest);
	
	$sql = mysqli_query($dbmysqli, "UPDATE `timer` SET `status` = 'waiting' WHERE `id` = '".$timer_id."' ");
	//
	}
	
	// delete timer from database
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	if ($delete_from_db == '1')
	{
	$sql = mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE `id` = '".$timer_id."' ");
	//
	echo "data:deleted_db\n\n";
	exit;
	
	} else {
	
	echo "data:deleted\n\n";
	exit;
	}
	}
	
	// hide single timer
	if ($action == 'hide'){
	$timer_id = $_REQUEST['timer_id'];
	$sql =  mysqli_query($dbmysqli, "UPDATE `timer` SET `hide` = '1' WHERE `id` = '".$timer_id."' ");
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data:hided\n\n";
	exit;
	}
	
	// unhide
	if ($action == 'unhide'){
	$sql = "SELECT * FROM `timer` WHERE `expired` = '0' ORDER BY `e2eventstart` ASC, `record_status` ASC"; 
	
	} else {
	
	$sql = "SELECT * FROM `timer` WHERE `expired` = '0' AND `hide` = '0' ORDER BY `e2eventstart` ASC, `record_status` ASC";
	}

	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	
	if(!isset($timerlist) or $timerlist == "") { $timerlist = ""; }
	
	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	$search_term = rawurldecode($obj->search_term);
	$exclude_term = rawurldecode($obj->exclude_term);
	$exclude_area = rawurldecode($obj->exclude_area);
	$record_status = $obj->record_status;
	$rec_replay = $obj->rec_replay;
	$is_replay = $obj->is_replay;
	$hidden = $obj->hide;
	
	if(!isset($exclude_term) or $exclude_term == "") { $exclude_term = ""; }
	if(!isset($exclude_area) or $exclude_area == "") { $exclude_area = ""; }
	if($exclude_area == '1'){ $exclude_area = 'title'; }
	if($exclude_area == '2'){ $exclude_area = 'description'; }
	if($exclude_area == '3'){ $exclude_area = 'extdescription'; }
	
	$exclude_term = str_replace(";", "; ", $exclude_term);
	
	if ($time_format == '1')
	{
	// time format 1
	$e2eventstart = $obj->e2eventstart;
	$date_start = date("l, d.m.Y", $e2eventstart);
	
	$e2eventend = $obj->e2eventend;
	$broadcast_time = date("H:i", $e2eventstart).' - '.date("H:i", $e2eventend);
	$broadcast_date = date("d.m", $e2eventstart);
	$time_class = 'cnt_time';
	}
	
	if ($time_format == '2')
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

if ($obj->status == 'waiting')
		{
		$status = '
		<i class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i> | 
		<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#D9534F" title="not sent"></i>
		';
		}
		
if ($obj->status == 'rec_deleted')
		{
		$status = '
		<i class="fa fa-hand-o-up fa-1x" style="color:#000" title="manual"></i> | 
		<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#D9534F" title="not sent"></i>
		';
		}
		
if ($obj->status == 'sent')
		{
		$status = '
		<i class="fa fa-search fa-1x" style="color:#000" title="Automatic search"></i> | 
		<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#5CB85C" title="sent"></i>
		';
		}
				
if ($obj->status == 'manual')
		{
		$status = '
		<i class="fa fa-hand-o-up fa-1x" style="color:#000" title="manual"></i> | 
		<i id="tl_glyphicon_status_'.$obj->id.'" class="glyphicon glyphicon-export" style="color:#5CB85C" title="sent"></i>
		';
		}
				
if ($obj->record_status == 'a_recording')
		{
		$record_status = '
		<i class="glyphicon glyphicon-transfer" style="color:#428BCA" title="now running"></i>
		';
		}
				
if ($obj->record_status == 'b_incoming')
		{
		$record_status = '
		<i class="glyphicon glyphicon-time" style="color:#428BCA" title="incoming event"></i>
		';
		}
				
if ($obj->record_status == 'c_expired')
		{
		$record_status = '
		<i class="glyphicon glyphicon-time" style="color:#F0AD4E" title="expired event"></i>
		';
		}
		
		if ($obj->exclude_term != ''){ $term_status = 'Exclude Term: '.$exclude_term.'<br>'; } else { $term_status = ''; }
		if ($obj->exclude_area != ''){ $area_status = 'Exclude Area: '.$exclude_area.''; } else { $area_status = ''; }
		if ($obj->rec_replay == 'on'){ $replay_status = 'Timer for Replays: '.$rec_replay.''; } else { $replay_status = ''; }
		if ($obj->exclude_term != '' and $obj->exclude_area != '' and $obj->rec_replay == 'on'){ $spacer = ' | '; } else { $spacer = ''; }
		
		// get record location id
		$sql = mysqli_query($dbmysqli, "SELECT * FROM `record_locations` WHERE `e2location` = '".$obj->record_location."' LIMIT 0,1");
		$result2 = mysqli_fetch_assoc($sql);
		$rec_location_id = $result2['id'];
		
		if ($is_replay == '1'){ $replay_sign = '<i class="fa fa-repeat"></i>'; } else { $replay_sign = ''; }
		
		if ($hidden == '1'){ $hidden_class = 'class="opac_70"'; } else { $hidden_class = ''; }
		
		$timerlist = $timerlist."<div id=\"timerlist_div_outer_$obj->id\" $hidden_class>
		<div id=\"timerlist\">
		<div id=\"cnt_checkbox\"><input id=\"box_$obj->hash\" type=\"checkbox\" name=\"timerlist_checkbox[]\" value=\"$obj->id\" onclick=\"count_selected()\"/>
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
		  Searchterm: <strong>$search_term</strong> | Searcharea: $obj->search_option | Record location: $obj->record_location 
		  <span id=\"timerlist_rec_location_$obj->hash\" style=\"display:none;\">$rec_location_id</span>
		  <div class=\"spacer_5\"></div>
		  $term_status $area_status $spacer $replay_status
		  <div class=\"spacer_5\"></div>
		  <input id=\"timerlist_send_timer_btn_$obj->hash\" name=\"$obj->id\" type=\"submit\" onClick=\"timerlist_send_timer(this.id,this.name)\" value=\"SEND TIMER\" class=\"btn btn-success btn-sm\" title=\"send Timer to Receiver\"/>
		  <input id=\"timerlist_hide_timer_btn_$obj->id\" type=\"submit\" onClick=\"timerlist_hide_timer(this.id)\" value=\"HIDE TIMER\" class=\"btn btn-primary btn-sm\" title=\"hide Timer from list\"/>
		  <input id=\"delete_timer_btn_$obj->id\" type=\"submit\" onClick=\"timerlist_delete_timer(this.id)\" value=\"DELETE TIMER\" class=\"btn btn-danger btn-sm\" title=\"delete Timer from Receiver\"/>
		  <span class=\"del_checkbox\"><input id=\"timerlist_delete_db_$obj->id\" type=\"checkbox\"> delete also from Database</span>
		  <span id=\"timerlist_status_$obj->id\"></span>
		  </div>
		</div><div class=\"spacer_10\"></div>
		</div>";
		}
	}
	if(!isset($timerlist) or $timerlist == "") { $timerlist = "No timer present.."; }
	
	echo $timerlist;
?>
</body>
</html>
