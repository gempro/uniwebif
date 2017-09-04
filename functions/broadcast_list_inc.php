<?php 

//	broadcast list main
	$timestamp = time();
	$today_start = $timestamp - $dur_down_broadcast;
	$today_end = $timestamp + $dur_up_broadcast;
	
	// get record locations
	$sql2 = "SELECT * FROM `record_locations` ORDER BY id ASC";
	
	if ($result2 = mysqli_query($dbmysqli,$sql2))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result2)) {	
	{
	if(!isset($rec_dropdown_broadcast) or $rec_dropdown_broadcast == "") { $rec_dropdown_broadcast = ""; } else { $rec_dropdown_broadcast = $rec_dropdown_broadcast; }

	$rec_dropdown_broadcast = $rec_dropdown_broadcast."<option value=\"$obj->id\">$obj->e2location</option>"; }
	}
	}
	
	$sql = "SELECT * FROM `epg_data` WHERE e2eventstart BETWEEN '$today_start' and '$today_end' ORDER BY e2eventstart ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	
	if(!isset($broadcast_list_main_today) or $broadcast_list_main_today == ""){ $broadcast_list_main_today = ""; } else { $broadcast_list_main_today = $broadcast_list_main_today; }
	
	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	
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
	
	$broadcast_list_main_today = $broadcast_list_main_today."
		<div id=\"broadcast_main\">
	  <div id=\"broadcast_$rand$obj->hash\" style=\"cursor: pointer;\" onclick=\"broadcast_list_desc(this.id);\">
		<div id=\"cnt_time\"> <span class=\"$timer\">$broadcast_time</span> </div>
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
    <input id=\"broadcast_timer_btn_$rand$obj->hash\" type=\"submit\" onClick=\"broadcast_timer(this.id)\" value=\"SET TIMER\" class=\"btn btn-success\" title=\"send timer instantly\"/> </div>
  <div id=\"row2\">
    <input id=\"broadcast_zap_btn_$rand$obj->hash\" type=\"submit\" onClick=\"broadcast_zap(this.id)\" value=\"ZAPP TO CHANNEL\" class=\"btn btn-default\"/> </div>
  <div id=\"row3\"><span id=\"broadcast_status_zap_$rand$obj->hash\"></span> <span id=\"broadcast_status_timer_$rand$obj->hash\"></span>
  </div>
  <div style=\"clear:both\"></div>
  </div>
  <div class=\"spacer_5\"></div>
  <span>Record location: </span><select id=\"rec_location_broadcast_$rand$obj->hash\" class=\"rec_location_dropdown\">$rec_dropdown_broadcast</select>
  <div class=\"spacer_5\"></div>
  </div>
  </div>
  <div class=\"spacer_10\"></div>";
  
  if(!isset($broadcast_list_main_today) or $broadcast_list_main_today == "") { $broadcast_list_main_today = "No data for this day"; } else { $broadcast_list_main_today = $broadcast_list_main_today; }
	
	}
}
?>