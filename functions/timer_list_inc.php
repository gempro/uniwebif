<?php 

	// timerlist
	if(!isset($_REQUEST['delete_id']) or $_REQUEST['delete_id'] == "") { $_REQUEST['delete_id'] = ""; 
	
	} else {
	
	include("../inc/dashboard_config.php");
	
	$_REQUEST['delete_id'] = $_REQUEST['delete_id'];
	
	if(!isset($_REQUEST['delete_from_box']) or $_REQUEST['delete_from_box'] == "") { $_REQUEST['delete_from_box'] = ""; } else { $_REQUEST['delete_from_box'] = $_REQUEST['delete_from_box']; }
	
	$delete_id = $_REQUEST['delete_id'];
	$delete_from_box = $_REQUEST['delete_from_box'];
	
	// clear timer
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE id = '".$delete_id."' ");
	$result = mysqli_fetch_assoc($sql);
	
	$sql = mysqli_query($dbmysqli, "UPDATE `epg_data` set timer = '0' WHERE hash = '".$result['hash']."' ");

	sleep(1);
	
	$e2eventservicereference = $result['e2eventservicereference'];
	$e2eventstart = $result['e2eventstart'];
	$e2eventend = $result['e2eventend'];
	
	// delete timer from receiver
	if ($delete_from_box == '1')
	{
	$deleteTimer = "http://$box_ip/web/timerdelete?sRef=".$e2eventservicereference."&begin=".$e2eventstart."&end=".$e2eventend."";
	$deleteTimer_request = file_get_contents($deleteTimer, false, $webrequest);
	}
	
	$sql = mysqli_query($dbmysqli, "DELETE FROM `timer` WHERE id = '".$delete_id."' ");
	
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data:deleted\n\n";
	exit;
	}
	
	$sql = "SELECT * FROM `timer` ORDER BY record_status ASC, e2eventstart ASC";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	
	if(!isset($timerlist) or $timerlist == "") { $timerlist = ""; } else { $timerlist = $timerlist; }
	
	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	$search_term = rawurldecode($obj->search_term);
	
	if ($time_format == '1')
	{
	// time format 1
	$e2eventstart = $obj->e2eventstart;
	//$date_start = date("l, d.m.Y - H:i", $e2eventstart);
	$date_start_weekday = date("l", $e2eventstart);
	$date_start_day = date("d", $e2eventstart);
	$date_start_month = date("m", $e2eventstart);
	$date_start_year = date("Y", $e2eventstart);
	$date_start_hour = date("H", $e2eventstart);
	$date_start_minute = date("i", $e2eventstart);
  	$date_start = "$date_start_weekday, $date_start_day.$date_start_month.$date_start_year";
	
	$e2eventend = $obj->e2eventend;
	$date_end_weekday = date("l", $e2eventend);
	$date_end_day = date("d", $e2eventend);
	$date_end_month = date("m", $e2eventend);
	$date_end_year = date("Y", $e2eventend);
	$date_end_hour = date("H", $e2eventend);
	$date_end_minute = date("i", $e2eventend);
	$date_end = "$date_end_weekday, $date_end_day.$date_end_month.$date_end_year - $date_end_hour:$date_end_minute";
	$broadcast_time = "$date_start_hour:$date_start_minute - $date_end_hour:$date_end_minute";
	$broadcast_date = "$date_start_day.$date_start_month";
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$e2eventstart = $obj->e2eventstart;
	//$date_start = date("l n/d/Y - g:i A", $e2eventstart);
	$date_start_weekday = date("l", $e2eventstart);
	$date_start_month = date("n", $e2eventstart);
	$date_start_day = date("d", $e2eventstart);
	$date_start_year = date("Y", $e2eventstart);
	$date_start_hour = date("g", $e2eventstart);
	$date_start_minute = date("i", $e2eventstart);
	$date_start_ampm = date("A", $e2eventstart);
	$date_start = "$date_start_weekday, $date_start_month/$date_start_day/$date_start_year";
	
	$e2eventend = $obj->e2eventend;
	$date_end_weekday = date("l", $e2eventend);
	$date_end_month= date("n", $e2eventend);
	$date_end_day = date("d", $e2eventend);
	$date_end_year = date("Y", $e2eventend);
	$date_end_hour = date("g", $e2eventend);
	$date_end_minute = date("i", $e2eventend);
	$date_end_ampm = date("A", $e2eventend);
	$date_end = "$date_end_weekday, $date_end_month/$date_end_day/$date_end_year - $date_end_hour:$date_end_minute $date_end_ampm";
	$broadcast_time = "$date_start_hour:$date_start_minute $date_start_ampm - $date_end_hour:$date_end_minute $date_end_ampm";
	$broadcast_date = "$date_start_month/$date_start_day";
	}

	$record_status = $obj->record_status;

if ($obj->status == 'waiting')
		  		{
				$status = '
				<i class="fa fa-search fa-1x" title="Automatic search"></i> | 
				<i class="glyphicon glyphicon-export" style="color:#D9534F" title="not sent"></i>
				';
				}
				
if ($obj->status == 'sent')
		  		{
				$status = '
				<i class="fa fa-search fa-1x" title="Automatic search"></i> | 
				<i class="glyphicon glyphicon-export" style="color:#5CB85C" title="sent"></i>
				';
				}
				
if ($obj->status == 'manual')
		  		{
				$status = '
				<i class="fa fa-hand-o-up fa-1x" title="manual"></i> | 
				<i class="glyphicon glyphicon-export" style="color:#5CB85C" title="sent"></i>
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
				
if ($obj->status == 'sent' or $obj->status == 'manual')
				{
				$delete_checkbox = "";
				} else { 
				$delete_checkbox = "style=\"display:none;\""; 
				}
		
		$timerlist = $timerlist."<div id=\"timerlist_div_outer_$obj->id\">
		<div id=\"timerlist\">
		<div id=\"timer_$obj->id\" style=\"cursor: pointer;\" onclick=\"timerlist_delete(this.id);\">
		<div id=\"cnt_time\">$status | $record_status | $broadcast_time | $broadcast_date
		</div>
		<div id=\"cnt_title\">
		  $title_enc
		  <div id=\"timerlist_desc_inner\">
		  </div>
		</div>
		<div id=\"cnt_channel\">
		  $servicename_enc
		</div>
		<div style=\"clear:both\"></div>
		</div>
		  <div id=\"timerlist_btn_$obj->id\" style=\"display:none;\">
		  
		  <div id=\"timerlist_div_$obj->id\"><div class=\"spacer_5\"></div>
		  $description_enc<div class=\"spacer_5\"></div>
		  $descriptionextended_enc<div class=\"spacer_5\"></div>
		  </div>
		  
		  <strong>$date_start</strong><div class=\"spacer_5\"></div>
		  Searchterm: $search_term | Searcharea: $obj->search_option | Record location: $obj->record_location<div class=\"spacer_5\"></div>
		  <input id=\"delete_timer_btn_$obj->id\" type=\"submit\" onClick=\"delete_timer(this.id)\" value=\"DELETE TIMER\" class=\"btn btn-danger\"/>
		  <input id=\"timerlist_send_timer_btn_$obj->hash\" type=\"submit\" onClick=\"timerlist_send_timer(this.id)\" value=\"SEND TIMER\" class=\"btn btn-success\" title=\"send timer instantly\"/>
		  <span id=\"del_checkbox\" $delete_checkbox><input id=\"delete_from_box_$obj->id\" type=\"checkbox\" $delete_checkbox> delete also from Receiver</span>
		  <span id=\"timerlist_status_$obj->id\"></span>
		  <span id=\"timerlist_send_timer_status_$obj->hash\"></span>
		  </div>
		</div><div class=\"spacer_10\"></div>
		</div>";
	}
}
if(!isset($timerlist) or $timerlist == "") { $timerlist = "No timer present.."; } else { $timerlist = $timerlist; }
?>