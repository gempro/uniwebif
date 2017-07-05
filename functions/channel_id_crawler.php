<?php 
//
include("../inc/dashboard_config.php");

$sql = mysqli_query($dbmysqli, "TRUNCATE `channel_list`");

if (mysqli_connect_errno())
	{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$sql = "SELECT * FROM bouquet_list WHERE crawl = 1";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {	
	{
	
	$sRef = $obj->e2servicereference;

	$xmlfile = 'http://'.$box_ip.'/web/getservices?sRef='.$sRef.'';
	
	$channel_ID_request = file_get_contents($xmlfile, false, $webrequest);
	
	$xml = simplexml_load_string($channel_ID_request);
	
	if ($xml) {
	
	for ($i = 0; $i <= $channel_entries; $i++) {

	///////////////////////////////////////////////
	if(!isset($xml->e2service[$i]->e2servicename) or $xml->e2service[$i]->e2servicename == "") 
	{ 
	$xml->e2service[$i]->e2servicename = "";
	
	} else {
	
	$xml->e2service[$i]->e2servicename = $xml->e2service[$i]->e2servicename; }
	
	// if no channel description is available
	if($xml->e2service[$i]->e2servicename == "" ) {
	
	} else {
	
	// define searchline
	$e2servicename = utf8_decode($xml->e2service[$i]->e2servicename);
	$e2servicereference = $xml->e2service[$i]->e2servicereference;
	$servicename_enc = rawurlencode($xml->e2service[$i]->e2servicename);
	
	// remove special chars
	$e2servicename = str_replace("Š", " ", $e2servicename);
	
	// channel hash
	$channel_hash = hash('md4',$e2servicename);
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO channel_list (e2servicename,servicename_enc,e2servicereference,channel_hash) values ('$e2servicename','$servicename_enc','$e2servicereference','$channel_hash')");
	}}}
	}}}
	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');	
	
	if ($e2servicereference == '' )
	{ 
	echo "data: error\n\n"; 
	
	} else { 
	
	echo "data: Channel ID crawling - done!\n\n";
	}
// delete channel duplicates
//$sql = mysqli_query($dbmysqli, "DELETE FROM channel_list USING channel_list,
//channel_list AS Dup WHERE NOT channel_list.id = Dup.id AND channel_list.id > Dup.id AND channel_list.e2servicereference = Dup.e2servicereference");
	
	// delete broken channels
	$sql = mysqli_query($dbmysqli, "DELETE FROM channel_list WHERE e2servicename = '<n/a>' OR e2servicename = '.' ");
?>