-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Des 2025 pada 07.11
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
-- Database: `db_kasir_pro`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama_pelanggan`, `alamat`, `no_hp`) VALUES
(1, 'Pelanggan Umum (System)', '-', '-'),
(4, 'Pelanggan VIP+', NULL, '-'),
(5, 'Bos A', '', '6281337225542');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `kode_barang` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `harga`, `stok`, `kode_barang`) VALUES
(11, 'Marugame Udon Original', 50000.00, 83, NULL),
(12, 'Marugame Udon Mix Fish', 65000.00, 72, NULL),
(13, 'Thai tea', 15000.00, 80, NULL),
(14, 'ButterSchott', 17000.00, 83, NULL),
(15, 'Coffe Afredo', 23000.00, 87, NULL),
(16, 'Aladin Cofee ', 30000.00, 86, NULL),
(17, 'Enthauis Coffe Irland', 32000.00, 78, NULL),
(18, 'El Pacciano Gridd', 42000.00, 92, NULL),
(19, 'Avocado ', 5000.00, 85, NULL),
(20, 'Asvoral Coffe ', 33000.00, 87, NULL),
(21, 'Jelly Fish Goub', 24000.00, 84, NULL),
(22, 'Angkero Toraja Coffe', 52000.00, 79, NULL),
(23, 'Rice Feat Kaviar Elegalto', 640000.00, 87, NULL),
(24, 'Tomahawk (1,2 kg)', 2300000.00, 17, NULL),
(26, 'Wagyu A5 (500 gr)', 500000.00, 6, NULL),
(28, 'Pork German Original (250 gr)', 230000.00, 8, NULL),
(29, 'German Sheard', 8000000.00, 87, NULL),
(30, 'Safron ', 3000000.00, 83, NULL),
(31, 'pineapple Grill', 24000.00, 77, NULL),
(33, 'Tequila Xw1', 18000000.00, 81, NULL),
(34, 'Coca Cola', 450000.00, 86, NULL),
(35, 'Large Exclusive Vibe Extra Shoot (Gold Champ)', 2000000.00, 1, NULL),
(36, 'Vibe (Original)', 1200000.00, 0, NULL),
(39, 'Vica (air mineral)', 3000.00, 99, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `no_transaksi` varchar(20) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `total_bayar` decimal(10,2) DEFAULT NULL,
  `jumlah_uang` decimal(10,2) DEFAULT NULL,
  `kembalian` decimal(10,2) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT 0,
  `diskon` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `no_transaksi`, `tanggal`, `total_bayar`, `jumlah_uang`, `kembalian`, `id_pelanggan`, `diskon`) VALUES
(1, 'TRX-20251201053728', '2025-12-01 05:37:28', 244000.00, 300000.00, 56000.00, 1, 0.00),
(2, 'TRX-20251201054240', '2025-12-01 05:42:40', 146000.00, 150000.00, 4000.00, 1, 0.00),
(3, 'TRX-20251201055244', '2025-12-01 05:52:44', 4218000.00, 5000000.00, 782000.00, 4, 0.00),
(4, 'TRX-20251201065329', '2025-12-01 06:53:29', 52000.00, 70000.00, 18000.00, 1, 230000.00),
(5, 'TRX-20251201071255', '2025-12-01 07:12:55', 86000.00, 100000.00, 14000.00, 1, 20000.00),
(6, 'TRX-20251201073057', '2025-12-01 07:30:57', 0.00, 0.00, 0.00, 1, 86000.00),
(7, 'TRX-20251201073125', '2025-12-01 07:31:25', 42000.00, 200000.00, 158000.00, 1, 0.00),
(8, 'TRX-20251202025839', '2025-12-02 02:58:39', 1092000.00, 1100000.00, 8000.00, 1, 50000.00),
(9, 'TRX-20251202145247', '2025-12-02 14:52:47', 2966667.00, 3000000.00, 33333.00, 1, 33333.00),
(10, 'TRX-20251202150228', '2025-12-02 15:02:28', 69500000.00, 70000000.00, 500000.00, 4, 0.00),
(11, 'TRX-20251202151503', '2025-12-02 15:15:03', 342778.00, 355000.00, 12222.00, 1, 12222.00),
(12, 'TRX-20251202151539', '2025-12-02 15:15:39', 48000.00, 50000.00, 2000.00, 1, 0.00),
(13, 'TRX-20251202152023', '2025-12-02 15:20:23', 549000.00, 600000.00, 51000.00, 1, 0.00),
(14, 'TRX-20251202152402', '2025-12-02 15:24:02', 44113000.00, 44113000.00, 0.00, 5, 0.00),
(15, 'TRX-20251203020948', '2025-12-03 02:09:48', 900000.00, 900000.00, 0.00, 1, 0.00),
(16, 'TRX-20251203022413', '2025-12-03 02:24:13', 99999999.99, 99999999.99, 200000.00, 1, 200000.00),
(17, 'TRX-20251203024437', '2025-12-03 02:44:37', 34000.00, 100000.00, 66000.00, 1, 0.00),
(18, 'TRX-20251203025448', '2025-12-03 02:54:48', 28000.00, 50000.00, 22000.00, 1, 0.00),
(19, 'TRX-20251203043417', '2025-12-03 04:34:17', 84000.00, 100000.00, 16000.00, 1, 0.00),
(20, 'TRX-20251203044756', '2025-12-03 04:47:56', 2000000.00, 3000000.00, 1000000.00, 1, 0.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `id` int(11) NOT NULL,
  `no_transaksi` varchar(20) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id`, `no_transaksi`, `id_produk`, `qty`, `subtotal`) VALUES
(1, 'TRX-20251201053728', 18, 1, 42000.00),
(2, 'TRX-20251201053728', 17, 1, 32000.00),
(3, 'TRX-20251201053728', 13, 1, 15000.00),
(4, 'TRX-20251201053728', 14, 1, 17000.00),
(5, 'TRX-20251201053728', 12, 1, 65000.00),
(6, 'TRX-20251201053728', 11, 1, 50000.00),
(7, 'TRX-20251201053728', 15, 1, 23000.00),
(8, 'TRX-20251201054240', 16, 1, 30000.00),
(9, 'TRX-20251201054240', 17, 1, 32000.00),
(10, 'TRX-20251201054240', 18, 2, 84000.00),
(11, 'TRX-20251201055244', 24, 1, 2300000.00),
(12, 'TRX-20251201055244', 23, 1, 640000.00),
(13, 'TRX-20251201055244', 12, 1, 65000.00),
(14, 'TRX-20251201055244', 20, 1, 33000.00),
(15, 'TRX-20251201055244', 21, 1, 24000.00),
(16, 'TRX-20251201055244', 22, 3, 156000.00),
(17, 'TRX-20251201055244', 26, 2, 1000000.00),
(18, 'TRX-20251201065329', 28, 1, 230000.00),
(19, 'TRX-20251201065329', 22, 1, 52000.00),
(20, 'TRX-20251201071255', 14, 1, 17000.00),
(21, 'TRX-20251201071255', 13, 1, 15000.00),
(22, 'TRX-20251201071255', 17, 1, 32000.00),
(23, 'TRX-20251201071255', 18, 1, 42000.00),
(24, 'TRX-20251201073057', 17, 1, 32000.00),
(25, 'TRX-20251201073057', 16, 1, 30000.00),
(26, 'TRX-20251201073057', 21, 1, 24000.00),
(27, 'TRX-20251201073125', 18, 1, 42000.00),
(28, 'TRX-20251202025839', 17, 1, 32000.00),
(29, 'TRX-20251202025839', 14, 1, 17000.00),
(30, 'TRX-20251202025839', 11, 2, 100000.00),
(31, 'TRX-20251202025839', 22, 1, 52000.00),
(32, 'TRX-20251202025839', 28, 1, 230000.00),
(33, 'TRX-20251202025839', 31, 1, 24000.00),
(34, 'TRX-20251202025839', 23, 1, 640000.00),
(35, 'TRX-20251202025839', 19, 1, 5000.00),
(36, 'TRX-20251202025839', 18, 1, 42000.00),
(37, 'TRX-20251202145247', 30, 1, 3000000.00),
(38, 'TRX-20251202150228', 16, 1, 30000.00),
(39, 'TRX-20251202150228', 22, 5, 260000.00),
(40, 'TRX-20251202150228', 20, 1, 33000.00),
(41, 'TRX-20251202150228', 30, 1, 3000000.00),
(42, 'TRX-20251202150228', 33, 3, 54000000.00),
(43, 'TRX-20251202150228', 11, 1, 50000.00),
(44, 'TRX-20251202150228', 26, 1, 500000.00),
(45, 'TRX-20251202150228', 36, 1, 1200000.00),
(46, 'TRX-20251202150228', 24, 1, 2300000.00),
(47, 'TRX-20251202150228', 13, 1, 15000.00),
(48, 'TRX-20251202150228', 31, 1, 24000.00),
(49, 'TRX-20251202150228', 21, 1, 24000.00),
(50, 'TRX-20251202150228', 29, 1, 8000000.00),
(51, 'TRX-20251202150228', 17, 2, 64000.00),
(52, 'TRX-20251202151503', 31, 10, 240000.00),
(53, 'TRX-20251202151503', 11, 1, 50000.00),
(54, 'TRX-20251202151503', 12, 1, 65000.00),
(55, 'TRX-20251202151539', 21, 2, 48000.00),
(56, 'TRX-20251202152023', 14, 1, 17000.00),
(57, 'TRX-20251202152023', 34, 1, 450000.00),
(58, 'TRX-20251202152023', 17, 1, 32000.00),
(59, 'TRX-20251202152023', 11, 1, 50000.00),
(60, 'TRX-20251202152402', 15, 1, 23000.00),
(61, 'TRX-20251202152402', 34, 1, 450000.00),
(62, 'TRX-20251202152402', 29, 2, 16000000.00),
(63, 'TRX-20251202152402', 35, 1, 2000000.00),
(64, 'TRX-20251202152402', 30, 1, 3000000.00),
(65, 'TRX-20251202152402', 23, 1, 640000.00),
(66, 'TRX-20251202152402', 24, 1, 2300000.00),
(67, 'TRX-20251202152402', 36, 1, 1200000.00),
(68, 'TRX-20251202152402', 26, 1, 500000.00),
(69, 'TRX-20251202152402', 33, 1, 18000000.00),
(70, 'TRX-20251203020948', 34, 2, 900000.00),
(71, 'TRX-20251203022413', 30, 4, 12000000.00),
(72, 'TRX-20251203022413', 33, 5, 90000000.00),
(73, 'TRX-20251203022413', 13, 6, 90000.00),
(74, 'TRX-20251203022413', 17, 3, 96000.00),
(75, 'TRX-20251203022413', 14, 2, 34000.00),
(76, 'TRX-20251203022413', 35, 4, 8000000.00),
(77, 'TRX-20251203022413', 31, 1, 24000.00),
(78, 'TRX-20251203022413', 20, 1, 33000.00),
(79, 'TRX-20251203022413', 22, 1, 52000.00),
(80, 'TRX-20251203022413', 36, 1, 1200000.00),
(81, 'TRX-20251203024437', 21, 1, 24000.00),
(82, 'TRX-20251203024437', 19, 2, 10000.00),
(83, 'TRX-20251203025448', 19, 1, 5000.00),
(84, 'TRX-20251203025448', 15, 1, 23000.00),
(85, 'TRX-20251203043417', 16, 1, 30000.00),
(86, 'TRX-20251203043417', 19, 1, 5000.00),
(87, 'TRX-20251203043417', 14, 1, 17000.00),
(88, 'TRX-20251203043417', 17, 1, 32000.00),
(89, 'TRX-20251203044756', 35, 1, 2000000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`) VALUES
(1, 'admin', 'admin', 'Administrator'),
(2, 'user-pagi', 'admin123', 'Kasir Sift Pagi'),
(4, 'malam', 'admin', 'Kasir Malam'),
(5, 'andre', 'admin', 'Andre');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_pelanggan_2` (`id_pelanggan`),
  ADD KEY `no_transaksi` (`no_transaksi`);

--
-- Indeks untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `no_transaksi` (`no_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `fk_detail_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`no_transaksi`) REFERENCES `transaksi` (`no_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
