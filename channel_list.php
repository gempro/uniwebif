﻿<?php 
session_start();
//
	include("inc/dashboard_config.php");
	include_once("inc/header_info.php");
	
	// channel list
	$sql = "SELECT * FROM `channel_list` ORDER BY `e2servicename` ASC";
	
	// delete selected channels
	if(isset($_POST['channel_delete']))
	{
	$checkbox_delete = $_POST['checkbox_delete'];
	
	for($i=0;$i<count($checkbox_delete);$i++){
	
	$del_id = $checkbox_delete[$i];
	
	$sql = mysqli_query($dbmysqli, "SELECT `e2servicereference` FROM `channel_list` WHERE `id` = '".$del_id."' ");
	$result = mysqli_fetch_assoc($sql);
	$e2servicereference = $result['e2servicereference'];
	
	// delete epg from channel
	mysqli_query($dbmysqli, "DELETE FROM `epg_data` WHERE `e2eventservicereference` = '".$e2servicereference."' ");
	
	// delete channel
	mysqli_query($dbmysqli, "DELETE FROM `channel_list` WHERE `id` = '".$del_id."' ");
	mysqli_query($dbmysqli, "OPTIMIZE TABLE `channel_list`");
	}
	if($result){
	Header('Location: channel_list.php');
	exit();
	}
	}
	
	// select crawl
	if(isset($_POST['select_all_crawl']))
	{
	mysqli_query($dbmysqli, "UPDATE `channel_list` SET `crawl` = '1' ");
	Header('Location: channel_list.php');
	exit();
	}
	
	// unselect all crawl
	if(isset($_POST['unselect_all_crawl']))
	{
	mysqli_query($dbmysqli, "UPDATE `channel_list` SET `crawl` = '0' ");
	Header('Location: channel_list.php');
	exit();
	}
	
	// select zap
	if(isset($_POST['select_all_zap']))
	{
	mysqli_query($dbmysqli, "UPDATE `channel_list` SET `zap` = '1' ");
	Header('Location: channel_list.php');
	exit();
	}
	
	// unselect all zap
	if(isset($_POST['unselect_all_zap']))
	{
	mysqli_query($dbmysqli, "UPDATE `channel_list` SET `zap` = '0' ");
	Header('Location: channel_list.php');
	exit();
	}

	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	if($obj->e2providername == 'IPTV;')
	{
	$obj->e2servicereference = rawurldecode($obj->e2servicereference);
	$obj->e2servicereference = str_replace('%3a', ':', $obj->e2servicereference);
	$e2service_reference_title = $obj->e2servicereference;
	$obj->e2servicereference = str_replace('4097:0:1:0:0:0:0:0:0:0:', '', $obj->e2servicereference);
	if(strlen($obj->e2servicereference) > '30'){ $obj->e2servicereference = substr($obj->e2servicereference, 0, 30); $obj->e2servicereference .= ' ... '; }
	$chkbox = 'disabled'; 
	} else { $chkbox = ''; $e2service_reference_title = ''; }
	
	$obj->e2providername = str_replace(';', '', $obj->e2providername);
	
	if($obj->crawl == '1'){ $checked_crawl = 'checked'; }
	if($obj->crawl == '0'){ $checked_crawl = ''; }
	if($obj->zap == '1'){ $checked_zap = 'checked'; }
	if($obj->zap == '0'){ $checked_zap = ''; }
	
	if(!isset($channel_list) or $channel_list == ''){ $channel_list = ''; }
	if(!isset($obj->e2providername) or $obj->e2providername == ''){ $obj->e2providername = ''; }
	
	$channel_list .= "
	<div id=\"channel_list_content\">
	<div id=\"row1\"><!--channel crawl-->
	  <input id=\"set_crawl_channel_$obj->id\" name=\"checkbox_crawl[]\" type=\"checkbox\" onClick=\"set_crawl_channel(this.id)\" $checked_crawl $chkbox>
	</div>
	<div id=\"row2\"><!--channel zap-->
	  <input id=\"set_zap_channel_$obj->id\" name=\"checkbox_zap[]\" type=\"checkbox\" onClick=\"set_zap_channel(this.id)\" $checked_zap $chkbox>
	</div>
	<div id=\"row3\"><!--channel delete-->
	  <input id=\"checkbox_del\" name=\"checkbox_delete[]\" type=\"checkbox\" value=\"$obj->id\">
	</div>
	<div id=\"row4\">$obj->e2servicename <span id=\"edit_channel_$obj->id\"></span>
	</div>
	<div id=\"row5\"><!--channel provider-->
	$obj->e2providername
	</div>
	<div id=\"row6\" title=\"$e2service_reference_title\"><!--channel provider-->
	$obj->e2servicereference
	</div>
	<div style=\"clear:both\">&nbsp;</div>
	</div>";
	}
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Uniwebif : Channel list</title>
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
<div id="scroll_top" class="scroll_top"><a href="#" title="top"><script>document.write("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script></a></div>
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
        <span class="navbar-brand"><span style="font-size:1.1em; color:#000; cursor:pointer;" onclick="remote_modal.open();" title="Remote Control"><i class="fa fa-wifi fa-1x"></i></span> <a class="navbar-link" href="channel_list.php">Channel list</a></span> </div>
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
        <script>document.write(navbar_header_channel_list)</script>
        <li> <a href="dashboard.php"><i class="fa fa-home"></i>HOME</a> </li>
        <li> <a href="search.php"><i class="fa fa-search"></i>Search</a> </li>
        <li> <a href="timer.php"><i class="fa fa-clock-o"></i>Timer & Saved Search</a> </li>
        <li> <a href="#"><i class="fa fa-wrench"></i>Crawler Tools<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
          <li> <a href="crawl_separate.php"><i class="fa fa-chevron-right"></i>Crawl channel separate</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_channel_id');"><i class="fa fa-chevron-right"></i>Crawl channel ID's</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_complete');"><i class="fa fa-chevron-right"></i>Crawl EPG from channels</a> </li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_crawl_search');"><i class="fa fa-chevron-right"></i>Crawl Search - Write timer in database</a></li>
            <li> <a href="#" onclick="animatedcollapse.toggle('div_send_timer');"><i class="fa fa-chevron-right"></i>Send timer from database to Receiver</a> </li>
          </ul>
        </li>
        <li role="presentation" class="active"> <a href="#"><i class="fa fa-cog"></i>Settings<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="settings.php"><i class="fa fa-cog"></i>Main Settings</a> </li>
            <li> <a href="channel_list.php"><i class="fa fa-list"></i><strong>Channel list</strong></a> </li>
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
          <h2>Channel list</h2>
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
          <form name="form1" method="post" action="">
            <div id="channel-list-button-group">
              <div id="row1"><input name="select_all_crawl" type="submit" class="btn btn-xs btn-success" value="select all">
              </div>
              <div id="row2"><input name="unselect_all_crawl" type="submit" class="btn btn-xs btn-success" value="unselect all">
              </div>
              <div id="row3">
              Channel to crawl
              </div>
              <div style="clear:both"></div>
            </div>
            <div class="spacer_10"></div>
            <div id="channel-list-button-group">
              <div id="row1"><input name="select_all_zap" type="submit" class="btn btn-xs btn-primary" value="select all">
              </div>
              <div id="row2"><input name="unselect_all_zap" type="submit" class="btn btn-xs btn-primary" value="unselect all">
              </div>
              <div id="row3">
                Channel for zapper 
              </div>
              <div style="clear:both"></div>
            </div>
            <div class="spacer_10"></div>
            <div id="channel-list-button-group">
              <div id="row1"><input name="channel_delete" type="submit" class="btn btn-xs btn-danger" value="delete selected">
              </div>
              <div id="row2">
              </div>
              <div id="row3">Delete channel from list</div>
              <div style="clear:both"></div>
               <div class="spacer_10"></div>
              <div class="row">
              <div class="col-md-4">Channel Name:<input id="channel_name" type="text" class="form-control" size="10" tabindex="1">
              <div class="spacer_5"></div>
              </div>
              <div class="col-md-4">Service Reference:<span style="float:right;">
              <input id="iptv_channel" type="checkbox" tabindex="3"> IPTV
              <i style="cursor:default;" class="glyphicon glyphicon-question-sign fa-1x" title="Format: http://domain.com/stream.m3u8 or rtsp://<?php echo $box_ip; ?>:554/stream" ></i></span>
              <input id="service_reference" type="text" class="form-control" size="50" tabindex="2">
              <div class="spacer_5"></div>
              </div>
              <div class="col-md-4"></div>
              </div><!-- ROW -->
              <div class="row">
              <div class="col-md-3">
              <div class="spacer_5"></div>
              <a onclick="add_single_channel()" class="btn btn-default" tabindex="4">Add channel</a>
              <span id="add_single_channel_status"></span>
              </div>
              </div><!-- ROW -->
            </div>
            <hr>
            <div id="channel_list">
			<?php if(!isset($channel_list) or $channel_list == ''){ $channel_list = ''; } else { echo utf8_encode($channel_list); } ?>
            </div>
            <!-- Channel list -->
          </form>
        </div>
      </div>
      <!-- /. ROW  -->
      <hr />
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
   var statusbar = '<?php if(!isset($_SESSION['statusbar']) or $_SESSION['statusbar'] == ''){ $_SESSION['statusbar'] = ''; } echo $_SESSION['statusbar']; ?>';
   if (statusbar == '1'){ $("#statusbar_outer").removeClass("statusbar_outer"); }
   //
   var cookies = navigator.cookieEnabled;
   if(cookies == false){ $("#cookie_js").html("To use all functions of the website, it's required to accept cookies."); }
});
</script>
</body>
</html>
