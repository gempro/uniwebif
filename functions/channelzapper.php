<?php 
//
	include("../inc/dashboard_config.php");

	// update timestamp
	$sql_0 = mysqli_query($dbmysqli, "SELECT * FROM `settings` WHERE `id` = '0' ");
	$result_0 = mysqli_fetch_assoc($sql_0);
	
	$cz_wait_time = $result_0['cz_wait_time'];
	$cz_repeat = $result_0['cz_repeat'];
	$cz_timestamp = $result_0['cz_timestamp'];
	$cz_device = $result_0['cz_device'];
	
	if($cz_repeat == 'daily')
	{
	$cz_timestamp = $cz_timestamp + 86400;
	}
	
	if($cz_repeat == 'daily_3')
	{
	$cz_timestamp = $cz_timestamp + 86400*3;
	}
	
	if($cz_repeat == 'daily_5')
	{
	$cz_timestamp = $cz_timestamp + 86400*5;
	}
	
	if($cz_repeat == 'daily_7')
	{
	$cz_timestamp = $cz_timestamp + 86400*7;
	}
	
	// dont increase timestamp on manual start
	if(!isset($_REQUEST['manual']) or $_REQUEST['manual'] == "") { $_REQUEST['manual'] = ""; }
	
	$manual = $_REQUEST['manual'];
	
	if($manual == 'yes')
	{
	$cz_timestamp = $result_0['cz_timestamp'];
	}
	
	mysqli_query($dbmysqli, "UPDATE `settings` SET `cz_timestamp` = '".$cz_timestamp."' WHERE `id` = '0' ");

	// calculate work time
	$sql_1 = mysqli_query($dbmysqli, 'SELECT COUNT(zap) FROM `channel_list` WHERE `zap` = "1" ');
	$result_1 = mysqli_fetch_row($sql_1);
	$sum_zap_channels = $result_1[0];
	$cz_worktime = $sum_zap_channels * $cz_wait_time + 10;
	
	mysqli_query($dbmysqli, "UPDATE `settings` SET `cz_worktime` = '".$cz_worktime."' WHERE `id` = '0' ");
	
	// zap device
	if($cz_device != '0' and $manual != 'yes')
	{
	$sql_2 = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$cz_device."' ");
	$result_2 = mysqli_fetch_assoc($sql_2);
	$box_ip = $result_2['device_ip'];
	$box_user = $result_2['device_user'];
	$box_password = $result_2['device_password'];
	$url_format = $result_2['url_format'];
	// Webrequest
	$webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	}
	
	// check power status
	$xmlfile = $url_format.'://'.$box_ip.'/web/powerstate';
	$power_command = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($power_command);
	if(!isset($xml->e2instandby) or $xml->e2instandby == ""){ $xml->e2instandby = ""; }
	$power_status = $xml->e2instandby;
	
	// turn on Receiver
	if(preg_match("/\btrue\b/i", $power_status))
	{
	$turn_on_request = $url_format.'://'.$box_ip.'/web/powerstate?newstate=0';
	$turn_on = @file_get_contents($turn_on_request, false, $webrequest);
	}	

	sleep(10);
	
	$sql_3 = "SELECT `e2servicereference` FROM `channel_list` where `zap` = '1' ORDER BY `e2servicename` ASC";
	
	if ($result_3 = mysqli_query($dbmysqli,$sql_3))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result_3)) {	
	{
	$e2servicereference = $obj->e2servicereference;
	
	$zap_request = $url_format.'://'.$box_ip.'/web/zap?sRef='.$e2servicereference.'';
	$zap_channel = @file_get_contents($zap_request, false, $webrequest);
	
	sleep($cz_wait_time);
	
	//save provider from channel in db
	$xmlfile = $url_format.'://'.$box_ip.'/web/getcurrent';
	$request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($request);
	
	if($xml->e2service->e2providername != "") 
	{	
	$e2providername = $xml->e2service->e2providername;
	mysqli_query($dbmysqli, "UPDATE `channel_list` SET `e2providername` = '".$e2providername."' WHERE `e2servicereference` = '".$obj->e2servicereference."' ");
	}
	}
    }
	}
	// zap to start channel
	$sql_4 = mysqli_query($dbmysqli, "SELECT `e2servicereference` FROM `channel_list` WHERE `zap_start` = '1' ");
	$result_4 = mysqli_fetch_assoc($sql_4);
	$start_channel = $result_4['e2servicereference'];
	
	$zap_request = $url_format.'://'.$box_ip.'/web/zap?sRef='.$start_channel.'';
	$zap_start_channel = @file_get_contents($zap_request, false, $webrequest);
	
	sleep(10);
	
	// turn off Receiver
	$turn_off_request = $url_format.'://'.$box_ip.'/web/powerstate?newstate=0';
	$turn_off = @file_get_contents($turn_off_request, false, $webrequest);

	// answer for ajax
	echo "data:done";
	
?>