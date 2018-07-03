<?php 
//
include("../inc/dashboard_config.php");

	sleep(1);

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	//recieve data	
	$crawl_bouquet = $_REQUEST['crawl_bouquet'];
	$bouquet_id = $_REQUEST['bouquet_id'];
	
	if(!isset($crawl_bouquet) or $crawl_bouquet == "") { $crawl_bouquet = ""; }
	if(!isset($bouquet_id) or $bouquet_id == "") { $bouquet_id = ""; }
	
	if(!isset($crawl_bouquet) or $crawl_bouquet == "" or !isset($bouquet_id) or $bouquet_id == "") 
	{ 
	echo "data:error"; 
	
	} else { 
	
	$sql = mysqli_query($dbmysqli, "UPDATE `bouquet_list` SET `crawl` = '$crawl_bouquet' WHERE `id` = '$bouquet_id' ");
	
	// answer for ajax
	echo "data:done";

}
?>