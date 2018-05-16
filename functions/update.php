<?php 
//
include("../inc/dashboard_config.php");

	$query = mysqli_query($dbmysqli, "ALTER TABLE `saved_search` CHANGE `exclude_term` `exclude_channel` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	
	$query = mysqli_query($dbmysqli, "ALTER TABLE `saved_search` CHANGE `exclude_area` `exclude_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	
	$query = mysqli_query($dbmysqli, "ALTER TABLE `saved_search` ADD `exclude_description` TEXT NOT NULL AFTER `exclude_title` , ADD `exclude_extdescription` TEXT NOT NULL AFTER `exclude_description`");
	
	$query = mysqli_query($dbmysqli, "ALTER TABLE `timer` CHANGE `exclude_term` `exclude_channel` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `exclude_area` `exclude_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	
	$query = mysqli_query($dbmysqli, "ALTER TABLE `timer` ADD `exclude_description` TEXT NOT NULL AFTER `exclude_title` ,
ADD `exclude_extdescription` TEXT NOT NULL AFTER `exclude_description`");

	echo 'Update done. This file could be deleted now..';

?>