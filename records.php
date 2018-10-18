<?php 
session_start();
//
	include("inc/dashboard_config.php");
	include_once("inc/header_info.php");

	// record locations
	$sql = "SELECT * FROM `record_locations` ORDER BY `id` ASC";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){	
	{
	if(!isset($rec_dropdown_broadcast) or $rec_dropdown_broadcast == "") { $rec_dropdown_broadcast = ""; }
	$rec_dropdown_broadcast = $rec_dropdown_broadcast."<option value=\"$obj->e2location\">$obj->e2location</option>"; }
	}
	}
	// devices
	$sql = "SELECT * FROM `device_list` ORDER BY `id` ASC";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){
	{
	$id = $obj->id;
	$device_description = rawurldecode($obj->device_description);
	if(!isset($device_dropdown) or $device_dropdown == ""){ $device_dropdown = ""; }
	$device_dropdown = $device_dropdown."<option value=\"$id\">$device_description</option>"; 
	}
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Uniwebif : Records</title>
<!-- BOOTSTRAP STYLES-->
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<!-- FONTAWESOME STYLES-->
<link href="assets/css/font-awesome.css" rel="stylesheet" />
<!-- CUSTOM STYLES-->
<link href="assets/css/custom.css" rel="stylesheet" />
<link href="assets/css/rmodal-no-bootstrap.css" rel="stylesheet" />
<!--noty-->
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
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
//$: Access to jQuery
//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
//state: "block" or "none", depending on state
}
animatedcollapse.init();
//
function load_device_record_location(){
	var device = $("#select_device").val();
	$.post("functions/device_rec_location.php",
	{
	device: device
	},
	function(data){
	$("#rec_location").html(data);
	$("#record_list").fadeOut();
	$("#storage_info").fadeOut();
	$("#storage_info_status").val("0");
	});
}
</script>
</head>
<body>
<a id="top"></a>
<div id="scroll_top" class="scroll_top"><a href="#" title="to top"><script>document.write("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script></a></div>
<!--statusbar modal -->
 <span id="showModal"></span>
  <div id="modal" class="modal">
    <div class="modal-dialog animated">
    <div class="modal-content">
      <div class="modal-header">EPG</div>
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
<div id="wrapper">
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="adjust-nav">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" onclick="nav_icon_scroll()" data-toggle="collapse" data-target=".sidebar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="records.php"><i class="fa fa-square-o"></i>&nbsp;Records</a> </div>
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
        <script>document.write(navbar_header_records)</script>
        <li> <a href="dashboard.php"><i class="fa fa-home"></i>HOME</a> </li>
        <li> <a href="search.php"><i class="fa fa-search"></i>Search</a> </li>
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
        <li role="presentation"> <a href="#"><i class="fa fa-cog"></i>Settings<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="settings.php"><i class="fa fa-cog"></i>Main Settings</a> </li>
            <li> <a href="channel_list.php"><i class="fa fa-list"></i>Channel List</a> </li>
            <li> <a href="bouquet_list.php"><i class="fa fa-list"></i>Bouquet List</a> </li>
          </ul>
        </li>
        <li> <a href="records.php"><i class="glyphicon glyphicon-record"></i><strong>Records</strong></a> </li>
        <li> <a id="116" onclick="power_control(this.id)" style="cursor:pointer;"> <i class="glyphicon glyphicon-off"></i>Wake up / Standby <span id="pc116"></span></a> </li>
        <li> <a href="#"><i class="glyphicon glyphicon-hand-right"></i>Extras<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
          	<li> <a href="services.php"><i class="fa fa-list"></i>All Services</a> </li>
            <li> <a href="teletext.php"><i class="fa fa-globe"></i>Teletext Browser</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_start_channelzapper');"> <i class="fa fa-arrow-up"></i>Channel Zapper</a> </li>
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
  <div id="statusbar_outer" class="statusbar_outer">
  <div id="statusbar_cnt">&nbsp;</div>
  </div>
  </div>
  </div>
    <div id="page-inner">
    <div class="row">
    <div id="cookie_js" class="col-md-12" style="color:#FF0000;">
    <noscript>To use all functions of the website, it's required to activate JavaScript.</noscript>
    </div>
    </div>
      <div class="row">
        <div class="col-md-12">
          <h2>Records</h2>
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
      <!--div_crawl_complete-->
      <!--crawl complete-->
      <div id="div_crawl_complete">
      <span class="panel-close" onclick="animatedcollapse.hide('div_crawl_complete')"><span aria-hidden="true">x</span></span>
        <h1>Crawl EPG from channels</h1>
        <input type="submit" class="btn btn-success" id="crawl_complete_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_complete_status'); crawl_complete();">
        <div id="crawl_complete_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--div_crawl_complete-->
      <!--crawl mysearch id-->
      <div id="div_crawl_search">
      <span class="panel-close" onclick="animatedcollapse.hide('div_crawl_search')"><span aria-hidden="true">x</span></span>
        <h1>Crawl search - Write timer in database</h1>
        <input type="submit" class="btn btn-success" id="crawl_search_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_search_status'); crawl_saved_search();">
        <div id="crawl_search_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--div_mysearch-->
      <!--send timer-->
      <div id="div_send_timer">
      <span class="panel-close" onclick="animatedcollapse.hide('div_send_timer')"><span aria-hidden="true">x</span></span>
        <h1>Send timer from database to Receiver</h1>
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
      <!--div_channelzapper-->
      <hr />
      <div class="row">
      <div class="col-md-3">
          <select id="select_device" class="form-control rec_location_dd" onchange="load_device_record_location(this.val)">
          <option value="0" selected>default</option>
            <?php if(!isset($device_dropdown) or $device_dropdown == ""){ $device_dropdown = ""; } echo $device_dropdown; ?>
          </select>
        </div>
        <div class="col-md-4">
          <select id="rec_location" class="form-control rec_location_dd">
            <?php if(!isset($rec_dropdown_broadcast) or $rec_dropdown_broadcast == ""){ $rec_dropdown_broadcast = ""; } echo $rec_dropdown_broadcast; ?>
          </select>
        </div>
        <div class="col-md-5">
          <button class="btn btn-default" onClick="browse_records();" data-toggle="tab">Show records</button>
          <button class="btn btn-default" onClick="reload_rec_location();" data-toggle="tab">Reload folders</button>
          <span id="rec_folder_status"></span>
        </div>
        <div class="col-md-3"></div>
      </div>
      <!-- /. ROW  -->
      <hr />
      <div class="row">
        <div class="col-md-12">
        <div id="storage_info" style="color:#ccc;"></div>
          <div id="record_list"></div>
          <input type="text" id="storage_info_status" value="0" hidden>
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
<!-- noty -->
<script src="js/noty.min.js"></script>
<script src="js/noty-msg.js"></script>
<!--modal-->
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
