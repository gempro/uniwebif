<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(!isset($_REQUEST['box_ip']) or $_REQUEST['box_ip'] == ""){ $_REQUEST['box_ip'] = ""; }
	if(!isset($_REQUEST['box_user']) or $_REQUEST['box_user'] == ""){ $_REQUEST['box_user'] = ""; }
	if(!isset($_REQUEST['box_password']) or $_REQUEST['box_password'] == ""){ $_REQUEST['box_password'] = ""; }	
	
	$set_box_ip = utf8_decode($_REQUEST['box_ip']);
	$set_box_user = utf8_decode($_REQUEST['box_user']);
	$set_box_password = utf8_decode($_REQUEST['box_password']);
	
	// request from setup
	if(!isset($_REQUEST['box_ip']) or $_REQUEST['box_ip'] == ""){ $set_box_ip = $box_ip; }
	if(!isset($_REQUEST['box_user']) or $_REQUEST['box_user'] == ""){ $set_box_user = $box_user; }
	if(!isset($_REQUEST['box_password']) or $_REQUEST['box_password'] == ""){ $set_box_password = $box_password; }

	// check xml
	$xmlfile = $url_format.'://'.$set_box_ip.'/web/about'.$session_part;
	$check_conn = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($check_conn);
	
	if(!isset($xml->e2about->e2enigmaversion) or $xml->e2about->e2enigmaversion == ""){ $xml->e2about->e2enigmaversion = ""; }
	
	if($xml->e2about->e2enigmaversion == "" ) { $conn = 'connection error'; } else { $conn = 'connection ok'; }
	
	$boxinfo = $xml->e2about->e2enigmaversion;
	$e2enigmaversion = $xml->e2about->e2enigmaversion;
	$e2imageversion = $xml->e2about->e2imageversion;
	$e2webifversion = $xml->e2about->e2webifversion;
	$e2model = $xml->e2about->e2model;
	
	if(!isset($set_box_ip) or $set_box_ip == "" or !isset($set_box_user) or $set_box_user == "" or !isset($set_box_password) or $set_box_password == "") 
	{ 
	echo "data: data missed"; 
	
	} else {
	
	mysqli_query($dbmysqli, "UPDATE `settings` SET `box_ip` = '$set_box_ip', `box_user` = '$set_box_user', `box_password` = '$set_box_password' WHERE `id` = '0' ");
	
	mysqli_query($dbmysqli, "TRUNCATE `box_info`");
	
	mysqli_query($dbmysqli, "INSERT INTO `box_info` (`e2enigmaversion`, `e2imageversion`, `e2webifversion`, `e2model`) VALUES ('$e2enigmaversion', '$e2imageversion', '$e2webifversion', '$e2model')");
	
	// answer for ajax
	echo "data: settings ok $conn";
	}
?>