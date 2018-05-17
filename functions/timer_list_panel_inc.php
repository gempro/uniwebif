<?php 
//
	include("../inc/dashboard_config.php");
	
	// count timer
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_timer FROM `timer` WHERE `expired` = "0" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_timer);
	$stmt->fetch();
	$stmt->close();
	
	// sent timer
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as sent_timer FROM `timer` WHERE `expired` = "0" AND `status` = "sent" OR `expired` = "0" AND `status` = "manual" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($sent_timer);
	$stmt->fetch();
	$stmt->close();
	
	// timer today
	$start = date("d.m.Y, 00:00", $time);
	$end = date("d.m.Y, 23:59", $time);
	$start_time = strtotime($start);
	$end_time = strtotime($end);
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as timer_today FROM `timer` WHERE `e2eventstart` BETWEEN "'.$start_time.'" AND "'.$end_time.'" AND `expired` = "0" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($timer_today);
	$stmt->fetch();
	$stmt->close();
	
	// hidden timer
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as hidden_timer FROM `timer` WHERE `expired` = "0" AND `hide` = "1" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($hidden_timer);
	$stmt->fetch();
	$stmt->close();
	
	// timer on receiver
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/timerlist';
	$getTimer = file_get_contents($xmlfile, false, $webrequest);
	$sum = preg_match_all("#<e2timerlist>(.*?)</e2timerlist>#si", $getTimer, $match_sum);
	$timer_summary = preg_match_all("#<e2timer>(.*?)</e2timer>#si", $match_sum[0][0]);
	
	$json = array();
	
	echo '
	[{"timer_total":"'.$count_timer.'",
	"sent_timer":"'.$sent_timer.'",
	"timer_today":"'.$timer_today.'",
	"hidden_timer":"'.$hidden_timer.'",
	"receiver_timer":"'.$timer_summary.'\r"}]';

?>