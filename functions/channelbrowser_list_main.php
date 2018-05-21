<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<script>
//
$(document).ready(function(){
    $("#channelbrowser_main*").hover(function(){
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
	
	$time = $_REQUEST['time'];
	$channel = $_REQUEST['channel'];
	
	$sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `cb_selected` = '0' ");
	$sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `cb_selected` = '1' WHERE `e2servicereference` = '$channel' ");
	
	$date_for_cb = date("d.m.Y");
	$date_start = $date_for_cb.'00:00';
	$date_end = $date_for_cb.'23:59';
	
	$cb_time_start = strtotime($date_start);
	$cb_time_end = strtotime($date_end);
	
	if(!isset($time) or $time == "") 
	{ 
	echo "time data missed"; 
	
	} else {
	
	if(!isset($_SESSION["sum_channelbrowser_days"]) or $_SESSION["sum_channelbrowser_days"] == ""){ $_SESSION["sum_channelbrowser_days"] = ""; }
	
	$timestamp = time();
	$today_start = $cb_time_start;
	$today_end = $cb_time_end;
		
	if ($time == 'cb_day_forward' or $time == 'cb_day_backward'){
	
	if ($time == 'cb_day_forward')
	{
	$summary = $_SESSION["sum_channelbrowser_days"] +1;
	}
	
	if ($time == 'cb_day_backward')
	{
	$summary = $_SESSION["sum_channelbrowser_days"] -1;
	}
				
	$_SESSION["sum_channelbrowser_days"] = $summary;
	
	$timestamp_dup = $summary * 86400;
	
	$timestamp_forward_start = $timestamp_dup + $today_start;
	$timestamp_forward_end = $timestamp_forward_start + 86400;
	
	$time_start = $timestamp_forward_start;
	$time_end = $timestamp_forward_end;

	// time info
	if ($time_format == '1')
	{
	// time format 1
	$date_from_day = date("l, d.m", $time_start);
	echo '<p>' .$date_from_day. '</p>';
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$date_from_day = date("l, n/d", $time_start);
	echo '<p>' .$date_from_day. '</p>';
	}
	
	}	
	}
	// get record locations
	$sql = "SELECT * FROM `record_locations` ORDER BY id ASC";
	if ($result2 = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result2)) {	
	{
	if(!isset($rec_dropdown_channelbrowser) or $rec_dropdown_channelbrowser == "") { $rec_dropdown_channelbrowser = ""; }
	$rec_dropdown_channelbrowser = $rec_dropdown_channelbrowser."<option value=\"$obj->id\">$obj->e2location</option>"; }
	}
	}
	//
	if ($time == 'cb_now_today' ){ $time_start = $today_start; $time_end = $today_end; unset($_SESSION['sum_channelbrowser_days']);  //session_destroy(); 
	}
	
	if(!isset($time_start) or $time_start == "") { $time_start = $cb_time_start; }
	if(!isset($time_end) or $time_end == "") { $time_end = $cb_time_end; }
	
	$sql = "SELECT * FROM `epg_data` WHERE `e2eventservicereference` = '$channel' AND `e2eventstart` BETWEEN '$time_start' and '$time_end' ORDER BY `e2eventstart` ASC";

	if ($result = mysqli_query($dbmysqli,$sql))
	{
	
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	
	if(!isset($channelbrowser_list) or $channelbrowser_list == "") { $channelbrowser_list = ""; }
	
	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	
	if ($time_format == '1')
	{
	// time format 1
	$e2eventstart = $obj->e2eventstart;
	$date_start = date("l, d.m.Y", $e2eventstart);
	
	$e2eventend = $obj->e2eventend;
	$broadcast_time = date("H:i", $e2eventstart).' - '.date("H:i", $e2eventend);
	
	$td_spacer = 'cnt_time';
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$e2eventstart = $obj->e2eventstart;
	$date_start = date("l n/d/Y", $e2eventstart);
	
	$e2eventend = $obj->e2eventend;
	$date_end = date("l n/d/Y - g:i A", $e2eventend);
	
	$broadcast_time = date("g:i A", $e2eventstart).' - '.date("g:i A", $e2eventend);
	
	$td_spacer = 'cnt_time_2';
	}
	
	if ($streaming_symbol == '1' ){ $stream_broadcast = '<a href="'.$url_format.'://'.$box_user.':'.$box_password.'@'.$box_ip.'/web/stream.m3u?ref='.$obj->e2eventservicereference.'" title="Stream"><i class="fa fa-desktop fa-1x"></i></a>'; 
	} else { 
	$stream_broadcast = ''; }
	
	if ($imdb_symbol == '1' ){ $imdb_broadcast = '<a href="https://www.imdb.com/find?ref_=nv_sr_fn&q='.$title_enc.'" target="_blank" title="Info on IMDb"><i class="fa fa-info-circle fa-1x"></i></a>'; 
	} else { 
	$imdb_broadcast = ''; }
	
	// mark existing timer
	if ($obj->timer == '1'){ $timer = "timer"; } else { $timer = ""; }
	
	//
	$rand = substr(str_shuffle(str_repeat('0123456789',3)),0,3);

$channelbrowser_list = $channelbrowser_list."
		<div id=\"channelbrowser_main\">
	  <div id=\"channelbrowser_$rand$obj->hash\" style=\"cursor: pointer;\" onclick=\"channelbrowser_list_desc(this.id);\">
		<div id=\"$td_spacer\"> <span class=\"$timer\">$broadcast_time</span> </div>
		<div id=\"cnt_title\"> <span class=\"$timer\">$title_enc</span>
		</div>
		<div id=\"cnt_channel\"> <span class=\"$timer\">$servicename_enc</span> </div>
		<div style=\"clear:both\"></div>
	  </div>
	  <div id=\"channelbrowser_btn_$rand$obj->hash\" style=\"display:none;\"><div class=\"spacer_5\"></div>
	  <strong>$date_start</strong><br />
	  <div id=\"channelbrowser_div_$rand$obj->hash\">
		  <div class=\"spacer_5\"></div>
		  $description_enc
		  <div class=\"spacer_5\"></div>
		  $descriptionextended_enc
		  <div class=\"spacer_5\"></div>
		</div>
		$imdb_broadcast $stream_broadcast <a href=\"search.php?searchterm=$obj->title_enc&option=title&search_channel=on&channel_id=$obj->e2eventservicereference\" target=\"_blank\" title=\"Search this broadcast only on this channel\">More broadcast on this channel</a>
		<div class=\"spacer_5\"></div>
<div id=\"broadcast-tab-button-group\">
  <div id=\"row1\">
    <input id=\"channelbrowser_timer_btn_$rand$obj->hash\" type=\"submit\" onClick=\"channelbrowser_timer(this.id)\" value=\"SET TIMER\" class=\"btn btn-success btn-sm\" title=\"send Timer to Receiver\"/> </div>
  <div id=\"row2\">
    <input id=\"channelbrowser_zap_btn_$rand$obj->hash\" type=\"submit\" name=\"$obj->e2eventservicereference\" onClick=\"channelbrowser_zap(this.id,this.name)\" value=\"ZAP TO CHANNEL\" class=\"btn btn-default btn-sm\"/> </div>
  <div id=\"row3\">
  <span id=\"channelbrowser_status_zap_$rand$obj->hash\"></span>
  <span id=\"channelbrowser_status_timer_$rand$obj->hash\"></span>
  </div>
  <div style=\"clear:both\"></div>
  </div>
  <div class=\"spacer_5\"></div>
  <span>Record location: </span><select id=\"rec_location_channelbrowser_$rand$obj->hash\" class=\"rec_location_dropdown\">$rec_dropdown_channelbrowser</select>
  <div class=\"spacer_5\"></div>
  </div>
  </div>
  <div class=\"spacer_10\"></div>";
	}
	}
	if(!isset($channelbrowser_list) or $channelbrowser_list == "") { $channelbrowser_list = "No data for this day"; }
	
	echo $channelbrowser_list;
	
  // Free result
  mysqli_free_result($result);

//close db
mysqli_close($dbmysqli);
?>

</body>
</html>
