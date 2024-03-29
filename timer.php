﻿<?php 
session_start();
//
	include("inc/dashboard_config.php");
	include_once("inc/header_info.php");
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
<!-- Modal-->
<link href="assets/css/rmodal-no-bootstrap.css" rel="stylesheet" />
<!-- Noty-->
<link href="assets/css/noty/noty.css" rel="stylesheet" />
<link href="assets/css/noty/animate.css" rel="stylesheet" />
<link href="assets/css/noty/themes/mint.css" rel="stylesheet" />
<!-- Ladda -->
<link href="assets/css/ladda/ladda-themeless.min.css" rel="stylesheet">
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
</head>
<body>
<a id="top"></a>
<div id="scroll_top"><a href="#" title="top"><script>document.write("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script></a></div>
<div id="scroll_top_saved_search"><a href="#" title="Saved Search"><script>document.write ("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+saved_search_btn_size+"x\"></i>");</script></a></div>
<!--statusbar modal -->
  <span id="showModal"></span>
  <div id="bn_epg_modal" class="modal">
    <div class="modal-dialog animated">
    <div class="modal-content">
      <div id="bn-modal-header" class="modal-header"></div>
      <div class="modal-body">
        <div id="bn_epgframe"></div>
        <hr>
        <div align="right">
        <button class="btn btn-default btn-sm" type="button" onclick="bn_epg_modal.close();">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="bn_modal_service" style="display:none"></span>
<span id="bn_modal_name" style="display:none"></span>
<!--broadcast now modal -->
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
        <button class="btn btn-default btn-sm" type="button" onclick="quickpanel_modal.close();">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--quickpanel modal -->
<!--manual timer modal -->
 <span id="showModal"></span>
  <div id="manual_timer_modal" class="modal">
    <div class="modal-dialog animated">
    <div class="modal-content">
      <div id="timer-modal-header" class="modal-header">Manual timer</div>
      <div class="modal-body">
        <div id="quickpanel_timerframe"></div>
        <hr>
        <div align="right">
        <button class="btn btn-default btn-sm" type="button" onclick="manual_timer_modal.close();">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--manual timer modal -->
<!--remote control modal -->
 <span id="showModal"></span>
  <div id="remote_modal" class="modal_rc">
    <div class="modal-dialog animated">
    <div class="modal-content">
      <div class="modal-header">Remote Control <span id="rc_status"><i class="fa fa-wifi gray"></i></span>
      </div>
      <div class="modal-body">
        <div id="rc_frame"></div>
        <hr>
        <div align="right">
        <button class="btn btn-default btn-sm" type="button" onclick="remote_modal.close();">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--remote control modal -->
<div id="wrapper">
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="adjust-nav">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" onclick="nav_icon_scroll()" data-toggle="collapse" data-target=".sidebar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <span class="navbar-brand"><span style="font-size:1.1em; color:#000; cursor:pointer;" onclick="remote_modal.open();" title="Remote Control"><i class="fa fa-wifi fa-1x"></i></span> <a class="navbar-link" href="timer.php">Timer</a></span> </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <div class="row">
            <div class="col-md-12">
              <div id="navbar_info">oldest: <span class="badge"><?php echo $date_first; echo ' - '; echo utf8_encode($first_entry['e2eventservicename']); ?></span> latest: <span class="badge-success"><?php echo $date_latest; echo ' - '; echo utf8_encode($last_entry['e2eventservicename']); ?></span> <?php echo $header_date; ?></div>
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
          <li> <a href="crawl_separate.php"><i class="fa fa-chevron-right"></i>Crawl channel separate</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_channel_id');"><i class="fa fa-chevron-right"></i>Crawl channel ID's</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_complete');"><i class="fa fa-chevron-right"></i>Crawl EPG from channels</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_search');"><i class="fa fa-chevron-right"></i>Crawl Search - Write timer in database</a></li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_send_timer');"><i class="fa fa-chevron-right"></i>Send timer from database to Receiver</a> </li>
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
            <li> <a onclick="remote_modal.open();" style="cursor:pointer;"><i class="fa fa-wifi"></i>Remote Control</a> </li>
            <li> <a href="teletext.php"><i class="fa fa-globe"></i>Teletext Browser</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_start_channelzapper');"> <i class="fa fa-arrow-up"></i>Channel Zapper</a> </li>
            <li> <a href="install.php"><i class="fa fa-wrench"></i>Install</a> </li>
            <li> <a href="about.php"><i class="glyphicon glyphicon-question-sign"></i>About</a> </li>
          </ul>
        </li>
        <li class="quickpanel_inc" id="quickpanel_inc"></li>
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
          <h2>Timer</h2>
        </div>
      </div>
      <!--crawl channel id-->
      <div id="div_crawl_channel_id">
      <span class="panel-close" onclick="animatedcollapse.hide('div_crawl_channel_id')"><span aria-hidden="true">x</span></span>
        <h1>Crawl channel ID's</h1>
        <input type="submit" class="btn btn-success" id="crawl_channel_id_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_channel_id_status'); crawl_channel_id();">
        <div id="crawl_channel_id_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--crawl channel id-->
      </div>
      <!--crawl complete-->
      <div id="div_crawl_complete">
      <span class="panel-close" onclick="animatedcollapse.hide('div_crawl_complete')"><span aria-hidden="true">x</span></span>
        <h1>Crawl EPG from channels</h1>
        <input type="submit" class="btn btn-success" id="crawl_complete_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_complete_status'); crawl_complete();">
        <div id="crawl_complete_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--crawl complete-->
      <!--crawl saved search-->
      <div id="div_crawl_search">
      <span class="panel-close" onclick="animatedcollapse.hide('div_crawl_search')"><span aria-hidden="true">x</span></span>
        <h1>Crawl Search - Write timer in database</h1>
        <input type="submit" class="btn btn-success" id="crawl_search_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_search_status'); crawl_saved_search();">
        <div id="crawl_search_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!--crawl saved search-->
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
      <!--channelzapper-->
      <hr />
      <div class="row">
        <div class="col-md-12">
          <h4>
          <span id="timerlist_panel"></span>
          <div class="spacer_5"></div>
          <span id="saved_search_panel"></span>
          </h4>
          <span id="hidden_status" class="hidden"></span>
          <div id="timerlist_main">
          <div class="timer_panel">
          <span class="timerlist_checkbox"><input id="select_all" type="checkbox" onClick="select_timer_checkbox()"></span>
          <button id="load_timerlist_btn" type="button" class="ladda-button btn btn-default-2 btn-xs" data-style="expand-right" Title="Reload Timer List" onclick="load_timerlist();"> Reload</button>
          <input id="send" type="button" class="btn btn-success btn-xs" value="send" title="Send timer to Receiver" onClick="timerlist_panel(this.id)">
          <input id="hide" type="button" class="btn btn-primary btn-xs" value="hide" title="Hide timer from list" onClick="timerlist_panel(this.id)">
          <input id="delete" type="button" class="btn btn-danger btn-xs" value="delete" title="Delete timer from list" onClick="timerlist_panel(this.id)">
          <span id="del_buttons" style="display:none">
          <input id="delete_rec" type="button" class="btn btn-default btn-xs" value="from Receiver" onClick="timerlist_panel(this.id)">
          <input id="delete_db" type="button" class="btn btn-default btn-xs" value="from Database" onClick="timerlist_panel(this.id)">
          <input id="delete_both" type="button" class="btn btn-default btn-xs" value="both" onClick="timerlist_panel(this.id)">
          </span>
          <input id="panel_unhide" type="button" class="btn btn-default btn-xs" style="display:none;" value="unhide" title="Unhide selected" onClick="timerlist_panel(this.id)">
          <span id="selected_box_sum"></span>
          <span id="panel_action_status"></span>
          </div>
		  <div id="timerlist_inc"></div>
          </div>
          <!--timerlist-->
        </div>
        <!---->
      </div>
      <a id="saved_search_row"></a>
      <!-- /. ROW  -->
      <hr/>
      <div class="row">
        <div class="col-md-12">
          <h4><span id="saved_search_panel2"></span>
            <select name="select" id="sort_setting" class="sort_setting" onChange="sortby()">
              <option value="id" <?php if($search_list_sort == 'id'){ echo 'selected'; } ?>>Sort standard</option>
              <option value="searchterm" <?php if($search_list_sort == 'searchterm'){ echo 'selected'; } ?>>Sort by term</option>
              <option value="search_option" <?php if($search_list_sort == 'search_option'){ echo 'selected'; } ?>>Sort by searcharea</option>
              <option value="e2location" <?php if($search_list_sort == 'e2location'){ echo 'selected'; } ?>>Sort by record location</option>
              <option value="activ" <?php if($search_list_sort == 'activ'){ echo 'selected'; } ?>>Sort by status</option>
            </select> 
            <button type="button" id="reload_saved_search_list_btn" class="ladda-button btn btn-default-2 btn-xs" data-style="expand-right" title="Reload Search List" onClick="load_saved_search();"> Reload</button>
          </h4>
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
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.min.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="assets/js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>
<!-- Noty -->
<script src="js/noty.min.js"></script>
<script src="js/noty-msg.js"></script>
<!-- Ladda -->
<script src="assets/js/ladda/spin.min.js"></script>
<script src="assets/js/ladda/ladda.min.js"></script>
<script src="assets/js/ladda/ladda.jquery.min.js"></script>
<!-- Modal-->
<script type="text/javascript" src="js/rmodal.js"></script>
<!---->
<script>
load_timer_list_panel();
// timerlist
$(function(){
	$.post("functions/timer_list_inc.php",
	function(data){
	$("#timerlist_inc").html(data);
	});
	// saved search panel
	$.post("functions/search_list_panel.php",
	function(data){
	var obj = JSON.parse(data)
	$("#saved_search_panel").html("<span class=\"timer_panel_info\">"+obj[0].summary_total+"\
	<a style=\"cursor:pointer\" onclick=\"javascript:$('html, body').animate({ scrollTop: ($(saved_search_row).offset().top)}, 'slow');\">\
	Saved Search</a> for Auto Timer | <a style=\"cursor:pointer;\" onClick=\"quickpanel('manual_timer')\">Manual Timer</a></span>");
	//
	$("#saved_search_panel2").html(obj[0].summary_total+" Saved Search for Auto Timer | <span class=\"timer_panel_info\">\
	<span class=\"saved_search_panel_info\">"+obj[0].activ+" activ | "+obj[0].inactiv+" inactiv | </span></span>");
	});
	// saved search list
	$.post("functions/search_list_inc.php",
	function(data){
	$("#search_list").html(data);
	});
});
//
$(function(){
   var statusbar = '<?php if(!isset($_SESSION['statusbar']) or $_SESSION['statusbar'] == ''){ $_SESSION['statusbar'] = ''; } echo $_SESSION['statusbar']; ?>';
   if (statusbar == '1'){ $("#statusbar_outer").removeClass("statusbar_outer"); }
   var cookies = navigator.cookieEnabled;
   if(cookies == false){ $("#cookie_js").html("To use all functions of the website, it's required to accept cookies."); }
});
function search_list_scroll(id){ 
	if($('#list_'+id).length){ $('html, body').animate({ scrollTop: ($('#list_'+id).offset().top-50)}, 'slow'); }
}
</script>
</body>
</html>
