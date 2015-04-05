-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Host: remote-mysql4.servage.net
-- Generation Time: Jan 13, 2015 at 11:27 PM
-- Server version: 5.0.85
-- PHP Version: 5.2.42-servage30

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `10000projects`
--

-- --------------------------------------------------------

--
-- Table structure for table `procentris_inout`
--

CREATE TABLE IF NOT EXISTS `procentris_inout` (
  `inout_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `in_time` bigint(20) default NULL,
  `out_time` bigint(20) default NULL,
  `diff_time` bigint(20) default NULL,
  `ip` varchar(20) default NULL,
  `ipout` varchar(20) default NULL,
  PRIMARY KEY  (`inout_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=123 ;

--
-- Dumping data for table `procentris_inout`
--

-- --------------------------------------------------------

--
-- Table structure for table `procentris_leavebalance`
--

CREATE TABLE IF NOT EXISTS `procentris_leavebalance` (
  `balance_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `name` varchar(200) default NULL,
  `pl` float(12,2) NOT NULL default '0.00',
  `cl` float(12,2) NOT NULL default '0.00',
  `sl` float(12,2) NOT NULL default '0.00',
  `compoff` float(12,2) NOT NULL default '0.00',
  PRIMARY KEY  (`balance_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

--
-- Dumping data for table `procentris_leavebalance`
--

-- --------------------------------------------------------

--
-- Table structure for table `procentris_list`
--

CREATE TABLE IF NOT EXISTS `procentris_list` (
  `list_id` int(11) NOT NULL auto_increment,
  `list` text NOT NULL,
  `list_type` enum('Category','Project','Task') NOT NULL default 'Category',
  `pid` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '1',
  `level` int(11) NOT NULL default '1',
  `color` varchar(50) default NULL,
  `deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2132 ;

--
-- Dumping data for table `procentris_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `procentris_logs`
--

CREATE TABLE IF NOT EXISTS `procentris_logs` (
  `log_id` int(11) NOT NULL auto_increment,
  `username` varchar(50) default NULL,
  `details` longtext,
  `ip` varchar(20) default NULL,
  `page` varchar(255) default NULL,
  PRIMARY KEY  (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `procentris_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `procentris_newsletter`
--

CREATE TABLE IF NOT EXISTS `procentris_newsletter` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) default NULL,
  `message` text,
  `send` int(1) NOT NULL default '0',
  `send_to` text,
  `send_on_day` int(1) NOT NULL default '5',
  `send_on_hour` int(2) NOT NULL default '0',
  `send_on_min` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `procentris_newsletter`
--

-- --------------------------------------------------------

--
-- Table structure for table `procentris_timesheet`
--

CREATE TABLE IF NOT EXISTS `procentris_timesheet` (
  `timesheet_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `category` int(11) NOT NULL default '0',
  `project` int(11) NOT NULL default '0',
  `tasks` int(11) NOT NULL default '0',
  `timetaken` float(12,2) default NULL,
  `cdate` date default NULL,
  `cday` int(2) default NULL,
  `cmonth` int(2) default NULL,
  `cyear` int(4) default NULL,
  PRIMARY KEY  (`timesheet_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3328 ;

--
-- Dumping data for table `procentris_timesheet`
--

-- --------------------------------------------------------

--
-- Table structure for table `procentris_tt`
--

CREATE TABLE IF NOT EXISTS `procentris_tt` (
  `tt_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `subject` varchar(255) default NULL,
  `message` longtext,
  `parent_id` int(11) NOT NULL default '0',
  `priority` int(11) NOT NULL default '0',
  `status` enum('Open','Inprocess','Closed/Resolved','Cancelled','Reopen') NOT NULL default 'Open',
  `created_dt` datetime default NULL,
  `modified_dt` datetime default NULL,
  `created_time` bigint(20) default NULL,
  `modified_time` bigint(20) default NULL,
  PRIMARY KEY  (`tt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `procentris_tt`
--

-- --------------------------------------------------------

--
-- Table structure for table `procentris_users`
--

CREATE TABLE IF NOT EXISTS `procentris_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `username` varchar(150) NOT NULL,
  `name` varchar(100) default NULL,
  `password` varchar(50) NOT NULL,
  `accesslevel` varchar(50) NOT NULL default 'Employee',
  `dob` date default NULL,
  `gender` enum('Male','Female') default NULL,
  `joining_date` date default NULL,
  `position` varchar(200) default NULL,
  `seatno` varchar(50) default NULL,
  `address1` varchar(255) default NULL,
  `address2` varchar(255) default NULL,
  `city` varchar(25) NOT NULL default 'Mumbai',
  `province` varchar(25) NOT NULL default 'Maharashtra',
  `country` varchar(25) NOT NULL default 'India',
  `zipcode` varchar(10) default NULL,
  `homephone` varchar(50) default NULL,
  `mobilephone` varchar(50) default NULL,
  `last_login_dt` datetime default NULL,
  `logged_in` int(1) NOT NULL default '0',
  `logged_in_time` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Dumping data for table `procentris_users`
--

-- --------------------------------------------------------

--
-- Table structure for table `procentris_user_client`
--

CREATE TABLE IF NOT EXISTS `procentris_user_client` (
  `rel_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `list_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`rel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=629 ;

--
-- Dumping data for table `procentris_user_client`
--
