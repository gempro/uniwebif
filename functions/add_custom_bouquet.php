<?php 
//
sleep(1);
include("../inc/dashboard_config.php");

	// ajax header
 	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	
	//recieve data
	if(!isset($custom_bouquet_url) or $custom_bouquet_url == "") { $custom_bouquet_url = ""; } else { $custom_bouquet_url = $custom_bouquet_url; }
	if(!isset($custom_bouquet_title) or $custom_bouquet_title == "") { $custom_bouquet_title = ""; } else { $custom_bouquet_title = $custom_bouquet_title; }
	
	$custom_bouquet_url = utf8_decode($_REQUEST['custom_bouquet_url']);
	$custom_bouquet_title = utf8_decode($_REQUEST['custom_bouquet_title']);
	
	// remove special chars
	$custom_bouquet_url = str_replace(" ", "%20", $custom_bouquet_url); //important
	$custom_bouquet_url = str_replace("\"", "%22", $custom_bouquet_url); //important
	
	if(!isset($custom_bouquet_url) or $custom_bouquet_url == "" or !isset($custom_bouquet_title) or $custom_bouquet_title == "") 
	{ 
	echo "data: data missed\n\n"; 
	
	} else { 
	
	$custom_bouquet_url = $custom_bouquet_url; $custom_bouquet_title = $custom_bouquet_title;
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO bouquet_list (e2servicereference, e2servicename, selected, crawl) values ('$custom_bouquet_url','$custom_bouquet_title','0','0')");
	
	// close db
	mysqli_close($dbmysqli);
	
	// answer for ajax
	echo "data: Bouquet saved\n\n";

}
?>