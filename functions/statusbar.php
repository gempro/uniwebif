<?php 
session_start();
//
	include('../inc/dashboard_config.php');
	
	sleep(1);

	// ajax header
 	header('Content-Type: text/html;');
	header('Cache-Control: no-cache');
	
	if(!ini_get('allow_url_fopen'))
	{
	echo '[{"statusbar":"2"}]';
	$_SESSION["statusbar"] = '2';
	exit;
	}

	// get info
	$xmlfile = $url_format.'://'.$box_ip.'/web/getcurrent'.$session_part;
	$getstatus_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($getstatus_request);
	
	if($xml === FALSE)
	{
	echo '[{"statusbar":"3"}]';
	$_SESSION["statusbar"] = '3';
	exit;
	}

	if(!isset($xml->e2service->e2servicereference) or $xml->e2service->e2servicereference == ''){ $xml->e2service->e2servicereference = ''; }
	
	// if no data on channel
	if($xml->e2service->e2servicereference == '')
	{
	echo '[{"statusbar":"4"}]';
	$_SESSION["statusbar"] = '4';
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
	
	if(strlen($e2eventname) > '100'){ $e2eventname = substr($e2eventname, 0, 100); $e2eventname = $e2eventname . ' ... '; }
	if(strlen($e2eventservicename) > '50'){ $e2eventservicename = substr($e2eventservicename, 0, 50); $e2eventservicename = $e2eventservicename . ' ... '; }
	if($e2eventname == ''){ $e2eventname = ' No EPG available '; }
	
	if($e2videowidth == 'N/A' and $e2eventname == '')
	{
	echo '[{"statusbar":"5"}]';
	$_SESSION["statusbar"] = '5';
	exit;
	
	} else {
	
	$channel_name = str_replace(' ', '+', $e2eventservicename);
	
	if($e2videowidth == 'N/A'){ $e2videowidth = ''; }
	if($e2videoheight == 'N/A'){ $e2videoheight = ''; }
	
	$stream_url = $url_format.'://'.$box_ip.'/web/stream.m3u?ref='.$e2servicereference.'&name='.$channel_name;
	$media = '';
	
	// file
	if(preg_match('(^(?=.*[a-z]).+$)', $e2servicereference))
	{
	$media = 'file';
	$e2servicereference = str_replace('1:0:0:0:0:0:0:0:0:0:', '', $e2servicereference);
	$stream_url = $url_format.'://'.$box_ip.'/file?file='.$e2servicereference.'&name='.$channel_name;
	} 
	
	// iptv
	if(preg_match('/\b4097:0:1:0:0:0:0:0:0:0:\b/i', $e2servicereference))
	{
	$media = 'stream';
	$stream_url = str_replace('4097:0:1:0:0:0:0:0:0:0:', '', $e2servicereference);
	$stream_url = str_replace('%3a', ':', $stream_url);
	$stream_url = str_replace(':'.$e2eventservicename, '', $stream_url);
	}
	
	$_SESSION['statusbar'] = '1';
	
	echo '
	[{"statusbar":"1",
	"media":"'.$media.'",
	"stream_url":"'.$stream_url.'",
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
?>