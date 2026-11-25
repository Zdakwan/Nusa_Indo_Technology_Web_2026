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
-- Basis data: `web-nusa-indo-tech`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `active_device_sessions`
--

CREATE TABLE `active_device_sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `authenticatable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authenticatable_id` bigint UNSIGNED NOT NULL,
  `session_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `last_activity_at` timestamp NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('super_admin','admin_konten','admin_layanan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin_konten',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `phone`, `role`, `password`, `created_at`, `updated_at`) VALUES
(1, 'SuperAdmin', 'SuperAdmin@nusaindotech', '081234567890', 'super_admin', '$2y$12$K9hr.3DM/nBwa7fIKb4faObuZ2FENhFnWslIXoHRhMTh.BN6yd9ma', '2026-11-18 06:50:00', '2026-11-18 06:50:00'),
(2, 'AdminKonten', 'AdminKonten@nusaindotech', NULL, 'admin_konten', '$2y$12$drCUT082MC5/BVs/4dJa.u/BtaN7r7Q.eV.iYUP8Bco5ICA1o7m1O', '2026-11-18 06:50:01', '2026-11-18 06:50:01'),
(3, 'AdminLayanan', 'AdminLayanan@nusaindotech', NULL, 'admin_layanan', '$2y$12$xnEywTqYwaBJiBJ2vZZImOg/hVCjraXhi/ORGbrLko.HEHpgdWk/u', '2026-11-18 06:50:01', '2026-11-18 06:50:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota_tims`
--

CREATE TABLE `anggota_tims` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `anggota_tims`
--

INSERT INTO `anggota_tims` (`id`, `nama`, `jabatan`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'Muhamad Nauval Zdakwan Utomo', 'Fullstack Dev', 'tim/ZBM255HIqjbiBFjKBdoQFyXMz9Hlfyhuf0yGlKAH.png', '2026-11-01 16:18:57', '2026-11-01 16:18:57'),
(2, 'Mey Lujeng Trisnawati', 'FrontEnd Dev', 'tim/CDPo61wTMYL8VbGPJg0BIfDGXZB7dtSbyiJijVjU.png', '2026-11-01 16:20:01', '2026-11-01 16:20:01'),
(3, 'Muhammad Syahrul Mubarok', 'BackEnd Dev', 'tim/1H1WhuY4CfP0wgQ7Y3tyvG9kdhcxaMfS4ktECKEq.png', '2026-11-01 16:20:47', '2026-11-01 16:20:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `call_centers`
--

CREATE TABLE `call_centers` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subjek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `pesan_admin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `clients`
--

CREATE TABLE `clients` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_synced_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `password`, `created_at`, `updated_at`, `avatar`, `address`, `company`, `position`, `username`, `mobile_synced_at`) VALUES
(1, 'Nauval', 'nauvaleyeks@gmail.com', '085175316273', '$2y$12$rD2qdVcoV4s84Zea0uapbu3s6EgVFY4m2Gd2glxOeIkbMFzs7/Roa', '2026-11-17 10:26:42', '2026-11-18 07:08:08', 'avatars/oTafhITOYRXZnrzVl8OU5uqJho8XBJAFtJu4bDbv.jpg', 'Pasuruan', 'PT. EMEL', 'CEO', 'nauval', NULL),
(2, 'Mey LuTri', 'nauvalutomo788@gmail.com', '085175316273', '$2y$12$bG0fucSdtloinjDV4PuuMe4DGe1ewwfYKVw.Kb//U/d3erw3AQh/a', '2026-11-17 14:36:49', '2026-11-17 14:56:39', NULL, NULL, NULL, NULL, 'Meyc', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `client_password_reset_tokens`
--

CREATE TABLE `client_password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ensiklopedia`
--

CREATE TABLE `ensiklopedia` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konten` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ensiklopedia`
--

INSERT INTO `ensiklopedia` (`id`, `judul`, `kategori`, `konten`, `tags`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Telecollection Software', 'IT Portfolio Management', 'Lorem Ipsum', NULL, NULL, '2026-10-26 12:32:00', '2026-10-26 12:52:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `tanggal_konsultasi` date NOT NULL,
  `waktu_konsultasi` time NOT NULL,
  `catatan_klien` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `link_meeting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nama_perusahaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon_pic` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_konsultasi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pesan_admin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `konsultan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `konsultasi`
--

INSERT INTO `konsultasi` (`id`, `client_id`, `tanggal_konsultasi`, `waktu_konsultasi`, `catatan_klien`, `status`, `link_meeting`, `created_at`, `updated_at`, `nama_perusahaan`, `nama_pic`, `jabatan_pic`, `email_pic`, `telepon_pic`, `jenis_konsultasi`, `pesan_admin`, `konsultan`, `metode`) VALUES
(1, 1, '2026-11-19', '09:00:00', 'Web untuk PT saya di bidang logistik', 'approved', 'http://127.0.0.1:8000', '2026-11-18 07:14:12', '2026-11-18 07:19:54', 'PT. EMEL', 'Nauval', 'CEO', 'nauvaleyeks@gmail.com', '085175316273', 'Software Development', 'Ditunggu, Terimakasih', 'Syahrul', 'Online (Zoom/GMeet)');

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_layanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Umum',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `layanan`
--

INSERT INTO `layanan` (`id`, `nama_layanan`, `kategori`, `deskripsi`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'IT Portfolio Management', 'TATA KELOLA', 'Perencanaan Strategis (High-Level) Jangka Panjang Pengembangan Sistem Informasi Perusahaan.', 'fa-solid fa-folder-open', '2026-10-20 15:39:54', '2026-11-03 06:45:10'),
(2, 'Training IT', 'UMUM', 'Kami dapat memberikan pelatihan teknologi informasi baik pada organisasi/perusahaan maupun individu.', 'fa-solid fa-laptop-code', '2026-10-20 15:41:06', '2026-11-03 06:44:41'),
(3, 'Enterprise Architecture', 'TATA KELOLA', 'Enterprise Architecture (EA) atau IT Architect. Pendekatan modern perencanaan SI/TI. IT Blueprint.', 'fa-solid fa-building-columns', '2026-10-20 15:41:34', '2026-11-03 06:44:55'),
(4, 'Software & Hardware Development', 'DEVELOPMENT', 'Kami Siap Untuk Memenuhi Kebutuhan Software dan Hardware Untuk Menunjang Bisnis Perusahaan Anda.', 'fa-solid fa-laptop-code', '2026-10-20 15:41:52', '2026-11-03 06:44:07'),
(5, 'IT Governance', 'TATA KELOLA', 'IT Management, IT Services dan Layanan Jasa Audit Assesment Strategis SI/TI dalam bentuk Audit IT.', 'fa-solid fa-code', '2026-10-20 15:42:28', '2026-11-03 06:27:58'),
(6, 'Multimedia', 'DEVELOPMENT', 'Kami Menyediakan Layanan : Pembuatan Company Profile Perusahaan, Editing Video, Animasi dan Fotografi.', 'fa-solid fa-camera', '2026-10-20 15:42:45', '2026-11-03 06:45:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_logs`
--

CREATE TABLE `login_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `login_logs`
--

INSERT INTO `login_logs` (`id`, `email`, `role`, `ip_address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Nauval', 'client', '127.0.0.1', 'Gagal', '2026-11-17 10:25:21', '2026-11-17 10:25:21'),
(2, 'Nauval', 'client', '127.0.0.1', 'Gagal', '2026-11-17 10:25:29', '2026-11-17 10:25:29'),
(3, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-17 10:25:50', '2026-11-17 10:25:50'),
(4, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-17 10:27:36', '2026-11-17 10:27:36'),
(5, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:47:51', '2026-11-17 14:47:51'),
(6, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:47:53', '2026-11-17 14:47:53'),
(7, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:48:30', '2026-11-17 14:48:30'),
(8, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:48:44', '2026-11-17 14:48:44'),
(9, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:49:12', '2026-11-17 14:49:12'),
(10, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:49:36', '2026-11-17 14:49:36'),
(11, 'MeyLuTri', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:50:36', '2026-11-17 14:50:36'),
(12, 'Mey LuTri', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:51:34', '2026-11-17 14:51:34'),
(13, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:52:41', '2026-11-17 14:52:41'),
(14, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-17 14:53:20', '2026-11-17 14:53:20'),
(15, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Logout', '2026-11-17 14:53:31', '2026-11-17 14:53:31'),
(16, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-17 14:54:09', '2026-11-17 14:54:09'),
(17, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-17 14:57:16', '2026-11-17 14:57:16'),
(18, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Logout', '2026-11-17 14:57:21', '2026-11-17 14:57:21'),
(19, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-17 14:58:08', '2026-11-17 14:58:08'),
(20, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Logout', '2026-11-17 15:00:26', '2026-11-17 15:00:26'),
(21, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-17 15:05:11', '2026-11-17 15:05:11'),
(22, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Logout', '2026-11-17 15:13:03', '2026-11-17 15:13:03'),
(23, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-17 15:28:27', '2026-11-17 15:28:27'),
(24, 'nauvalutomo788@gmail.com', 'client', '127.0.0.1', 'Logout', '2026-11-17 15:42:07', '2026-11-17 15:42:07'),
(25, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-17 15:45:45', '2026-11-17 15:45:45'),
(26, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Logout', '2026-11-17 15:48:29', '2026-11-17 15:48:29'),
(27, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-17 15:50:58', '2026-11-17 15:50:58'),
(28, 'SuperAdmin@nusaindotech', 'super_admin', '127.0.0.1', 'Berhasil', '2026-11-18 06:50:36', '2026-11-18 06:50:36'),
(29, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Gagal', '2026-11-18 07:06:33', '2026-11-18 07:06:33'),
(30, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-18 07:06:42', '2026-11-18 07:06:42'),
(31, 'SuperAdmin@nusaindotech', 'super_admin', '127.0.0.1', 'Berhasil', '2026-11-18 13:09:04', '2026-11-18 13:09:04'),
(32, 'SuperAdmin@nusaindotech', 'super_admin', '127.0.0.1', 'Berhasil', '2026-11-19 00:54:39', '2026-11-19 00:54:39'),
(33, 'AdminKonten@nusaindotech', 'admin_konten', '127.0.0.1', 'Berhasil', '2026-11-19 00:57:48', '2026-11-19 00:57:48'),
(34, 'SuperAdmin@nusaindotech', 'super_admin', '127.0.0.1', 'Berhasil', '2026-11-19 01:48:00', '2026-11-19 01:48:00'),
(35, 'nauvaleyeks@gmail.com', 'client', '127.0.0.1', 'Berhasil', '2026-11-19 01:48:25', '2026-11-19 01:48:25'),
(36, 'SuperAdmin@nusaindotech', 'super_admin', '127.0.0.1', 'Berhasil', '2026-11-19 12:14:09', '2026-11-19 12:14:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_09_30_043641_add_profile_fields_to_clients_table', 1),
(2, '2026_10_12_134115_add_username_to_clients_table', 2),
(3, '2026_10_19_052757_add_booking_fields_to_konsultasi_table', 3),
(4, '2026_10_19_061413_add_jabatan_pic_to_konsultasi_table', 4),
(5, '2026_10_19_065412_create_cache_table', 5),
(7, '2026_10_19_124556_create_preferensi_notifikasi_table', 6),
(8, '2026_10_21_082026_add_username_to_clients_table', 7),
(9, '2026_10_24_045348_add_layanan_id_and_rating_to_portofolio_table', 7),
(10, '2026_10_24_060253_add_kategori_to_layanan_table', 7),
(11, '2026_10_24_061529_add_fitur_dan_teknologi_to_portofolio_table', 7),
(12, '2026_10_26_012948_create_login_logs_table', 7),
(13, '2026_10_26_023103_create_tentang_kamis_table', 7),
(14, '2026_10_26_023104_create_anggota_tims_table', 7),
(15, '2026_10_27_015045_create_call_centers_table', 8),
(16, '2026_10_27_023939_add_pesan_admin_to_konsultasi_table', 9),
(17, '2026_10_27_024924_add_kategori_to_ensiklopedia_table', 10),
(18, '2026_11_02_063600_modify_status_column_in_login_logs_table', 10),
(19, '2026_11_02_073245_create_proyek_table', 11),
(20, '2026_11_02_073314_create_riwayat_proyek_table', 11),
(21, '2026_11_02_154924_add_konsultasi_id_to_proyek_table', 12),
(22, '2026_11_02_061619_add_kolaborasi_to_tentang_kamis_table', 13),
(23, '2026_11_02_083207_add_details_to_konsultasi_table', 13),
(24, '2026_11_16_194740_create_client_password_reset_tokens_table', 14),
(25, '2026_10_01_000002_create_jobs_table', 15),
(26, '2026_11_10_000001_add_mobile_synced_at_to_clients_table', 15),
(27, '2026_11_19_183445_create_active_device_sessions_table', 16);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `client_id`, `judul`, `pesan`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pendaftaran Konsultasi Berhasil', 'Pendaftaran konsultasi Software Development berhasil dikirim dan sedang menunggu persetujuan Admin.', 1, '2026-11-18 07:14:12', '2026-11-18 07:14:20'),
(2, 1, 'Konsultasi Ditolak', 'Status konsultasi Software Development Anda telah diperbarui menjadi: Ditolak. Pesan dari admin: Ditunggu, Terimakasih', 1, '2026-11-18 07:19:37', '2026-11-18 07:20:01'),
(3, 1, 'Konsultasi Disetujui', 'Status konsultasi Software Development Anda telah diperbarui menjadi: Disetujui. Pesan dari admin: Ditunggu, Terimakasih Jadwal: 19 November 2026, pukul 09:00 WIB.', 1, '2026-11-18 07:19:54', '2026-11-18 07:20:01'),
(4, 1, 'Updated Proyek', 'Progres proyek \"Web Logistik\" telah diperbarui menjadi 24%. Catatan perkembangan: Sedang dalam Proses Desain', 1, '2026-11-18 07:21:19', '2026-11-18 07:21:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelatihan`
--

CREATE TABLE `pelatihan` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(10,2) NOT NULL DEFAULT '0.00',
  `kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pelatihan`
--

INSERT INTO `pelatihan` (`id`, `judul`, `deskripsi`, `harga`, `kategori`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Pembelajaran IT Dasar Untuk Pelajar SMK', 'Pembelajaran IT Dasar Untuk Pelajar SMK', 150000.00, 'Training IT', 'pelatihan/FB5z9QqYfL9s2MkUoWRuUCSx2MIG2Et4hRYK2dYK.jpg', '2026-10-23 17:22:27', '2026-11-02 09:28:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `portofolio`
--

CREATE TABLE `portofolio` (
  `id` bigint UNSIGNED NOT NULL,
  `layanan_id` bigint UNSIGNED DEFAULT NULL,
  `rating` float NOT NULL DEFAULT '0',
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fitur_utama` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `teknologi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_klien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_project` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `portofolio`
--

INSERT INTO `portofolio` (`id`, `layanan_id`, `rating`, `judul`, `deskripsi`, `fitur_utama`, `teknologi`, `nama_klien`, `link_project`, `tahun`, `kategori`, `gambar`, `created_at`, `updated_at`) VALUES
(1, NULL, 0, 'Flashcomindonesia.com', 'Flashcomindonesia.com  adalah lembaga penyedia layanan kursus dan pelatihan komputer serta teknologi informasi terkemuka di Indonesia. Platform ini menawarkan pembelajaran 100% praktis dengan materi yang dapat disesuaikan (custom), jadwal fleksibel, serta dibimbing oleh instruktur dari kalangan praktisi dan akademisi.', NULL, NULL, NULL, NULL, NULL, 'Lembaga Pembelajaran', 'portofolio/N315ypAFsN9gUSSzG4Rr7DP5xPLrhl4dxtzTZaJV.png', '2026-10-20 15:45:02', '2026-10-20 15:45:02'),
(2, NULL, 0, 'Dinas Kebudayaan Pariwisata Jatim', 'Aplikasi Pendataan Disbudpar Jatim', NULL, NULL, NULL, NULL, NULL, 'Aplikasi Pendataan Disbudpar Jatim', 'portofolio/0ABJrrWBWfsgRI05D2EnAGZDV1Jx3j0Dm7KBJwOm.png', '2026-10-20 15:47:55', '2026-10-20 15:47:55'),
(3, 4, 5, 'Telecollection Software', 'Agency Bank PT. Chrismalis Artha', NULL, 'Java', NULL, NULL, NULL, 'Agency Bank PT. Chrismalis Artha', 'portofolio/ELEKffzdefg6efuFRXWOLun0dORgICwRItcK5KYi.png', '2026-10-20 15:49:28', '2026-10-23 17:00:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `preferensi_notifikasi`
--

CREATE TABLE `preferensi_notifikasi` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `update_konsultasi` tinyint(1) NOT NULL DEFAULT '1',
  `perubahan_jadwal` tinyint(1) NOT NULL DEFAULT '1',
  `updated_proyek` tinyint(1) NOT NULL DEFAULT '1',
  `artikel_ensiklopedia` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `preferensi_notifikasi`
--

INSERT INTO `preferensi_notifikasi` (`id`, `client_id`, `update_konsultasi`, `perubahan_jadwal`, `updated_proyek`, `artikel_ensiklopedia`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, '2026-11-18 07:14:12', '2026-11-18 07:14:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `proyek`
--

CREATE TABLE `proyek` (
  `id` bigint UNSIGNED NOT NULL,
  `konsultasi_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `nama_proyek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `progres` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'berjalan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `proyek`
--

INSERT INTO `proyek` (`id`, `konsultasi_id`, `client_id`, `admin_id`, `nama_proyek`, `deskripsi`, `progres`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Web Logistik', 'Keluar, Masuk, dan Perhitungan barang', 24, 'berjalan', '2026-11-18 07:20:58', '2026-11-18 07:21:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_proyek`
--

CREATE TABLE `riwayat_proyek` (
  `id` bigint UNSIGNED NOT NULL,
  `proyek_id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `progres` tinyint UNSIGNED NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `riwayat_proyek`
--

INSERT INTO `riwayat_proyek` (`id`, `proyek_id`, `admin_id`, `progres`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 'Proyek berhasil dibuat berdasarkan konsultasi yang disetujui.', '2026-11-18 07:20:58', '2026-11-18 07:20:58'),
(2, 1, 1, 24, 'Sedang dalam Proses Desain', '2026-11-18 07:21:19', '2026-11-18 07:21:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('PgrqWyi2T76p97OKFrK2Iol8HHyzpT5avxLC7jjI', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiOTZUUlJWTXlJVzhUSWpXME1tRmJ1cVNTUmpPMXJlbk8xZWU5NUp4TyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wZW5nYXR1cmFuIjtzOjU6InJvdXRlIjtzOjIyOiJhZG1pbi5wZW5nYXR1cmFuLmluZGV4Ijt9czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTk6InBhc3N3b3JkX2hhc2hfYWRtaW4iO3M6NjQ6ImVmMzg1YTczNDgwNTM0NWU0MzY2ODA3ZmExYjkxMzViNDY4ZjFkMjYxYjYyZGYzY2U5MzM1OTRiMjNiMjIwMzMiO3M6NTM6ImxvZ2luX2NsaWVudF81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoyMDoicGFzc3dvcmRfaGFzaF9jbGllbnQiO3M6NjQ6IjFlYzFiNTY2NmIwN2FiMjlmYWU2ODA4OTdlM2NmZjA2ZTUwODVlNDNhMjFkNWQ5YzdiYjIzY2U4NDVjNDA5YzIiO30=', 1795059340),
('XWq3xVuI9Xdb6PbVhH5CfdossoG9cWdKHw0eTTYR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVWRCa3hleDg5dUN4R2FzN2NwZ1NFSTlMUXZDanlWRUhhVTVIdmttbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO319', 1795090456);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tentang_kamis`
--

CREATE TABLE `tentang_kamis` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Transformasi Digital Bersama Kami',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `visi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `misi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gmaps_embed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teks_kolaborasi_tim` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `teks_kolaborasi_serah_terima` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `foto_kolaborasi_tim` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_kolaborasi_serah_terima` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tentang_kamis`
--

INSERT INTO `tentang_kamis` (`id`, `judul`, `deskripsi`, `visi`, `misi`, `alamat`, `telepon`, `email`, `gmaps_embed`, `created_at`, `updated_at`, `teks_kolaborasi_tim`, `teks_kolaborasi_serah_terima`, `foto_kolaborasi_tim`, `foto_kolaborasi_serah_terima`) VALUES
(1, 'Transformasi Digital Bersama Kami', 'Nusa Indo Tech hadir memberikan solusi teknologi inovatif, terstruktur, dan andal untuk membantu bisnis Anda berkembang secara eksponensial di era digital modern.', 'Menjadi perusahaan konsultan IT terkemuka yang mendorong transformasi digital.', 'Mengembangkan perangkat lunak inovatif yang berorientasi pada penyelesaian masalah bisnis.\r\n\r\nMenyediakan layanan konsultasi dan arsitektur IT yang andal, aman, dan efisien.\r\n\r\nMendampingi klien secara profesional dalam setiap fase transformasi digital.', 'Rungkut Asri Timur XV H No.2, V No.2 Blk H, Rungkut Kidul, Kec. Rungkut, Surabaya, Jawa Timur 60293', '+62 878-5246-1990', 'nusaindotech@gmail.com', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.2149542553!2d112.7848928327933!3d-7.329738420023053!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fa9551ec3715%3A0xb2f84d0e9424af79!2sNusa%20Indo%20Konsultan%20IT%20SURABAYA!5e0!3m2!1sid!2sid!4v1792986262810!5m2!1sid!2sid', '2026-10-25 13:46:31', '2026-11-01 16:51:24', 'Sistem Informasi web dan aplikasi mobile native Nusa Indo Technology ini dirancang dan dikembangkan secara khusus melalui kerja sama dengan tim mahasiswa Teknik Informatika dari Politeknik Negeri Jember (Polije) PSDKU Kampus 4 Sidoarjo. Kolaborasi ini merupakan bentuk nyata penerapan ilmu rekayasa perangkat lunak untuk menghadirkan solusi teknologi industri yang terintegrasi.', 'Sistem ini dibuat bertujuan untuk mengoptimalkan proses bisnis dan manajemen layanan klien melalui digitalisasi terpusat. Momen serah terima ini menjadi tonggak sejarah kerja sama yang sukses, menghasilkan ekosistem digital yang modern, aman, dan siap beroperasi penuh untuk melayani klien Nusa Indo Technology.', 'kolaborasi/FJo1GfB3YjGtktWY8Vf9xeGUYwdfACJ6fdoNCF17.png', NULL);

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `active_device_sessions`
--
ALTER TABLE `active_device_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_active_device_per_user` (`authenticatable_type`,`authenticatable_id`),
  ADD UNIQUE KEY `active_device_sessions_session_token_unique` (`session_token`),
  ADD KEY `active_device_sessions_expires_at_index` (`expires_at`);

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indeks untuk tabel `anggota_tims`
--
ALTER TABLE `anggota_tims`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `call_centers`
--
ALTER TABLE `call_centers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_centers_client_id_foreign` (`client_id`);

--
-- Indeks untuk tabel `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_email_unique` (`email`),
  ADD UNIQUE KEY `clients_username_unique` (`username`);

--
-- Indeks untuk tabel `client_password_reset_tokens`
--
ALTER TABLE `client_password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `ensiklopedia`
--
ALTER TABLE `ensiklopedia`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_logs_email_index` (`email`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indeks untuk tabel `pelatihan`
--
ALTER TABLE `pelatihan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `portofolio`
--
ALTER TABLE `portofolio`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `preferensi_notifikasi`
--
ALTER TABLE `preferensi_notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `preferensi_notifikasi_client_id_unique` (`client_id`);

--
-- Indeks untuk tabel `proyek`
--
ALTER TABLE `proyek`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proyek_konsultasi_id_unique` (`konsultasi_id`),
  ADD KEY `proyek_client_id_foreign` (`client_id`),
  ADD KEY `proyek_admin_id_foreign` (`admin_id`);

--
-- Indeks untuk tabel `riwayat_proyek`
--
ALTER TABLE `riwayat_proyek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `riwayat_proyek_proyek_id_foreign` (`proyek_id`),
  ADD KEY `riwayat_proyek_admin_id_foreign` (`admin_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `tentang_kamis`
--
ALTER TABLE `tentang_kamis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `active_device_sessions`
--
ALTER TABLE `active_device_sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `call_centers`
--
ALTER TABLE `call_centers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `ensiklopedia`
--
ALTER TABLE `ensiklopedia`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pelatihan`
--
ALTER TABLE `pelatihan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `portofolio`
--
ALTER TABLE `portofolio`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `preferensi_notifikasi`
--
ALTER TABLE `preferensi_notifikasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `proyek`
--
ALTER TABLE `proyek`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `riwayat_proyek`
--
ALTER TABLE `riwayat_proyek`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tentang_kamis`
--
ALTER TABLE `tentang_kamis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `call_centers`
--
ALTER TABLE `call_centers`
  ADD CONSTRAINT `call_centers_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `preferensi_notifikasi`
--
ALTER TABLE `preferensi_notifikasi`
  ADD CONSTRAINT `preferensi_notifikasi_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `proyek`
--
ALTER TABLE `proyek`
  ADD CONSTRAINT `proyek_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `proyek_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `proyek_konsultasi_id_foreign` FOREIGN KEY (`konsultasi_id`) REFERENCES `konsultasi` (`id`) ON DELETE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `riwayat_proyek`
--
ALTER TABLE `riwayat_proyek`
  ADD CONSTRAINT `riwayat_proyek_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `riwayat_proyek_proyek_id_foreign` FOREIGN KEY (`proyek_id`) REFERENCES `proyek` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
