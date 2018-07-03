-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 03. Jul 2018 um 18:27
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
-- Tabellenstruktur für Tabelle `all_services`
--

DROP TABLE IF EXISTS `all_services`;
CREATE TABLE IF NOT EXISTS `all_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2servicename` varchar(255) NOT NULL,
  `servicename_enc` varchar(255) NOT NULL,
  `e2servicereference` varchar(255) NOT NULL,
  `channel_hash` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1471 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bouquet_list`
--

DROP TABLE IF EXISTS `bouquet_list`;
CREATE TABLE IF NOT EXISTS `bouquet_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2servicereference` varchar(255) NOT NULL,
  `e2servicename` varchar(255) NOT NULL,
  `selected` int(1) NOT NULL DEFAULT '0',
  `crawl` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `box_info`
--

DROP TABLE IF EXISTS `box_info`;
CREATE TABLE IF NOT EXISTS `box_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2enigmaversion` varchar(255) NOT NULL,
  `e2imageversion` varchar(255) NOT NULL,
  `e2webifversion` varchar(255) NOT NULL,
  `e2model` varchar(255) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `channel_list`
--

DROP TABLE IF EXISTS `channel_list`;
CREATE TABLE IF NOT EXISTS `channel_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2servicename` varchar(255) NOT NULL,
  `servicename_enc` varchar(255) NOT NULL,
  `e2servicereference` varchar(255) NOT NULL,
  `e2providername` varchar(255) NOT NULL,
  `selected` int(1) NOT NULL DEFAULT '0',
  `crawl` int(1) NOT NULL DEFAULT '1',
  `zap` int(1) NOT NULL DEFAULT '0',
  `zap_start` int(1) NOT NULL DEFAULT '0',
  `cb_selected` int(1) NOT NULL,
  `channel_hash` varchar(100) NOT NULL,
  `last_crawl` int(12) NOT NULL DEFAULT '0',
  `last_epg` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=127 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `device_list`
--

DROP TABLE IF EXISTS `device_list`;
CREATE TABLE IF NOT EXISTS `device_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_description` varchar(255) NOT NULL,
  `device_ip` varchar(255) NOT NULL,
  `device_user` varchar(255) NOT NULL,
  `device_password` varchar(255) NOT NULL,
  `device_record_location` varchar(255) NOT NULL,
  `device_color` varchar(255) NOT NULL,
  `url_format` varchar(255) NOT NULL,
  `rec_location0` varchar(255) NOT NULL,
  `rec_location1` varchar(255) NOT NULL,
  `rec_location2` varchar(255) NOT NULL,
  `rec_location3` varchar(255) NOT NULL,
  `rec_location4` varchar(255) NOT NULL,
  `rec_location5` varchar(255) NOT NULL,
  `rec_location6` varchar(255) NOT NULL,
  `rec_location7` varchar(255) NOT NULL,
  `rec_location8` varchar(255) NOT NULL,
  `rec_location9` varchar(255) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `epg_data`
--

DROP TABLE IF EXISTS `epg_data`;
CREATE TABLE IF NOT EXISTS `epg_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2eventtitle` varchar(255) NOT NULL,
  `title_enc` varchar(255) NOT NULL,
  `e2eventservicename` varchar(255) NOT NULL,
  `servicename_enc` varchar(255) NOT NULL,
  `e2eventdescription` varchar(255) NOT NULL,
  `description_enc` varchar(255) NOT NULL,
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
  `e2eventservicereference` varchar(255) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=854837 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `keywords`
--

DROP TABLE IF EXISTS `keywords`;
CREATE TABLE IF NOT EXISTS `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `searchterm` varchar(255) NOT NULL,
  `word` varchar(255) NOT NULL,
  `sum_total` int(4) NOT NULL,
  `sum_title` int(4) NOT NULL,
  `sum_description` int(4) NOT NULL,
  `sum_extdescription` int(4) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT '1',
  `activ` int(1) NOT NULL DEFAULT '1',
  `counter` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `record_locations`
--

DROP TABLE IF EXISTS `record_locations`;
CREATE TABLE IF NOT EXISTS `record_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2location` varchar(255) NOT NULL,
  `selected` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `saved_search`
--

DROP TABLE IF EXISTS `saved_search`;
CREATE TABLE IF NOT EXISTS `saved_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `searchterm` varchar(255) NOT NULL,
  `search_option` varchar(255) NOT NULL,
  `exclude_channel` text NOT NULL,
  `exclude_title` text NOT NULL,
  `exclude_description` text NOT NULL,
  `exclude_extdescription` text NOT NULL,
  `e2location` varchar(255) NOT NULL,
  `save_date` int(12) NOT NULL,
  `last_change` int(12) NOT NULL,
  `last_crawl` int(12) NOT NULL,
  `crawled` int(1) NOT NULL,
  `e2eventservicereference` varchar(255) NOT NULL,
  `e2eventservicename` varchar(255) NOT NULL,
  `servicename_enc` varchar(255) NOT NULL,
  `activ` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `rec_replay` varchar(255) NOT NULL DEFAULT 'off',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(1) NOT NULL,
  `box_ip` varchar(255) NOT NULL,
  `box_user` varchar(255) NOT NULL,
  `box_password` varchar(255) NOT NULL,
  `server_ip` varchar(255) NOT NULL,
  `script_folder` varchar(255) NOT NULL DEFAULT 'uniwebif',
  `activate_cron` int(1) NOT NULL DEFAULT '0',
  `epg_entries_per_channel` int(6) NOT NULL DEFAULT '500',
  `channel_entries` int(6) NOT NULL DEFAULT '100',
  `time_format` int(1) NOT NULL DEFAULT '2',
  `epg_crawler` int(1) NOT NULL DEFAULT '0',
  `epg_crawler_activ` int(1) NOT NULL DEFAULT '0',
  `crawler_timestamp` int(12) NOT NULL,
  `crawler_hour` varchar(2) NOT NULL,
  `crawler_minute` varchar(2) NOT NULL,
  `last_epg_crawl` int(12) NOT NULL,
  `last_epg` int(12) NOT NULL,
  `start_epg_crawler` int(4) NOT NULL DEFAULT '30',
  `after_crawl_action` int(1) NOT NULL DEFAULT '0',
  `search_crawler` int(1) NOT NULL DEFAULT '0',
  `last_search_crawl` int(12) NOT NULL,
  `display_old_epg` int(1) NOT NULL DEFAULT '0',
  `streaming_symbol` int(1) NOT NULL DEFAULT '1',
  `imdb_symbol` int(1) NOT NULL,
  `timer_ticker` int(1) NOT NULL DEFAULT '1',
  `show_hidden_ticker` int(1) NOT NULL DEFAULT '0',
  `ticker_time` int(6) NOT NULL DEFAULT '86400',
  `mark_searchterm` int(1) NOT NULL DEFAULT '1',
  `send_timer` int(1) NOT NULL DEFAULT '0',
  `hide_old_timer` int(1) NOT NULL DEFAULT '1',
  `delete_old_timer` int(1) NOT NULL DEFAULT '0',
  `delete_receiver_timer` int(1) NOT NULL DEFAULT '0',
  `dummy_timer` int(1) NOT NULL DEFAULT '0',
  `dummy_timer_time` int(12) NOT NULL,
  `dummy_timer_current` int(12) NOT NULL,
  `delete_old_epg` int(1) NOT NULL DEFAULT '0',
  `url_format` varchar(5) NOT NULL DEFAULT 'http',
  `del_time` int(5) NOT NULL DEFAULT '21600',
  `reload_progressbar` int(1) NOT NULL DEFAULT '1',
  `search_list_sort` varchar(255) NOT NULL DEFAULT 'id',
  `extra_rec_time` int(4) NOT NULL DEFAULT '0',
  `cz_activate` int(1) NOT NULL DEFAULT '0',
  `cz_wait_time` int(2) NOT NULL DEFAULT '30',
  `cz_repeat` varchar(10) NOT NULL,
  `cz_hour` varchar(2) NOT NULL,
  `cz_minute` varchar(2) NOT NULL,
  `cz_am_pm` varchar(2) NOT NULL,
  `cz_start_channel` varchar(50) NOT NULL,
  `cz_timestamp` varchar(12) NOT NULL DEFAULT '0',
  `cz_worktime` varchar(12) NOT NULL,
  `dur_down_broadcast` int(4) NOT NULL DEFAULT '600',
  `dur_up_broadcast` int(4) NOT NULL DEFAULT '600',
  `primetime` int(12) NOT NULL DEFAULT '0',
  `dur_down_primetime` int(4) NOT NULL DEFAULT '600',
  `dur_up_primetime` int(5) NOT NULL DEFAULT '7200',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timer`
--

DROP TABLE IF EXISTS `timer`;
CREATE TABLE IF NOT EXISTS `timer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e2eventtitle` varchar(255) NOT NULL,
  `title_enc` varchar(255) NOT NULL,
  `e2eventdescription` varchar(255) NOT NULL,
  `description_enc` varchar(255) NOT NULL,
  `e2eventdescriptionextended` text NOT NULL,
  `descriptionextended_enc` text NOT NULL,
  `e2eventservicename` varchar(255) NOT NULL,
  `servicename_enc` varchar(255) NOT NULL,
  `e2eventservicereference` varchar(255) NOT NULL,
  `search_term` varchar(255) NOT NULL,
  `search_option` varchar(255) NOT NULL,
  `exclude_channel` text NOT NULL,
  `exclude_title` text NOT NULL,
  `exclude_description` text NOT NULL,
  `exclude_extdescription` text NOT NULL,
  `record_location` varchar(255) NOT NULL,
  `e2eventstart` varchar(12) NOT NULL,
  `e2eventend` varchar(12) NOT NULL,
  `timer_request` varchar(255) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `channel_hash` varchar(100) NOT NULL,
  `status` varchar(255) NOT NULL,
  `record_status` varchar(255) NOT NULL,
  `show_ticker` int(1) NOT NULL DEFAULT '1',
  `rec_replay` varchar(255) NOT NULL,
  `is_replay` int(1) NOT NULL,
  `expired` int(1) NOT NULL DEFAULT '0',
  `hide` int(1) NOT NULL DEFAULT '0',
  `device` int(4) NOT NULL,
  `search_id` int(4) NOT NULL,
  `conflict` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1359 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
