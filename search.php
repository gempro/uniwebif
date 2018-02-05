<?php 
session_start();
//
include("inc/dashboard_config.php");
include_once("inc/header_info.php");

	if(!isset($_POST['searchterm']) or $_POST['searchterm'] == "") { $_POST['searchterm'] = ""; }
	if(!isset($_REQUEST['searchterm']) or $_REQUEST['searchterm'] == "") { $_REQUEST['searchterm'] = ""; }
	if(!isset($_REQUEST['option']) or $_REQUEST['option'] == "") { $_REQUEST['option'] = ""; }
	if(!isset($_REQUEST['search_channel']) or $_REQUEST['search_channel'] == ""){ $_REQUEST['search_channel'] = ""; }
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == "") { $_REQUEST['channel_id'] = ""; }
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == "") { $_REQUEST['record_location'] = ""; }
	if(!isset($_REQUEST['exclude_term']) or $_REQUEST['exclude_term'] == "") { $_REQUEST['exclude_term'] = ""; }
	if(!isset($_REQUEST['exclude_area']) or $_REQUEST['exclude_area'] == "") { $_REQUEST['exclude_area'] = ""; }
	if(!isset($_REQUEST['rec_replay']) or $_REQUEST['rec_replay'] == "") { $_REQUEST['rec_replay'] = ""; }
	
	$searchterm = $_POST["searchterm"];
	$searchterm = $_REQUEST["searchterm"];
	$searchterm = str_replace("\"", "", $searchterm);
	//$searchterm = str_replace("'", "", $searchterm);
	$searchterm = str_replace("%", "", $searchterm);
	
	$option = $_REQUEST["option"];
	$search_channel = $_REQUEST["search_channel"];
	$channel_id = $_REQUEST["channel_id"];
	$record_location = $_REQUEST["record_location"];
	
	$exclude_term = $_REQUEST["exclude_term"];
	$exclude_area = $_REQUEST["exclude_area"];
	$exclude_term = str_replace("\"", "", $exclude_term);
	//$exclude_term = str_replace("'", "", $exclude_term);
	$exclude_term = str_replace("%", "", $exclude_term);
	
	$rec_replay = $_REQUEST["rec_replay"];
	
	// empty selected record location
    $sql = mysqli_query($dbmysqli, "UPDATE record_locations SET selected = 0");
	
	if($searchterm == "" or strlen($searchterm) < "3") {
	
	$p_save_search = "<p><strong>Please use more than 2 signs for searchterm</strong></p>";

	} else {
	
	$p_save_search = "<p><a href=\"#save_search\" onclick=\"save_search()\">Save this search for timer</a></p>";
	
	// empty selected channel
    $sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `selected` = '0' ");
	
	// set selected channel in dropdown
	if ($search_channel == 'on'){
    // set selected
    $sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `selected` = '1' WHERE `e2servicereference` = '".$channel_id."' ");
	}
	
	// record location in dropdown / result list
	$sql2 = "SELECT * FROM `record_locations` ORDER BY `id` ASC";
	
	if ($result2 = mysqli_query($dbmysqli,$sql2))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result2)){
	{
	
	if(!isset($rec_dropdown_broadcast) or $rec_dropdown_broadcast == "") { $rec_dropdown_broadcast = ""; }

	$rec_dropdown_broadcast = $rec_dropdown_broadcast."<option value=\"$obj->id\">$obj->e2location</option>"; }
	}
	}
	
	// set record location from search options
	if ($record_location !== ''){
    // set selected
    $sql = mysqli_query($dbmysqli, "UPDATE `record_locations` SET `selected` = '1' WHERE `id` = '".$record_location."' ");
	}
	
	if ($display_old_epg == '0'){ $exclude_time = 'AND `e2eventend` > '.$time.''; } else { $exclude_time = ''; }

	// search only selected channel
	if ($channel_id !== ''){ 
	$search_include = 'WHERE `e2eventservicereference` = "'.$channel_id.'" AND'; 
	$search_include2 = 'OR `e2eventservicereference` = "'.$channel_id.'" AND'; 
	} else { 
	$search_include = 'WHERE'; 
	$search_include2 = 'OR'; 
	}
	
	$raw_term = rawurlencode($searchterm);
	
	$raw_exclude = rawurlencode($exclude_term);
	// exclude titel
	if ($exclude_term !== '' and $exclude_area == '1'){
	// explode
	$tags = explode(rawurlencode(';') , $raw_exclude);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_part) or $exclude_part == "") { $exclude_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND `title_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_part = $exclude_part.$search_string;
	} // ecplode 	
	}
	
	// exclude description
	if ($exclude_term !== '' and $exclude_area == '2'){
	// explode
	$tags = explode(rawurlencode(';') , $raw_exclude);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_part) or $exclude_part == "") { $exclude_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND `description_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_part = $exclude_part.$search_string;
	} // ecplode
	}
	
	// exclude extended description
	if ($exclude_term !== '' and $exclude_area == '3'){
	// explode
	$tags = explode(rawurlencode(';') , $raw_exclude);
	foreach($tags as $i =>$key) { $i >0;
	if(!isset($exclude_part) or $exclude_part == "") { $exclude_part = ""; }
	if(!isset($key) or $key == "") { $search_string = ''; } else { $search_string = 'AND `descriptionextended_enc` NOT LIKE "%'.$key.'%" '; }
	$exclude_part = $exclude_part.$search_string;
	} // ecplode
	}
	
	if(!isset($exclude_part) or $exclude_part == "") { $exclude_part = ""; }

	// search all
	if ($option == 'all' or $option == '')
	{
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' MATCH (title_enc, e2eventservicename, description_enc, descriptionextended_enc) AGAINST ("%'.$raw_term.'%") '.$exclude_time.' '.$search_include2.' `title_enc` LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' `e2eventservicename` LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' `description_enc` LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' `descriptionextended_enc` LIKE "%'.$raw_term.'%" '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	
	// count hits
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_search FROM `epg_data` '.$search_include.' MATCH (title_enc, e2eventservicename, description_enc, descriptionextended_enc) AGAINST ("%'.$raw_term.'%") '.$exclude_time.' '.$search_include2.' `title_enc` LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' `e2eventservicename` LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' `description_enc` LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' `descriptionextended_enc` LIKE "%'.$raw_term.'%" '.$exclude_time.' ');
	
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_search);
	$stmt->fetch();
	$stmt->close();
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
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' `title_enc` LIKE "%'.$raw_term.'%" '.$exclude_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	
	// count hits
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_search FROM `epg_data` '.$search_include.' `title_enc` LIKE "%'.$raw_term.'%" '.$exclude_part.' '.$exclude_time.' ');
	
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_search);
	$stmt->fetch();
	$stmt->close();
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
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' `description_enc` LIKE "%'.$raw_term.'%" '.$exclude_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	
	// count hits
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_search FROM `epg_data` '.$search_include.' `description_enc` LIKE "%'.$raw_term.'%" '.$exclude_part.' '.$exclude_time.' ');
	
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_search);
	$stmt->fetch();
	$stmt->close();
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
	$sql = 'SELECT * FROM `epg_data` '.$search_include.' descriptionextended_enc LIKE "%'.$raw_term.'%" '.$exclude_part.' '.$exclude_time.' ORDER BY `e2eventstart` ASC';
	
	// count hits
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_search FROM `epg_data` '.$search_include.' `descriptionextended_enc` LIKE "%'.$raw_term.'%" '.$exclude_part.' '.$exclude_time.' ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_search);
	$stmt->fetch();
	$stmt->close();
	if ($count_search > 1000)
	{
	echo "There are more than 1000 results for this search.<br>Please define term more exactly. There is the risk that the process crash..<br>";
	echo "<a href=\"javascript:history.back();\">back</a>";
	exit;
	}
	}
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
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
	
	if(!isset($result_list) or $result_list == "") { $result_list = ""; }
	
	$result_list = $result_list."
		<div id=\"search_result_header\"><a href=\"search.php?searchterm=$raw_term&option=$option&search_channel=on&channel_id=".$obj->e2eventservicereference."\" target=\"_blank\" class=\"links\" title=\"Search the term only on this channel\"><strong>$obj->e2eventservicename</strong></a>
		| $obj->e2eventtitle | Description: $obj->e2eventdescription</div>
		<div class=\"spacer_10\"></div>
		Extended description:
		<div class=\"spacer_10\"></div>
		<p>$obj->e2eventdescriptionextended</p>
		<p>Start: ".$date_start."<br>
		End: ".$date_end."<br>
		Duration: $obj->total_min Min.<div class=\"spacer_5\"></div>
		<input id=\"searchlist_timer_btn_$obj->hash\" type=\"submit\" onClick=\"searchlist_timer(this.id)\" value=\"SET TIMER\" class=\"btn btn-success btn-sm\" title=\"send timer instantly\"/>
		<input id=\"searchlist_zap_btn_$obj->hash\" type=\"submit\" name=\"$obj->e2eventservicereference\" onClick=\"searchlist_zap(this.id,this.name)\" value=\"ZAP TO CHANNEL\" class=\"btn btn-default btn-sm\"/>
		<span id=\"searchlist_status_zap_$obj->hash\"></span>
		<span id=\"searchlist_status_timer_$obj->hash\"></span>
		<div class=\"spacer_10\"></div>
		<span>Record location: </span><select id=\"searchlist_record_location_$obj->hash\" class=\"rec_location_dropdown\">$rec_dropdown_broadcast</select>
		<hr>";
		}
    }
  // Free result set
  mysqli_free_result($result);
	}
}
//close db
//mysqli_close($dbmysqli);
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
<link rel="apple-touch-icon" sizes="180x180" href="images/icon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="images/icon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="images/icon/favicon-16x16.png">
<link rel="manifest" href="images/icon/manifest.json">
<link rel="mask-icon" href="images/icon/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">
<!-- GOOGLE FONTS-->
<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />-->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/ie_sse.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
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

<!--
function save_search() {
if(typeof(EventSource) !== "undefined") {

	var rec_loc = '<?php echo $_REQUEST['record_location']; ?>';
	if (rec_loc == '') {
	var record_location = document.getElementById("searchlist_record_location").value;
	}
	if (rec_loc !== '') {
	var record_location = '<?php echo $_REQUEST['record_location']; ?>';
	}
	
    var source = new EventSource("functions/save_search.php?option=<?php echo $_REQUEST['option']; ?>&searchterm=<?php echo rawurlencode($_REQUEST['searchterm']); ?>&record_location="+record_location+"&channel_id=<?php echo $_REQUEST['channel_id']; ?>&exclude_area=<?php echo $_REQUEST['exclude_area']; ?>&exclude_term=<?php echo rawurlencode($_REQUEST['exclude_term']); ?>&rec_replay=<?php echo $_REQUEST['rec_replay']; ?>");
	
    source.onmessage = function(event) {

	if (event.data == 'save search - done!') {
	function save_search_ok () { document.getElementById("save_search_status").innerHTML = "Search was saved!";
	animatedcollapse.show('save_search_status');
	}
	window.setTimeout(save_search_ok, 100);
	this.close();
	}

	if (event.data == 'save search - nok!') {
	function save_search_error () { document.getElementById("save_search_status").innerHTML = "<span class=\"error\">Search already in database!</span>";
	animatedcollapse.show('save_search_status');	
	}
	window.setTimeout(save_search_error, 100);
	this.close();
	}
	
	function reset_save_search_status () { animatedcollapse.hide('save_search_status');		
	}
	window.setTimeout(reset_save_search_status, 2500);
	document.getElementById("save_search_status").innerHTML = "";
	this.close();
	};
	} else {
	document.getElementById("save_search_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}
</script>
<script type="text/javascript">
function check_channel_search() {
	if (search_channel.checked == true) { document.getElementById("channel_id").disabled = false; }
	if (search_channel.checked == false) { document.getElementById("channel_id").disabled = true; }
}
function check_exclude(){
	if (exclude.checked == true) { document.getElementById("exclude_term").disabled = false; document.getElementById("exclude_area").disabled = false; }
	if (exclude.checked == false) { document.getElementById("exclude_term").disabled = true; document.getElementById("exclude_area").disabled = true; }
}
</script>
</head>
<body onload="check_channel_search(); check_exclude();">
<a id="top"></a>
<div id="scroll_top" class="scroll_top"><a href="#" title="to top"><script>document.write("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script></a></div>
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
            <li> <a href="channel_list.php"><i class="fa fa-list"></i>Channel List</a> </li>
            <li> <a href="bouquet_list.php"><i class="fa fa-list"></i>Bouquet List</a> </li>
          </ul>
        </li>
        <li> <a href="records.php"><i class="glyphicon glyphicon-record"></i>Records</a> </li>
        <li> <a id="116" onclick="power_control(this.id)" style="cursor:pointer;"> <i class="glyphicon glyphicon-off"></i>Wake up / Standby <span id="pc116"></span></a> </li>
        <li> <a href="#"><i class="glyphicon glyphicon-hand-right"></i>Extras<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="teletext.php"><i class="fa fa-globe"></i>Teletext Browser</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_start_channelzapper');"> <i class="fa fa-arrow-up"></i>Channel Zapper</a> </li>
            <li><a href="services.php"><i class="fa fa-list"></i>All Services</a> </li>
            <li> <a href="setup.php"><i class="fa fa-wrench"></i>Setup</a> </li>
            <li> <a href="about.php"><i class="glyphicon glyphicon-question-sign"></i>About</a> </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <!-- /. NAV SIDE  -->
  <div id="page-wrapper">
  <div class="row">
  <div class="col-md-12">
  <div id="statusbar_cnt_outter" class="statusbar_cnt_outter">
  <div id="statusbar_cnt"></div>
  </div>
  </div>
  </div><!-- /. ROW  -->
    <div id="page-inner">
      <div class="row">
        <div class="col-md-12">
          <h2>Search</h2>
        </div>
      </div>
      <!--crawl channel id-->
      <div id="div_crawl_channel_id">
        <h1>Crawl channel ID's</h1>
        <input type="submit" class="btn btn-success" id="crawl_channel_id_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_channel_id_status'); crawl_channel_id();">
        <div id="crawl_channel_id_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--div_crawl_complete-->
      <!--crawl complete-->
      <div id="div_crawl_complete">
        <h1>Crawl EPG from channels</h1>
        <input type="submit" class="btn btn-success" id="crawl_complete_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_complete_status'); crawl_complete();">
        <div id="crawl_complete_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--div_crawl_complete-->
      <!--crawl mysearch id-->
      <div id="div_crawl_search">
        <h1>Crawl search - Write timer in database</h1>
        <input type="submit" class="btn btn-success" id="crawl_search_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_search_status'); crawl_saved_search();">
        <div id="crawl_search_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--div_mysearch-->
      <!--send timer-->
      <div id="div_send_timer">
        <h1>Send timer from database to Receiver</h1>
        <input type="submit" class="btn btn-success" id="send_timer_btn" value="Click to confirm" onclick="animatedcollapse.show('send_timer_status'); send_timer();">
        <div id="send_timer_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--send timer-->
      <!--channelzapper-->
      <div id="div_start_channelzapper">
        <h1>Start Channel Zapper</h1>
        <input type="submit" class="btn btn-success" id="start_channelzapper_btn" value="Click to confirm" onclick="animatedcollapse.show('channelzapper_status'); start_channelzapper();">
        <div id="channelzapper_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--div_channelzapper-->
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
                all</label>
              </div>
              <div id="radio2">
                <label>
                <input type="radio" name="option" value="title" id="option1_1" <?php if ($option == 'title'){ echo "checked"; } ?>>
                title</label>
              </div>
              <div id="radio3">
                <label>
                <input type="radio" name="option" value="description" id="option1_2" <?php if ($option == 'description'){ echo "checked"; } ?>>
                description</label>
              </div>
              <div id="radio4">
                <label>
                <input type="radio" name="option" value="extdescription" id="option1_3" <?php if ($option == 'extdescription'){ echo "checked"; } ?>>
                extended description</label>
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
					$sql = "SELECT * from record_locations order by id ASC";
			
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
					?>
                </select>
              </div>
              <div style="clear:both">&nbsp;</div>
            </div>
            <?php 
        	if(!isset($count_search) or $count_search == "") { $count_search = ""; }
        	if ($count_search == '' ){ $count_search = '0'; }
        	if ($count_search == '1' ){ $sum = 'result'; } else { $sum = 'results'; }
        	if($searchterm == "" ) { $desc_text = ''; } else { $desc_text = 'Found <strong>'.$count_search.'</strong> '.$sum.' with this term'; }
        	echo $desc_text;
        	if ($searchterm !== ''){ echo $p_save_search; }
        	?>
            <div id="save_search_status"> </div>
            <!-- status -->
          </div>
          <div class="col-md-6">
            <label>
            <input type="checkbox" name="search_channel" id="search_channel" onclick="check_channel_search()" <?php if ($search_channel == 'on'){ echo "checked"; } ?> />
            Search only at this channel</label>
            <select name="channel_id" id="channel_id" class="form-control">
			<?php 
			// get channels
			$sql = "SELECT * FROM channel_list ORDER BY e2servicename ASC";
			
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
			?>
            </select>
          </div>
        <div class="col-md-6">
        <div class="spacer_5"></div>
        <label><input id="exclude" type="checkbox" onclick="check_exclude()" <?php if ($exclude_term !== ''){ echo "checked"; } ?>>
        Exclude Term: </label>
        <input name="exclude_term" id="exclude_term" type="text" value="<?php if(!isset($exclude_term) or $exclude_term == "") 
		{ $exclude_term = ""; } echo $exclude_term; ?>">
        <select name="exclude_area" id="exclude_area">
          <option value="1" <?php if ($exclude_area == '1'){ echo "selected"; } ?>>title</option>
          <option value="2" <?php if ($exclude_area == '2'){ echo "selected"; } ?>>description</option>
          <option value="3" <?php if ($exclude_area == '3'){ echo "selected"; } ?>>extended description</option>
        </select>
        <label><input name="rec_replay" id="rec_replay" type="checkbox" <?php if ($rec_replay == 'on'){ echo "checked"; } ?>> Set also Timer for repeating Broadcast's</label>
        </div>
        </form>
      </div>
      <!-- /. ROW  -->
      <hr />
      <div class="row"></div>
      <!-- /. ROW  -->
      <div class="row">
        <div class="col-md-12">
          <?php if(!isset($result_list) or $result_list == "") { $result_list = ""; } else { echo utf8_encode($result_list); } ?>
        </div>
      </div>
      <!-- /. ROW  -->
    </div>
    <!-- /. PAGE INNER  -->
  </div>
  <!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.min.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="assets/js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>
<script>
$(document).ready(function(){
   var statusbar = '<?php if(!isset($_SESSION["statusbar"]) or $_SESSION["statusbar"] == "") { $_SESSION["statusbar"] = ""; } echo $_SESSION["statusbar"]; ?>';
   if (statusbar == '1'){
   $("#statusbar_cnt_outter").removeClass("statusbar_cnt_outter"); 
   $("#statusbar_cnt").html("&nbsp;");
   }
});
</script>
</body>
</html>
