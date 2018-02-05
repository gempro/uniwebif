<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Uniwebif : Installation</title>
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
<script type="text/javascript" src="js/animatedcollapse.js">
/***********************************************
* Animated Collapsible DIV v2.4- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Please keep this notice intact
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>
<script type="text/javascript">
animatedcollapse.addDiv('sql_done', 'fade=1,speed=400,height=auto')
animatedcollapse.addDiv('receiver_settings', 'fade=1,speed=400,height=auto')
animatedcollapse.addDiv('steps', 'fade=1,speed=400,height=auto')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
//$: Access to jQuery
//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
//state: "block" or "none", depending on state
}
animatedcollapse.init()
</script>
<script>
function save_sql(){

	var sql_host = $("#sql_host").val();
	var sql_user = $("#sql_user").val();
	var sql_pass = $("#sql_pass").val();
	$("#sql_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");

	$.post("functions/setup_inc.php",
	{
	setting: 'sql',
	sql_host: sql_host,
	sql_user: sql_user,
	sql_pass: sql_pass
	},
	function(data){
	// write data in container
	$("#sql_status").html(data);
	
	if (data == 'Connection OK!'){ 
	//$('#receiver_settings').fadeIn();
	animatedcollapse.show('receiver_settings');
	
	}
	});
}

//
function save_receiver(){

	var receiver_ip = $("#receiver_ip").val();
	var receiver_user = $("#receiver_user").val();
	var receiver_pass = $("#receiver_pass").val();
	$("#receiver_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");

	$.post("functions/setup_inc.php",
	{
	setting: 'receiver',
	receiver_ip: receiver_ip,
	receiver_user: receiver_user,
	receiver_pass: receiver_pass
	},
	function(data){
	// write data in container
	$("#receiver_status").html(data);
	
	if (data == 'Connection OK!'){
	//$('#sql_done').fadeIn();
	animatedcollapse.show('sql_done');
	
	}
	});
}

function install(){
	$("#install_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	var server_ip = $("#server_ip").val();
	var script_folder = $("#script_folder").val();
	
	$.post("functions/setup_inc.php",
	{
	setting: 'install',
	server_ip: server_ip,
	script_folder: script_folder
	},
	function(data){
	// write data in container
	$("#install_status").html(data);
	
	if (data == 'SQL Installation OK!'){
	//$('#steps').fadeIn();
	animatedcollapse.show('steps');
	
	}
	});
}
</script>
</head>
<body>
<a id="top"></a>
<div id="scroll_top" class="scroll_top"><a href="#" title="to top">
  <script>document.write("<i class=\"glyphicon glyphicon-circle-arrow-up fa-"+scrolltop_btn_size+"x\"></i>");</script>
  </a></div>
<div id="wrapper">
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="adjust-nav">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" onclick="nav_icon_scroll()" data-toggle="collapse" data-target=".sidebar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="about.php"><i class="fa fa-square-o"></i>&nbsp;Setup</a> </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <div class="row"> </div>
        </ul>
      </div>
    </div>
  </div>
  <!-- /. NAV TOP  -->
  <nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
      <ul class="nav" id="main-menu">
        <li class="nav-header">
          <div id="nav-header"><i class="fa fa-wrench fa-3-5x"></i> </div>
        </li>
        <li> <a href="setup.php"><i class="fa fa-wrench"></i><strong>Setup</strong></a> </li>
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
    </div>
    <!-- /. ROW  -->
    <div id="page-inner">
      <div class="row">
        <div class="col-md-12">
          <h2>Setup</h2>
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
        <div class="col-md-12" align="center"> <span>allow_url_fopen =
          <?php if(!ini_get("allow_url_fopen") ) { 
	  echo "not activated <i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i>
	  <div class=\"spacer_10\"></div>
	  Please edit php.ini (mostly in /etc/php5/apache2) and set `<strong>allow_url_fopen = on</strong>`<br>Otherwise Uniwebif could not work..<div class=\"spacer_10\"></div>
	  <hr>";
	  } else { 
	  echo "activated <i class='glyphicon glyphicon-ok green'></i><hr>"; 
	  } ?>
          </span> </div>
        <!-- col -->
      </div>
      <!-- row -->
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-2"> SQL Host
          <input type="text" id="sql_host" value="localhost" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-2"> SQL Admin
          <input type="text" id="sql_user" value="root" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-2"> SQL Pass
          <input type="password" id="sql_pass" value="" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-1">
          <div class="spacer_20"></div>
          <input type="submit" class="btn btn-success btn-xs" value="Send" onclick="save_sql();" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-2">
          <div class="spacer_20"></div>
          <span id="sql_status"></span> </div>
      </div>
      <!-- row -->
      <div class="spacer_10"></div>
      <!---->
      <div class="row" id="receiver_settings" style="display:none;">
        <div class="col-md-2"></div>
        <div class="col-md-2"> Receiver IP
          <input type="text" id="receiver_ip" value="" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-2"> Receiver User
          <input type="text" id="receiver_user" value="" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-2"> Receiver Pass
          <input type="password" id="receiver_pass" value="" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-1">
          <div class="spacer_20"></div>
          <input type="submit" class="btn btn-success btn-xs" value="Send" onclick="save_receiver();" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-2">
          <div class="spacer_20"></div>
          <span id="receiver_status"></span> </div>
      </div>
      <!-- row -->
      <!---->
      <div class="row" id="sql_done" style="display:none;">
        <div class="spacer_20"></div>
        <div class="col-md-2"></div>
        <div class="col-md-2"> Server IP
          <input type="text" id="server_ip" value="<?php echo $_SERVER['HTTP_HOST']; ?>" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-2"> Script folder
          <input type="text" id="script_folder" value="uniwebif" />
          <div class="spacer_5"></div>
        </div>
        <div class="col-md-4">
          <div class="spacer_20"></div>
          If Settings done, click install!
          <input type="submit" class="btn btn-success btn-xs" value="Install" onclick="install()">
          <div class="spacer_5"></div>
        </div>
      </div>
      <!-- row -->
      <!---->
      <div class="row">
        <div class="col-md-12" align="center">
          <div class="spacer_20"></div>
          <span id="install_status"></span>
          <div class="spacer_20"></div>
        </div>
        <div class="row">
          <div class="col-md-12" align="center">
            <div id="steps" style="display:none;">
              <p>User uniwebif with password uniwebif, USAGE rights for database `uniwebif` created.. <i class='glyphicon glyphicon-ok green'></i></p>
              <p>Database `uniwebif` with 9 tables created.. <i class='glyphicon glyphicon-ok green'></i></p>
              <p>Bouquets and Record locations added to database.. <i class='glyphicon glyphicon-ok green'></i></p>
              <hr>
              <p>Please do follow steps now:<br>
                <br>
                <strong>1.</strong> Open your FTP program and make CHMOD 755 for folder /uniwebif/<strong>tmp</strong><br>
                When you want stream a recorded file from Receiver, the m3u playlist file will be stored there.<br>
              <p><strong>2.</strong> Open the Bouquet list in Browser - <a href="bouquet_list.php" target="_blank">Link</a> <br>
                Select the Bouqet which includes channels, you wanna have the EPG from.<br>
                After selecting open the Menue "Crawler Tools" and click "Crawl channel ID's".<br>
                Click confirm button to start.<br>
                Now the channels from the selected Bouquet are in database.<br>
                <br>
                <strong>3.</strong> Open "Channel list" in Browser - <a href="channel_list.php" target="_blank">Link</a> <br>
                Select here the channels, from which you wanna have the EPG in database. <br>
                After selecting, open again the Menue "Crawler Tools" and click "Crawl EPG from channels".<br>
                Click confirm button to start.<br>
                Now you have the EPG from selected channels in database. </p>
              <strong>Automate the crawling</strong><br>
              To have always the current EPG in database, make these steps.<br>
              Open Menue 'Main Settings' in Browser and scroll bottom to Crawler - <a href="settings.php" target="_blank">Link</a><br>
              Make there your prefered settings and select "<strong>Activate automatic EPG Crawler</strong>" and "<strong>Activate Cron</strong>".<br>
              Scroll bottom and click save Settings.<br>
              Note: It's also possible to get the EPG from Receiver manual. Read FAQ for more details.<br>
              <br>
              <strong>Automate the search within the EPG</strong><br>
              To have always Timer from your desired broadcasts in Timerlist, make these steps.<br>
              Open 'Search' in Browser - <a href="search.php" target="_blank">Link</a><br>
              Type in any term and click 'Search trough'. After that click 'Save this search for timer'.<br>
              Now this term is saved under Timer & Saved Search. Open Menue 'Main Settings' and select there 'Activate automatic Search Crawler'.<br>
              <br>
              Now create a <strong>Cron</strong>, which called may every 3 minutes the file /uniwebif/inc/<strong>cron.php</strong><br>
              <h2>Ready!</h2>
              Most important Settings are done, and the script was working.<br>
              For more Information read the <a href="help/guide.html" target="_blank">Guide</a> or <a href="http://uniwebif.techweb.at/faq.php" target="_blank">FAQ</a><br>
              Tutorial Video which show the main functions is <a href="https://www.youtube.com/watch?v=lj4EOlJzquk" target="_blank">here</a>.<br>
              For any questions, issues or suggestions visit <a href="https://github.com/gempro/uniwebif/issues" target="_blank">Github</a>.</div>
          </div>
          <!-- col -->
        </div>
        <!-- row -->
        <hr />
      </div>
      <!---->
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
