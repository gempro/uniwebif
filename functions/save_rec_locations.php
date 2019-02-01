<?php 
//
include("../inc/dashboard_config.php");
	
	// ajax header
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == "") { $_REQUEST['device'] = ""; }
	$device = $_REQUEST['device'];
	
	if($device == "0" or $device == ""){
	$sql = mysqli_query($dbmysqli, "TRUNCATE `record_locations`");
	
	// get locations
	$xmlfile = $url_format.'://'.$box_ip.'/web/getlocations';
	$getlocations_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($getlocations_request);
	
	if($xml){
    for ($i = 0; $i <= $i; $i++){
	
	if(!isset($xml->e2location[$i]) or $xml->e2location[$i] == ""){ $xml->e2location[$i] = ""; }
	
	if($xml->e2location[$i] != ""){
	$e2locations = utf8_decode($xml->e2location[$i]);
	$sql = mysqli_query($dbmysqli, "INSERT INTO `record_locations` (`e2location`) VALUES ('$e2locations')");
	}
	
	if($xml->e2location[$i] == ""){ echo "data:done"; exit; }
	}
	}
	} // default receiver

	else {
	
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result = mysqli_fetch_assoc($sql);
	$device_ip = $result['device_ip'];
	$device_user = $result['device_user'];
	$device_password = $result['device_password'];
	$url_format = "http";
	$rl_webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$device_user:$device_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$xmlfile = $url_format.'://'.$device_ip.'/web/getlocations';
	$getlocations_request = @file_get_contents($xmlfile, false, $rl_webrequest);
	$xml = simplexml_load_string($getlocations_request);
	
	if($xml == ""){ echo "data:connection_error"; exit; }
	if($xml){
    for ($i = 0; $i <= $i; $i++){
	
	if(!isset($xml->e2location[$i]) or $xml->e2location[$i] == ""){ $xml->e2location[$i] = ""; }
	
	if($xml->e2location[$i] != ""){
	$e2location = utf8_decode($xml->e2location[$i]);
	$sql = mysqli_query($dbmysqli, "UPDATE `device_list` SET `rec_location".$i."` = ('$e2location') WHERE `device_ip` = '".$device_ip."' ");
	}
	if($xml->e2location[$i] == ""){ echo "data:done"; exit; }
	}
	}
	//
	echo "data:done";
	exit;
	}
?>