<?php 
//
include("../inc/dashboard_config.php");
	
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == ''){ $_REQUEST['device'] = ''; }
	$device = $_REQUEST['device'];
	
	if(!isset($_REQUEST['location']) or $_REQUEST['location'] == ''){ $_REQUEST['location'] = ''; }
	$location = $_REQUEST['location'];
	
	if(!isset($_REQUEST['id']) or $_REQUEST['id'] == ''){ $_REQUEST['id'] = ''; }
	$id = $_REQUEST['id'];
	
	// record location dropdown
	$sql = "SELECT * FROM `record_locations` WHERE `device` = '".$device."' ORDER BY `id` ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){
	{
	if(!isset($device_dropdown) or $device_dropdown == ''){ $device_dropdown = ''; }

	$device_dropdown .= '<option value="'.$obj->id.'">'.$obj->e2location.'</option>';
	}
	}
	}
	
	if($location == 'timerlist')
	{
	$select_p1 = '
	<select id="timerlist_rec_location_device_'.$id.'">
	';
	$select_p2 = '</select>';
	
	if(!isset($device_dropdown) or $device_dropdown == ''){ $device_dropdown = ''; $select_p1 = ''; $select_p2 = ''; }
	
	echo $select_p1.$device_dropdown.$select_p2;

	} else {
	
	echo $device_dropdown;
	
	}
	
?>