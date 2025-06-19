-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Jun 2025 pada 14.53
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_pembeli` char(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Super Admin') DEFAULT NULL,
  `foto_profil` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `id_pembeli` char(5) DEFAULT NULL,
  `id_kriuk` char(5) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cart`
--

INSERT INTO `cart` (`id_cart`, `id_pembeli`, `id_kriuk`, `jumlah`) VALUES
(10, 'P0002', 'OTK02', 2),
(12, 'P0002', 'OTK03', 2),
(14, 'P0002', 'EJA03', 2),
(16, 'P0002', 'EJA01', 2),
(18, 'P0002', 'MAK01', 2),
(23, 'P0002', 'SBK03', 3),
(25, 'P0001', 'OTK01', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `item_pesanan`
--

CREATE TABLE `item_pesanan` (
  `id_item` int(11) NOT NULL,
  `no_pesanan` char(5) DEFAULT NULL,
  `id_kriuk` char(5) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga_akhir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembeli`
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
-- Dumping data untuk tabel `pembeli`
--

INSERT INTO `pembeli` (`id_pembeli`, `nama`, `email`, `password`, `foto_profil`, `alamat`, `no_telp`, `created_at`) VALUES
('P0001', 'Andini', 'andini@mail.com', '$2y$10$KPwqi428lIt3ixgbIgQ/PO3.nSFE9cCewkwYHeSXGlt4j4it11NBS', '', '', '', '2025-06-18 19:46:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `no_pesanan` char(5) NOT NULL,
  `id_pembeli` char(5) DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT curdate(),
  `subtotal_produk` int(11) DEFAULT NULL,
  `biaya_pengiriman` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_kriuk` varchar(5) NOT NULL,
  `jenis_kriuk` varchar(30) NOT NULL,
  `rasa_kriuk` varchar(30) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
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
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_pembeli`);

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD UNIQUE KEY `id_kriuk` (`id_kriuk`);

--
-- Indeks untuk tabel `item_pesanan`
--
ALTER TABLE `item_pesanan`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `no_pesanan` (`no_pesanan`),
  ADD KEY `id_kriuk` (`id_kriuk`);

--
-- Indeks untuk tabel `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`id_pembeli`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`no_pesanan`),
  ADD KEY `id_pembeli` (`id_pembeli`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_kriuk`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `item_pesanan`
--
ALTER TABLE `item_pesanan`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `item_pesanan`
--
ALTER TABLE `item_pesanan`
  ADD CONSTRAINT `item_pesanan_ibfk_1` FOREIGN KEY (`no_pesanan`) REFERENCES `pesanan` (`no_pesanan`),
  ADD CONSTRAINT `item_pesanan_ibfk_2` FOREIGN KEY (`id_kriuk`) REFERENCES `produk` (`id_kriuk`);

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
