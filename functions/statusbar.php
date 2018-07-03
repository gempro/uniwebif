<?php 
session_start();
//
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/html;');
	header('Cache-Control: no-cache');
	
	//
	if(!ini_get('allow_url_fopen')){
	echo '[{"statusbar":"0"}]';
	$_SESSION["statusbar"] = "0";
	exit;
	}

	// get info
	$xmlfile = $url_format.'://'.$box_ip.'/web/getcurrent';
	$getstatus_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($getstatus_request);
	
	if($xml === FALSE){
	echo '[{"statusbar":"0"}]';
	$_SESSION["statusbar"] = "0";
	exit;
	}
	
	//$json = array();

if ($xml){

	if(!isset($xml->e2service->e2servicereference) or $xml->e2service->e2servicereference == ""){ $xml->e2service->e2servicereference = ""; }
	
	// if no data on channel
	if($xml->e2service->e2servicereference == ""){
	echo '[{"statusbar":"0"}]';
	$_SESSION["statusbar"] = "0";
	exit;
	
	} else {
	
	$e2servicereference = $xml->e2service->e2servicereference;
	$e2eventname = $xml->e2eventlist->e2event->e2eventname;
	$e2eventservicename = $xml->e2eventlist->e2event->e2eventservicename;
	$e2videowidth = $xml->e2service->e2videowidth;
	$e2videoheight = $xml->e2service->e2videoheight;
	$e2eventstart = $xml->e2eventlist->e2event->e2eventstart;
	$e2eventduration = $xml->e2eventlist->e2event->e2eventduration;
	$e2eventremaining = $xml->e2eventlist->e2event->e2eventremaining;
	$e2eventdescriptionextended = $xml->e2eventlist->e2event->e2eventdescriptionextended;
	$e2eventend = $e2eventstart + $e2eventduration;
	
	$time_complete = round($e2eventduration/60,0);
	$time_remaining = round($e2eventremaining/60,0);
	
	if(strlen($e2eventname) > "70" ){
	$e2eventname = substr($e2eventname, 0, 70);
	$e2eventname = $e2eventname . '...';
	}
	
	if ($e2eventname == ""){
	$e2eventname = ' No EPG available ';
	}
	
	if ($xml->e2service->e2videowidth == "N/A"){
	echo '[{"statusbar":"0\r"}]';
	$_SESSION["statusbar"] = "0";
	exit;
	
	} else {
	
	$channel_name = str_replace(" ", "+", $e2eventservicename);
	//$stream_url = "$url_format://$box_user:$box_password@$box_ip/web/stream.m3u?ref=$e2servicereference&name=$channel_name";
	$stream_url = "$url_format://$box_ip/web/stream.m3u?ref=$e2servicereference&name=$channel_name";
	
	$_SESSION["statusbar"] = "1";
	
	echo '
	[{"statusbar":"1",
	"stream_url":"'.rawurlencode($stream_url).'",
	"e2servicereference":"'.$e2servicereference.'",
	"e2eventname":"'.rawurlencode($e2eventname).'",
	"e2eventservicename":"'.rawurlencode($e2eventservicename).'",
	"e2videowidth":"'.$e2videowidth.'",
	"e2videoheight":"'.$e2videoheight.'",
	"e2eventstart":"'.$e2eventstart.'",
	"e2eventduration":"'.$e2eventduration.'",
	"e2eventremaining":"'.$e2eventremaining.'",
	"e2eventdescriptionextended":"'.rawurlencode($e2eventdescriptionextended).'",
	"e2eventend":"'.$e2eventend.'",
	"time_complete":"'.$time_complete.'",
	"time_remaining":"'.$time_remaining.'\r"}]';
	}
	}
}
?>