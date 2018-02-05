<?php 
//
include_once("inc/dashboard_config.php");

	// epg info header
	// select oldest entry
	$query = mysqli_query($dbmysqli, "SELECT e2eventservicename, e2eventstart FROM `epg_data` ORDER BY e2eventstart ASC LIMIT 0 , 1");
	$first_entry = mysqli_fetch_assoc($query);
	
	if ($time_format == '1')
	{
	// time format 1
	$date_first = date("d.m.Y H:i", $first_entry['e2eventstart']);
	}
	if ($time_format == '2')
	{
	// time format 2
	$date_first = date("n/d/Y g:i A", $first_entry['e2eventstart']);
	}
	
	// select latest entry
	$query = mysqli_query($dbmysqli, "SELECT e2eventservicename, e2eventstart FROM `epg_data` ORDER BY e2eventstart DESC LIMIT 0 , 1");
	$last_entry = mysqli_fetch_assoc($query);
	
	if ($time_format == '1')
	{
	// time format 1
	$date_latest = date("d.m.Y H:i", $last_entry['e2eventstart']);
	}
	if ($time_format == '2')
	{
	// time format 2
	$date_latest = date("n/d/Y g:i A", $last_entry['e2eventstart']);
	}
	
	if ($date_first == '' or $date_first == '01.01.1970 01:00' or $date_first == '1/01/1970 1:00 AM'){ $date_first = 'no data'; }
	if ($date_latest == '' or $date_latest == '01.01.1970 01:00' or $date_latest == '1/01/1970 1:00 AM'){ $date_latest = 'no data'; }
	if ($first_entry['e2eventservicename'] == ''){ $first_entry['e2eventservicename'] = 'no data'; }	
	if ($last_entry['e2eventservicename'] == ''){ $last_entry['e2eventservicename'] = 'no data'; }
	
	if ($time_format == '1'){ $header_date = date("D, d.m.Y", $time); }
	if ($time_format == '2'){ $header_date = date("D, n/d/Y", $time); }

?>