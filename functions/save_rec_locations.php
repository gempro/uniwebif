<?php 
//
include("../inc/dashboard_config.php");

// maximum of record locations
$sum_rec_locations = 100;

// ajax header
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// empty database
$sql = mysqli_query($dbmysqli, "TRUNCATE `record_locations`");

// get locations
$xmlfile = 'http://'.$box_ip.'/web/getlocations';

$setTimer_request = file_get_contents($xmlfile, false, $webrequest);

$xml = simplexml_load_string($setTimer_request);

if ($xml) {
    for ($i = 0; $i <= $sum_rec_locations; $i++) {

	///////////////////////////////////////////////
	if(!isset($xml->e2location[$i]) or $xml->e2location[$i] == ""){ $xml->e2location[$i] = ""; } else { $xml->e2location[$i] = $xml->e2location[$i]; }
	
	// if no data exit
	if($xml->e2location[$i] == "" ) {
	//echo 'empty';
	
	} else {
	
	// define line
	$e2locations = utf8_decode($xml->e2location[$i]);
	
	$sql = mysqli_query($dbmysqli, "INSERT INTO record_locations (e2location) values ('$e2locations')");
	}
	}
	}
	// answer for ajax
	echo "data: ok\n\n";
	
	// close db
	mysqli_close($dbmysqli);
?>