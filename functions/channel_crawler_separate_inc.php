<?php 
//
include("../inc/dashboard_config.php");

	sleep(1);

	$channel = $_REQUEST['channel'];
	
	if($channel == 'all'){ 
	
	$sql = 'SELECT * FROM `channel_list` ORDER BY `e2servicename` ASC';
	
	} else { 
	
	$sql = 'SELECT * FROM `channel_list` WHERE `e2servicereference` = "'.$channel.'" '; 
	
	}
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	
	// last crawl
	$last_crawl = date("d.m.Y", $obj->last_crawl);
	
	// latest epg entry
	if($channel == 'all'){
	$sql = mysqli_query($dbmysqli, "SELECT `e2eventend` FROM `epg_data` WHERE `e2eventservicereference` = '".$obj->e2servicereference."' ORDER BY `e2eventend` DESC LIMIT 0 , 1");
	
	} else {
	
	$sql = mysqli_query($dbmysqli, "SELECT `e2eventend` FROM `epg_data` WHERE `e2eventservicereference` = '".$channel."' ORDER BY `e2eventend` DESC LIMIT 0 , 1");
	}
	$result2 = mysqli_fetch_assoc($sql);
	if($time_format == '1')
	{
	// time format 1
	$e2eventend = $result2['e2eventend'];
	$date_last = date("d.m.Y, H:i", $e2eventend);
	if($date_last == '01.01.1970, 01:00'){ $date_last = "no data"; }
	$last_crawl = date("d.m.Y", $obj->last_crawl);
	if($last_crawl == '01.01.1970'){ $last_crawl = "no data"; }
	}
	if($time_format == '2')
	{
	// time format 2
	$e2eventend = $result2['e2eventend'];
	$date_last = date("n/d/Y, g:i A", $e2eventend);
	if ($date_last == '1/01/1970, 1:00 AM'){ $date_last = "no data"; }
	$last_crawl = date("n/d/Y", $obj->last_crawl);
	if ($last_crawl == '1/01/1970'){ $last_crawl = "no data"; }
	}
	
	//count entries
	if($channel == 'all'){
	$sql3 = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `epg_data` WHERE `e2eventservicereference` = "'.$obj->e2servicereference.'" ');
	
	} else {
	
	$sql3 = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `epg_data` WHERE `e2eventservicereference` = "'.$channel.'" ');
	}
	$result3 = mysqli_fetch_row($sql3);
	$sum_entries = $result3[0];
	
	if($sum_entries < $start_epg_crawler or $sum_entries == '0'){ $sum_entries = '<strong>'.$sum_entries.'</strong>'; }
	
	if(!isset($channel_list) or $channel_list == "") { $channel_list = ""; }
	
	$header = "
	<div class=\"col-md-6\"></div>
	<div class=\"col-md-2\">Latest EPG</div>
	<div class=\"col-md-2\">Last crawl</div>
	<div class=\"col-md-2\">Entries</div>
	<div class=\"spacer_20\"></div>";
	
	$channel_list = $channel_list. "
	<div class=\"col-md-2\">
	<input id=\"channel_crawler_zap_$obj->channel_hash\" name=\"$obj->e2servicereference\" type=\"submit\" onClick=\"channel_crawler_zap(this.id,this.name)\" value=\"Zap\" class=\"btn btn-xs btn-default\"/>
	<input id=\"channel_crawler_$obj->channel_hash\" type=\"submit\" onClick=\"channel_crawler(this.id)\" value=\"Crawl channel\" class=\"btn btn-xs btn-default\"/>
	</div>
	<div class=\"col-md-4\">$obj->e2servicename
	<span id=\"channel_crawler_status_zap_$obj->channel_hash\"></span>
	<span id=\"channel_crawler_status_$obj->channel_hash\"></span>
	</div>
	<div class=\"col-md-2\">$date_last</div>
	<div class=\"col-md-2\">$last_crawl</div>
	<div class=\"col-md-2\">$sum_entries</div>
	<div class=\"spacer_10\"></div>";
	}
	}
	
	if(!isset($channel_list) or $channel_list == ""){ $channel_list = "No channels to display..."; }
	if(!isset($header) or $header == ""){ $header = ""; }
	
	echo utf8_encode($header.$channel_list.'<hr>');

}
?>