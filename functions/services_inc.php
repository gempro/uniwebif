<!DOCTYPE html>
<html>
<head>
<script>
//
$(document).ready(function(){
    $("#services_row*").hover(function(){
        $(this).css("background-color", "#FAFAFA");
        }, function(){
        $(this).css("background-color", "white");
    });
});
</script>
</head>
<body>
<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST["action"]) or $_REQUEST["action"] == ""){ $_REQUEST["action"] = ""; }
	$action = $_REQUEST["action"];
	
	if(!isset($_REQUEST["service"]) or $_REQUEST["service"] == ""){ $_REQUEST["service"] = ""; }
	$service = $_REQUEST["service"];
	
	if(!isset($_REQUEST["searchterm"]) or $_REQUEST["searchterm"] == ""){ $_REQUEST["searchterm"] = ""; }
	$searchterm = $_REQUEST["searchterm"];
	
	sleep(1);
	
	if($action == 'add')
	{
	$id = $_REQUEST["id"];
	
	$sql = mysqli_query($dbmysqli, "SELECT e2servicename, servicename_enc, e2servicereference FROM `all_services` WHERE `e2servicereference` = '$id' ");
	$result = mysqli_fetch_assoc($sql);
	$e2servicename = $result['e2servicename'];
	$servicename_enc = $result['servicename_enc'];
	$e2servicereference = $result['e2servicereference'];
	
	$sql_2 = mysqli_query($dbmysqli, "SELECT e2servicename, servicename_enc, e2servicereference FROM `channel_list` WHERE `e2servicename` = '$e2servicename' AND `servicename_enc` = '$servicename_enc' AND `e2servicereference` = '$e2servicereference' ");
	$result_2 = mysqli_fetch_assoc($sql_2);

	if(mysqli_num_rows($sql_2) < 1)
	{
	$channel_hash = hash('md4',$e2servicename);

	mysqli_query($dbmysqli, "INSERT INTO `channel_list` (e2servicename, servicename_enc, e2servicereference, e2providername, channel_hash) values ('$e2servicename', '$servicename_enc', '$e2servicereference', '-', '$channel_hash')");
	
	echo "<i class='glyphicon glyphicon-ok green'></i>"; 
	
	} else { 
	
	echo "<i class='glyphicon glyphicon-ok gray'></i>"; 
	
	}
	exit; 
	}
	
	if($action == 'crawl')
	{
	mysqli_query($dbmysqli, "TRUNCATE `all_services`");
	
	$xmlfile = $url_format.'://'.$box_ip.'/web/getservices?sRef=1:7:1:0:0:0:0:0:0:0:%20ORDER%20BY%20name';
	
	$get_services_request = @file_get_contents($xmlfile, false, $webrequest);
	
	$xml = simplexml_load_string(preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $get_services_request));
	
	if($xml)
	{
	for ($i = 0; $i <= $i; $i++){

	if(!isset($xml->e2service[$i]->e2servicereference) or $xml->e2service[$i]->e2servicereference == ""){ $xml->e2service[$i]->e2servicereference = "";	}

	if($xml->e2service[$i]->e2servicereference == "")
	{
	// answer for ajax
	echo "error";
	exit;
	
	} else {
	
	// define searchline
	$e2servicereference = $xml->e2service[$i]->e2servicereference;
	$e2servicename = utf8_decode($xml->e2service[$i]->e2servicename);
	$servicename_enc = rawurlencode($xml->e2service[$i]->e2servicename);
	
	// remove special chars
	$e2servicereference = str_replace(" ", "%20", $e2servicereference); //important
	$e2servicereference = str_replace("\"", "%22", $e2servicereference); //important
	
	if($e2servicereference[4] == '2'){ $service = 'radio'; } else { $service = 'tv'; }
	
	mysqli_query($dbmysqli, "INSERT INTO `all_services` (e2servicename, servicename_enc, e2servicereference, service) VALUES ('$e2servicename', '$servicename_enc', '$e2servicereference', '$service')");
	}
	}
	}
	
	} else {
	
	if($service == '' or $service == 'both'){ $query = ''; }
	if($service == 'tv'){ $query = 'WHERE `service` = "tv" '; }
	if($service == 'radio'){ $query = 'WHERE `service` = "radio" '; }
	if($service == 'search'){ $query = 'WHERE `e2servicename` LIKE "%'.$searchterm.'%" '; }
	
	// count
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `all_services` '.$query.' ');
	$result = mysqli_fetch_row($sql);
	$summary_services = $result[0];
	
	$sql_2 = 'SELECT * FROM `all_services` '.$query.' ';
	
	if ($result_2 = mysqli_query($dbmysqli,$sql_2))
	{
	while ($obj = mysqli_fetch_object($result_2)) {
	{
	
	if(!isset($all_services_list) or $all_services_list == "") { $all_services_list = ""; }
	
	if($service == "search" and $obj->service == "radio"){ $channel_type = "<i class=\"glyphicon glyphicon-music\" title=\"Radio\"></i>"; } else { $channel_type = ""; }
	
	$services_total = '
	<div class="row">
	<div class="col-md-2">Total: '.$summary_services.'</div>
	<div class="col-md-10"></div>
	<div class="spacer_10"></div>
	</div>';
	
	$all_services_list = $all_services_list. "
	<div id=\"services_row\">
	<div class=\"row\">
	<div class=\"col-md-2\">
	<input id=\"all_services_add_btn_$obj->id\" name=\"$obj->e2servicereference\" type=\"submit\" onClick=\"all_services_add(this.id,this.name)\" title=\"Add $obj->e2servicename to Channel list\" value=\"Add\" class=\"btn btn-xs btn-default\"/>
	<input id=\"all_services_zapp_btn_$obj->id\" name=\"$obj->e2servicereference\" type=\"submit\" onClick=\"all_services_zapp(this.id,this.name)\" title=\"Zap to $obj->e2servicename\" value=\"Zap\" class=\"btn btn-xs btn-default\"/>
	<a href=\"$url_format://$box_ip/web/stream.m3u?ref=$obj->e2servicereference\" title=\"Stream $obj->e2servicename\"><i class=\"fa fa-desktop fa-1x\"></i></a>
	<span id=\"all_services_status_zapp_$obj->id\"></span>
	<span id=\"all_services_status_add_$obj->id\"></span>
	</div>
	<div class=\"col-md-6\"><span id=\"service_list_name_$obj->id\">$obj->e2servicename $channel_type</span></div>
	<div class=\"col-md-4\">$obj->e2servicereference</div>
	</div>
	</div><!--row-->
	<div class=\"spacer_10\"></div>";
	}
	}
	if(!isset($all_services_list) or $all_services_list == "") { $all_services_list = ""; }
	
	if($all_services_list == "" and $service == "search"){ $all_services_list = "No channels found.."; }
	
	if($all_services_list == "" and $service != "search"){ $all_services_list = "Service list in database is empty. Click button to copy services from Receiver.."; }
	
	if(!isset($services_total) or $services_total == "") { $services_total = ""; }
	
	echo utf8_encode($services_total.$all_services_list);
	
	}	
}
?>

</body>
</html>
