-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 05. Jul 2017 um 18:26
-- Server Version: 5.5.38
-- PHP-Version: 5.4.45-0+deb7u6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `uniwebif`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bouquet_list`
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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `box_info`
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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `channel_list`
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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `epg_data`
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
  `crawler_time` text NOT NULL,
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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `record_locations`
--

DROP TABLE IF EXISTS `record_locations`;
CREATE TABLE IF NOT EXISTS `record_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2location` text NOT NULL,
  `selected` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `record_locations`
--

INSERT INTO `record_locations` (`id`, `e2location`, `selected`) VALUES
(1, '/hdd/movie/', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `saved_search`
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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
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
  `start_epg_crawler` int(2) NOT NULL DEFAULT '30',
  `search_crawler` int(1) NOT NULL DEFAULT '0',
  `last_search_crawl` int(12) NOT NULL,
  `display_old_epg` int(1) NOT NULL DEFAULT '0',
  `streaming_symbol` int(1) NOT NULL DEFAULT '1',
  `timer_ticker` int(1) NOT NULL DEFAULT '1',
  `ticker_time` int(6) NOT NULL DEFAULT '86400',
  `mark_searchterm` int(1) NOT NULL DEFAULT '1',
  `send_timer` int(1) NOT NULL DEFAULT '0',
  `delete_old_timer` int(1) NOT NULL DEFAULT '0',
  `delete_old_epg` int(1) NOT NULL DEFAULT '0',
  `del_time` int(5) NOT NULL DEFAULT '21600',
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
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` (`id`, `box_ip`, `box_user`, `box_password`, `activate_cron`, `epg_entries_per_channel`, `channel_entries`, `time_format`, `epg_crawler`, `crawler_timestamp`, `crawler_hour`, `crawler_minute`, `crawler_am_pm`, `last_epg_crawl`, `start_epg_crawler`, `search_crawler`, `last_search_crawl`, `display_old_epg`, `streaming_symbol`, `timer_ticker`, `ticker_time`, `mark_searchterm`, `send_timer`, `delete_old_timer`, `delete_old_epg`, `del_time`, `extra_rec_time`, `cz_activate`, `cz_wait_time`, `cz_repeat`, `cz_hour`, `cz_minute`, `cz_am_pm`, `cz_start_channel`, `cz_timestamp`, `cz_worktime`, `dur_down_broadcast`, `dur_up_broadcast`, `dur_down_primetime`, `dur_up_primetime`) VALUES
(0, '', '', '', 0, 100, 100, 2, 1, 0, '12', '00', 'PM', 0, 75, 0, 0, 0, 1, 1, 604800, 1, 0, 1, 1, 86400, 600, 0, 15, 'daily', '08', '00', 'AM', '1:0:19:132F:3EF:1:C00000:0:0:0:', '0', '0', 600, 1800, 300, 7200);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timer`
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
