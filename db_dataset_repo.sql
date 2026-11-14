-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2026 at 10:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_dataset_repo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$e.PqK6E0wU0jQY4z7O5yO.xZ9oX4Z4yQY4z7O5yO.xZ9oX4Z4yQY');

-- --------------------------------------------------------

--
-- Table structure for table `datasets`
--

CREATE TABLE `datasets` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `file_format` enum('CSV','SQL','CSV & SQL') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` varchar(50) NOT NULL,
  `row_count` int(11) DEFAULT 0,
  `downloads` int(11) DEFAULT 0,
  `rating_sum` int(11) DEFAULT 0,
  `rating_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `datasets`
--

INSERT INTO `datasets` (`id`, `title`, `description`, `category`, `file_format`, `file_path`, `file_size`, `row_count`, `downloads`, `rating_sum`, `rating_count`, `created_at`) VALUES
(4, 'Breast Cancer Wisconsin Diagnostic Original', 'Data diagnosa fisiologis kanker payudara', 'Kesehatan', 'CSV', '1793198627_breast_cancer_wisconsin_diagnostic.csv', '122.27 KB', 569, 0, 0, 0, '2026-10-28 14:43:47'),
(5, 'Breast Cancer Wisconsin Diagnostic Large', 'Data diagnosa fisiologis kanker payudara', 'Kesehatan', 'CSV', '1793199415_breast_cancer_wisconsin_diagnostic_large.csv', '86.17 KB', 400, 0, 0, 0, '2026-10-28 14:56:55'),
(6, 'Breast Cancer Wisconsin Diagnostic Medium', 'Data diagnosa fisiologis kanker payudara', 'Kesehatan', 'CSV', '1793199508_breast_cancer_wisconsin_diagnostic_medium.csv', '53.91 KB', 250, 0, 0, 0, '2026-10-28 14:58:28'),
(7, 'Breast Cancer Wisconsin Diagnostic Tiny', 'Data diagnosa fisiologis kanker payudara', 'Kesehatan', 'CSV', '1793199590_breast_cancer_wisconsin_diagnostic_tiny.csv', '21.92 KB', 100, 1, 0, 0, '2026-10-28 14:59:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `datasets`
--
ALTER TABLE `datasets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `datasets`
--
ALTER TABLE `datasets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
