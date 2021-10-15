<!DOCTYPE html>
<html>
<head>
<script>
$(function(){
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
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['e2servicereference']) or $_REQUEST['e2servicereference'] == ''){ $_REQUEST['e2servicereference'] = ''; }
	$e2servicereference = $_REQUEST['e2servicereference'];
	
	// record locations dropdown
	$sql = "SELECT * FROM `record_locations` WHERE `device` = '0' ORDER BY `id` ASC";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	if(!isset($rec_dropdown_channelbrowser) or $rec_dropdown_channelbrowser == ''){ $rec_dropdown_channelbrowser = ''; }
	$rec_dropdown_channelbrowser .= '<option value="'.$obj->id.'">'.$obj->e2location.'</option>'; 
	}
	}
	}
	
	// device dropdown
	$sql_2 = "SELECT * FROM `device_list` ORDER BY `id` ASC";
	
	if ($result_2 = mysqli_query($dbmysqli,$sql_2))
	{
	while ($obj = mysqli_fetch_object($result_2)) {
	{
	$id = $obj->id;
	$device_description = rawurldecode($obj->device_description);
	
	if(!isset($device_dropdown) or $device_dropdown == ''){ $device_dropdown = ''; }

	$device_dropdown .= '<option value="'.$id.'">'.$device_description.'</option>'; 
	}
	}
	}
	
	$sql = "SELECT * FROM `epg_data` WHERE `e2eventservicereference` = '".$e2servicereference."' AND `e2eventend` > '".$time."' ORDER BY `e2eventend` ASC LIMIT 0,50";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	if(!isset($epg_modal) or $epg_modal == ''){ $epg_modal = ''; }

	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	$e2eventservicereference = $obj->e2eventservicereference;
	$e2eventduration = $obj->e2eventduration;
	
	if ($time_format == '1')
	{
	// time format 1
	$e2eventstart = $obj->e2eventstart;
	$date_start = date('l, d.m.Y', $e2eventstart);
	$e2eventend = $obj->e2eventend;
	$broadcast_time = date('H:i', $e2eventstart).' - '.date('H:i', $e2eventend);
	$td_spacer = 'cnt_time';
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$e2eventstart = $obj->e2eventstart;
	$date_start = date('l, n/d/Y', $e2eventstart);
	$e2eventend = $obj->e2eventend;
	$date_end = date('l, n/d/Y - g:i A', $e2eventend);
	$broadcast_time = date('g:i A', $e2eventstart).' - '.date('g:i A', $e2eventend);
	$td_spacer = 'cnt_time_2';
	}
	
	if($streaming_symbol == '1')
	{
	$stream_broadcast = '<a href="'.$url_format.'://'.$box_ip.'/web/stream.m3u?ref='.$obj->e2eventservicereference.'&name='.$servicename_enc.'" target="_blank" title="Stream"><i class="fa fa-desktop fa-1x"></i></a>'; 
	} else { 
	$stream_broadcast = '';
	}
	
	if ($imdb_symbol == '1' ){ $imdb_broadcast = '<a href="https://www.imdb.com/find?ref_=nv_sr_fn&q='.$title_enc.'" target="_blank" title="Info on IMDb"><i class="fa fa-info-circle fa-1x"></i></a>'; 
	} else { 
	$imdb_broadcast = ''; }
	
	// mark existing timer
	if ($obj->timer == '1'){ $timer = 'timer'; } else { $timer = ''; }
	
	$rand = substr(str_shuffle(str_repeat('0123456789',3)),0,3);
	
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

	$epg_modal = $epg_modal."
	<div id=\"channelbrowser_main\">
	  <div id=\"channelbrowser_$rand$obj->hash\" style=\"cursor: pointer;\" onclick=\"channelbrowser_list_desc(this.id);\">
		<div id=\"$td_spacer\"> <span class=\"$timer\">$broadcast_time</span> </div>
		<div style=\"float:left\"> <span class=\"$timer\">$title_enc</span> </div>
		<div style=\"clear:both\"></div>
	  </div>
	  <div id=\"channelbrowser_btn_$rand$obj->hash\" style=\"display:none;\">
		<div class=\"spacer_5\"></div>
		<strong>$date_start</strong><br />
		<div id=\"channelbrowser_div_$rand$obj->hash\">
		  <div class=\"spacer_5\"></div>
		  $description_enc
		  <div class=\"spacer_5\"></div>
		  $descriptionextended_enc $event_duration
		  <div class=\"spacer_5\"></div>
		</div>
		$imdb_broadcast $stream_broadcast <a href=\"search.php?searchterm=$obj->title_enc&option=title&search_channel=on&channel_id=$obj->e2eventservicereference\" target=\"_blank\" title=\"Search this broadcast on this channel\">More from this broadcast</a>
		<div class=\"spacer_5\"></div>
		<div id=\"broadcast-tab-button-group\">
		  <div id=\"row1\">
			<input id=\"channelbrowser_timer_btn_$rand$obj->hash\" type=\"submit\" onClick=\"channelbrowser_timer(this.id,'record','modal')\" value=\"SET TIMER\" class=\"btn btn-success btn-xs\" title=\"Send timer to Receiver\"/> </div>
			<div id=\"row2\">
			<input id=\"channelbrowser_timer_btn_$rand$obj->hash\" type=\"submit\" onClick=\"channelbrowser_timer(this.id,'zap','modal')\" value=\"ZAP TIMER\" class=\"btn btn-warning btn-xs\" title=\"Send zap timer to Receiver\"/> </div>		
			<div id=\"row3\">
    <input id=\"channelbrowser_zap_btn_$rand$obj->hash\" type=\"submit\" name=\"$obj->e2eventservicereference\" onClick=\"channelbrowser_zap(this.id,this.name)\" value=\"ZAP TO CHANNEL\" class=\"btn btn-default btn-xs\"/ title=\"Zap to channel\"> </div>
		  <div id=\"row4\"> 
		  <span id=\"channelbrowser_status_zap_$rand$obj->hash\"></span>
		  <span id=\"channelbrowser_status_timer_$rand$obj->hash\"></span> 
		  </div>
		  <div style=\"clear:both\"></div>
		</div>
		<div class=\"spacer_5\"></div>
		<span>Receiver: </span>
		<select id=\"channelbrowser_device_dropdown_$rand$obj->hash\" class=\"device_dropdown\" onchange=\"channelbrowser_change_device(this.id)\">
		  <option value=\"0\">default</option>
		  $device_dropdown
		</select>
		<div class=\"spacer_10\"></div>
		<span>Record location: </span>
		<select id=\"rec_location_channelbrowser_$rand$obj->hash\" style=\"width:40%\">$rec_dropdown_channelbrowser
		</select>
	  </div>
	</div>
	<div class=\"spacer_10\"></div>";
	}
	}
	}
	
	if(!isset($epg_modal) or $epg_modal == '')
	{ 
	// xml from receiver
	$xmlfile = $url_format.'://'.$box_ip.'/web/epgservice?sRef='.$e2servicereference.$session_part_2;
	$getEPG_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string(preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $getEPG_request));

if($xml){

	for($i = 0; $i <= 50; $i++){

	if(!isset($xml->e2event[$i]->e2eventtitle) or $xml->e2event[$i]->e2eventtitle == ''){ $xml->e2event[$i]->e2eventtitle = ''; }
	
	if($xml->e2event[$i]->e2eventtitle != '')
	{
	// define search line
	$e2eventtitle = $xml->e2event[$i]->e2eventtitle;
	$e2eventservicename = $xml->e2event[$i]->e2eventservicename;
	$e2eventdescription = $xml->e2event[$i]->e2eventdescription;
	$e2eventdescriptionextended = $xml->e2event[$i]->e2eventdescriptionextended;
	$e2eventid = $xml->e2event[$i]->e2eventid;
	$e2eventstart = $xml->e2event[$i]->e2eventstart;
	$e2eventduration = $xml->e2event[$i]->e2eventduration;
	$e2eventcurrenttime = $xml->e2event[$i]->e2eventcurrenttime;
	$e2eventservicereference = $xml->e2event[$i]->e2eventservicereference;
	$starttime = $e2eventstart / 1;
	$e2eventend = $e2eventstart + $e2eventduration;
	
	// remove special chars
	$e2eventtitle = str_replace('\'', '', $e2eventtitle);
	$e2eventtitle = str_replace('"', '', $e2eventtitle);
	
	$e2eventdescription = str_replace('\'', '', $e2eventdescription);
	$e2eventdescription = str_replace('"', '', $e2eventdescription);
	
	$e2eventdescriptionextended = str_replace('\'', '', $e2eventdescriptionextended);
	$e2eventdescriptionextended = str_replace('"', '', $e2eventdescriptionextended);
	
	if ($time_format == '1')
	{
	// time format 1
	$date_start = date('l, d.m.Y', $starttime);
	$broadcast_time = date('H:i', $starttime).' - '.date('H:i', $e2eventend);
	$td_spacer = 'cnt_time';
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$date_start = date('l, n/d/Y', $starttime);
	$date_end = date('l, n/d/Y - g:i A', $e2eventend);
	$broadcast_time = date('g:i A', $starttime).' - '.date('g:i A', $e2eventend);
	$td_spacer = 'cnt_time_2';
	}
	
	if ($imdb_symbol == '1' ){ $imdb_broadcast = '<a href="https://www.imdb.com/find?ref_=nv_sr_fn&q='.$e2eventtitle.'" target="_blank" title="Info on IMDb"><i class="fa fa-info-circle fa-1x"></i></a>'; 
	} else { 
	$imdb_broadcast = ''; }
	
	$rand = substr(str_shuffle(str_repeat('0123456789',3)),0,3);
	
	$hash = hash('md4',$e2eventservicename.$e2eventstart.$e2eventend);
	
	if(!isset($epg_modal) or $epg_modal == ''){ $epg_modal = ''; }
	
		$epg_modal = $epg_modal."
	<div id=\"channelbrowser_main\">
	  <div id=\"channelbrowser_$rand$hash\" style=\"cursor: pointer;\" onclick=\"channelbrowser_list_desc(this.id);\">
		<div id=\"$td_spacer\"> <span>$broadcast_time</span> </div>
		<div style=\"float:left\">$e2eventtitle</div>
		<div style=\"clear:both\"></div>
	  </div>
	  <div id=\"channelbrowser_btn_$rand$hash\" style=\"display:none;\">
		<div class=\"spacer_5\"></div>
		<strong>$date_start</strong><br />
		<div id=\"channelbrowser_div_$rand$hash\">
		  <div class=\"spacer_5\"></div>
		  $e2eventdescription
		  <div class=\"spacer_5\"></div>
		  $e2eventdescriptionextended
		  <div class=\"spacer_5\"></div>
		</div>
		$imdb_broadcast <a href=\"search.php?searchterm=$e2eventtitle&option=title\" target=\"_blank\" title=\"Search this broadcast\">More from this broadcast</a>
		<div class=\"spacer_5\"></div>
	  </div>
	</div>
	<div class=\"spacer_10\"></div>";
	}
	
	}
	}
	}
	
	if(!isset($epg_modal) or $epg_modal == ''){ $epg_modal = 'No data for this day'; }
	
	echo $epg_modal;

?>

</body>
</html>
