<?php 
//
include("../inc/dashboard_config.php");

	// ajax header
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	if(!isset($_REQUEST['search_id']) or $_REQUEST['search_id'] == "") { $_REQUEST['search_id'] = ""; }
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == "") { $_REQUEST['action'] = ""; }
	if(!isset($_REQUEST['searchterm']) or $_REQUEST['searchterm'] == "") { $_REQUEST['searchterm'] = ""; }
	if(!isset($_REQUEST['option']) or $_REQUEST['option'] == "") { $_REQUEST['option'] = ""; }
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == "") { $_REQUEST['channel_id'] = ""; }
	if(!isset($_REQUEST['record_location']) or $_REQUEST['record_location'] == "") { $_REQUEST['record_location'] = "NULL"; }
	if(!isset($_REQUEST['exclude_title']) or $_REQUEST['exclude_title'] == "") { $_REQUEST['exclude_title'] = ""; }
	if(!isset($_REQUEST['exclude_channel']) or $_REQUEST['exclude_channel'] == "") { $_REQUEST['exclude_channel'] = ""; }
	if(!isset($_REQUEST['exclude_description']) or $_REQUEST['exclude_description'] == "") { $_REQUEST['exclude_description'] = ""; }
	if(!isset($_REQUEST['exclude_extdescription']) or $_REQUEST['exclude_extdescription'] == "") { $_REQUEST['exclude_extdescription'] = ""; }
	if(!isset($_REQUEST['rec_replay']) or $_REQUEST['rec_replay'] == "") { $_REQUEST['rec_replay'] = "off"; }
	
	$search_id = $_REQUEST["search_id"];
	$action = $_REQUEST["action"];
	$searchterm = rawurlencode($_REQUEST["searchterm"]);
	$search_option = $_REQUEST["option"];
	$channel_id = $_REQUEST["channel_id"];
	$record_location = $_REQUEST["record_location"];
	
	$exclude_channel = strtolower($_REQUEST["exclude_channel"]);
	$exclude_channel = rawurlencode($exclude_channel);
	$exclude_title = strtolower($_REQUEST["exclude_title"]);
	$exclude_title = rawurlencode($exclude_title);
	$exclude_description = strtolower($_REQUEST["exclude_description"]);
	$exclude_description = rawurlencode($exclude_description);
	$exclude_extdescription = strtolower($_REQUEST["exclude_extdescription"]);
	$exclude_extdescription = rawurlencode($exclude_extdescription);
	$rec_replay = $_REQUEST["rec_replay"];
	
	if ($channel_id !== ''){ $e2servicereference = $channel_id; } else { $e2servicereference = 'NULL'; }
	if ($exclude_channel !== ''){ $exclude_channel = $exclude_channel; } else { $exclude_channel = ''; }
	if ($exclude_title !== ''){ $exclude_title = $exclude_title; } else { $exclude_title = ''; }
	if ($exclude_description !== ''){ $exclude_description = $exclude_description; } else { $exclude_description = ''; }
	if ($exclude_extdescription !== ''){ $exclude_extdescription = $exclude_extdescription; } else { $exclude_extdescription = ''; }
	
	if ($searchterm == ''){ 
	
	exit; 
	
	} else {
	
	// get channel name
	$query = mysqli_query($dbmysqli, "SELECT e2servicename, servicename_enc FROM `channel_list` WHERE `e2servicereference` = '".$e2servicereference."' LIMIT 0 , 1");
	$result = mysqli_fetch_assoc($query);
	
	$e2servicename = $result['e2servicename'];
	$servicename_enc = $result['servicename_enc'];
	
	if ($e2servicename == ''){ $e2servicename = 'NULL'; }
	if ($servicename_enc == ''){ $e2servicename = 'NULL'; }
	
	// get record path
	$query = mysqli_query($dbmysqli, "SELECT `e2location` FROM `record_locations` WHERE `id` = '".$record_location."' LIMIT 0 , 1");
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
	if ($obj->searchterm == $searchterm and $obj->search_option == $search_option and $obj->e2location == $record_location and $obj->e2eventservicereference == $e2servicereference and $obj->exclude_channel == $exclude_channel and $obj->exclude_title == $exclude_title and $obj->exclude_description == $exclude_description and $obj->exclude_extdescription == $exclude_extdescription and $obj->rec_replay == $rec_replay and $action != 'update'){
	
	echo "data:save nok"; 
	exit;
	}
	}
	}
    }
	
	$save_date = $time;
	
	// sort excluded
	// channel
	$terms = $exclude_channel;
	$sorted_channel = explode("%3B", $terms); 
	sort($sorted_channel, SORT_STRING); 
	$exclude_channel = implode("%3B", $sorted_channel);
	
	// title
	$terms = $exclude_title;
	$sorted_title = explode("%3B", $terms); 
	sort($sorted_title, SORT_STRING); 
	$exclude_title = implode("%3B", $sorted_title);
	
	// description
	$terms = $exclude_description;
	$sorted_description = explode("%3B", $terms); 
	sort($sorted_description, SORT_STRING); 
	$exclude_description = implode("%3B", $sorted_description);
	
	// extended description
	$terms = $exclude_extdescription;
	$sorted_extdescription = explode("%3B", $terms); 
	sort($sorted_extdescription, SORT_STRING); 
	$exclude_extdescription = implode("%3B", $sorted_extdescription);
	
	if($action == "update")
	{
	$sql = mysqli_query($dbmysqli, "
	UPDATE `saved_search` SET searchterm = '$searchterm', search_option = '$search_option', exclude_channel = '$exclude_channel', exclude_title = '$exclude_title', 
	exclude_description = '$exclude_description', exclude_extdescription = '$exclude_extdescription', e2location = '$record_location', last_change = '$time', crawled = '0',
	save_date = '$save_date', e2eventservicereference = '$e2servicereference', e2eventservicename = '$e2servicename', servicename_enc = '$servicename_enc', rec_replay = '$rec_replay' WHERE `id` = '$search_id' ");
	echo "data:update done";
	}
	
	if($action == "save")
	{
   $sql = mysqli_query($dbmysqli, "INSERT INTO `saved_search` (searchterm, search_option, exclude_channel, exclude_title, exclude_description, exclude_extdescription, e2location, save_date, e2eventservicereference, e2eventservicename, servicename_enc, activ, rec_replay) VALUES ('$searchterm', '$search_option', '$exclude_channel', '$exclude_title', '$exclude_description', '$exclude_extdescription', '$record_location', '$save_date', '$e2servicereference', '$e2servicename', '$servicename_enc', 'yes', '$rec_replay')");
   
   echo "data:save done";
   }
}
?>