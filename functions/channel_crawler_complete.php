<?php 
//
include("../inc/dashboard_config.php");
include("utc.php");

	$channel_id = $_REQUEST["channel_id"];
	
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/epgservice?sRef='.$channel_id.'';
	
	$getEPG_request = file_get_contents($xmlfile, false, $webrequest);
	
	$xml = simplexml_load_string(preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $getEPG_request));

if ($xml) {
    for ($i = 0; $i <= $epg_entries_per_channel; $i++) {

	///////////////////////////////////////////////
	if(!isset($xml->e2event[$i]->e2eventtitle) or $xml->e2event[$i]->e2eventtitle == "") 
	{
	$xml->e2event[$i]->e2eventtitle = "";
	
	} else { 
	
	$xml->e2event[$i]->e2eventtitle = $xml->e2event[$i]->e2eventtitle;
	}
	
	// if no title dont write in database
	if($xml->e2event[$i]->e2eventtitle == "" ) {
	
	} else {
	
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
	$e2eventtitle = str_replace("'", "", $e2eventtitle);
	$e2eventtitle = str_replace("\"", "", $e2eventtitle);
	
	$e2eventdescription = str_replace("'", "", $e2eventdescription);
	$e2eventdescription = str_replace("\"", "", $e2eventdescription);
	
	$e2eventdescriptionextended = str_replace("'", "", $e2eventdescriptionextended);
	$e2eventdescriptionextended = str_replace("\"", "", $e2eventdescriptionextended);
	
	// Timestamp Start
	$start_day = date("d",$starttime);
	$start_month = date("m",$starttime);
	$start_year = date("Y",$starttime);
	
	$start_hour = date("H",$starttime);
	$start_minute = date("i",$starttime);
	
	$start_date = $start_day. "." .$start_month. "." .$start_year. " " .$start_hour. ":" .$start_minute;
	
	$start_weekday = date("l",$starttime);
	
	if ($time_format == '1')
	{
	// time format 1
	$start_weekday = str_replace("Monday", "Montag", $start_weekday);
	$start_weekday = str_replace("Tuesday", "Dienstag", $start_weekday);
	$start_weekday = str_replace("Wednesday", "Mittwoch", $start_weekday);
	$start_weekday = str_replace("Thursday", "Donnerstag", $start_weekday);
	$start_weekday = str_replace("Friday", "Freitag", $start_weekday);
	$start_weekday = str_replace("Saturday", "Samstag", $start_weekday);
	$start_weekday = str_replace("Sunday", "Sonntag", $start_weekday);
	}
		
	// Timestamp End
	$end_day = date("d",$e2eventend);
	$end_month = date("m",$e2eventend);
	$end_year = date("Y",$e2eventend);
	
	$end_hour = date("H",$e2eventend);
	$end_minute = date("i",$e2eventend);
	
	$end_date = $end_day. "." .$end_month. "." .$end_year. " " .$end_hour. ":" .$end_minute;
	
	if ($time_format == '1')
	{ // time format 1
	$end_weekday = date("l",$e2eventend);
	$end_weekday = str_replace("Monday", "Montag", $end_weekday);
	$end_weekday = str_replace("Tuesday", "Dienstag", $end_weekday);
	$end_weekday = str_replace("Wednesday", "Mittwoch", $end_weekday);
	$end_weekday = str_replace("Thursday", "Donnerstag", $end_weekday);
	$end_weekday = str_replace("Friday", "Freitag", $end_weekday);
	$end_weekday = str_replace("Saturday", "Samstag", $end_weekday);
	$end_weekday = str_replace("Sunday", "Sonntag", $end_weekday);
	}
		
	// timeformat
	// us start time
	$starthour = date("H",$starttime);
	if ($starthour == '00'){ $us_starthour = '12' and $ampm = 'AM';}
	if ($starthour == '01'){ $us_starthour = '1' and $ampm = 'AM';}
	if ($starthour == '02'){ $us_starthour = '2' and $ampm = 'AM';}
	if ($starthour == '03'){ $us_starthour = '3' and $ampm = 'AM';}
	if ($starthour == '04'){ $us_starthour = '4' and $ampm = 'AM';}
	if ($starthour == '05'){ $us_starthour = '5' and $ampm = 'AM';}
	if ($starthour == '06'){ $us_starthour = '6' and $ampm = 'AM';}
	if ($starthour == '07'){ $us_starthour = '7' and $ampm = 'AM';}
	if ($starthour == '08'){ $us_starthour = '8' and $ampm = 'AM';}
	if ($starthour == '09'){ $us_starthour = '9' and $ampm = 'AM';}
	if ($starthour == '10'){ $us_starthour = '10' and $ampm = 'AM';}
	if ($starthour == '11'){ $us_starthour = '11' and $ampm = 'AM';}
	if ($starthour == '12'){ $us_starthour = '12' and $ampm = 'PM';}
	if ($starthour == '13'){ $us_starthour = '1' and $ampm = 'PM';}
	if ($starthour == '14'){ $us_starthour = '2' and $ampm = 'PM';}
	if ($starthour == '15'){ $us_starthour = '3' and $ampm = 'PM';}
	if ($starthour == '16'){ $us_starthour = '4' and $ampm = 'PM';}
	if ($starthour == '17'){ $us_starthour = '5' and $ampm = 'PM';}
	if ($starthour == '18'){ $us_starthour = '6' and $ampm = 'PM';}
	if ($starthour == '19'){ $us_starthour = '7' and $ampm = 'PM';}
	if ($starthour == '20'){ $us_starthour = '8' and $ampm = 'PM';}
	if ($starthour == '21'){ $us_starthour = '9' and $ampm = 'PM';}
	if ($starthour == '22'){ $us_starthour = '10' and $ampm = 'PM';}
	if ($starthour == '23'){ $us_starthour = '11' and $ampm = 'PM';}
	
	$us_start_date = $start_month. "/" .$start_day. "/" .$start_year. " " .$us_starthour. ":" .$start_minute. " " .$ampm;
	
	// us end time
	$endhour = date("H",$e2eventend);
	if ($endhour == '00'){ $us_endhour = '12' and $ampm = 'AM';}
	if ($endhour == '01'){ $us_endhour = '1' and $ampm = 'AM';}
	if ($endhour == '02'){ $us_endhour = '2' and $ampm = 'AM';}
	if ($endhour == '03'){ $us_endhour = '3' and $ampm = 'AM';}
	if ($endhour == '04'){ $us_endhour = '4' and $ampm = 'AM';}
	if ($endhour == '05'){ $us_endhour = '5' and $ampm = 'AM';}
	if ($endhour == '06'){ $us_endhour = '6' and $ampm = 'AM';}
	if ($endhour == '07'){ $us_endhour = '7' and $ampm = 'AM';}
	if ($endhour == '08'){ $us_endhour = '8' and $ampm = 'AM';}
	if ($endhour == '09'){ $us_endhour = '9' and $ampm = 'AM';}
	if ($endhour == '10'){ $us_endhour = '10' and $ampm = 'AM';}
	if ($endhour == '11'){ $us_endhour = '11' and $ampm = 'AM';}
	if ($endhour == '12'){ $us_endhour = '12' and $ampm = 'PM';}
	if ($endhour == '13'){ $us_endhour = '1' and $ampm = 'PM';}
	if ($endhour == '14'){ $us_endhour = '2' and $ampm = 'PM';}
	if ($endhour == '15'){ $us_endhour = '3' and $ampm = 'PM';}
	if ($endhour == '16'){ $us_endhour = '4' and $ampm = 'PM';}
	if ($endhour == '17'){ $us_endhour = '5' and $ampm = 'PM';}
	if ($endhour == '18'){ $us_endhour = '6' and $ampm = 'PM';}
	if ($endhour == '19'){ $us_endhour = '7' and $ampm = 'PM';}
	if ($endhour == '20'){ $us_endhour = '8' and $ampm = 'PM';}
	if ($endhour == '21'){ $us_endhour = '9' and $ampm = 'PM';}
	if ($endhour == '22'){ $us_endhour = '10' and $ampm = 'PM';}
	if ($endhour == '23'){ $us_endhour = '11' and $ampm = 'PM';}
	
	$us_end_date = $end_month. "/" .$end_day. "/" .$end_year. " " .$us_endhour. ":" .$end_minute. " " .$ampm;
	
	// mark hd channels
	if (preg_match("/\bHD\b/i", $e2eventservicename)) {
	$hd_channel = 'yes';
	} else {
	$hd_channel = 'no';
	}
	
	// complete time
	$difference = $e2eventend - $starttime;
	$stunden = floor ($difference / 3600);
	$total_min = floor ($difference / 60);
	$rest = $difference % 3600;
	$minuten = floor ($rest / 60);
	$sekunden = $rest % 60;
	
	// crawler_time
	$crawler_time = "".$thedate." ".$thetime."";
	
	// hash
	$hash = substr(md5(rand()),0,50);
	
	// channel hash
	$channel_hash = hash('md4',$e2eventservicename);
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO epg_data (e2eventtitle,title_enc,e2eventservicename,servicename_enc,e2eventdescription,description_enc,e2eventdescriptionextended,descriptionextended_enc,e2eventid,start_date,us_start_date,start_day,start_month,start_year,start_hour,start_minute,start_weekday,end_date,us_end_date,end_day,end_month,end_year,end_hour,end_minute,end_weekday,total_min,e2eventstart,e2eventend,e2eventduration,e2eventcurrenttime,e2eventservicereference,hd_channel,crawler_time,hash,channel_hash)
	 values ('$e2eventtitle','$title_enc','$e2eventservicename','$servicename_enc','$e2eventdescription','$description_enc','$e2eventdescriptionextended','$descriptionextended_enc','$e2eventid','$start_date','$us_start_date','$start_day','$start_month','$start_year','$start_hour','$start_minute','$start_weekday','$end_date','$us_end_date','$end_day','$end_month','$end_year','$end_hour','$end_minute','$end_weekday','$total_min','$e2eventstart','$e2eventend','$e2eventduration','$e2eventcurrenttime','$e2eventservicereference','$hd_channel','$crawler_time','$hash','$channel_hash')"); 
	}
	}
	}
	$time = time();
	$sql = mysqli_query($dbmysqli, "UPDATE channel_list set last_crawl = '$time' WHERE channel_hash = '$channel_hash' ");
	
	// close db
	mysqli_close($dbmysqli);
?>