﻿<?php 
session_start();
//
include("inc/dashboard_config.php");
include_once("inc/header_info.php");

	// crawler time
	if ($time_format == '1'){
	$crawler_hh = date("H",$crawler_timestamp);
	$crawler_mm = date("i",$crawler_timestamp);
	}
	if ($time_format == '2'){
	$crawler_hh = date("g",$crawler_timestamp);
	$crawler_mm = date("i",$crawler_timestamp);
	}
	
	// cz time
	if ($time_format == '1'){
	$cz_hh = date("H",$cz_timestamp);
	$cz_mm = date("i",$cz_timestamp);
	}
	if ($time_format == '2'){
	$cz_hh = date("g",$cz_timestamp);
	$cz_mm = date("i",$cz_timestamp);
	}
	
	// get settings
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `settings`");
	$settings = mysqli_fetch_assoc($sql);
	$cz_wait_time = $settings['cz_wait_time'];
	
	// calculate work time
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) AS sum_zap_channels FROM `channel_list` WHERE `zap` = "1" ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
	
	$stmt->execute();
	$stmt->bind_result($sum_zap_channels);
	$stmt->fetch();
	$stmt->close();	
	$cz_worktime = $sum_zap_channels*$cz_wait_time+10;
	
	// read device info
	$sql2 = mysqli_query($dbmysqli, "SELECT * FROM `box_info`");
	$result = mysqli_fetch_assoc($sql2);
	if(!isset($result['e2enigmaversion']) or $result['e2enigmaversion'] == "") { $result['e2enigmaversion'] = ""; } else { $result['e2enigmaversion'] = 'OS:<br>'.$result['e2enigmaversion']; }
	if(!isset($result['e2imageversion']) or $result['e2imageversion'] == "") { $result['e2imageversion'] = ""; } else { $result['e2imageversion'] = 'Image:<br>'.$result['e2imageversion']; }
	if(!isset($result['e2webifversion']) or $result['e2webifversion'] == "") { $result['e2webifversion'] = ""; } else { $result['e2webifversion'] = 'Webinterface:<br>'.$result['e2webifversion']; }
	if(!isset($result['e2model']) or $result['e2model'] == "") { $result['e2model'] = ""; } else { $result['e2model'] = 'Device:<br>'.$result['e2model']; }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Uniwebif : Settings</title>
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
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
//$: Access to jQuery
//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
//state: "block" or "none", depending on state
}
animatedcollapse.init();
//
$(function(){
   $("#display_time_format").val("<?php if (!isset($time_format) or $time_format == ""){ $time_format = '2'; } echo $time_format; ?>");
});
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
        <a class="navbar-brand" href="settings.php"><i class="fa fa-square-o"></i>&nbsp;Settings</a> </div>
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
        <script>document.write(navbar_header_settings)</script>
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
        <li role="presentation" class="active"> <a href="#"><i class="fa fa-cog"></i>Settings<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="settings.php"><i class="fa fa-cog"></i><strong>Main Settings</strong></a> </li>
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
          <h2>Settings</h2>
          <input id="display_time_format" type="hidden" value="">
        </div>
      </div>
      <!--crawl channel id-->
      <div id="div_crawl_channel_id">
        <h1>Crawl channel ID's</h1>
        <input type="submit" class="btn btn-success" id="crawl_channel_id_btn" value="Click to confirm" onclick="animatedcollapse.show('crawl_channel_id_status'); crawl_channel_id();">
        <div id="crawl_channel_id_status"><img src="images/loading.gif" width="16" height="16" align="absmiddle"> </div>
        <!--status-->
      </div>
      <!-- crawl channel id-->
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
      <div class="row"></div>
      <!-- /. ROW  -->
      <hr />
      <div class="row">
        <div class="col-md-4">
          <h5>Receiver Login</h5>
          <div class="form-group">
            <p class="help-block">IP from Receiver:</p>
            <input id="box_ip" class="form-control" maxlength="50" value="<?php echo $settings['box_ip']; ?>" />
            <div class="spacer_20"></div>
            <p class="help-block">Username from Receiver:</p>
            <input id="box_user" class="form-control" maxlength="50" value="<?php echo $settings['box_user']; ?>" />
            <div class="spacer_20"></div>
            <p class="help-block">Password from Receiver:</p>
            <input id="box_password" class="form-control" type="password" maxlength="50" value="<?php echo $settings['box_password']; ?>" />
          </div>
        </div>
        <div class="col-md-4">
          <div class="spacer_80"></div>
          <a onclick="save_box_settings(); save_settings(); animatedcollapse.show('save_box_settings_status')" class="btn btn-success btn-lg btn-block">SAVE SETTINGS</a>
          <div id="save_box_settings_status"></div>
          <div id="save_box_info"><a onclick="save_rec_locations(); animatedcollapse.show('save_box_info_status')" class="btn btn-primary btn-lg btn-block" title="Copy bouquets and record locations to database">Copy Receiver data</a></div>
          <div id="save_box_info_status">
            <div id="save_bouquet_data_status"></div>
          </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-3">
          <h5>Receiver Info</h5>
          <p><?php echo $result['e2enigmaversion']; ?></p>
          <p><?php echo $result['e2imageversion']; ?></p>
          <p><?php echo $result['e2webifversion']; ?></p>
          <p><?php echo $result['e2model']; ?></p>
        </div>
      </div>
      <hr />
      <div class="row">
        <div class="col-md-4">
          <h5>Crawler</h5>
          <div class="form-group"> How many <strong>EPG entries</strong> per channel should be write in database:
            <input id="epg_entries_per_channel" size="4" maxlength="4" value="<?php echo $settings['epg_entries_per_channel']; ?>" />
            <div class="spacer_10"></div>
            How many <strong>Channels/Services</strong> per Bouquet should be write in database:
            <input id="channel_entries" size="4" maxlength="4" value="<?php echo $settings['channel_entries']; ?>" />
            <div class="spacer_10"></div>
            <input type="checkbox" name="" id="search_crawler" onclick="" <?php if ($settings['search_crawler'] == '1'){ echo "checked"; } ?> />
            <strong>Activate</strong> automatic Search Crawler
            <div class="spacer_10"></div>
            <input type="checkbox" name="" id="epg_crawler" onclick="" <?php if ($settings['epg_crawler'] == '1'){ echo "checked"; } ?> />
            <strong>Activate</strong> automatic EPG Crawler
            <div class="spacer_10"></div>
            <input type="checkbox" name="" id="dummy_timer" onclick="" <?php if ($settings['dummy_timer'] == '1'){ echo "checked"; } ?> />
            Send a <strong>dummy timer</strong> to wake up Receiver from Deep Standby, before EPG Crawler start
            <div class="spacer_10"></div>
            Start Crawler at remaining EPG entries
            <input type="text" id="start_epg_crawler" size="3" maxlength="3" value="<?php echo $settings['start_epg_crawler']?>">
            <div class="spacer_10"></div>
            Set time when EPG Crawler should start
            <div class="spacer_5"></div>
            Hour:
            <input type="text" id="crawler_hour" size="2" maxlength="2" value="<?php echo $crawler_hh; ?>">
            Minute:
            <input type="text" id="crawler_minute" size="2" maxlength="2" value="<?php echo $crawler_mm; ?>">
            <?php if ($time_format == '2'){
			if(date("A", $crawler_timestamp) == 'AM'){ $selected0 = 'selected'; } else { $selected0 = ''; }
			if(date("A", $crawler_timestamp) == 'PM'){ $selected1 = 'selected'; } else { $selected1 = ''; }
			echo '<select id="crawler_am_pm">
			<option value="AM" '.$selected0.'>AM</option>
			<option value="PM" '.$selected1.'>PM</option>
			</select>'; }
			?>
            <div class="spacer_5"></div>
            Next crawling:
			<?php // time format 1
			if ($settings['time_format'] == '1'){ $next_crawling = date("d.m.Y - H:i", $settings['crawler_timestamp']); }
			// time format 2
			if ($settings['time_format'] == '2'){ $next_crawling = date("n/d/Y - g:i A", $settings['crawler_timestamp']); }
			if(!isset($next_crawling) or $next_crawling == "") { $next_crawling = ""; }
			echo $next_crawling; ?>
            <div class="spacer_10"></div>
            When crawling finished, change to this channel
            <div class="spacer_5"></div>
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
			if ($obj->zap_start == "1") { $select = "selected=\"selected\""; } 
			elseif ($obj->zap_start == "0") { $select = ""; }
			echo utf8_encode("<option value='$obj->e2servicereference' $select>$obj->e2servicename</option>"); }    
			}
			}
			?>
            </select>
            <div class="spacer_10"></div>
            Switch Receiver after crawling
            <select id="after_crawl_action">
            <option value="0" <?php if ($settings['after_crawl_action'] == '0'){ echo "selected"; } ?>>Standby</option>
            <option value="1" <?php if ($settings['after_crawl_action'] == '1'){ echo "selected"; } ?>>Deep Standby</option>
            <option value="9" <?php if ($settings['after_crawl_action'] == '9'){ echo "selected"; } ?>>Nothing</option>
            </select>
            <div class="spacer_10"></div>
            <h5>Channel Zapper</h5>
          <input type="checkbox" name="" id="cz_activate" onclick="" <?php if ($settings['cz_activate'] == '1'){ echo "checked"; } ?> />
          <strong>Activate</strong> automatic Channel Zapper
          <div class="spacer_10"></div>
          Wait on channel (in seconds)
          <input name="textfield" type="text" id="cz_wait_time" size="2" maxlength="2" value="<?php echo $settings['cz_wait_time']?>" >
          <div class="spacer_10"></div>
          Set time when Channel Zapper should start
          <div class="spacer_5"></div>
          Hour:
          <input type="text" id="cz_hour" size="2" maxlength="2" value="<?php echo $cz_hh; ?>">
          Minute:
          <input type="text" id="cz_minute" size="2" maxlength="2" value="<?php echo $cz_mm; ?>">
          <?php 
		  //
		  if ($time_format == '2'){
		  if(date('A', $cz_timestamp) == 'AM'){ $selected2 = 'selected'; } else { $selected2 = ''; }
		  if(date('A', $cz_timestamp) == 'PM'){ $selected3 = 'selected'; } else { $selected3 = ''; }
		  
		  echo '<select id="cz_am_pm">
		  <option value="AM" '.$selected2.'>AM</option>
		  <option value="PM" '.$selected3.'>PM</option>
		  </select>';
		  }
		  ?>
          <div class="spacer_10"></div>
          Repeat zapping
          <select id="cz_repeat">
            <option value="daily" <?php if ($settings['cz_repeat'] == 'daily'){ echo "selected"; } ?>>every day</option>
            <option value="daily_3" <?php if ($settings['cz_repeat'] == 'daily_3'){ echo "selected"; } ?>>every 3 days</option>
            <option value="daily_5" <?php if ($settings['cz_repeat'] == 'daily_5'){ echo "selected"; } ?>>every 5 days</option>
            <option value="daily_7" <?php if ($settings['cz_repeat'] == 'daily_7'){ echo "selected"; } ?>>every 7 days</option>
          </select>
          <div class="spacer_10"></div>
          <p>Next zapping:
            <?php 
			// time format 1
			if(!isset($next_day) or $next_day == "") { $next_day = ""; } else { $next_day = $next_day; }
			if ($settings['time_format'] == '1'){ $next_day = date("d.m.Y - H:i", $settings['cz_timestamp']); }
			// time format 2
			if ($settings['time_format'] == '2'){ $next_day = date("n/d/Y - g:i A", $settings['cz_timestamp']); } echo $next_day; ?>
            <div>Duration from zapping about: <i class="fa fa-clock-o"></i> <?php echo round($cz_worktime/60,0); ?> min</div></p>
          </div>
        </div>
        <div class="col-md-4">
          <h5>Settings</h5>
          <input type="checkbox" name="" id="activate_cron" onclick="" <?php if ($settings['activate_cron'] == '1'){ echo "checked"; } ?> />
          <strong>Activate</strong> Cron
          <div class="spacer_10"></div>
          Displayed  time format
          <select id="time_format">
            <option value="1" <?php if ($settings['time_format'] == '1'){ echo "selected"; } ?>>dd.mm.YY 23:59</option>
            <option value="2" <?php if ($settings['time_format'] == '2'){ echo "selected"; } ?>>mm/dd/YY 11:59 PM</option>
          </select>
          <div class="spacer_10"></div>
          Period of time from <strong>start</strong>, at Broadcast list
          <select id="dur_down_broadcast">
            <option value="0" <?php if ($settings['dur_down_broadcast'] == '0'){ echo "selected"; } ?>>0 minutes</option>
            <option value="300" <?php if ($settings['dur_down_broadcast'] == '300'){ echo "selected"; } ?>>5 minutes</option>
            <option value="600" <?php if ($settings['dur_down_broadcast'] == '600'){ echo "selected"; } ?>>10 minutes</option>
            <option value="900" <?php if ($settings['dur_down_broadcast'] == '900'){ echo "selected"; } ?>>15 minutes</option>
            <option value="1800" <?php if ($settings['dur_down_broadcast'] == '1800'){ echo "selected"; } ?>>30 minutes</option>
            <option value="2700" <?php if ($settings['dur_down_broadcast'] == '2700'){ echo "selected"; } ?>>45 minutes</option>
            <option value="3600" <?php if ($settings['dur_down_broadcast'] == '3600'){ echo "selected"; } ?>>60 minutes</option>
            <option value="7200" <?php if ($settings['dur_down_broadcast'] == '7200'){ echo "selected"; } ?>>120 minutes</option>
            <option value="10800" <?php if ($settings['dur_down_broadcast'] == '10800'){ echo "selected"; } ?>>180 minutes</option>
          </select>
          <div class="spacer_10"></div>
          Period of time from <strong>end</strong>, at Broadcast list
          <select id="dur_up_broadcast">
            <option value="300" <?php if ($settings['dur_up_broadcast'] == '300'){ echo "selected"; } ?>>5 minutes</option>
            <option value="600" <?php if ($settings['dur_up_broadcast'] == '600'){ echo "selected"; } ?>>10 minutes</option>
            <option value="900" <?php if ($settings['dur_up_broadcast'] == '900'){ echo "selected"; } ?>>15 minutes</option>
            <option value="1800" <?php if ($settings['dur_up_broadcast'] == '1800'){ echo "selected"; } ?>>30 minutes</option>
            <option value="2700" <?php if ($settings['dur_up_broadcast'] == '2700'){ echo "selected"; } ?>>45 minutes</option>
            <option value="3600" <?php if ($settings['dur_up_broadcast'] == '3600'){ echo "selected"; } ?>>60 minutes</option>
            <option value="7200" <?php if ($settings['dur_up_broadcast'] == '7200'){ echo "selected"; } ?>>120 minutes</option>
            <option value="10800" <?php if ($settings['dur_up_broadcast'] == '10800'){ echo "selected"; } ?>>180 minutes</option>
          </select>
          <div class="spacer_10"></div>
          Period of time from <strong>start</strong>, at Primetime list
          <select id="dur_down_primetime">
            <option value="0" <?php if ($settings['dur_down_primetime'] == '0'){ echo "selected"; } ?>>0 minutes</option>
            <option value="300" <?php if ($settings['dur_down_primetime'] == '300'){ echo "selected"; } ?>>5 minutes</option>
            <option value="600" <?php if ($settings['dur_down_primetime'] == '600'){ echo "selected"; } ?>>10 minutes</option>
            <option value="900" <?php if ($settings['dur_down_primetime'] == '900'){ echo "selected"; } ?>>15 minutes</option>
            <option value="1800" <?php if ($settings['dur_down_primetime'] == '1800'){ echo "selected"; } ?>>30 minutes</option>
            <option value="2700" <?php if ($settings['dur_down_primetime'] == '2700'){ echo "selected"; } ?>>45 minutes</option>
            <option value="3600" <?php if ($settings['dur_down_primetime'] == '3600'){ echo "selected"; } ?>>60 minutes</option>
          </select>
          <div class="spacer_10"></div>
          Period of time from <strong>end</strong>, at Primetime list
          <select id="dur_up_primetime">
            <option value="3600" <?php if ($settings['dur_up_primetime'] == '3600'){ echo "selected"; } ?>>1 hour</option>
            <option value="7200" <?php if ($settings['dur_up_primetime'] == '7200'){ echo "selected"; } ?>>2 hours</option>
            <option value="10800" <?php if ($settings['dur_up_primetime'] == '10800'){ echo "selected"; } ?>>3 hours</option>
          </select>
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="display_old_epg" onclick="" <?php if ($settings['display_old_epg'] == '1'){ echo "checked"; } ?> />
          Display EPG at search <i class="fa fa-search fa-1x"></i> from broadcasts who already expired
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="streaming_symbol" onclick="" <?php if ($settings['streaming_symbol'] == '1'){ echo "checked"; } ?> />
          Display Streaming symbol <i class="fa fa-desktop fa-1x"></i> at Broadcast, Primetime and Channel Browser list
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="imdb_symbol" onclick="" <?php if ($settings['imdb_symbol'] == '1'){ echo "checked"; } ?> />
          Display IMDb symbol <i class="fa fa-info-circle fa-1x"></i> at Broadcast, Primetime and Channel Browser list
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="timer_ticker" onclick="" <?php if ($settings['timer_ticker'] == '1'){ echo "checked"; } ?> />
          Display Timer <strong>Ticker</strong> on Startpage
          <div class="spacer_10"></div>
          Period of time from Ticker
          <select id="ticker_time">
            <option value="86400" <?php if ($settings['ticker_time'] == '86400'){ echo "selected"; } ?>>1 day</option>
            <option value="259200" <?php if ($settings['ticker_time'] == '259200'){ echo "selected"; } ?>>3 days</option>
            <option value="432000" <?php if ($settings['ticker_time'] == '432000'){ echo "selected"; } ?>>5 days</option>
            <option value="604800" <?php if ($settings['ticker_time'] == '604800'){ echo "selected"; } ?>>7 days</option>
          </select>
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="mark_searchterm" onclick="" <?php if ($settings['mark_searchterm'] == '1'){ echo "checked"; } ?> />
          Mark searchterm at search results <i class="fa fa-search fa-1x"></i>
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="reload_progressbar" onclick="" <?php if ($settings['reload_progressbar'] == '1'){ echo "checked"; } ?> />
          Reload Broadcast today Progressbar on Startpage continously
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="delete_old_epg" onclick="" <?php if ($settings['delete_old_epg'] == '1'){ echo "checked"; } ?> />
          Delete EPG which is older than
          <select id="del_time">
            <option value="3600" <?php if ($settings['del_time'] == '3600'){ echo "selected"; } ?>>1 hour</option>
            <option value="10800" <?php if ($settings['del_time'] == '10800'){ echo "selected"; } ?>>3 hours</option>
            <option value="21600" <?php if ($settings['del_time'] == '21600'){ echo "selected"; } ?>>6 hours</option>
            <option value="43200" <?php if ($settings['del_time'] == '43200'){ echo "selected"; } ?>>12 hours</option>
            <option value="86400" <?php if ($settings['del_time'] == '86400'){ echo "selected"; } ?>>24 hours</option>
          </select>
          <div class="spacer_10"></div>
            Connect to Receiver with 
            <select id="url_format">
            <option value="http" <?php if ($settings['url_format'] == 'http'){ echo "selected"; } ?>>http</option>
            <option value="https" <?php if ($settings['url_format'] == 'https'){ echo "selected"; } ?>>https</option>
            </select>
          </div>
        <!-- row -->
        <div class="col-md-4">
          <h5>Timer Settings</h5>
          <div class="spacer_10"></div>
          <input type="checkbox" id="send_timer" onclick="" <?php if ($settings['send_timer'] == '1'){ echo "checked"; } ?> />
          Send timer automatic to Receiver
          <div class="spacer_10"></div>
          <input type="checkbox" id="hide_old_timer" onclick="" <?php if ($settings['hide_old_timer'] == '1'){ echo "checked"; } ?> />
          Hide expired timer in timerlist
          <div class="spacer_10"></div>
          
          <input type="checkbox" id="show_hidden_ticker" onclick="" <?php if ($settings['show_hidden_ticker'] == '1'){ echo "checked"; } ?> />
          Show hidden timer in Ticker on Startpage
          <div class="spacer_10"></div>
          
          <input type="checkbox" id="delete_old_timer" onclick="" <?php if ($settings['delete_old_timer'] == '1'){ echo "checked"; } ?> />
          Delete expired timer from database 
          <div class="spacer_10"></div>
          <input type="checkbox" id="delete_receiver_timer" onclick="" <?php if ($settings['delete_receiver_timer'] == '1'){ echo "checked"; } ?> />
          Delete expired timer from Receiver
          <div class="spacer_10"></div>
          Additional record time at end from broadcast
          <select id="extra_rec_time">
            <option value="0" <?php if ($settings['extra_rec_time'] == '0'){ echo "selected"; } ?>>0 minutes</option>
            <option value="300" <?php if ($settings['extra_rec_time'] == '300'){ echo "selected"; } ?>>5 minutes</option>
            <option value="600" <?php if ($settings['extra_rec_time'] == '600'){ echo "selected"; } ?>>10 minutes</option>
            <option value="900" <?php if ($settings['extra_rec_time'] == '900'){ echo "selected"; } ?>>15 minutes</option>
            <option value="1800" <?php if ($settings['extra_rec_time'] == '1800'){ echo "selected"; } ?>>30 minutes</option>
            <option value="3600" <?php if ($settings['extra_rec_time'] == '3600'){ echo "selected"; } ?>>60 minutes</option>
          </select>
        </div>
      </div>
      <!-- /. ROW  -->
      <div class="row">
        <div class="col-md-4"> </div>
        <div class="col-md-4">
          <div class="spacer_10"></div>
          <a onclick="save_settings();" class="btn btn-success btn-lg btn-block">SAVE SETTINGS</a>
          <div id="save_settings_status"></div>
        </div>
        <div class="col-md-4"> </div>
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
