-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 16, 2015 at 08:28 PM
-- Server version: 5.6.23
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `consultl_donation`
--

-- --------------------------------------------------------

--
-- Table structure for table `donation_ads`
--

CREATE TABLE IF NOT EXISTS `donation_ads` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `donation_requested` double DEFAULT NULL,
  `ad_description` text,
  `ad_created_dt` datetime DEFAULT NULL,
  `ad_modify_dt` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `donation_ads_details`
--

CREATE TABLE IF NOT EXISTS `donation_ads_details` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_id` int(11) DEFAULT NULL,
  `payer_user` varchar(50) DEFAULT NULL,
  `pay_date` datetime DEFAULT NULL,
  `pay_status` int(1) NOT NULL DEFAULT '0',
  `pay_details` text,
  PRIMARY KEY (`detail_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `donation_profiles`
--

CREATE TABLE IF NOT EXISTS `donation_profiles` (
  `user` varchar(50) NOT NULL,
  `paypal_email` varchar(150) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `age` int(4) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
