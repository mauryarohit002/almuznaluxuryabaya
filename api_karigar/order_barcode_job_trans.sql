-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2024 at 01:20 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zoop`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_barcode_job_trans`
--

CREATE TABLE `order_barcode_job_trans` (
  `objt_id` int(11) NOT NULL,
  `objt_obt_id` int(11) NOT NULL,
  `objt_oobbt_id` int(11) NOT NULL,
  `objt_proces_id` int(11) NOT NULL,
  `objt_karigar_id` int(11) NOT NULL,
  `objt_status` tinyint(4) NOT NULL,
  `objt_delete_status` tinyint(4) NOT NULL,
  `objt_created_at` datetime NOT NULL,
  `objt_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_barcode_job_trans`
--
ALTER TABLE `order_barcode_job_trans`
  ADD PRIMARY KEY (`objt_id`),
  ADD KEY `objt_obt_id` (`objt_obt_id`),
  ADD KEY `objt_proces_id` (`objt_proces_id`),
  ADD KEY `objt_karigar_id` (`objt_karigar_id`),
  ADD KEY `objt_delete_status` (`objt_delete_status`),
  ADD KEY `objt_status` (`objt_status`),
  ADD KEY `objt_oobbt_id` (`objt_oobbt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_barcode_job_trans`
--
ALTER TABLE `order_barcode_job_trans`
  MODIFY `objt_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
