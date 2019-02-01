<?php 
//
	include("../inc/dashboard_config.php");
	
	// count timer
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `timer` WHERE `expired` = "0" ');
	$result = mysqli_fetch_row($sql);
	$count_timer = $result[0];
	
	// sent timer
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `timer` WHERE `expired` = "0" AND `status` = "sent" OR `expired` = "0" AND `status` = "manual" ');
	$result = mysqli_fetch_row($sql);
	$sent_timer = $result[0];
	
	//
	$start = date("d.m.Y, 00:00", $time);
	$end = date("d.m.Y, 23:59", $time);
	$start_time = strtotime($start);
	$end_time = strtotime($end);
	
	// hidden timer today
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `timer` WHERE `e2eventstart` BETWEEN "'.$start_time.'" AND "'.$end_time.'" AND `expired` = "0" AND `hide` = "1" ');
	$result = mysqli_fetch_row($sql);
	$hidden_timer_today = $result[0];
	
	// timer today
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `timer` WHERE `e2eventstart` BETWEEN "'.$start_time.'" AND "'.$end_time.'" AND `expired` = "0" ');
	$result = mysqli_fetch_row($sql);
	$timer_today = $result[0];
	
	// hidden timer
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `timer` WHERE `expired` = "0" AND `hide` = "1" ');
	$result = mysqli_fetch_row($sql);
	$hidden_timer = $result[0];
	
	// timer on receiver
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/timerlist';
	$getTimer = @file_get_contents($xmlfile, false, $webrequest);
	$sum = preg_match_all("#<e2timerlist>(.*?)</e2timerlist>#si", $getTimer, $match_sum);
	$timer_summary = preg_match_all("#<e2timer>(.*?)</e2timer>#si", $match_sum[0][0]);
	
	echo '
	[{"timer_total":"'.$count_timer.'",
	"sent_timer":"'.$sent_timer.'",
	"timer_today":"'.$timer_today.'",
	"hidden_timer_today":"'.$hidden_timer_today.'",
	"hidden_timer":"'.$hidden_timer.'",
	"receiver_timer":"'.$timer_summary.'\r"}]';

?>