/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: insta_campaign
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-0+deb13u1 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `ambassador_profiles`
--

DROP TABLE IF EXISTS `ambassador_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ambassador_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `ig_username` varchar(255) NOT NULL,
  `ig_profile_url` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `province_id` bigint(20) unsigned DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  `followers_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `bio` text DEFAULT NULL,
  `avg_views_7d` int(10) unsigned NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_at` timestamp NULL DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ambassador_profiles_ig_username_unique` (`ig_username`),
  KEY `ambassador_profiles_user_id_foreign` (`user_id`),
  KEY `ambassador_profiles_category_id_foreign` (`category_id`),
  KEY `ambassador_profiles_province_id_foreign` (`province_id`),
  KEY `ambassador_profiles_city_id_foreign` (`city_id`),
  KEY `ambassador_profiles_group_id_foreign` (`group_id`),
  KEY `ambassador_profiles_status_index` (`status`),
  KEY `ambassador_profiles_avg_views_7d_index` (`avg_views_7d`),
  CONSTRAINT `ambassador_profiles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ambassador_profiles_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ambassador_profiles_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ambassador_profiles_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ambassador_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ambassador_profiles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `ambassador_profiles` WRITE;
/*!40000 ALTER TABLE `ambassador_profiles` DISABLE KEYS */;
INSERT INTO `ambassador_profiles` VALUES
(1,3,'amb1','https://instagram.com/amb1',1,1,1,2,25000,NULL,3200,'active',1,'2026-08-19 14:51:28',NULL,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(2,4,'amb2','https://instagram.com/amb2',1,1,1,1,8000,NULL,900,'active',1,'2026-08-19 14:51:29',NULL,'2026-08-19 14:51:29','2026-08-19 14:51:29'),
(3,5,'amb3','https://instagram.com/amb3',1,1,1,2,50000,NULL,6100,'active',1,'2026-08-19 14:51:29',NULL,'2026-08-19 14:51:29','2026-08-19 14:51:29');
/*!40000 ALTER TABLE `ambassador_profiles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `campaign_assignments`
--

DROP TABLE IF EXISTS `campaign_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaign_assignments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` bigint(20) unsigned NOT NULL,
  `ambassador_id` bigint(20) unsigned NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'assigned',
  `assigned_at` timestamp NULL DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `decline_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaign_assignments_campaign_id_ambassador_id_unique` (`campaign_id`,`ambassador_id`),
  KEY `campaign_assignments_ambassador_id_status_index` (`ambassador_id`,`status`),
  CONSTRAINT `campaign_assignments_ambassador_id_foreign` FOREIGN KEY (`ambassador_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `campaign_assignments_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_assignments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `campaign_assignments` WRITE;
/*!40000 ALTER TABLE `campaign_assignments` DISABLE KEYS */;
INSERT INTO `campaign_assignments` VALUES
(1,1,3,'accepted','2026-08-18 14:51:29','2026-08-18 14:51:29',NULL,NULL,'2026-08-19 14:51:29','2026-08-19 14:51:29');
/*!40000 ALTER TABLE `campaign_assignments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `advertiser_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `story_content` text DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `province_id` bigint(20) unsigned DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `price_per_view` bigint(20) unsigned NOT NULL DEFAULT 0,
  `commission_rate` decimal(5,2) DEFAULT NULL,
  `capacity` int(10) unsigned NOT NULL DEFAULT 0,
  `remaining_capacity` int(10) unsigned NOT NULL DEFAULT 0,
  `min_avg_views` int(10) unsigned NOT NULL DEFAULT 0,
  `max_assignments_per_ambassador` int(10) unsigned NOT NULL DEFAULT 1,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaigns_slug_unique` (`slug`),
  KEY `campaigns_category_id_foreign` (`category_id`),
  KEY `campaigns_province_id_foreign` (`province_id`),
  KEY `campaigns_city_id_foreign` (`city_id`),
  KEY `campaigns_status_start_date_index` (`status`,`start_date`),
  KEY `campaigns_advertiser_id_index` (`advertiser_id`),
  CONSTRAINT `campaigns_advertiser_id_foreign` FOREIGN KEY (`advertiser_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `campaigns_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `campaigns_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  CONSTRAINT `campaigns_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
INSERT INTO `campaigns` VALUES
(1,2,'کمپین معرفی محصول آرایشی','kmpyn-maarfy-mhsol-arayshy','معرفی کرم ضدآفتاب جدید با تخفیف ویژه','لطفاً استوری معرفی محصول را منتشر کنید.',1,1,1,1000,10.00,100,90,500,1,'active','2026-08-18 14:51:29','2026-08-26 14:51:29',NULL,'2026-08-19 14:51:29','2026-08-19 14:51:29');
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,'زیبایی و آرایشی','beauty',NULL,1,'2026-08-19 14:51:27','2026-08-19 14:51:27'),
(2,'مد و پوشاک','fashion',NULL,1,'2026-08-19 14:51:27','2026-08-19 14:51:27'),
(3,'غذا و رستوران','food',NULL,1,'2026-08-19 14:51:27','2026-08-19 14:51:27'),
(4,'تکنولوژی و گجت','technology',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(5,'ورزش و تناسب اندام','sport',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(6,'آموزش و تحصیل','education',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(7,'سلامت و درمان','health',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(8,'سفر و گردشگری','travel',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(9,'موسیقی و هنر','art',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(10,'خودرو','automotive',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `province_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_province_id_name_index` (`province_id`,`name`),
  CONSTRAINT `cities_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES
(1,1,'تهران',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(2,1,'ری',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(3,1,'شمیرانات',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(4,1,'اسلامشهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(5,1,'کرج',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(6,1,'ورامین',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(7,2,'مشهد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(8,2,'نیشابور',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(9,2,'سبزوار',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(10,2,'تربت حیدریه',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(11,2,'قوچان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(12,3,'اصفهان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(13,3,'کاشان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(14,3,'نجف‌آباد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(15,3,'خمینی‌شهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(16,3,'شاهین‌شهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(17,4,'شیراز',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(18,4,'مرودشت',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(19,4,'جهرم',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(20,4,'فسا',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(21,4,'کازرون',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(22,5,'تبریز',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(23,5,'مراغه',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(24,5,'مرند',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(25,5,'اهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(26,5,'بناب',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(27,6,'ارومیه',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(28,6,'خوی',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(29,6,'مهاباد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(30,6,'بوکان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(31,6,'میاندوآب',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(32,7,'اهواز',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(33,7,'آبادان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(34,7,'دزفول',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(35,7,'خرمشهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(36,7,'ماهشهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(37,8,'ساری',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(38,8,'بابل',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(39,8,'آمل',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(40,8,'قائم‌شهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(41,8,'بابلسر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(42,9,'رشت',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(43,9,'بندر انزلی',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(44,9,'لاهیجان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(45,9,'لنگرود',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(46,9,'رودسر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(47,10,'قم',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(48,11,'کرج',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(49,11,'فردیس',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(50,11,'ساوجبلاغ',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(51,11,'نظرآباد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(52,12,'کرمان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(53,12,'سیرجان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(54,12,'رفسنجان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(55,12,'جیرفت',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(56,12,'بم',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(57,13,'یزد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(58,13,'میبد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(59,13,'اردکان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(60,13,'مهریز',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(61,14,'همدان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(62,14,'ملایر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(63,14,'نهاوند',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(64,14,'تویسرکان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(65,15,'قزوین',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(66,15,'تاکستان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(67,15,'آبیک',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(68,15,'الوند',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(69,16,'زنجان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(70,16,'ابهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(71,16,'خرمدره',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(72,16,'قیدار',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(73,17,'سنندج',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(74,17,'سقز',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(75,17,'مریوان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(76,17,'بانه',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(77,18,'کرمانشاه',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(78,18,'اسلام‌آباد غرب',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(79,18,'کنگاور',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(80,18,'هرسین',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(81,19,'خرم‌آباد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(82,19,'بروجرد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(83,19,'دورود',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(84,19,'کوهدشت',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(85,20,'ایلام',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(86,20,'دهلران',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(87,20,'مهران',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(88,20,'آبدانان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(89,21,'شهرکرد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(90,21,'بروجن',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(91,21,'فارسان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(92,21,'لردگان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(93,22,'یاسوج',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(94,22,'دوگنبدان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(95,22,'دهدشت',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(96,23,'بوشهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(97,23,'برازجان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(98,23,'کنگان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(99,23,'گناوه',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(100,24,'بندرعباس',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(101,24,'میناب',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(102,24,'قشم',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(103,24,'کیش',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(104,25,'زاهدان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(105,25,'زابل',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(106,25,'چابهار',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(107,25,'ایرانشهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(108,26,'بیرجند',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(109,26,'قائن',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(110,26,'طبس',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(111,26,'فردوس',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(112,27,'بجنورد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(113,27,'شیروان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(114,27,'اسفراین',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(115,27,'آشخانه',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(116,28,'گرگان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(117,28,'گنبد کاووس',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(118,28,'علی‌آباد کتول',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(119,28,'بندر ترکمن',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(120,29,'سمنان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(121,29,'شاهرود',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(122,29,'دامغان',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(123,29,'گرمسار',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(124,30,'اردبیل',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(125,30,'پارس‌آباد',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(126,30,'مشگین‌شهر',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(127,30,'خلخال',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(128,31,'اراک',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(129,31,'ساوه',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(130,31,'خمین',1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(131,31,'محلات',1,'2026-08-19 14:51:28','2026-08-19 14:51:28');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `daily_campaign_limit` int(10) unsigned NOT NULL DEFAULT 1,
  `weekly_campaign_limit` int(10) unsigned NOT NULL DEFAULT 5,
  `min_avg_views` int(10) unsigned NOT NULL DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES
(1,'سطح ۱','level-1',1,3,0,'سفیران تازه‌کار با کمترین سقف دریافت تبلیغ',1,'2026-08-19 14:51:27','2026-08-19 14:51:27'),
(2,'سطح ۲','level-2',2,7,500,'سفیران فعال با میانگین ویوی متوسط',1,'2026-08-19 14:51:27','2026-08-19 14:51:27'),
(3,'سطح ۳','level-3',4,14,2000,'سفیران برتر با بالاترین سقف دریافت تبلیغ',1,'2026-08-19 14:51:27','2026-08-19 14:51:27');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_08_18_000001_create_categories_table',1),
(5,'2026_08_18_000002_create_provinces_table',1),
(6,'2026_08_18_000003_create_cities_table',1),
(7,'2026_08_18_000004_create_groups_table',1),
(8,'2026_08_18_000005_add_profile_columns_to_users_table',1),
(9,'2026_08_18_000006_create_ambassador_profiles_table',1),
(10,'2026_08_18_000007_create_campaigns_table',1),
(11,'2026_08_18_000008_create_campaign_assignments_table',1),
(12,'2026_08_18_000009_create_view_submissions_table',1),
(13,'2026_08_18_000010_create_wallets_table',1),
(14,'2026_08_18_000011_create_wallet_transactions_table',1),
(15,'2026_08_18_000012_create_withdrawal_requests_table',1),
(16,'2026_08_18_000013_create_settings_table',1),
(17,'2026_08_18_000014_create_activity_logs_table',1),
(18,'2026_08_18_000015_create_notifications_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(40) NOT NULL DEFAULT 'info',
  `title` varchar(255) NOT NULL,
  `body` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `provinces` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provinces_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinces`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `provinces` WRITE;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` VALUES
(1,'تهران',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(2,'خراسان رضوی',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(3,'اصفهان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(4,'فارس',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(5,'آذربایجان شرقی',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(6,'آذربایجان غربی',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(7,'خوزستان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(8,'مازندران',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(9,'گیلان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(10,'قم',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(11,'البرز',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(12,'کرمان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(13,'یزد',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(14,'همدان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(15,'قزوین',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(16,'زنجان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(17,'کردستان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(18,'کرمانشاه',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(19,'لرستان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(20,'ایلام',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(21,'چهارمحال و بختیاری',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(22,'کهگیلویه و بویراحمد',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(23,'بوشهر',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(24,'هرمزگان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(25,'سیستان و بلوچستان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(26,'خراسان جنوبی',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(27,'خراسان شمالی',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(28,'گلستان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(29,'سمنان',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(30,'اردبیل',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(31,'مرکزی',NULL,1,'2026-08-19 14:51:28','2026-08-19 14:51:28');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'string',
  `group` varchar(30) NOT NULL DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
(1,'site_name','سامانه مدیریت کمپین استوری اینستاگرام','string','general','نام سامانه','2026-08-19 14:51:28','2026-08-19 14:51:28'),
(2,'commission_rate','10','string','financial','نرخ کمیسیون سامانه (درصد)','2026-08-19 14:51:28','2026-08-19 14:51:28'),
(3,'min_withdrawal_amount','100000','integer','financial','حداقل مبلغ برداشت (تومان)','2026-08-19 14:51:28','2026-08-19 14:51:28'),
(4,'default_price_per_view','1000','integer','financial','قیمت پیش‌فرض هر ویو (تومان)','2026-08-19 14:51:28','2026-08-19 14:51:28');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'ambassador',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `national_code` varchar(10) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_status_index` (`role`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'مدیر سیستم','admin@example.com','09120000000','admin','active',NULL,NULL,NULL,'$2y$12$QrT5lrTTF062UNIjp7AQFuCf0Idps.u.SJZK8AR/9RVZXIcvUpRO.',NULL,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(2,'شرکت تبلیغاتی نمونه','advertiser@example.com','09121111111','advertiser','active',NULL,NULL,NULL,'$2y$12$qXFTZKI5La3iT0rTKWCaOOjFGLV3F/VXHvtIcbyfNIy3ibqiXZ8UC',NULL,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(3,'سفیر یک','ambassador1@example.com','09120000000','ambassador','active',NULL,NULL,NULL,'$2y$12$MOUdP7ecGuwTdlY6Zo.rr.ZebZkgenWu1/fJWo3lcjrPS0gDTJRBq',NULL,'2026-08-19 14:51:28','2026-08-19 14:51:28'),
(4,'سفیر دو','ambassador2@example.com','09120000000','ambassador','active',NULL,NULL,NULL,'$2y$12$FqRb0kgxRQgELV6fEgdB1O6EvO5H7VZKl/ZDiqsxJWQevPngWbxSK',NULL,'2026-08-19 14:51:29','2026-08-19 14:51:29'),
(5,'سفیر سه','ambassador3@example.com','09120000000','ambassador','active',NULL,NULL,NULL,'$2y$12$Nj3M2bn9laWMa7/0TxBGIO.4hElDVpA9Mwk3tEQaAr3E676iTJVfC',NULL,'2026-08-19 14:51:29','2026-08-19 14:51:29');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `view_submissions`
--

DROP TABLE IF EXISTS `view_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `view_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `assignment_id` bigint(20) unsigned NOT NULL,
  `campaign_id` bigint(20) unsigned NOT NULL,
  `ambassador_id` bigint(20) unsigned NOT NULL,
  `views_count` int(10) unsigned NOT NULL DEFAULT 0,
  `screenshot_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `review_note` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `view_submissions_assignment_id_foreign` (`assignment_id`),
  KEY `view_submissions_reviewed_by_foreign` (`reviewed_by`),
  KEY `view_submissions_ambassador_id_status_index` (`ambassador_id`,`status`),
  KEY `view_submissions_campaign_id_status_index` (`campaign_id`,`status`),
  CONSTRAINT `view_submissions_ambassador_id_foreign` FOREIGN KEY (`ambassador_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `view_submissions_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `campaign_assignments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `view_submissions_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `view_submissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_submissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `view_submissions` WRITE;
/*!40000 ALTER TABLE `view_submissions` DISABLE KEYS */;
INSERT INTO `view_submissions` VALUES
(1,1,1,3,1200,NULL,NULL,'pending',NULL,NULL,'2026-08-19 14:51:29',NULL,'2026-08-19 14:51:29','2026-08-19 14:51:29');
/*!40000 ALTER TABLE `view_submissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `amount` decimal(15,0) NOT NULL,
  `balance_after` decimal(15,0) NOT NULL DEFAULT 0,
  `reference_type` varchar(30) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
  KEY `wallet_transactions_user_id_type_index` (`user_id`,`type`),
  KEY `wallet_transactions_reference_type_index` (`reference_type`),
  CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_transactions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wallet_transactions` WRITE;
/*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `balance` decimal(15,0) NOT NULL DEFAULT 0,
  `blocked_balance` decimal(15,0) NOT NULL DEFAULT 0,
  `currency` varchar(8) NOT NULL DEFAULT 'IRT',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallets_user_id_unique` (`user_id`),
  CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
INSERT INTO `wallets` VALUES
(1,1,0,0,'IRT','2026-08-19 14:51:28','2026-08-19 14:51:28'),
(2,2,0,0,'IRT','2026-08-19 14:51:28','2026-08-19 14:51:28'),
(3,3,0,0,'IRT','2026-08-19 14:51:28','2026-08-19 14:51:28'),
(4,4,0,0,'IRT','2026-08-19 14:51:29','2026-08-19 14:51:29'),
(5,5,0,0,'IRT','2026-08-19 14:51:29','2026-08-19 14:51:29');
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `withdrawal_requests`
--

DROP TABLE IF EXISTS `withdrawal_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdrawal_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(15,0) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `sheba_number` varchar(26) DEFAULT NULL,
  `card_number` varchar(16) DEFAULT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `review_note` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `withdrawal_requests_reviewed_by_foreign` (`reviewed_by`),
  KEY `withdrawal_requests_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `withdrawal_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `withdrawal_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdrawal_requests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `withdrawal_requests` WRITE;
/*!40000 ALTER TABLE `withdrawal_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdrawal_requests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Dumping routines for database 'insta_campaign'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-08-19 14:51:29
