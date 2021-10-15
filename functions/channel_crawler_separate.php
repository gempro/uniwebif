<?php 
//
include("../inc/dashboard_config.php");

	$channel_hash = $_REQUEST["channel_hash"];

	// set epg crawler as current working
	mysqli_query($dbmysqli, "UPDATE `settings` SET `epg_crawler_activ` = '1' ");
	
	$sql = mysqli_query($dbmysqli, "SELECT `e2servicereference` FROM `channel_list` WHERE `channel_hash` = '".$channel_hash."' ");
	$result = mysqli_fetch_assoc($sql);
	$e2servicereference = $result['e2servicereference'];
	
	// delete before crawling
	mysqli_query($dbmysqli, "DELETE FROM `epg_data` WHERE `channel_hash` = '".$channel_hash."' ");
	
	$xmlfile = $url_format.'://'.$box_ip.'/web/epgservice?sRef='.$e2servicereference.$session_part_2;
	$getEPG_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string(preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $getEPG_request));
	
	$sql = mysqli_query($dbmysqli, "SELECT `e2eventstart` FROM `epg_data` WHERE `e2eventservicereference` LIKE '".$e2servicereference."' ORDER BY `e2eventstart` DESC");
	$result = mysqli_fetch_assoc($sql);
	$last_epg = $result['e2eventstart'];

	if($xml)
	{
	for ($i = 0; $i <= $epg_entries_per_channel; $i++){

	if(!isset($xml->e2event[$i]->e2eventtitle) or $xml->e2event[$i]->e2eventtitle == ""){ $xml->e2event[$i]->e2eventtitle = ""; }
	
	// if no title dont write in database
	if($xml->e2event[$i]->e2eventtitle != "")
	{
	// define search line
	$e2eventtitle = utf8_decode($xml->e2event[$i]->e2eventtitle);
	$title_enc = rawurlencode($xml->e2event[$i]->e2eventtitle);
	$e2eventservicename = utf8_decode($xml->e2event[$i]->e2eventservicename);
	$servicename_enc = rawurlencode($xml->e2event[$i]->e2eventservicename);
	$e2eventdescription = utf8_decode($xml->e2event[$i]->e2eventdescription);
	$description_enc = rawurlencode($xml->e2event[$i]->e2eventdescription);
	$e2eventdescriptionextended = utf8_decode($xml->e2event[$i]->e2eventdescriptionextended);
	$descriptionextended_enc = rawurlencode($xml->e2event[$i]->e2eventdescriptionextended);
	$e2eventid = $xml->e2event[$i]->e2eventid;
	$e2eventstart = $xml->e2event[$i]->e2eventstart;
	$e2eventduration = $xml->e2event[$i]->e2eventduration;
	$e2eventcurrenttime = $xml->e2event[$i]->e2eventcurrenttime;
	$e2eventservicereference = $xml->e2event[$i]->e2eventservicereference;
	$starttime = $e2eventstart / 1;
	$e2eventend = $e2eventstart + $e2eventduration;
	
	// remove special chars
//	$e2eventtitle = str_replace("'", "", $e2eventtitle);
//	$e2eventtitle = str_replace("\"", "", $e2eventtitle);
//	$title_enc = str_replace("'", "%20", $title_enc);
//	$title_enc = str_replace("\"", "%20", $title_enc);
//	$title_enc = str_replace("%5Cn", "%20", $title_enc);
//	$title_enc = str_replace("%C2%8A", "%20", $title_enc);
//	$e2eventdescription = str_replace("'", "", $e2eventdescription);
//	$e2eventdescription = str_replace("\"", "", $e2eventdescription);
//	$e2eventdescription = str_replace("%5Cn", "%20", $e2eventdescription);
//	$e2eventdescription = str_replace("%C2%8A", "%20", $e2eventdescription);
//	$description_enc = str_replace("'", "", $description_enc);
//	$description_enc = str_replace("\"", "", $description_enc);
//	$description_enc = str_replace("%5Cn", "%20", $description_enc);
//	$description_enc = str_replace("%C2%8A", "%20", $description_enc);
//	$e2eventdescriptionextended = str_replace("'", "", $e2eventdescriptionextended);
//	$e2eventdescriptionextended = str_replace("\"", "", $e2eventdescriptionextended);
//	$descriptionextended_enc = str_replace("%5Cn", "%20", $descriptionextended_enc);
//	$descriptionextended_enc = str_replace("%C2%8A", "%20", $descriptionextended_enc);
	
	$replace = array('\'' => '', '"' => '', '%5Cn' => '%20', '%C2%8A' => '%20');
	$e2eventtitle = strtr($e2eventtitle, $replace);
	$title_enc = strtr($title_enc, $replace);
	$e2eventdescription = strtr($e2eventdescription, $replace);
	$description_enc = strtr($description_enc, $replace);
	$e2eventdescriptionextended = strtr($e2eventdescriptionextended, $replace);
	$descriptionextended_enc = strtr($descriptionextended_enc, $replace);
	
	// timestamp start
	$start_day = date("d",$starttime);
	$start_weekday = date("l",$starttime);
	$start_month = date("m",$starttime);
	$start_year = date("Y",$starttime);
	$start_hour = date("H",$starttime);
	$start_minute = date("i",$starttime);
	$start_date = date("d.m.Y H:i", $starttime);
	$us_start_date = date("m/d/Y H:i A", $starttime);
		
	// timestamp end
	$end_day = date("d",$e2eventend);
	$end_weekday = date("l",$e2eventend);
	$end_month = date("m",$e2eventend);
	$end_year = date("Y",$e2eventend);
	$end_hour = date("H",$e2eventend);
	$end_minute = date("i",$e2eventend);
	$end_date = date("d.m.Y H:i", $e2eventend);
	$us_end_date = date("m/d/Y H:i A", $e2eventend);
	
	// mark hd channels
	if (preg_match("/\bHD\b/i", $e2eventservicename)) {
	$hd_channel = 'yes';
	} else {
	$hd_channel = 'no';
	}
	
	// complete time
	$difference = $e2eventend - $starttime;
	$hours = floor ($difference / 3600);
	$total_min = floor ($difference / 60);
	$rest = $difference % 3600;
	$minutes = floor ($rest / 60);
	$seconds = $rest % 60;
	
	// crawler_time
	$crawler_time = time();
	
	// unique id
	$hash = hash('md4',$servicename_enc.$e2eventstart.$e2eventend);
	
	// channel hash
	$channel_hash = hash('md4',$e2eventservicename);
	
	// if last epg <
	if($last_epg < $e2eventstart)
	{
	mysqli_query($dbmysqli, "INSERT INTO `epg_data`
	(
	e2eventtitle, 
	title_enc, 
	e2eventservicename, 
	servicename_enc, 
	e2eventdescription, 
	description_enc, 
	e2eventdescriptionextended, 
	descriptionextended_enc, 
	e2eventid, 
	start_date, 
	us_start_date, 
	start_day, 
	start_month, 
	start_year, 
	start_hour, 
	start_minute, 
	start_weekday, 
	end_date, 
	us_end_date, 
	end_day, 
	end_month, 
	end_year, 
	end_hour, 
	end_minute, 
	end_weekday, 
	total_min, 
	e2eventstart, 
	e2eventend, 
	e2eventduration, 
	e2eventcurrenttime, 
	e2eventservicereference, 
	hd_channel, 
	crawler_time, 
	hash, 
	channel_hash
	) VALUES (
	'$e2eventtitle', 
	'$title_enc', 
	'$e2eventservicename', 
	'$servicename_enc', 
	'$e2eventdescription', 
	'$description_enc', 
	'$e2eventdescriptionextended', 
	'$descriptionextended_enc', 
	'$e2eventid', 
	'$start_date', 
	'$us_start_date', 
	'$start_day', 
	'$start_month', 
	'$start_year', 
	'$start_hour', 
	'$start_minute', 
	'$start_weekday', 
	'$end_date', 
	'$us_end_date', 
	'$end_day', 
	'$end_month', 
	'$end_year', 
	'$end_hour', 
	'$end_minute', 
	'$end_weekday', 
	'$total_min', 
	'$e2eventstart', 
	'$e2eventend', 
	'$e2eventduration', 
	'$e2eventcurrenttime', 
	'$e2eventservicereference', 
	'$hd_channel', 
	'$crawler_time', 
	'$hash', 
	'$channel_hash'
	)");
	}
	
	}
	}
	}
	// latest entry
	$sql = mysqli_query($dbmysqli, "SELECT `e2eventend` FROM `epg_data` WHERE `channel_hash` = '".$channel_hash."' ORDER BY `e2eventend` DESC LIMIT 0 , 1");
	$result = mysqli_fetch_assoc($sql);
	$last_epg = $result['e2eventend'];
	
	// last crawl / last entry
	mysqli_query($dbmysqli, "UPDATE `channel_list` SET `last_crawl` = '".$time."', `last_epg` = '".$last_epg."' WHERE `channel_hash` = '".$channel_hash."' ");
	
	// update last epg timestamp // settings
	$sql = mysqli_query($dbmysqli, "SELECT `e2eventend` FROM `epg_data` ORDER BY `e2eventend` DESC LIMIT 0 , 1");
	$result = mysqli_fetch_assoc($sql);
	$last_epg = $result['e2eventend'];
	
	mysqli_query($dbmysqli, "UPDATE `settings` SET `last_epg` = '".$last_epg."' WHERE `id` = '0' ");
	
	// reset saved search
	mysqli_query($dbmysqli, "UPDATE `saved_search` SET `crawled` = '0' WHERE `activ` = 'yes' ");
	
	// set epg crawler not working
	mysqli_query($dbmysqli, "UPDATE `settings` SET `epg_crawler_activ` = '0' ");
	
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `epg_data` WHERE `channel_hash` LIKE '".$channel_hash."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($time_format == '1'){ $last_crawl = date('d.m.Y, H:i', $time); }
	if($time_format == '2'){ $last_crawl = date('n/d/Y, g:i A', $time); }
	
	// answer for ajax
	$json['status'] = 'done';
	$json['last_crawl'] = $last_crawl;
	$json['summary'] = $summary;
	
	echo json_encode($json);

?>