<?php
include("../inc/dashboard_config.php");

	$timestamp = time();
	
	// time format 1
	$format_for_int_endtime = date("d.m.Y");
	$endtime = $format_for_int_endtime.'23:59:59';
	$end_timestamp = strtotime($endtime);
	
	// time format 2
	//$format_for_us_endtime = date("n/d/Y");
//	$us_date = $format_for_us_endtime.'11:59:59 PM';
//	$end_timestamp = strtotime($us_date);
	
	// count remaining entries
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) AS count_remaining FROM `epg_data` WHERE e2eventend BETWEEN "'.$timestamp.'" and "'.$end_timestamp.'" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	
	$stmt->execute();
	$stmt->bind_result($count_remaining);
	$stmt->fetch();
	$stmt->close();
	
	// calculate progressbar
	//time format 1
	$broadcast_date = date("d.m.Y");
	// time format 2
	//$broadcast_date = date("n/d/Y");
	
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) AS sum FROM `epg_data` WHERE start_date LIKE "%'.$broadcast_date.'%" ');
	$stmt->execute();
	$stmt->bind_result($sum);
	$stmt->fetch();
	$stmt->close();
				
	if ($sum == '')
		  		{
				$sum = '1';
				}
	
	$progressbar1 = round($count_remaining*100/$sum,1);
	
	if ($progressbar1 < 7 ){ 
	echo 'Broadcast today: '.$count_remaining.' remaining..
	<div class="progress progress-striped">
	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: '.$progressbar1.'%">
	</div><span style="color:#000">&nbsp;'.$progressbar1.' %</span>
	</div>'; 
	
	} else {  
	
	echo 'Broadcast today: '.$count_remaining.' remaining..
	<div class="progress progress-striped">
	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: '.$progressbar1.'%">
	<span style="color:#fff">'.$progressbar1.' %</span>
	</div></div>';
	}
	
	// close db
	mysqli_close($dbmysqli);

?>
