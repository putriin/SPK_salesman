-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 10:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_topsis`
--

-- --------------------------------------------------------

--
-- Table structure for table `hasil_perhitungan`
--

CREATE TABLE `hasil_perhitungan` (
  `id` int(11) NOT NULL,
  `periode` varchar(7) NOT NULL,
  `salesman_id` int(11) NOT NULL,
  `nilai_preferensi` decimal(10,6) NOT NULL,
  `ranking` int(11) NOT NULL,
  `d_plus` decimal(12,8) NOT NULL DEFAULT 0.00000000,
  `d_minus` decimal(12,8) NOT NULL DEFAULT 0.00000000,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hasil_perhitungan`
--

INSERT INTO `hasil_perhitungan` (`id`, `periode`, `salesman_id`, `nilai_preferensi`, `ranking`, `d_plus`, `d_minus`, `created_at`, `updated_at`) VALUES
(4, '2025-10', 1, 0.945038, 1, 0.01701135, 0.29249726, '2026-04-02 06:30:13', '2026-04-02 06:30:13'),
(5, '2025-10', 3, 0.054962, 2, 0.29249726, 0.01701135, '2026-04-02 06:30:13', '2026-04-02 06:30:13'),
(56, '2026-03', 3, 0.626327, 1, 0.02022983, 0.03390794, '2026-04-15 04:11:58', '2026-04-15 04:11:58'),
(57, '2026-03', 1, 0.520099, 2, 0.03111047, 0.03371638, '2026-04-15 04:11:58', '2026-04-15 04:11:58'),
(58, '2026-03', 2, 0.381930, 3, 0.03902656, 0.02411604, '2026-04-15 04:11:58', '2026-04-15 04:11:58'),
(65, '2026-02', 1, 0.665778, 1, 0.01628273, 0.03243560, '2026-04-19 02:28:16', '2026-04-19 02:28:16'),
(66, '2026-02', 5, 0.652655, 2, 0.01737075, 0.03263936, '2026-04-19 02:28:16', '2026-04-19 02:28:16'),
(67, '2026-02', 3, 0.366037, 3, 0.03105759, 0.01793200, '2026-04-19 02:28:16', '2026-04-19 02:28:16'),
(77, '2026-04', 1, 0.983629, 1, 0.00355569, 0.21363527, '2026-04-30 13:01:27', '2026-04-30 13:01:27'),
(78, '2026-04', 2, 0.880222, 2, 0.02602132, 0.19122580, '2026-04-30 13:01:27', '2026-04-30 13:01:27'),
(79, '2026-04', 3, 0.033912, 3, 0.21374548, 0.00750308, '2026-04-30 13:01:27', '2026-04-30 13:01:27'),
(84, '2026-10', 1, 0.751909, 1, 0.10336782, 0.31328554, '2026-05-06 06:05:52', '2026-05-06 06:05:52'),
(85, '2026-10', 5, 0.248091, 2, 0.31328554, 0.10336782, '2026-05-06 06:05:52', '2026-05-06 06:05:52'),
(86, '2026-10', 2, 0.211458, 3, 0.31167524, 0.08357988, '2026-05-06 06:05:52', '2026-05-06 06:05:52'),
(87, '2026-10', 3, 0.117650, 4, 0.31732985, 0.04231198, '2026-05-06 06:05:52', '2026-05-06 06:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `tipe` enum('benefit','cost') NOT NULL DEFAULT 'benefit',
  `bobot` decimal(12,9) NOT NULL,
  `bobot_normalisasi` decimal(10,8) NOT NULL DEFAULT 0.00000000,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `kode_kriteria`, `nama_kriteria`, `tipe`, `bobot`, `bobot_normalisasi`, `created_at`, `updated_at`) VALUES
(1, 'C1', 'Close Order', 'benefit', 5.000000000, 0.27777778, '2026-03-16 14:21:53', '2026-05-05 03:33:46'),
(2, 'C2', 'Product Knowledge', 'benefit', 3.000000000, 0.16666667, '2026-03-16 14:21:53', '2026-05-05 03:33:46'),
(3, 'C3', 'Kedisiplinan', 'benefit', 4.000000000, 0.22222222, '2026-03-16 14:21:53', '2026-05-05 03:33:46'),
(4, 'C4', 'Tanggung Jawab', 'benefit', 4.000000000, 0.22222222, '2026-03-16 14:21:53', '2026-05-05 03:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id` int(11) NOT NULL,
  `periode` varchar(7) NOT NULL,
  `salesman_id` int(11) NOT NULL,
  `kriteria_id` int(11) NOT NULL,
  `nilai` decimal(8,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id`, `periode`, `salesman_id`, `kriteria_id`, `nilai`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2026-04', 1, 1, 90.00, '2026-03-16 14:26:10', '2026-05-05 10:33:56', '2026-05-05 10:33:56'),
(2, '2026-04', 1, 2, 85.00, '2026-03-16 14:26:10', '2026-05-05 10:33:56', '2026-05-05 10:33:56'),
(3, '2026-04', 1, 3, 88.00, '2026-03-16 14:26:10', '2026-05-05 10:33:56', '2026-05-05 10:33:56'),
(4, '2026-04', 1, 4, 92.00, '2026-03-16 14:26:10', '2026-05-05 10:33:56', '2026-05-05 10:33:56'),
(13, '2026-04', 2, 1, 81.00, '2026-03-17 14:10:04', '2026-05-05 10:34:58', '2026-05-05 10:34:58'),
(14, '2026-04', 2, 2, 87.00, '2026-03-17 14:10:04', '2026-05-05 10:34:58', '2026-05-05 10:34:58'),
(15, '2026-04', 2, 3, 84.00, '2026-03-17 14:10:04', '2026-05-05 10:34:58', '2026-05-05 10:34:58'),
(16, '2026-04', 2, 4, 85.00, '2026-03-17 14:10:04', '2026-05-05 10:34:58', '2026-05-05 10:34:58'),
(17, '2026-03', 2, 1, 90.00, '2026-04-02 03:04:01', '2026-05-05 10:34:50', '2026-05-05 10:34:50'),
(18, '2026-03', 2, 2, 70.00, '2026-04-02 03:04:01', '2026-05-05 10:34:50', '2026-05-05 10:34:50'),
(19, '2026-03', 2, 3, 80.00, '2026-04-02 03:04:01', '2026-05-05 10:34:50', '2026-05-05 10:34:50'),
(20, '2026-03', 2, 4, 80.00, '2026-04-02 03:04:01', '2026-05-05 10:34:50', '2026-05-05 10:34:50'),
(21, '2026-03', 1, 1, 78.00, '2026-04-02 03:09:28', '2026-05-05 10:33:50', '2026-05-05 10:33:50'),
(22, '2026-03', 1, 2, 90.00, '2026-04-02 03:09:28', '2026-05-05 10:33:50', '2026-05-05 10:33:50'),
(23, '2026-03', 1, 3, 80.00, '2026-04-02 03:09:28', '2026-05-05 10:33:50', '2026-05-05 10:33:50'),
(24, '2026-03', 1, 4, 80.00, '2026-04-02 03:09:28', '2026-05-05 10:33:50', '2026-05-05 10:33:50'),
(25, '2025-10', 1, 1, 33.00, '2026-04-02 04:53:10', '2026-05-05 10:33:31', '2026-05-05 10:33:31'),
(26, '2025-10', 1, 2, 95.00, '2026-04-02 04:53:10', '2026-05-05 10:33:31', '2026-05-05 10:33:31'),
(27, '2025-10', 1, 3, 80.00, '2026-04-02 04:53:10', '2026-05-05 10:33:31', '2026-05-05 10:33:31'),
(28, '2025-10', 1, 4, 90.00, '2026-04-02 04:53:10', '2026-05-05 10:33:31', '2026-05-05 10:33:31'),
(29, '2025-10', 3, 1, 1.00, '2026-04-02 06:30:03', '2026-05-05 10:35:04', '2026-05-05 10:35:04'),
(30, '2025-10', 3, 2, 79.00, '2026-04-02 06:30:03', '2026-05-05 10:35:04', '2026-05-05 10:35:04'),
(31, '2025-10', 3, 3, 89.00, '2026-04-02 06:30:03', '2026-05-05 10:35:04', '2026-05-05 10:35:04'),
(32, '2025-10', 3, 4, 85.00, '2026-04-02 06:30:03', '2026-05-05 10:35:04', '2026-05-05 10:35:04'),
(41, '2026-03', 3, 1, 90.00, '2026-04-15 04:11:41', '2026-05-05 10:32:47', '2026-05-05 10:32:47'),
(42, '2026-03', 3, 2, 78.00, '2026-04-15 04:11:41', '2026-05-05 10:32:47', '2026-05-05 10:32:47'),
(43, '2026-03', 3, 3, 88.00, '2026-04-15 04:11:41', '2026-05-05 10:32:47', '2026-05-05 10:32:47'),
(44, '2026-03', 3, 4, 89.00, '2026-04-15 04:11:41', '2026-05-05 10:32:47', '2026-05-05 10:32:47'),
(49, '2026-02', 1, 1, 90.00, '2026-04-19 01:57:03', '2026-05-05 10:33:38', '2026-05-05 10:33:38'),
(50, '2026-02', 1, 2, 88.00, '2026-04-19 01:57:03', '2026-05-05 10:33:38', '2026-05-05 10:33:38'),
(51, '2026-02', 1, 3, 78.00, '2026-04-19 01:57:03', '2026-05-05 10:33:38', '2026-05-05 10:33:38'),
(52, '2026-02', 1, 4, 88.00, '2026-04-19 01:57:03', '2026-05-05 10:33:38', '2026-05-05 10:33:38'),
(53, '2026-02', 5, 1, 90.00, '2026-04-19 02:27:25', '2026-05-05 10:11:45', '2026-05-05 10:11:45'),
(54, '2026-02', 5, 2, 78.00, '2026-04-19 02:27:25', '2026-05-05 10:11:45', '2026-05-05 10:11:45'),
(55, '2026-02', 5, 3, 88.00, '2026-04-19 02:27:25', '2026-05-05 10:11:45', '2026-05-05 10:11:45'),
(56, '2026-02', 5, 4, 90.00, '2026-04-19 02:27:25', '2026-05-05 10:11:45', '2026-05-05 10:11:45'),
(57, '2026-02', 3, 1, 76.00, '2026-04-19 02:28:04', '2026-05-05 10:32:56', '2026-05-05 10:32:56'),
(58, '2026-02', 3, 2, 89.00, '2026-04-19 02:28:04', '2026-05-05 10:32:56', '2026-05-05 10:32:56'),
(59, '2026-02', 3, 3, 80.00, '2026-04-19 02:28:04', '2026-05-05 10:32:56', '2026-05-05 10:32:56'),
(60, '2026-02', 3, 4, 90.00, '2026-04-19 02:28:04', '2026-05-05 10:32:56', '2026-05-05 10:32:56'),
(61, '2026-04', 3, 1, 4.00, '2026-04-30 12:26:05', '2026-05-05 10:11:56', '2026-05-05 10:11:56'),
(62, '2026-04', 3, 2, 78.00, '2026-04-30 12:26:05', '2026-05-05 10:11:56', '2026-05-05 10:11:56'),
(63, '2026-04', 3, 3, 89.00, '2026-04-30 12:26:05', '2026-05-05 10:11:56', '2026-05-05 10:11:56'),
(64, '2026-04', 3, 4, 85.00, '2026-04-30 12:26:05', '2026-05-05 10:11:56', '2026-05-05 10:11:56'),
(65, '2025-10', 2, 1, 1.00, '2026-05-05 10:40:33', '2026-05-06 04:07:19', '2026-05-06 04:07:19'),
(66, '2025-10', 2, 2, 85.00, '2026-05-05 10:40:33', '2026-05-06 04:07:19', '2026-05-06 04:07:19'),
(67, '2025-10', 2, 3, 70.00, '2026-05-05 10:40:33', '2026-05-06 04:07:19', '2026-05-06 04:07:19'),
(68, '2025-10', 2, 4, 85.00, '2026-05-05 10:40:33', '2026-05-06 04:07:19', '2026-05-06 04:07:19'),
(69, '2025-10', 2, 1, 1.00, '2026-05-06 04:07:19', '2026-05-06 04:32:40', '2026-05-06 04:32:40'),
(70, '2025-10', 2, 2, 85.00, '2026-05-06 04:07:19', '2026-05-06 04:32:40', '2026-05-06 04:32:40'),
(71, '2025-10', 2, 3, 70.00, '2026-05-06 04:07:19', '2026-05-06 04:32:40', '2026-05-06 04:32:40'),
(72, '2025-10', 2, 4, 85.00, '2026-05-06 04:07:19', '2026-05-06 04:32:40', '2026-05-06 04:32:40'),
(73, '2026-10', 1, 1, 184.00, '2026-05-06 04:28:43', '2026-05-06 04:28:43', NULL),
(74, '2026-10', 1, 2, 95.00, '2026-05-06 04:28:43', '2026-05-06 04:28:43', NULL),
(75, '2026-10', 1, 3, 30.00, '2026-05-06 04:28:43', '2026-05-06 04:28:43', NULL),
(76, '2026-10', 1, 4, 80.00, '2026-05-06 04:28:43', '2026-05-06 04:28:43', NULL),
(77, '2026-10', 3, 1, 1.00, '2026-05-06 04:29:24', '2026-05-06 04:29:24', NULL),
(78, '2026-10', 3, 2, 80.00, '2026-05-06 04:29:24', '2026-05-06 04:29:24', NULL),
(79, '2026-10', 3, 3, 50.00, '2026-05-06 04:29:24', '2026-05-06 04:29:24', NULL),
(80, '2026-10', 3, 4, 85.00, '2026-05-06 04:29:24', '2026-05-06 04:29:24', NULL),
(81, '2026-10', 5, 1, 0.00, '2026-05-06 04:30:45', '2026-05-06 04:30:45', NULL),
(82, '2026-10', 5, 2, 75.00, '2026-05-06 04:30:45', '2026-05-06 04:30:45', NULL),
(83, '2026-10', 5, 3, 80.00, '2026-05-06 04:30:45', '2026-05-06 04:30:45', NULL),
(84, '2026-10', 5, 4, 85.00, '2026-05-06 04:30:45', '2026-05-06 04:30:45', NULL),
(85, '2025-10', 2, 1, 1.00, '2026-05-06 04:32:40', '2026-05-06 04:32:40', NULL),
(86, '2025-10', 2, 2, 85.00, '2026-05-06 04:32:40', '2026-05-06 04:32:40', NULL),
(87, '2025-10', 2, 3, 70.00, '2026-05-06 04:32:40', '2026-05-06 04:32:40', NULL),
(88, '2025-10', 2, 4, 85.00, '2026-05-06 04:32:40', '2026-05-06 04:32:40', NULL),
(89, '2026-10', 2, 1, 1.00, '2026-05-06 05:36:25', '2026-05-06 05:36:25', NULL),
(90, '2026-10', 2, 2, 85.00, '2026-05-06 05:36:25', '2026-05-06 05:36:25', NULL),
(91, '2026-10', 2, 3, 70.00, '2026-05-06 05:36:25', '2026-05-06 05:36:25', NULL),
(92, '2026-10', 2, 4, 85.00, '2026-05-06 05:36:25', '2026-05-06 05:36:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `perhitungan_snapshot`
--

CREATE TABLE `perhitungan_snapshot` (
  `id` int(11) NOT NULL,
  `periode` varchar(7) NOT NULL,
  `source_hash` varchar(64) NOT NULL,
  `snapshot_json` longtext NOT NULL,
  `calculated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perhitungan_snapshot`
--

INSERT INTO `perhitungan_snapshot` (`id`, `periode`, `source_hash`, `snapshot_json`, `calculated_at`, `created_at`, `updated_at`) VALUES
(1, '2026-04', '31f52bf0ce5871e2c22ee6b602f01c210689806d5a633e43f0cafc51b94759e0', '{\"criteria\":[{\"id\":\"1\",\"kode\":\"C1\",\"nama\":\"Close Order\",\"bobot\":0.3,\"tipe\":\"benefit\"},{\"id\":\"2\",\"kode\":\"C2\",\"nama\":\"Product Knowledge\",\"bobot\":0.232887189,\"tipe\":\"benefit\"},{\"id\":\"3\",\"kode\":\"C3\",\"nama\":\"Kedisiplinan\",\"bobot\":0.226195029,\"tipe\":\"benefit\"},{\"id\":\"4\",\"kode\":\"C4\",\"nama\":\"Tanggung Jawab\",\"bobot\":0.240917782,\"tipe\":\"benefit\"}],\"weights\":[0.3,0.232887189,0.226195029,0.240917782],\"alternatives\":[{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"scores\":[90,85,88,92]},{\"id\":\"2\",\"kode\":\"A02\",\"nama\":\"Daeng\",\"scores\":[81,87,84,85]},{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"scores\":[4,78,89,85]}],\"divisors\":[121.1486689980538,144.49221432312538,150.7348665704123,151.37370973851438],\"normalized\":[[0.742888888044208,0.5882669900117663,0.5838065339640105,0.6077673603885537],[0.6685999992397872,0.6021085662473373,0.5572698733292828,0.5615241916633378],[0.033017283913075915,0.5398214731872679,0.5904406991226925,0.5615241916633378]],\"weighted\":[[0.22286666641326242,0.13699984568533133,0.13205413588037884,0.14642196443680502],[0.20057999977193616,0.14022337146616268,0.12605167515854343,0.13528116279487423],[0.009905185173922774,0.1257175054524217,0.1335547510608377,0.13528116279487423]],\"idealPositive\":[0.22286666641326242,0.14022337146616268,0.1335547510608377,0.14642196443680502],\"idealNegative\":[0.009905185173922774,0.1257175054524217,0.12605167515854343,0.13528116279487423],\"results\":[{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"d_plus\":0.003555694640925724,\"d_minus\":0.2136352749166831,\"preferensi\":0.9836287178598252,\"ranking\":1},{\"id\":\"2\",\"kode\":\"A02\",\"nama\":\"Daeng\",\"d_plus\":0.02602132047383532,\"d_minus\":0.19122579603912068,\"preferensi\":0.8802224816996203,\"ranking\":2},{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"d_plus\":0.21374547972223196,\"d_minus\":0.007503075902294265,\"preferensi\":0.03391242885683508,\"ranking\":3}],\"incompleteAlternatives\":[{\"id\":\"5\",\"kode\":\"A04\",\"nama\":\"Dodi\",\"scores\":[]},{\"id\":\"6\",\"kode\":\"a05\",\"nama\":\"inl\",\"scores\":[]},{\"id\":\"8\",\"kode\":\"vr\",\"nama\":\"vtrwr\",\"scores\":[]}],\"winner\":{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"d_plus\":0.003555694640925724,\"d_minus\":0.2136352749166831,\"preferensi\":0.9836287178598252,\"ranking\":1},\"validAlternativeCount\":3}', '2026-04-30 13:01:27', '2026-04-19 05:47:42', '2026-04-30 13:01:27'),
(2, '2026-10', '2657a65b86f51c56d225b8fa026caedaf1f9b254ccd70cc0a3a391c8f80c7ff8', '{\"criteria\":[{\"id\":\"1\",\"kode\":\"C1\",\"nama\":\"Close Order\",\"bobot\":5,\"tipe\":\"benefit\"},{\"id\":\"2\",\"kode\":\"C2\",\"nama\":\"Product Knowledge\",\"bobot\":3,\"tipe\":\"benefit\"},{\"id\":\"3\",\"kode\":\"C3\",\"nama\":\"Kedisiplinan\",\"bobot\":4,\"tipe\":\"benefit\"},{\"id\":\"4\",\"kode\":\"C4\",\"nama\":\"Tanggung Jawab\",\"bobot\":4,\"tipe\":\"benefit\"}],\"weights\":[0.3125,0.1875,0.25,0.25],\"alternatives\":[{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"scores\":[184,95,30,80]},{\"id\":\"2\",\"kode\":\"A02\",\"nama\":\"Daeng\",\"scores\":[1,85,70,85]},{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"scores\":[1,80,50,85]},{\"id\":\"5\",\"kode\":\"A04\",\"nama\":\"Dodi\",\"scores\":[0,75,80,85]}],\"divisors\":[184.00543470234786,168.15171720800237,121.2435565298214,167.5559608011604],\"normalized\":[[0.9999704644465711,0.56496598177755,0.24743582965269678,0.477452426147563],[0.005434622089383539,0.5054958784325447,0.5773502691896258,0.5072932027817857],[0.005434622089383539,0.47576082676004205,0.4123930494211613,0.5072932027817857],[0,0.44602577508753943,0.659828879073858,0.5072932027817857]],\"weighted\":[[0.31249077013955345,0.10593112158329063,0.061858957413174195,0.11936310653689075],[0.0016983194029323559,0.09478047720610214,0.14433756729740646,0.12682330069544642],[0.0016983194029323559,0.08920515501750789,0.10309826235529032,0.12682330069544642],[0,0.08362983282891365,0.1649572197684645,0.12682330069544642]],\"idealPositive\":[0.31249077013955345,0.10593112158329063,0.1649572197684645,0.12682330069544642],\"idealNegative\":[0,0.08362983282891365,0.061858957413174195,0.11936310653689075],\"results\":[{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"d_plus\":0.10336781993233493,\"d_minus\":0.31328553892977146,\"preferensi\":0.7519093084605492,\"ranking\":1},{\"id\":\"5\",\"kode\":\"A04\",\"nama\":\"Dodi\",\"d_plus\":0.31328553892977146,\"d_minus\":0.10336781993233493,\"preferensi\":0.24809069153945076,\"ranking\":2},{\"id\":\"2\",\"kode\":\"A02\",\"nama\":\"Daeng\",\"d_plus\":0.3116752386265691,\"d_minus\":0.08357988241281288,\"preferensi\":0.21145806332129782,\"ranking\":3},{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"d_plus\":0.3173298536297517,\"d_minus\":0.04231197555412887,\"preferensi\":0.11765031795702291,\"ranking\":4}],\"incompleteAlternatives\":[],\"winner\":{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"d_plus\":0.10336781993233493,\"d_minus\":0.31328553892977146,\"preferensi\":0.7519093084605492,\"ranking\":1},\"validAlternativeCount\":4}', '2026-05-06 06:05:52', '2026-05-06 05:43:30', '2026-05-06 06:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `salesman`
--

CREATE TABLE `salesman` (
  `id` int(11) NOT NULL,
  `kode_alternatif` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salesman`
--

INSERT INTO `salesman` (`id`, `kode_alternatif`, `nama`, `gender`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'A01', 'Rahman', 'L', 'Depok', '2026-03-16 14:20:08', '2026-04-13 02:58:48'),
(2, 'A02', 'Daeng', 'L', 'Gowa', '2026-03-16 14:20:08', '2026-03-16 14:20:08'),
(3, 'A03', 'Alle', 'L', 'Maros', '2026-03-16 14:20:08', '2026-03-16 14:20:08'),
(5, 'A04', 'Dodi', 'L', 'Bekasi', '2026-04-13 02:58:40', '2026-04-13 02:58:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `google_sub` varchar(100) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `auth_provider` enum('manual','google') NOT NULL DEFAULT 'manual',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `full_name`, `google_sub`, `avatar_url`, `password_hash`, `created_at`, `updated_at`, `role`, `auth_provider`, `is_active`, `email_verified_at`, `last_login_at`) VALUES
(2, 'manajer', 'manajer@hamasa.com', NULL, NULL, NULL, '$2y$10$Sb2Dx4S64yl7z2p.CbHr4eYsgrFBlnwsePM5Vv5YkqEyp5fB1aXuq', '2026-03-16 06:10:05', '2026-05-06 07:45:42', 'manajer', 'manual', 1, NULL, '2026-05-06 07:45:42'),
(3, 'ceo', 'ceo@hamasa.com', NULL, NULL, NULL, '$2y$10$5pN8glRIEj5O.yF3CkLNLuE4yKa./jmof1FzBRlQpM5uKqJvgNwKm', '2026-03-17 14:54:57', '2026-05-04 06:00:23', 'ceo', 'manual', 1, NULL, '2026-05-04 06:00:23'),
(4, 'putri', 'yagustibismillah@gmail.com', 'putri indaryani', '109024891412211767668', 'https://lh3.googleusercontent.com/a/ACg8ocJx_qyNSmjhAu4G6to-SvrXXJb93eKNWCBHxqaLG8dRfp8bhic=s96-c', '$2y$10$QDf0IQasy2QwvtFSkf26VOfIGPtWA6pwZ7EFp0VSkLtRsp3JK61yG', '2026-04-15 11:44:59', '2026-04-15 11:52:07', 'manajer', 'manual', 1, '2026-04-15 11:52:07', '2026-04-15 11:52:07'),
(6, 'ramram', 'ram@hamasa.com', 'ramni', NULL, NULL, '$2y$10$1ye9AAaNvplG4gVeR6vA6eaCafBdRharQ7stQaCnW6Gflrl7zEKsW', '2026-05-06 03:02:23', '2026-05-06 03:02:38', 'admin', 'manual', 1, NULL, '2026-05-06 03:02:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hasil_perhitungan`
--
ALTER TABLE `hasil_perhitungan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hasil_salesman` (`salesman_id`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kriteria` (`kode_kriteria`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_penilaian_salesman` (`salesman_id`),
  ADD KEY `fk_penilaian_kriteria` (`kriteria_id`);

--
-- Indexes for table `perhitungan_snapshot`
--
ALTER TABLE `perhitungan_snapshot`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_periode` (`periode`);

--
-- Indexes for table `salesman`
--
ALTER TABLE `salesman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_alternatif` (`kode_alternatif`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD UNIQUE KEY `uq_users_google_sub` (`google_sub`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hasil_perhitungan`
--
ALTER TABLE `hasil_perhitungan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `perhitungan_snapshot`
--
ALTER TABLE `perhitungan_snapshot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `salesman`
--
ALTER TABLE `salesman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil_perhitungan`
--
ALTER TABLE `hasil_perhitungan`
  ADD CONSTRAINT `fk_hasil_salesman` FOREIGN KEY (`salesman_id`) REFERENCES `salesman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `fk_penilaian_kriteria` FOREIGN KEY (`kriteria_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penilaian_salesman` FOREIGN KEY (`salesman_id`) REFERENCES `salesman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
