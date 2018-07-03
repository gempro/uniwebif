<?php 
//
include("../inc/dashboard_config.php");

	$timestamp = time();
	
	// time format 1
	$format_for_int_endtime = date("d.m.Y");
	$endtime = $format_for_int_endtime.'23:59:59';
	$end_timestamp = strtotime($endtime);
	
	// count remaining entries
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `epg_data` WHERE `e2eventend` BETWEEN "'.$timestamp.'" and "'.$end_timestamp.'" ');
	$result = mysqli_fetch_row($sql);
	$count_remaining = $result[0];
	//
	
	// calculate progressbar
	$broadcast_date = date("d.m.Y");
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) AS sum FROM `epg_data` WHERE `start_date` LIKE "%'.$broadcast_date.'%" ');
	$result = mysqli_fetch_row($sql);
	$sum = $result[0];
	//
				
	if($sum == '' or $sum == '0'){ $sum = '1'; }
	if($count_remaining == '' or $count_remaining == '0'){ $count_remaining = '0'; }
	
	$progressbar = round($count_remaining*100/$sum,1);
	
	if($progressbar < 7 ){ 
	echo 'Broadcast today: '.$count_remaining.' remaining..
	<div class="progress progress-striped">
	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: '.$progressbar.'%">
	</div><span style="color:#000">&nbsp;'.$progressbar.' %</span>
	</div>'; 
	
	} else {  
	
	echo 'Broadcast today: '.$count_remaining.' remaining..
	<div class="progress progress-striped">
	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: '.$progressbar.'%">
	<span style="color:#fff">'.$progressbar.' %</span>
	</div></div>';
	}
//
?>