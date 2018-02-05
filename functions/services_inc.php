<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST["action"]) or $_REQUEST["action"] == ""){ $_REQUEST["action"] = ""; }
	
	$action = $_REQUEST["action"];
	
	sleep(1);
	
if ($action == 'add'){

	$id = $_REQUEST["id"];
	
	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	$sql = mysqli_query($dbmysqli, "SELECT e2servicename, servicename_enc, e2servicereference FROM `all_services` WHERE `e2servicereference` = '$id' ");
	$result = mysqli_fetch_assoc($sql);
	
	$e2servicename = $result['e2servicename'];
	$servicename_enc = $result['servicename_enc'];
	$e2servicereference = $result['e2servicereference'];
	//
	
	$sql2 = mysqli_query($dbmysqli, "SELECT e2servicename, servicename_enc, e2servicereference FROM `channel_list` WHERE `e2servicename` = '$e2servicename' AND `servicename_enc` = '$servicename_enc' AND `e2servicereference` = '$e2servicereference' ");
	$result2 = mysqli_fetch_assoc($sql2);

if (mysqli_num_rows($sql2) < 1) {
	
	$channel_hash = hash('md4',$e2servicename);

	$sql = mysqli_query($dbmysqli, "INSERT INTO `channel_list` (e2servicename, servicename_enc, e2servicereference, e2providername, channel_hash) values ('$e2servicename', '$servicename_enc', '$e2servicereference', '-', '$channel_hash')");
	
	echo "data: <i class='glyphicon glyphicon-ok green'></i>\n\n"; 
	
	} else { 
	
	echo "data: <i class='glyphicon glyphicon-ok gray'></i>\n\n"; 
	
	}
	// close db
	mysqli_close($dbmysqli);
	
	exit; 
}
	
if ($action == 'crawl' ){
	
	$sql = mysqli_query($dbmysqli, "TRUNCATE `all_services`");
	
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/getservices?sRef=1:7:1:0:0:0:0:0:0:0:%20ORDER%20BY%20name';
	
	$get_services_request = file_get_contents($xmlfile, false, $webrequest);
	
	$xml = simplexml_load_string(preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $get_services_request));
	
if ($xml) {
	for ($i = 0; $i <= $i; $i++) {

	if(!isset($xml->e2service[$i]->e2servicereference) or $xml->e2service[$i]->e2servicereference == ""){ $xml->e2service[$i]->e2servicereference = "";	}

	if($xml->e2service[$i]->e2servicereference == "" ) {
	
	// answer for ajax
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data: done!\n\n";
	exit;
	
	} else {
	
	// define searchline
	$e2servicereference = $xml->e2service[$i]->e2servicereference;
	$e2servicename = utf8_decode($xml->e2service[$i]->e2servicename);
	$servicename_enc = rawurlencode($xml->e2service[$i]->e2servicename);
	
	// remove special chars
	$e2servicereference = str_replace(" ", "%20", $e2servicereference); //important
	$e2servicereference = str_replace("\"", "%22", $e2servicereference); //important
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO `all_services` (e2servicename, servicename_enc, e2servicereference) values ('$e2servicename', '$servicename_enc', '$e2servicereference')"); }
	}
	}	
	// close db
	mysqli_close($dbmysqli);
	
	//
	} else {
	
	// count all epg entries
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) AS summary_services FROM `all_services`');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	
	$stmt->execute();
	$stmt->bind_result($summary_services);
	$stmt->fetch();
	$stmt->close();
	
	$sql = "SELECT * FROM `all_services`";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	{
	
	if(!isset($all_services_list) or $all_services_list == "") { $all_services_list = ""; }
	
	$services_total = '<div class="row">
	<div class="col-md-2">Total: '.$summary_services.'</div>
	<div class="col-md-10"></div>
	<div class="spacer_10"></div>
	</div>';
	
	$all_services_list = $all_services_list. "
	<div class=\"row\">
	<div class=\"col-md-2\">
	<input id=\"all_services_add_btn_$obj->id\" name=\"$obj->e2servicereference\" type=\"submit\" onClick=\"all_services_add(this.id,this.name)\" title=\"Add $obj->e2servicename to Channel list\" value=\"Add\" class=\"btn btn-xs btn-default\"/>
	<input id=\"all_services_zapp_btn_$obj->id\" name=\"$obj->e2servicereference\" type=\"submit\" onClick=\"all_services_zapp(this.id,this.name)\" title=\"Zap to $obj->e2servicename\" value=\"Zap\" class=\"btn btn-xs btn-default\"/>
	<a href=\"$url_format://$box_user:$box_password@$box_ip/web/stream.m3u?ref=$obj->e2servicereference\" title=\"Stream $obj->e2servicename\"><i class=\"fa fa-desktop fa-1x\"></i></a>
	<span id=\"all_services_status_zapp_$obj->id\"></span>
	<span id=\"all_services_status_add_$obj->id\"></span>
	</div>
	<div class=\"col-md-6\">$obj->e2servicename</div>
	<div class=\"col-md-4\">$obj->e2servicereference</div>
	<div class=\"spacer_10\"></div>
	</div>";
	}
	}
	if(!isset($all_services_list) or $all_services_list == "") { $all_services_list = "No service list in database. Click Button to copy it from Receiver.."; }
	
	if(!isset($services_total) or $services_total == "") { $services_total = ""; }
	
	echo utf8_encode($services_total.$all_services_list);
	
	// Free result set
	mysqli_free_result($result);
	}	
}
?>