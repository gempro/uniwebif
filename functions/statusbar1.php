<?php 
//
include("../inc/dashboard_config.php");

if(!isset($box_ip) or $box_ip == "" or !isset($box_user) or $box_user == "" or !isset($box_password) or $box_password == "") { exit; }

// get info
$xmlfile = ''.$url_format.'://'.$box_ip.'/web/getcurrent';

$getstatus_request = file_get_contents($xmlfile, false, $webrequest);

$xml = simplexml_load_string($getstatus_request);

if ($xml) {

	if(!isset($xml->e2service->e2servicereference) or $xml->e2service->e2servicereference == "")
	{ 
	$xml->e2service->e2servicereference = ""; } else { $xml->e2service->e2servicereference = $xml->e2service->e2servicereference; 
	}
	
	// if no data
	if($xml->e2service->e2servicereference == "") {

	$statusbar = '';
	
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
	
	if(strlen($e2eventname) > "70" ) {
	$e2eventname = substr($e2eventname, 0, 70);
	$e2eventname = $e2eventname . '...';
	} else {
	$e2eventname = $e2eventname;
	}
	
	if ($e2eventname == ""){
	$e2eventname = ' No EPG available ';
	}
	
	if ($xml->e2service->e2videowidth == "N/A"){
	$statusbar = '';
	
	} else {
	
	$statusbar = '<div id="statusbar1">
	<div id="row1">
	<a href="'.$url_format.'://'.$box_user.':'.$box_password.'@'.$box_ip.'/web/stream.m3u?ref='.$e2servicereference.'" title="Stream">
	<i class="fa fa-desktop fa-1x"></i></a> '.$e2eventname.' | +'.$time_remaining.' of '.$time_complete.' min | <strong>'.$e2eventservicename.'</strong>
	</div>
	<div id="row2">'.$e2videowidth.'p x '.$e2videoheight.'p</div>
	<div style="clear:both"></div>
	</div>';
	
	}
	}
	
	if(!isset($statusbar) or $statusbar == "") { $statusbar = ""; } else { $statusbar = $statusbar; }
	
	echo $statusbar;
}
?>