-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 22, 2025 at 02:58 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kereta`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int NOT NULL,
  `id_kereta` int DEFAULT NULL,
  `asal` varchar(255) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `tanggal_berangkat` date NOT NULL,
  `waktu_berangkat` time NOT NULL,
  `waktu_sampai` time NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `id_kereta`, `asal`, `tujuan`, `tanggal_berangkat`, `waktu_berangkat`, `waktu_sampai`, `harga`) VALUES
(1, 1, 'bogor', 'semarang', '2025-04-20', '03:30:00', '17:30:00', '400.00'),
(4, 3, 'bogor', 'surabaya', '2025-04-22', '02:01:00', '14:01:00', '500.00');

-- --------------------------------------------------------

--
-- Table structure for table `kereta`
--

CREATE TABLE `kereta` (
  `id` int NOT NULL,
  `nama_kereta` varchar(255) NOT NULL,
  `jenis` enum('vip','bisnis','ekonomi') NOT NULL,
  `kapasitas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kereta`
--

INSERT INTO `kereta` (`id`, `nama_kereta`, `jenis`, `kapasitas`) VALUES
(1, 'KAI', 'ekonomi', 100),
(3, 'KAJJ', 'bisnis', 300),
(4, 'MRT', 'vip', 500),
(5, 'Listrik', 'vip', 100);

-- --------------------------------------------------------

--
-- Table structure for table `kursi`
--

CREATE TABLE `kursi` (
  `id` int NOT NULL,
  `id_jadwal` int DEFAULT NULL,
  `nomor_kursi` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int NOT NULL,
  `id_pemesanan` int DEFAULT NULL,
  `metode_bayar` enum('transfer','credit card','e-wallet') NOT NULL,
  `status_proses` enum('berhasil','gagal','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
  `bukti_bayar` varchar(255) NOT NULL,
  `waktu_bayar` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `id_pemesanan`, `metode_bayar`, `status_proses`, `bukti_bayar`, `waktu_bayar`) VALUES
(1, 5, 'e-wallet', 'berhasil', 'uploads/1745210388_Epson_L3210_Series_EM_50_Web.exe', '2025-04-20 21:39:48'),
(2, 6, 'e-wallet', 'berhasil', 'uploads/1745210467_daftar_buku.php', '2025-04-20 21:41:07'),
(3, 9, 'credit card', 'berhasil', 'uploads/1745211425_Hitam modern poster acara webinar.png', '2025-04-20 21:57:05'),
(4, 10, 'transfer', 'berhasil', 'uploads/1745211592_Hitam modern poster acara webinar (1).png', '2025-04-20 21:59:52'),
(5, 11, 'transfer', 'berhasil', 'uploads/1745213819_WhatsApp Image 2025-03-07 at 18.50.45_66bb11dc3e.jpg', '2025-04-20 22:36:59'),
(6, 12, 'transfer', 'berhasil', 'uploads/1745214524_WhatsApp Image 2025-03-07 at 18.50.45_66bbdc3e.jpg', '2025-04-20 22:48:44'),
(7, 13, 'e-wallet', 'berhasil', '1745215204_WhatsApp Image 2025-03-07 at 18.50.45_66bb11dc3e.jpg', '2025-04-20 23:00:04'),
(8, 14, 'transfer', 'berhasil', '1745215799_Hitam modern poster acara webinar.png', '2025-04-20 23:09:59'),
(9, 15, 'e-wallet', 'pending', '1745218100_WhatsApp Image 2025-03-07 at 18.50.45_66bbdc3e.jpg', '2025-04-20 23:48:20');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_jadwal` int DEFAULT NULL,
  `jumlah_tiket` int NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL,
  `status_bayar` enum('belum bayar','sudah bayar','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `waktu_pemesanan` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `id_user`, `id_jadwal`, `jumlah_tiket`, `total_bayar`, `status_bayar`, `waktu_pemesanan`) VALUES
(5, 1, 4, 1, '500.00', 'sudah bayar', '2025-04-21 04:39:48'),
(6, 1, 1, 2, '800.00', 'sudah bayar', '2025-04-21 04:41:07'),
(9, 1, 1, 4, '1600.00', 'belum bayar', '2025-04-21 04:57:05'),
(10, 1, 4, 5, '2500.00', 'belum bayar', '2025-04-21 04:59:52'),
(11, 1, 4, 1, '500.00', 'belum bayar', '2025-04-21 05:36:59'),
(12, 1, 1, 1, '400.00', 'sudah bayar', '2025-04-21 05:48:44'),
(13, 1, 1, 1, '400.00', 'belum bayar', '2025-04-21 06:00:04'),
(14, 1, 1, 2, '800.00', 'belum bayar', '2025-04-21 06:09:59'),
(15, 3, 1, 1, '400.00', 'belum bayar', '2025-04-21 06:48:20');

-- --------------------------------------------------------

--
-- Table structure for table `penumpang`
--

CREATE TABLE `penumpang` (
  `id` int NOT NULL,
  `id_pemesanan` int DEFAULT NULL,
  `nama_penumpang` varchar(255) NOT NULL,
  `nomor_kursi` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penumpang`
--

INSERT INTO `penumpang` (`id`, `id_pemesanan`, `nama_penumpang`, `nomor_kursi`) VALUES
(1, 13, 'rehan', '2'),
(2, 14, 'ojan', '1'),
(3, 14, 'ale', '3'),
(4, 15, 'aldia', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `nama`, `password`, `role`, `created_at`) VALUES
(1, 'muf@gmail.com', 'mufti', '$2y$10$RwlSswkP53vWslEcjnFwCOtrgyzAuYMBS4THI.YntpEVaoF72bzru', 'user', '2025-04-20 12:27:24'),
(2, 'aldia@gmail.com', 'aldia', '$2y$10$yOHHCcmd/Om7Qj2BtlJGS.PW1SdaFfnSdDhWDglGexubaWBQKSisC', 'admin', '2025-04-20 14:17:25'),
(3, 'ale@gmail.com', 'aldia riski', '$2y$10$t6kHHvjc7ay9OPbJF33kUuqmmMJTTDpHVIH9Jy4qidlSP1DOVqFaq', 'user', '2025-04-21 06:47:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kereta` (`id_kereta`);

--
-- Indexes for table `kereta`
--
ALTER TABLE `kereta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kursi`
--
ALTER TABLE `kursi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `penumpang`
--
ALTER TABLE `penumpang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kereta`
--
ALTER TABLE `kereta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kursi`
--
ALTER TABLE `kursi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `penumpang`
--
ALTER TABLE `penumpang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_kereta`) REFERENCES `kereta` (`id`);

--
-- Constraints for table `kursi`
--
ALTER TABLE `kursi`
  ADD CONSTRAINT `kursi_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id`);

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id`);

--
-- Constraints for table `penumpang`
--
ALTER TABLE `penumpang`
  ADD CONSTRAINT `penumpang_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
