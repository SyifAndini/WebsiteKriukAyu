-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2025 at 04:17 PM
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
-- Database: `db_kriukayu`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` char(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Super Admin') DEFAULT NULL,
  `foto_profil` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `email`, `password`, `role`, `foto_profil`, `created_at`) VALUES
('A0001', 'Andini', 'andini@mail.com', 'abc5dasar', 'Super Admin', 'uploads/syifa.jpg', '2025-06-21 14:04:13'),
('A0002', 'Putri', 'putri@mail.com', 'putriawan123', 'Admin', 'uploads/syifa.jpg', '2025-06-21 15:01:42');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `id_pembeli` char(5) DEFAULT NULL,
  `id_kriuk` char(5) NOT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id_cart`, `id_pembeli`, `id_kriuk`, `jumlah`) VALUES
(54, 'P0003', 'OTK01', 2);

-- --------------------------------------------------------

--
-- Table structure for table `item_pesanan`
--

CREATE TABLE `item_pesanan` (
  `id_item` int(11) NOT NULL,
  `no_pesanan` char(12) NOT NULL,
  `id_kriuk` char(5) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_akhir` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_pesanan`
--

INSERT INTO `item_pesanan` (`id_item`, `no_pesanan`, `id_kriuk`, `jumlah`, `harga_akhir`) VALUES
(7, 'TR2025062203', 'OTK02', 2, 14000),
(18, 'TR2025062286', 'MAK01', 2, 14000),
(19, 'TR2025062701', 'SBK03', 2, 14000),
(20, 'TR2025062701', 'MAK02', 4, 28000);

-- --------------------------------------------------------

--
-- Table structure for table `pembeli`
--

CREATE TABLE `pembeli` (
  `id_pembeli` char(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembeli`
--

INSERT INTO `pembeli` (`id_pembeli`, `nama`, `email`, `password`, `foto_profil`, `alamat`, `no_telp`, `created_at`) VALUES
('P0002', 'Dylan Wong', 'dylanwong@mail.com', '$2y$10$/20I71duM4z0W6L.r/0mj.JMKAKSN3jczbzcz4Xy6.kgeZpIMxoza', 'uploads/profile_20250627_685e47313331e.jpg', 'Dong Sheng Apartment, Panyu District, Guangzhou City, Guangdong Province, 337592', '02134789500', '2025-06-27 14:15:33'),
('P0003', 'Dennis Beban', 'dennis@mail.com', '$2y$10$K3c9aERW.K.6m1D1UTztouTHsrP3BaoNKsCiklZq9ncztHH8.Pu6K', 'uploads/profile_20250627_685e69b0dfb83.jpg', 'Gang Belong No. 17', '0217653809', '2025-06-27 14:40:30'),
('P0004', 'Taylor Swiftie', 'swiftie@mail.com', '$2y$10$JFKEA1pPkiw5BVE/6X/SCeJZjRVW0TpKWUqjxf97BFLAejZqW4yYq', 'uploads/profile_20250622_6857c7ea8f632.jpg', 'Cornelia Street, No. 2, New York City', '02357896015', '2025-06-22 11:37:50'),
('P6754', 'Adit Saputra', 'aditskuy@mail.com', '$2y$10$W8NrU6.xBx4m2eAGfFS.SOlrX0d.TbtsXkgms4FriJ2/wXQi5FhtG', 'uploads/profile_20250627_685e68f3d2311.jpg', 'Jl. Kurma Raya No. 13', '02134576890', '2025-06-27 16:48:35');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `no_pesanan` char(12) NOT NULL,
  `id_pembeli` char(5) DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `subtotal_produk` int(11) DEFAULT NULL,
  `biaya_pengiriman` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `metode_pembayaran` varchar(30) NOT NULL,
  `bukti_bayar` varchar(255) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`no_pesanan`, `id_pembeli`, `tanggal`, `subtotal_produk`, `biaya_pengiriman`, `total`, `metode_pembayaran`, `bukti_bayar`, `status`) VALUES
('TR2025062203', 'P0004', '2025-06-22 22:00:14', 14000, 10000, 24000, 'Cash On Delivery (COD)', '', 'Sedang Diproses'),
('TR2025062286', 'P0004', '2025-06-22 22:56:59', 14000, 10000, 24000, 'E-Wallet', 'uploads/buktiBayar_20250622_685827cb16399.jpg', 'Menunggu Konfirmasi'),
('TR2025062701', 'P0002', '2025-06-27 17:06:40', 42000, 10000, 52000, 'Transfer Bank', 'uploads/buktiBayar_20250627_685e6d30d73f5.jpg', 'Pesanan Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_kriuk` char(5) NOT NULL,
  `jenis_kriuk` varchar(30) NOT NULL,
  `rasa_kriuk` varchar(30) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_kriuk`, `jenis_kriuk`, `rasa_kriuk`, `harga`) VALUES
('EJA01', 'Emping Jagung', 'Jagung Bakar', 7000),
('EJA02', 'Emping Jagung', 'Pedas Manis', 7000),
('EJA03', 'Emping Jagung', 'Ekstra Pedas', 7000),
('MAK01', 'Makaroni', 'Jagung Bakar', 7000),
('MAK02', 'Makaroni', 'Pedas Manis', 7000),
('MAK03', 'Makaroni', 'Ekstra Pedas', 7000),
('OTK01', 'Otak-Otak', 'Jagung Bakar', 7000),
('OTK02', 'Otak-Otak', 'Pedas Manis', 7000),
('OTK03', 'Otak-Otak', 'Ekstra Pedas', 7000),
('SBK01', 'Kerupuk Seblak', 'Jagung Bakar', 7000),
('SBK02', 'Kerupuk Seblak', 'Pedas Manis', 7000),
('SBK03', 'Kerupuk Seblak', 'Ekstra Pedas', 7000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`);

--
-- Indexes for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `fk_item` (`no_pesanan`),
  ADD KEY `fk_produk` (`id_kriuk`);

--
-- Indexes for table `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`id_pembeli`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`no_pesanan`),
  ADD KEY `fk_pembeli` (`id_pembeli`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_kriuk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  ADD CONSTRAINT `fk_item` FOREIGN KEY (`no_pesanan`) REFERENCES `pesanan` (`no_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produk` FOREIGN KEY (`id_kriuk`) REFERENCES `produk` (`id_kriuk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pembeli` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
