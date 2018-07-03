<?php 
//
include("../inc/dashboard_config.php");

	sleep(1);

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	if(!isset($_POST['set_crawl']) or $_POST['set_crawl'] == "") { $_POST['set_crawl'] = ""; }
	if(!isset($_POST['set_zap']) or $_POST['set_zap'] == "") { $_POST['set_zap'] = ""; }
	if(!isset($_POST['channel_id']) or $_POST['channel_id'] == "") { $_POST['channel_id'] = ""; }
	if(!isset($_REQUEST['set_crawl']) or $_REQUEST['set_crawl'] == "") { $_REQUEST['set_crawl'] = ""; }
	if(!isset($_REQUEST['set_zap']) or $_REQUEST['set_zap'] == "") { $_REQUEST['set_zap'] = ""; }
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == "") { $_REQUEST['channel_id'] = ""; }
	
	$set_crawl = $_REQUEST['set_crawl'];
	$set_zap = $_REQUEST['set_zap'];
	$channel_id = $_REQUEST['channel_id'];
	
	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == "") { $_REQUEST['action'] = ""; }
	if(!isset($_REQUEST['channel_name']) or $_REQUEST['channel_name'] == "") { $_REQUEST['channel_name'] = ""; }
	if(!isset($_REQUEST['service_reference']) or $_REQUEST['service_reference'] == "") { $_REQUEST['service_reference'] = ""; }
	
	$action = $_REQUEST['action'];
	$channel_name = $_REQUEST['channel_name'];
	$service_reference = $_REQUEST['service_reference'];
	
	if($action == 'add')
	{
	
	if ($channel_name == '' or $service_reference == ''){ echo "data:error"; exit; }
	
	$servicename_enc = rawurlencode($channel_name);
	$channel_name = utf8_decode($channel_name);
	$channel_hash = hash('md4',$channel_name);
	$service_reference = $_REQUEST['service_reference'];
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO `channel_list` (e2servicename, servicename_enc, e2servicereference, selected, crawl, zap, channel_hash) 
	values ('".$channel_name."', '".$servicename_enc."', '".$service_reference."', '0', '1', '1', '".$channel_hash."') ");
	}
	
	if(!isset($set_crawl) or $set_crawl == ""){ $sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `zap` = '".$set_zap."' WHERE `id` = '".$channel_id."'"); $color = '#428BCA'; }
	
	if(!isset($set_zap) or $set_zap == ""){ $sql = mysqli_query($dbmysqli, "UPDATE `channel_list` SET `crawl` = '".$set_crawl."' WHERE `id` = '".$channel_id."'"); $color = '#5CB85C'; }
	
	echo "data:done";
?>