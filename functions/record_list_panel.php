<?php 
//
include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == ""){ $_REQUEST['device'] = ""; }
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == ""){ $_REQUEST['record_location'] = ""; }
	
	$device = $_REQUEST['device'];
	$record_location = $_REQUEST['record_location'];
	
	// calculate filesize
	function formatBytes($size, $precision = 2)
	{
	$base = log($size, 1024);
	$suffixes = array('', 'kB', 'MB', 'GB', 'TB');
	return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}

	// recorded files default receiver
	if($device == "0"){
	$xmlfile = $url_format.'://'.$box_ip.'/web/movielist?dirname='.$record_location.'';
	$getRecords_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($getRecords_request);
	}
	
	if($device != "0"){
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result = mysqli_fetch_assoc($sql);
	$box_ip = $result['device_ip'];
	$box_user = $result['device_user'];
	$box_password = $result['device_password'];
	$url_format = $result['url_format'];
	// Webrequest
	$rl_webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$xmlfile = $url_format.'://'.$box_ip.'/web/movielist?dirname='.$record_location.'';
	$getRecords_request = @file_get_contents($xmlfile, false, $rl_webrequest);
	$xml = simplexml_load_string($getRecords_request);
	}
	
if($xml){
	
	$sum_today = 0;
	$files_summary = 0;
	$filespace = 0;
	
	for ($i = 0; $i <= 500; $i++){
	
	if(!isset($xml->e2movie[$i]->e2servicereference) or $xml->e2movie[$i]->e2servicereference == ""){ $xml->e2movie[$i]->e2servicereference = ""; }
	
	if($xml->e2movie[$i]->e2servicereference != "")
	{
	$e2filesize = $xml->e2movie[$i]->e2filesize;
	$filespace = (''.$filespace.'') + (''.$e2filesize.'');
	$record_filesize = formatBytes("".$e2filesize."");
	$files_summary = $i+1;
	}
	}
	}
	
	$filespace_total = formatBytes("".$filespace."");
	if($filespace == "0"){ $filespace_total = "0 kB"; }
	
	// panel
	echo '
	[{"files_summary":"'.$files_summary.'",
	"today_summary":"'.$sum_today.'",
	"discspace_used":"'.$filespace_total.'\r"}]';
?>
