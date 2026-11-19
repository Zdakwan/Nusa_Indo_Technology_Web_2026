-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: web-nusa-indo-tech
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('super_admin','admin_konten','admin_layanan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin_konten',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'SuperAdmin','SuperAdmin@nusaindotech','081234567890','super_admin','$2y$12$K9hr.3DM/nBwa7fIKb4faObuZ2FENhFnWslIXoHRhMTh.BN6yd9ma','2026-11-18 06:50:00','2026-11-18 06:50:00'),(2,'AdminKonten','AdminKonten@nusaindotech',NULL,'admin_konten','$2y$12$drCUT082MC5/BVs/4dJa.u/BtaN7r7Q.eV.iYUP8Bco5ICA1o7m1O','2026-11-18 06:50:01','2026-11-18 06:50:01'),(3,'AdminLayanan','AdminLayanan@nusaindotech',NULL,'admin_layanan','$2y$12$xnEywTqYwaBJiBJ2vZZImOg/hVCjraXhi/ORGbrLko.HEHpgdWk/u','2026-11-18 06:50:01','2026-11-18 06:50:01');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anggota_tims`
--

DROP TABLE IF EXISTS `anggota_tims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota_tims` (
  `id` bigint unsigned NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggota_tims`
--

LOCK TABLES `anggota_tims` WRITE;
/*!40000 ALTER TABLE `anggota_tims` DISABLE KEYS */;
INSERT INTO `anggota_tims` VALUES (1,'Muhamad Nauval Zdakwan Utomo','Fullstack Dev','tim/ZBM255HIqjbiBFjKBdoQFyXMz9Hlfyhuf0yGlKAH.png','2026-11-01 16:18:57','2026-11-01 16:18:57'),(2,'Mey Lujeng Trisnawati','FrontEnd Dev','tim/CDPo61wTMYL8VbGPJg0BIfDGXZB7dtSbyiJijVjU.png','2026-11-01 16:20:01','2026-11-01 16:20:01'),(3,'Muhammad Syahrul Mubarok','BackEnd Dev','tim/1H1WhuY4CfP0wgQ7Y3tyvG9kdhcxaMfS4ktECKEq.png','2026-11-01 16:20:47','2026-11-01 16:20:47');
/*!40000 ALTER TABLE `anggota_tims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `call_centers`
--

DROP TABLE IF EXISTS `call_centers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `call_centers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subjek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `pesan_admin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `admin_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `call_centers_client_id_foreign` (`client_id`),
  CONSTRAINT `call_centers_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `call_centers`
--

LOCK TABLES `call_centers` WRITE;
/*!40000 ALTER TABLE `call_centers` DISABLE KEYS */;
/*!40000 ALTER TABLE `call_centers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_password_reset_tokens`
--

DROP TABLE IF EXISTS `client_password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_password_reset_tokens`
--

LOCK TABLES `client_password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `client_password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
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
  `mobile_synced_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_email_unique` (`email`),
  UNIQUE KEY `clients_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'Nauval','nauvaleyeks@gmail.com','085175316273','$2y$12$rD2qdVcoV4s84Zea0uapbu3s6EgVFY4m2Gd2glxOeIkbMFzs7/Roa','2026-11-17 10:26:42','2026-11-18 07:08:08','avatars/oTafhITOYRXZnrzVl8OU5uqJho8XBJAFtJu4bDbv.jpg','Pasuruan','PT. EMEL','CEO','nauval',NULL),(2,'Mey LuTri','nauvalutomo788@gmail.com','085175316273','$2y$12$bG0fucSdtloinjDV4PuuMe4DGe1ewwfYKVw.Kb//U/d3erw3AQh/a','2026-11-17 14:36:49','2026-11-17 14:56:39',NULL,NULL,NULL,NULL,'Meyc',NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ensiklopedia`
--

DROP TABLE IF EXISTS `ensiklopedia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ensiklopedia` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konten` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ensiklopedia`
--

LOCK TABLES `ensiklopedia` WRITE;
/*!40000 ALTER TABLE `ensiklopedia` DISABLE KEYS */;
INSERT INTO `ensiklopedia` VALUES (1,'Telecollection Software','IT Portfolio Management','Lorem Ipsum',NULL,NULL,'2026-10-26 12:32:00','2026-10-26 12:52:58');
/*!40000 ALTER TABLE `ensiklopedia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konsultasi`
--

DROP TABLE IF EXISTS `konsultasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `konsultasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
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
  `metode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `konsultasi_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konsultasi`
--

LOCK TABLES `konsultasi` WRITE;
/*!40000 ALTER TABLE `konsultasi` DISABLE KEYS */;
INSERT INTO `konsultasi` VALUES (1,1,'2026-11-19','09:00:00','Web untuk PT saya di bidang logistik','approved','http://127.0.0.1:8000','2026-11-18 07:14:12','2026-11-18 07:19:54','PT. EMEL','Nauval','CEO','nauvaleyeks@gmail.com','085175316273','Software Development','Ditunggu, Terimakasih','Syahrul','Online (Zoom/GMeet)');
/*!40000 ALTER TABLE `konsultasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layanan`
--

DROP TABLE IF EXISTS `layanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `layanan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Umum',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `layanan`
--

LOCK TABLES `layanan` WRITE;
/*!40000 ALTER TABLE `layanan` DISABLE KEYS */;
INSERT INTO `layanan` VALUES (1,'IT Portfolio Management','TATA KELOLA','Perencanaan Strategis (High-Level) Jangka Panjang Pengembangan Sistem Informasi Perusahaan.','fa-solid fa-folder-open','2026-10-20 15:39:54','2026-11-03 06:45:10'),(2,'Training IT','UMUM','Kami dapat memberikan pelatihan teknologi informasi baik pada organisasi/perusahaan maupun individu.','fa-solid fa-laptop-code','2026-10-20 15:41:06','2026-11-03 06:44:41'),(3,'Enterprise Architecture','TATA KELOLA','Enterprise Architecture (EA) atau IT Architect. Pendekatan modern perencanaan SI/TI. IT Blueprint.','fa-solid fa-building-columns','2026-10-20 15:41:34','2026-11-03 06:44:55'),(4,'Software & Hardware Development','DEVELOPMENT','Kami Siap Untuk Memenuhi Kebutuhan Software dan Hardware Untuk Menunjang Bisnis Perusahaan Anda.','fa-solid fa-laptop-code','2026-10-20 15:41:52','2026-11-03 06:44:07'),(5,'IT Governance','TATA KELOLA','IT Management, IT Services dan Layanan Jasa Audit Assesment Strategis SI/TI dalam bentuk Audit IT.','fa-solid fa-code','2026-10-20 15:42:28','2026-11-03 06:27:58'),(6,'Multimedia','DEVELOPMENT','Kami Menyediakan Layanan : Pembuatan Company Profile Perusahaan, Editing Video, Animasi dan Fotografi.','fa-solid fa-camera','2026-10-20 15:42:45','2026-11-03 06:45:22');
/*!40000 ALTER TABLE `layanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_logs`
--

DROP TABLE IF EXISTS `login_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_logs_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_logs`
--

LOCK TABLES `login_logs` WRITE;
/*!40000 ALTER TABLE `login_logs` DISABLE KEYS */;
INSERT INTO `login_logs` VALUES (1,'Nauval','client','127.0.0.1','Gagal','2026-11-17 10:25:21','2026-11-17 10:25:21'),(2,'Nauval','client','127.0.0.1','Gagal','2026-11-17 10:25:29','2026-11-17 10:25:29'),(3,'nauvaleyeks@gmail.com','client','127.0.0.1','Gagal','2026-11-17 10:25:50','2026-11-17 10:25:50'),(4,'nauvaleyeks@gmail.com','client','127.0.0.1','Berhasil','2026-11-17 10:27:36','2026-11-17 10:27:36'),(5,'nauvalutomo788@gmail.com','client','127.0.0.1','Gagal','2026-11-17 14:47:51','2026-11-17 14:47:51'),(6,'nauvalutomo788@gmail.com','client','127.0.0.1','Gagal','2026-11-17 14:47:53','2026-11-17 14:47:53'),(7,'nauvalutomo788@gmail.com','client','127.0.0.1','Gagal','2026-11-17 14:48:30','2026-11-17 14:48:30'),(8,'nauvalutomo788@gmail.com','client','127.0.0.1','Gagal','2026-11-17 14:48:44','2026-11-17 14:48:44'),(9,'nauvalutomo788@gmail.com','client','127.0.0.1','Gagal','2026-11-17 14:49:12','2026-11-17 14:49:12'),(10,'nauvalutomo788@gmail.com','client','127.0.0.1','Gagal','2026-11-17 14:49:36','2026-11-17 14:49:36'),(11,'MeyLuTri','client','127.0.0.1','Gagal','2026-11-17 14:50:36','2026-11-17 14:50:36'),(12,'Mey LuTri','client','127.0.0.1','Gagal','2026-11-17 14:51:34','2026-11-17 14:51:34'),(13,'nauvalutomo788@gmail.com','client','127.0.0.1','Gagal','2026-11-17 14:52:41','2026-11-17 14:52:41'),(14,'nauvaleyeks@gmail.com','client','127.0.0.1','Berhasil','2026-11-17 14:53:20','2026-11-17 14:53:20'),(15,'nauvaleyeks@gmail.com','client','127.0.0.1','Logout','2026-11-17 14:53:31','2026-11-17 14:53:31'),(16,'nauvalutomo788@gmail.com','client','127.0.0.1','Gagal','2026-11-17 14:54:09','2026-11-17 14:54:09'),(17,'nauvalutomo788@gmail.com','client','127.0.0.1','Berhasil','2026-11-17 14:57:16','2026-11-17 14:57:16'),(18,'nauvalutomo788@gmail.com','client','127.0.0.1','Logout','2026-11-17 14:57:21','2026-11-17 14:57:21'),(19,'nauvalutomo788@gmail.com','client','127.0.0.1','Berhasil','2026-11-17 14:58:08','2026-11-17 14:58:08'),(20,'nauvalutomo788@gmail.com','client','127.0.0.1','Logout','2026-11-17 15:00:26','2026-11-17 15:00:26'),(21,'nauvalutomo788@gmail.com','client','127.0.0.1','Berhasil','2026-11-17 15:05:11','2026-11-17 15:05:11'),(22,'nauvalutomo788@gmail.com','client','127.0.0.1','Logout','2026-11-17 15:13:03','2026-11-17 15:13:03'),(23,'nauvalutomo788@gmail.com','client','127.0.0.1','Berhasil','2026-11-17 15:28:27','2026-11-17 15:28:27'),(24,'nauvalutomo788@gmail.com','client','127.0.0.1','Logout','2026-11-17 15:42:07','2026-11-17 15:42:07'),(25,'nauvaleyeks@gmail.com','client','127.0.0.1','Berhasil','2026-11-17 15:45:45','2026-11-17 15:45:45'),(26,'nauvaleyeks@gmail.com','client','127.0.0.1','Logout','2026-11-17 15:48:29','2026-11-17 15:48:29'),(27,'nauvaleyeks@gmail.com','client','127.0.0.1','Berhasil','2026-11-17 15:50:58','2026-11-17 15:50:58'),(28,'SuperAdmin@nusaindotech','super_admin','127.0.0.1','Berhasil','2026-11-18 06:50:36','2026-11-18 06:50:36'),(29,'nauvaleyeks@gmail.com','client','127.0.0.1','Gagal','2026-11-18 07:06:33','2026-11-18 07:06:33'),(30,'nauvaleyeks@gmail.com','client','127.0.0.1','Berhasil','2026-11-18 07:06:42','2026-11-18 07:06:42'),(31,'SuperAdmin@nusaindotech','super_admin','127.0.0.1','Berhasil','2026-11-18 13:09:04','2026-11-18 13:09:04'),(32,'SuperAdmin@nusaindotech','super_admin','127.0.0.1','Berhasil','2026-11-19 00:54:39','2026-11-19 00:54:39'),(33,'AdminKonten@nusaindotech','admin_konten','127.0.0.1','Berhasil','2026-11-19 00:57:48','2026-11-19 00:57:48'),(34,'SuperAdmin@nusaindotech','super_admin','127.0.0.1','Berhasil','2026-11-19 01:48:00','2026-11-19 01:48:00'),(35,'nauvaleyeks@gmail.com','client','127.0.0.1','Berhasil','2026-11-19 01:48:25','2026-11-19 01:48:25');
/*!40000 ALTER TABLE `login_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_09_30_043641_add_profile_fields_to_clients_table',1),(2,'2026_10_12_134115_add_username_to_clients_table',2),(3,'2026_10_19_052757_add_booking_fields_to_konsultasi_table',3),(4,'2026_10_19_061413_add_jabatan_pic_to_konsultasi_table',4),(5,'2026_10_19_065412_create_cache_table',5),(7,'2026_10_19_124556_create_preferensi_notifikasi_table',6),(8,'2026_10_21_082026_add_username_to_clients_table',7),(9,'2026_10_24_045348_add_layanan_id_and_rating_to_portofolio_table',7),(10,'2026_10_24_060253_add_kategori_to_layanan_table',7),(11,'2026_10_24_061529_add_fitur_dan_teknologi_to_portofolio_table',7),(12,'2026_10_26_012948_create_login_logs_table',7),(13,'2026_10_26_023103_create_tentang_kamis_table',7),(14,'2026_10_26_023104_create_anggota_tims_table',7),(15,'2026_10_27_015045_create_call_centers_table',8),(16,'2026_10_27_023939_add_pesan_admin_to_konsultasi_table',9),(17,'2026_10_27_024924_add_kategori_to_ensiklopedia_table',10),(18,'2026_11_02_063600_modify_status_column_in_login_logs_table',10),(19,'2026_11_02_073245_create_proyek_table',11),(20,'2026_11_02_073314_create_riwayat_proyek_table',11),(21,'2026_11_02_154924_add_konsultasi_id_to_proyek_table',12),(22,'2026_11_02_061619_add_kolaborasi_to_tentang_kamis_table',13),(23,'2026_11_02_083207_add_details_to_konsultasi_table',13),(24,'2026_11_16_194740_create_client_password_reset_tokens_table',14),(25,'2026_10_01_000002_create_jobs_table',15),(26,'2026_11_10_000001_add_mobile_synced_at_to_clients_table',15);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
INSERT INTO `notifikasi` VALUES (1,1,'Pendaftaran Konsultasi Berhasil','Pendaftaran konsultasi Software Development berhasil dikirim dan sedang menunggu persetujuan Admin.',1,'2026-11-18 07:14:12','2026-11-18 07:14:20'),(2,1,'Konsultasi Ditolak','Status konsultasi Software Development Anda telah diperbarui menjadi: Ditolak. Pesan dari admin: Ditunggu, Terimakasih',1,'2026-11-18 07:19:37','2026-11-18 07:20:01'),(3,1,'Konsultasi Disetujui','Status konsultasi Software Development Anda telah diperbarui menjadi: Disetujui. Pesan dari admin: Ditunggu, Terimakasih Jadwal: 19 November 2026, pukul 09:00 WIB.',1,'2026-11-18 07:19:54','2026-11-18 07:20:01'),(4,1,'Updated Proyek','Progres proyek \"Web Logistik\" telah diperbarui menjadi 24%. Catatan perkembangan: Sedang dalam Proses Desain',1,'2026-11-18 07:21:19','2026-11-18 07:21:46');
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pelatihan`
--

DROP TABLE IF EXISTS `pelatihan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pelatihan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(10,2) NOT NULL DEFAULT '0.00',
  `kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pelatihan`
--

LOCK TABLES `pelatihan` WRITE;
/*!40000 ALTER TABLE `pelatihan` DISABLE KEYS */;
INSERT INTO `pelatihan` VALUES (1,'Pembelajaran IT Dasar Untuk Pelajar SMK','Pembelajaran IT Dasar Untuk Pelajar SMK',150000.00,'Training IT','pelatihan/FB5z9QqYfL9s2MkUoWRuUCSx2MIG2Et4hRYK2dYK.jpg','2026-10-23 17:22:27','2026-11-02 09:28:11');
/*!40000 ALTER TABLE `pelatihan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portofolio`
--

DROP TABLE IF EXISTS `portofolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portofolio` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `layanan_id` bigint unsigned DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portofolio`
--

LOCK TABLES `portofolio` WRITE;
/*!40000 ALTER TABLE `portofolio` DISABLE KEYS */;
INSERT INTO `portofolio` VALUES (1,NULL,0,'Flashcomindonesia.com','Flashcomindonesia.com  adalah lembaga penyedia layanan kursus dan pelatihan komputer serta teknologi informasi terkemuka di Indonesia. Platform ini menawarkan pembelajaran 100% praktis dengan materi yang dapat disesuaikan (custom), jadwal fleksibel, serta dibimbing oleh instruktur dari kalangan praktisi dan akademisi.',NULL,NULL,NULL,NULL,NULL,'Lembaga Pembelajaran','portofolio/N315ypAFsN9gUSSzG4Rr7DP5xPLrhl4dxtzTZaJV.png','2026-10-20 15:45:02','2026-10-20 15:45:02'),(2,NULL,0,'Dinas Kebudayaan Pariwisata Jatim','Aplikasi Pendataan Disbudpar Jatim',NULL,NULL,NULL,NULL,NULL,'Aplikasi Pendataan Disbudpar Jatim','portofolio/0ABJrrWBWfsgRI05D2EnAGZDV1Jx3j0Dm7KBJwOm.png','2026-10-20 15:47:55','2026-10-20 15:47:55'),(3,4,5,'Telecollection Software','Agency Bank PT. Chrismalis Artha',NULL,'Java',NULL,NULL,NULL,'Agency Bank PT. Chrismalis Artha','portofolio/ELEKffzdefg6efuFRXWOLun0dORgICwRItcK5KYi.png','2026-10-20 15:49:28','2026-10-23 17:00:35');
/*!40000 ALTER TABLE `portofolio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preferensi_notifikasi`
--

DROP TABLE IF EXISTS `preferensi_notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preferensi_notifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `update_konsultasi` tinyint(1) NOT NULL DEFAULT '1',
  `perubahan_jadwal` tinyint(1) NOT NULL DEFAULT '1',
  `updated_proyek` tinyint(1) NOT NULL DEFAULT '1',
  `artikel_ensiklopedia` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `preferensi_notifikasi_client_id_unique` (`client_id`),
  CONSTRAINT `preferensi_notifikasi_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preferensi_notifikasi`
--

LOCK TABLES `preferensi_notifikasi` WRITE;
/*!40000 ALTER TABLE `preferensi_notifikasi` DISABLE KEYS */;
INSERT INTO `preferensi_notifikasi` VALUES (1,1,1,1,1,1,'2026-11-18 07:14:12','2026-11-18 07:14:12');
/*!40000 ALTER TABLE `preferensi_notifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyek`
--

DROP TABLE IF EXISTS `proyek`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proyek` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `konsultasi_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `admin_id` bigint unsigned DEFAULT NULL,
  `nama_proyek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `progres` tinyint unsigned NOT NULL DEFAULT '0',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'berjalan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proyek_konsultasi_id_unique` (`konsultasi_id`),
  KEY `proyek_client_id_foreign` (`client_id`),
  KEY `proyek_admin_id_foreign` (`admin_id`),
  CONSTRAINT `proyek_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `proyek_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proyek_konsultasi_id_foreign` FOREIGN KEY (`konsultasi_id`) REFERENCES `konsultasi` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyek`
--

LOCK TABLES `proyek` WRITE;
/*!40000 ALTER TABLE `proyek` DISABLE KEYS */;
INSERT INTO `proyek` VALUES (1,1,1,1,'Web Logistik','Keluar, Masuk, dan Perhitungan barang',24,'berjalan','2026-11-18 07:20:58','2026-11-18 07:21:19');
/*!40000 ALTER TABLE `proyek` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `riwayat_proyek`
--

DROP TABLE IF EXISTS `riwayat_proyek`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `riwayat_proyek` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proyek_id` bigint unsigned NOT NULL,
  `admin_id` bigint unsigned DEFAULT NULL,
  `progres` tinyint unsigned NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `riwayat_proyek_proyek_id_foreign` (`proyek_id`),
  KEY `riwayat_proyek_admin_id_foreign` (`admin_id`),
  CONSTRAINT `riwayat_proyek_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `riwayat_proyek_proyek_id_foreign` FOREIGN KEY (`proyek_id`) REFERENCES `proyek` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riwayat_proyek`
--

LOCK TABLES `riwayat_proyek` WRITE;
/*!40000 ALTER TABLE `riwayat_proyek` DISABLE KEYS */;
INSERT INTO `riwayat_proyek` VALUES (1,1,1,0,'Proyek berhasil dibuat berdasarkan konsultasi yang disetujui.','2026-11-18 07:20:58','2026-11-18 07:20:58'),(2,1,1,24,'Sedang dalam Proses Desain','2026-11-18 07:21:19','2026-11-18 07:21:19');
/*!40000 ALTER TABLE `riwayat_proyek` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('PgrqWyi2T76p97OKFrK2Iol8HHyzpT5avxLC7jjI',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiOTZUUlJWTXlJVzhUSWpXME1tRmJ1cVNTUmpPMXJlbk8xZWU5NUp4TyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTk6InBhc3N3b3JkX2hhc2hfYWRtaW4iO3M6NjQ6ImVmMzg1YTczNDgwNTM0NWU0MzY2ODA3ZmExYjkxMzViNDY4ZjFkMjYxYjYyZGYzY2U5MzM1OTRiMjNiMjIwMzMiO3M6NTM6ImxvZ2luX2NsaWVudF81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoyMDoicGFzc3dvcmRfaGFzaF9jbGllbnQiO3M6NjQ6IjFlYzFiNTY2NmIwN2FiMjlmYWU2ODA4OTdlM2NmZjA2ZTUwODVlNDNhMjFkNWQ5YzdiYjIzY2U4NDVjNDA5YzIiO30=',1795052906);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tentang_kamis`
--

DROP TABLE IF EXISTS `tentang_kamis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tentang_kamis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
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
  `foto_kolaborasi_serah_terima` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tentang_kamis`
--

LOCK TABLES `tentang_kamis` WRITE;
/*!40000 ALTER TABLE `tentang_kamis` DISABLE KEYS */;
INSERT INTO `tentang_kamis` VALUES (1,'Transformasi Digital Bersama Kami','Nusa Indo Tech hadir memberikan solusi teknologi inovatif, terstruktur, dan andal untuk membantu bisnis Anda berkembang secara eksponensial di era digital modern.','Menjadi perusahaan konsultan IT terkemuka yang mendorong transformasi digital.','Mengembangkan perangkat lunak inovatif yang berorientasi pada penyelesaian masalah bisnis.\r\n\r\nMenyediakan layanan konsultasi dan arsitektur IT yang andal, aman, dan efisien.\r\n\r\nMendampingi klien secara profesional dalam setiap fase transformasi digital.','Rungkut Asri Timur XV H No.2, V No.2 Blk H, Rungkut Kidul, Kec. Rungkut, Surabaya, Jawa Timur 60293','+62 878-5246-1990','nusaindotech@gmail.com','https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.2149542553!2d112.7848928327933!3d-7.329738420023053!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fa9551ec3715%3A0xb2f84d0e9424af79!2sNusa%20Indo%20Konsultan%20IT%20SURABAYA!5e0!3m2!1sid!2sid!4v1792986262810!5m2!1sid!2sid','2026-10-25 13:46:31','2026-11-01 16:51:24','Sistem Informasi web dan aplikasi mobile native Nusa Indo Technology ini dirancang dan dikembangkan secara khusus melalui kerja sama dengan tim mahasiswa Teknik Informatika dari Politeknik Negeri Jember (Polije) PSDKU Kampus 4 Sidoarjo. Kolaborasi ini merupakan bentuk nyata penerapan ilmu rekayasa perangkat lunak untuk menghadirkan solusi teknologi industri yang terintegrasi.','Sistem ini dibuat bertujuan untuk mengoptimalkan proses bisnis dan manajemen layanan klien melalui digitalisasi terpusat. Momen serah terima ini menjadi tonggak sejarah kerja sama yang sukses, menghasilkan ekosistem digital yang modern, aman, dan siap beroperasi penuh untuk melayani klien Nusa Indo Technology.','kolaborasi/FJo1GfB3YjGtktWY8Vf9xeGUYwdfACJ6fdoNCF17.png',NULL);
/*!40000 ALTER TABLE `tentang_kamis` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-19  9:28:31

