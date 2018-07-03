<?php 
//

	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['id']) or $_REQUEST['id'] == ""){ $_REQUEST['id'] = ""; }
	$id = $_REQUEST['id'];

	$sql = mysqli_query($dbmysqli, "SELECT * FROM `timer` WHERE `id` = '".$id."' ");
	$result = mysqli_fetch_assoc($sql);
	$title = strtolower($result['e2eventtitle']);
	$description = strtolower($result['e2eventdescription']);
	$descriptionextended = strtolower($result['e2eventdescriptionextended']);
	$search_term = strtolower($result['search_term']);
	
	$total_string = ($title.$description.$descriptionextended);

	$total_string = explode(' ',$total_string);
	foreach ($total_string as $key => $word){
	if(strlen($word) == 4 or strlen($word) > 4){ $words[] = $word; }
	}
	$count = array_count_values($words);
	array_multisort($count,SORT_DESC);
	
	$i = 1;
	foreach ($count as $keyword => $value){
	if($value == 3 or $value > 3){
	$i++;
	$summary_title = substr_count($title, $keyword);
	$summary_description = substr_count($description, $keyword);
	$summary_extdescription = substr_count($descriptionextended, $keyword);
	$summary_total = $summary_title + $summary_description + $summary_extdescription;
	
	$word_lower = strtolower($keyword);
	$searchterm_lower = strtolower($search_term);
	$hash = hash('md4',$word_lower.$searchterm_lower.$summary_title.$summary_description.$summary_extdescription);
	
//	echo 'title: '.$title;
//	echo '<br>';
//	echo 'description: '.$description;
//	echo '<br>';
//	echo 'ext description: '.$descriptionextended;
//	echo '<hr>';
//	echo 'wort: '.$keyword;
//	echo '<br>';
//	echo 'in title: '.$summary_title;
//	echo '<br>';
//	echo 'in description: '.$summary_description;
//	echo '<br>';
//	echo 'in ext description: '.$summary_extdescription;
//	echo '<br>';
//	echo 'total: '.$summary_total;
//	echo '<hr>';

	$sql = mysqli_query($dbmysqli, "SELECT COUNT(*) FROM `keywords_h` WHERE `hash` = '".$hash."' ");
	$result = mysqli_fetch_row($sql);
	$summary = $result[0];
	
	if($summary < 1){
	$sql = mysqli_query($dbmysqli, "INSERT INTO `keywords_h` (
	`searchterm`, 
	`word`, 
	`sum_total`, 
	`sum_title`, 
	`sum_description`, 
	`sum_extdescription`, 
	`hash`, 
	`timestamp`) VALUES (
	'".$search_term."', 
	'".$word_lower."', 
	'".$summary_total."', 
	'".$summary_title."', 
	'".$summary_description."', 
	'".$summary_extdescription."', 
	'".$hash."', 
	'".$time."' )");
	}
	}

	//exit();
}
?>