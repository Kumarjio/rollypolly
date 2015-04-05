-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 11, 2015 at 04:08 PM
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
