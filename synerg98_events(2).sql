-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 24, 2017 at 05:18 AM
-- Server version: 5.6.28-76.1-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `synerg98_events`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(255) NOT NULL,
  `type_id` varchar(255) NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `eng_name` varchar(100) NOT NULL,
  `ar_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_romanian_ci NOT NULL,
  `eng_company_name` varchar(100) NOT NULL,
  `ar_company_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_romanian_ci NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `weblink` varchar(100) NOT NULL,
  `start_date` varchar(50) DEFAULT NULL,
  `end_date` varchar(50) DEFAULT NULL,
  `all_day` tinyint(1) DEFAULT '0',
  `event_language` varchar(100) CHARACTER SET utf8 COLLATE utf8_romanian_ci NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `free_event` tinyint(1) NOT NULL,
  `eng_description` varchar(255) DEFAULT NULL,
  `ar_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_romanian_ci DEFAULT NULL,
  `venue` varchar(100) NOT NULL,
  `is_kids` tinyint(1) DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT NULL,
  `share_count` int(10) unsigned DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `reference_no`, `type_id`, `category_id`, `keyword`, `eng_name`, `ar_name`, `eng_company_name`, `ar_company_name`, `phone`, `email`, `weblink`, `start_date`, `end_date`, `all_day`, `event_language`, `facebook`, `twitter`, `instagram`, `free_event`, `eng_description`, `ar_description`, `venue`, `is_kids`, `is_disabled`, `is_featured`, `share_count`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '58f8a2326bf88', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 1, 25, '2017-04-20 15:57:38', '2017-04-20 15:57:38'),
(2, '58f8a29448f90', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 1, 25, '2017-04-20 15:59:16', '2017-04-20 15:59:16'),
(3, '58f8a319073a6', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 0, 25, '2017-04-20 16:01:29', '2017-04-20 16:01:29'),
(4, '58f8a319073a7', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 0, 25, '2017-04-20 16:01:29', '2017-04-20 16:01:29'),
(5, '58f8a319073a8', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 0, 25, '2017-04-20 16:01:29', '2017-04-20 16:01:29'),
(6, '58f8a319073a9', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 0, 25, '2017-04-20 16:01:29', '2017-04-20 16:01:29'),
(7, '58f8a319073a10', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 0, 25, '2017-04-20 16:01:29', '2017-04-20 16:01:29'),
(8, '58f8a319073a11', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 0, 25, '2017-04-20 16:01:29', '2017-04-20 16:01:29'),
(9, '58f8a319073a12', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 0, 25, '2017-04-20 16:01:29', '2017-04-20 16:01:29'),
(10, '58f8a319073a13', '7', '10', 'Havana,s,s,s,d,ebbs,s,she,d,d,find,s,s,s', 'testing', '', 'test company', '', '03211245780', 'test@test.com', 'google.com', '20/04/2017', '30/04/2017', 1, '2,10,5,1,14,13,4,12,18,17,9,11,6,16', 'http://www.facebook.com/tester', 'http://www.facebook.com/tester', 'http://www.instagram.com/tester', 0, 'Baby%20d%20did%0AF%0AF%0A%0AF%0AF%0AFdjjsbejd%20d%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AD%0AD%0AD%0A%0AD%0AD%0AEntendres%0AD%0AF%0AF%0AF%0AF%0A%0AG%0AD%0AC%0A%0AS%0AS%0AS%0AF', NULL, 'public', 1, 1, NULL, 0, 25, '2017-04-20 16:01:29', '2017-04-20 16:01:29'),
(11, '58f9f52daad90', '14', '8', 'cjfjfj', 'Arabic', '', 'test', '', '565658956', 'fbdhd', 'dxhxhjd', '21/4/2017', '29/4/2017', 1, '2', '', '', '', 1, 'fbdjdjdj', NULL, 'men', 1, 1, NULL, 0, 21, '2017-04-21 16:03:57', '2017-04-21 16:03:57');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
