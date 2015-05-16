-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 16, 2015 at 08:30 PM
-- Server version: 5.6.23
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `consultl_groupjole`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

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

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `user_id`, `group_name`, `group_headline`, `group_url`, `group_description`, `group_image_url`, `group_youtube_url`, `members_called`, `group_created_dt`, `group_status`, `group_expiry_date`, `payment_status`, `payment_date`, `payment_history`, `topics_added`, `location`, `lat`, `lng`, `city`, `state`, `country`, `group_type`, `who_can_create_pages`, `admin_approved`, `require_photo`, `require_profile_questions`, `profile_questions`, `welcome_message`, `visibility`) VALUES
('674EBF61-2A28-F7BA-B9DF-5C9A0D9BDAB3', 12, 'dgd', 'dgd', 'dgd', 'dgd', NULL, NULL, 'Members', '2015-04-29 04:38:34', 1, NULL, 'Completed', NULL, NULL, '["24"]', 'Santa Clara, CA, United States', 37.3541079, -121.9552356, 'Santa Clara', 'CA', 'United States', 1, 1, 1, 0, 0, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

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
-- Table structure for table `topics`
--

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

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `access_level` enum('member','admin') NOT NULL DEFAULT 'member',
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created_dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `access_level`, `username`, `email`, `password`, `status`, `created_dt`, `name`, `paypal_email`, `gender`, `age`, `facebook_auth`, `google_auth`, `facebook_id`, `google_id`, `first_name`, `last_name`) VALUES
(1, 'member', 'mkhancha', 'mkhancha@mkgalaxy.com', 'password', 1, '2015-04-25 07:11:00', 'Manish Khanchandani', 'finance@mkgalaxy.com', 'male', 41, 0, 0, NULL, NULL, NULL, NULL),
(2, 'member', 'nkhancha', 'nkhancha@mkgalaxy.com', 'myflash74', 1, '2015-04-25 17:23:20', 'nkhancha here', 'nkhanchap@mkgalaxy.com', 'male', 41, 0, 0, NULL, NULL, NULL, NULL),
(3, 'member', 'nkhancha1', 'nkhancha@mkgalaxy.com', 'dddd', 1, '2015-04-25 17:24:04', 'nkhancha here', 'nkhanchap@mkgalaxy.com', 'male', 41, 0, 0, NULL, NULL, NULL, NULL),
(4, 'member', 'nkhancha12', 'nkhancha@mkgalaxy.com', 'abc', 1, '2015-04-25 17:25:53', 'nkhancha here', 'nkhanchap@mkgalaxy.com', 'male', 41, 0, 0, NULL, NULL, NULL, NULL),
(5, 'member', 'nkhancha121', 'nkhancha@mkgalaxy.com', '111', 1, '2015-04-25 17:26:38', 'nkhancha here', 'nkhanchap@mkgalaxy.com', 'male', 41, 0, 0, NULL, NULL, NULL, NULL),
(6, 'member', 'nkhancha1211', 'nkhancha@mkgalaxy.com', '111', 1, '2015-04-25 17:28:23', 'nkhancha here', 'nkhanchap@mkgalaxy.com', 'male', 41, 0, 0, NULL, NULL, NULL, NULL),
(7, 'member', 'mango', 'mango@mkglaxy.com', 'password', 1, '2015-04-25 17:34:23', 'mango@mkgalaxy.com', 'mango@mkgalaxy.com', 'male', 18, 0, 0, NULL, NULL, NULL, NULL),
(8, 'member', 'user1', 'user1@mkgalaxy.com', 'password', 1, '2015-04-25 17:39:18', 'user1kk', 'user1@mkgalaxy.com', 'male', 41, 0, 0, NULL, NULL, NULL, NULL),
(9, 'member', 'kranti', 'kranti@mkgalaxy.com', 'kk', 1, '2015-04-25 17:42:47', 'kranti', 'kranti@mkgalaxy.com', 'male', 41, 0, 0, NULL, NULL, NULL, NULL),
(10, 'member', 'sss', 'sss@mkgalaxy.com', 'sss', 1, '2015-04-25 17:43:52', 'st', 'sss@mkgalaxy.com', 'male', 18, 0, 0, NULL, NULL, NULL, NULL),
(11, 'member', 'nk', 'nk@mkgalaxy.com', 'pp', 1, '2015-04-27 00:38:25', 'nk@mkgalaxy.com', 'nk@mkgalaxy.com', 'male', 31, 0, 0, NULL, NULL, NULL, NULL),
(12, 'member', NULL, 'naveenkhanchandani@gmail.com', NULL, 1, '2015-04-29 07:06:31', 'Manish Khanchandani', NULL, 'male', NULL, 1, 0, '10153196420515977', NULL, 'Manish', 'Khanchandani');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
