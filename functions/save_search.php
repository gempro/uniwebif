<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(!isset($_REQUEST['searchterm']) or $_REQUEST['searchterm'] == "") { $_REQUEST['searchterm'] = ""; }
	if(!isset($_REQUEST['option']) or $_REQUEST['option'] == "") { $_REQUEST['option'] = ""; }
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == "") { $_REQUEST['channel_id'] = ""; }
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == "") { $_REQUEST['record_location'] = "NULL"; }
	if(!isset($_REQUEST['exclude_term']) or $_REQUEST['exclude_term'] == "") { $_REQUEST['exclude_term'] = ""; }
	if(!isset($_REQUEST['exclude_area']) or $_REQUEST['exclude_area'] == "") { $_REQUEST['exclude_area'] = ""; }
	if(!isset($_REQUEST['rec_replay']) or $_REQUEST['rec_replay'] == "") { $_REQUEST['rec_replay'] = "off"; }
	
	$searchterm = rawurlencode($_REQUEST["searchterm"]);
	$search_option = $_REQUEST["option"];
	$channel_id = $_REQUEST["channel_id"];
	$record_location = $_REQUEST["record_location"];
	
	$exclude_term = rawurlencode($_REQUEST["exclude_term"]);
	$exclude_area = $_REQUEST["exclude_area"];
//	$exclude_term = str_replace("\"", "", $exclude_term);
//	$exclude_term = str_replace("'", "", $exclude_term);
//	$exclude_term = str_replace("%", "", $exclude_term);
	
	$rec_replay = $_REQUEST["rec_replay"];
	
	if ($channel_id !== ''){ $e2servicereference = $channel_id; } else { $e2servicereference = 'NULL'; }
	if ($exclude_term !== ''){ $exclude_term = $exclude_term; } else { $exclude_term = ''; }
	if ($exclude_area !== ''){ $exclude_area = $exclude_area; } else { $exclude_area = ''; }
	
	if ($searchterm == ''){ 
	
	mysqli_close($dbmysqli); exit; 
	
	} else {
	
	// get channel name
	$query = mysqli_query($dbmysqli, "SELECT e2servicename, servicename_enc FROM `channel_list` WHERE e2servicereference = '".$e2servicereference."' LIMIT 0 , 1");
	$result = mysqli_fetch_assoc($query);
	
	$e2servicename = $result['e2servicename'];
	$servicename_enc = $result['servicename_enc'];
	
	if ($e2servicename == ''){ $e2servicename = 'NULL'; }
	if ($servicename_enc == ''){ $e2servicename = 'NULL'; }
	
	// get record path
	$query = mysqli_query($dbmysqli, "SELECT e2location FROM `record_locations` WHERE id = '".$record_location."' LIMIT 0 , 1");
	$result = mysqli_fetch_assoc($query);
	$record_location = $result['e2location'];
	if ($record_location == ''){ $record_location = 'NULL'; }
	
	// check existing entries
	$sql = "SELECT * FROM `saved_search`";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {	
	{
	//
	if ($obj->searchterm == $searchterm and $obj->search_option == $search_option and $obj->e2location == $record_location and $obj->e2eventservicereference == $e2servicereference and $obj->exclude_term == $exclude_term and $obj->exclude_area == $exclude_area and $obj->rec_replay == $rec_replay){
	
	echo "data: save search - nok!\n\n"; mysqli_free_result($result); mysqli_close($dbmysqli); exit; }
	}
	}
    }
	
	if ($time_format == '1')
	{
	// time format 1
	$save_date = date("d.m.Y", $time);
	}
	if ($time_format == '2')
	{
	// time format 2
	$save_date = date("n/d/Y", $time);
	}
	
	//write in db
   $sql = mysqli_query($dbmysqli, "INSERT INTO saved_search (searchterm, search_option, exclude_term, exclude_area, e2location, save_date, e2eventservicereference, e2eventservicename, servicename_enc, activ, rec_replay) values ('$searchterm','$search_option','$exclude_term','$exclude_area','$record_location','$save_date','$e2servicereference','$e2servicename','$servicename_enc','yes','$rec_replay')");
	}	
	// ajax header
	echo "data: save search - done!\n\n";
	//close db
mysqli_close($dbmysqli);
?>