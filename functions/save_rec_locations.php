<?php 
//
include("../inc/dashboard_config.php");
	
	// ajax header
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == ''){ $_REQUEST['device'] = ''; }
	$device = $_REQUEST['device'];
	
	if($device == '0' or $device == '')
	{
	// record locations
	$xmlfile = $url_format.'://'.$box_ip.'/web/getlocations'.$session_part;
	$getlocations_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($getlocations_request);
	
	if($xml)
	{
	mysqli_query($dbmysqli, "DELETE FROM `record_locations` WHERE `device` = '".$device."' ");
	
    for ($i = 0; $i <= $i; $i++){
	
	if(!isset($xml->e2location[$i]) or $xml->e2location[$i] == ''){ $xml->e2location[$i] = ''; }
	
	if($xml->e2location[$i] != '')
	{
	$e2locations = utf8_decode($xml->e2location[$i]);
	mysqli_query($dbmysqli, "INSERT INTO `record_locations` (`e2location`) VALUES ('".$e2locations."') ");
	}
	
	if($xml->e2location[$i] == ''){ echo 'data:done'; exit; }
	}
	}
	} // default receiver

	// different device
	if($device != '0') 
	{
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result = mysqli_fetch_assoc($sql);
	$device_ip = $result['device_ip'];
	$device_user = $result['device_user'];
	$device_password = $result['device_password'];
	$url_format = $result['url_format'];
	
	$rl_webrequest = stream_context_create(array (
	'http' => array (
	'method' => 'POST',
	'header' => 'Authorization: Basic ' . base64_encode("$device_user:$device_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	
	// check if token session is required
	$xmlfile = $url_format.'://'.$box_ip.'/web/session?sessionid=0';
	$session_info = @file_get_contents($xmlfile, false, $rl_webrequest);
	$e2sessionid = simplexml_load_string($session_info);
	
	if($e2sessionid != ''){ $session_part = '?sessionid='.$e2sessionid; } else { $session_part = ''; }
	
	$xmlfile = $url_format.'://'.$device_ip.'/web/getlocations'.$session_part;
	$getlocations_request = @file_get_contents($xmlfile, false, $rl_webrequest);
	$xml = simplexml_load_string($getlocations_request);
	
	if($xml == ''){ echo 'data:connection_error'; exit; }
	
	if($xml)
	{
	mysqli_query($dbmysqli, "DELETE FROM `record_locations` WHERE `device` = '".$device."' ");

	$sql_1 = mysqli_query($dbmysqli, "SELECT device_description FROM `device_list` WHERE `device` LIKE '".$device."' ");
	$result_1 = mysqli_fetch_assoc($sql_1);
	$device_description = $result['device_description'];
	
    for ($i = 0; $i <= $i; $i++){
	
	if(!isset($xml->e2location[$i]) or $xml->e2location[$i] == ''){ $xml->e2location[$i] = ''; }
	
	if($xml->e2location[$i] != '')
	{
	$e2location = utf8_decode($xml->e2location[$i]);
	
	mysqli_query($dbmysqli, "UPDATE `device_list` SET `rec_location".$i."` = '".$e2location."' WHERE `device_ip` = '".$device_ip."' ");
	
	mysqli_query($dbmysqli, "INSERT INTO `record_locations` 
	(
	`e2location`, 
	`device`, 
	`description` 
	) VALUES ( 
	'".$e2location."', 
	'".$device."', 
	'".$device_description."' 
	)
	");	
	}
	
	if($xml->e2location[$i] == ''){ echo 'data:done'; exit; }
	}
	}

	echo 'data:done';
}

?>