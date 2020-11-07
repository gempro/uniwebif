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
	if(is_numeric($size))
	{
	$base = log($size, 1024);
	$suffixes = array('', 'kB', 'MB', 'GB', 'TB');
	return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
	}

	// recorded files default receiver
	if($device == "0")
	{
	$xmlfile = $url_format.'://'.$box_ip.'/web/movielist?dirname='.$record_location.$session_part_2;
	$getRecords_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($getRecords_request);
	
	// storage info
	$xmlfile = $url_format.'://'.$box_ip.'/web/deviceinfo'.$session_part;
	$storageInfo_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml2 = simplexml_load_string($storageInfo_request);
	for ($i = 0; $i <= 10; $i++)
	{
	if(!isset($xml2->e2hdds[$i]->e2hdd) or $xml2->e2hdds[$i]->e2hdd == ""){ $xml2->e2hdds[$i]->e2hdd = ""; }
	if($xml2->e2hdds->e2hdd[$i] != "")
	{
	if(!isset($storage_data) or $storage_data == ""){ $storage_data = ""; }
	$e2model = $xml2->e2hdds->e2hdd[$i]->e2model;
	$e2capacity = $xml2->e2hdds->e2hdd[$i]->e2capacity;
	$e2free = $xml2->e2hdds->e2hdd[$i]->e2free;
	$storage_data = $storage_data.'Label: '.$e2model.' | Size: '.$e2capacity.' | Free: '.$e2free.'<br>';
	}
	} // storage info
	}
	
	// recorded files different device
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
	'method' => 'POST',
	'header' => 'Authorization: Basic ' . base64_encode("$box_user:$box_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$xmlfile = $url_format.'://'.$box_ip.'/web/movielist?dirname='.$record_location.'';
	$getRecords_request = @file_get_contents($xmlfile, false, $rl_webrequest);
	$xml = simplexml_load_string($getRecords_request);
	
	// storage info
	$xmlfile = $url_format.'://'.$box_ip.'/web/deviceinfo';
	$storageInfo_request = @file_get_contents($xmlfile, false, $rl_webrequest);
	$xml2 = simplexml_load_string($storageInfo_request);
	for ($i = 0; $i <= 10; $i++)
	{
	if(!isset($xml2->e2hdds[$i]->e2hdd) or $xml2->e2hdds[$i]->e2hdd == ""){ $xml2->e2hdds[$i]->e2hdd = ""; }
	if($xml2->e2hdds->e2hdd[$i] != "")
	{
	if(!isset($storage_data) or $storage_data == ""){ $storage_data = ""; }
	$e2model = $xml2->e2hdds->e2hdd[$i]->e2model;
	$e2capacity = $xml2->e2hdds->e2hdd[$i]->e2capacity;
	$e2free = $xml2->e2hdds->e2hdd[$i]->e2free;
	$storage_data = $storage_data.'Label: '.$e2model.' | Size: '.$e2capacity.' | Free: '.$e2free.'<br>';
	}
	} // storage info
	}
	
if($xml){
	
	$sum_today = 0;
	$files_summary = 0;
	$filespace = 0;
	
	for ($i = 0; $i <= 500; $i++){
	if(!isset($xml->e2movie[$i]->e2servicereference) or $xml->e2movie[$i]->e2servicereference == ""){ $xml->e2movie[$i]->e2servicereference = ""; }
	if($xml->e2movie[$i]->e2servicereference != "")
	{
	$e2time = $xml->e2movie[$i]->e2time;
	$e2filesize = $xml->e2movie[$i]->e2filesize;
	$filespace = (''.$filespace.'') + (''.$e2filesize.'');
	$record_filesize = formatBytes("".$e2filesize."");
	$files_summary = $i+1;
	}
	}
	}
	
	if($time_format == '1')
	{
	// time format 1
	$record_date = date("d.m.Y - H:i |", "".$e2time."");
	$day_today = date("d.m.Y", time());
	$today_record = date("d.m.Y", "".$e2time."");
	if($day_today == $today_record){ $sum_today = $sum_today +1; }
	}
	
	if($time_format == '2')
	{
	// time format 2
	$record_date = date("n/d/Y - g:i A |", "".$e2time."");
	$day_today = date("n/d/Y", time());
	$today_record = date("n/d/Y", "".$e2time."");
	if($day_today == $today_record){ $sum_today = $sum_today +1; }
	}
	
	$filespace_total = formatBytes("".$filespace."");
	if($filespace == "0"){ $filespace_total = "0 kB"; }
	
	// panel
	echo '
	[{"storage_info":"'.$storage_data.'",
	"files_summary":"'.$files_summary.'",
	"today_summary":"'.$sum_today.'",
	"discspace_used":"'.$filespace_total.'\r"}]';
	
?>
