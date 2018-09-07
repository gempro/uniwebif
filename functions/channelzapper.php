<?php 
//
include("../inc/dashboard_config.php");

// update timestamp
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `settings` WHERE `id` = '0' ");
	$result = mysqli_fetch_assoc($sql);
	
	$cz_wait_time = $result['cz_wait_time'];
	$cz_repeat = $result['cz_repeat'];
	$cz_timestamp = $result['cz_timestamp'];
	
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
	$cz_timestamp = $result['cz_timestamp'];
	}
	
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `cz_timestamp` = '".$cz_timestamp."' WHERE `id` = '0' ");

	// calculate work time
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(zap) FROM `channel_list` WHERE `zap` = "1" ');
	$result = mysqli_fetch_row($sql);
	$sum_zap_channels = $result[0];
	
	$cz_worktime = $sum_zap_channels * $cz_wait_time + 10;
	
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `cz_worktime` = '".$cz_worktime."' WHERE `id` = '0' ");

	$sql = "SELECT `e2servicereference` FROM `channel_list` where `zap` = '1' ORDER BY `e2servicename` ASC";
	
	// check power status
	$xmlfile = $url_format.'://'.$box_ip.'/web/powerstate';
	$power_command = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($power_command);
	if(!isset($xml->e2instandby) or $xml->e2instandby == ""){ $xml->e2instandby = ""; }
	$power_status = $xml->e2instandby;
	
	// turn on Receiver
	if(preg_match("/\btrue\b/i", $power_status)){
	$turn_on_request = $url_format.'://'.$box_ip.'/web/powerstate?newstate=0';
	$turn_on = @file_get_contents($turn_on_request, false, $webrequest);
	}	

	sleep(10);
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {	
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
	$sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `e2providername` = '".$e2providername."' WHERE `e2servicereference` = '".$obj->e2servicereference."' ");
	}
	}
    }
	}
	// zap to start channel
	$res = mysqli_query($dbmysqli, "SELECT `e2servicereference` FROM `channel_list` WHERE `zap_start` = '1' ");
	$result = mysqli_fetch_assoc($res);
	$start_channel = $result['e2servicereference'];
	
	$zap_request = $url_format.'://'.$box_ip.'/web/zap?sRef='.$start_channel.'';
	$zap_start_channel = @file_get_contents($zap_request, false, $webrequest);
	
	sleep(10);
	
	// turn off Receiver
	$turn_off_request = $url_format.'://'.$box_ip.'/web/powerstate?newstate=0';
	$turn_off = @file_get_contents($turn_off_request, false, $webrequest);

	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data:done";
?>