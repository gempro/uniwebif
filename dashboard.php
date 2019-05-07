<?php 
session_start();
//
	include("inc/dashboard_config.php");
	include_once("inc/header_info.php");

	// primetime
	if ($time_format == '1'){
	$primetime_hh = date("H",$primetime);
	$primetime_mm = date("i",$primetime);
	}
	if ($time_format == '2'){
	$primetime_hh = date("g",$primetime);
	$primetime_mm = date("i",$primetime);
	}
	
	// count timer for display ticker
	$ticker_time_end = $time + $ticker_time;

	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `timer` WHERE `show_ticker` = "1" AND `e2eventstart` BETWEEN "'.$time.'" AND "'.$ticker_time_end.'" ');
	$result = mysqli_fetch_row($sql);
	$sum_timer = $result[0];
		
	if ($sum_timer == '0'){ $activate_ticker = "no"; } else { $activate_ticker = 'yes'; }
	
	// percent for latest epg
	$time_now = time();
	
	if(!isset($last_entry['e2eventstart']) or $last_entry['e2eventstart'] == "") { $last_entry['e2eventstart'] = ""; 
	
	} else { 
	
	$last_entry['e2eventstart'] = $last_entry['e2eventstart']; }
	
	if ($last_entry['e2eventstart'] == '')
	{
	$percent_latest = '0';
	$percent = '0';
	
	} else {
	
	$diff = $last_entry['e2eventstart'] - $time_now;
	$percent = $diff *100000 / $last_entry['e2eventstart'];
	};
	
	$percent_latest = round($percent,1);
	
	$pb1_status = 'primary';
	
	if($percent_latest > 30)
	{
	$pb1_status = 'primary';
	}
	if($percent_latest < 30)
	{
	$pb1_status = 'warning';
	}
	if($percent_latest < 10)
	{
	$pb1_status = 'danger';
	}
	
	if($percent_latest < 5 ){ $progressbar2 = '<div class="progress progress-striped active">
	<div class="progress-bar progress-bar-'.$pb1_status.'" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent_latest.'%"></div>
	&nbsp;'.$percent_latest.' %</div>'; 
	
	} else { 
	
	$progressbar2 = '<div class="progress progress-striped active">
	<div class="progress-bar progress-bar-'.$pb1_status.'" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent_latest.'%">'.$percent_latest.' %</div></div>'; }

//
function DiskSpace(){
	function getByte($bytes){
	$symbol = "Bytes";
	if($bytes > 1024){
	$symbol = " KB";
	$bytes /= 1024;
	}
	if($bytes > 1024){
	$symbol = " MB";
	$bytes /= 1024;
	}
	if($bytes > 1024){
	$symbol = " GB";
	$bytes /= 1024;
	}
	$bytes = round($bytes, 2);
	return $bytes.$symbol;
	}
	function getFreespace($path){
	if(preg_match("#^(https?|ftps?)://#si", $path)) {
	return false;
	}
	$freeBytes = disk_free_space($path);
	$totalBytes = disk_total_space($path);
	$usedBytes = $totalBytes - $freeBytes;
	
	$percentFree = 100 / $totalBytes * $freeBytes;
	$percentUsed = 100 / $totalBytes * $usedBytes;
	
	echo "<div class=\"panel panel-primary text-center no-boder bg-color-blue\">
	<div class=\"panel-body\"> <i class=\"fa fa-bar-chart-o fa-5x\"></i>";
	echo "<div class=\"spacer_10\"></div>";
	echo "Disk space: ".getByte($totalBytes)."<br />";
	echo "Used: ".getByte($usedBytes);
	printf(" (%01.2f%%)", $percentUsed);
	echo "</div>";
	echo "<div class=\"panel-footer back-footer-blue\">";
	echo "<strong>Free: ".getByte($freeBytes);
	printf(" (%01.2f%%)", $percentFree);
	echo "</strong></div></div>";
	}
	getFreespace(".");
}

function DatabaseSpace(){

	include("inc/dashboard_config.php");

	$total = 0;
	$sql = "Show Table Status";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($row = mysqli_fetch_array($result)) {	
	{
	$summary = $row["Index_length"] + $row["Data_length"];
	$total += $summary;
	}
    }
}

function getDatabaseByte($bytes){
	
	$symbol = "Bytes";
	if ($bytes > 1024){
	$symbol = " KB";
	$bytes /= 1024;
	}
	if($bytes > 1024){
	$symbol = " MB";
	$bytes /= 1024;
	}
	if($bytes > 1024){
	$symbol = " GB";
	$bytes /= 1024;
	}
	$bytes = round($bytes, 2);
	return $bytes.$symbol;
	}

	$totalBytes = $total;
	
	// count all epg entries
	include("inc/dashboard_config.php");
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `epg_data`');
	$result = mysqli_fetch_row($sql);
	$count_all_epg = $result[0];
	echo "<div class=\"alert alert-info text-center\"> <i class=\"fa fa-bar-chart-o fa-5x\"></i>";
	echo "<h3>".getByte($totalBytes)."</h3>
	<div class=\"spacer_10\"></div>EPG Entries total: <strong>$count_all_epg</strong></div>
	"; 
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Uniwebif : Dashboard</title>
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
animatedcollapse.addDiv('broadcast_main_div', 'fade=1,height=auto')
animatedcollapse.addDiv('primetime_main_today', 'fade=1,height=auto');
animatedcollapse.addDiv('channelbrowser_main_today', 'fade=1,height=auto');
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
//$: Access to jQuery
//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
//state: "block" or "none", depending on state
}
animatedcollapse.init()
</script>
<script type="text/javascript">
// load on start
	$.post("functions/progressbar.php",
	function(data){
	$("#progressbar").html(data);
	}
);
// remaining broadcast progressbar
	var reload_progressbar = '<?php echo $reload_progressbar; ?>';
	if (reload_progressbar == 1){
	var file = "functions/progressbar.php";
	var seconds_load = 180;
//
$(document).ready(function(){
    setInterval(function(){
    $('#progressbar').load(file + '?ts=' + (new Date().getTime()));
    }, (seconds_load*1000));
});
}
// ticker
document.addEventListener('DOMContentLoaded', checkWidth);
document.addEventListener('resize', checkWidth);
function checkWidth(){
if (document.querySelector('html').clientWidth > 1200) {	
	// load ticker
$(document).ready(function() {
	$.post("ticker/ticker.php",
	function(data){
	// write data in container
	$("#ticker").html(data);	
	});
});
}
};
// broadcast list main
$(document).ready(function(){
$("#broadcast_main_now_today").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$.post("functions/broadcast_list_main.php",
	{
	time: 'now_today'
	},
	function(data){
	// write data in container
	$("#broadcast_main_now_today").html(data);
	});
});
// time input broadcast list
$(function(){
	var time_format = '<?php echo $time_format; ?>';
$("#broadcast_hh").on({
    change: function () {
	var hh = $('#broadcast_hh').val();
	if (time_format == '1'){
	if(isFinite(String(hh)) == false || hh > 23 || hh < 1){ var hh = '00'; $("#broadcast_hh").val("00");
	}
	}
	if (time_format == '2') {
	if(isFinite(String(hh)) == false || hh > 12 || hh < 1){ var hh = '12'; $("#broadcast_hh").val("12");
	}
	}
   }
});
});
$(function(){
$("#broadcast_mm").on({
    change: function () {
	var mm = $('#broadcast_mm').val();
	if(isFinite(String(mm)) == false || mm > 59 || mm < 1){ var mm = '00'; $("#broadcast_mm").val("00");
	}
    }
});
});
// time input primetime list
$(function(){
	var time_format = '<?php echo $time_format; ?>';
$("#primetime_hh").on({
    change: function () {
	var hh = $('#primetime_hh').val();
	if (time_format == '1') {
	if(isFinite(String(hh)) == false || hh > 23 || hh < 1){ var hh = '00'; $("#primetime_hh").val("00");
	} else { $("#primetime_hh").removeClass("error-input"); };
	}
	if (time_format == '2') {
	if(isFinite(String(hh)) == false || hh > 12 || hh < 1){ var hh = '12'; $("#primetime_hh").val("12");
	}
	}
   }
});
});
$(function(){
$("#primetime_mm").on({
    change: function () {
	var mm = $('#primetime_mm').val();
	if(isFinite(String(mm)) == false || mm > 59 || mm < 1){ var mm = '00'; $("#primetime_mm").val("00");
	}
    }
});
});
//
$(function(){
   $("#time_format").val("<?php if (!isset($time_format) or $time_format == ""){ $time_format = '2'; } echo $time_format; ?>");
});
</script>
</head>
<body>
<a id="top"></a>
<div id="scroll_top_channelbrowser_list"><a href="#" title="to Channelbrowser"><script>document.write ("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+channelbrowser_btn_size+"x\"></i>");</script></a></div>
<div id="scroll_top_primetime_list"><a href="#" title="to Primetime"><script>document.write ("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+primetime_btn_size+"x\"></i>");</script></a></div>
<div id="scroll_top_broadcast_list"><a href="#" title="to Broadcast now"><script>document.write ("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+broadcast_btn_size+"x\"></i>");</script></a></div>
<div id="scroll_top"><a href="#" title="to top"><script>document.write("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script></a></div>
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
        <a class="navbar-brand" href="dashboard.php"><i class="fa fa-square-o"></i>&nbsp;Dashboard</a> </div>
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
	  <script>document.write(navbar_header_dashboard)</script>
        <li> <a href="dashboard.php"><i class="fa fa-home"></i><strong>HOME</strong></a> </li>
        <li> <a href="search.php"><i class="fa fa-search"></i>Search</a> </li>
        <li> <a href="timer.php"><i class="fa fa-clock-o"></i>Timer & Saved Search</a> </li>
        <li> <a href="#"><i class="fa fa-wrench"></i>Crawler Tools<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_channel_id');"><i class="fa fa-chevron-right"></i>Crawl Channel ID's</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_complete');"><i class="fa fa-chevron-right"></i>Crawl EPG from Channels</a> </li>
            <li> <a href="crawl_separate.php"><i class="fa fa-chevron-right"></i>Crawl Channel separate</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_search');"><i class="fa fa-chevron-right"></i>Crawl search - Write Timer in Database</a></li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_send_timer');"><i class="fa fa-chevron-right"></i>Send Timer from Database to Receiver</a> </li>
          </ul>
        </li>
        <li> <a href="#"><i class="fa fa-cog"></i>Settings<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="settings.php"><i class="fa fa-cog"></i>Main Settings</a> </li>
            <li> <a href="channel_list.php"><i class="fa fa-list"></i>Channel list</a> </li>
            <li> <a href="bouquet_list.php"><i class="fa fa-list"></i>Bouquet list</a> </li>
            <li> <a href="ignore_list.php"><i class="fa fa-list"></i>Ignore list</a> </li>
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
          <h2>Dashboard</h2>
        </div>
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
        <div class="col-md-4">
          <div id="broadcast_banner" style="cursor: pointer;"> <i class="fa fa-desktop fa-5x"></i>
            <h3>Broadcast now</h3>
          </div>
          <!--bc banner-->
        </div>
        <div class="col-md-4">
          <div id="primetime_banner" style="cursor: pointer;"> <i class="fa fa-film fa-5x"></i>
            <h3>Prime Time</h3>
          </div>
          <!--bc banner-->
        </div>
        <div class="col-md-4">
          <div id="channelbrowser_banner" style="cursor: pointer;"> <i class="fa fa-globe fa-5x"></i>
            <h3>Channel Browser</h3>
          </div>
          <!--bc banner-->
        </div>
      </div>
      <hr />
      <div class="row">
        <div class="col-md-3"> <?php echo DiskSpace(); ?> </div>
        <div class="col-md-3"> <?php echo DatabaseSpace(); ?> </div>
        <div class="col-md-6">
          <h5>EPG INFORMATION</h5>
          <div id="progressbar"><img src="images/loading.gif" width="16" height="16"></div>
          Time left to latest EPG entry
          <div id="progressbar2"> <?php echo $progressbar2; ?> </div>
        </div>
      </div>
      <hr />
      <div class="row" <?php if ($timer_ticker == '1' and $activate_ticker != 'no'){  echo 'style=""'; } else { echo 'style="display:none;"'; } ?>>
        <div class="col-md-12">
          <div id="ticker_content">
            <div id="ticker"></div>
          </div>
          <!-- ticker content -->
          <hr />
        </div>
        <!-- col -->
      </div>
      <!-- row -->
      <a name="broadcast_list" id="broadcast_list"></a>
      <input id="time_format" type="hidden" value="">
      <div class="row">
        <div class="col-md-12">
          <h5>Broadcast</h5>
          <ul class="nav nav-tabs">
            <li class="active">
              <button id="now_today" href="#display_now_today" class="btn btn-default nav-btn-a" onClick="broadcast_main(this.id);" data-toggle="tab">Broadcast now</button>
            </li>
            <li class="">
              <button id="time_forward" href="#display_time_forward" class="btn btn-default nav-btn-a" onClick="broadcast_main(this.id);" data-toggle="tab"><?php echo $dur_up_broadcast/60; ?> min +</button>
            </li>
            <li class="">
              <button id="time_backward" href="#display_time_backward" class="btn btn-default nav-btn-a" onClick="broadcast_main(this.id);" data-toggle="tab"><?php echo $dur_up_broadcast/60; ?> min -</button>
            </li>
            <li class="">
              <button id="day_forward" href="#display_day_forward" class="btn btn-default nav-btn-a" onClick="broadcast_main(this.id)" data-toggle="tab">Day +</button>
            </li>
            <li class="">
              <button id="day_backward" href="#display_day_backward" class="btn btn-default nav-btn-a" onClick="broadcast_main(this.id)" data-toggle="tab">Day -</button>
            </li>
            <li id ="browse_time_panel">
            <div class="spacer_5"></div>
            <span>&nbsp;Time: 
            <input id="broadcast_hh" class="basic-input" type="text" size="2" maxlength="2" placeholder="hh">
            <input id="broadcast_mm" class="basic-input" type="text" size="2" maxlength="2" placeholder="mm">
			<?php if($time_format == '2'){ 
			echo '<select id="broadcast_am_pm">
              <option value="AM">AM</option>
              <option value="PM">PM</option>
            </select>'; } 
			?>  
            <button id="show_time" href="#display_broadcast_browse_time" class="btn btn-xs btn-default" onClick="broadcast_show_time(this.id)" data-toggle="tab">show</button>
            </span>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active in" id="display_now_today">
              <h4>Broadcast now</h4>
              <div id="broadcast_main_now_today"></div>
              <!--broadcast_main_now_today-->
            </div>
            <div class="tab-pane fade" id="display_day_forward">
              <h4>Broadcast</h4>
              <div id="broadcast_main_day_forward"> </div>
              <!--broadcast_main_day_forward-->
            </div>
            <div class="tab-pane fade" id="display_day_backward">
              <h4>Broadcast</h4>
              <div id="broadcast_main_day_backward"> </div>
              <!--broadcast_main_day_forward-->
            </div>
            <div class="tab-pane fade" id="display_time_forward">
              <h4>Broadcast</h4>
              <div id="broadcast_main_time_forward"> </div>
              <!--broadcast_main_time_forward-->
            </div>
            <div class="tab-pane fade" id="display_time_backward">
              <h4>Broadcast</h4>
              <div id="broadcast_main_time_backward"> </div>
              <!--broadcast_main_time_backward-->
            </div>
            <div class="tab-pane fade" id="display_broadcast_browse_time">
              <h4>Broadcast</h4>
              <div id="broadcast_browse_time"> </div>
              <!--broadcast_main_time_forward-->
            </div>
          </div>
        </div>
      </div>
      <a name="primetime_list" id="primetime_list" value="<?php echo $time_format; ?>"></a>
      <!-- /. ROW  -->
      <hr />
      <div class="row">
        <div class="col-md-12">
          <h5>Primetime</h5>
          <span id="pt_status" style="display:none;"></span>
          <ul class="nav nav-tabs">
            <li class="active">
              <button id="primetime_today" href="#display_primetime_today" class="btn btn-default nav-btn-a" onClick="primetime_main(this.id); animatedcollapse.show('primetime_main_today');" data-toggle="tab">Primetime today</button>
            </li>
            <li class="">
              <button id="primetime_day_forward" href="#display_primetime_day_forward" class="btn btn-default nav-btn-a" onClick="primetime_main(this.id); animatedcollapse.show('primetime_main_today');" data-toggle="tab">Day +</button>
            </li>
            <li class="">
              <button id="primetime_day_backward" href="#display_primetime_day_backward" class="btn btn-default nav-btn-a" onClick="primetime_main(this.id); animatedcollapse.show('primetime_main_today');" data-toggle="tab">Day -</button>
            </li>
            <li class="">
            <div class="spacer_5"></div>
            <span>&nbsp;Set Primetime: 
            <input id="primetime_hh" class="basic-input" type="text" size="2" maxlength="2" value="<?php echo $primetime_hh; ?>" placeholder="hh">
            <input id="primetime_mm" class="basic-input" type="text" size="2" maxlength="2" value="<?php echo $primetime_mm; ?>" placeholder="mm">
			<?php if ($time_format == '2'){
			  if(date("A", $primetime) == 'AM'){ $selected0 = 'selected'; } else { $selected0 = ''; }
			  if(date("A", $primetime) == 'PM'){ $selected1 = 'selected'; } else { $selected1 = ''; }
			  echo '<select id="primetime_am_pm">
              <option value="AM" '.$selected0.'>AM</option>
              <option value="PM" '.$selected1.'>PM</option>
			  </select>'; } 
			  ?>
              <input id="set_primetime" class="btn btn-xs btn-default" type="button" onclick="set_primetime()" value="set">
              <span id="set_status"></span>
              </span>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active in" id="display_primetime_today">
              <h4>Primetime</h4>
              <div id="primetime_main_today"></div>
              <!--primetime_main_today-->
            </div>
            <div class="tab-pane fade" id="display_primetime_day_forward">
              <h4>Primetime</h4>
              <div id="primetime_main_day_forward"> </div>
              <!--primetime_main_day_forward-->
            </div>
            <div class="tab-pane fade" id="display_primetime_day_backward">
              <h4>Primetime</h4>
              <div id="primetime_main_day_backward"> </div>
              <!--primetime_main_day_forward-->
            </div>
            <!---->
          </div>
        </div>
        <div class="col-md-12"> <a name="channelbrowser_list" id="channelbrowser_list"></a>
          <hr />
          <h5>Channel Browser</h5>
          <ul class="nav nav-tabs">
            <li>
              <select name="channel_id" id="channel_id" class="form-control">
                <?php 
				// get channels
            	$sql = "SELECT * FROM `channel_list` ORDER BY `e2servicename` ASC";
				
				if ($result = mysqli_query($dbmysqli,$sql))
				{
				while ($obj = mysqli_fetch_object($result)) {
				{
				// set selected
				if($obj->cb_selected == "1")
				{
				$select = "selected=\"selected\"";
				}
				elseif ($obj->cb_selected == "0")
				{
				$select = "";
				}
				echo utf8_encode("<option value='$obj->e2servicereference' $select>$obj->e2servicename</option>"); }    
				}
				}
				?>
              </select>
            </li>
            <li class="active">
              <button id="cb_now_today" href="#cb_display_now_today" class="btn btn-default nav-btn-b" onClick="channelbrowser_main(this.id);" data-toggle="tab">Broadcast today</button>
            </li>
            <li class="">
              <button id="cb_day_forward" href="#cb_display_day_forward" class="btn btn-default nav-btn-b" onClick="channelbrowser_main(this.id)" data-toggle="tab">Day +</button>
            </li>
            <li class="">
              <button id="cb_day_backward" href="#cb_display_day_backward" class="btn btn-default nav-btn-b" onClick="channelbrowser_main(this.id)" data-toggle="tab">Day -</button>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active in" id="cb_display_now_today">
              <h4>Broadcast</h4>
              <div id="channelbrowser_main_cb_now_today"> </div>
              <!--channelbrowser_main_now_today-->
            </div>
            <div class="tab-pane fade" id="cb_display_day_forward">
              <h4>Broadcast</h4>
              <div id="channelbrowser_main_cb_day_forward"> </div>
              <!--channelbrowser_main_day_forward-->
            </div>
            <div class="tab-pane fade" id="cb_display_day_backward">
              <h4>Broadcast</h4>
              <div id="channelbrowser_main_cb_day_backward"> </div>
              <!--channelbrowser_main_day_forward-->
            </div>
            <!---->
          </div>
        </div>
        <hr />
        <div class="col-md-12">
          <hr />
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
