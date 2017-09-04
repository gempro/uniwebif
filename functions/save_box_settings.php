<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	//recieve data	
	$set_box_ip = utf8_decode($_REQUEST['box_ip']);
	$set_box_user = utf8_decode($_REQUEST['box_user']);
	$set_box_password = utf8_decode($_REQUEST['box_password']);
	
	// Webrequest
	$check_webrequest = stream_context_create(array (
		'http' => array (
			'header' => 'Authorization: Basic ' . base64_encode("$set_box_user:$set_box_password"),
			'ssl' =>array (
			'verify_peer' => false,
			'verify_peer_name' => false,
			)
		)
	));

	// check xml
	$xmlfile = ''.$url_format.'://'.$set_box_ip.'/web/about';
	
	$check_conn = file_get_contents($xmlfile, false, $check_webrequest);
	
	$xml = simplexml_load_string($check_conn);
	
	if(!isset($xml->e2about->e2enigmaversion) or $xml->e2about->e2enigmaversion == "")
	{ 
	$xml->e2about->e2enigmaversion = "";
	
	} else { 
	
	$xml->e2about->e2enigmaversion = $xml->e2about->e2enigmaversion;
	}
	
	if($xml->e2about->e2enigmaversion == "" ) { $conn = 'connection error'; } else { $conn = 'connection ok'; }
	
	//
	$boxinfo = $xml->e2about->e2enigmaversion;
	$e2enigmaversion = $xml->e2about->e2enigmaversion;
	$e2imageversion = $xml->e2about->e2imageversion;
	$e2webifversion = $xml->e2about->e2webifversion;
	$e2model = $xml->e2about->e2model;
	//
	
	if(!isset($set_box_ip) or $set_box_ip == "" or !isset($set_box_user) or $set_box_user == "" or !isset($set_box_password) or $set_box_password == "") 
	{ 
	echo "data: data missed\n\n"; 
	
	} else { 
	
	$set_box_ip = $set_box_ip; $set_box_user = $set_box_user; $set_box_password = $set_box_password;
	
	$sql = mysqli_query($dbmysqli, "UPDATE settings SET box_ip = '$set_box_ip', box_user = '$set_box_user', box_password = '$set_box_password' WHERE id = 0");
	
	$sql = mysqli_query($dbmysqli, "TRUNCATE `box_info`");
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO box_info (e2enigmaversion, e2imageversion, e2webifversion, e2model) values ('$e2enigmaversion','$e2imageversion','$e2webifversion','$e2model')");
	
	// close db
	mysqli_close($dbmysqli);
	
	// answer for ajax
	echo "data: settings ok $conn\n\n";

	}
?>