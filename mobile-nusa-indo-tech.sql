-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 24 Nov 2026 pada 13.14
-- Versi server: 8.0.30
-- Versi PHP: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `mobile-nusa-indo-tech`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_statistik`
--

CREATE TABLE `admin_statistik` (
  `id_admin` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admin_statistik`
--

INSERT INTO `admin_statistik` (`id_admin`, `id_user`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_klien`
--

CREATE TABLE `data_klien` (
  `id_data_klien` bigint UNSIGNED NOT NULL,
  `id_klien` bigint UNSIGNED NOT NULL,
  `tanggal_input` date NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_persetujuan`
--

CREATE TABLE `detail_persetujuan` (
  `id_detail` bigint UNSIGNED NOT NULL,
  `id_review` bigint UNSIGNED NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `id_user`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `klien`
--

CREATE TABLE `klien` (
  `id_klien` bigint UNSIGNED NOT NULL,
  `id_konselor` bigint UNSIGNED DEFAULT NULL,
  `nama_klien` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sumber` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mobile',
  `id_web` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `klien`
--

INSERT INTO `klien` (`id_klien`, `id_konselor`, `nama_klien`, `kontak`, `email`, `alamat`, `status`, `created_at`, `sumber`, `id_web`) VALUES
(2, NULL, 'PT. EPEP', '08517777777', 'nauvaleyeks@gmail.com', 'Lwg', 'aktif', '2026-11-02 08:07:52', 'web', '6'),
(3, NULL, 'mey', '08976543557', 'mey@gmail.com', NULL, 'aktif', '2026-11-10 03:05:41', 'web', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konselor`
--

CREATE TABLE `konselor` (
  `id_konselor` bigint UNSIGNED NOT NULL,
  `id_admin` bigint UNSIGNED NOT NULL,
  `nama_konselor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` bigint UNSIGNED NOT NULL,
  `id_admin` bigint UNSIGNED NOT NULL,
  `nama_layanan` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id_login` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `waktu_login` datetime DEFAULT NULL,
  `waktu_logout` datetime DEFAULT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`id_login`, `id_user`, `waktu_login`, `waktu_logout`, `status`) VALUES
(1, 1, '2026-11-02 09:25:55', NULL, 'berhasil'),
(2, 1, '2026-11-02 09:26:49', '2026-11-02 09:27:53', 'logout'),
(3, 3, '2026-11-02 09:28:02', '2026-11-02 09:28:36', 'logout'),
(4, 2, '2026-11-02 09:28:49', '2026-11-02 09:35:49', 'logout'),
(5, 1, '2026-11-02 09:42:46', '2026-11-02 09:43:42', 'logout'),
(6, 2, '2026-11-02 09:44:23', NULL, 'berhasil'),
(7, 1, '2026-11-03 08:32:39', '2026-11-03 08:33:34', 'logout'),
(8, 3, '2026-11-03 08:33:45', '2026-11-03 08:34:15', 'logout'),
(9, 1, '2026-11-03 08:34:21', '2026-11-03 08:34:46', 'logout'),
(10, 3, '2026-11-03 08:34:57', '2026-11-03 08:35:30', 'logout'),
(11, 2, '2026-11-03 08:35:37', NULL, 'berhasil'),
(12, 1, '2026-11-03 20:49:18', '2026-11-03 20:49:34', 'logout'),
(13, 3, '2026-11-03 20:49:44', NULL, 'berhasil'),
(14, 1, '2026-11-07 18:50:01', NULL, 'berhasil'),
(15, 1, '2026-11-07 18:50:03', NULL, 'berhasil'),
(16, 1, '2026-11-07 18:54:52', NULL, 'berhasil'),
(17, 1, '2026-11-08 17:48:15', NULL, 'berhasil'),
(18, 1, '2026-11-10 10:21:37', NULL, 'berhasil');

-- --------------------------------------------------------

--
-- Struktur dari tabel `progres_tugas`
--

CREATE TABLE `progres_tugas` (
  `id_progres` bigint UNSIGNED NOT NULL,
  `id_tugas` bigint UNSIGNED NOT NULL,
  `persen` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `review_persetujuan`
--

CREATE TABLE `review_persetujuan` (
  `id_review` bigint UNSIGNED NOT NULL,
  `id_tugas` bigint UNSIGNED NOT NULL,
  `id_supervisor` bigint UNSIGNED NOT NULL,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tanggal_review` datetime DEFAULT NULL,
  `tanggal_persetujuan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id_role` bigint UNSIGNED NOT NULL,
  `nama_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id_role`, `nama_role`, `deskripsi`) VALUES
(1, 'super_admin', 'Mengelola sistem dan hak akses'),
(2, 'admin_statistik', 'Mengelola data layanan dan statistik'),
(3, 'karyawan', 'Menerima dan mengerjakan tugas'),
(4, 'supervisor', 'Melakukan review dan persetujuan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supervisor`
--

CREATE TABLE `supervisor` (
  `id_supervisor` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `supervisor`
--

INSERT INTO `supervisor` (`id_supervisor`, `id_user`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` bigint UNSIGNED NOT NULL,
  `id_karyawan` bigint UNSIGNED NOT NULL,
  `id_klien` bigint UNSIGNED NOT NULL,
  `judul_tugas` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `persen` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `id_karyawan`, `id_klien`, `judul_tugas`, `deskripsi`, `status`, `persen`, `tanggal_mulai`, `tanggal_selesai`, `created_at`) VALUES
(1, 1, 2, 'harus ini', 'ini itu', 'diajukan', 0, '2026-11-03', '2026-11-03', '2026-11-03 01:34:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` bigint UNSIGNED NOT NULL,
  `id_role` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `id_role`, `nama`, `username`, `password`, `status`, `created_at`) VALUES
(1, 2, 'Admin Statistik', 'admin', '$2y$10$fTLAzSjmPA9X/EUu2cIj4efshj0EWRP0fn7O45JwEkP7lbYpxJU5C', 'aktif', '2026-11-02 02:25:22'),
(2, 3, 'Karyawan Satu', 'karyawan1', '$2y$10$Mh4nIdDibanXHnvK8GiJ2uxJjK38iXWihVonQo0SbqH87b/m/a/cO', 'aktif', '2026-11-02 02:25:22'),
(3, 4, 'Supervisor Satu', 'supervisor1', '$2y$10$bs.b8hkOoh1QTYk0uSrjJeplBUo6IPZU6d1nvGD8jf7tnzhWF4J76', 'aktif', '2026-11-02 02:25:22');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `admin_statistik`
--
ALTER TABLE `admin_statistik`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `data_klien`
--
ALTER TABLE `data_klien`
  ADD PRIMARY KEY (`id_data_klien`),
  ADD KEY `fk_data_klien` (`id_klien`);

--
-- Indeks untuk tabel `detail_persetujuan`
--
ALTER TABLE `detail_persetujuan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_review` (`id_review`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `klien`
--
ALTER TABLE `klien`
  ADD PRIMARY KEY (`id_klien`),
  ADD UNIQUE KEY `uq_klien_web` (`id_web`),
  ADD KEY `fk_klien_konselor` (`id_konselor`);

--
-- Indeks untuk tabel `konselor`
--
ALTER TABLE `konselor`
  ADD PRIMARY KEY (`id_konselor`),
  ADD KEY `fk_konselor_admin` (`id_admin`);

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`),
  ADD KEY `fk_layanan_admin` (`id_admin`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_login`),
  ADD KEY `fk_login_user` (`id_user`);

--
-- Indeks untuk tabel `progres_tugas`
--
ALTER TABLE `progres_tugas`
  ADD PRIMARY KEY (`id_progres`),
  ADD KEY `fk_progres_tugas` (`id_tugas`);

--
-- Indeks untuk tabel `review_persetujuan`
--
ALTER TABLE `review_persetujuan`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `fk_review_tugas` (`id_tugas`),
  ADD KEY `fk_review_supervisor` (`id_supervisor`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`),
  ADD UNIQUE KEY `nama_role` (`nama_role`);

--
-- Indeks untuk tabel `supervisor`
--
ALTER TABLE `supervisor`
  ADD PRIMARY KEY (`id_supervisor`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id_tugas`),
  ADD KEY `fk_tugas_karyawan` (`id_karyawan`),
  ADD KEY `fk_tugas_klien` (`id_klien`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_user_role` (`id_role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin_statistik`
--
ALTER TABLE `admin_statistik`
  MODIFY `id_admin` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `data_klien`
--
ALTER TABLE `data_klien`
  MODIFY `id_data_klien` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `detail_persetujuan`
--
ALTER TABLE `detail_persetujuan`
  MODIFY `id_detail` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `klien`
--
ALTER TABLE `klien`
  MODIFY `id_klien` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `konselor`
--
ALTER TABLE `konselor`
  MODIFY `id_konselor` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `id_login` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `progres_tugas`
--
ALTER TABLE `progres_tugas`
  MODIFY `id_progres` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `review_persetujuan`
--
ALTER TABLE `review_persetujuan`
  MODIFY `id_review` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id_role` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `supervisor`
--
ALTER TABLE `supervisor`
  MODIFY `id_supervisor` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `admin_statistik`
--
ALTER TABLE `admin_statistik`
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `data_klien`
--
ALTER TABLE `data_klien`
  ADD CONSTRAINT `fk_data_klien` FOREIGN KEY (`id_klien`) REFERENCES `klien` (`id_klien`);

--
-- Ketidakleluasaan untuk tabel `detail_persetujuan`
--
ALTER TABLE `detail_persetujuan`
  ADD CONSTRAINT `fk_detail_review` FOREIGN KEY (`id_review`) REFERENCES `review_persetujuan` (`id_review`);

--
-- Ketidakleluasaan untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `fk_karyawan_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `klien`
--
ALTER TABLE `klien`
  ADD CONSTRAINT `fk_klien_konselor` FOREIGN KEY (`id_konselor`) REFERENCES `konselor` (`id_konselor`);

--
-- Ketidakleluasaan untuk tabel `konselor`
--
ALTER TABLE `konselor`
  ADD CONSTRAINT `fk_konselor_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin_statistik` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD CONSTRAINT `fk_layanan_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin_statistik` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_login_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `progres_tugas`
--
ALTER TABLE `progres_tugas`
  ADD CONSTRAINT `fk_progres_tugas` FOREIGN KEY (`id_tugas`) REFERENCES `tugas` (`id_tugas`);

--
-- Ketidakleluasaan untuk tabel `review_persetujuan`
--
ALTER TABLE `review_persetujuan`
  ADD CONSTRAINT `fk_review_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `supervisor` (`id_supervisor`),
  ADD CONSTRAINT `fk_review_tugas` FOREIGN KEY (`id_tugas`) REFERENCES `tugas` (`id_tugas`);

--
-- Ketidakleluasaan untuk tabel `supervisor`
--
ALTER TABLE `supervisor`
  ADD CONSTRAINT `fk_supervisor_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `fk_tugas_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`),
  ADD CONSTRAINT `fk_tugas_klien` FOREIGN KEY (`id_klien`) REFERENCES `klien` (`id_klien`);

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
