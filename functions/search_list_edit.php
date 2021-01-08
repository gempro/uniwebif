<?php 
//
include("../inc/dashboard_config.php");

	sleep(1);

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	//recieve data
	$id = $_REQUEST['id'];
	$action = $_REQUEST['action'];	
	
	if($action == 'delete'){
	mysqli_query($dbmysqli, "DELETE FROM `saved_search` WHERE `id` = '$id' ");
	
	// answer for ajax
	echo "data:done";
	
	} else {
	
	$searchterm = rawurlencode($_REQUEST['searchterm']);
	$searcharea = utf8_decode($_REQUEST['searcharea']);
	$exclude_channel = strtolower($_REQUEST["exclude_channel"]);
	$exclude_channel = rawurlencode($exclude_channel);
	$exclude_title = strtolower($_REQUEST["exclude_title"]);
	$exclude_title = rawurlencode($exclude_title);
	$exclude_description = strtolower($_REQUEST["exclude_description"]);
	$exclude_description = rawurlencode($exclude_description);
	$exclude_extdescription = strtolower($_REQUEST["exclude_extdescription"]);
	$exclude_extdescription = rawurlencode($exclude_extdescription);
	$rec_replay = $_REQUEST['rec_replay'];
	$channel = $_REQUEST['channel'];
	$record_location = utf8_decode($_REQUEST['record_location']);
	$active = $_REQUEST['active'];
	
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
	
	// get record location id
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `record_locations` WHERE `e2location` = '".$record_location."' LIMIT 0,1");
	$result = mysqli_fetch_assoc($sql);
	$rec_location_id = $result['id'];
	
	// get channel name
	$sql = mysqli_query($dbmysqli, "SELECT `e2servicename`, `servicename_enc` FROM `channel_list` WHERE `e2servicereference` = '$channel' ");
	$result = mysqli_fetch_assoc($sql);
	
	$e2servicename = $result['e2servicename'];
	$servicename_enc = $result['servicename_enc'];
	
	if($channel == 'NULL'){ $e2servicename = 'NULL'; }
	if($searchterm == ''){ $searchterm_sql = ''; } else { $searchterm_sql = 'searchterm = "'.$searchterm.'"'; }
	if($searcharea == ''){ $searcharea_sql = ''; } else { $searcharea_sql = ', search_option = "'.$searcharea.'"'; }
	
	if($exclude_channel == ''){ $exclude_channel_sql = ', exclude_channel = ""'; $exclude_channel = ''; } else { $exclude_channel_sql = ', exclude_channel = "'.$exclude_channel.'"'; }
	if($exclude_title == ''){ $exclude_title_sql = ', exclude_title = ""'; $exclude_title = ''; } else { $exclude_title_sql = ', exclude_title = "'.$exclude_title.'"'; }
	if($exclude_description == ''){ $exclude_description_sql = ', exclude_description = ""'; } else { $exclude_description_sql = ', exclude_description = "'.$exclude_description.'"'; }
	if($exclude_extdescription == ''){ $exclude_extdescription_sql = ', exclude_extdescription = ""'; } else { $exclude_extdescription_sql = ', exclude_extdescription = "'.$exclude_extdescription.'"'; }
	if($rec_replay == 'yes'){ $rec_replay_sql = ', rec_replay = "on"'; } else { $rec_replay_sql = ', rec_replay = "off"'; }
	
	if($channel == ''){ $channel_sql = ''; } else { $channel_sql = ', e2eventservicereference = "'.$channel.'", e2eventservicename = "'.$e2servicename.'", servicename_enc = "'.$servicename_enc.'" '; }
	if($record_location == ''){ $rec_location_sql =  ''; } else { $rec_location_sql = ', e2location = "'.$record_location.'"'; }
	if($active == ''){ $active_sql = ''; } else { $active_sql = ', activ = "'.$active.'"'; }
	
	mysqli_query($dbmysqli, "UPDATE `saved_search` SET $searchterm_sql $searcharea_sql $exclude_channel_sql $exclude_title_sql $exclude_description_sql $exclude_extdescription_sql $rec_replay_sql $channel_sql $rec_location_sql, last_change = '$time', crawled = '0' $active_sql WHERE `id` = '$id' ");
	
	// answer for ajax
	if($time_format == "1")
	{
	$last_change = date("d.m.Y - H:i", $time);
	}
	if($time_format == "2" or $time_format == "")
	{
	$last_change = date("n/d/Y - g:i A", $time);
	}
	
	if($channel == '' or $channel == 'NULL')
	{
	$search_channel = '';
	$channel_id = '';
	
	} else {
	
	$search_channel = 'on';
	$channel_id = $channel; 
	}
	
	if($rec_replay == 'yes'){ $rec_replay = 'on'; } else { $rec_replay = 'off'; }
	
	$json['last_change'] = $last_change;
	$json['search_link'] = 'search.php?searchterm='.$searchterm.'&option='.$searcharea.'&record_location='.$rec_location_id.'&exclude_channel='.$exclude_channel.'&exclude_title='.$exclude_title.'&exclude_description='.$exclude_description.'&exclude_extdescription='.$exclude_extdescription.'&search_channel='.$search_channel.'&channel_id='.$channel_id.'&rec_replay='.$rec_replay.'&search_id='.$id.'';
	
	echo json_encode($json);
	
}
?>