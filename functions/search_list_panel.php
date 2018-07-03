<?php 
//
	include("../inc/dashboard_config.php");
	
	// count entries
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `saved_search`');
	$result = mysqli_fetch_row($sql);
	$summary_total = $result[0];
	
	// count activ
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `saved_search` WHERE `activ` = "yes" ');
	$result = mysqli_fetch_row($sql);
	$activ = $result[0];
	
	// count inactiv
	$sql = mysqli_query($dbmysqli, 'SELECT COUNT(*) FROM `saved_search` WHERE `activ` = "no" ');
	$result = mysqli_fetch_row($sql);
	$inactiv = $result[0];
	
	echo '
	[{"summary_total":"'.$summary_total.'",
	"activ":"'.$activ.'",
	"inactiv":"'.$inactiv.'\r"}]';

?>