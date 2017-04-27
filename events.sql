-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 24, 2017 at 03:33 PM
-- Server version: 5.7.17-0ubuntu0.16.04.2
-- PHP Version: 7.0.18-1+deb.sury.org~xenial+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `events_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
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
  `free_event` tinyint(1) NOT NULL COMMENT 'paid=0, free=1',
  `eng_description` varchar(255) DEFAULT NULL,
  `ar_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_romanian_ci DEFAULT NULL,
  `venue` varchar(100) NOT NULL,
  `is_kids` tinyint(1) DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT NULL,
  `share_count` int(10) UNSIGNED DEFAULT '0',
  `status` char(8) NOT NULL DEFAULT 'Inactive',
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `reference_no`, `type_id`, `category_id`, `keyword`, `eng_name`, `ar_name`, `eng_company_name`, `ar_company_name`, `phone`, `email`, `weblink`, `start_date`, `end_date`, `all_day`, `event_language`, `facebook`, `twitter`, `instagram`, `free_event`, `eng_description`, `ar_description`, `venue`, `is_kids`, `is_disabled`, `is_featured`, `share_count`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '58f9a7183e4f9', '2', '1,2,4', 'ayashi', 'aa', 'ksjdkasj', 'tup', 'qwewq', '123123', 'hafizmabuzar@synergistics.pk', 'http://synergistics.pk', '2017-05-14 02:00:00', '2017-05-14 16:30:00', NULL, '1,3', 'hma', 'hmabuzar', 'hafiz', 0, 'zxczxcxzc', 'xcxcxcxcxzc', 'men', NULL, NULL, NULL, 1, 'Inactive', 0, '2017-04-21 10:33:50', '2017-04-21 10:33:50'),
(2, '58f9b9e00cd67', '2', '2,3', 'mojain', 'cc', 'ksjdkasj', 'gup', 'qwewq', '123123', 'hafizmabuzar@synergistics.pk', 'http://synergistics.pk', '2017-05-15 00:00:00', '2017-05-15 00:00:00', NULL, '1,3', 'hma', 'hmabuzar', 'hafiz', 1, 'zxczxcxzc', 'xcxcxcxcxzc', 'men', NULL, NULL, NULL, 1, 'Active', 0, '2017-04-21 11:52:40', '2017-04-21 11:52:40'),
(3, '58f9b9e00cd67', '2', '1', 'enjoyment', 'ccc', 'ksjdkasj', 'gup company', 'qwewq', '123123', 'hafizmabuzar@synergistics.pk', 'http://synergistics.pk', '2017-05-11 00:00:00', '2017-05-15 00:00:00', NULL, '1,2,3,4', 'hma', 'hmabuzar', 'hafiz', 0, 'zxczxcxzc', 'xcxcxcxcxzc', 'seperate', NULL, NULL, NULL, 1, 'Active', 2, '2017-04-24 11:58:59', '2017-04-24 11:58:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
