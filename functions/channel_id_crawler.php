<?php 
//
	include("../inc/dashboard_config.php");

	sleep(1);

	mysqli_query($dbmysqli, "TRUNCATE `channel_list` ");

	$sql = "SELECT * FROM `bouquet_list` WHERE `crawl` = '1' ";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){	
	{
	
	$xmlfile = $url_format.'://'.$box_ip.'/web/getservices?sRef='.$obj->e2servicereference.$session_part_2;
	$channel_ID_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($channel_ID_request);
	
	if($xml){
	
	for ($i = 0; $i <= $channel_entries; $i++){

	if(!isset($xml->e2service[$i]->e2servicename) or $xml->e2service[$i]->e2servicename == ''){ $xml->e2service[$i]->e2servicename = ''; }
	
	if($xml->e2service[$i]->e2servicename != '')
	{

	$e2servicename = utf8_decode($xml->e2service[$i]->e2servicename);
	$e2servicereference = $xml->e2service[$i]->e2servicereference;
	$servicename_enc = rawurlencode($xml->e2service[$i]->e2servicename);
	$provider = '-';
	$crawl = '1';
	
	if(preg_match('/\b4097:0:1:0:0:0:0:0:0:0:\b/i', $e2servicereference))
	{
	$service_reference = str_replace('4097:0:1:0:0:0:0:0:0:0:', '', $e2servicereference);
	$p = '4097:0:1:0:0:0:0:0:0:0:';
	$service_reference = str_replace('%3a', '%253a', $service_reference);
	//$service_reference = str_replace(" ", "+", $service_reference);
	//$service_reference = str_replace(":", "%3a", $service_reference);
	$service_reference = str_replace(':', '', $service_reference);
	$service_reference = str_replace('/', '%2F', $service_reference);
	$service_reference = str_replace($e2servicename, '', $service_reference);
	$e2servicereference = $p.$service_reference;
	$crawl = '0';
	$provider = 'IPTV;';
	}
	
	$channel_hash = hash('md4',$e2servicename);
	
	mysqli_query($dbmysqli, "INSERT INTO `channel_list` 
	(
	e2servicename, 
	servicename_enc, 
	e2servicereference, 
	e2providername, 
	crawl, 
	channel_hash 
	) VALUES (
	'".$e2servicename."', 
	'".$servicename_enc."', 
	'".$e2servicereference."', 
	'".$provider."', 
	'".$crawl."', 
	'".$channel_hash."'
	)"
	);
	}
	}
	}
	
	}
	}
	}
	
	if(!isset($e2servicereference) or $e2servicereference == ''){ $e2servicereference = ''; }
	
	if($e2servicereference == ''){ echo "data:error"; } else { echo "data:done"; }
	
	// delete channel duplicates
	//$sql = mysqli_query($dbmysqli, "DELETE FROM channel_list USING channel_list,
	//channel_list AS Dup WHERE NOT channel_list.id = Dup.id AND channel_list.id > Dup.id AND channel_list.e2servicereference = Dup.e2servicereference");
	
	// delete broken channels
	mysqli_query($dbmysqli, "DELETE FROM `channel_list` WHERE `e2servicename` = '<n/a>' OR `e2servicename` = '.' ");
	
?>