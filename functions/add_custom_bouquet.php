<?php 
//
include("../inc/dashboard_config.php");

	sleep(1);

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	//recieve data
	if(!isset($custom_bouquet_url) or $custom_bouquet_url == "") { $custom_bouquet_url = ""; }
	if(!isset($custom_bouquet_title) or $custom_bouquet_title == "") { $custom_bouquet_title = ""; }
	
	$custom_bouquet_url = utf8_decode($_REQUEST['custom_bouquet_url']);
	$custom_bouquet_title = utf8_decode($_REQUEST['custom_bouquet_title']);
	
	// remove special chars
	$custom_bouquet_url = str_replace(" ", "%20", $custom_bouquet_url); //important
	$custom_bouquet_url = str_replace("\"", "%22", $custom_bouquet_url); //important
	
	if(!isset($custom_bouquet_url) or $custom_bouquet_url == "" or !isset($custom_bouquet_title) or $custom_bouquet_title == ""){
	
	echo "data missed"; 
	
	} else {
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO `bouquet_list` (`e2servicereference`, `e2servicename`, `selected`, `crawl`) VALUES ('$custom_bouquet_url', '$custom_bouquet_title', '0', '0')");
	
	// answer for ajax
	echo "Bouquet successfully saved.."; 

}
?>