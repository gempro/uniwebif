<?php 
//
	include("../inc/dashboard_config.php");

	sleep(1);
	
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ''){ $_REQUEST['action'] = ''; }
	if(!isset($_REQUEST['channel_name']) or $_REQUEST['channel_name'] == ''){ $_REQUEST['channel_name'] = ''; }
	if(!isset($_REQUEST['service_reference']) or $_REQUEST['service_reference'] == ''){ $_REQUEST['service_reference'] = ''; }
	if(!isset($_REQUEST['iptv_channel']) or $_REQUEST['iptv_channel'] == ''){ $_REQUEST['iptv_channel'] = ''; }
	
	if(!isset($_REQUEST['set_crawl']) or $_REQUEST['set_crawl'] == ''){ $_REQUEST['set_crawl'] = ''; }
	if(!isset($_REQUEST['set_zap']) or $_REQUEST['set_zap'] == ''){ $_REQUEST['set_zap'] = ''; }
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == ''){ $_REQUEST['channel_id'] = ''; }
	
	$action = $_REQUEST['action'];
	$channel_name = $_REQUEST['channel_name'];
	$service_reference = $_REQUEST['service_reference'];
	$iptv_channel = $_REQUEST['iptv_channel'];
	
	$set_crawl = $_REQUEST['set_crawl'];
	$set_zap = $_REQUEST['set_zap'];
	$channel_id = $_REQUEST['channel_id'];
	
	if($action == 'add')
	{
	if($channel_name == '' or $service_reference == ''){ echo 'data:error'; exit; }

	$servicename_enc = rawurlencode($channel_name);
	$channel_name = utf8_decode($channel_name);
	$channel_hash = hash('md4',$channel_name);
	$provider = '-';
	$selected = '0';
	$crawl = '1';
	$zap = '1';

	//
	if($iptv_channel == '1')
	{
	$p = '4097:0:1:0:0:0:0:0:0:0:';
	$p2 = str_replace(' ', '+', $servicename_enc);
	$service_reference = str_replace(':', '%253a', $service_reference);
	$service_reference = str_replace('/', '%2F', $service_reference);
	$service_reference = $p.$service_reference;
	$provider = 'IPTV;';
	$crawl = '0';
	$zap = '0';
	}
	
	$sql = mysqli_query($dbmysqli, "SELECT COUNT(id) FROM `channel_list` WHERE `e2servicereference` LIKE '".$service_reference."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($summary != 0){ echo 'data:exists'; exit; }
	
	mysqli_query($dbmysqli, "INSERT INTO `channel_list` 
	(
	e2servicename, 
	servicename_enc, 
	e2servicereference, 
	e2providername, 
	selected, 
	crawl, 
	zap, 
	channel_hash
	) VALUES ( 
	'".$channel_name."', 
	'".$servicename_enc."', 
	'".$service_reference."', 
	'".$provider."', 
	'0', 
	'".$crawl."', 
	'".$zap."', 
	'".$channel_hash."'
	) 
	");
	} // add channel
	
	if(!isset($set_crawl) or $set_crawl == ''){ mysqli_query($dbmysqli, "UPDATE `channel_list` SET `zap` = '".$set_zap."' WHERE `id` = '".$channel_id."'"); $color = '#428BCA'; }
	
	if(!isset($set_zap) or $set_zap == ''){ mysqli_query($dbmysqli, "UPDATE `channel_list` SET `crawl` = '".$set_crawl."' WHERE `id` = '".$channel_id."'"); $color = '#5CB85C'; }
	
	echo 'data:done';
	
?>