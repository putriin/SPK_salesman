-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 05:18 AM
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
(53, '2026-04', 1, 0.983629, 1, 0.00355569, 0.21363527, '2026-04-14 04:10:23', '2026-04-14 04:10:23'),
(54, '2026-04', 2, 0.880222, 2, 0.02602132, 0.19122580, '2026-04-14 04:10:23', '2026-04-14 04:10:23'),
(55, '2026-04', 3, 0.033912, 3, 0.21374548, 0.00750308, '2026-04-14 04:10:23', '2026-04-14 04:10:23'),
(56, '2026-03', 3, 0.626327, 1, 0.02022983, 0.03390794, '2026-04-15 04:11:58', '2026-04-15 04:11:58'),
(57, '2026-03', 1, 0.520099, 2, 0.03111047, 0.03371638, '2026-04-15 04:11:58', '2026-04-15 04:11:58'),
(58, '2026-03', 2, 0.381930, 3, 0.03902656, 0.02411604, '2026-04-15 04:11:58', '2026-04-15 04:11:58');

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
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `kode_kriteria`, `nama_kriteria`, `tipe`, `bobot`, `created_at`, `updated_at`) VALUES
(1, 'C1', 'Close Order', 'benefit', 0.300000000, '2026-03-16 14:21:53', '2026-04-02 04:42:56'),
(2, 'C2', 'Product Knowledge', 'benefit', 0.232887189, '2026-03-16 14:21:53', '2026-04-02 04:43:37'),
(3, 'C3', 'Kedisiplinan', 'benefit', 0.226195029, '2026-03-16 14:21:53', '2026-04-02 04:44:04'),
(4, 'C4', 'Tanggung Jawab', 'benefit', 0.240917782, '2026-03-16 14:21:53', '2026-04-02 04:44:21');

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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id`, `periode`, `salesman_id`, `kriteria_id`, `nilai`, `created_at`, `updated_at`) VALUES
(1, '2026-04', 1, 1, 90.00, '2026-03-16 14:26:10', '2026-03-16 14:26:10'),
(2, '2026-04', 1, 2, 85.00, '2026-03-16 14:26:10', '2026-03-16 14:26:10'),
(3, '2026-04', 1, 3, 88.00, '2026-03-16 14:26:10', '2026-03-16 14:26:10'),
(4, '2026-04', 1, 4, 92.00, '2026-03-16 14:26:10', '2026-03-16 14:26:10'),
(13, '2026-04', 2, 1, 81.00, '2026-03-17 14:10:04', '2026-03-17 14:10:04'),
(14, '2026-04', 2, 2, 87.00, '2026-03-17 14:10:04', '2026-03-17 14:10:04'),
(15, '2026-04', 2, 3, 84.00, '2026-03-17 14:10:04', '2026-03-17 14:10:04'),
(16, '2026-04', 2, 4, 85.00, '2026-03-17 14:10:04', '2026-03-17 14:10:04'),
(17, '2026-03', 2, 1, 90.00, '2026-04-02 03:04:01', '2026-04-02 03:04:01'),
(18, '2026-03', 2, 2, 70.00, '2026-04-02 03:04:01', '2026-04-02 03:04:01'),
(19, '2026-03', 2, 3, 80.00, '2026-04-02 03:04:01', '2026-04-02 03:04:01'),
(20, '2026-03', 2, 4, 80.00, '2026-04-02 03:04:01', '2026-04-02 03:04:01'),
(21, '2026-03', 1, 1, 78.00, '2026-04-02 03:09:28', '2026-04-02 03:09:28'),
(22, '2026-03', 1, 2, 90.00, '2026-04-02 03:09:28', '2026-04-02 03:09:28'),
(23, '2026-03', 1, 3, 80.00, '2026-04-02 03:09:28', '2026-04-02 03:09:28'),
(24, '2026-03', 1, 4, 80.00, '2026-04-02 03:09:28', '2026-04-02 03:09:28'),
(25, '2025-10', 1, 1, 33.00, '2026-04-02 04:53:10', '2026-04-02 04:53:10'),
(26, '2025-10', 1, 2, 95.00, '2026-04-02 04:53:10', '2026-04-02 04:53:10'),
(27, '2025-10', 1, 3, 80.00, '2026-04-02 04:53:10', '2026-04-02 04:53:10'),
(28, '2025-10', 1, 4, 90.00, '2026-04-02 04:53:10', '2026-04-02 04:53:10'),
(29, '2025-10', 3, 1, 1.00, '2026-04-02 06:30:03', '2026-04-02 06:30:03'),
(30, '2025-10', 3, 2, 79.00, '2026-04-02 06:30:03', '2026-04-02 06:30:03'),
(31, '2025-10', 3, 3, 89.00, '2026-04-02 06:30:03', '2026-04-02 06:30:03'),
(32, '2025-10', 3, 4, 85.00, '2026-04-02 06:30:03', '2026-04-02 06:30:03'),
(37, '2026-04', 3, 1, 4.00, '2026-04-13 02:59:50', '2026-04-13 02:59:50'),
(38, '2026-04', 3, 2, 78.00, '2026-04-13 02:59:50', '2026-04-13 02:59:50'),
(39, '2026-04', 3, 3, 89.00, '2026-04-13 02:59:50', '2026-04-13 02:59:50'),
(40, '2026-04', 3, 4, 85.00, '2026-04-13 02:59:50', '2026-04-13 02:59:50'),
(41, '2026-03', 3, 1, 90.00, '2026-04-15 04:11:41', '2026-04-15 04:11:41'),
(42, '2026-03', 3, 2, 78.00, '2026-04-15 04:11:41', '2026-04-15 04:11:41'),
(43, '2026-03', 3, 3, 88.00, '2026-04-15 04:11:41', '2026-04-15 04:11:41'),
(44, '2026-03', 3, 4, 89.00, '2026-04-15 04:11:41', '2026-04-15 04:11:41');

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
(1, 'admin', 'admin@hamasa.com', NULL, NULL, NULL, '$2y$10$JV0w2P0uMwfwVVDqVQlvs.RO1nqnc01qr7iYscvaZRAWK57XGMdFu', NULL, '2026-04-15 11:44:14', 'admin', 'manual', 1, NULL, '2026-04-15 11:44:14'),
(2, 'manajer', 'manajer@hamasa.com', NULL, NULL, NULL, '$2y$10$rWJtOj7h2SBmzYcAUbm3C.qyLhD/.BUWwSKz0lD7mGzn9KBb48G9K', '2026-03-16 06:10:05', '2026-03-16 06:10:05', 'manajer', 'manual', 1, NULL, NULL),
(3, 'ceo', 'ceo@hamasa.com', NULL, NULL, NULL, '$2y$10$5pN8glRIEj5O.yF3CkLNLuE4yKa./jmof1FzBRlQpM5uKqJvgNwKm', '2026-03-17 14:54:57', '2026-03-17 14:54:57', 'ceo', 'manual', 1, NULL, NULL),
(4, 'putri', 'yagustibismillah@gmail.com', 'putri indaryani', '109024891412211767668', 'https://lh3.googleusercontent.com/a/ACg8ocJx_qyNSmjhAu4G6to-SvrXXJb93eKNWCBHxqaLG8dRfp8bhic=s96-c', '$2y$10$QDf0IQasy2QwvtFSkf26VOfIGPtWA6pwZ7EFp0VSkLtRsp3JK61yG', '2026-04-15 11:44:59', '2026-04-15 11:52:07', 'manajer', 'manual', 1, '2026-04-15 11:52:07', '2026-04-15 11:52:07');

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
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_penilaian_salesman` (`salesman_id`),
  ADD KEY `fk_penilaian_kriteria` (`kriteria_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `salesman`
--
ALTER TABLE `salesman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
