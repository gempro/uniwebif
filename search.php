<?php 
session_start();
//
include("inc/dashboard_config.php");

	if(!isset($_POST['searchterm']) or $_POST['searchterm'] == "") { $_POST['searchterm'] = ""; } else { $_POST['searchterm'] = $_POST['searchterm']; }
	
	if(!isset($_REQUEST['searchterm']) or $_REQUEST['searchterm'] == "") { $_REQUEST['searchterm'] = ""; } else { $_REQUEST['searchterm'] = $_REQUEST['searchterm']; }
	
	if(!isset($_REQUEST['option']) or $_REQUEST['option'] == "") { $_REQUEST['option'] = ""; } else { $_REQUEST['option'] = $_REQUEST['option']; }
	
	if(!isset($_REQUEST['search_channel']) or $_REQUEST['search_channel'] == ""){ $_REQUEST['search_channel'] = ""; } else { $_REQUEST['search_channel'] = $_REQUEST['search_channel']; }
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == "") { $_REQUEST['channel_id'] = ""; } else { $_REQUEST['channel_id'] = $_REQUEST['channel_id']; }
	
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == "") { $_REQUEST['record_location'] = ""; } else { $_REQUEST['record_location'] = $_REQUEST['record_location']; }
	
	$searchterm = $_POST["searchterm"];
	$searchterm = $_REQUEST["searchterm"];
	$searchterm = str_replace("\"", "", $searchterm);
	$searchterm = str_replace("'", "", $searchterm);
	$searchterm = str_replace("%", "", $searchterm);
	
	$option = $_REQUEST["option"];
	$search_channel = $_REQUEST["search_channel"];
	$channel_id = $_REQUEST["channel_id"];
	$record_location = $_REQUEST["record_location"];
	
	// select oldest entry
	$query = mysqli_query($dbmysqli, "SELECT e2eventservicename, e2eventstart FROM `epg_data` ORDER BY e2eventstart ASC LIMIT 0 , 1");
	$first_entry = mysqli_fetch_assoc($query);
	
	if ($time_format == '1')
	{
	// time format 1
	$date_first = date("d.m.Y H:i", $first_entry['e2eventstart']);
	}
	if ($time_format == '2')
	{
	// time format 2
	$date_first = date("n/d/Y g:i A", $first_entry['e2eventstart']);
	}
	
	// select latest entry
	$query = mysqli_query($dbmysqli, "SELECT e2eventservicename, e2eventstart FROM `epg_data` ORDER BY e2eventstart DESC LIMIT 0 , 1");
	$last_entry = mysqli_fetch_assoc($query);
	
	if ($time_format == '1')
	{
	// time format 1
	$date_latest = date("d.m.Y H:i", $last_entry['e2eventstart']);
	}
	if ($time_format == '2')
	{
	// time format 2
	$date_latest = date("n/d/Y g:i A", $last_entry['e2eventstart']);
	}
	
	if ($date_first == '01.01.1970 01:00' or $date_first == '1/01/1970 1:00 AM'){ $date_first = 'no data'; }
	if ($date_latest == '01.01.1970 01:00' or $date_latest == '1/01/1970 1:00 AM'){ $date_latest = 'no data'; }
	if ($first_entry['e2eventservicename'] == ''){ $first_entry['e2eventservicename'] = 'no data'; }	
	if ($last_entry['e2eventservicename'] == ''){ $last_entry['e2eventservicename'] = 'no data'; }
	
	if($searchterm == "" or strlen($searchterm) < "3") {
	
	$p_save_search = "<p><strong>Please use more than 2 signs for searchterm</strong></p>";

	} else {
	
	if ($searchterm != '')
	
	$p_save_search = "<p><a href=\"#save_search\" onclick=\"save_search()\">Save this search for timer</a></p>";

	// wildcard
	//if ($wildcard == 'on' ){ $wildcard_suche = utf8_decode("%".$searchterm."%"); } else { $wildcard_suche = utf8_decode($searchterm); }
	//
	
	$raw_term = rawurlencode($searchterm);
	
	// set selected channel in dropdown
	if ($search_channel == 'on'){
    // empty selected
    $sql = mysqli_query($dbmysqli, "UPDATE channel_list SET selected = 0");
    // set selected
    $sql = mysqli_query($dbmysqli, "UPDATE channel_list SET selected = 1 WHERE e2servicereference = '".$channel_id."'");
	}
	
	// get record locations
	$sql2 = "SELECT * FROM `record_locations` ORDER BY id ASC";
	
	if ($result2 = mysqli_query($dbmysqli,$sql2))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result2)) {	
	{
	if(!isset($rec_dropdown_broadcast) or $rec_dropdown_broadcast == "") { $rec_dropdown_broadcast = ""; } else { $rec_dropdown_broadcast = $rec_dropdown_broadcast; }

	$rec_dropdown_broadcast = $rec_dropdown_broadcast."<option value=\"$obj->id\">$obj->e2location</option>"; }
	}
	}
	
	// set selected in record locations dropdown
	if ($record_location !== ''){
    // empty selected
    $sql = mysqli_query($dbmysqli, "UPDATE record_locations SET selected = 0");
    // set selected
    $sql = mysqli_query($dbmysqli, "UPDATE record_locations SET selected = 1 WHERE id = '".$record_location."'");
	}
	
	if ($display_old_epg == '0'){ $exclude_time = 'AND e2eventend > '.$time.''; } else { $exclude_time = ''; }

	// search only selected channel
	if ($channel_id !== ''){ 
	$search_include = 'WHERE e2eventservicereference = "'.$channel_id.'" AND'; 
	$search_include2 = 'OR e2eventservicereference = "'.$channel_id.'" AND'; 
	
	} else { 
	
	$search_include = 'WHERE'; 
	$search_include2 = 'OR'; 
	}

	// search all
	if ($option == 'all' or $option == '')
	{
	$sql = 'SELECT * FROM epg_data '.$search_include.' MATCH (title_enc, e2eventservicename, description_enc, descriptionextended_enc) AGAINST ("%'.$raw_term.'%") '.$exclude_time.' '.$search_include2.' title_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' e2eventservicename LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' description_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' descriptionextended_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' ORDER BY e2eventstart ASC';
	
	// count hits
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_search FROM epg_data '.$search_include.' MATCH (title_enc, e2eventservicename, description_enc, descriptionextended_enc) AGAINST ("%'.$raw_term.'%") '.$exclude_time.' '.$search_include2.' title_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' e2eventservicename LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' description_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' '.$search_include2.' descriptionextended_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' ');
	
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
	$sql = 'SELECT * FROM epg_data '.$search_include.' title_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' ORDER BY e2eventstart ASC';
	
	// count hits
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_search FROM epg_data '.$search_include.' title_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' ');
	
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
	$sql = 'SELECT * FROM epg_data '.$search_include.' description_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' ORDER BY e2eventstart ASC';
	
	// count hits
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_search FROM epg_data '.$search_include.' description_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' ');
	
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
	$sql = 'SELECT * FROM epg_data '.$search_include.' descriptionextended_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' ORDER BY e2eventstart ASC';
	
	// count hits
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_search FROM epg_data '.$search_include.' descriptionextended_enc LIKE "%'.$raw_term.'%" '.$exclude_time.' ');
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
	//$date_start = date("l, d.m.Y - H:i", $e2eventstart);
	$date_start_weekday = date("l", $e2eventstart);
	$date_start_day = date("d", $e2eventstart);
	$date_start_month = date("m", $e2eventstart);
	$date_start_year = date("Y", $e2eventstart);
	$date_start_hour = date("H", $e2eventstart);
	$date_start_minute = date("i", $e2eventstart);
	$date_start = "$date_start_weekday, $date_start_day.$date_start_month.$date_start_year - <strong>$date_start_hour:$date_start_minute</strong>";
	
	$e2eventend = $obj->e2eventend;
	$date_end_weekday = date("l", $e2eventend);
	$date_end_day = date("d", $e2eventend);
	$date_end_month = date("m", $e2eventend);
	$date_end_year = date("Y", $e2eventend);
	$date_end_hour = date("H", $e2eventend);
	$date_end_minute = date("i", $e2eventend);
	$date_end = "$date_end_weekday, $date_end_day.$date_end_month.$date_end_year - $date_end_hour:$date_end_minute";
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$e2eventstart = $obj->e2eventstart;
	//$date_start = date("l n/d/Y - g:i A", $e2eventstart);
	$date_start_weekday = date("l", $e2eventstart);
	$date_start_month = date("n", $e2eventstart);
	$date_start_day = date("d", $e2eventstart);
	$date_start_year = date("Y", $e2eventstart);
	$date_start_hour = date("g", $e2eventstart);
	$date_start_minute = date("i", $e2eventstart);
	$date_start_ampm = date("A", $e2eventstart);
	$date_start = "$date_start_weekday, $date_start_month/$date_start_day/$date_start_year - <strong>$date_start_hour:$date_start_minute $date_start_ampm</strong>";
	
	$e2eventend = $obj->e2eventend;
	$date_end_weekday = date("l", $e2eventend);
	$date_end_month= date("n", $e2eventend);
	$date_end_day = date("d", $e2eventend);
	$date_end_year = date("Y", $e2eventend);
	$date_end_hour = date("g", $e2eventend);
	$date_end_minute = date("i", $e2eventend);
	$date_end_ampm = date("A", $e2eventend);
	$date_end = "$date_end_weekday, $date_end_month/$date_end_day/$date_end_year - $date_end_hour:$date_end_minute $date_end_ampm";
	}
	
	if(!isset($result_list) or $result_list == "") { $result_list = ""; } else { $result_list = $result_list; }
	
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
		<input id=\"searchlist_timer_btn_$obj->hash\" type=\"submit\" onClick=\"searchlist_timer(this.id)\" value=\"SET TIMER\" class=\"btn btn-success\" title=\"send timer instantly\"/>
		<input id=\"searchlist_zap_btn_$obj->hash\" type=\"submit\" onClick=\"searchlist_zap(this.id)\" value=\"ZAPP TO CHANNEL\" class=\"btn btn-default\"/>
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

	var rec_loc = '<? echo $_REQUEST['record_location']; ?>';
	if (rec_loc == '') {
	var record_location = document.getElementById("searchlist_record_location").value;
	}
	if (rec_loc !== '') {
	var record_location = '<? echo $_REQUEST['record_location']; ?>';
	}
	
    var source = new EventSource("functions/save_search.php?option=<? echo $_REQUEST['option']; ?>&searchterm=<? echo $_REQUEST['searchterm'] = str_replace("\"", "", $_REQUEST['searchterm']); ?>&record_location="+record_location+"&channel_id=<? echo $_REQUEST['channel_id']; ?>");
	
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
</script>
</head>
<body onload="check_channel_search()">
<a id="top"></a>
<div id="scroll_top" class="scroll_top"><a href="#" title="to top"><script language="JavaScript" type="text/javascript"> document.write ("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script></a></div>
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
              <div id="navbar_info">oldest EPG: <span class="badge"><?php echo $date_first; echo " - "; echo utf8_encode($first_entry['e2eventservicename']); ?></span> latest EPG: <span class="badge-success"><?php echo $date_latest; echo " - "; echo utf8_encode($last_entry['e2eventservicename']); ?></span> </div>
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
        <script language="JavaScript" type="text/javascript"> document.write(navbar_header);</script>
        <li> <a href="dashboard.php"><i class="fa fa-home"></i>HOME</a> </li>
        <li> <a href="search.php"><i class="fa fa-search"></i>Search</a> </li>
        <li> <a href="timer.php"><i class="fa fa-clock-o"></i>Timer</a> </li>
        <li> <a href="#"><i class="fa fa-wrench"></i>Crawler Tools<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_channel_id');"><i class="fa fa-chevron-right"></i>Crawl channel ID's</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_complete');"><i class="fa fa-chevron-right"></i>Crawl EPG from channels</a> </li>
            <li> <a href="crawl_channel_separate.php"><i class="fa fa-chevron-right"></i>Crawl channel separate</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_search');"><i class="fa fa-chevron-right"></i>Crawl search - Write timer in database</a></li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_send_timer');"><i class="fa fa-chevron-right"></i>Send timer from database to Receiver</a> </li>
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
        <li> <a href="#" onclick="animatedcollapse.toggle('div_start_channelzapper');"> <i class="fa fa-arrow-up"></i>Channel Zapper</a> </li>
        <li> <a id="116" onclick="power_control(this.id)" style="cursor:pointer;"> <i class="glyphicon glyphicon-off"></i>Wake up / Standby <span id="pc116"></span></a> </li>
      </ul>
    </div>
  </nav>
  <!-- /. NAV SIDE  -->
  <div id="page-wrapper">
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
			{ $searchterm = ""; } else { $searchterm = $searchterm; } echo $searchterm; ?>">
            <div class="spacer_10"></div>
            <div id="radio-group">
              <div id="radio1">
                <label>
                <input type="radio" name="option" value="all" id="option1_0" <? if ($option == 'all' or $option == ''){ echo "checked"; } ?>>
                all</label>
              </div>
              <div id="radio2">
                <label>
                <input type="radio" name="option" value="title" id="option1_1" <? if ($option == 'title'){ echo "checked"; } ?>>
                title</label>
              </div>
              <div id="radio3">
                <label>
                <input type="radio" name="option" value="description" id="option1_2" <? if ($option == 'description'){ echo "checked"; } ?>>
                description</label>
              </div>
              <div id="radio4">
                <label>
                <input type="radio" name="option" value="extdescription" id="option1_3" <? if ($option == 'extdescription'){ echo "checked"; } ?>>
                extended description</label>
              </div>
            </div>
            <!-- radio-group-->
            <div id="btn-group">
              <div id="btn1">
                <input type="submit" value="Search trough" class="btn btn-success"/>
              </div>
              <div id="btn2">
                <select name="record_location" id="searchlist_record_location">
                  <?php 
					$sql = "SELECT * from record_locations order by id ASC";
			
					if ($result = mysqli_query($dbmysqli,$sql))
					{
					// Fetch one and one row
					while ($obj = mysqli_fetch_object($result)) {
					{
					//set selected
					if ($obj->selected == "1")
					{
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
        if(!isset($count_search) or $count_search == "") { $count_search = ""; } else { $count_search = $count_search; }
        if ($count_search == '' ){ $count_search = '0'; } else { $count_search = $count_search; }
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
            <input type="checkbox" name="search_channel" id="search_channel" onclick="check_channel_search()" <? if ($search_channel == 'on'){ echo "checked"; } ?> />
            Search only in this channel</label>
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
</body>
</html>
