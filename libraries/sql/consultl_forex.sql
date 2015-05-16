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
-- Database: `consultl_forex`
--

-- --------------------------------------------------------

--
-- Table structure for table `forex_orders`
--

CREATE TABLE IF NOT EXISTS `forex_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_creation_type` enum('Create') NOT NULL DEFAULT 'Create',
  `order_status` int(1) NOT NULL DEFAULT '1',
  `order_creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_type` enum('Buy','Sell') DEFAULT NULL,
  `order_symbol` varchar(50) DEFAULT NULL,
  `order_lots` float DEFAULT NULL,
  `order_stop_loss` int(11) DEFAULT NULL,
  `order_take_profit` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `forex_orders`
--

INSERT INTO `forex_orders` (`id`, `order_creation_type`, `order_status`, `order_creation_date`, `order_type`, `order_symbol`, `order_lots`, `order_stop_loss`, `order_take_profit`) VALUES
(1, 'Create', 1, '2014-12-04 07:04:55', 'Buy', 'USDJPY', NULL, NULL, NULL),
(2, 'Create', 1, '2014-12-04 07:24:15', 'Sell', 'GBPJPY', NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
