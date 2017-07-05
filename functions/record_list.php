<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>_broadcast_list_main</title>
<script type="text/javascript">
// record_list hover
$(document).ready(function(){
    $("#record_list_main*").hover(function(){
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
	header('Cache-Control: no-cache');
	
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == "") { $_REQUEST['record_location'] = ""; } else { $_REQUEST['record_location'] = $_REQUEST['record_location']; }
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == "") { $_REQUEST['action'] = ""; } else { $_REQUEST['action'] = $_REQUEST['action']; }
	if(!isset($_REQUEST['del_id']) or $_REQUEST['del_id'] == "") { $_REQUEST['del_id'] = ""; } else { $_REQUEST['del_id'] = $_REQUEST['del_id']; }
	
	$record_location = $_REQUEST['record_location'];
	$action = $_REQUEST['action'];
	$del_id = $_REQUEST['del_id'];
	
	if ($action == 'delete')
	{
	$delete_request = 'http://'.$box_ip.'/web/moviedelete?sRef='.$del_id.'';
	$delete_record = file_get_contents($delete_request, false, $webrequest);
	}
	
	// calculate filesize
	function formatBytes($size, $precision = 2)
	{
	$base = log($size, 1024);
	$suffixes = array('', 'kB', 'MB', 'GB', 'TB');   
	
	return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}

	// get recorded data
	$xmlfile = 'http://'.$box_ip.'/web/movielist?dirname='.$record_location.'&tag=';
	
	$getRecords_request = file_get_contents($xmlfile, false, $webrequest);
	
	$xml = simplexml_load_string($getRecords_request);
	
	$sum_rec_locations = 1000;
	
	if ($xml) {
	for ($i = 0; $i <= $sum_rec_locations; $i++) {

	///////////////////////////////////////////////
	
	if(!isset($xml->e2movie[$i]->e2servicereference) or $xml->e2movie[$i]->e2servicereference == ""){ $xml->e2movie[$i]->e2servicereference = "";
	
	} else { 
	
	$xml->e2movie[$i]->e2servicereference = $xml->e2movie[$i]->e2servicereference; }
	
	// if no data exit
	if($xml->e2movie[$i]->e2servicereference == "" ) {
	//echo 'empty';
	
	} else {
	
	// define line
	$e2servicereference = rawurlencode($xml->e2movie[$i]->e2servicereference);
	$e2title = rawurldecode($xml->e2movie[$i]->e2title);
	$e2description = rawurldecode($xml->e2movie[$i]->e2description);
	$e2descriptionextended = rawurldecode($xml->e2movie[$i]->e2descriptionextended);
	$e2servicename = rawurldecode($xml->e2movie[$i]->e2servicename);
	$e2time = $xml->e2movie[$i]->e2time;
	$e2length = $xml->e2movie[$i]->e2length;
	$e2filename = rawurldecode($xml->e2movie[$i]->e2filename);
	$e2filesize = $xml->e2movie[$i]->e2filesize;
	
	if ($time_format == '1')
	{
	// time format 1
	$record_date = date("d.m.Y - H:i |", "".$e2time."");
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$record_date = date("n/d/Y - g:i A |", "".$e2time."");
	}	
	$record_filesize = formatBytes("".$e2filesize."");
	
	$record_hash = hash('md4',$e2filename);
	
	if(!isset($record_list) or $record_list == "") { $record_list = ""; } else { $record_list = $record_list; }
	
	$record_list = $record_list."
	<div id=\"record_entry_$i\">
		<div id=\"record_list_main\">
	  <div id=\"record_$i\" style=\"cursor: pointer;\" onclick=\"record_list_desc(this.id);\">
		<div id=\"cnt_title\"> <span class=\"\">$record_date $e2title</span>
		</div>
		<div id=\"cnt_channel\"> <span class=\"\">$e2servicename</span> </div>
		<div style=\"clear:both\"></div>
	  </div>
	  <div id=\"record_btn_$i\" style=\"display:none; cursor: auto;\">
	  <div id=\"record_list_div_$i\">
		  <div class=\"spacer_5\"></div>
		  $e2description
		  <div class=\"spacer_5\"></div>
		  $e2descriptionextended
		  <div class=\"spacer_5\"></div>
		  <p>$e2length minutes, $record_filesize</p>
		</div>
		<input id=\"$e2servicereference\" type=\"submit\" onClick=\"delete_record(this.id)\" value=\"DELETE RECORD\" title=\"Delete record\" class=\"btn btn-xs btn-danger\"/>
		<a id=\"$e2filename\" onClick=\"create_m3u(this.id)\" title=\"Stream\" style=\"cursor:pointer;\"><i class=\"fa fa-desktop fa-1x\"></i></a>
		<span id=\"record_id_$e2filename\" style=\"display:none\">$record_hash</span>
		<span id=\"record_no_$e2servicereference\" style=\"display:none;\">$i</span>
		<a href=\"http://$box_user:$box_password@$box_ip/file?file=$e2filename\" title=\"Download\"><i class=\"glyphicon glyphicon-download-alt fa-1x\"></i></a>
		<span id=\"m3u_$e2filename\"></span>
		<span id=\"del_status_$i\"></span>
		</div>
	</div>
	<div class=\"spacer_10\"></div></div>";
	}
	}
	}
	if(!isset($record_list) or $record_list == "") { $record_list = "No files to display.."; } else { $record_list = $record_list; }
	echo $record_list;
?>
</body>
</html>
