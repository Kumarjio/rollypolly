-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 16, 2015 at 08:24 PM
-- Server version: 5.6.23
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `consultl_chess`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_name` varchar(200) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`book_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `book_name`, `parent_id`) VALUES
(1, '5334 Problems, Combinations, and Games', 0),
(2, 'Mate in one', 1),
(3, 'Mate in two', 1),
(4, 'Mate in three', 1),
(5, '600 miniature games', 1),
(6, 'f3 (f6) combinations', 5),
(7, 'g3 (g6)  combinations', 5),
(8, 'h3 (h6) combinations', 5),
(9, 'f2 (f7) combinations', 5),
(10, 'g2 (g7) combinations', 5),
(11, 'h2 (h7) combinations', 5),
(12, 'Simple Endgames', 1),
(13, 'Tournament - game combinations', 1),
(14, 'White to move', 3),
(15, 'Combinations - white to move', 3),
(16, 'Combinations - black to move', 3),
(17, 'Compositions - white to move', 3),
(18, 'Combinational compositions - white to move', 3),
(19, 'Combinations - white to move', 4),
(20, 'Combinations - black to move', 4);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `player_id` int(11) NOT NULL AUTO_INCREMENT,
  `player_name` varchar(200) DEFAULT NULL,
  `player_email` varchar(200) DEFAULT NULL,
  `player_details` text,
  PRIMARY KEY (`player_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`player_id`, `player_name`, `player_email`, `player_details`) VALUES
(1, 'Shone Chacko', NULL, NULL),
(2, 'Michelle Shone', NULL, NULL),
(3, 'Emmanuel Shone', NULL, NULL),
(4, 'Lisa Himawan', NULL, NULL),
(5, 'Bruce Ricard', NULL, NULL),
(6, 'Loanna Tran', NULL, NULL),
(7, 'Ngai Fong', NULL, NULL),
(8, 'Greg Nakanishi', NULL, NULL),
(9, 'Kris Koratikere', NULL, NULL),
(10, 'Kuldeep Ghate', NULL, NULL),
(11, 'Hassan Sharghi', NULL, NULL),
(12, 'Elijah Chua', NULL, NULL),
(13, 'Erwin Chua', NULL, NULL),
(14, 'Shanmuganatha', NULL, NULL),
(15, 'N Thirunavukkarsu', NULL, NULL),
(16, 'Ahmet Ustunel', NULL, NULL),
(17, 'John OShea', NULL, NULL),
(18, 'Manav Singh', NULL, NULL),
(19, 'Manas Paldhe', NULL, NULL),
(20, 'Clayton Poon', NULL, NULL),
(21, 'Nitin Chutani', NULL, NULL),
(22, 'zhiyang zhou', NULL, NULL),
(23, 'Sathvik Rajesh', NULL, NULL),
(24, 'Saketh Rajesh', NULL, NULL),
(25, 'Vipul Kanade', NULL, NULL),
(26, 'Krishna Sunkammurali', NULL, NULL),
(27, 'Jenna Himawan', NULL, NULL),
(28, 'Jyoti Rani', NULL, NULL),
(29, 'Aman Gupta', NULL, NULL),
(30, 'Shallu Bhalla', NULL, NULL),
(31, 'Luc Truong', NULL, NULL),
(32, 'Narayana Antharvedigoda', NULL, NULL),
(33, 'Anika B', NULL, NULL),
(34, 'Neha Mehrotra', NULL, NULL),
(35, 'DanTram Nguyen', NULL, NULL),
(36, 'Dharani Govindan', NULL, NULL),
(37, 'Nithin Katere', NULL, NULL),
(38, 'Dante Vasudevan', NULL, NULL),
(39, 'Bob Taniguchi', NULL, NULL),
(40, 'Srini Gowthaman', NULL, NULL),
(41, 'Nyel Azam', NULL, NULL),
(42, 'Caroline Youkhanna', NULL, NULL),
(43, 'Hung-Tam Yie', NULL, NULL),
(44, 'susan thein', NULL, NULL),
(45, 'Amit Bansal', NULL, NULL),
(46, 'Anjika Bansal', NULL, NULL),
(47, 'Abhinav Garg', NULL, NULL),
(48, 'Abdul Ahmed', NULL, NULL),
(49, 'Kelly Spivey', NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `pointCalculation`
--
CREATE TABLE IF NOT EXISTS `pointCalculation` (
`totalpoints` double
,`player_id` int(11)
,`tournament_id` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE IF NOT EXISTS `problems` (
  `problem_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL,
  `sorting` int(11) DEFAULT NULL,
  `fen` text,
  `sidetomove` enum('w','b') DEFAULT NULL,
  `comments` text,
  `solution` text,
  PRIMARY KEY (`problem_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`problem_id`, `book_id`, `sorting`, `fen`, `sidetomove`, `comments`, `solution`) VALUES
(1, 2, 1, '3q1rk1/5pbp/5Qp1/8/8/2B5/5PPP/6K1 w - - 0 1', 'w', NULL, 'Qxg7+'),
(2, 2, 2, '2r2rk1/2q2p1p/6pQ/4P1N1/8/8/1PP5/2KR4 w - - 0 1', 'w', NULL, 'Qxh7+'),
(3, 2, 3, 'r2q1rk1/pp1p1p1p/5PpQ/8/4N3/8/PP3PPP/R5K1 w - - 0 1', 'w', NULL, 'Qg7+'),
(4, 2, 4, '6r1/7k/2p1pPp1/3p4/8/1R6/5PPP/6K1 w - - 0 1', 'w', NULL, 'Rh3+'),
(5, 2, 5, '1r4k1/1q3p2/5Bp1/8/8/8/PP6/1K5R w - - 0 1', 'w', NULL, 'Rh8+'),
(6, 2, 6, 'r4rk1/5p1p/8/8/8/8/1BP5/2KR4 w - - 0 1', 'w', NULL, 'Rg1+'),
(7, 2, 7, '4r2k/4r1p1/6p1/8/2B5/8/1PP5/2KR4 w - - 0 1', 'w', NULL, 'Rh1+'),
(8, 2, 8, '8/2r1N1pk/8/8/8/2q2p2/2P5/2KR4 w - - 0 1', 'w', NULL, 'Rh1+');

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

CREATE TABLE IF NOT EXISTS `tournament` (
  `tournament_id` int(11) NOT NULL AUTO_INCREMENT,
  `tournament` varchar(255) NOT NULL,
  PRIMARY KEY (`tournament_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tournament`
--

INSERT INTO `tournament` (`tournament_id`, `tournament`) VALUES
(1, 'San Jose Open Tournament');

-- --------------------------------------------------------

--
-- Table structure for table `tournament_pairings`
--

CREATE TABLE IF NOT EXISTS `tournament_pairings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tournament_id` int(11) DEFAULT NULL,
  `player_id` int(11) DEFAULT NULL,
  `player_id2` int(11) DEFAULT NULL,
  `round` int(11) DEFAULT NULL,
  `score` float DEFAULT NULL,
  `white` int(11) DEFAULT NULL,
  `black` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `tournament_pairings`
--

INSERT INTO `tournament_pairings` (`id`, `tournament_id`, `player_id`, `player_id2`, `round`, `score`, `white`, `black`) VALUES
(1, 1, 19, 46, 1, NULL, 19, 46),
(2, 1, 46, 19, 1, NULL, 19, 46),
(3, 1, 13, 15, 1, NULL, 13, 15),
(4, 1, 15, 13, 1, NULL, 13, 15),
(5, 1, 31, 44, 1, NULL, 31, 44),
(6, 1, 44, 31, 1, NULL, 31, 44),
(7, 1, 21, 30, 1, NULL, 21, 30),
(8, 1, 30, 21, 1, NULL, 21, 30),
(9, 1, 1, 32, 1, NULL, 1, 32),
(10, 1, 32, 1, 1, NULL, 1, 32),
(11, 1, 3, 6, 1, NULL, 3, 6),
(12, 1, 6, 3, 1, NULL, 3, 6),
(13, 1, 12, 47, 1, NULL, 12, 47),
(14, 1, 47, 12, 1, NULL, 12, 47),
(15, 1, 10, 35, 1, NULL, 10, 35),
(16, 1, 35, 10, 1, NULL, 10, 35),
(17, 1, 8, 28, 1, NULL, 8, 28),
(18, 1, 28, 8, 1, NULL, 8, 28),
(19, 1, 2, 43, 1, NULL, 2, 43),
(20, 1, 43, 2, 1, NULL, 2, 43),
(21, 1, 7, 45, 1, NULL, 7, 45),
(22, 1, 45, 7, 1, NULL, 7, 45),
(23, 1, 14, 48, 1, NULL, 14, 48),
(24, 1, 48, 14, 1, NULL, 14, 48),
(25, 1, 9, 24, 1, NULL, 9, 24),
(26, 1, 24, 9, 1, NULL, 9, 24),
(27, 1, 4, 26, 1, NULL, 4, 26),
(28, 1, 26, 4, 1, NULL, 4, 26),
(29, 1, 36, 49, 1, NULL, 36, 49),
(30, 1, 49, 36, 1, NULL, 36, 49),
(31, 1, 16, 27, 1, NULL, 16, 27),
(32, 1, 27, 16, 1, NULL, 16, 27),
(33, 1, 38, 39, 1, NULL, 38, 39),
(34, 1, 39, 38, 1, NULL, 38, 39),
(35, 1, 29, 37, 1, NULL, 29, 37),
(36, 1, 37, 29, 1, NULL, 29, 37),
(37, 1, 25, 34, 1, NULL, 25, 34),
(38, 1, 34, 25, 1, NULL, 25, 34),
(39, 1, 5, 41, 1, NULL, 5, 41),
(40, 1, 41, 5, 1, NULL, 5, 41),
(41, 1, 11, 23, 1, NULL, 11, 23),
(42, 1, 23, 11, 1, NULL, 11, 23),
(43, 1, 18, 33, 1, NULL, 18, 33),
(44, 1, 33, 18, 1, NULL, 18, 33),
(45, 1, 17, 42, 1, NULL, 17, 42),
(46, 1, 42, 17, 1, NULL, 17, 42),
(47, 1, 22, 40, 1, NULL, 22, 40),
(48, 1, 40, 22, 1, NULL, 22, 40),
(49, 1, 20, 20, 1, NULL, 20, 20);

-- --------------------------------------------------------

--
-- Table structure for table `tournament_player`
--

CREATE TABLE IF NOT EXISTS `tournament_player` (
  `tournament_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  PRIMARY KEY (`tournament_id`,`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tournament_player`
--

INSERT INTO `tournament_player` (`tournament_id`, `player_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),
(1, 41),
(1, 42),
(1, 43),
(1, 44),
(1, 45),
(1, 46),
(1, 47),
(1, 48),
(1, 49),
(1, 50),
(1, 51),
(1, 52),
(1, 53),
(1, 54),
(1, 55),
(1, 56),
(1, 57),
(1, 58),
(1, 59),
(1, 60);

-- --------------------------------------------------------

--
-- Structure for view `pointCalculation`
--
DROP TABLE IF EXISTS `pointCalculation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`consultlawyers`@`localhost` SQL SECURITY DEFINER VIEW `pointCalculation` AS select sum(`tournament_pairings`.`score`) AS `totalpoints`,`tournament_pairings`.`player_id` AS `player_id`,`tournament_pairings`.`tournament_id` AS `tournament_id` from `tournament_pairings` where (`tournament_pairings`.`score` is not null) group by `tournament_pairings`.`player_id`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
