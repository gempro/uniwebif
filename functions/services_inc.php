<?php 
//

include("../inc/dashboard_config.php");

	//
	if(!isset($_REQUEST["action"]) or $_REQUEST["action"] == ""){ $_REQUEST["action"] = ""; }
	
	$action = $_REQUEST["action"];
	
	if ($action == 'crawl' ){
	
	$sql = mysqli_query($dbmysqli, "TRUNCATE all_services");
	
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/getservices?sRef=1:7:1:0:0:0:0:0:0:0:%20ORDER%20BY%20name';
	
	$get_services_request = file_get_contents($xmlfile, false, $webrequest);
	
	$xml = simplexml_load_string(preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $get_services_request));
	
if ($xml) {
	for ($i = 0; $i <= $i; $i++) {

	///////////////////////////////////////////////
	if(!isset($xml->e2service[$i]->e2servicereference) or $xml->e2service[$i]->e2servicereference == "") 
	{
	$xml->e2service[$i]->e2servicereference = "";
	
	} else {
	
	$xml->e2service[$i]->e2servicereference = $xml->e2service[$i]->e2servicereference;
	}
	
	// empty
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
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO all_services (e2servicename,servicename_enc,e2servicereference) values ('$e2servicename','$servicename_enc','$e2servicereference')"); }
	}
	}	
	// close db
	mysqli_close($dbmysqli);
	
	//
	} else {
	
	$sql = "SELECT * FROM all_services";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	{
	
	if(!isset($tv_services_list) or $tv_services_list == "") { $tv_services_list = ""; } else { $tv_services_list = $tv_services_list; }
	$tv_services_list = $tv_services_list. "
	<div class=\"col-md-4\">$obj->e2servicename
	</div>
	<div class=\"col-md-4\">
	$obj->e2servicereference
	</div>
	<div class=\"col-md-4\">
	<a href=\"$url_format://$box_user:$box_password@$box_ip/web/stream.m3u?ref=$obj->e2servicereference\" title=\"Stream $obj->e2servicename\"><i class=\"fa fa-desktop fa-1x\"></i></a>
	<input id=\"tv_services_zapp_btn_$obj->e2servicereference\" type=\"submit\" onClick=\"tv_services_zapp(this.id)\" title=\"Zapp to $obj->e2servicename\" value=\"ZAPP TO CHANNEL\" class=\"btn btn-xs btn-default\"/>
	<span id=\"tv_services_status_zapp_$obj->e2servicereference\"></span>
	</div>
	<div class=\"spacer_10\"></div>";
	}
	}
	if(!isset($tv_services_list) or $tv_services_list == "") { $tv_services_list = "Please copy services from Receiver into database.."; } else { $tv_services_list = $tv_services_list; }
	echo utf8_encode($tv_services_list);
	
	// Free result set
	mysqli_free_result($result);
	}	
}
?>