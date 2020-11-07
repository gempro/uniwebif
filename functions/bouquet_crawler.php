<?php 
//
include("../inc/dashboard_config.php");

	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	mysqli_query($dbmysqli, "TRUNCATE `bouquet_list`");
		
	$xmlfile = $url_format.'://'.$box_ip.'/web/getservices'.$session_part;
	$get_bouquet_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string(preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $get_bouquet_request));
	
	if($xml)
	{
	for ($i = 0; $i <= $i; $i++){

	if(!isset($xml->e2service[$i]->e2servicereference) or $xml->e2service[$i]->e2servicereference == ""){ $xml->e2service[$i]->e2servicereference = ""; }
	
	// empty
	if($xml->e2service[$i]->e2servicereference == "" )
	{
	echo "data: ok\n\n";
	exit;
	
	} else {
	
	// define searchline
	$e2servicereference = $xml->e2service[$i]->e2servicereference;
	$e2servicename = utf8_decode($xml->e2service[$i]->e2servicename);
	
	// remove special chars
	$e2servicereference = str_replace(" ", "%20", $e2servicereference); //important
	$e2servicereference = str_replace("\"", "%22", $e2servicereference); //important
	
	$e2servicename = str_replace("Š", " ", $e2servicename);
	
	mysqli_query($dbmysqli, "INSERT INTO `bouquet_list` (e2servicereference, e2servicename) VALUES ('$e2servicereference', '$e2servicename')"); 
	
	}
	}
	}
	
	// answer for ajax
	echo "data:ok";
?>