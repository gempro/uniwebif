<!DOCTYPE html>
<html>
<head>

</head>
<body>
<?php 
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['e2servicereference']) or $_REQUEST['e2servicereference'] == ''){ $_REQUEST['e2servicereference'] = ''; }
	$e2servicereference = $_REQUEST['e2servicereference'];
	
	// channel dropdown
	$sql = "SELECT * FROM `channel_list` ORDER BY `".$sort_quickpanel."` ASC";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	if($obj->qp_selected == '1'){ $select = 'selected="selected"'; } else { $select = ''; }
	if(!isset($channel_dropdown) or $channel_dropdown == ''){ $channel_dropdown = ''; }
	$channel_dropdown .= '<option value="'.utf8_encode($obj->e2servicereference).'" '.$select.'>'.utf8_encode($obj->e2servicename).'</option>';
	}
	}
	}
	
	// record locations dropdown
	$sql = "SELECT * FROM `record_locations` WHERE `device` = '0' ORDER BY `id` ASC";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {	
	{
	if(!isset($rec_location_dropdown) or $rec_location_dropdown == ''){ $rec_location_dropdown = ''; }
	$rec_location_dropdown .= '<option value="'.$obj->id.'">'.$obj->e2location.'</option>'; }
	}
	}
	
	// device dropdown
	$sql_2 = "SELECT * FROM `device_list` ORDER BY `id` ASC";
	if ($result_2 = mysqli_query($dbmysqli,$sql_2))
	{
	while ($obj = mysqli_fetch_object($result_2)){
	{
	$id = $obj->id;
	$device_description = rawurldecode($obj->device_description);
	if(!isset($device_dropdown) or $device_dropdown == ''){ $device_dropdown = ''; }
	$device_dropdown .= '<option value="'.$id.'">'.$device_description.'</option>'; 
	}
	}
	}
	
	//
	if($time_format == '2')
	{ 
	$am_pm_start = '
	<select id="am_pm_start">
	<option value="AM">AM</option>
	<option value="PM">PM</option>
	</select>'; 
	
	$am_pm_end = '
	<select id="am_pm_end">
	<option value="AM">AM</option>
	<option value="PM">PM</option>
	</select>';
	
	} else { $am_pm_start = ''; $am_pm_end = ''; }
	
	$day = date('j', time());
	$month = date('n', time());
	$y1 = date('Y', time());
	$y2 = $y1 + 1;
	
	$day_list = '';
	for ($i = 1; $i <= 31; $i++) {
	if($day == $i){ $selected = 'selected="selected"'; } else { $selected = ''; }
	$day_list .= '<option '.$selected.'>'.$i.'</option>';
	}
	
	$month_list = '';
	for ($i = 1; $i <= 12; $i++) {
	if($month == $i){ $selected = 'selected="selected"'; } else { $selected = ''; }
	$month_list .= '<option '.$selected.'>'.$i.'</option>';
	}
	
	echo '
	<div class="col-md-9">
	<div class="col-md-4">
	<select id="manual_timer" class="manual_timer_form" style="width:100%">
	<option value="record">Record</option>
	<option value="zap">Zap</option>
	</select>
	</div><!-- col-->
	<div class="col-md-8">
	<select id="manual_timer_channel" class="manual_timer_form" style="width:100%">
	'.$channel_dropdown.'
	</select>
	</div><!-- col-->
	<div class="spacer_10"></div>
	<div class="col-md-4">
	<select id="manual_timer_device" class="manual_timer_form" style="width:100%" onChange="modal_timer_device()">
	<option value="0">default</option>
	'.$device_dropdown.'
	</select>
	</div><!-- col-->
	<div class="col-md-8">
	<select id="rec_location_manual_timer" style="width:100%">
	'.$rec_location_dropdown.'
	</select>
	</div><!-- col-->
	<div class="spacer_10"></div>
	<div class="col-md-12">
	<input id="manual_timer_title" style="width:100%" type="text" placeholder="Title">
	</div><!-- col-->
	<div class="spacer_10"></div>
	<div class="col-md-12">
	<input id="manual_timer_description" style="width:100%" type="text" placeholder="Description">
	</div><!-- col-->
	<div class="spacer_10"></div>
	<div class="col-md-12">
	<strong>Start:</strong> Day
	<select id="start_day" class="manual_timer_form2">
	'.$day_list.'
	</select>
	Month 
	<select id="start_month" class="manual_timer_form2">
	'.$month_list.'
	</select>
	Year 
	<select id="start_year" class="manual_timer_form2">
	<option>'.$y1.'</option>
	<option>'.$y2.'</option>
	</select>
	&nbsp;<input id="start_hour" class="manual_timer_t manual_timer_form2" type="text" placeholder="hh" maxlength="2">
	&nbsp;<input id="start_minute" class="manual_timer_t manual_timer_form2" type="text" placeholder="mm" maxlength="2">'.$am_pm_start.'
	</div><!-- col-->
	<div class="spacer_10"></div>
	<div class="col-md-12">
	<strong>End:</strong>&nbsp;&nbsp;&nbsp;Day
	<select id="end_day" class="manual_timer_form2">
	'.$day_list.'
	</select>
	Month 
	<select id="end_month" class="manual_timer_form2">
	'.$month_list.'
	</select>
	Year 
	<select id="end_year" class="manual_timer_form2">
	<option>'.$y1.'</option>
	<option>'.$y2.'</option>
	</select>
	&nbsp;<input id="end_hour" class="manual_timer_t manual_timer_form2" type="text" placeholder="hh" maxlength="2">
	&nbsp;<input id="end_minute" class="manual_timer_t manual_timer_form2" type="text" placeholder="mm" maxlength="2">'.$am_pm_end.'
	</div><!-- col-->
	</div><!-- col-->
	<div class="col-md-3">Repeat timer:<br>
	<input id="week31" value="31" name="tdays[]" data="mon" type="checkbox" onClick="timer_days(\'31\')"> Mon - Fri<br>
	<input id="week127" value="127" name="tdays[]" data="mon" type="checkbox" onClick="timer_days(\'127\')"> Mon - Sun<br>
	<input id="day1" value="1" name="tdays[]" data="mon" type="checkbox" onClick="timer_days(\'1\')"> Mon<br>
	<input id="day2" value="2" name="tdays[]" data="tue" type="checkbox" onClick="timer_days(\'2\')"> Tue<br>
	<input id="day3" value="4" name="tdays[]" data="wed" type="checkbox" onClick="timer_days(\'4\')"> Wed<br>
	<input id="day4" value="8" name="tdays[]" data="thu" type="checkbox" onClick="timer_days(\'8\')"> Thu<br>
	<input id="day5" value="16" name="tdays[]" data="fri" type="checkbox" onClick="timer_days(\'16\')"> Fri<br>
	<input id="day6" value="32" name="tdays[]" data="sat" type="checkbox" onClick="timer_days(\'32\')"> Sat<br>
	<input id="day7" value="64" name="tdays[]" data="sun" type="checkbox" onClick="timer_days(\'64\')"> Sun<br>
	</div>
	<div class="col-md-12">
	<button class="btn btn-success btn-sm" onClick="manual_timer(\'set_timer\')">SET TIMER</button>
	<span id="manual_timer_status"></span>
	</div><!-- col-->
	';
	
?>

</body>
</html>
