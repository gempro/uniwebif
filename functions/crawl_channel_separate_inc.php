<?php 
//
include("../inc/dashboard_config.php");
	
	$sql = "SELECT * FROM channel_list ORDER BY e2servicename ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	{
	
	// last crawl
	$last_crawl = date("d.m.Y", $obj->last_crawl);
	
	// latest epg entry
	$sql = mysqli_query($dbmysqli, "SELECT e2eventend FROM `epg_data` WHERE channel_hash = '".$obj->channel_hash."' ORDER BY e2eventend DESC LIMIT 0 , 1");
	$result2 = mysqli_fetch_assoc($sql);
	if ($time_format == '1')
	{
	// time format 1
	$e2eventend = $result2['e2eventend'];
	$date_last = date("d.m.Y, H:i", $e2eventend);
	if ($date_last == '01.01.1970, 01:00'){ $date_last = "no data"; }
	$last_crawl = date("d.m.Y", $obj->last_crawl);
	if ($last_crawl == '01.01.1970'){ $last_crawl = "no data"; }
	}
	if ($time_format == '2')
	{
	// time format 2
	$e2eventend = $result2['e2eventend'];
	$date_last = date("n/d/Y, g:i A", $e2eventend);
	if ($date_last == '1/01/1970, 1:00 AM'){ $date_last = "no data"; }
	$last_crawl = date("n/d/Y", $obj->last_crawl);
	if ($last_crawl == '1/01/1970'){ $last_crawl = "no data"; }
	}
	
	//count entries
	$stmt = $dbmysqli->prepare("SELECT COUNT(*) AS sum_entries FROM `epg_data` WHERE channel_hash = '".$obj->channel_hash."' ");
	if( !is_a($stmt, 'MySQLI_Stmt') || $dbmysqli->errno > 0 )
	throw new Exception( $dbmysqli->error, $dbmysqli->errno );
		
	$stmt->execute();
	$stmt->bind_result($sum_entries);
	$stmt->fetch();
	$stmt->close();
	
	if ($sum_entries < $start_epg_crawler or $sum_entries == '0'){ $sum_entries = '<strong>'.$sum_entries.'</strong>'; }
	
	if(!isset($channel_list) or $channel_list == "") { $channel_list = ""; } else { $channel_list = $channel_list; }
	$channel_list = $channel_list. "
	<div class=\"col-md-2\">
	<input id=\"channel_crawler_zap_$obj->channel_hash\" type=\"submit\" onClick=\"channel_crawler_zap(this.id)\" value=\"Zapp\" class=\"btn btn-xs btn-default\"/>
	<input id=\"channel_crawler_$obj->channel_hash\" type=\"submit\" onClick=\"channel_crawler(this.id)\" value=\"Crawl channel\" class=\"btn btn-xs btn-default\"/>
	</div>
	<div class=\"col-md-6\">$obj->e2servicename
	<span id=\"channel_crawler_status_zap_$obj->channel_hash\"></span>
	<span id=\"channel_crawler_status_$obj->channel_hash\"></span>
	</div>
	<div class=\"col-md-4\">
	$date_last | $last_crawl | $sum_entries
	</div>
	<div class=\"spacer_10\"></div>";
	}
	}
	echo utf8_encode($channel_list);
	
	// Free result set
	mysqli_free_result($result);
}
?>