<?php 
session_start();
//
include("inc/dashboard_config.php");
include_once("inc/header_info.php");

	// count timer
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_timer FROM `timer` WHERE `expired` = "0" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_timer);
	$stmt->fetch();
	$stmt->close();
	$count_timer = $count_timer.' Timer in Database';
	
	// sent timer
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as sent_timer FROM `timer` WHERE `expired` = "0" AND `status` = "sent" OR `expired` = "0" AND `status` = "manual" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($sent_timer);
	$stmt->fetch();
	$stmt->close();
	if ($sent_timer > 0){ 
	$show_sent_timer = ' | <span class="timer_panel_info">'.$sent_timer.' sent | </span>'; 
	} else { $show_sent_timer = ' | <span class="timer_panel_info">0 sent | </span>';
	}
	
	// timer today
	$start = date("d.m.Y, 00:00", $time);
	$end = date("d.m.Y, 23:59", $time);
	$start_time = strtotime($start);
	$end_time = strtotime($end);
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as timer_today FROM `timer` WHERE `e2eventstart` BETWEEN "'.$start_time.'" AND "'.$end_time.'" AND `expired` = "0" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($timer_today);
	$stmt->fetch();
	$stmt->close();
	if ($sent_timer > 0){
	$show_timer_today = ' <span class="timer_panel_info">'.$timer_today.' today | </span>'; 
	} else { $show_timer_today = ' <span class="timer_panel_info">0 today | </span>'; 
	}
	
	// hidden timer
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as hidden_timer FROM `timer` WHERE `expired` = "0" AND `hide` = "1" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($hidden_timer);
	$stmt->fetch();
	$stmt->close();
	if ($hidden_timer > 0){ 
	$show_hidden_timer = ' <span class="timer_panel_info">
	<a id="show_unhide" onclick="timerlist_panel(this.id)" title="show" style="cursor:pointer;">'.$hidden_timer.' hidden</a></span>'; 
	} else { 
	$show_hidden_timer = '<span class="timer_panel_info"> 0 hidden</span>';
	}
	
	// count saved search
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) as count_saved_search FROM `saved_search` ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	$stmt->execute();
	$stmt->bind_result($count_saved_search);
	$stmt->fetch();
	$stmt->close();
	$count_saved_search = '('.$count_saved_search.')';
	
	// timer on receiver
	$xmlfile = ''.$url_format.'://'.$box_ip.'/web/timerlist';
	$getTimer = file_get_contents($xmlfile, false, $webrequest);
	$sum = preg_match_all("#<e2timerlist>(.*?)</e2timerlist>#si", $getTimer, $match_sum);
	$timer_summary = preg_match_all("#<e2timer>(.*?)</e2timer>#si", $match_sum[0][0]);
	
	$receiver_timer = ' <span class="timer_panel_info">| '.$timer_summary.' on Receiver</span>';
	
//close db
mysqli_close($dbmysqli);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Uniwebif : Timer &amp; Saved Search</title>
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
animatedcollapse.addDiv('save_box_info', 'fade=1,height=auto')
animatedcollapse.addDiv('save_box_info_status', 'fade=1,height=auto')
animatedcollapse.addDiv('save_box_settings_status', 'fade=1,height=auto')
animatedcollapse.addDiv('save_bouquet_settings_status', 'fade=1,height=auto')
animatedcollapse.addDiv('save_bouquets_btn', 'fade=1,height=auto')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
//$: Access to jQuery
//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
//state: "block" or "none", depending on state
}
animatedcollapse.init()
</script>
<script>
// load timerlist
$(document).ready(function(){
	$.post("functions/timer_list_inc.php",
	function(data){
	$("#timerlist_inc").html(data);
	});
});
// display channel list
$(window).load(function() {
	$.post("functions/search_list_inc.php",
	function(data){
	// write data in container
	$("#search_list").html(data);
	}
	);
});
function sortby(){
var search_list_sort = document.getElementById("sort_setting").value;
	$.post("functions/search_list_inc.php?sort_list="+search_list_sort+"",
	function(data){
	// write data in container
	$("#search_list").html(data);
	});
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
        <a class="navbar-brand" href="timer.php"><i class="fa fa-square-o"></i>&nbsp;Timer</a> </div>
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
        <script>document.write(navbar_header_timer)</script>
        <li> <a href="dashboard.php"><i class="fa fa-home"></i>HOME</a> </li>
        <li> <a href="search.php"><i class="fa fa-search"></i>Search</a> </li>
        <li> <a href="timer.php"><i class="fa fa-clock-o"></i><strong>Timer & Saved Search</strong></a> </li>
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
  <div id="statusbar_cnt_outer" class="statusbar_cnt_outer">
  <div id="statusbar_cnt"></div>
  </div>
  </div>
  </div><!-- /. ROW  -->
    <div id="page-inner">
      <div class="row">
        <div class="col-md-12">
          <h2>Timer</h2>
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
          <h4><?php echo $count_timer; echo $show_sent_timer; echo $show_timer_today; echo $show_hidden_timer; echo $receiver_timer; ?> </h4>
          <div id="timerlist_main">
          <div class="timer_panel">
          <span class="timerlist_checkbox"><input id="select_all" type="checkbox" onClick="select_timer_checkbox()"></span>
          <input id="send" type="button" class="btn btn-default btn-success btn-xs" value="send" title="send Timer to Receiver" onClick="timerlist_panel(this.id)">
          <input id="hide" type="button" class="btn btn-primary btn-xs" value="hide" title="hide Timer from list" onClick="timerlist_panel(this.id)">
          <input id="delete" type="button" class="btn btn-default btn-danger btn-xs" value="delete" onClick="timerlist_panel(this.id)">
          <span id="del_buttons" style="display:none">
          <input id="delete_rec" type="button" class="btn btn-default btn-xs" value="from Receiver" onClick="timerlist_panel(this.id)">
          <input id="delete_db" type="button" class="btn btn-default btn-xs" value="from Database" onClick="timerlist_panel(this.id)">
          <input id="delete_both" type="button" class="btn btn-default btn-xs" value="both" onClick="timerlist_panel(this.id)">
          </span>
          <input id="unhide" type="button" class="btn btn-default btn-xs hidden" value="unhide" title="unhide selected" onClick="timerlist_panel(this.id)">
          <span id="selected_box_sum"></span>
          <span id="panel_action_status"></span>
          </div>
		  <div id="timerlist_inc"></div>
          </div>
          <!--timerlist-->
        </div>
        <!---->
      </div>
      <!-- /. ROW  -->
      <hr />
      <div class="row">
        <div class="col-md-12">
          <h4>Saved Search <?php echo $count_saved_search; ?>
          <select name="select" id="sort_setting" class="sort_setting" onChange="sortby()">
              <option value="id" <?php if($search_list_sort == 'id'){ echo 'selected'; } ?>>sort standard</option>
              <option value="searchterm" <?php if($search_list_sort == 'searchterm'){ echo 'selected'; } ?>>sort by term</option>
            </select></h4>
          <div id="search_list"></div>
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
   $("#statusbar_cnt_outer").removeClass("statusbar_cnt_outer"); 
   $("#statusbar_cnt").html("&nbsp;");
   }
});
</script>
</body>
</html>
