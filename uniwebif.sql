-- phpMyAdmin SQL Dump

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `uniwebif`
--

-- --------------------------------------------------------

--
-- Table `bouquet_list`
--

DROP TABLE IF EXISTS `bouquet_list`;
CREATE TABLE IF NOT EXISTS `bouquet_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2servicereference` text NOT NULL,
  `e2servicename` varchar(50) NOT NULL,
  `selected` int(1) NOT NULL DEFAULT '0',
  `crawl` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data Table `bouquet_list`
--


-- --------------------------------------------------------

--
-- Table `box_info`
--

DROP TABLE IF EXISTS `box_info`;
CREATE TABLE IF NOT EXISTS `box_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2enigmaversion` varchar(100) NOT NULL,
  `e2imageversion` varchar(100) NOT NULL,
  `e2webifversion` varchar(100) NOT NULL,
  `e2model` varchar(100) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data Table `box_info`
--


-- --------------------------------------------------------

--
-- Table `channel_list`
--

DROP TABLE IF EXISTS `channel_list`;
CREATE TABLE IF NOT EXISTS `channel_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2servicename` text NOT NULL,
  `servicename_enc` text NOT NULL,
  `e2servicereference` varchar(50) NOT NULL,
  `selected` int(1) NOT NULL DEFAULT '0',
  `crawl` int(1) NOT NULL DEFAULT '1',
  `zap` int(1) NOT NULL DEFAULT '0',
  `zap_start` int(1) NOT NULL DEFAULT '0',
  `cb_selected` int(1) NOT NULL,
  `channel_hash` varchar(100) NOT NULL,
  `last_crawl` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data Table `channel_list`
--


-- --------------------------------------------------------

--
-- Table `epg_data`
--

DROP TABLE IF EXISTS `epg_data`;
CREATE TABLE IF NOT EXISTS `epg_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2eventtitle` text NOT NULL,
  `title_enc` text NOT NULL,
  `e2eventservicename` text NOT NULL,
  `servicename_enc` text NOT NULL,
  `e2eventdescription` text NOT NULL,
  `description_enc` text NOT NULL,
  `e2eventdescriptionextended` text NOT NULL,
  `descriptionextended_enc` text NOT NULL,
  `e2eventid` varchar(10) NOT NULL,
  `start_date` text NOT NULL,
  `us_start_date` text NOT NULL,
  `start_day` varchar(2) NOT NULL,
  `start_month` varchar(2) NOT NULL,
  `start_year` varchar(4) NOT NULL,
  `start_hour` varchar(2) NOT NULL,
  `start_minute` varchar(2) NOT NULL,
  `start_weekday` text NOT NULL,
  `end_date` text NOT NULL,
  `us_end_date` text NOT NULL,
  `end_day` varchar(2) NOT NULL,
  `end_month` varchar(2) NOT NULL,
  `end_year` varchar(4) NOT NULL,
  `end_hour` varchar(2) NOT NULL,
  `end_minute` varchar(2) NOT NULL,
  `end_weekday` text NOT NULL,
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
  FULLTEXT KEY `e2eventservicename` (`e2eventservicename`),
  FULLTEXT KEY `e2eventdescription` (`e2eventdescription`),
  FULLTEXT KEY `e2eventdescriptionextended` (`e2eventdescriptionextended`),
  FULLTEXT KEY `epgsearch` (`e2eventtitle`,`e2eventservicename`,`e2eventdescription`,`e2eventdescriptionextended`),
  FULLTEXT KEY `title_enc` (`title_enc`),
  FULLTEXT KEY `description_enc` (`description_enc`),
  FULLTEXT KEY `descriptionextended_enc` (`descriptionextended_enc`),
  FULLTEXT KEY `epgsearch_enc` (`title_enc`,`e2eventservicename`,`description_enc`,`descriptionextended_enc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data Table `epg_data`
--


-- --------------------------------------------------------

--
-- Table `record_locations`
--

DROP TABLE IF EXISTS `record_locations`;
CREATE TABLE IF NOT EXISTS `record_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2location` text NOT NULL,
  `selected` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data Table `record_locations`
--


-- --------------------------------------------------------

--
-- Table `saved_search`
--

DROP TABLE IF EXISTS `saved_search`;
CREATE TABLE IF NOT EXISTS `saved_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `searchterm` text NOT NULL,
  `search_option` text NOT NULL,
  `e2location` text NOT NULL,
  `save_date` text NOT NULL,
  `e2eventservicereference` text NOT NULL,
  `e2eventservicename` text NOT NULL,
  `servicename_enc` text NOT NULL,
  `activ` text NOT NULL,
  `action` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data Table `saved_search`
--


-- --------------------------------------------------------

--
-- Table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(1) NOT NULL,
  `box_ip` text NOT NULL,
  `box_user` text NOT NULL,
  `box_password` text NOT NULL,
  `activate_cron` int(1) NOT NULL DEFAULT '0',
  `epg_entries_per_channel` int(6) NOT NULL DEFAULT '500',
  `channel_entries` int(6) NOT NULL DEFAULT '100',
  `time_format` int(1) NOT NULL DEFAULT '2',
  `epg_crawler` int(1) NOT NULL DEFAULT '0',
  `crawler_timestamp` int(12) NOT NULL,
  `crawler_hour` varchar(2) NOT NULL,
  `crawler_minute` varchar(2) NOT NULL,
  `crawler_am_pm` text NOT NULL,
  `last_epg_crawl` int(12) NOT NULL,
  `start_epg_crawler` int(4) NOT NULL DEFAULT '30',
  `after_crawl_action` int(1) NOT NULL DEFAULT '0',
  `search_crawler` int(1) NOT NULL DEFAULT '0',
  `last_search_crawl` int(12) NOT NULL,
  `display_old_epg` int(1) NOT NULL DEFAULT '0',
  `streaming_symbol` int(1) NOT NULL DEFAULT '1',
  `imdb_symbol` int(1) NOT NULL,
  `timer_ticker` int(1) NOT NULL DEFAULT '1',
  `ticker_time` int(6) NOT NULL DEFAULT '86400',
  `mark_searchterm` int(1) NOT NULL DEFAULT '1',
  `send_timer` int(1) NOT NULL DEFAULT '0',
  `delete_old_timer` int(1) NOT NULL DEFAULT '0',
  `delete_receiver_timer` int(1) NOT NULL DEFAULT '0',
  `dummy_timer` int(1) NOT NULL DEFAULT '0',
  `dummy_timer_time` int(12) NOT NULL,
  `dummy_timer_current` int(12) NOT NULL,
  `delete_old_epg` int(1) NOT NULL DEFAULT '0',
  `url_format` varchar(5) NOT NULL DEFAULT 'http',
  `del_time` int(5) NOT NULL DEFAULT '21600',
  `reload_progressbar1` int(1) NOT NULL DEFAULT '1',
  `extra_rec_time` int(4) NOT NULL DEFAULT '0',
  `cz_activate` int(1) NOT NULL DEFAULT '0',
  `cz_wait_time` int(2) NOT NULL DEFAULT '30',
  `cz_repeat` varchar(10) NOT NULL,
  `cz_hour` varchar(2) NOT NULL,
  `cz_minute` varchar(2) NOT NULL,
  `cz_am_pm` text NOT NULL,
  `cz_start_channel` varchar(50) NOT NULL,
  `cz_timestamp` varchar(12) NOT NULL DEFAULT '0',
  `cz_worktime` varchar(12) NOT NULL,
  `dur_down_broadcast` int(4) NOT NULL DEFAULT '600',
  `dur_up_broadcast` int(4) NOT NULL DEFAULT '600',
  `dur_down_primetime` int(4) NOT NULL DEFAULT '600',
  `dur_up_primetime` int(5) NOT NULL DEFAULT '7200',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Data Table `settings`
--

INSERT INTO `settings` (`id`, `box_ip`, `box_user`, `box_password`, `activate_cron`, `epg_entries_per_channel`, `channel_entries`, `time_format`, `epg_crawler`, `crawler_timestamp`, `crawler_hour`, `crawler_minute`, `crawler_am_pm`, `last_epg_crawl`, `start_epg_crawler`, `after_crawl_action`, `search_crawler`, `last_search_crawl`, `display_old_epg`, `streaming_symbol`, `imdb_symbol`, `timer_ticker`, `ticker_time`, `mark_searchterm`, `send_timer`, `delete_old_timer`, `delete_receiver_timer`, `dummy_timer`, `dummy_timer_time`, `dummy_timer_current`, `delete_old_epg`, `url_format`, `del_time`, `reload_progressbar1`, `extra_rec_time`, `cz_activate`, `cz_wait_time`, `cz_repeat`, `cz_hour`, `cz_minute`, `cz_am_pm`, `cz_start_channel`, `cz_timestamp`, `cz_worktime`, `dur_down_broadcast`, `dur_up_broadcast`, `dur_down_primetime`, `dur_up_primetime`) VALUES
(0, 'localhost', 'root', '', 0, 500, 100, 2, 0, 0, '', '', '', 0, 30, 0, 0, 0, 0, 1, 0, 1, 86400, 1, 0, 0, 0, 0, 0, 0, 0, 'http', 21600, 1, 0, 0, 30, '', '', '', '', '', '0', '', 600, 600, 600, 7200);

-- --------------------------------------------------------

--
-- Table `timer`
--

DROP TABLE IF EXISTS `timer`;
CREATE TABLE IF NOT EXISTS `timer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2eventtitle` text NOT NULL,
  `title_enc` text NOT NULL,
  `e2eventdescription` text NOT NULL,
  `description_enc` text NOT NULL,
  `e2eventdescriptionextended` text NOT NULL,
  `descriptionextended_enc` text NOT NULL,
  `e2eventservicename` text NOT NULL,
  `servicename_enc` text NOT NULL,
  `e2eventservicereference` varchar(255) NOT NULL,
  `search_term` text NOT NULL,
  `search_option` text NOT NULL,
  `record_location` text NOT NULL,
  `e2eventstart` varchar(12) NOT NULL,
  `e2eventend` varchar(12) NOT NULL,
  `timer_request` text NOT NULL,
  `hash` varchar(50) NOT NULL,
  `channel_hash` varchar(100) NOT NULL,
  `status` text NOT NULL,
  `record_status` text NOT NULL,
  `show_ticker` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data Table `timer`
--


-- --------------------------------------------------------

--
-- Table `tv_services`
--

DROP TABLE IF EXISTS `tv_services`;
CREATE TABLE IF NOT EXISTS `tv_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2servicename` text NOT NULL,
  `servicename_enc` text NOT NULL,
  `e2servicereference` varchar(50) NOT NULL,
  `channel_hash` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data Table `tv_services`
--

