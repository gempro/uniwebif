<?php 
//	time format 1
	$date_for_primetime = date("d.m.Y");
	$date = $date_for_primetime.'20:15';
	
	// time format 2
//	$date_now = date("l n/d/Y - g:i A", $timestamp);
//	$date_for_primetime = date("n/d/Y");
//	$date = $date_for_primetime.'11:59 PM';
//	$primetime = strtotime($date);
	//
	
	$primetime_start = strtotime($date) - $dur_down_primetime;
	$primetime_end = strtotime($date) + $dur_up_primetime;
	
	// get record locations
	$sql2 = "SELECT * FROM `record_locations` ORDER BY id ASC";
	
	if ($result2 = mysqli_query($dbmysqli,$sql2))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result2)) {	
	{
	if(!isset($rec_dropdown_primetime) or $rec_dropdown_primetime == "") { $rec_dropdown_primetime = ""; } else { $rec_dropdown_primetime = $rec_dropdown_primetime; }
	$rec_dropdown_primetime = $rec_dropdown_primetime."<option value=\"$obj->id\">$obj->e2location</option>"; }
	}
	}
	
	$sql = "SELECT * FROM `epg_data` WHERE e2eventstart BETWEEN '$primetime_start' and '$primetime_end' ORDER BY e2eventstart ASC";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	
	if(!isset($primetime_list_main_today) or $primetime_list_main_today == "") { $primetime_list_main_today = ""; } else { $primetime_list_main_today = $primetime_list_main_today;
	}
	
	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	
	if ($time_format == '1')
	{
	// time format 1
	//$date_start = date("l, d.m.Y - H:i", $e2eventstart);
	$e2eventstart = $obj->e2eventstart;
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
	$primetime_time = "$date_start_hour:$date_start_minute - $date_end_hour:$date_end_minute";
	}
	
	if ($time_format == '2')
	{
	// time format 2
	//$date_start = date("l n/d/Y - g:i A", $e2eventstart);
	$e2eventstart = $obj->e2eventstart;
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
	$primetime_time = "$date_start_hour:$date_start_minute $date_start_ampm - $date_end_hour:$date_end_minute $date_end_ampm";
	}
	
	if ($streaming_symbol == '1' ){ $stream_broadcast = '<a href="http://'.$box_user.':'.$box_password.'@'.$box_ip.'/web/stream.m3u?ref='.$obj->e2eventservicereference.'" title="Stream"><i class="fa fa-desktop fa-1x"></i></a>'; 
	} else { 
	$stream_broadcast = ''; }
	
	// mark existing timer
	if ($obj->timer == '1'){ $timer = "timer"; } else { $timer = ""; }
	
	$primetime_list_main_today = $primetime_list_main_today."
		<div id=\"primetime_main\">
	  <div id=\"primetime_$obj->hash\" style=\"cursor: pointer;\" onclick=\"primetime_list_desc(this.id);\">
		<div id=\"cnt_time\"> <span class=\"$timer\">$primetime_time</span> </div>
		<div id=\"cnt_title\"> <span class=\"$timer\">$title_enc</span>
		  <div id=\"primetime_desc_inner\"> </div>
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
		  $descriptionextended_enc
		  <div class=\"spacer_5\"></div>
		</div>
		$stream_broadcast <a href=\"search.php?searchterm=$obj->title_enc&option=title\" target=\"_blank\" title=\"Search this broadcast on all channels\">More from this broadcast</a>
		<div class=\"spacer_5\"></div>
		<div id=\"broadcast-tab-button-group\">
  <div id=\"row1\">
    <input id=\"primetime_timer_btn_$obj->hash\" type=\"submit\" onClick=\"primetime_timer(this.id)\" value=\"SET TIMER\" class=\"btn btn-success\"/ title=\"send timer instantly\"> </div>
  <div id=\"row2\">
    <input id=\"primetime_zap_btn_$obj->hash\" type=\"submit\" onClick=\"primetime_zap(this.id)\" value=\"ZAPP TO CHANNEL\" class=\"btn btn-default\"/> </div>
  <div id=\"row3\">
    <select name=\"\" id=\"rec_location_primetime_$obj->hash\">$rec_dropdown_primetime</select>
		<span id=\"primetime_status_zap_$obj->hash\"></span> <span id=\"primetime_status_timer_$obj->hash\"></span> </div>
  <div style=\"clear:both\"></div>
</div> </div>
	</div>
	<div class=\"spacer_10\"></div>";
	}
}
?>