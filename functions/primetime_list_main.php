<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<script>
$(function(){
    $("#primetime_main*").hover(function(){
    $(this).css("background-color", "#FAFAFA");
    }, function(){
    $(this).css("background-color", "white");
    });
});
</script>
</head>
<body>
<?php 
header('Cache-Control: no-cache');
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ''){ $_REQUEST['action'] = ''; }
	$action = $_REQUEST['action'];
	
	// set
	if($action == 'set'){
	
	$timestamp = time();
	$hour = $_REQUEST['hour'];
	$minute = $_REQUEST['minute'];
	$ampm = $_REQUEST['ampm'];
	
	if($ampm !== '0'){
	if($ampm == 'PM'){  
	if($hour == '12'){ $hour = $hour - 12; }
	}
	if($ampm == 'AM'){
	if($hour == '12'){ $hour = $hour + 12; }
	}
	}
	
	$date_start = date('d.m.Y, '.$hour.':'.$minute.'',$timestamp);
	$time_start = strtotime($date_start);
	
	if($ampm !== '0')
	{
	if($ampm == 'AM'){ $time_start - 43200; }
	if($ampm == 'PM'){ $time_start = $time_start + 43200; }
	}
	
	mysqli_query($dbmysqli, "UPDATE `settings` SET `primetime` = '".$time_start."' WHERE `id` = '0' ");
	
	echo '<i class=\'glyphicon glyphicon-ok fa-1x green\'></i>';
	
	sleep(1);
	}

	// show
	if($action == 'show')
	{
	$time = $_REQUEST['time'];
	
	if(!isset($time) or $time == '') 
	{ 
	echo 'time data missed'; 
	exit;
	}
	
	if(!isset($_SESSION['sum_primetime_days']) or $_SESSION['sum_primetime_days'] == ''){ $_SESSION['sum_primetime_days'] = ''; }
	
	$timestamp = time();
	$date_for_primetime = date('d.m.Y');
	$primetime_start = date('H:i',$primetime);
	$date = $date_for_primetime.$primetime_start;
	$primetime_start = strtotime($date) - $dur_down_primetime;
	$primetime_end = strtotime($date) + $dur_up_primetime;
	
	if($time == 'day_forward' or $time == 'day_backward' ){
	
	if($time == 'day_forward')
	{
	$summary = $_SESSION['sum_primetime_days'] +1;
	}
	
	if($time == 'day_backward')
	{
	$summary = $_SESSION['sum_primetime_days'] -1;
	}
				
	$_SESSION['sum_primetime_days'] = $summary;
	
	$timestamp_dup = $summary * 86400;
	$timestamp_forward_start = $timestamp_dup + $primetime_start;
	$timestamp_forward_end = $timestamp_forward_start + $dur_up_primetime;
	$time_start = $timestamp_forward_start;
	$time_end = $timestamp_forward_end;
	
	// time format 1
	if($time_format == '1')
	{
	$date_from_day = date('l, d.m', $time_start);
	echo '<p>' .$date_from_day. '</p>';
	}
	
	// time format 2
	if ($time_format == '2')
	{
	$date_from_day = date('l, n/d', $time_start);
	echo '<p>' .$date_from_day. '</p>'; }
	}
	
	// record locations
	$sql = "SELECT * FROM `record_locations` ORDER BY `id` ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {	
	{
	if(!isset($rec_dropdown_primetime) or $rec_dropdown_primetime == ''){ $rec_dropdown_primetime = ''; }
	$rec_dropdown_primetime .= '<option value="'.$obj->id.'">'.$obj->e2location.'</option>'; }
	}
	}
	
	// device dropdown
	$sql_2 = "SELECT * FROM `device_list` ORDER BY `id` ASC";
	
	if ($result_2 = mysqli_query($dbmysqli,$sql_2))
	{
	while ($obj = mysqli_fetch_object($result_2)){
	{
	$id = $obj->id;
	$device_description = rawurldecode($obj->device_description);
	
	if(!isset($device_dropdown) or $device_dropdown == ''){ $device_dropdown = ''; }

	$device_dropdown .= '<option value="'.$id.'">'.$device_description.'</option>'; 
	}
	}
	} // device
		
	if($time == 'today'){ $time_start = $primetime_start; $time_end = $primetime_end; unset($_SESSION['sum_primetime_days']); } // session_destroy();
	
	$sql = "SELECT * FROM `epg_data` WHERE `e2eventstart` BETWEEN '".$time_start."' and '".$time_end."' ORDER BY `e2eventstart` ASC";
	
	if($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	
	if(!isset($primetime_list) or $primetime_list == ''){ $primetime_list = ''; }

	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	$e2eventstart = $obj->e2eventstart;
	$e2eventend = $obj->e2eventend;
	$e2eventduration = $obj->e2eventduration;
	
	// time format 1
	if($time_format == '1')
	{
	$date_start = date('l, d.m.Y', $e2eventstart);
	$primetime_time = date('H:i', $e2eventstart).' - '.date('H:i', $e2eventend);
	$td_spacer = 'cnt_time';
	}
	
	// time format 2
	if($time_format == '2')
	{
	$date_start = date('l, n/d/Y', $e2eventstart);
	$date_end = date('l, n/d/Y - g:i A', $e2eventend);
	$primetime_time = date('g:i A', $e2eventstart).' - '.date('g:i A', $e2eventend);
	$td_spacer = 'cnt_time_2';
	}
	
	if($streaming_symbol == '1')
	{ 
	$stream_broadcast = '<a href="'.$url_format.'://'.$box_ip.'/web/stream.m3u?ref='.$obj->e2eventservicereference.'&name='.$servicename_enc.'" target="_blank" title="Stream"><i class="fa fa-desktop fa-1x"></i></a>'; 
	} else { 
	$stream_broadcast = ''; }
	
	if($imdb_symbol == '1'){ $imdb_broadcast = '<a href="https://www.imdb.com/find?ref_=nv_sr_fn&q='.$title_enc.'" target="_blank" title="Info on IMDb">
	<i class="fa fa-info-circle fa-1x"></i></a>'; 
	} else { 
	$imdb_broadcast = ''; }
	
	// mark existing timer
	if($obj->timer == '1'){ $timer = 'timer'; } else { $timer = ''; }
	
	// broadcast length
	if($descriptionextended_enc == ''){ $spacer_d = ''; } else { $spacer_d = '<br>'; }
	$event_duration = $e2eventduration / 60;
	$event_duration = $spacer_d.round($event_duration, 0).' min.';
	
	// highlight term
	if($highlight_term !== '')
	{
	$terms = explode(rawurldecode(';'), rawurldecode($highlight_term));
	foreach($terms as $i =>$key) { $i > 0;
	$descriptionextended_enc = str_replace($key, '<strong>'.$key.'</strong>', $descriptionextended_enc);
	}
	}
	
	if(!isset($device_dropdown) or $device_dropdown == ''){ $device_dropdown = ''; }

	$primetime_list = $primetime_list."
		<div id=\"primetime_main\">
	  <div id=\"primetime_$obj->hash\" style=\"cursor: pointer;\" onclick=\"primetime_list_desc(this.id);\">
		<div id=\"$td_spacer\"> <span class=\"$timer\">$primetime_time</span> </div>
		<div id=\"cnt_title\"> <span class=\"$timer\">$title_enc</span>
		</div>
		<div id=\"cnt_channel\"> <span class=\"$timer\">$servicename_enc</span> </div>
		<div style=\"clear:both\"></div>
	  </div>
	  <div id=\"primetime_btn_$obj->hash\" style=\"display:none;\"><div class=\"spacer_5\"></div>
	  <strong>$date_start</strong><br />
	  <div id=\"primetime_div_$obj->hash\">
		  <div class=\"spacer_5\"></div>
		  $description_enc
		  <div class=\"spacer_5\"></div>
		  $descriptionextended_enc $event_duration
		  <div class=\"spacer_5\"></div>
		</div>
		$imdb_broadcast $stream_broadcast <a onclick=\"open_bn_modal('$obj->e2eventservicereference','$servicename_enc')\" title=\"Show EPG\" style=\"cursor:pointer;\">
		<i class=\"fa fa-list-alt fa-1x\"></i></a>
		<a href=\"search.php?searchterm=$obj->title_enc&option=title\" target=\"_blank\" title=\"Search this broadcast on all channels\">More from this broadcast</a>
		<div class=\"spacer_5\"></div>
		<div id=\"broadcast-tab-button-group\">
  <div id=\"row1\">
    <input id=\"primetime_timer_btn_$obj->hash\" type=\"submit\" onClick=\"primetime_timer(this.id,'record')\" value=\"SET TIMER\" class=\"btn btn-success btn-xs\"/ title=\"Send timer to Receiver\"> </div>
	<div id=\"row2\">
    <input id=\"primetime_timer_btn_$obj->hash\" type=\"submit\" onClick=\"primetime_timer(this.id,'zap')\" value=\"ZAP TIMER\" class=\"btn btn-warning btn-xs\"/ title=\"Send zap timer to Receiver\"> </div>
  <div id=\"row3\">
    <input id=\"primetime_zap_btn_$obj->hash\" type=\"submit\" name=\"$obj->e2eventservicereference\" onClick=\"primetime_zap(this.id,this.name)\" value=\"ZAP TO CHANNEL\" class=\"btn btn-default btn-xs\"/> </div>
  <div id=\"row4\">
  <span id=\"primetime_status_zap_$obj->hash\"></span> <span id=\"primetime_status_timer_$obj->hash\"></span> </div>
  <div style=\"clear:both\"></div>
  </div>
  <div class=\"spacer_5\"></div>
  <span>Receiver: </span>
  <select id=\"primetime_device_dropdown_$obj->hash\" class=\"device_dropdown\" onchange=\"primetime_change_device(this.id)\">
  <option value=\"0\">default</option>
  $device_dropdown
  </select>
  <div class=\"spacer_10\"></div>
  <span>Record location: </span>
  <select id=\"rec_location_primetime_$obj->hash\" class=\"rec_location_dropdown\">
  $rec_dropdown_primetime
  </select>
  </div>
  </div>
  <div class=\"spacer_10\"></div>"; 
  }
  }
  if(!isset($primetime_list) or $primetime_list == ''){ $primetime_list = 'No data for this day'; }
  
  echo $primetime_list;
}
?>

</body>
</html>
