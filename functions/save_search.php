<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(!isset($_REQUEST['searchterm']) or $_REQUEST['searchterm'] == "") { $_REQUEST['searchterm'] = ""; } else { $_REQUEST['searchterm'] = $_REQUEST['searchterm']; }
	
	if(!isset($_REQUEST['option']) or $_REQUEST['option'] == "") { $_REQUEST['option'] = ""; } else { $_REQUEST['option'] = $_REQUEST['option']; }
	
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == "") { $_REQUEST['record_location'] = "NULL"; } else { $_REQUEST['record_location'] = $_REQUEST['record_location']; }
	
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == "") { $_REQUEST['channel_id'] = ""; } else { $_REQUEST['channel_id'] = $_REQUEST['channel_id']; }	
	
	$searchterm = rawurlencode($_REQUEST["searchterm"]);
	
	$search_option = $_REQUEST["option"];
	
	$record_location = $_REQUEST["record_location"];
	
	$channel_id = $_REQUEST["channel_id"];
	
	if ($_REQUEST["channel_id"] !== ''){ $e2servicereference = $channel_id; } else { $e2servicereference = 'NULL'; }
	
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
	if ($obj->searchterm == $searchterm and $obj->search_option == $search_option and $obj->e2location == $record_location and $obj->wildcard == $wildcard and $obj->e2eventservicereference == $e2servicereference){
	
	echo "data: save search - nok!\n\n"; mysqli_free_result($result); mysqli_close($dbmysqli); exit; }
	}
	}
    }
	//write in db
   $sql = mysqli_query($dbmysqli, "INSERT INTO saved_search (searchterm, search_option, e2location, save_date, e2eventservicereference, e2eventservicename, servicename_enc, activ) values ('$searchterm','$search_option','$record_location','$thedate','$e2servicereference','$e2servicename','$servicename_enc','yes')");
	}	
	// ajax header
	echo "data: save search - done!\n\n";
	//close db
mysqli_close($dbmysqli);
?>