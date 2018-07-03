<?php 
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['id']) or $_REQUEST['id'] == ""){ $_REQUEST['id'] = ""; }
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ""){ $_REQUEST['action'] = ""; }
	if(!isset($_REQUEST['device_description']) or $_REQUEST['device_description'] == ""){ $_REQUEST['device_description'] = ""; }
	if(!isset($_REQUEST['device_ip']) or $_REQUEST['device_ip'] == ""){ $_REQUEST['device_ip'] = ""; }
	if(!isset($_REQUEST['device_user']) or $_REQUEST['device_user'] == ""){ $_REQUEST['device_user'] = ""; }
	if(!isset($_REQUEST['device_password']) or $_REQUEST['device_password'] == ""){ $_REQUEST['device_password'] = ""; }
	if(!isset($_REQUEST['device_record_location']) or $_REQUEST['device_record_location'] == ""){ $_REQUEST['device_record_location'] = ""; }
	if(!isset($_REQUEST['device_color']) or $_REQUEST['device_color'] == ""){ $_REQUEST['device_color'] = ""; }
	if(!isset($_REQUEST['url_format']) or $_REQUEST['url_format'] == ""){ $_REQUEST['url_format'] = ""; }
	
	$id = $_REQUEST['id'];
	$action = $_REQUEST['action'];
	$device_description = rawurlencode($_REQUEST['device_description']);
	$device_ip = $_REQUEST['device_ip'];
	$device_user = $_REQUEST['device_user'];
	$device_password = $_REQUEST['device_password'];
	$device_record_location = $_REQUEST['device_record_location'];
	$device_color = $_REQUEST['device_color'];
	$url_format = $_REQUEST['url_format'];
	
	$device_description = preg_replace('/\s+/', ' ', $device_description);
	$device_record_location = preg_replace('/\s+/', '', $device_record_location);
	
	//
	if($action == "show"){
	
	$sql = "SELECT * FROM `device_list`";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){	
	{
	
	$id = $obj->id;
	$device_description = rawurldecode($obj->device_description);
	$device_ip = $obj->device_ip;
	$device_user = $obj->device_user;
	$device_password = $obj->device_password;
	$device_record_location = $obj->device_record_location;
	$device_color = $obj->device_color;
	$url_format = $obj->url_format;
	
//	$rec_location0 = $obj->rec_location0;
//	$rec_location1 = $obj->rec_location1;
//	$rec_location2 = $obj->rec_location2;
//	$rec_location3 = $obj->rec_location3;
//	$rec_location4 = $obj->rec_location4;
//	$rec_location5 = $obj->rec_location5;
//	$rec_location6 = $obj->rec_location6;
//	$rec_location7 = $obj->rec_location7;
//	$rec_location8 = $obj->rec_location8;
//	$rec_location9 = $obj->rec_location9;
//	
//	if($rec_location0 != ""){ $option_0 = "<option disabled selected>".$rec_location0."</option>"; } else { $option_0 = ""; }
//	if($rec_location1 != ""){ $option_1 = "<option disabled>".$rec_location1."</option>"; } else { $option_1 = ""; }
//	if($rec_location2 != ""){ $option_2 = "<option disabled>".$rec_location2."</option>"; } else { $option_2 = ""; }
//	if($rec_location3 != ""){ $option_3 = "<option disabled>".$rec_location3."</option>"; } else { $option_3 = ""; }
//	if($rec_location4 != ""){ $option_4 = "<option disabled>".$rec_location4."</option>"; } else { $option_4 = ""; }
//	if($rec_location5 != ""){ $option_5 = "<option disabled>".$rec_location5."</option>"; } else { $option_5 = ""; }
//	if($rec_location6 != ""){ $option_6 = "<option disabled>".$rec_location6."</option>"; } else { $option_6 = ""; }
//	if($rec_location7 != ""){ $option_7 = "<option disabled>".$rec_location7."</option>"; } else { $option_7 = ""; }
//	if($rec_location8 != ""){ $option_8 = " <option disabled>".$rec_location8."</option>"; } else { $option_8 = ""; }
//	if($rec_location9 != ""){ $option_9 = "<option disabled>".$rec_location9."</option>"; } else { $option_9 = ""; }
//	
//	$device_dropdown = 
//	"<select style=\"width:100%;\">
//	".$option_0."
//	".$option_1."
//	".$option_2."
//	".$option_3."
//	".$option_4."
//	".$option_5."
//	".$option_6."
//	".$option_7."
//	".$option_8."
//	".$option_9."
//	</select>";
	
	if(!isset($device_list) or $device_list == ""){ $device_list = ""; }
	
	$color = "#DDDDDD";
	if($device_color == "#DDDDDD"){ $gray = "selected"; $color = "#DDDDDD"; } else { $gray = ""; }
	if($device_color == "#000000"){ $black = "selected"; $color = "#000000"; } else { $black = ""; }
	if($device_color == "#428BCA"){ $blue = "selected"; $color = "#428BCA"; } else { $blue = ""; }
	if($device_color == "#999999"){ $darkgray = "selected"; $color = "#999999"; } else { $darkgray = ""; }
	if($device_color == "#5CB85C"){ $green = "selected"; $color = "#5CB85C"; } else { $green = ""; }
	if($device_color == "#F0AD4E"){ $orange = "selected"; $color = "#F0AD4E"; } else { $orange = ""; }
	if($device_color == "#D9534F"){ $red = "selected"; $color = "#D9534F"; } else { $red = ""; }
	if($device_color == "#FFFF00"){ $yellow = "selected"; $color = "#FFFF00"; } else { $yellow = ""; }
	
	if($url_format == "http"){ $http_selected = "selected"; } else { $http_selected = ""; }
	if($url_format == "https"){ $https_selected = "selected"; } else { $https_selected = ""; }
	
	$device_list = $device_list. "
	<div class=\"row\">
	<div class=\"spacer_20\"></div>
	<div class=\"col-md-2\">
	<input id=\"device_description_$id\" style=\"border: thin solid $color;\" type=\"text\" value=\"$device_description\" maxlength=\"15\">
	</div><!--col 2-->
	<div class=\"col-md-2\">
	<input id=\"device_ip_$id\" class=\"\" type=\"text\" value=\"$device_ip\">
	</div><!--col 2-->
	<div class=\"col-md-2\">
	<input id=\"device_user_$id\" class=\"\" type=\"text\" value=\"$device_user\">
	</div><!--col 2-->
	<div class=\"col-md-2\">
	<input id=\"device_password_$id\" class=\"\" type=\"password\" value=\"$device_password\">
	</div><!--col 2-->
	</div><!-- row -->
	<div class=\"row\">
	<div class=\"spacer_20\"></div>
	<div class=\"col-md-2\">
	<input id=\"device_record_location_$id\" class=\"\" type=\"text\" value=\"$device_record_location\">
	</div><!--col 2-->
	<div class=\"col-md-2\">
	<select id=\"device_color_$id\" style=\"width: 100%;\">
	<option value=\"#DDDDDD\" $gray>default</option>
	<option value=\"#000000\" $black>black</option>
	<option value=\"#428BCA\" $blue>blue</option>
	<option value=\"#999999\" $darkgray>dark gray</option>
	<option value=\"#5CB85C\" $green>green</option>
	<option value=\"#F0AD4E\" $orange>orange</option>
	<option value=\"#D9534F\" $red>red</option>
	<option value=\"#FFFF00\" $yellow>yellow</option>
	</select>
	</div><!--col 2-->
	<div class=\"col-md-2\">
	<select id=\"device_url_format_$id\">
	<option value=\"http\" $http_selected>http</option>
	<option value=\"https\" $https_selected>https</option>
	</select>
	</div><!--col 2-->
	<div class=\"col-md-2\">
	<input name=\"save\" id=\"save_device_no_$id\" type=\"button\" class=\"btn btn-default btn-xs\" value=\"Save\" onclick=\"device_list(this.id,this.name)\">
	<input name=\"delete\" id=\"delete_device_no_$id\" type=\"button\" class=\"btn btn-default btn-xs\" value=\"Delete\" onclick=\"device_list(this.id,this.name)\">
	<span id=\"device_list_status_$id\"></span>
	</div><!--col 2-->
	</div><!--row-->
	<div class=\"row\">
	<div class=\"col-md-12\">
	<span id=\"device_list\"></span>
	</div><!--col-->
	</div><!--row-->
	<hr>
	";
	}
	}
	}
	if(!isset($device_list) or $device_list == ""){ $device_list = ""; } else { echo $device_list; }
	exit;
	} // show
	
	if($action == "add"){
	sleep(1);
	
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(*) FROM `device_list` WHERE `device_ip` = '".$device_ip."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	if($summary != "0"){ echo "data:duplicate"; exit; }
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO `device_list` (`device_description`, `device_ip`, `device_user`, `device_password`, `device_record_location`, `device_color`, `url_format`) VALUES ('".$device_description."', '".$device_ip."', '".$device_user."', '".$device_password."', '".$device_record_location."', '".$device_color."', '".$url_format."')");
	
	// get record locations from receiver
	$rl_webrequest = stream_context_create(array (
	'http' => array (
	'header' => 'Authorization: Basic ' . base64_encode("$device_user:$device_password"),
	'ssl' =>array (
	'verify_peer' => false,
	'verify_peer_name' => false,
	))
	));
	$xmlfile = $url_format.'://'.$device_ip.'/web/getlocations';
	$getlocations_request = @file_get_contents($xmlfile, false, $rl_webrequest);
	$xml = simplexml_load_string($getlocations_request);
	
	if($xml == ""){ 
	//echo "data:connection_error"; 
	exit; 
	}
	
	if($xml){
    for ($i = 0; $i <= $i; $i++){
	
	if(!isset($xml->e2location[$i]) or $xml->e2location[$i] == ""){ $xml->e2location[$i] = ""; }
	
	if($xml->e2location[$i] != ""){
	$e2location = utf8_decode($xml->e2location[$i]);
	$sql = mysqli_query($dbmysqli, "UPDATE `device_list` SET `rec_location".$i."` = ('$e2location') WHERE `device_ip` = '".$device_ip."' ");
	}
	if($xml->e2location[$i] == ""){ echo "data:done"; exit; }
	}
	}
	//
	} // add
	
	if($action == "save"){
	sleep(1);
	$sql = mysqli_query($dbmysqli, "UPDATE `device_list` SET `device_description` = '".$device_description."', `device_ip` = '".$device_ip."', `device_user` = '".$device_user."', `device_password` = '".$device_password."', `device_record_location` = '".$device_record_location."', `device_color` = '".$device_color."', `url_format` = '".$url_format."' WHERE `id` = '".$id."' ");
	}
	
	if($action == "delete"){
	sleep(1);
	$sql = mysqli_query($dbmysqli, "DELETE FROM `device_list` WHERE `id` = '".$id."' ");
	echo "data:deleted";
	} // delete

?>