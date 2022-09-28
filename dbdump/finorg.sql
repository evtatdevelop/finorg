-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 28, 2022 at 01:33 AM
-- Server version: 8.0.29
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finorg`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int NOT NULL,
  `time` bigint NOT NULL,
  `currensy` varchar(255) NOT NULL,
  `value` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL DEFAULT 'cash',
  `status` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL DEFAULT 'active',
  `queue` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `time`, `currensy`, `value`, `type`, `status`, `queue`) VALUES
(126, 1662792708141, 'RUB', 100649, 'cash', 'active', 1),
(127, 1662792992365, 'RUB', 67601, 'card', 'active', 2),
(128, 1662793015047, 'THB', 26093, 'cash', 'active', 4),
(129, 1659693087119, 'THB', 210, 'card', 'not_active', 5),
(130, 1657793190888, 'HKD', 51, 'cash', 'not_active', 7),
(131, 1657793206578, 'VND', 80000, 'cash', 'not_active', 8),
(132, 1655011973373, 'INR', 368, 'cash', 'not_active', 11),
(133, 1655010783990, 'EGP', 35, 'cash', 'not_active', 12),
(134, 1655010778685, 'MYR', 55, 'cash', 'not_active', 6),
(135, 1655010775059, 'KHR', 4000, 'cash', 'not_active', 10),
(136, 1657264167990, 'SGD', 4, 'cash', 'not_active', 9),
(139, 1662793004666, 'CNY', 5000, 'cash', 'active', 3);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  `date` bigint NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  `value` int DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci DEFAULT NULL,
  `cash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8_general_ci,
  `status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  `mode` varchar(255) NOT NULL,
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `date`, `type`, `value`, `currency`, `cash`, `description`, `status`, `mode`, `add_time`, `modify_time`) VALUES
(167, 'DNS', 1657710000000, 'costs', 2299, 'RUB', 'card', 'HDD box', 'success', 'onetime', '2022-07-13 03:17:11', '2022-07-13 03:17:11'),
(188, 'Юани (банк)', 1658721600000, 'event', NULL, '', NULL, 'Позвонить в банк и разузнать обстановку по покупке валюты', 'success', 'onetime', '2022-07-23 14:03:50', '2022-07-23 14:03:50'),
(189, 'Buy yuan', 1658806200000, 'costs', 54600, 'RUB', 'cash', NULL, 'success', 'onetime', '2022-07-25 03:30:59', '2022-07-25 03:30:59'),
(191, 'Buy yuan', 1658806560000, 'profit', 5000, 'CNY', 'cash', NULL, 'success', 'onetime', '2022-07-25 03:37:25', '2022-07-25 03:37:25'),
(193, 'Alexandra (debt)', 1660281480000, 'profit', 8000, 'RUB', 'card', 'Alexandra debt for floor reparing', 'success', 'onetime', '2022-07-26 05:19:59', '2022-07-26 05:19:59'),
(195, 'Phone', 1659344837000, 'costs', 865, 'RUB', 'card', 'decsription', 'success', 'regular', '2022-08-01 09:07:17', '2022-08-01 09:07:17'),
(242, 'TV shelf', 1660378680000, 'costs', 3432, 'RUB', 'card', NULL, 'success', 'onetime', '2022-08-03 08:19:12', '2022-08-03 08:19:12'),
(251, 'Pocket Money', 1659663735000, 'costs', 1000, 'RUB', 'cash', NULL, 'success', 'regular', '2022-08-05 01:42:15', '2022-08-05 01:42:15'),
(253, 'Laptop', 1659877200000, 'costs', 59000, 'RUB', 'card', NULL, 'success', 'onetime', '2022-08-05 11:21:16', '2022-08-05 11:21:16'),
(254, 'Salory', 1660103752000, 'profit', 49062, 'RUB', 'card', 'ЗП', 'success', 'regular', '2022-08-10 03:55:53', '2022-08-10 03:55:53'),
(255, 'Home', 1660133776000, 'costs', 4300, 'RUB', 'card', NULL, 'success', 'regular', '2022-08-10 12:16:16', '2022-08-10 12:16:16'),
(256, 'Ивилина (игрушка)', 1659748080000, 'costs', 1000, 'RUB', 'card', NULL, 'active', 'onetime', '2022-08-11 01:09:47', '2022-08-11 01:09:47'),
(257, 'Pocket Money', 1660205621000, 'costs', 1000, 'RUB', 'cash', NULL, 'success', 'regular', '2022-08-11 08:13:41', '2022-08-11 08:13:41'),
(258, 'КОЦК', 1661133600000, 'profit', 690, 'RUB', 'cash', NULL, 'success', 'onetime', '2022-08-15 09:28:41', '2022-08-15 09:28:41'),
(259, 'Pocket Money', 1661130546000, 'costs', 1000, 'RUB', 'cash', NULL, 'success', 'regular', '2022-08-22 01:09:06', '2022-08-22 01:09:06'),
(260, 'Counters', 1661236095000, 'event', NULL, 'RUB', NULL, NULL, 'success', 'regular', '2022-08-23 06:28:15', '2022-08-23 06:28:15'),
(261, 'КОЦК', 1660781700000, 'profit', 680, 'RUB', 'card', NULL, 'active', 'onetime', '2022-08-24 00:16:55', '2022-08-24 00:16:55'),
(262, 'Salory', 1661399077000, 'profit', 30663, 'RUB', 'card', NULL, 'success', 'regular', '2022-08-25 03:44:37', '2022-08-25 03:44:37'),
(263, 'DNS', 1661399400000, 'costs', 2747, 'RUB', 'card', NULL, 'success', 'onetime', '2022-08-25 03:51:11', '2022-08-25 03:51:11'),
(264, 'AliExp', 1661399460000, 'costs', 1200, 'RUB', 'card', NULL, 'success', 'onetime', '2022-08-25 03:51:51', '2022-08-25 03:51:51'),
(265, '1000', 1661414400000, 'profit', 1000, 'RUB', 'card', NULL, 'success', 'onetime', '2022-08-25 08:01:03', '2022-08-25 08:01:03'),
(266, 'Мёд', 1661440860000, 'costs', 500, 'RUB', 'card', NULL, 'success', 'onetime', '2022-08-25 15:21:56', '2022-08-25 15:21:56'),
(267, 'Pocket Money', 1661484365000, 'costs', 1000, 'RUB', 'cash', NULL, 'success', 'regular', '2022-08-26 03:26:05', '2022-08-26 03:26:05'),
(268, 'unsubscribe Яндекс Plus', 1666496340000, 'event', NULL, 'RUB', NULL, NULL, 'active', 'onetime', '2022-08-26 03:41:05', '2022-08-26 03:41:05'),
(269, 'GoodFood', 1661579247000, 'costs', 2000, 'RUB', 'card', NULL, 'success', 'regular', '2022-08-27 05:47:27', '2022-08-27 05:47:27'),
(270, 'Phones', 1661748159000, 'costs', 630, 'RUB', 'card', NULL, 'success', 'regular', '2022-08-29 04:42:39', '2022-08-29 04:42:39'),
(271, 'Леха Вещи Учебный год', 1661761920000, 'costs', 6000, 'RUB', 'card', NULL, 'success', 'onetime', '2022-08-29 08:33:30', '2022-08-29 08:33:30'),
(272, 'КОЦК', 1662034500000, 'profit', 690, 'RUB', 'cash', NULL, 'success', 'onetime', '2022-09-01 12:15:47', '2022-09-01 12:15:47'),
(273, 'Pocket Money', 1662198946000, 'costs', 1000, 'RUB', 'cash', NULL, 'success', 'regular', '2022-09-03 09:55:46', '2022-09-03 09:55:46'),
(274, 'GoodFood', 1662358409000, 'costs', 2000, 'RUB', 'card', NULL, 'success', 'regular', '2022-09-05 06:13:29', '2022-09-05 06:13:29'),
(275, 'Pocket Money', 1662535961000, 'costs', 1000, 'RUB', 'cash', NULL, 'success', 'regular', '2022-09-07 07:32:41', '2022-09-07 07:32:41'),
(276, 'Mom BD', 1662646740000, 'costs', 1500, 'RUB', 'card', NULL, 'success', 'onetime', '2022-09-08 14:20:36', '2022-09-08 14:20:36'),
(277, 'Salory', 1662777383000, 'profit', 49062, 'RUB', 'card', 'ЗП', 'success', 'regular', '2022-09-10 02:36:23', '2022-09-10 02:36:23'),
(278, 'GoodFood', 1662787155000, 'costs', 1000, 'RUB', 'card', NULL, 'success', 'regular', '2022-09-10 05:19:15', '2022-09-10 05:19:15'),
(279, 'Ira (jaket)', 1662949980000, 'costs', 5000, 'RUB', 'card', NULL, 'success', 'onetime', '2022-09-12 02:34:06', '2022-09-12 02:34:06'),
(280, 'Ira (jaket)', 1663900440000, 'profit', 5000, 'RUB', 'card', NULL, 'success', 'onetime', '2022-09-12 02:34:39', '2022-09-12 02:34:39'),
(281, 'Home', 1663051605000, 'costs', 4508, 'RUB', 'card', NULL, 'success', 'regular', '2022-09-13 06:46:45', '2022-09-13 06:46:45'),
(282, 'Pocket Money', 1663054493000, 'costs', 1000, 'RUB', 'cash', NULL, 'success', 'regular', '2022-09-13 07:34:53', '2022-09-13 07:34:53'),
(283, 'Nalog', 1663212951000, 'costs', 1160, 'RUB', 'card', NULL, 'success', 'regular', '2022-09-15 03:35:51', '2022-09-15 03:35:51'),
(284, 'Алкусандра (стиралка)', 1663311480000, 'costs', 10000, 'RUB', 'card', NULL, 'success', 'onetime', '2022-09-16 06:59:15', '2022-09-16 06:59:15'),
(285, 'Александра', 1664780400000, 'profit', 5000, 'RUB', 'card', 'стиралка', 'active', 'onetime', '2022-09-16 07:01:09', '2022-09-16 07:01:09'),
(286, 'Александра', 1665558060000, 'profit', 5000, 'RUB', 'card', 'стиралка', 'active', 'onetime', '2022-09-16 07:01:44', '2022-09-16 07:01:44'),
(287, 'Висы', 1663328760000, 'costs', 2000, 'RUB', 'card', NULL, 'success', 'onetime', '2022-09-16 11:46:30', '2022-09-16 11:46:30'),
(288, 'mi band 7', 1663387620000, 'costs', 3500, 'RUB', 'card', NULL, 'success', 'onetime', '2022-09-20 04:07:55', '2022-09-20 04:07:55'),
(289, 'GoodFood', 1663742132000, 'costs', 1000, 'RUB', 'card', NULL, 'success', 'regular', '2022-09-21 06:35:32', '2022-09-21 06:35:32'),
(290, 'Salory', 1663904883000, 'profit', 30663, 'RUB', 'card', NULL, 'success', 'regular', '2022-09-23 03:48:03', '2022-09-23 03:48:03'),
(291, 'Pocket Money', 1663906326000, 'costs', 1000, 'RUB', 'cash', NULL, 'success', 'regular', '2022-09-23 04:12:06', '2022-09-23 04:12:06'),
(292, 'Counters', 1663906374000, 'event', NULL, 'RUB', NULL, NULL, 'success', 'regular', '2022-09-23 04:12:54', '2022-09-23 04:12:54'),
(293, 'GoodFood', 1664020855000, 'costs', 1000, 'RUB', 'card', NULL, 'success', 'regular', '2022-09-24 12:00:55', '2022-09-24 12:00:55');

-- --------------------------------------------------------

--
-- Table structure for table `regulars`
--

CREATE TABLE `regulars` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  `date_from` bigint NOT NULL,
  `date_to` bigint DEFAULT NULL,
  `last_date` bigint DEFAULT NULL,
  `period` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` int DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci DEFAULT NULL,
  `cash` varchar(255) DEFAULT NULL,
  `description` text,
  `status` varchar(255) NOT NULL,
  `mode` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL DEFAULT 'regular',
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `regulars`
--

INSERT INTO `regulars` (`id`, `name`, `code`, `date_from`, `date_to`, `last_date`, `period`, `type`, `value`, `currency`, `cash`, `description`, `status`, `mode`, `add_time`, `modify_time`) VALUES
(5, 'Pocket Money', 'QF8WSjI', 1659661260000, NULL, 1663894860000, 'week', 'costs', 1000, 'RUB', 'cash', NULL, 'active', 'regular', '2022-07-11 09:02:57', '2022-07-11 09:02:57'),
(10, 'Salory', 'k6qvRnB', 1657472340000, NULL, 1662829140000, 'month', 'profit', 49062, 'RUB', 'card', 'ЗП', 'active', 'regular', '2022-07-12 06:04:05', '2022-07-12 06:04:05'),
(34, 'Salory', 'hSGyShw', 1661401440000, NULL, 1664079840000, 'month', 'profit', 30663, 'RUB', 'card', NULL, 'active', 'regular', '2022-08-04 04:25:17', '2022-08-04 04:25:17'),
(35, 'GoodFood', 'tseX7uG', 1659846300000, NULL, 1664079900000, 'week', 'costs', 1000, 'RUB', 'card', NULL, 'active', 'regular', '2022-08-04 04:26:02', '2022-08-04 04:26:02'),
(36, 'Phones', 'dDAhRlz', 1661994000000, NULL, 1661994000000, 'month', 'costs', 630, 'RUB', 'card', NULL, 'active', 'regular', '2022-08-04 04:27:23', '2022-08-04 04:27:23'),
(37, 'тест', 'jRWwwVl', 1659928320000, 1659669192000, 1659841920000, 'day', 'profit', 100, 'RUB', 'cash', NULL, 'active', 'regular', '2022-08-05 03:12:57', '2022-08-05 03:12:57'),
(38, 'Counters', '8woF9bz', 1661265000000, NULL, 1663943400000, 'month', 'event', NULL, 'RUB', NULL, NULL, 'active', 'regular', '2022-08-05 08:18:49', '2022-08-05 08:18:49'),
(39, 'Home', 'xMzhIsO', 1660213800000, NULL, 1662892200000, 'month', 'costs', 4508, 'RUB', 'card', NULL, 'active', 'regular', '2022-08-09 00:59:00', '2022-08-09 00:59:00'),
(40, 'Nalog', 'bXpSztR', 1663212900000, NULL, 1663212900000, 'year', 'costs', 1160, 'RUB', 'card', NULL, 'active', 'regular', '2022-09-15 03:35:48', '2022-09-15 03:35:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regulars`
--
ALTER TABLE `regulars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;

--
-- AUTO_INCREMENT for table `regulars`
--
ALTER TABLE `regulars`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
