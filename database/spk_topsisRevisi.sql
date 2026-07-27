-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2026 at 11:10 AM
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
(56, '2026-03', 3, 0.626327, 1, 0.02022983, 0.03390794, '2026-04-15 04:11:58', '2026-04-15 04:11:58'),
(57, '2026-03', 1, 0.520099, 2, 0.03111047, 0.03371638, '2026-04-15 04:11:58', '2026-04-15 04:11:58'),
(58, '2026-03', 2, 0.381930, 3, 0.03902656, 0.02411604, '2026-04-15 04:11:58', '2026-04-15 04:11:58'),
(65, '2026-02', 1, 0.665778, 1, 0.01628273, 0.03243560, '2026-04-19 02:28:16', '2026-04-19 02:28:16'),
(66, '2026-02', 5, 0.652655, 2, 0.01737075, 0.03263936, '2026-04-19 02:28:16', '2026-04-19 02:28:16'),
(67, '2026-02', 3, 0.366037, 3, 0.03105759, 0.01793200, '2026-04-19 02:28:16', '2026-04-19 02:28:16'),
(77, '2026-04', 1, 0.983629, 1, 0.00355569, 0.21363527, '2026-04-30 13:01:27', '2026-04-30 13:01:27'),
(78, '2026-04', 2, 0.880222, 2, 0.02602132, 0.19122580, '2026-04-30 13:01:27', '2026-04-30 13:01:27'),
(79, '2026-04', 3, 0.033912, 3, 0.21374548, 0.00750308, '2026-04-30 13:01:27', '2026-04-30 13:01:27'),
(96, '2026-10', 1, 0.751909, 1, 0.10336782, 0.31328554, '2026-07-24 01:46:24', '2026-07-24 01:46:24'),
(97, '2026-10', 5, 0.248091, 2, 0.31328554, 0.10336782, '2026-07-24 01:46:24', '2026-07-24 01:46:24'),
(98, '2026-10', 2, 0.211458, 3, 0.31167524, 0.08357988, '2026-07-24 01:46:24', '2026-07-24 01:46:24'),
(99, '2026-10', 3, 0.117650, 4, 0.31732985, 0.04231198, '2026-07-24 01:46:24', '2026-07-24 01:46:24'),
(100, '2026-05', 3, 0.972582, 1, 0.00490990, 0.17416307, '2026-07-24 01:47:17', '2026-07-24 01:47:17'),
(101, '2026-05', 5, 0.027418, 2, 0.17416307, 0.00490990, '2026-07-24 01:47:17', '2026-07-24 01:47:17'),
(102, '2025-10', 1, 0.658867, 1, 0.20801257, 0.40175705, '2026-07-27 07:53:10', '2026-07-27 07:53:10'),
(103, '2025-10', 2, 0.341133, 2, 0.40175705, 0.20801257, '2026-07-27 07:53:10', '2026-07-27 07:53:10'),
(104, '2025-10', 3, 0.253811, 3, 0.40769648, 0.13867505, '2026-07-27 07:53:10', '2026-07-27 07:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `tipe` enum('benefit','cost') NOT NULL DEFAULT 'benefit',
  `bobot` decimal(5,2) DEFAULT NULL,
  `bobot_normalisasi` decimal(10,8) NOT NULL DEFAULT 0.00000000,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `kode_kriteria`, `nama_kriteria`, `tipe`, `bobot`, `bobot_normalisasi`, `created_at`, `updated_at`) VALUES
(24, 'C1', 'Close Order', 'benefit', 41.66, 0.00000000, NULL, NULL),
(25, 'C2', 'Pencapaian Kunjungan', 'benefit', 33.33, 0.00000000, NULL, NULL),
(26, 'C3', 'Jumlah Demo', 'benefit', 25.00, 0.00000000, NULL, NULL);

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
(113, '2025-10', 1, 24, 29.00, '2026-07-27 07:46:21', '2026-07-27 07:46:21', NULL),
(114, '2025-10', 1, 25, 120.00, '2026-07-27 07:46:21', '2026-07-27 07:46:21', NULL),
(115, '2025-10', 1, 26, 0.00, '2026-07-27 07:46:21', '2026-07-27 07:46:21', NULL),
(116, '2025-10', 2, 24, 1.00, '2026-07-27 07:47:28', '2026-07-27 07:47:28', NULL),
(117, '2025-10', 2, 25, 120.00, '2026-07-27 07:47:28', '2026-07-27 07:47:28', NULL),
(118, '2025-10', 2, 26, 3.00, '2026-07-27 07:47:28', '2026-07-27 07:47:28', NULL),
(119, '2025-10', 3, 24, 1.00, '2026-07-27 07:48:04', '2026-07-27 07:48:04', NULL),
(120, '2025-10', 3, 25, 120.00, '2026-07-27 07:48:04', '2026-07-27 07:48:04', NULL),
(121, '2025-10', 3, 26, 2.00, '2026-07-27 07:48:04', '2026-07-27 07:48:04', NULL);

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
(2, '2026-10', 'c1915312911a98e14169ebbb6a5f655847a9e1c756f94ea9d577d10f7a8e9295', '{\"criteria\":[{\"id\":\"3\",\"kode\":\"C3\",\"nama\":\"Kedisiplinan\",\"bobot\":4,\"tipe\":\"benefit\"},{\"id\":\"1\",\"kode\":\"C1\",\"nama\":\"Close Order\",\"bobot\":5,\"tipe\":\"benefit\"},{\"id\":\"4\",\"kode\":\"C4\",\"nama\":\"Tanggung Jawab\",\"bobot\":4,\"tipe\":\"benefit\"},{\"id\":\"2\",\"kode\":\"C2\",\"nama\":\"Product Knowledge\",\"bobot\":3,\"tipe\":\"benefit\"}],\"weights\":[0.25,0.3125,0.25,0.1875],\"alternatives\":[{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"scores\":[30,184,80,95]},{\"id\":\"2\",\"kode\":\"A02\",\"nama\":\"Daeng\",\"scores\":[70,1,85,85]},{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"scores\":[50,1,85,80]},{\"id\":\"5\",\"kode\":\"A04\",\"nama\":\"Dodi\",\"scores\":[80,0,85,75]}],\"divisors\":[121.2435565298214,184.00543470234786,167.5559608011604,168.15171720800237],\"normalized\":[[0.24743582965269678,0.9999704644465711,0.477452426147563,0.56496598177755],[0.5773502691896258,0.005434622089383539,0.5072932027817857,0.5054958784325447],[0.4123930494211613,0.005434622089383539,0.5072932027817857,0.47576082676004205],[0.659828879073858,0,0.5072932027817857,0.44602577508753943]],\"weighted\":[[0.061858957413174195,0.31249077013955345,0.11936310653689075,0.10593112158329063],[0.14433756729740646,0.0016983194029323559,0.12682330069544642,0.09478047720610214],[0.10309826235529032,0.0016983194029323559,0.12682330069544642,0.08920515501750789],[0.1649572197684645,0,0.12682330069544642,0.08362983282891365]],\"idealPositive\":[0.1649572197684645,0.31249077013955345,0.12682330069544642,0.10593112158329063],\"idealNegative\":[0.061858957413174195,0,0.11936310653689075,0.08362983282891365],\"results\":[{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"d_plus\":0.10336781993233493,\"d_minus\":0.31328553892977146,\"preferensi\":0.7519093084605492,\"ranking\":1},{\"id\":\"5\",\"kode\":\"A04\",\"nama\":\"Dodi\",\"d_plus\":0.31328553892977146,\"d_minus\":0.10336781993233493,\"preferensi\":0.24809069153945076,\"ranking\":2},{\"id\":\"2\",\"kode\":\"A02\",\"nama\":\"Daeng\",\"d_plus\":0.3116752386265691,\"d_minus\":0.08357988241281288,\"preferensi\":0.21145806332129782,\"ranking\":3},{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"d_plus\":0.3173298536297517,\"d_minus\":0.04231197555412887,\"preferensi\":0.11765031795702291,\"ranking\":4}],\"incompleteAlternatives\":[],\"winner\":{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"d_plus\":0.10336781993233493,\"d_minus\":0.31328553892977146,\"preferensi\":0.7519093084605492,\"ranking\":1},\"validAlternativeCount\":4}', '2026-07-24 01:46:24', '2026-05-06 05:43:30', '2026-07-24 01:46:24'),
(3, '2026-05', '4269840f62d6507d6fe535d0dce79e31eb92926cab6f1db4b25576283b4eba58', '{\"criteria\":[{\"id\":\"3\",\"kode\":\"C3\",\"nama\":\"Kedisiplinan\",\"bobot\":4,\"tipe\":\"benefit\"},{\"id\":\"1\",\"kode\":\"C1\",\"nama\":\"Close Order\",\"bobot\":5,\"tipe\":\"benefit\"},{\"id\":\"4\",\"kode\":\"C4\",\"nama\":\"Tanggung Jawab\",\"bobot\":4,\"tipe\":\"benefit\"},{\"id\":\"2\",\"kode\":\"C2\",\"nama\":\"Product Knowledge\",\"bobot\":3,\"tipe\":\"benefit\"}],\"weights\":[0.25,0.3125,0.25,0.1875],\"alternatives\":[{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"scores\":[87,5,78,80]},{\"id\":\"5\",\"kode\":\"A04\",\"nama\":\"Dodi\",\"scores\":[88,2,80,77]}],\"divisors\":[123.7457069962429,5.385164807134504,111.73182178770737,111.03603018840326],\"normalized\":[[0.703054692658077,0.9284766908852594,0.6981001361295398,0.7204868533597422],[0.7111357810794342,0.3713906763541037,0.7160001396200408,0.6934685963587518]],\"weighted\":[[0.17576367316451924,0.2901489659016436,0.17452503403238495,0.13509128500495166],[0.17778394526985855,0.11605958636065741,0.1790000349050102,0.13002536181726596]],\"idealPositive\":[0.17778394526985855,0.2901489659016436,0.1790000349050102,0.13509128500495166],\"idealNegative\":[0.17576367316451924,0.11605958636065741,0.17452503403238495,0.13002536181726596],\"results\":[{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"d_plus\":0.004909901443981227,\"d_minus\":0.17416307199492395,\"preferensi\":0.9725815607476002,\"ranking\":1},{\"id\":\"5\",\"kode\":\"A04\",\"nama\":\"Dodi\",\"d_plus\":0.17416307199492395,\"d_minus\":0.004909901443981227,\"preferensi\":0.027418439252399812,\"ranking\":2}],\"incompleteAlternatives\":[],\"winner\":{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"d_plus\":0.004909901443981227,\"d_minus\":0.17416307199492395,\"preferensi\":0.9725815607476002,\"ranking\":1},\"validAlternativeCount\":2}', '2026-07-24 01:47:17', '2026-07-24 01:47:17', '2026-07-24 01:47:17'),
(4, '2025-10', 'd84f3baac12a34e99e6f845dd8914f51513ac42c27dbf517be490d47409fe0ac', '{\"criteria\":[{\"id\":\"24\",\"kode\":\"C1\",\"nama\":\"Close Order\",\"bobot\":41.66,\"tipe\":\"benefit\"},{\"id\":\"25\",\"kode\":\"C2\",\"nama\":\"Pencapaian Kunjungan\",\"bobot\":33.33,\"tipe\":\"benefit\"},{\"id\":\"26\",\"kode\":\"C3\",\"nama\":\"Jumlah Demo\",\"bobot\":25,\"tipe\":\"benefit\"}],\"weights\":[0.41659999999999997,0.3333,0.25],\"alternatives\":[{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"scores\":[29,120,0]},{\"id\":\"2\",\"kode\":\"A02\",\"nama\":\"Daeng\",\"scores\":[1,120,3]},{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"scores\":[1,120,2]}],\"divisors\":[29.03446228191595,207.84609690826528,3.605551275463989],\"normalized\":[[0.9988130559615215,0.5773502691896257,0],[0.034441829515914534,0.5773502691896257,0.8320502943378437],[0.034441829515914534,0.5773502691896257,0.5547001962252291]],\"weighted\":[[0.4161055191135698,0.19243084472090224,0],[0.014348466176329993,0.19243084472090224,0.20801257358446093],[0.014348466176329993,0.19243084472090224,0.1386750490563073]],\"idealPositive\":[0.4161055191135698,0.19243084472090224,0.20801257358446093],\"idealNegative\":[0.014348466176329993,0.19243084472090224,0],\"results\":[{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"d_plus\":0.20801257358446093,\"d_minus\":0.4017570529372398,\"preferensi\":0.6588669482095659,\"ranking\":1},{\"id\":\"2\",\"kode\":\"A02\",\"nama\":\"Daeng\",\"d_plus\":0.4017570529372398,\"d_minus\":0.20801257358446093,\"preferensi\":0.34113305179043396,\"ranking\":2},{\"id\":\"3\",\"kode\":\"A03\",\"nama\":\"Alle\",\"d_plus\":0.4076964825608732,\"d_minus\":0.1386750490563073,\"preferensi\":0.2538108979540886,\"ranking\":3}],\"incompleteAlternatives\":[],\"winner\":{\"id\":\"1\",\"kode\":\"A01\",\"nama\":\"Rahman\",\"d_plus\":0.20801257358446093,\"d_minus\":0.4017570529372398,\"preferensi\":0.6588669482095659,\"ranking\":1},\"validAlternativeCount\":3}', '2026-07-27 07:53:10', '2026-07-27 07:53:10', '2026-07-27 07:53:10');

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
(1, 'A01', 'Rahman', 'L', 'Bekasi', '2026-03-16 14:20:08', '2026-07-22 09:25:30'),
(2, 'A02', 'Daeng', 'L', 'depok', '2026-03-16 14:20:08', '2026-05-11 04:58:43'),
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
(2, 'manajer', 'manajer@hamasa.com', NULL, NULL, NULL, '$2y$10$Sb2Dx4S64yl7z2p.CbHr4eYsgrFBlnwsePM5Vv5YkqEyp5fB1aXuq', '2026-03-16 06:10:05', '2026-07-27 06:09:22', 'manajer', 'manual', 1, NULL, '2026-07-27 06:09:22'),
(3, 'ceo', 'ceo@hamasa.com', NULL, NULL, NULL, '$2y$10$5pN8glRIEj5O.yF3CkLNLuE4yKa./jmof1FzBRlQpM5uKqJvgNwKm', '2026-03-17 14:54:57', '2026-05-04 06:00:23', 'ceo', 'manual', 1, NULL, '2026-05-04 06:00:23'),
(6, 'ramram', 'ram@hamasa.com', 'ramni', NULL, NULL, '$2y$10$1ye9AAaNvplG4gVeR6vA6eaCafBdRharQ7stQaCnW6Gflrl7zEKsW', '2026-05-06 03:02:23', '2026-05-06 03:02:38', 'admin', 'manual', 1, NULL, '2026-05-06 03:02:38'),
(7, 'yaiya', 'yaiya@gmail.com', '', NULL, NULL, '$2y$10$ujJSeLMRcMdDfnj4CjUiCuGGxS4gcQ6jKGDuxu.3Oe.iPLpeJbHri', '2026-05-12 10:05:59', '2026-05-12 10:05:59', 'admin', 'manual', 1, NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `perhitungan_snapshot`
--
ALTER TABLE `perhitungan_snapshot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salesman`
--
ALTER TABLE `salesman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
