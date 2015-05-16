-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 16, 2015 at 08:31 PM
-- Server version: 5.6.23
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `consultl_work`
--

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE IF NOT EXISTS `donations` (
  `did` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `donation_title` varchar(200) DEFAULT NULL,
  `donation_desc` text,
  `donation_needed` float DEFAULT NULL,
  `donation_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `donation_status` int(1) NOT NULL DEFAULT '1',
  `donation_category_id` int(11) DEFAULT NULL,
  `donation_payment_details` text,
  `donation_payment_status` varchar(200) NOT NULL DEFAULT 'Pending',
  `donation_payment_date` datetime DEFAULT NULL,
  `donation_image` varchar(255) DEFAULT NULL,
  `donation_paypal_email` varchar(150) DEFAULT NULL,
  `is_featured` int(1) NOT NULL DEFAULT '0',
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`did`, `user_id`, `donation_title`, `donation_desc`, `donation_needed`, `donation_created`, `donation_status`, `donation_category_id`, `donation_payment_details`, `donation_payment_status`, `donation_payment_date`, `donation_image`, `donation_paypal_email`, `is_featured`, `city`, `state`, `country`, `lat`, `lng`) VALUES
('B333092F-2C8B-BD8B-F9F1-72795C7DABC1', 5, 'Funds Needed for Dance Academy', 'I wanted to start dance academy and I needed money for it. If someone can help me in my mission then i will fulfil my dream of opening a dance academy.', 2500, '2015-04-20 04:23:40', 1, 4, NULL, 'Completed', NULL, 'f_1429493020_kizomba3.jpg', 'bhanikm@yahoo.com', 0, 'San Jose', 'California', 'United States', 37.3382082, -121.88632860000001);

-- --------------------------------------------------------

--
-- Stand-in structure for view `donations_calc_received`
--
CREATE TABLE IF NOT EXISTS `donations_calc_received` (
`total_amount` varchar(63)
,`did2` varchar(50)
);
-- --------------------------------------------------------

--
-- Table structure for table `donations_category`
--

CREATE TABLE IF NOT EXISTS `donations_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `donations_category`
--

INSERT INTO `donations_category` (`category_id`, `category`) VALUES
(1, 'Animals'),
(2, 'Business'),
(3, 'Charity'),
(4, 'Community'),
(5, 'Competitions'),
(6, 'Creative'),
(7, 'Events'),
(8, 'Faith'),
(9, 'Family'),
(10, 'Newly Weds'),
(11, 'Other'),
(12, 'Travel'),
(13, 'Wishes'),
(14, 'Medical'),
(15, 'Volunteer'),
(16, 'Emergencies'),
(17, 'Memorials'),
(18, 'Sports');

-- --------------------------------------------------------

--
-- Table structure for table `donations_received`
--

CREATE TABLE IF NOT EXISTS `donations_received` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `did` varchar(50) DEFAULT NULL,
  `donar_user_id` int(11) DEFAULT NULL,
  `donar_paypal_email` varchar(150) DEFAULT NULL,
  `donar_amount` float DEFAULT NULL,
  `donar_transaction_id` varchar(200) DEFAULT NULL,
  `donar_dt` datetime DEFAULT NULL,
  `donar_transaction_details` text,
  `donar_transaction_status` varchar(200) DEFAULT NULL,
  `donar_message` text,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `gender` enum('Male','Female') DEFAULT NULL,
  `age` int(4) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `access_level`, `username`, `email`, `password`, `status`, `created_dt`, `name`, `paypal_email`, `gender`, `age`) VALUES
(1, 'admin', 'admin', 'admin@mkgalaxy.com', 'password', 1, '2015-04-11 01:49:18', 'Admin', 'renu09@live.com', 'Female', 65),
(2, 'member', 'mkhancha', 'manishkk74@gmail.com', 'password', 1, '2015-04-11 22:44:19', 'Manish Khanchandani', 'finance@mkgalaxy.com', 'Male', 40),
(5, 'member', 'bhanikm', 'bhanikm@yahoo.com', 'passwords123', 1, '2015-04-19 23:17:52', 'Bhani K', 'bhanikm@yahoo.com', 'Female', 37);

-- --------------------------------------------------------

--
-- Structure for view `donations_calc_received`
--
DROP TABLE IF EXISTS `donations_calc_received`;

CREATE ALGORITHM=UNDEFINED DEFINER=`consultlawyers`@`localhost` SQL SECURITY DEFINER VIEW `donations_calc_received` AS select format(sum(`donations_received`.`donar_amount`),2) AS `total_amount`,`donations_received`.`did` AS `did2` from `donations_received` where (`donations_received`.`donar_transaction_status` = 'Completed') group by `donations_received`.`did`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
