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
	$count_timer = $count_timer.' Timer in Database';
	
	// sent timer
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as sent_timer FROM `timer` WHERE `expired` = "0" AND `status` = "sent" OR `expired` = "0" AND `status` = "manual" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($sent_timer);
	$stmt->fetch();
	$stmt->close();
	if ($sent_timer > 0){ 
	$show_sent_timer = ' | <span class="timer_panel_info">'.$sent_timer.' sent | </span>'; 
	} else { $show_sent_timer = ' | <span class="timer_panel_info">0 sent | </span>';
	}
	
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
	if ($sent_timer > 0){
	$show_timer_today = ' <span class="timer_panel_info">'.$timer_today.' today | </span>'; 
	} else { $show_timer_today = ' <span class="timer_panel_info">0 today | </span>'; 
	}
	
	// hidden timer
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as hidden_timer FROM `timer` WHERE `expired` = "0" AND `hide` = "1" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($hidden_timer);
	$stmt->fetch();
	$stmt->close();
	if ($hidden_timer > 0){ 
	$show_hidden_timer = ' <span class="timer_panel_info">
	<a id="show_unhide" onclick="timerlist_panel(this.id)" title="show" style="cursor:pointer;">'.$hidden_timer.' hidden</a></span>'; 
	} else { 
	$show_hidden_timer = '<span class="timer_panel_info"> 0 hidden</span>';
	}
	
	// count saved search
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_saved_search FROM `saved_search` ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_saved_search);
	$stmt->fetch();
	$stmt->close();
	$count_saved_search = '('.$count_saved_search.')';
	
	// timer on receiver
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/timerlist';
	$getTimer = file_get_contents($xmlfile, false, $webrequest);
	$sum = preg_match_all("#<e2timerlist>(.*?)</e2timerlist>#si", $getTimer, $match_sum);
	$timer_summary = preg_match_all("#<e2timer>(.*?)</e2timer>#si", $match_sum[0][0]);
	
	$receiver_timer = ' <span class="timer_panel_info">| '.$timer_summary.' on Receiver</span>';
	
	echo $count_timer; echo $show_sent_timer; echo $show_timer_today; echo $show_hidden_timer; echo $receiver_timer;

?>