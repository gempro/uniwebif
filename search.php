<?php 
session_start();
//
	include("inc/dashboard_config.php");
	include_once("inc/header_info.php");

	if(!isset($_POST['searchterm']) or $_POST['searchterm'] == ""){ $_POST['searchterm'] = ""; }
	if(!isset($_REQUEST['searchterm']) or $_REQUEST['searchterm'] == ""){ $_REQUEST['searchterm'] = ""; }
	if(!isset($_REQUEST['option']) or $_REQUEST['option'] == ""){ $_REQUEST['option'] = ""; }
	if(!isset($_REQUEST['search_channel']) or $_REQUEST['search_channel'] == ""){ $_REQUEST['search_channel'] = ""; }
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == ""){ $_REQUEST['channel_id'] = ""; }
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == ""){ $_REQUEST['record_location'] = ""; }
	if(!isset($_REQUEST['exclude_channel']) or $_REQUEST['exclude_channel'] == ""){ $_REQUEST['exclude_channel'] = ""; }
	if(!isset($_REQUEST['exclude_title']) or $_REQUEST['exclude_title'] == ""){ $_REQUEST['exclude_title'] = ""; }
	if(!isset($_REQUEST['exclude_description']) or $_REQUEST['exclude_description'] == ""){ $_REQUEST['exclude_description'] = ""; }
	if(!isset($_REQUEST['exclude_extdescription']) or $_REQUEST['exclude_extdescription'] == ""){ $_REQUEST['exclude_extdescription'] = ""; }
	if(!isset($_REQUEST['rec_replay']) or $_REQUEST['rec_replay'] == ""){ $_REQUEST['rec_replay'] = ""; }
	if(!isset($_REQUEST['search_id']) or $_REQUEST['search_id'] == ""){ $_REQUEST['search_id'] = ""; }
	
	$search_id = $_REQUEST["search_id"];
	$searchterm = trim($_POST["searchterm"]);
	$searchterm = trim($_REQUEST["searchterm"]);
	$searchterm = str_replace("\"", "", $searchterm);
	//$searchterm = str_replace("'", "", $searchterm);
	$searchterm = str_replace("%", "", $searchterm);
	//$searchterm = utf8_decode($searchterm);
	
	$option = $_REQUEST["option"];
	$search_channel = $_REQUEST["search_channel"];
	$channel_id = $_REQUEST["channel_id"];
	$record_location = $_REQUEST["record_location"];
	
	$exclude_channel = $_REQUEST["exclude_channel"];
	$exclude_channel = str_replace("\"", "", $exclude_channel);
	//$exclude_channel = str_replace("'", "", $exclude_channel);
	$exclude_channel = str_replace("%", "", $exclude_channel);
	
	$exclude_title = $_REQUEST["exclude_title"];
	$exclude_title = str_replace("\"", "", $exclude_title);
	//$exclude_title = str_replace("'", "", $exclude_title);
	$exclude_title = str_replace("%", "", $exclude_title);
	
	$exclude_description = $_REQUEST["exclude_description"];
	$exclude_description = str_replace("\"", "", $exclude_description);
	//$exclude_description = str_replace("'", "", $exclude_description);
	$exclude_description = str_replace("%", "", $exclude_description);
	
	$exclude_extdescription = $_REQUEST["exclude_extdescription"];
	$exclude_extdescription = str_replace("\"", "", $exclude_extdescription);
	//$exclude_extdescription = str_replace("'", "", $exclude_extdescription);
	$exclude_extdescription = str_replace("%", "", $exclude_extdescription);
	
	$rec_replay = $_REQUEST["rec_replay"];
	
	// update saved search
	if(is_numeric($search_id)){ 
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `saved_search` WHERE `id` = '".$search_id."' ");
	$result = mysqli_fetch_assoc($sql);
	if($result != ""){ 
	$update_search = '<span class="update_search"><a id="'.$search_id.'" onclick="save_search(this.id)">Update saved search</a></span>';
	}
	}
	if(!isset($update_search) or $update_search == ""){ $update_search = ""; }
	// saved search update end
	
	// set record location
	if($record_location != "")
	{ 
	$sql = mysqli_query($dbmysqli, "UPDATE `record_locations` SET `selected` = '1' WHERE `id` = '".$record_location."' "); 
	} else {
    $sql = mysqli_query($dbmysqli, "UPDATE `record_locations` SET `selected` = '0' ");
	}
	//
	
	if($searchterm == "" or strlen($searchterm) < "3"){
	
	$p_save_search = "<p><strong>Please use more than 2 signs for searchterm</strong></p>";

	} else {
	
	if($search_id != ""){
	
	$p_save_search = "<p>".$update_search."</p>";
	
	} else {
	
	$p_save_search = '<p><span class="save_search"><a id="save" onclick="save_search(this.id)">Save search for Auto Timer</a></span></p>';
	}
	
	// set selected channel in dropdown
	if($search_channel == 'on'){
	// reset selected
    $sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `selected` = '0' ");
    // set selected
    $sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `selected` = '1' WHERE `e2servicereference` = '".$channel_id."' ");
	}
	
	// record location in dropdown / result list
	$sql2 = "SELECT * FROM `record_locations` ORDER BY `id` ASC";
	
	if($result2 = mysqli_query($dbmysqli,$sql2))
	{
	while ($obj = mysqli_fetch_object($result2)){
	{
	
	if(!isset($rec_dropdown_broadcast) or $rec_dropdown_broadcast == ""){ $rec_dropdown_broadcast = ""; }

	$rec_dropdown_broadcast = $rec_dropdown_broadcast."<option value=\"$obj->id\">$obj->e2location</option>"; 
	}
	}
	}
	
	// device dropdown
	$sql3 = "SELECT * FROM `device_list` ORDER BY `id` ASC";
	
	if($result3 = mysqli_query($dbmysqli,$sql3))
	{
	while ($obj = mysqli_fetch_object($result3)){
	{
	$id = $obj->id;
	$device_description = utf8_decode(rawurldecode($obj->device_description));
	
	if(!isset($device_dropdown) or $device_dropdown == ""){ $device_dropdown = ""; }

	$device_dropdown = $device_dropdown."<option value=\"$id\">$device_description</option>"; 
	}
	}
	}
	
	if ($display_old_epg == '0'){ $exclude_time = 'AND `e2eventend` > '.$time.''; } else { $exclude_time = ''; }

	// search only selected channel
	if($channel_id !== ''){ 
	$search_include = 'WHERE `e2eventservicereference` = "'.$channel_id.'" AND'; 
	$search_include2 = 'OR `e2eventservicereference` = "'.$channel_id.'" AND'; 
	} else { 
	$search_include = 'WHERE'; 
	$search_include2 = 'OR'; 
	}
	
	$raw_term = rawurlencode($searchterm);
	
	$raw_exclude_channel = rawurlencode($exclude_channel);
	// exclude titel
	if ($raw_exclude_channel !== ''){
	$tags = explode(rawurlencode(';') , $raw_exclude_channel);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_channel_part) or $exclude_channel_part == "") { $exclude_channel_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND `servicename_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_channel_part = $exclude_channel_part.$search_string;
	}	
	} else { $exclude_channel_part = ""; }
	
	$raw_exclude_title = rawurlencode($exclude_title);
	// exclude titel
	if ($raw_exclude_title !== ''){
	$tags = explode(rawurlencode(';') , $raw_exclude_title);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_title_part) or $exclude_title_part == "") { $exclude_title_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND `title_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_title_part = $exclude_title_part.$search_string;
	}
	} else { $exclude_title_part = ""; }
	
	$raw_exclude_description = rawurlencode($exclude_description);
	// exclude description
	if($raw_exclude_description !== ''){
	$tags = explode(rawurlencode(';') , $raw_exclude_description);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_description_part) or $exclude_description_part == "") { $exclude_description_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND `description_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_description_part = $exclude_description_part.$search_string;
	}
	} else { $exclude_description_part = ""; }
	
	$raw_exclude_extdescription = rawurlencode($exclude_extdescription);
	// exclude extended description
	if ($raw_exclude_extdescription !== ''){
	$tags = explode(rawurlencode(';') , $raw_exclude_extdescription);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_extdescription_part) or $exclude_extdescription_part == "") { $exclude_extdescription_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND `descriptionextended_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_extdescription_part = $exclude_extdescription_part.$search_string;
	}
	} else { $exclude_extdescription_part = ""; }

	// search all
	if ($option == 'all' or $option == '')
	{
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' MATCH (title_enc, description_enc, descriptionextended_enc) AGAINST ("%'.$raw_term.'%") '.$exclude_time.' '.$search_include2.' `title_enc` LIKE "%'.$raw_term.'%" '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_channel_part.' '.$exclude_time.' '.$search_include2.' `description_enc` LIKE "%'.$raw_term.'%" '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_channel_part.' '.$exclude_time.' '.$search_include2.' `descriptionextended_enc` LIKE "%'.$raw_term.'%" '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_channel_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	
	// count hits
	$sql2 = mysqli_query($dbmysqli, "SELECT COUNT(*) FROM `epg_data` ".$search_include." MATCH (title_enc, description_enc, descriptionextended_enc) 
	AGAINST ('%".$raw_term."%') ".$exclude_time." ".$search_include2." `title_enc` LIKE '%".$raw_term."%' 
	".$exclude_title_part." ".$exclude_description_part." ".$exclude_extdescription_part." ".$exclude_channel_part." ".$exclude_time." ".$search_include2." 
	`description_enc` LIKE '%".$raw_term."%' ".$exclude_title_part." ".$exclude_description_part." ".$exclude_extdescription_part." ".$exclude_channel_part." ".$exclude_time." 
	".$search_include2." `descriptionextended_enc` LIKE '%".$raw_term."%' ".$exclude_title_part." ".$exclude_description_part." ".$exclude_extdescription_part." 
	".$exclude_channel_part." ".$exclude_time." ");
	$result = mysqli_fetch_row($sql2);
	$count_search = $result[0];
	//
	if ($count_search > 1000)
	{
	echo "There are more than 1000 results for this search.<br>Please define term more exactly. There is the risk that the process crash..<br>";
	echo "<a href=\"javascript:history.back();\">back</a>";
	exit;
	}
	}
	  
	// search title
	if ($option == 'title')
	{
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' `title_enc` LIKE "%'.$raw_term.'%" '.$exclude_channel_part.' '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	
	// count hits
	$sql2 = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `epg_data` '.$search_include.' `title_enc` LIKE "%'.$raw_term.'%" '.$exclude_channel_part.' '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_time.'');
	$result = mysqli_fetch_row($sql2);
	$count_search = $result[0];
	//
	if ($count_search > 1000)
	{
	echo "There are more than 1000 results for this search.<br>Please define term more exactly. There is the risk that the process crash..<br>";
	echo "<a href=\"javascript:history.back();\">back</a>";
	exit;
	}
	}
	
	// search description
	if ($option == 'description')
	{
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' `description_enc` LIKE "%'.$raw_term.'%" '.$exclude_channel_part.' '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	
	// count hits
	$sql2 = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `epg_data` '.$search_include.' `description_enc` LIKE "%'.$raw_term.'%" '.$exclude_channel_part.' '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_time.' ');
	$result = mysqli_fetch_row($sql2);
	$count_search = $result[0];
	//
	if ($count_search > 1000)
	{
	echo "There are more than 1000 results for this search.<br>Please define term more exactly. There is the risk that the process crash..<br>";
	echo "<a href=\"javascript:history.back();\">back</a>";
	exit;
	}
	}
	
	// search extended description
	if ($option == 'extdescription')
	{
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' descriptionextended_enc LIKE "%'.$raw_term.'%" '.$exclude_channel_part.' '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	
	// count hits
	$sql2 = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `epg_data` '.$search_include.' `descriptionextended_enc` LIKE "%'.$raw_term.'%" '.$exclude_channel_part.' '.$exclude_title_part.' '.$exclude_description_part.' '.$exclude_extdescription_part.' '.$exclude_time.' ');
	$result = mysqli_fetch_row($sql2);
	$count_search = $result[0];
	//
	if ($count_search > 1000)
	{
	echo "There are more than 1000 results for this search.<br>Please define term more exactly. There is the risk that the process crash..<br>";
	echo "<a href=\"javascript:history.back();\">back</a>";
	exit;
	}
	}
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
    {
	
	if ($mark_searchterm == '1')
	{
	// mark search term
	$obj->e2eventtitle = preg_replace("#".quotemeta($searchterm)."#is", "<span class=\"highlight_e2eventtitle\">".$searchterm."</span>", $obj->e2eventtitle);
	$obj->e2eventdescription = preg_replace("#".quotemeta($searchterm)."#is", "<span class=\"highlight_e2eventdescription\">".$searchterm."</span>", $obj->e2eventdescription);
	$obj->e2eventdescriptionextended = preg_replace("#".quotemeta($searchterm)."#is", "<span class=\"highlight_e2eventdescriptionextended\">".$searchterm."</span>", $obj->e2eventdescriptionextended);
	}
	
	if ($time_format == '1')
	{
	// time format 1
	$e2eventstart = $obj->e2eventstart;
	$date_start = date("l, d.m.Y - H:i", $e2eventstart);
	$e2eventend = $obj->e2eventend;
	$date_end = date("l, d.m.Y - H:i", $e2eventend);
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$e2eventstart = $obj->e2eventstart;
	$date_start = date("l n/d/Y - g:i A", $e2eventstart);
	$e2eventend = $obj->e2eventend;
	$date_end = date("l n/d/Y - g:i A", $e2eventend);
	}
	
	if($obj->e2eventdescription == ""){ $obj->e2eventdescription = "No description"; }
	if($obj->e2eventdescriptionextended == ""){ $obj->e2eventdescriptionextended = "No extended description"; }
	if(!isset($result_list) or $result_list == "") { $result_list = ""; }
	
	if(!isset($device_dropdown) or $device_dropdown == ""){ $device_dropdown = ""; }
	
	$result_list = $result_list."
		<div id=\"search_result_header\"><a href=\"search.php?searchterm=$raw_term&option=$option&search_channel=on&channel_id=$obj->e2eventservicereference\" target=\"_blank\" class=\"links\" title=\"Search the term only on this channel\"><strong>$obj->e2eventservicename</strong></a>
		| $obj->e2eventtitle | Description: $obj->e2eventdescription</div>
		<div class=\"spacer_10\"></div>
		Extended description:
		<div class=\"spacer_10\"></div>
		<p>$obj->e2eventdescriptionextended</p>
		<p>Start: $date_start<br>
		End: $date_end<br>
		Duration: $obj->total_min Min.<div class=\"spacer_5\"></div>
		<input id=\"searchlist_timer_btn_$obj->hash\" type=\"submit\" onClick=\"searchlist_timer(this.id)\" value=\"SET TIMER\" class=\"btn btn-success btn-sm\" title=\"send Timer to Receiver\"/>
		<input id=\"searchlist_zap_btn_$obj->hash\" type=\"submit\" name=\"$obj->e2eventservicereference\" onClick=\"searchlist_zap(this.id,this.name)\" value=\"ZAP TO CHANNEL\" class=\"btn btn-default btn-sm\"/>
		<span id=\"searchlist_status_zap_$obj->hash\"></span>
		<span id=\"searchlist_status_timer_$obj->hash\"></span>
		<div class=\"spacer_10\"></div>
		<span>Receiver: </span>
		<select id=\"searchlist_device_dropdown_$obj->hash\" class=\"device_dropdown\" onchange=\"searchlist_change_device(this.id)\">
		<option value=\"0\">default</option>
		$device_dropdown
		</select>
		<div class=\"spacer_10\"></div>
		<span>Record location: </span>
		<select id=\"rec_location_searchlist_$obj->hash\" class=\"rec_location_dropdown\">
		$rec_dropdown_broadcast
		</select>
		<hr>";
		}
    }
}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Uniwebif : Search in EPG</title>
<!-- BOOTSTRAP STYLES-->
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<!-- FONTAWESOME STYLES-->
<link href="assets/css/font-awesome.css" rel="stylesheet" />
<!-- CUSTOM STYLES-->
<link href="assets/css/custom.css" rel="stylesheet" />
<!-- Modal-->
<link href="assets/css/rmodal-no-bootstrap.css" rel="stylesheet" />
<!-- Noty-->
<link href="assets/css/noty/noty.css" rel="stylesheet" />
<link href="assets/css/noty/animate.css" rel="stylesheet" />
<link href="assets/css/noty/themes/mint.css" rel="stylesheet" />
<!-- favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="images/icon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="images/icon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="images/icon/favicon-16x16.png">
<link rel="manifest" href="images/icon/manifest.json">
<link rel="mask-icon" href="images/icon/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">
<!-- GOOGLE FONTS-->
<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />-->
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/pace.min.js"></script>
<script type="text/javascript" src="js/animatedcollapse.js">
/***********************************************
* Animated Collapsible DIV v2.4- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Please keep this notice intact
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>
<script type="text/javascript">
animatedcollapse.addDiv('div_crawl_complete', 'fade=1,speed=400,height=auto')
animatedcollapse.addDiv('crawl_complete_status', 'fade=1,height=auto')
animatedcollapse.addDiv('div_crawl_channel_id', 'fade=1,speed=400,height=auto')
animatedcollapse.addDiv('crawl_channel_id_status', 'fade=1,height=auto')
animatedcollapse.addDiv('div_crawl_search', 'fade=1,speed=400,height=auto')
animatedcollapse.addDiv('crawl_search_status', 'fade=1,height=auto')
animatedcollapse.addDiv('div_send_timer', 'fade=1,speed=400,height=auto')
animatedcollapse.addDiv('send_timer_status', 'fade=1,height=auto')
animatedcollapse.addDiv('div_start_channelzapper', 'fade=1,speed=400,height=auto')
animatedcollapse.addDiv('channelzapper_status', 'fade=1,height=auto')
animatedcollapse.addDiv('save_search_status', 'fade=1,height=auto')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
//$: Access to jQuery
//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
//state: "block" or "none", depending on state
}
animatedcollapse.init()

function save_search(id){

	if(id == 'save'){ var action = 'save'; var search_id = ''; } else { var action = 'update'; var search_id = id; }
	var rec_loc = '<?php echo $_REQUEST['record_location']; ?>';
	if (rec_loc == '') {
	var record_location = $("#searchlist_record_location").val();
	}
	if (rec_loc !== '') {
	var record_location = '<?php echo $_REQUEST['record_location']; ?>';
	}
	$.post("functions/save_search.php",
	{
	search_id: search_id,
	action: action,
	option: '<?php echo $_REQUEST['option']; ?>', 
	searchterm: '<?php echo $_REQUEST['searchterm']; ?>', 
	record_location: record_location, 
	channel_id: '<?php echo $_REQUEST['channel_id']; ?>', 
	exclude_channel: '<?php echo $_REQUEST['exclude_channel']; ?>', 
	exclude_title: '<?php echo $_REQUEST['exclude_title']; ?>', 
	exclude_description: '<?php echo $_REQUEST['exclude_description']; ?>', 
	exclude_extdescription: '<?php echo $_REQUEST['exclude_extdescription']; ?>', 
	rec_replay: '<?php echo $_REQUEST['rec_replay']; ?>'
	},
	function(data){
	if(data == 'data:save done'){
	function save_search_ok()
	{
	$("#save_search_status").html("Search was saved!");
	animatedcollapse.show('save_search_status');
	}
	window.setTimeout(save_search_ok, 100);
	}

	if(data == 'data:save nok'){
	function save_search_error()
	{
	$("#save_search_status").html("<span class=\"error\">Search already in database!</span>");
	animatedcollapse.show('save_search_status');	
	}
	window.setTimeout(save_search_error, 100);
	}
	
	if(data == 'data:update done'){
	function update_search_ok()
	{
	$("#save_search_status").html("<span class=\"search_update_ok\">Search was updated!</span>");
	animatedcollapse.show('save_search_status');	
	}
	window.setTimeout(update_search_ok, 100);
	}
	
	function reset_save_search_status()
	{
	animatedcollapse.hide('save_search_status');		
	}
	window.setTimeout(reset_save_search_status, 2500);
	$("#save_search_status").html("");
	});
}
//
function check_channel_search(){
	if (search_channel.checked == true) { document.getElementById("channel_id").disabled = false; }
	if (search_channel.checked == false) { document.getElementById("channel_id").disabled = true; }
}
function check_exclude(){
	if (exclude_channel_checkbox.checked == true){
	$("#status_exclude_channel").fadeIn();
	$("#exclude_channel").attr({ disabled:false, class:'exclude_channel_c' }); $("#status_exclude_channel").text("Channel"); } else { $("#exclude_channel").attr({ disabled:true, class:'exclude_channel_g' }); $("#status_exclude_channel").fadeOut(); $("#status_exclude_channel").text(""); }

	if (exclude_title_checkbox.checked == true){
	$("#status_exclude_title").fadeIn();
	$("#exclude_title").attr({ disabled:false, class:'exclude_title_c' }); $("#status_exclude_title").text("Title"); } else { $("#exclude_title").attr({ disabled:true, class:'exclude_title_g' }); $("#status_exclude_title").fadeOut(); $("#status_exclude_title").text(""); }
	
	if (exclude_description_checkbox.checked == true){
	$("#status_exclude_description").fadeIn();
	$("#exclude_description").attr({ disabled:false, class:'exclude_description_c' }); $("#status_exclude_description").text("Description"); } else { $("#exclude_description").attr({ disabled:true, class:'exclude_description_g' }); $("#status_exclude_description").fadeOut(); $("#status_exclude_description").text(""); }
	
	if (exclude_extdescription_checkbox.checked == true){
	$("#status_exclude_extdescription").fadeIn();
	$("#exclude_extdescription").attr({ disabled:false, class:'exclude_extdescription_c' }); $("#status_exclude_extdescription").text("Ext. description"); } else { $("#exclude_extdescription").attr({ disabled:true, class:'exclude_extdescription_g' }); $("#status_exclude_extdescription").fadeOut(); $("#status_exclude_extdescription").text(""); }
}
</script>
</head>
<body onload="check_channel_search(); check_exclude();">
<a id="top"></a>
<div id="scroll_top" class="scroll_top"><a href="#" title="to top"><script>document.write("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script></a></div>
<!--statusbar modal -->
 <span id="showModal"></span>
  <div id="modal" class="modal">
    <div class="modal-dialog animated">
    <div class="modal-content">
      <div id="sb-modal-header" class="modal-header"></div>
      <div class="modal-body">
        <div id="epgframe"></div>
        <hr>
        <div align="right">
        <button class="btn btn-default" type="button" onclick="modal.close();">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--statusbar modal -->
<!--remote control modal -->
 <span id="showModal"></span>
  <div id="remote_modal" class="modal_rc">
    <div class="modal-dialog animated">
    <div class="modal-content">
      <div class="modal-header">Remote Control <span id="rc_status"></span>
      </div>
      <div class="modal-body">
        <div id="rc_frame"></div>
        <hr>
        <div align="right">
        <button class="btn btn-default" type="button" onclick="remote_modal.close();">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--remote control modal -->
<!--quickpanel modal -->
 <span id="showModal"></span>
  <div id="quickpanel_modal" class="modal">
    <div class="modal-dialog animated">
    <div class="modal-content">
      <div id="quickpanel-modal-header" class="modal-header"></div>
      <div class="modal-body">
        <div id="quickpanel_epgframe"></div>
        <hr>
        <div align="right">
        <button class="btn btn-default" type="button" onclick="quickpanel_modal.close();">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--quickpanel modal -->
<div id="wrapper">
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="adjust-nav">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" onclick="nav_icon_scroll()" data-toggle="collapse" data-target=".sidebar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="search.php"><i class="fa fa-square-o"></i>&nbsp;Search</a> </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <div class="row">
            <div class="col-md-12">
              <div id="navbar_info">oldest: <span class="badge"><?php echo $date_first; echo " - "; echo utf8_encode($first_entry['e2eventservicename']); ?></span> latest: <span class="badge-success"><?php echo $date_latest; echo " - "; echo utf8_encode($last_entry['e2eventservicename']); ?></span> <?php echo $header_date; ?></div>
              <!--navbar_info-->
            </div>
          </div>
        </ul>
      </div>
    </div>
  </div>
  <!-- /. NAV TOP  -->
  <nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
      <ul class="nav" id="main-menu">
        <script>document.write(navbar_header_search)</script>
        <li> <a href="dashboard.php"><i class="fa fa-home"></i>HOME</a> </li>
        <li> <a href="search.php"><i class="fa fa-search"></i><strong>Search</strong></a> </li>
        <li> <a href="timer.php"><i class="fa fa-clock-o"></i>Timer & Saved Search</a> </li>
        <li> <a href="#"><i class="fa fa-wrench"></i>Crawler Tools<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_channel_id');"><i class="fa fa-chevron-right"></i>Crawl Channel ID's</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_complete');"><i class="fa fa-chevron-right"></i>Crawl EPG from Channels</a> </li>
            <li> <a href="crawl_separate.php"><i class="fa fa-chevron-right"></i>Crawl Channel separate</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_search');"><i class="fa fa-chevron-right"></i>Crawl Search - Write Timer in Database</a></li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_send_timer');"><i class="fa fa-chevron-right"></i>Send Timer from Database to Receiver</a> </li>
          </ul>
        </li>
        <li> <a href="#"><i class="fa fa-cog"></i>Settings<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="settings.php"><i class="fa fa-cog"></i>Main Settings</a> </li>
            <li> <a href="channel_list.php"><i class="fa fa-list"></i>Channel list</a> </li>
            <li> <a href="bouquet_list.php"><i class="fa fa-list"></i>Bouquet list</a> </li>
          </ul>
        </li>
        <li> <a href="records.php"><i class="glyphicon glyphicon-record"></i>Records</a> </li>
        <li> <a id="116" onclick="power_control(this.id)" style="cursor:pointer;"> <i class="glyphicon glyphicon-off"></i>Wake up / Standby <span id="pc116"></span></a> </li>
        <li> <a href="#"><i class="glyphicon glyphicon-hand-right"></i>Extras<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
          	<li> <a href="services.php"><i class="fa fa-list"></i>All Services</a> </li>
            <li> <a onclick="remote_modal.open();" style="cursor:pointer;"><i class="fa fa-table"></i>Remote Control</a> </li>
            <li> <a href="teletext.php"><i class="fa fa-globe"></i>Teletext Browser</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_start_channelzapper');"> <i class="fa fa-arrow-up"></i>Channel Zapper</a> </li>
            <li> <a href="install.php"><i class="fa fa-wrench"></i>Install</a> </li>
            <li> <a href="about.php"><i class="glyphicon glyphicon-question-sign"></i>About</a> </li>
          </ul>
        </li>
        <li style="background-color: #fff;" id="quickpanel_inc"></li>
      </ul>
    </div>
  </nav>
  <!-- /. NAV SIDE  -->
  <div id="page-wrapper">
  <div class="row">
  <div class="col-md-12">
  <div id="statusbar_outer" class="statusbar_outer">
  <div id="statusbar_cnt">&nbsp;</div>
  </div>
  </div>
  </div><!-- /. ROW  -->
    <div id="page-inner">
    <div class="row">
    <div id="cookie_js" class="col-md-12" style="color:#FF0000;">
    <noscript>To use all functions of the website, it's required to activate JavaScript.</noscript>
    </div>
    </div>
      <div class="row">
        <div class="col-md-12">
          <h2>Search</h2>
        </div>
      </div>
      <!--crawl channel id-->
      <div id="div_crawl_channel_id">
      <span class="panel-close" onclick="animatedcollapse.hide('div_crawl_channel_id')"><span aria-hidden="true">x</span></span>
        <h1>Crawl channel ID's</h1>
        <input type="submit" class="btn btn-success" id="crawl_channel_id_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_channel_id_status'); crawl_channel_id();">
        <div id="crawl_channel_id_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--crawl channel id-->
      <div id="div_crawl_channel_id">
      <span class="panel-close" onclick="animatedcollapse.hide('div_crawl_channel_id')"><span aria-hidden="true">x</span></span>
        <h1>Crawl Channel ID's</h1>
        <input type="submit" class="btn btn-success" id="crawl_channel_id_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_channel_id_status'); crawl_channel_id();">
        <div id="crawl_channel_id_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--crawl channel id-->
      </div>
      <!--crawl complete-->
      <div id="div_crawl_complete">
      <span class="panel-close" onclick="animatedcollapse.hide('div_crawl_complete')"><span aria-hidden="true">x</span></span>
        <h1>Crawl EPG from Channels</h1>
        <input type="submit" class="btn btn-success" id="crawl_complete_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_complete_status'); crawl_complete();">
        <div id="crawl_complete_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--crawl complete-->
      <!--crawl saved search-->
      <div id="div_crawl_search">
      <span class="panel-close" onclick="animatedcollapse.hide('div_crawl_search')"><span aria-hidden="true">x</span></span>
        <h1>Crawl Search - Write Timer in Database</h1>
        <input type="submit" class="btn btn-success" id="crawl_search_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_search_status'); crawl_saved_search();">
        <div id="crawl_search_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--crawl saved search-->
      <!--send timer-->
      <div id="div_send_timer">
      <span class="panel-close" onclick="animatedcollapse.hide('div_send_timer')"><span aria-hidden="true">x</span></span>
        <h1>Send Timer from Database to Receiver</h1>
        <input type="submit" class="btn btn-success" id="send_timer_btn" value="Click to confirm" onclick="animatedcollapse.show('send_timer_status'); send_timer();">
        <div id="send_timer_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--send timer-->
      <!--channelzapper-->
      <div id="div_start_channelzapper">
      <span class="panel-close" onclick="animatedcollapse.hide('div_start_channelzapper')"><span aria-hidden="true">x</span></span>
        <h1>Start Channel Zapper</h1>
        <input type="submit" class="btn btn-success" id="start_channelzapper_btn" value="Click to confirm" onclick="animatedcollapse.show('channelzapper_status'); start_channelzapper();">
        <div id="channelzapper_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--channelzapper-->
      <hr />
      <div class="row">
        <form method="#" action="">
          <div class="col-md-6">
            <div class="spacer_3"></div>
            <input name="searchterm" type="text" class="form-control" size="50" id="searchterm" value="<?php if(!isset($searchterm) or $searchterm == "") 
			{ $searchterm = ""; } echo $searchterm; ?>">
            <div class="spacer_10"></div>
            <div id="radio-group">
              <div id="radio1">
                <label>
                <input type="radio" name="option" value="all" id="option1_0" <?php if ($option == 'all' or $option == ''){ echo "checked"; } ?>>
                All</label>
              </div>
              <div id="radio2">
                <label>
                <input type="radio" name="option" value="title" id="option1_1" <?php if ($option == 'title'){ echo "checked"; } ?>>
                Title</label>
              </div>
              <div id="radio3">
                <label>
                <input type="radio" name="option" value="description" id="option1_2" <?php if ($option == 'description'){ echo "checked"; } ?>>
                Description</label>
              </div>
              <div id="radio4">
                <label>
                <input type="radio" name="option" value="extdescription" id="option1_3" <?php if ($option == 'extdescription'){ echo "checked"; } ?>>
                Extended description</label>
              </div>
              <div style="clear:both"></div>
            </div>
            <!-- radio-group-->
            <div id="btn-group">
              <div id="btn1">
                <input type="submit" value="Search trough" class="btn btn-success"/>
              </div>
              <div id="btn2">Recording path: 
                <select name="record_location" id="searchlist_record_location">
                  <?php 
					$sql = "SELECT * FROM `record_locations` ORDER BY `id` ASC";
			
					if ($result = mysqli_query($dbmysqli,$sql))
					{
					// Fetch one and one row
					while ($obj = mysqli_fetch_object($result)) {
					{
					//set selected
					if ($obj->selected == "1"){
					$select = "selected=\"selected\"";
					}
					elseif ($obj->selected == "0")
					{
					$select = "";
					}
					echo "<option value='$obj->id' $select>$obj->e2location</option>
					"; }
					}
					} 
					?></select>
                    </div>
              	<div style="clear:both"></div>
              <div class="spacer_10"></div>
             <label>
            <input type="checkbox" name="search_channel" id="search_channel" onclick="check_channel_search()" <?php if ($search_channel == 'on'){ echo "checked"; } ?> />
            Search only at this channel</label>
            <select name="channel_id" id="channel_id" class="form-control">
			<?php 
			// get channels
			$sql = "SELECT * FROM `channel_list` ORDER BY `e2servicename` ASC";
			
			if ($result = mysqli_query($dbmysqli,$sql))
			{
			// Fetch one and one row
			while ($obj = mysqli_fetch_object($result)) {
			{
			// set selected
			if ($obj->selected == "1")
			{
			$select = "selected=\"selected\"";
			}
			elseif ($obj->selected == "0")
			{
			$select = "";
			}
			echo utf8_encode("<option value='$obj->e2servicereference' $select>$obj->e2servicename</option>
			"); }
			}
			}
			?></select>
            </div>
            <div class="spacer_10"></div>
            <div class="spacer_5"></div>
            <?php 
        	if(!isset($count_search) or $count_search == "") { $count_search = ""; }
        	if ($count_search == '' ){ $count_search = '0'; }
        	if ($count_search == '1' ){ $sum = 'result'; } else { $sum = 'results'; }
        	if($searchterm == "" ) { $desc_text = ''; } else { $desc_text = 'Found <strong>'.$count_search.'</strong> '.$sum.' with this term'; }
        	echo $desc_text;
        	if ($searchterm !== ''){ echo $p_save_search; }
        	?><div id="save_search_status"></div>
            <!-- status -->
          </div>
          <div class="col-md-6">
          Excluded term(s): 
          <span id="status_exclude_channel"></span>
          <span id="status_exclude_title"></span>
          <span id="status_exclude_description"></span>
          <span id="status_exclude_extdescription"></span>
          <div class="spacer_5"></div>
          <label><input id="exclude_channel_checkbox" type="checkbox" onclick="check_exclude()" <?php if ($exclude_channel !== ''){ echo "checked"; } ?>></label>
          <input name="exclude_channel" id="exclude_channel" type="text" style="width:80%;" value="<?php if(!isset($exclude_channel) or $exclude_channel == ""){ $exclude_channel = ""; } echo $exclude_channel; ?>" placeholder="Channel">
          <!---->
          <div class="spacer_5"></div>
          <label><input id="exclude_title_checkbox" type="checkbox" onclick="check_exclude()" <?php if ($exclude_title !== ''){ echo "checked"; } ?>></label>
          <input name="exclude_title" id="exclude_title" type="text" style="width:80%;" value="<?php if(!isset($exclude_title) or $exclude_title == ""){ $exclude_title = ""; } echo $exclude_title; ?>" placeholder="Title">
          <!---->
          <div class="spacer_5"></div>
          <label><input id="exclude_description_checkbox" type="checkbox" onclick="check_exclude()" <?php if ($exclude_description !== ''){ echo "checked"; } ?>></label>
          <input name="exclude_description" id="exclude_description" type="text" style="width:80%;" value="<?php if(!isset($exclude_description) or $exclude_description == ""){ $exclude_description = ""; } echo $exclude_description; ?>" placeholder="Description">
          <!---->
          <div class="spacer_5"></div>
          <label><input id="exclude_extdescription_checkbox" type="checkbox" onclick="check_exclude()" <?php if ($exclude_extdescription !== ''){ echo "checked"; } ?>></label>
          <input name="exclude_extdescription" id="exclude_extdescription" type="text" style="width:80%;" value="<?php if(!isset($exclude_extdescription) or $exclude_extdescription == ""){ $exclude_extdescription = ""; } echo $exclude_extdescription; ?>" placeholder="Extended description">
        <div class="spacer_10"></div>
             <label><input name="rec_replay" id="rec_replay" type="checkbox" <?php if ($rec_replay == 'on'){ echo "checked"; } ?>> Set also Timer for repeating Broadcast's</label>
             <div class="spacer_10"></div> 
          </div><!--exclude-->
          <input name="search_id" id="search_id" type="text" class="hidden" value="<?php if(!isset($search_id) or $search_id == ""){ $search_id = ""; } echo $search_id; ?>" placeholder="Extended description">
        </form>
      </div>
      <!-- /. ROW  -->
      <hr />
      <div class="row">
        <div class="col-md-12"><?php if(!isset($result_list) or $result_list == "") { $result_list = ""; } else { echo utf8_encode($result_list); } ?></div>
      </div>
      <!-- /. ROW  -->
    </div>
    <!-- /. PAGE INNER  -->
  </div>
  <!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.min.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="assets/js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>
<!-- Noty -->
<script src="js/noty.min.js"></script>
<script src="js/noty-msg.js"></script>
<!-- Modal-->
<script type="text/javascript" src="js/rmodal.js"></script>
<!---->
<script>
$(function(){
   var statusbar = '<?php if(!isset($_SESSION["statusbar"]) or $_SESSION["statusbar"] == "") { $_SESSION["statusbar"] = ""; } echo $_SESSION["statusbar"]; ?>';
   if (statusbar == '1'){ $("#statusbar_outer").removeClass("statusbar_outer"); }
   //
   var cookies = navigator.cookieEnabled;
   if(cookies == false){ $("#cookie_js").html("To use all functions of the website, it's required to accept Cookies."); }
});
</script>
</body>
</html>