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

	$query = mysqli_query($dbmysqli, "DROP TABLE IF EXISTS `epg_data`;
CREATE TABLE IF NOT EXISTS `epg_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2eventtitle` varchar(255) NOT NULL,
  `title_enc` varchar(255) NOT NULL,
  `e2eventservicename` varchar(255) NOT NULL,
  `servicename_enc` varchar(255) NOT NULL,
  `e2eventdescription` varchar(255) NOT NULL,
  `description_enc` text NOT NULL,
  `e2eventdescriptionextended` text NOT NULL,
  `descriptionextended_enc` text NOT NULL,
  `e2eventid` varchar(10) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `us_start_date` varchar(255) NOT NULL,
  `start_day` varchar(2) NOT NULL,
  `start_month` varchar(2) NOT NULL,
  `start_year` varchar(4) NOT NULL,
  `start_hour` varchar(2) NOT NULL,
  `start_minute` varchar(2) NOT NULL,
  `start_weekday` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `us_end_date` varchar(255) NOT NULL,
  `end_day` varchar(2) NOT NULL,
  `end_month` varchar(2) NOT NULL,
  `end_year` varchar(4) NOT NULL,
  `end_hour` varchar(2) NOT NULL,
  `end_minute` varchar(2) NOT NULL,
  `end_weekday` varchar(255) NOT NULL,
  `total_min` varchar(3) NOT NULL,
  `e2eventstart` varchar(12) NOT NULL,
  `e2eventend` varchar(12) NOT NULL,
  `e2eventduration` varchar(5) NOT NULL,
  `e2eventcurrenttime` varchar(10) NOT NULL,
  `e2eventservicereference` text NOT NULL,
  `hd_channel` varchar(3) NOT NULL,
  `crawler_time` varchar(10) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `channel_hash` varchar(100) NOT NULL,
  `timer` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_enc` (`title_enc`),
  FULLTEXT KEY `description_enc` (`description_enc`),
  FULLTEXT KEY `descriptionextended_enc` (`descriptionextended_enc`),
  FULLTEXT KEY `epgsearch_enc` (`title_enc`,`description_enc`,`descriptionextended_enc`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

	echo 'Update done. This file could be deleted now..';

?>