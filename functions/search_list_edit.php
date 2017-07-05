<?php 
sleep(1);
//
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	//recieve data
	$id = $_REQUEST['id'];
	$action = $_REQUEST['action'];	
	
	if ($action == 'delete'){
	$sql = mysqli_query($dbmysqli, "DELETE FROM saved_search WHERE id = $id");
	
	// answer for ajax
	echo "data: done!\n\n";
	mysqli_close($dbmysqli);
	
	} else {
	
	$searchterm = rawurlencode($_REQUEST['searchterm']);
	$searcharea = utf8_decode($_REQUEST['searcharea']);
	$channel = $_REQUEST['channel'];
	$record_location = utf8_decode($_REQUEST['record_location']);
	$active = $_REQUEST['active'];
	
	// get channel name
	$sql = mysqli_query($dbmysqli, "SELECT e2servicename, servicename_enc FROM `channel_list` WHERE e2servicereference = '$channel'");
	$result = mysqli_fetch_assoc($sql);
	
	$e2servicename = $result['e2servicename'];
	$servicename_enc = $result['servicename_enc'];
	
	if ($channel == 'NULL'){ $e2servicename = 'NULL'; }
	if ($searchterm == ''){ $searchterm_sql = ''; } else { $searchterm_sql = 'searchterm = "'.$searchterm.'"'; }
	if ($searcharea == ''){ $searcharea_sql = ''; } else { $searcharea_sql = ', search_option = "'.$searcharea.'"'; }
	if ($channel == ''){ $channel_sql = ''; } else { $channel_sql = ', e2eventservicereference = "'.$channel.'", e2eventservicename = "'.$e2servicename.'", servicename_enc = "'.$servicename_enc.'" '; }
	if ($record_location == ''){ $rec_location_sql =  ''; } else { $rec_location_sql = ', e2location = "'.$record_location.'"'; }
	//if ($wildcard == ''){ $wildcard_sql = ''; } else { $wildcard_sql = ', wildcard = "'.$wildcard.'"'; }
	if ($active == ''){ $active_sql = ''; } else { $active_sql = ', activ = "'.$active.'"'; }
	
	$sql = mysqli_query($dbmysqli, "UPDATE saved_search SET $searchterm_sql$searcharea_sql$channel_sql$rec_location_sql$active_sql WHERE id = $id");
	
	// close db
	mysqli_close($dbmysqli);
	
	// answer for ajax
	echo "data: done!\n\n";
}
?>