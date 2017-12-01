<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>_broadcast_list_main</title>
<script type="text/javascript">
// hover color
$(document).ready(function(){
    $("#broadcast_main*").hover(function(){
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
	
	if(!isset($time) or $time == ""){ echo "time data missed"; }
	
	if ($time == 'now_today'){ $_SESSION["browse_timestamp"] = time(); }
	
	// save timestamp in session
	if ($time == 'time_forward'){ $_SESSION["browse_timestamp"] = $_SESSION["browse_timestamp"] + $dur_up_broadcast; }
	if ($time == 'time_backward'){ $_SESSION["browse_timestamp"] = $_SESSION["browse_timestamp"] - $dur_up_broadcast; }
	if ($time == 'day_forward'){ $_SESSION["browse_timestamp"] = $_SESSION["browse_timestamp"] + 86400; }
	if ($time == 'day_backward'){ $_SESSION["browse_timestamp"] = $_SESSION["browse_timestamp"] - 86400; }
	
	// set time
	if ($time == 'show_time' ){
	$hour = $_REQUEST['hour'];
	$minute = $_REQUEST['minute'];
	$ampm = $_REQUEST['ampm'];
	
	if ($ampm !== '0'){
	if ($ampm == 'AM'){ $ampm = 'A'; }
	if ($ampm == 'PM'){  $ampm = 'P'; }
	} else { $ampm = ''; }
	
	//
	$set_time = date("d.m.Y, ".$hour.":".$minute." ".$ampm."", $_SESSION["browse_timestamp"]);
	$_SESSION["browse_timestamp"] = strtotime($set_time) + $dur_down_broadcast;
	}
	
	//
	$time_start = $_SESSION["browse_timestamp"] - $dur_down_broadcast;
	$time_end = $_SESSION["browse_timestamp"] + $dur_up_broadcast;
	
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
	
	
	// get record locations
	$sql = "SELECT * FROM `record_locations` ORDER BY id ASC";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {	
	{
	if(!isset($rec_dropdown_broadcast) or $rec_dropdown_broadcast == "") { $rec_dropdown_broadcast = ""; }
	$rec_dropdown_broadcast = $rec_dropdown_broadcast."<option value=\"$obj->id\">$obj->e2location</option>"; }
	}
	}

	$sql = "SELECT * FROM `epg_data` WHERE e2eventstart BETWEEN '$time_start' and '$time_end' ORDER BY e2eventstart ASC";

	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	
	if (!isset($broadcast_list) or $broadcast_list == "") { $broadcast_list = ""; }
	
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
	$spacer = '';
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$e2eventstart = $obj->e2eventstart;
	$date_start = date("l n/d/Y", $e2eventstart);
	
	$e2eventend = $obj->e2eventend;
	$date_end = date("l n/d/Y - g:i A", $e2eventend);
	
	$broadcast_time = date("g:i A", $e2eventstart).' - '.date("g:i A", $e2eventend);;
	$spacer = '';
	
	$time_string_length = strlen($broadcast_time);
	if($time_string_length == 16){ $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;'; }
	if($time_string_length == 17){ $spacer = '&nbsp;&nbsp;&nbsp;'; }
	if($time_string_length == 18){ $spacer = '&nbsp;&nbsp;'; }
	if($time_string_length > 18){ $spacer = '&nbsp;'; }
	}
	
	if ($streaming_symbol == '1' ){
	$stream_broadcast = '<a href="'.$url_format.'://'.$box_user.':'.$box_password.'@'.$box_ip.'/web/stream.m3u?ref='.$obj->e2eventservicereference.'" title="Stream">
	<i class="fa fa-desktop fa-1x"></i></a>'; 
	} else { 
	$stream_broadcast = '';
	}
	
	if ($imdb_symbol == '1' ){
	$imdb_broadcast = '<a href="https://www.imdb.com/find?ref_=nv_sr_fn&q='.$title_enc.'" target="_blank" title="Info on IMDb">
	<i class="fa fa-info-circle fa-1x"></i></a>'; 
	} else { 
	$imdb_broadcast = ''; 
	}
	
	// mark existing timer
	if ($obj->timer == '1'){ $timer = "timer"; } else { $timer = ""; }
	
	$rand = substr(str_shuffle(str_repeat('0123456789',3)),0,3);

	$broadcast_list = $broadcast_list."
		<div id=\"broadcast_main\">
	  <div id=\"broadcast_$rand$obj->hash\" style=\"cursor: pointer;\" onclick=\"broadcast_list_desc(this.id);\">
		<div id=\"cnt_time\"> <span class=\"$timer\">$broadcast_time$spacer</span> </div>
		<div id=\"cnt_title\"> <span class=\"$timer\">$title_enc</span>
		  <div id=\"broadcast_desc_inner\"> </div>
		</div>
		<div id=\"cnt_channel\"> <span class=\"$timer\">$servicename_enc</span> </div>
		<div style=\"clear:both\"></div>
	  </div>
	  <div id=\"broadcast_btn_$rand$obj->hash\" style=\"display:none;\"><div class=\"spacer_5\"></div>
	  <strong>$date_start</strong><br />
	  <div id=\"broadcast_div_$rand$obj->hash\">
		  <div class=\"spacer_5\"></div>
		  $description_enc
		  <div class=\"spacer_5\"></div>
		  $descriptionextended_enc
		  <div class=\"spacer_5\"></div>
		</div>
		$imdb_broadcast $stream_broadcast <a href=\"search.php?searchterm=$obj->title_enc&option=title\" target=\"_blank\" title=\"Search this broadcast on all channels\">More from this broadcast</a>
		<div class=\"spacer_5\"></div>
		<div id=\"broadcast-tab-button-group\">
  <div id=\"row1\">
    <input id=\"broadcast_timer_btn_$rand$obj->hash\" type=\"submit\" onClick=\"broadcast_timer(this.id)\" value=\"SET TIMER\" class=\"btn btn-success btn-sm\" title=\"send timer instantly\"/> </div>
  <div id=\"row2\">
    <input id=\"broadcast_zap_btn_$rand$obj->hash\" type=\"submit\" onClick=\"broadcast_zap(this.id)\" value=\"ZAPP TO CHANNEL\" class=\"btn btn-default btn-sm\"/> </div>
  <div id=\"row3\">
	<span id=\"broadcast_status_zap_$rand$obj->hash\"></span> <span id=\"broadcast_status_timer_$rand$obj->hash\"></span> </div>
	<div style=\"clear:both\"></div>
	</div>
	<div class=\"spacer_5\"></div>
	<span>Record location: </span><select id=\"rec_location_broadcast_$rand$obj->hash\" class=\"rec_location_dropdown\">$rec_dropdown_broadcast</select>
	<div class=\"spacer_5\"></div>
	</div>
	</div>
	<div class=\"spacer_10\"></div>";
	}
	}
	if(!isset($broadcast_list) or $broadcast_list == "") { $broadcast_list = "No data for this day."; }
	
	echo $broadcast_list;
	
  // Free result set
  mysqli_free_result($result);

//close db
mysqli_close($dbmysqli);
?>

</body>
</html>
