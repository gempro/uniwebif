<?php 
//
include("../inc/dashboard_config.php");
	
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(!isset($_REQUEST['device']) or $_REQUEST['device'] == "") { $_REQUEST['device'] = ""; }
	$device = $_REQUEST['device'];
	
	if($device != "0"){
	$sql = mysqli_query($dbmysqli ,"SELECT * FROM `device_list` WHERE `id` = '".$device."' ");
	$result = mysqli_fetch_assoc($sql);
	
	$rec_location0 = $result['rec_location0'];
	$rec_location1 = $result['rec_location1'];
	$rec_location2 = $result['rec_location2'];
	$rec_location3 = $result['rec_location3'];
	$rec_location4 = $result['rec_location4'];
	$rec_location5 = $result['rec_location5'];
	$rec_location6 = $result['rec_location6'];
	$rec_location7 = $result['rec_location7'];
	$rec_location8 = $result['rec_location8'];
	$rec_location9 = $result['rec_location9'];
	
	if($rec_location0 != ""){ $option_0 = "<option value=\"".$rec_location0."\">".$rec_location0."</option>"; } else { $option_0 = ""; }
	if($rec_location1 != ""){ $option_1 = "<option value=\"".$rec_location1."\">".$rec_location1."</option>"; } else { $option_1 = ""; }
	if($rec_location2 != ""){ $option_2 = "<option value=\"".$rec_location2."\">".$rec_location2."</option>"; } else { $option_2 = ""; }
	if($rec_location3 != ""){ $option_3 = "<option value=\"".$rec_location3."\">".$rec_location3."</option>"; } else { $option_3 = ""; }
	if($rec_location4 != ""){ $option_4 = "<option value=\"".$rec_location4."\">".$rec_location4."</option>"; } else { $option_4 = ""; }
	if($rec_location5 != ""){ $option_5 = "<option value=\"".$rec_location5."\">".$rec_location5."</option>"; } else { $option_5 = ""; }
	if($rec_location6 != ""){ $option_6 = "<option value=\"".$rec_location6."\">".$rec_location6."</option>"; } else { $option_6 = ""; }
	if($rec_location7 != ""){ $option_7 = "<option value=\"".$rec_location7."\">".$rec_location7."</option>"; } else { $option_7 = ""; }
	if($rec_location8 != ""){ $option_8 = "<option value=\"".$rec_location8."\">".$rec_location8."</option>"; } else { $option_8 = ""; }
	if($rec_location9 != ""){ $option_9 = "<option value=\"".$rec_location9."\">".$rec_location9."</option>"; } else { $option_9 = ""; }
	
	$device_dropdown = "
	".$option_0."
	".$option_1."
	".$option_2."
	".$option_3."
	".$option_4."
	".$option_5."
	".$option_6."
	".$option_7."
	".$option_8."
	".$option_9."
	";
	
	echo $device_dropdown;
	exit;
	} // if device 0
	
	else {
	
	// record location in dropdown
	$sql = "SELECT * FROM `record_locations` ORDER BY `id` ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){
	{
	
	if(!isset($device_dropdown) or $device_dropdown == "") { $device_dropdown = ""; }

	$device_dropdown = $device_dropdown."<option name=\"$obj->id\" value=\"$obj->e2location\">$obj->e2location</option>
	"; }
	}
	}
	}
	echo $device_dropdown;
?>