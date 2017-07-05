<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_POST['set_crawl']) or $_POST['set_crawl'] == "") { $_POST['set_crawl'] = ""; } else { $_POST['set_crawl'] = $_POST['set_crawl']; }
	
	if(!isset($_POST['set_zap']) or $_POST['set_zap'] == "") { $_POST['set_zap'] = ""; } else { $_POST['set_zap'] = $_POST['set_zap']; }
	
	if(!isset($_POST['channel_id']) or $_POST['channel_id'] == "") { $_POST['channel_id'] = ""; } else { $_POST['channel_id'] = $_POST['channel_id']; }
	
	if(!isset($_REQUEST['set_crawl']) or $_REQUEST['set_crawl'] == "") { $_REQUEST['set_crawl'] = ""; } else { $_REQUEST['set_crawl'] = $_REQUEST['set_crawl']; }
	
	if(!isset($_REQUEST['set_zap']) or $_REQUEST['set_zap'] == "") { $_REQUEST['set_zap'] = ""; } else { $_REQUEST['set_zap'] = $_REQUEST['set_zap']; }
	
	if(!isset($_REQUEST['channel_id']) or $_REQUEST['channel_id'] == "") { $_REQUEST['channel_id'] = ""; } else { $_REQUEST['channel_id'] = $_REQUEST['channel_id']; }
	
	$set_crawl = $_REQUEST['set_crawl'];
	
	$set_zap = $_REQUEST['set_zap'];
	
	$channel_id = $_REQUEST['channel_id'];
	
	if(!isset($set_crawl) or $set_crawl == "") { $sql = mysqli_query($dbmysqli, "UPDATE channel_list SET zap = '".$set_zap."' WHERE id = '".$channel_id."'"); }
	
	if(!isset($set_zap) or $set_zap == "") { $sql = mysqli_query($dbmysqli, "UPDATE channel_list SET crawl = '".$set_crawl."' WHERE id = '".$channel_id."'"); }
	
	// ajax header
	sleep(1);
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data: \n\n"; 
?>