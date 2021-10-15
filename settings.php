<?php 
session_start();
//
	include("inc/dashboard_config.php");
	include_once("inc/header_info.php");

	// crawler time
	if($time_format == '1'){
	$crawler_hh = date("H",$crawler_timestamp);
	$crawler_mm = date("i",$crawler_timestamp);
	}
	if($time_format == '2'){
	$crawler_hh = date("g",$crawler_timestamp);
	$crawler_mm = date("i",$crawler_timestamp);
	}
	
	// cz time
	if($time_format == '1'){
	$cz_hh = date("H",$cz_timestamp);
	$cz_mm = date("i",$cz_timestamp);
	}
	if($time_format == '2'){
	$cz_hh = date("g",$cz_timestamp);
	$cz_mm = date("i",$cz_timestamp);
	}
	
	// get settings
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `settings`");
	$settings = mysqli_fetch_assoc($sql);
	$cz_wait_time = $settings['cz_wait_time'];
	$cz_device_no = $settings['cz_device'];
	
	// calculate work time
	$sql_1 = mysqli_query($dbmysqli, "SELECT COUNT(*) FROM `channel_list` WHERE `zap` = '1' ");
	$result_1 = mysqli_fetch_row($sql_1);
	$sum_zap_channels = $result_1[0];
	//
	$cz_worktime = $sum_zap_channels * $cz_wait_time + 10;
	
	// read device info
	$sql_2 = mysqli_query($dbmysqli, "SELECT * FROM `box_info`");
	$result_2 = mysqli_fetch_assoc($sql_2);
	if(!isset($result_2['e2enigmaversion']) or $result_2['e2enigmaversion'] == ''){ $result_2['e2enigmaversion'] = ''; } else { $result_2['e2enigmaversion'] = 'OS:<br>'.$result_2['e2enigmaversion']; }
	if(!isset($result_2['e2imageversion']) or $result_2['e2imageversion'] == ''){ $result_2['e2imageversion'] = ''; } else { $result_2['e2imageversion'] = 'Image:<br>'.$result_2['e2imageversion']; }
	if(!isset($result_2['e2webifversion']) or $result_2['e2webifversion'] == ''){ $result_2['e2webifversion'] = ''; } else { $result_2['e2webifversion'] = 'Webinterface:<br>'.$result_2['e2webifversion']; }
	if(!isset($result_2['e2model']) or $result_2['e2model'] == ''){ $result_2['e2model'] = ''; } else { $result_2['e2model'] = 'Device:<br>'.$result_2['e2model']; }
	
	// cz device list
	$sql_3 = "SELECT * FROM `device_list` ";
	
	if ($result_3 = mysqli_query($dbmysqli,$sql_3))
	{
	while ($obj = mysqli_fetch_object($result_3)){
	{
	
	if(!isset($cz_device_list) or $cz_device_list == ''){ $cz_device_list = ''; }
	
	if($cz_device_no == $obj->id){ $cz_list_selected = 'selected="selected"'; } else { $cz_list_selected = ''; }
	
	$cz_device_list = $cz_device_list.'
	<option value="'.$obj->id.'" '.$cz_list_selected.'>'.rawurldecode($obj->device_description).'</option>
	';
	}
	}
}
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
animatedcollapse.addDiv('save_box_info', 'fade=1,height=auto')
animatedcollapse.addDiv('save_box_info_status', 'fade=1,height=auto')
animatedcollapse.addDiv('save_box_settings_status', 'fade=1,height=auto')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
//$: Access to jQuery
//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
//state: "block" or "none", depending on state
}
animatedcollapse.init();
</script>
</head>
<body>
<a id="top"></a>
<div id="scroll_top"><a href="#" title="top"><script>document.write("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script></a></div>
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
        <span class="navbar-brand"><span style="font-size:1.1em; color:#000; cursor:pointer;" onclick="remote_modal.open();" title="Remote Control"><i class="fa fa-wifi fa-1x"></i></span> <a class="navbar-link" href="settings.php">Settings</a></span> </div>
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
        <script>document.write(navbar_header_settings)</script>
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
            <li> <a href="settings.php"><i class="fa fa-cog"></i><strong>Main Settings</strong></a> </li>
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
    </div>
    <!-- /. ROW  -->
    <div id="page-inner">
    <div class="row">
    <div id="cookie_js" class="col-md-12" style="color:#FF0000;">
    <noscript>To use all functions of the website, it's required to activate JavaScript.</noscript>
    </div>
    </div>
      <div class="row">
        <div class="col-md-12">
          <h2>Settings</h2>
          <input id="display_time_format" type="hidden" value="">
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
        <div class="col-md-4">
          <h5>Receiver, Server Settings</h5>
          <div class="form-group">
            <p class="help-block">IP from Receiver:</p>
            <input id="box_ip" class="form-control" maxlength="50" value="<?php echo $settings['box_ip']; ?>" />
            <div class="spacer_10"></div>
            <p class="help-block">Username from Receiver:</p>
            <input id="box_user" class="form-control" maxlength="50" value="<?php echo $settings['box_user']; ?>" />
            <div class="spacer_10"></div>
            <p class="help-block">Password from Receiver:</p>
            <input id="box_password" class="form-control" type="password" maxlength="50" value="<?php echo $settings['box_password']; ?>" />
            <div class="spacer_10"></div>
            <p class="help-block">Server IP:</p>
            <input id="server_ip" class="form-control" maxlength="50" value="<?php echo $settings['server_ip']; ?>" />
            <div class="spacer_10"></div>
            <p class="help-block">Script folder:</p>
            <input id="script_folder" class="form-control" maxlength="50" value="<?php echo $settings['script_folder']; ?>" /> 
          </div>
        </div>
        <div class="col-md-4">
          <div class="spacer_80"></div>
          <a onclick="save_box_settings(); save_settings(); animatedcollapse.show('save_box_settings_status')" class="btn btn-success btn-lg btn-block">SAVE SETTINGS</a>
          <div id="save_box_settings_status"></div>
          <div id="save_box_info"><a onclick="get_receiver_data(); animatedcollapse.show('save_box_info_status')" class="btn btn-primary btn-lg btn-block" title="Copy bouquets and record locations to database">Copy Receiver data</a></div>
          <div id="save_box_info_status">
            <div id="save_bouquet_data_status"></div>
          </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-3">
          <h5>Receiver Info</h5>
          <p><?php echo $result_2['e2enigmaversion']; ?></p>
          <p><?php echo $result_2['e2imageversion']; ?></p>
          <p><?php echo $result_2['e2webifversion']; ?></p>
          <p><?php echo $result_2['e2model']; ?></p>
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
            <input type="checkbox" name="" id="search_crawler" onclick="" <?php if($settings['search_crawler'] == '1'){ echo 'checked'; } ?> />
            <strong>Activate</strong> automatic Search Crawler
            <div class="spacer_10"></div>
            <input type="checkbox" name="" id="epg_crawler" onclick="" <?php if($settings['epg_crawler'] == '1'){ echo 'checked'; } ?> />
            <strong>Activate</strong> automatic EPG Crawler
            <div class="spacer_10"></div>
            <input type="checkbox" name="" id="dummy_timer" onclick="" <?php if($settings['dummy_timer'] == '1'){ echo 'checked'; } ?> />
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
			if($settings['time_format'] == '1'){ $next_crawling = date('d.m.Y - H:i', $settings['crawler_timestamp']); }
			// time format 2
			if($settings['time_format'] == '2'){ $next_crawling = date('n/d/Y - g:i A', $settings['crawler_timestamp']); }
			if(!isset($next_crawling) or $next_crawling == ''){ $next_crawling = ''; }
			echo $next_crawling; ?>
            <br>
            Duration from last crawling: ~ <?php $crawler_duration = $crawler_end - $crawler_start; 
			$crawler_duration = round($crawler_duration/60,0); 
			$crawler_duration = str_replace('.', ',', $crawler_duration);
			echo $crawler_duration; ?> min.
            <div class="spacer_10"></div>
            When crawling finished, change to this channel
            <div class="spacer_5"></div>
            <select name="channel_id" id="channel_id" class="form-control">
			<?php 
            // channel dropdown			
			$sql = "SELECT * FROM `channel_list` ORDER BY `e2servicename` ASC";
			if ($result = mysqli_query($dbmysqli,$sql))
			{
			while ($obj = mysqli_fetch_object($result)) {
			{
			// set selected
			if($obj->zap_start == '1'){ $select = 'selected="selected"'; } else { $select = ''; }
			echo utf8_encode('<option value="'.$obj->e2servicereference.'" '.$select.'>'.$obj->e2servicename.'</option>'); }    
			}
			}
			?>
            </select>
            <div class="spacer_10"></div>
            Switch Receiver after crawling
            <select id="after_crawl_action">
              <option value="0" <?php if($settings['after_crawl_action'] == '0'){ echo "selected"; } ?>>Standby</option>
              <option value="1" <?php if($settings['after_crawl_action'] == '1'){ echo "selected"; } ?>>Deep Standby</option>
              <option value="9" <?php if($settings['after_crawl_action'] == '9'){ echo "selected"; } ?>>Nothing</option>
            </select>
            <div class="spacer_10"></div>
            <h5>Channel Zapper</h5>
            <input type="checkbox" name="" id="cz_activate" onclick="" <?php if($settings['cz_activate'] == '1'){ echo 'checked'; } ?> />
            <strong>Activate</strong> automatic Channel Zapper
            <div class="spacer_10"></div>
            Device for zapping 
            <select id="cz_device">
            <option value="0">default</option>
            <?php echo $cz_device_list; ?>
            </select>
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
		  if($time_format == '2'){
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
              <option value="daily" <?php if($settings['cz_repeat'] == 'daily'){ echo 'selected'; } ?>>every day</option>
              <option value="daily_3" <?php if($settings['cz_repeat'] == 'daily_3'){ echo 'selected'; } ?>>every 3 days</option>
              <option value="daily_5" <?php if($settings['cz_repeat'] == 'daily_5'){ echo 'selected'; } ?>>every 5 days</option>
              <option value="daily_7" <?php if($settings['cz_repeat'] == 'daily_7'){ echo 'selected'; } ?>>every 7 days</option>
            </select>
            <div class="spacer_10"></div>
            <p>Next zapping:
              <?php 
			// time format 1
			if(!isset($next_day) or $next_day == ''){ $next_day = ''; }
			if($settings['time_format'] == '1'){ $next_day = date('d.m.Y - H:i', $settings['cz_timestamp']); }
			// time format 2
			if($settings['time_format'] == '2'){ $next_day = date('n/d/Y - g:i A', $settings['cz_timestamp']); } echo $next_day; ?>
            <br>
            Duration from zapping: ~ <?php echo round($cz_worktime/60,0); ?> min.
            </p>
          </div>
        </div>
        <div class="col-md-4">
          <h5>Settings</h5>
          <input type="checkbox" name="" id="activate_cron" onclick="" <?php if($settings['activate_cron'] == '1'){ echo 'checked'; } ?> />
          <strong>Activate</strong> Cron
          <div class="spacer_10"></div>
          Displayed  time format
          <select id="time_format">
            <option value="1" <?php if($settings['time_format'] == '1'){ echo 'selected'; } ?>>dd.mm.YY 23:59</option>
            <option value="2" <?php if($settings['time_format'] == '2'){ echo 'selected'; } ?>>mm/dd/YY 11:59 PM</option>
          </select>
          <div class="spacer_10"></div>
          Period of time from <strong>start</strong>, at Broadcast list
          <select id="dur_down_broadcast">
            <option value="0" <?php if($settings['dur_down_broadcast'] == '0'){ echo 'selected'; } ?>>0 minutes</option>
            <option value="300" <?php if($settings['dur_down_broadcast'] == '300'){ echo 'selected'; } ?>>5 minutes</option>
            <option value="600" <?php if($settings['dur_down_broadcast'] == '600'){ echo 'selected'; } ?>>10 minutes</option>
            <option value="900" <?php if($settings['dur_down_broadcast'] == '900'){ echo 'selected'; } ?>>15 minutes</option>
            <option value="1800" <?php if($settings['dur_down_broadcast'] == '1800'){ echo 'selected'; } ?>>30 minutes</option>
            <option value="2700" <?php if($settings['dur_down_broadcast'] == '2700'){ echo 'selected'; } ?>>45 minutes</option>
            <option value="3600" <?php if($settings['dur_down_broadcast'] == '3600'){ echo 'selected'; } ?>>60 minutes</option>
            <option value="7200" <?php if($settings['dur_down_broadcast'] == '7200'){ echo 'selected'; } ?>>120 minutes</option>
            <option value="10800" <?php if($settings['dur_down_broadcast'] == '10800'){ echo 'selected'; } ?>>180 minutes</option>
          </select>
          <div class="spacer_10"></div>
          Period of time from <strong>end</strong>, at Broadcast list
          <select id="dur_up_broadcast">
            <option value="300" <?php if($settings['dur_up_broadcast'] == '300'){ echo 'selected'; } ?>>5 minutes</option>
            <option value="600" <?php if($settings['dur_up_broadcast'] == '600'){ echo 'selected'; } ?>>10 minutes</option>
            <option value="900" <?php if($settings['dur_up_broadcast'] == '900'){ echo 'selected'; } ?>>15 minutes</option>
            <option value="1800" <?php if($settings['dur_up_broadcast'] == '1800'){ echo 'selected'; } ?>>30 minutes</option>
            <option value="2700" <?php if($settings['dur_up_broadcast'] == '2700'){ echo 'selected'; } ?>>45 minutes</option>
            <option value="3600" <?php if($settings['dur_up_broadcast'] == '3600'){ echo 'selected'; } ?>>60 minutes</option>
            <option value="7200" <?php if($settings['dur_up_broadcast'] == '7200'){ echo 'selected'; } ?>>120 minutes</option>
            <option value="10800" <?php if($settings['dur_up_broadcast'] == '10800'){ echo 'selected'; } ?>>180 minutes</option>
          </select>
          <div class="spacer_10"></div>
          Period of time from <strong>start</strong>, at Primetime list
          <select id="dur_down_primetime">
            <option value="0" <?php if($settings['dur_down_primetime'] == '0'){ echo 'selected'; } ?>>0 minutes</option>
            <option value="300" <?php if($settings['dur_down_primetime'] == '300'){ echo 'selected'; } ?>>5 minutes</option>
            <option value="600" <?php if($settings['dur_down_primetime'] == '600'){ echo 'selected'; } ?>>10 minutes</option>
            <option value="900" <?php if($settings['dur_down_primetime'] == '900'){ echo 'selected'; } ?>>15 minutes</option>
            <option value="1800" <?php if($settings['dur_down_primetime'] == '1800'){ echo 'selected'; } ?>>30 minutes</option>
            <option value="2700" <?php if($settings['dur_down_primetime'] == '2700'){ echo 'selected'; } ?>>45 minutes</option>
            <option value="3600" <?php if($settings['dur_down_primetime'] == '3600'){ echo 'selected'; } ?>>60 minutes</option>
          </select>
          <div class="spacer_10"></div>
          Period of time from <strong>end</strong>, at Primetime list
          <select id="dur_up_primetime">
            <option value="3600" <?php if($settings['dur_up_primetime'] == '3600'){ echo 'selected'; } ?>>1 hour</option>
            <option value="7200" <?php if($settings['dur_up_primetime'] == '7200'){ echo 'selected'; } ?>>2 hours</option>
            <option value="10800" <?php if($settings['dur_up_primetime'] == '10800'){ echo 'selected'; } ?>>3 hours</option>
          </select>
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="display_old_epg" onclick="" <?php if($settings['display_old_epg'] == '1'){ echo 'checked'; } ?> />
          Display EPG at search <i class="fa fa-search fa-1x"></i> from broadcasts who already expired
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="streaming_symbol" onclick="" <?php if($settings['streaming_symbol'] == '1'){ echo 'checked'; } ?> />
          Display Streaming symbol <i class="fa fa-desktop fa-1x"></i> at Broadcast, Primetime and Channel Browser list
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="imdb_symbol" onclick="" <?php if($settings['imdb_symbol'] == '1'){ echo 'checked'; } ?> />
          Display IMDb symbol <i class="fa fa-info-circle fa-1x"></i> at Broadcast, Primetime and Channel Browser list
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="timer_ticker" onclick="" <?php if($settings['timer_ticker'] == '1'){ echo 'checked'; } ?> />
          Display Timer <strong>Ticker</strong> on Startpage
          <div class="spacer_10"></div>
          Period of time from Ticker
          <select id="ticker_time">
            <option value="86400" <?php if($settings['ticker_time'] == '86400'){ echo 'selected'; } ?>>1 day</option>
            <option value="259200" <?php if($settings['ticker_time'] == '259200'){ echo 'selected'; } ?>>3 days</option>
            <option value="432000" <?php if($settings['ticker_time'] == '432000'){ echo 'selected'; } ?>>5 days</option>
            <option value="604800" <?php if($settings['ticker_time'] == '604800'){ echo 'selected'; } ?>>7 days</option>
          </select>
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="mark_searchterm" onclick="" <?php if($settings['mark_searchterm'] == '1'){ echo 'checked'; } ?> />
          Mark searchterm at search results <i class="fa fa-search fa-1x"></i>
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="reload_progressbar" onclick="" <?php if($settings['reload_progressbar'] == '1'){ echo 'checked'; } ?> />
          Reload Broadcast today Progressbar on Startpage continously
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="delete_old_epg" onclick="" <?php if($settings['delete_old_epg'] == '1'){ echo 'checked'; } ?> />
          Delete EPG which is older than
          <select id="del_time">
            <option value="3600" <?php if($settings['del_time'] == '3600'){ echo 'selected'; } ?>>1 hour</option>
            <option value="10800" <?php if($settings['del_time'] == '10800'){ echo 'selected'; } ?>>3 hours</option>
            <option value="21600" <?php if($settings['del_time'] == '21600'){ echo 'selected'; } ?>>6 hours</option>
            <option value="43200" <?php if($settings['del_time'] == '43200'){ echo 'selected'; } ?>>12 hours</option>
            <option value="86400" <?php if($settings['del_time'] == '86400'){ echo 'selected'; } ?>>24 hours</option>
          </select>
          <div class="spacer_10"></div>
          Connect to Receiver with
          <select id="url_format">
            <option value="http" <?php if($settings['url_format'] == 'http'){ echo 'selected'; } ?>>http</option>
            <option value="https" <?php if($settings['url_format'] == 'https'){ echo 'selected'; } ?>>https</option>
          </select>
          <div class="spacer_10"></div>
          <input type="checkbox" name="" id="del_m3u" onclick="" <?php if($settings['del_m3u'] == '1'){ echo 'checked'; } ?> />
          Delete m3u files from /tmp
          <div class="spacer_10"></div>
            Sorting channels in Quickpanel
            <select id="sort_quickpanel">
          <option value="e2servicename" <?php if($settings['sort_quickpanel'] == 'e2servicename'){ echo 'selected'; } ?>>Sort by name</option>
          <option value="id" <?php if($settings['sort_quickpanel'] == 'id'){ echo 'selected'; } ?>>Sorting from Receiver</option>
        </select>
        <div class="spacer_10"></div>
        Highlight term in extended description
          <i style="cursor:default;" class="glyphicon glyphicon-question-sign fa-1x" title="Seperate terms with ; (semicolon) Case sensitive."></i> 
          <div class="spacer_10"></div>
          <textarea id="highlight_term" cols="40" rows="3"><?php echo rawurldecode($highlight_term); ?></textarea>
          <div class="spacer_20"></div>
        </div>
        <!-- row -->
        <div class="col-md-4">
          <h5>Timer Settings</h5>
          <div class="spacer_10"></div>
          <input type="checkbox" id="send_timer" onclick="" <?php if($settings['send_timer'] == '1'){ echo 'checked'; } ?> />
          Send timer automatic to Receiver
          <div class="spacer_10"></div>
          <input type="checkbox" id="hide_old_timer" onclick="" <?php if($settings['hide_old_timer'] == '1'){ echo 'checked'; } ?> />
          Hide expired timer in timerlist
          <div class="spacer_10"></div>
          <input type="checkbox" id="show_hidden_ticker" onclick="" <?php if($settings['show_hidden_ticker'] == '1'){ echo 'checked'; } ?> />
          Show hidden timer in Ticker on Startpage
          <div class="spacer_10"></div>
          <input type="checkbox" id="delete_old_timer" onclick="" <?php if($settings['delete_old_timer'] == '1'){ echo 'checked'; } ?> />
          Delete expired timer from database
          <div class="spacer_10"></div>
          <input type="checkbox" id="delete_receiver_timer" onclick="" <?php if($settings['delete_receiver_timer'] == '1'){ echo 'checked'; } ?> />
          Delete expired timer from Receiver
          <div class="spacer_10"></div>
          <input type="checkbox" id="delete_further_receiver_timer" onclick="" <?php if($settings['delete_further_receiver_timer'] == '1'){ echo 'checked'; } ?> />
          Delete expired timer from  additional Receiver
          <div class="spacer_10"></div>
          Additional record time at end from broadcast
          <select id="extra_rec_time">
            <option value="0" <?php if($settings['extra_rec_time'] == '0'){ echo 'selected'; } ?>>0 minutes</option>
            <option value="300" <?php if($settings['extra_rec_time'] == '300'){ echo 'selected'; } ?>>5 minutes</option>
            <option value="600" <?php if($settings['extra_rec_time'] == '600'){ echo 'selected'; } ?>>10 minutes</option>
            <option value="900" <?php if($settings['extra_rec_time'] == '900'){ echo 'selected'; } ?>>15 minutes</option>
            <option value="1800" <?php if($settings['extra_rec_time'] == '1800'){ echo 'selected'; } ?>>30 minutes</option>
            <option value="3600" <?php if($settings['extra_rec_time'] == '3600'){ echo 'selected'; } ?>>60 minutes</option>
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
      </div>
      <!-- /. ROW  -->
      <hr />
      <div class="row">
        <div class="col-md-12">
          <h5>Add Receiver</h5>
        </div>
        <div class="col-md-2">
          <p>Description</p>
          <input id="device_description" class="" type="text" maxlength="17">
        </div>
        <!--col 2-->
        <div class="col-md-2">
          <p>IP Adress</p>
          <input id="device_ip" class="" type="text">
        </div>
        <!--col 2-->
        <div class="col-md-2">
          <p>Username</p>
          <input id="device_user" class="" type="text">
        </div>
        <!--col 2-->
        <div class="col-md-2">
          <p>Password</p>
          <input id="device_password" class="" type="password">
        </div>
        <!--col 2-->
        </div><!-- row-->
        <div class="spacer_10"></div>
        <div class="row">
        <!--<div class="col-md-2">
          <p>Record location</p>
          <input id="device_record_location" class="" type="text" placeholder="/media/hdd/">
        </div>-->
        <!--col 2-->
        <div class="col-md-2">
          <p>Color for Timerlist</p>
          <select id="device_color" style="width: 100%;">
          <option value="#DDDDDD">default</option>
            <option value="#000000">black</option>
            <option value="#428BCA">blue</option>
            <option value="#999999">dark grey</option>
            <option value="#5CB85C">green</option>
            <option value="#F0AD4E">orange</option>
            <option value="#D9534F">red</option>
            <option value="#FFFF00">yellow</option>
          </select>
        </div>
        <div class="col-md-2">
        <p>Url format</p>
        <select id="device_url_format">
        <option value="http">http</option>
        <option value="https">https</option>
        </select>
        </div>
        <div class="col-md-2">
          <div class="spacer_20"></div>
          <div class="spacer_5"></div>
          <input id="add" name="add" type="button" class="btn btn-success btn-sm" value="Add device" onclick="device_list(this.id,this.name)">
          <span id="device_list_status"></span>
          </div>
        <!--col 2-->
      </div>
      <hr>
      <!--row-->
      <div class="row">
        <div class="col-md-12"> <span id="device_list"></span>
          <div class="spacer_30"></div>
        </div>
        <!--col-->
      </div>
      <!--row-->
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
   $("#display_time_format").val("<?php if (!isset($time_format) or $time_format == ''){ $time_format = '2'; } echo $time_format; ?>");
// device list
$.post("functions/device_list_inc.php",
	{
	action: 'show'
	},
	function(data){
	$("#device_list").html(data);
	});
});
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
