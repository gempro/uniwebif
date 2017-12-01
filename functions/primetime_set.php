<? 
//
include("../inc/dashboard_config.php");
	
	$timestamp = time();
	$hour = $_REQUEST['hour'];
	$minute = $_REQUEST['minute'];
	$ampm = $_REQUEST['ampm'];
	
	if ($ampm !== '0'){
	if ($ampm == 'PM'){  
	if ($hour == '12'){ $hour = $hour - 12; }
	}
	if ($ampm == 'AM'){
	if ($hour == '12'){ $hour = $hour + 12; }
	}
	}
	
	$date_start = date("d.m.Y, ".$hour.":".$minute."",$timestamp);
	$time_start = strtotime($date_start);
	
	if ($ampm !== '0'){
	//
	if ($ampm == 'AM'){ $time_start - 43200; }
	if ($ampm == 'PM'){ $time_start = $time_start + 43200; }
	}
	
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` set primetime = '$time_start' WHERE `id` = '0'");
	
	echo '<i class=\'glyphicon glyphicon-ok fa-1x green\'></i>';
	
	sleep(1);
?>