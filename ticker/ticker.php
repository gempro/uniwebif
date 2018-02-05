<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<style type="text/css">
<!--
#timer_banner {
	width: 100%;
	padding-left: 100px;
	padding-top: 10px;
}
#timer_banner #row1 {
	float: left;
	padding-top: 20px;
	padding-right: 20px;
}
#timer_banner #row2 {
	float: left;
	padding-right: 25px;
}
#timer_banner #row3 {
	float: left;
	padding-top: 15px;
	font-size:16px;
	font-weight: bold;
}
#no_timer {
	padding-top: 50px;
	padding-left: 400px;
}
#ticker {
	background-color:#FAFAFA;
	border: thin solid #F0F0F0;
}
#ticker_btn {
	width:64px;
	text-align:center;
}
#tticker-container i {
	width:100%;
	text-align: center;
}
.popover {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1010;
  display: none;
  max-width: 70%;
  min-width: 50%;
  height:auto;
  padding: 1px;
  text-align: left;
  white-space: normal;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, .2);
  border-radius: 6px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
  box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
}
.popover-title {
  padding: 8px 14px;
  margin: 0;
  font-size: 14px;
  font-weight: normal;
  line-height: 18px;
  background-color: #f7f7f7;
  border-bottom: 1px solid #ebebeb;
  border-radius: 5px 5px 0 0;
}
.popover-content {
  padding: 9px 14px;
}
-->
</style>
<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST["action"]) or $_REQUEST["action"] == "") { $action = ""; } else { $action = $_REQUEST["action"]; }
	if(!isset($_REQUEST["hash"]) or $_REQUEST["hash"] == "") { $hash = ""; } else { $hash = $_REQUEST["hash"]; }
	
	if ($action == 'hide'){ 
	$sql = mysqli_query($dbmysqli, "UPDATE timer set show_ticker = '0' WHERE hash = '".$hash."' ");
	exit;
	}
	
	if ($show_hidden_ticker == '1'){ 
	$hidden_timer = '';
	} else { $hidden_timer = ' AND `hide` = 0'; }

	$time_duration = $time + $ticker_time;
	
	// count timer for scroll duration
	$ticker_time_end = $time + $ticker_time;
	$stmt = $dbmysqli->prepare('SELECT COUNT(*) AS sum_timer FROM timer WHERE show_ticker = "1" AND e2eventstart BETWEEN "'.$time.'" AND "'.$ticker_time_end.'" '.$hidden_timer.' ');
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
		
	$stmt->execute();
	$stmt->bind_result($sum_timer);
	$stmt->fetch();
	$stmt->close();
	
	if ($sum_timer == '0'){ $ticker_list = "<div id=\"no_timer\">No timer to display..</div>"; }
		
	if ($sum_timer < '2'){ $scroll_duration = '36000000'; $show_navigate = 'display:none;'; } else { $scroll_duration = '15000'; $show_navigate = ''; }

	$sql = "SELECT * FROM timer WHERE show_ticker = '1' AND e2eventstart BETWEEN '".$time."' AND '".$time_duration."' ".$hidden_timer." ";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
    	
	{
	if(!isset($ticker_list) or $ticker_list == "") { $ticker_list = ""; }
	
	if ($obj->status == 'manual' or $obj->status == 'sent'){ 
	$show_timer_btn = 'display:none;'; 
	$timer_status = '<i class="glyphicon glyphicon-ok fa-1x" style="color:#5CB85C; cursor:default;" title="already sent"></i>';
	
	} else { 
	
	$show_timer_btn = ''; 
	$timer_status = '';}
	
	$title_enc = rawurldecode($obj->title_enc);
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$description_enc = rawurldecode($obj->description_enc);
	$descriptionextended_enc = rawurldecode($obj->descriptionextended_enc);
	$hash = $obj->hash;
	
	if(strlen($obj->e2eventtitle) > "80" ) {
	$e2eventtitle = substr($obj->e2eventtitle, 0, 80);
	$e2eventtitle = $e2eventtitle . '...';
	$title_enc = utf8_encode($e2eventtitle);
	}
	
	// remove special chars
	$title_enc = str_replace("\"", "", $title_enc);
	$description_enc = str_replace("\"", "", $description_enc);
	$descriptionextended_enc = str_replace("\"", "", $descriptionextended_enc);
	
	$title_enc = str_replace("'", "", $title_enc);
	$description_enc = str_replace("'", "", $description_enc);
	$descriptionextended_enc = str_replace("'", "", $descriptionextended_enc);
	
if ($time_format == '1')
	{
	// time format 1
	$e2eventstart = $obj->e2eventstart;
	$date_start_day = date("d", $e2eventstart);
	$date_start_month = date("m", $e2eventstart);
	$date_start_hour = date("H", $e2eventstart);
	$date_start_minute = date("i", $e2eventstart);
	
	$e2eventend = $obj->e2eventend;
	$date_end_day = date("d", $e2eventend);
	$date_end_hour = date("H", $e2eventend);
	$date_end_minute = date("i", $e2eventend);
	$timer_time = "$date_start_day.$date_start_month, $date_start_hour:$date_start_minute - $date_end_hour:$date_end_minute";
	}
	
	if ($time_format == '2')
	{
	// time format 2
	$e2eventstart = $obj->e2eventstart;
	$date_start_day = date("d", $e2eventstart);
	$date_start_month = date("n", $e2eventstart);
	$date_start_hour = date("g", $e2eventstart);
	$date_start_minute = date("i", $e2eventstart);
	$date_start_ampm = date("A", $e2eventstart);
	
	$e2eventend = $obj->e2eventend;
	$date_end_hour = date("g", $e2eventend);
	$date_end_minute = date("i", $e2eventend);
	$date_end_ampm = date("A", $e2eventend);
	$timer_time = "$date_start_month/$date_start_day, $date_start_hour:$date_start_minute $date_start_ampm - $date_end_hour:$date_end_minute $date_end_ampm";
	}
	
	$ticker_list = $ticker_list. "
		<li style=\"list-style: none;\">
		<div id=\"timer_banner_div_$hash\">
	  <div id=\"timer_banner\">
	<div id=\"row1\">
	<div id=\"ticker_btn\">
	<p><a href=\"#timer_info\" title=\"$title_enc\" class=\"btn btn-default btn-xs btn-block\" data-toggle=\"popover\" data-trigger=\"focus\" data-html=\"true\" data-content=\"<p>$description_enc</p> $descriptionextended_enc\">more Info</a></p>
	</div>
	  <center><span id=\"tickerlist_send_timer_status_$hash\"><p>
		<input type=\"submit\" id=\"tickerlist_send_timer_btn_$hash\" onclick=\"tickerlist_send_timer(this.id)\" class=\"btn btn-xs btn-success\" title=\"set Timer instantly\" value=\"set Timer\" style=\"$show_timer_btn\">$timer_status
	  </p></span></center>
	</div>
	<div id=\"row2\"> <i class=\"glyphicon glyphicon-hand-right fa-4x\"></i>
	  <p>
		<input type=\"submit\" id=\"tickerlist_$hash\" onclick=\"remove_ticker_event(this.id)\" class=\"btn btn-xs btn-default\" title=\"hide from Ticker\" value=\"remove\">
	  </p>
	</div>
	<div id=\"row3\">
	  <p>$title_enc</p>
	  <p>$timer_time | $servicename_enc </p>
	</div>
	<div style=\"clear:both\">&nbsp;</div>
  </div></div>
</li>";
	}
  }
// Free result set
mysqli_free_result($result);
}
//close db
mysqli_close($dbmysqli);
?>
</head>
<body>
<div class="row">
  <div class="col-md-12">
    <div id="tticker-container"> <i class="fa fa-arrow-up" id="tticker-prev" style="cursor:pointer; <?php echo $show_navigate; ?>"></i>
      <ul id="tticker">
		<?php if(!isset($ticker_list) or $ticker_list == ""){ $ticker_list = ""; } else { echo $ticker_list; } ?>
      </ul>
      <i class="fa fa-arrow-down" id="tticker-next" style="cursor:pointer; <?php echo $show_navigate; ?>"></i> </div>
  </div>
</div>
<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="ticker/js/jquery-1.10.2.min.js"></script>
<script src="ticker/js/bootstrap.js"></script>
<script src="ticker/js/jquery.mCustomScrollbar.min.js"></script>
<script src="ticker/js/jquery.newsTicker.js"></script>

<script type="text/javascript">
// ticker
$(window).load(function(){
	$('code.language-javascript').mCustomScrollbar();
});
var tticker_1 = $('#tticker').newsTicker({
	row_height: 100,
	max_rows: 1,
	duration: <?php echo $scroll_duration; ?>,
	prevButton: $('#tticker-prev'),
	nextButton: $('#tticker-next')
});

function remove_ticker_event(id) {
	var this_id = id.replace(/tickerlist_/g, "");
	
	if(typeof(EventSource) !== "undefined") {
    var source = new EventSource("ticker/ticker.php?action=hide&hash="+this_id+"");	
	animatedcollapse.addDiv('timer_banner_div_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init();
	animatedcollapse.hide('timer_banner_div_'+this_id);
	animatedcollapse.addDiv('ticker_content', 'fade=1,height=auto');
	animatedcollapse.init();
	animatedcollapse.hide('ticker_content');
		
function reload_ticker()
		{
		$("#ticker").load('ticker/ticker.php');
		}
		window.setTimeout(reload_ticker, 500);
		
		this.close();
		
function show_ticker()
		{
		animatedcollapse.addDiv('ticker_content', 'fade=1,height=auto');
		animatedcollapse.init();
		animatedcollapse.show('ticker_content');
		}
		window.setTimeout(show_ticker, 1000);
		
		} else {
    	document.getElementById("ticker_content").value = "<center>Sorry, your browser does not support server-sent events...</center>";
	}
}
// tooltip
$(document).ready(function(){
	$('[data-toggle="popover"]').popover();   
});
</script>
</body>
</html>
