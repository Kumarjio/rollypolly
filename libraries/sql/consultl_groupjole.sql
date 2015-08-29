-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 02, 2015 at 08:24 PM
-- Server version: 5.6.23
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `consultl_groupjole`
--
CREATE DATABASE IF NOT EXISTS `consultl_groupjole` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `consultl_groupjole`;

-- --------------------------------------------------------

--
-- Table structure for table `dcomerce_custom_horopoints`
--

DROP TABLE IF EXISTS `dcomerce_custom_horopoints`;
CREATE TABLE IF NOT EXISTS `dcomerce_custom_horopoints` (
  `pt_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `from_nak` int(11) DEFAULT NULL,
  `to_nak` int(11) DEFAULT NULL,
  `pts` int(11) DEFAULT NULL,
  `pt_date` datetime DEFAULT NULL,
  PRIMARY KEY (`pt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dcomerce_settings`
--

DROP TABLE IF EXISTS `dcomerce_settings`;
CREATE TABLE IF NOT EXISTS `dcomerce_settings` (
  `user_id` int(11) NOT NULL,
  `bday` int(2) DEFAULT NULL,
  `bmonth` int(2) DEFAULT NULL,
  `byear` int(4) DEFAULT NULL,
  `btime` time DEFAULT NULL,
  `bplace` varchar(200) DEFAULT NULL,
  `blatitude` double DEFAULT NULL,
  `blongitude` double DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `currentLocation` text,
  `placeLocation` text,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dcomerce_settings`
--

INSERT INTO `dcomerce_settings` (`user_id`, `bday`, `bmonth`, `byear`, `btime`, `bplace`, `blatitude`, `blongitude`, `nickname`, `currentLocation`, `placeLocation`) VALUES
(1, 5, 6, 1974, '12:30:00', 'Mumbai, Maharashtra, India', 19.0759837, 72.87765590000004, 'mango', '{"currentLocation":"Manteca, CA, United States","currentLocationLatitude":"37.7974273","currentLocationLongitude":"-121.21605260000001","details":{"timezone":{"time":"2015-07-19 22:39","countryName":"United States","sunset":"2015-07-20 20:23","rawOffset":-8,"dstOffset":-7,"countryCode":"US","gmtOffset":-8,"lng":-121.2160526,"sunrise":"2015-07-20 05:58","timezoneId":"America\\/Los_Angeles","lat":37.7974273},"location":{"dst":1,"lat_h":"37","lat_m":"47","lat_s":0,"lon_h":"121","lon_m":"12","lon_e":0,"zone_h":8,"zone_m":0},"dd2dms":["N","W","37","121","47","12",51,58]}}', '{"timezone":{"sunrise":"2015-07-20 06:10","lng":72.8776559,"countryCode":"IN","gmtOffset":5.5,"rawOffset":5.5,"sunset":"2015-07-20 19:18","timezoneId":"Asia\\/Kolkata","dstOffset":5.5,"countryName":"India","time":"2015-07-20 11:23","lat":19.0759837},"location":{"dst":0,"lat_h":"19","lat_m":"4","lat_s":0,"lon_h":"72","lon_m":"52","lon_e":1,"zone_h":5,"zone_m":30},"dd2dms":["N","E","19","72","4","52",34,40]}'),
(2, 5, 4, 1977, '13:30:00', 'Ulhasnagar, Maharashtra, India', 19.2215115, 73.16446280000002, 'bhani', '{"currentLocation":"Manteca, CA, United States","currentLocationLatitude":"37.7974273","currentLocationLongitude":"-121.21605260000001","details":{"timezone":{"time":"2015-07-19 22:39","countryName":"United States","sunset":"2015-07-20 20:23","rawOffset":-8,"dstOffset":-7,"countryCode":"US","gmtOffset":-8,"lng":-121.2160526,"sunrise":"2015-07-20 05:58","timezoneId":"America\\/Los_Angeles","lat":37.7974273},"location":{"dst":1,"lat_h":"37","lat_m":"47","lat_s":0,"lon_h":"121","lon_m":"12","lon_e":0,"zone_h":8,"zone_m":0},"dd2dms":["N","W","37","121","47","12",51,58]}}', '{"timezone":{"sunrise":"2015-07-20 06:09","lng":73.1644628,"countryCode":"IN","gmtOffset":5.5,"rawOffset":5.5,"sunset":"2015-07-20 19:17","timezoneId":"Asia\\/Kolkata","dstOffset":5.5,"countryName":"India","time":"2015-07-20 13:34","lat":19.2215115},"location":{"dst":0,"lat_h":"19","lat_m":"13","lat_s":0,"lon_h":"73","lon_m":"9","lon_e":1,"zone_h":5,"zone_m":30},"dd2dms":["N","E","19","73","13","9",17,52]}');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_name` varchar(200) DEFAULT NULL,
  `group_headline` varchar(200) DEFAULT NULL,
  `group_url` varchar(255) DEFAULT NULL,
  `group_description` text,
  `group_image_url` varchar(255) DEFAULT NULL,
  `group_youtube_url` varchar(255) DEFAULT NULL,
  `members_called` varchar(100) DEFAULT NULL,
  `group_created_dt` datetime DEFAULT NULL,
  `group_status` int(1) NOT NULL DEFAULT '1',
  `group_expiry_date` datetime DEFAULT NULL,
  `payment_status` varchar(200) NOT NULL DEFAULT 'Pending',
  `payment_date` datetime DEFAULT NULL,
  `payment_history` longtext,
  `topics_added` text,
  `location` text,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `group_type` int(1) DEFAULT NULL COMMENT '1 = free, 2 = trail period, 3 = normal pay',
  `who_can_create_pages` int(1) NOT NULL DEFAULT '1' COMMENT '1 = all, 2 organiser & co org, 3 leadership',
  `admin_approved` int(1) NOT NULL DEFAULT '1',
  `require_photo` int(1) NOT NULL DEFAULT '0',
  `require_profile_questions` int(1) NOT NULL DEFAULT '0',
  `profile_questions` text,
  `welcome_message` text,
  `visibility` int(1) NOT NULL DEFAULT '1' COMMENT '1 = full, 0 = partial',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

DROP TABLE IF EXISTS `group_members`;
CREATE TABLE IF NOT EXISTS `group_members` (
  `group_id` varchar(50) NOT NULL,
  `member_user_id` int(11) NOT NULL,
  `member_joined_date` datetime NOT NULL,
  `member_status` int(1) NOT NULL DEFAULT '1' COMMENT '1 = active, 2 = blocked, ',
  `profile_question_answers` text,
  `member` int(1) NOT NULL DEFAULT '1',
  `organizer` int(1) NOT NULL DEFAULT '0',
  `coorganizer` int(1) NOT NULL DEFAULT '0',
  `assistant_organizer` int(1) NOT NULL DEFAULT '0',
  `event_organizer` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`member_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `maps_addressbook`
--

DROP TABLE IF EXISTS `maps_addressbook`;
CREATE TABLE IF NOT EXISTS `maps_addressbook` (
  `addr_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lng` float DEFAULT NULL,
  `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_dt` datetime DEFAULT NULL,
  `url` text,
  PRIMARY KEY (`addr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `maps_addressbook`
--

INSERT INTO `maps_addressbook` (`addr_id`, `uid`, `address`, `lat`, `lng`, `created_dt`, `updated_dt`, `url`) VALUES
(1, '112913147917981568678', '4747 Willow Road, Pleasanton, CA, United States', 37.6971, -121.897, '2015-07-03 15:21:06', '2015-07-03 16:54:05', 'https://maps.googleapis.com/maps/api/directions/json?origin=37.7782023,-121.2253853&destination=37.6971045,-121.89720970000002&mode=driving&alternatives=true&key=AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw&avoid=highways'),
(3, '112913147917981568678', '1543 Monte Stella Pl, Manteca, CA, United States', 37.7781, -121.225, '2015-07-03 15:29:45', '2015-07-03 15:31:57', 'https://maps.googleapis.com/maps/api/directions/json?origin=37.7782002,-121.22533800000001&destination=37.778149,-121.225323&mode=driving&alternatives=true&key=AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw'),
(4, '112913147917981568678', '12101 Shadow Creek Parkway, Pearland, Texas 77584, United States', 29.5783, -95.406, '2015-07-10 15:40:06', '2015-07-10 15:40:06', 'https://maps.googleapis.com/maps/api/directions/json?origin=37.7782121,-121.2253407&destination=29.5782558,-95.40598690000002&mode=driving&alternatives=true&key=AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw'),
(5, '112913147917981568678', '12155 Shadow Creek Pkwy, Pearland, TX, United States', 29.5783, -95.4078, '2015-07-10 15:40:54', '2015-07-10 15:40:54', 'https://maps.googleapis.com/maps/api/directions/json?origin=37.7782087,-121.2253681&destination=29.5782621,-95.4078341&mode=driving&alternatives=true&key=AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw');

-- --------------------------------------------------------

--
-- Table structure for table `religion_proposals`
--

DROP TABLE IF EXISTS `religion_proposals`;
CREATE TABLE IF NOT EXISTS `religion_proposals` (
  `proposal_id` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext,
  `reference` text,
  `status` enum('New','Approved','Deleted','Disapproved') NOT NULL DEFAULT 'New',
  `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_dt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`proposal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `religion_proposals`
--

INSERT INTO `religion_proposals` (`proposal_id`, `user_id`, `title`, `description`, `reference`, `status`, `created_dt`, `updated_dt`) VALUES
('D6F11BFF-B7D1-68F2-2DCB-55ADF7A350B3', 1, 'dfdfdfd', 'dfdfdf', 'dfdfdfd', 'New', '2015-07-06 02:41:38', '2015-07-05 23:41:38'),
('0081BC36-67B3-2BA7-9357-D9BD5E9F090D', 1, 'dfdfdfd', 'dfdfdf', 'dfdfdfd', 'New', '2015-07-06 02:42:07', '2015-07-05 23:42:07');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
CREATE TABLE IF NOT EXISTS `topics` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(50) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '1',
  `sorting` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`topic_id`, `topic`, `parent_id`, `sorting`) VALUES
(1, 'Arts & culture', 0, 0),
(2, 'Beliefs', 0, 0),
(3, 'Books & Writing', 0, 0),
(4, 'Career & Biz', 0, 0),
(5, 'Crafts & Hobbies', 0, 0),
(6, 'Dancing', 0, 0),
(7, 'Education', 0, 0),
(8, 'Food & Drink', 0, 0),
(9, 'Games & Sci-Fi', 0, 0),
(10, 'Identity', 0, 0),
(11, 'Languages', 0, 0),
(12, 'Moms & Dads', 0, 0),
(13, 'Movements', 0, 0),
(14, 'Music', 0, 0),
(15, 'Outdoors', 0, 0),
(16, 'Pets', 0, 0),
(17, 'Photo & Films', 0, 0),
(18, 'Social', 0, 0),
(19, 'Sports & Fitness', 0, 0),
(20, 'Tech', 0, 0),
(21, 'Well-being', 0, 0),
(22, 'Women', 0, 0),
(23, 'Performing Arts', 1, 1),
(24, 'Storytelling', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `access_level` enum('member','admin') NOT NULL DEFAULT 'member',
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(50) DEFAULT NULL,
  `paypal_email` varchar(150) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `age` int(4) DEFAULT NULL,
  `facebook_auth` int(1) NOT NULL DEFAULT '0',
  `google_auth` int(1) NOT NULL DEFAULT '0',
  `facebook_id` varchar(200) DEFAULT NULL,
  `google_id` varchar(200) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `sitename` enum('groupjole.com','religionofhumanity.us','neighbors') DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `access_level`, `username`, `email`, `password`, `status`, `created_dt`, `name`, `paypal_email`, `gender`, `age`, `facebook_auth`, `google_auth`, `facebook_id`, `google_id`, `first_name`, `last_name`, `sitename`) VALUES
(1, 'member', '', 'manishkk74@gmail.com', '', 1, '2015-07-19 16:37:37', 'Manish Khanchandani', '', 'male', 0, 0, 1, NULL, '112913147917981568678', 'Manish', 'Khanchandani', NULL),
(2, 'member', '', 'bhanimk@gmail.com', '', 1, '2015-07-20 08:03:40', 'Bhani Khanchandani', '', 'female', 0, 0, 1, NULL, '106824472099671503991', 'Bhani', 'Khanchandani', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
