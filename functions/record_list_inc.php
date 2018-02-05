<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
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
include("../inc/dashboard_config.php");

	header('Cache-Control: no-cache');	
	
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == "") { $_REQUEST['record_location'] = ""; }
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == "") { $_REQUEST['action'] = ""; }
	if(!isset($_REQUEST['del_id']) or $_REQUEST['del_id'] == "") { $_REQUEST['del_id'] = ""; }
	
	$record_location = $_REQUEST['record_location'];
	$action = $_REQUEST['action'];
	$del_id = $_REQUEST['del_id'];
	
	if ($action == 'delete')
	{
	$delete_request = ''.$url_format.'://'.$box_ip.'/web/moviedelete?sRef='.$del_id.'';
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
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/movielist?dirname='.$record_location.'&tag=';
	
	$getRecords_request = file_get_contents($xmlfile, false, $webrequest);
	
	$xml = simplexml_load_string($getRecords_request);
	
if ($xml) {
	
	$sum_today = 0;
	$files_summary = 0;
	$filespace = 0;
	
	for ($i = 0; $i <= 250; $i++) {
	
	if(!isset($xml->e2movie[$i]->e2servicereference) or $xml->e2movie[$i]->e2servicereference == ""){ $xml->e2movie[$i]->e2servicereference = ""; }
	
	// if no data 
	if($xml->e2movie[$i]->e2servicereference == "" ) {
	
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
	
	$filespace = $filespace + $e2filesize;
	
	if ($time_format == '1')
	{
	// time format 1
	$record_date = date("d.m.Y - H:i |", "".$e2time."");
	
	$day_today = date("d.m.Y", "".$time."");
	
	$today_record = date("d.m.Y", "".$e2time."");
	
	if ($day_today == $today_record){ $sum_today = $sum_today +1; }
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$record_date = date("n/d/Y - g:i A |", "".$e2time."");
	
	$day_today = date("n/d/Y", "".$time."");
	
	$today_record = date("n/d/Y", "".$e2time."");

	if ($day_today == $today_record){ $sum_today = $sum_today +1; }
	}
	
	//
	$record_filesize = formatBytes("".$e2filesize."");
	
	$record_hash = hash('md4',$e2filename);
	
	if(!isset($record_list) or $record_list == ""){ $record_list = ""; }
	
	if ($imdb_symbol == '1' ){
	
	$imdb_broadcast = '<a href="https://www.imdb.com/find?ref_=nv_sr_fn&q='.$e2title.'" target="_blank" title="Info on IMDb"><i class="fa fa-info-circle fa-1x"></i></a>'; 
	
	} else {
	
	$imdb_broadcast = ''; }
	
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
		$imdb_broadcast
		<a id=\"$e2filename\" onClick=\"create_m3u(this.id)\" title=\"Stream\" style=\"cursor:pointer;\"><i class=\"fa fa-desktop fa-1x\"></i></a>
		<span id=\"record_id_$e2filename\" style=\"display:none\">$record_hash</span>
		<span id=\"record_no_$e2servicereference\" style=\"display:none;\">$i</span>
		<a href=\"$url_format://$box_user:$box_password@$box_ip/file?file=$e2filename\" title=\"Download\"><i class=\"glyphicon glyphicon-download-alt fa-1x\"></i></a>
		<span id=\"m3u_$e2filename\"></span>
		<span id=\"del_status_$i\"></span>
		</div>
	</div>
	<div class=\"spacer_10\"></div></div>";
	$files_summary = $i+1;
	}
	}
	}
	if(!isset($record_list) or $record_list == "") { $record_list = "No files to display.."; }
	
	$filespace_total = formatBytes("".$filespace."");
	
	if($filespace == "0") { $filespace_total = "0 kB"; }
	
	echo 'Records in folder: <strong>'.$files_summary.'</strong> | Today recorded: '.$sum_today.' | Diskspace used: '.$filespace_total.' <div class="spacer_20">
	</div>'.$record_list;
?>
</body>
</html>
