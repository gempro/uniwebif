﻿<?php 
session_start();
//
include("inc/dashboard_config.php");

	// check connection
	if (mysqli_connect_errno()) {
	printf("Connection failed: %s\n", mysqli_connect_error());
	exit(); 
	}
	
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

//close db
mysqli_close($dbmysqli);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Uniwebif : All Services</title>
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
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
//$: Access to jQuery
//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
//state: "block" or "none", depending on state
}
animatedcollapse.init()

<!--
// tv services list
$(window).load(function() {
	$("#tv_services_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$.post("functions/services_inc.php",
	function(data){
	// write data in container
	$("#tv_services_list").html(data);
	}
	);
});

// zapp request
function tv_services_zapp(id) {
	
	var this_id = id.replace(/tv_services_zapp_btn_/g, "");
	
	document.getElementById("tv_services_status_zapp_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/services_zapp_request.php?e2servicereference="+this_id+"");
    source.onmessage = function(event) {
		
	document.getElementById("tv_services_status_zapp_"+this_id+"").innerHTML = "";
	document.getElementById("tv_services_zapp_btn_"+this_id+"").value = "CHANNEL ZAPP OK";
	
	this.close();
	};
	} else {
	document.getElementById("tv_services_status_zapp_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// crawl tv services
function tv_services_crawl() {

	$("#tv_services_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\"> Copying TV Services from Receiver..");
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/services_inc.php?action=crawl");
    source.onmessage = function(event) {
	
	$.post("functions/services_inc.php",
	function(data){
	// write data in container
	$("#tv_services_list").html(data);
	}
	);
	
	this.close();
	};
	} else {
	document.getElementById("tv_services_list").value = "Sorry, your browser does not support server-sent events...";
	}
}
</script>
</head>
<body>
<a id="top"></a>
<div id="scroll_top" class="scroll_top"><a href="#" title="to top"><script>document.write("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script></a></div>
<div id="wrapper">
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="adjust-nav">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" onclick="nav_icon_scroll()" data-toggle="collapse" data-target=".sidebar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="services.php"><i class="fa fa-square-o"></i>&nbsp;Services</a> </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <div class="row">
            <div class="col-md-12">
              <div id="navbar_info">oldest: <span class="badge"><?php echo $date_first; echo " - "; echo utf8_encode($first_entry['e2eventservicename']); ?></span> latest: <span class="badge-success"><?php echo $date_latest; echo " - "; echo utf8_encode($last_entry['e2eventservicename']); ?></span> </div>
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
        <script>document.write(navbar_header_tv_services)</script>
        <li> <a href="dashboard.php"><i class="fa fa-home"></i>HOME</a> </li>
        <li> <a href="search.php"><i class="fa fa-search"></i>Search</a> </li>
        <li> <a href="timer.php"><i class="fa fa-clock-o"></i>Timer & Saved Search</a> </li>
        <li class=""> <a href="#"><i class="fa fa-wrench"></i>Crawler Tools<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_channel_id');"><i class="fa fa-chevron-right"></i>Crawl Channel ID's</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_complete');"><i class="fa fa-chevron-right"></i>Crawl EPG from Channels</a> </li>
            <li> <a href="crawl_separate.php"><i class="fa fa-chevron-right"></i>Crawl Channel separate</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_search');"><i class="fa fa-chevron-right"></i>Crawl Search - Write Timer in Database</a></li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_send_timer');"><i class="fa fa-chevron-right"></i>Send Timer from Database to Receiver</a> </li>
          </ul>
        </li>
        <li role="presentation"> <a href="#"><i class="fa fa-cog"></i>Settings<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="settings.php"><i class="fa fa-cog"></i>Main Settings</a> </li>
            <li> <a href="channel_list.php"><i class="fa fa-list"></i>Channel List</a> </li>
            <li> <a href="bouquet_list.php"><i class="fa fa-list"></i>Bouquet List</a> </li>
          </ul>
        </li>
        <li> <a href="records.php"><i class="glyphicon glyphicon-record"></i>Records</a> </li>
        <li> <a id="116" onclick="power_control(this.id)" style="cursor:pointer;"> <i class="glyphicon glyphicon-off"></i>Wake up / Standby <span id="pc116"></span></a> </li>
        <li role="presentation" class="active"> <a href="#"><i class="glyphicon glyphicon-hand-right"></i>Extras<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="teletext.php"><i class="fa fa-globe"></i>Teletext Browser</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_start_channelzapper');"> <i class="fa fa-arrow-up"></i>Channel Zapper</a> </li>
            <li><a href="services.php"><i class="fa fa-list"></i><strong>All Services</strong></a> </li>
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
  <div id="statusbar_cnt_outter">
  <div id="statusbar_cnt"></div>
  </div>
  </div>
  </div><!-- /. ROW  -->
    <div id="page-inner">
      <div class="row">
        <div class="col-md-12">
          <h2>Services</h2>
          <input type="submit" onClick="tv_services_crawl()" value="Get Services from Receiver" class="btn btn-default"/>
          <span id="tv_services_status_crawl"></span>
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
        <div class="col-md-12">
          <div id="channel_list">
            <div id="tv_services_list"></div>
          </div>
          <!-- channel list -->
        </div>
      </div>
      <!-- /. ROW  -->
      <hr />
      <!-- /. ROW  -->
      <div class="row">
        <div class="col-md-12"></div>
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