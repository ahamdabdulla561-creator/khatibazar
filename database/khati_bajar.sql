-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: khati_bajar
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'Home',
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `upazila` varchar(255) NOT NULL,
  `full_address` text NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  KEY `audit_logs_action_index` (`action`),
  KEY `audit_logs_created_at_index` (`created_at`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'Admin Login','অ্যাডমিন Khati Bazar Admin সিস্টেমে লগইন করেছেন।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0','2026-10-01 07:21:32','2026-10-01 07:21:32'),(2,1,'Admin Login','অ্যাডমিন Khati Bazar Admin সিস্টেমে লগইন করেছেন।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-01 10:16:30','2026-10-01 10:16:30'),(3,1,'Admin Login','অ্যাডমিন Khati Bazar Admin সিস্টেমে লগইন করেছেন।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-03 05:57:54','2026-10-03 05:57:54'),(4,1,'Hero Banner Updated','ব্যানার আপডেট করা হয়েছে: Khati Bazar — Pure Agricultural & Veterinary Supplies','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-03 05:59:04','2026-10-03 05:59:04'),(5,1,'Hero Banner Updated','ব্যানার আপডেট করা হয়েছে: Khati Bazar — kkkkkkkkkkkk','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-03 06:39:02','2026-10-03 06:39:02'),(6,1,'Product Updated','পণ্য \'ffffffff\' আপডেট করা হয়েছে।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-03 09:34:46','2026-10-03 09:34:46'),(7,1,'Admin Login','অ্যাডমিন Khati Bazar Admin সিস্টেমে লগইন করেছেন।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-05 08:09:32','2026-10-05 08:09:32'),(8,1,'Order Status Changed','অর্ডার #KB-20261005-LQFPJ স্ট্যাটাস পরিবর্তন করা হয়েছে (pending -> confirmed)।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-05 09:01:35','2026-10-05 09:01:35'),(9,1,'Order Status Changed','অর্ডার #KB-20261005-LQFPJ স্ট্যাটাস পরিবর্তন করা হয়েছে (confirmed -> confirmed)।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-05 09:02:50','2026-10-05 09:02:50'),(10,1,'Order Status Changed','অর্ডার #KB-20261005-NUV5Y স্ট্যাটাস পরিবর্তন করা হয়েছে (pending -> confirmed)।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-05 09:28:10','2026-10-05 09:28:10'),(11,1,'Admin Order Message Sent','অর্ডার #KB-20261005-V5UVX এর জন্য মেসেজ পাঠানো হয়েছে।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-05 09:54:49','2026-10-05 09:54:49'),(12,1,'Admin Order Message Sent','অর্ডার #KB-20261005-V5UVX এর জন্য মেসেজ পাঠানো হয়েছে।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-05 10:05:05','2026-10-05 10:05:05'),(13,1,'Order Status Changed','অর্ডার #KB-20261005-I2JEG স্ট্যাটাস পরিবর্তন করা হয়েছে (pending -> confirmed)।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-05 10:22:57','2026-10-05 10:22:57'),(14,1,'Admin Login','অ্যাডমিন Khati Bazar Admin সিস্টেমে লগইন করেছেন।','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-06 07:15:16','2026-10-06 07:15:16');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `badge_text` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `position` varchar(255) NOT NULL DEFAULT 'hero_main',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'Khati Bazar — kkkkkkkkkkkk','hhhhhhhhhhhhhhhhhhhhhhhhhhh','100% Genuine Farm Products',NULL,'https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=1000&auto=format&fit=crop','Shop Now','/products','hero_main','active',1,'2026-10-03 05:50:51','2026-10-03 06:39:02'),(2,'CFC Plus Combo Feed Concentrate','Boost animal immunity, milk production, and overall farm profitability.','Special Combo Package',NULL,'https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=1000&auto=format&fit=crop','View Combo Deals','/category/cfc-plus-combo','hero_main','active',2,'2026-10-03 05:50:51','2026-10-03 05:50:51'),(3,'Pure Fish Medicine & Water Treatment','Original vitamins, growth boosters, and fish farming health solutions.','Fast Delivery Nationwide',NULL,'https://images.unsplash.com/photo-1544816155-12df9643f363?w=1000&auto=format&fit=crop','Browse All Products','/products','hero_main','active',3,'2026-10-03 05:50:51','2026-10-03 05:50:51');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Khati Bazar Organics','khati-bajar-organics',NULL,'active','2026-10-01 06:09:32','2026-10-01 06:09:32'),(2,'AgroVet Ltd','agrovet-ltd',NULL,'active','2026-10-01 06:09:32','2026-10-01 06:09:32'),(3,'BioAqua Labs','bioaqua-labs',NULL,'active','2026-10-01 06:09:32','2026-10-01 06:09:32');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_variant_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_cart_id_foreign` (`cart_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  KEY `cart_items_product_variant_id_foreign` (`product_variant_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (3,1,7,NULL,1,580.00,'2026-10-01 10:48:51','2026-10-01 10:48:51');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_session_id_index` (`session_id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,NULL,'bY3kpxKr9WtqoCVdbEuhwnRv3YbEN51xog0ROktb','2026-10-01 06:58:17','2026-10-01 06:58:17'),(2,NULL,'MT13zzFBdWEhfmFJbnwCL3dlRz3Sfnve2WnaVSE2','2026-10-01 07:21:13','2026-10-01 07:21:13'),(3,NULL,'IDoEvMFjVxyo7ZYOJlTdh8uNjDfuqHFIgpG2xbPT','2026-10-03 05:56:17','2026-10-03 05:56:17'),(4,NULL,'9kEy0Rj4uB39NQ7PZECf4MvGXs3gOASwmQ7dZxHD','2026-10-03 05:57:36','2026-10-03 05:57:36'),(5,NULL,'zwj11CgVQC5LlUNiPLWp1ZSu0M67SYvNqtm0JOJ3','2026-10-03 07:30:13','2026-10-03 07:30:13'),(6,NULL,'6rD2C8tW9Zbx6HpaerirnfTP92IHNggrw3y8qral','2026-10-05 07:43:56','2026-10-05 07:43:56'),(7,NULL,'Z7HLJgDMTeVAcMl1IKW2skPPG9hkBONTcNUQjIpe','2026-10-05 08:05:17','2026-10-05 08:05:17'),(8,NULL,'RBhT4mzSyrmv2UXujo5JnBVyVK4oGAk1gGOWWGGm','2026-10-06 07:08:02','2026-10-06 07:08:02'),(9,NULL,'2mN4H5EALA0rNMBaVaHQrfAAgpzXULnyXrsPMVNL','2026-10-06 07:15:10','2026-10-06 07:15:10');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Veterinary Medicine','veterinary-medicine','উচ্চমানের গবাদিপশুর ঔষুধ ও সাপ্লিমেন্ট','categories/vet_medicine.png','active',1,'2026-10-01 06:09:32','2026-10-05 07:58:31'),(2,'Fish Medicine','fish-medicine','মাছ চাষের জন্য প্রয়োজনীয় উপাদান ও ঔষুধ','categories/fish_medicine.png','active',2,'2026-10-01 06:09:32','2026-10-05 07:58:31'),(3,'CFC Plus Combo','cfc-plus-combo','বিশেষ সিএফসি প্লাস খামার প্যাকেজ ও কম্বো','categories/cfc_combo.png','active',3,'2026-10-01 06:09:32','2026-10-05 07:58:31'),(4,'Other Products','other-products','অন্যান্য কৃষি ও খামার পণ্য সামগ্রী','categories/other_products.png','active',4,'2026-10-01 06:09:32','2026-10-05 07:58:31');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `combo_offers`
--

DROP TABLE IF EXISTS `combo_offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `combo_offers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `badge_text` varchar(255) NOT NULL DEFAULT 'BIG COMBO OFFER',
  `image` varchar(255) DEFAULT NULL,
  `offer_badge_text` varchar(255) NOT NULL DEFAULT 'BIG OFFER',
  `offer_text` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `link` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `combo_offers_product_id_foreign` (`product_id`),
  CONSTRAINT `combo_offers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `combo_offers`
--

LOCK TABLES `combo_offers` WRITE;
/*!40000 ALTER TABLE `combo_offers` DISABLE KEYS */;
INSERT INTO `combo_offers` VALUES (1,'Special Feed Package','BIG COMBO OFFER','combo_offers/cfc_combo_1.png','BIG OFFER','৳ 340',340.00,'http://localhost:8000/products/cfc-plus-combo-feed-concentrate',1,'active',1,'2026-10-03 09:39:33','2026-10-05 07:58:31'),(2,'CFC Plus Booster','BIG COMBO OFFER','combo_offers/cfc_combo_2.png','BIG OFFER','৳ 460',460.00,'http://localhost:8000/products/super-vet-calcium-mineral-solution-1l',2,'active',2,'2026-10-03 09:39:33','2026-10-05 07:58:31'),(3,'Vitamin Mineral Mix','BIG COMBO OFFER','combo_offers/fish_combo.png','BIG OFFER','৳ 420',420.00,'http://localhost:8000/products/bioaqua-fish-growth-booster-500g',3,'active',3,'2026-10-03 09:39:33','2026-10-05 07:58:31'),(4,'Fish Growth Medicine','BIG COMBO OFFER','combo_offers/vet_combo.png','BIG OFFER','৳ 1,850',1850.00,'http://localhost:8000/products/organic-farm-soil-enhancer-5kg',4,'active',4,'2026-10-03 09:39:33','2026-10-05 07:58:31'),(5,'Poultry Vaccine Supplement','BIG COMBO OFFER','combo_offers/soil_combo.png','BIG OFFER','৳ 240',240.00,'http://localhost:8000/products/cfc-plus-combo-feed-concentrate-1kg',5,'active',5,'2026-10-03 09:39:33','2026-10-05 07:58:31'),(6,'Organic Farm Feed 1kg','BIG COMBO OFFER','combo_offers/cfc_combo_1.png','BIG OFFER','৳ 310',310.00,'http://localhost:8000/products/bio-blue-spray-for-animal-wounds',6,'active',6,'2026-10-03 09:39:33','2026-10-05 07:58:31'),(7,'CFC Super Combo Pack','BIG COMBO OFFER',NULL,'BIG OFFER','৳ 410',410.00,'http://localhost:8000/products/lactiva-cattle-milk-growth-powder-1kg',7,'active',7,'2026-10-03 09:39:33','2026-10-03 09:39:33'),(8,'Livestock Calcium Feed','BIG COMBO OFFER',NULL,'BIG OFFER','৳ 650',650.00,'http://localhost:8000/products/aqua-prob-soil-water-probiotic-1kg',8,'active',8,'2026-10-03 09:39:33','2026-10-03 09:39:33'),(9,'Fish Oxygen Powder','BIG COMBO OFFER',NULL,'BIG OFFER','৳ 190',190.00,'http://localhost:8000/products/argunil-fish-parasite-treatment-100ml',9,'active',9,'2026-10-03 09:39:33','2026-10-03 09:39:33'),(10,'Dairy Protein Concentrate','BIG COMBO OFFER',NULL,'BIG OFFER','৳ 280',280.00,'http://localhost:8000/products/reegain-fish-growth-supplement-1kg',10,'active',10,'2026-10-03 09:39:33','2026-10-03 09:39:33'),(11,'Aqua Care Supplement','BIG COMBO OFFER',NULL,'BIG OFFER','৳ 510',510.00,'http://localhost:8000/products/cal-d-phos-cattle-calcium-syrup-1l',11,'active',11,'2026-10-03 09:39:33','2026-10-03 09:39:33'),(12,'CFC Jumbo Farm Combo','BIG COMBO OFFER',NULL,'BIG OFFER','৳ 1,380',1380.00,'http://localhost:8000/products/vet-mineral-premix-500g',12,'active',12,'2026-10-03 09:39:33','2026-10-03 09:39:33');
/*!40000 ALTER TABLE `combo_offers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courier_services`
--

DROP TABLE IF EXISTS `courier_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courier_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tracking_url_template` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courier_services_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courier_services`
--

LOCK TABLES `courier_services` WRITE;
/*!40000 ALTER TABLE `courier_services` DISABLE KEYS */;
INSERT INTO `courier_services` VALUES (1,'Sundarban Courier Service','sundarban',NULL,0.00,'https://www.sundarbancourier.com.bd/tracking?id={tracking_code}','সারাদেশে শাখা ভিত্তিক দ্রুত কুরিয়ার ডেলিভারি সার্ভিস।','active',1,'2026-10-03 07:07:16','2026-10-03 07:07:16'),(2,'SA Paribahan','sa_paribahan',NULL,0.00,NULL,'এস এ পরিবহন পার্সেল ও কুরিয়ার সার্ভিস।','active',2,'2026-10-03 07:07:16','2026-10-03 07:07:16'),(3,'Pathao Courier','pathao',NULL,0.00,'https://pathao.com/tracking/?consignment_id={tracking_code}','পাঠাও এক্সপ্রেস হোম ডেলিভারি সার্ভিস।','active',3,'2026-10-03 07:07:16','2026-10-03 07:07:16'),(4,'Steadfast Courier','steadfast',NULL,0.00,'https://steadfast.com.bd/t/{tracking_code}','স্টেডফাস্ট কুরিয়ার ফাস্ট ও সেফ ডেলিভারি।','active',4,'2026-10-03 07:07:16','2026-10-03 07:07:16'),(5,'RedX','redx',NULL,0.00,'https://redx.com.bd/track-order/?trackingId={tracking_code}','রেডএক্স লজিস্টিকস ও কুরিয়ার।','active',5,'2026-10-03 07:07:16','2026-10-03 07:07:16'),(6,'Paperfly','paperfly',NULL,0.00,'https://paperfly.com.bd/tracking/{tracking_code}','পেপারফ্লাই স্মার্ট লজিস্টিকস।','active',6,'2026-10-03 07:07:16','2026-10-03 07:07:16'),(7,'eCourier','ecourier',NULL,0.00,'https://ecourier.com.bd/track/?id={tracking_code}','ই-কুরিয়ার ডিজিটাল ডেলিভারি সমাধান।','active',7,'2026-10-03 07:07:16','2026-10-03 07:07:16'),(8,'AJR Courier','ajr',NULL,0.00,NULL,'এজেআর কুরিয়ার ও পরিবহন সেবা।','active',8,'2026-10-03 07:07:16','2026-10-03 07:07:16'),(9,'Janani Express','janani',NULL,0.00,NULL,'জননী এক্সপ্রেস পার্সেল সার্ভিস।','active',9,'2026-10-03 07:07:16','2026-10-03 07:07:16'),(10,'Karatoa Courier Service','karatoa',NULL,0.00,NULL,'করতোয়া কুরিয়ার ও ট্রান্সপোর্ট সার্ভিস।','active',10,'2026-10-03 07:07:16','2026-10-03 07:07:16');
/*!40000 ALTER TABLE `courier_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
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

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_01_000001_create_addresses_table',1),(5,'2026_10_01_000002_create_categories_table',1),(6,'2026_10_01_000003_create_brands_table',1),(7,'2026_10_01_000004_create_products_table',1),(8,'2026_10_01_000005_create_product_images_table',1),(9,'2026_10_01_000006_create_product_variants_table',1),(10,'2026_10_01_000007_create_carts_table',1),(11,'2026_10_01_000008_create_cart_items_table',1),(12,'2026_10_01_000009_create_orders_table',1),(13,'2026_10_01_000010_create_order_items_table',1),(14,'2026_10_01_000011_create_site_settings_table',1),(15,'2026_10_01_000012_create_audit_logs_table',1),(16,'2026_10_01_000013_add_payment_fields_to_orders_table',2),(17,'2026_10_03_000014_create_banners_table',3),(18,'2026_10_03_000015_create_courier_services_table',4),(19,'2026_10_03_000016_add_courier_service_to_orders_table',4),(20,'2026_10_03_000017_create_combo_offers_table',5),(21,'2026_10_03_000018_add_size_and_color_to_product_variants_table',6),(22,'2026_10_05_000019_create_order_messages_table',7);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_variant_id` bigint(20) unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `variant_name` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_product_variant_id_foreign` (`product_variant_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,4,NULL,'Organic Farm Soil Enhancer 5KG',NULL,'KB-AGRI-301',450.00,1,450.00,'2026-10-01 10:18:53','2026-10-01 10:18:53'),(2,1,15,NULL,'Cattle Liver Tonic 1L',NULL,'9KMHHLG4',420.00,1,420.00,'2026-10-01 10:18:53','2026-10-01 10:18:53'),(3,2,1,NULL,'CFC Plus Combo Feed Concentrate',NULL,NULL,800.00,3,2400.00,'2026-10-03 11:07:40','2026-10-03 11:07:40'),(4,3,2,NULL,'Super Vet Calcium & Mineral Solution',NULL,NULL,580.00,2,1160.00,'2026-10-03 11:07:40','2026-10-03 11:07:40'),(5,4,3,NULL,'BioAqua Fish Growth Booster 500g',NULL,NULL,390.00,10,3900.00,'2026-10-03 11:07:40','2026-10-03 11:07:40'),(6,5,23,NULL,'BioAqua Emergency Oxygen Powder 500g',NULL,'RKOKNIRV',340.00,1,340.00,'2026-10-05 09:00:47','2026-10-05 09:00:47'),(7,6,20,NULL,'Mastitis Care Teat Spray 250ml',NULL,'DIJMGZZV',280.00,1,280.00,'2026-10-05 09:26:33','2026-10-05 09:26:33'),(8,7,23,NULL,'BioAqua Emergency Oxygen Powder 500g',NULL,'RKOKNIRV',340.00,1,340.00,'2026-10-05 09:52:57','2026-10-05 09:52:57'),(9,8,5,NULL,'CFC Plus Combo Feed Concentrate 1kg',NULL,'M7F9SWLC',750.00,1,750.00,'2026-10-05 10:16:04','2026-10-05 10:16:04'),(10,8,23,NULL,'BioAqua Emergency Oxygen Powder 500g',NULL,'RKOKNIRV',340.00,1,340.00,'2026-10-05 10:16:04','2026-10-05 10:16:04');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_messages`
--

DROP TABLE IF EXISTS `order_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `sender_type` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `sender_name` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_messages_order_id_created_at_index` (`order_id`,`created_at`),
  CONSTRAINT `order_messages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_messages`
--

LOCK TABLES `order_messages` WRITE;
/*!40000 ALTER TABLE `order_messages` DISABLE KEYS */;
INSERT INTO `order_messages` VALUES (1,7,'admin','Khati Bazar Admin','Hi sir',1,'2026-10-05 09:54:49','2026-10-05 09:54:49'),(2,7,'admin','Khati Bazar Admin','fff',1,'2026-10-05 10:05:05','2026-10-05 10:05:05'),(3,5,'customer','ddddddd','hi',0,'2026-10-05 10:20:57','2026-10-05 10:20:57');
/*!40000 ALTER TABLE `order_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `shipping_district` varchar(255) NOT NULL,
  `shipping_upazila` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `delivery_area` varchar(255) NOT NULL DEFAULT 'inside_dhaka',
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cod',
  `courier_service_id` bigint(20) unsigned DEFAULT NULL,
  `courier_name` varchar(255) DEFAULT NULL,
  `courier_tracking_id` varchar(255) DEFAULT NULL,
  `sender_number` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_screenshot` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `order_status` varchar(255) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_order_status_payment_status_index` (`order_status`,`payment_status`),
  KEY `orders_customer_phone_index` (`customer_phone`),
  KEY `orders_courier_service_id_foreign` (`courier_service_id`),
  CONSTRAINT `orders_courier_service_id_foreign` FOREIGN KEY (`courier_service_id`) REFERENCES `courier_services` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'KB-20261001-XYQKL',NULL,'sdd','123456678900',NULL,'dcvef','efefasdfqwde','vsdvggsfhfhrhbsrg','inside_dhaka',870.00,0.00,70.00,940.00,'cod',NULL,NULL,NULL,NULL,NULL,NULL,'pending','pending',NULL,'2026-10-01 10:18:53','2026-10-01 10:18:53'),(2,'KB-ORD-20261003-01',2,'Md. Rahim','01712345678','rahim@example.com','Dhaka','Mirpur','Mirpur-10, Dhaka','inside_dhaka',2400.00,0.00,70.00,2470.00,'cod',NULL,'Sundarban Courier Service',NULL,NULL,NULL,NULL,'paid','delivered',NULL,'2026-10-05 07:59:55','2026-10-05 07:59:55'),(3,'KB-ORD-20261002-02',2,'Karim Farmer','01898765432',NULL,'Bogura','Sadar','Bogura Sadar, Bogura','inside_dhaka',1160.00,0.00,130.00,1290.00,'bkash',NULL,'SA Paribahan',NULL,NULL,NULL,NULL,'paid','delivered',NULL,'2026-10-04 07:59:55','2026-10-05 07:59:55'),(4,'KB-ORD-20260928-03',2,'Alim Enterprise','01911223344',NULL,'Dhaka','Savar','Savar, Dhaka','inside_dhaka',3900.00,0.00,130.00,4030.00,'cod',NULL,'Pathao Courier',NULL,NULL,NULL,NULL,'paid','delivered',NULL,'2026-09-30 07:59:55','2026-10-05 07:59:55'),(5,'KB-20261005-LQFPJ',NULL,'ddddddd','01908850133',NULL,'dcvef','efefasdfqwde','kobirpur','inside_dhaka',340.00,0.00,70.00,410.00,'bkash',3,'Pathao Courier','236453TY','01908850133','efasfsderweWDE','payments/screenshots/4jEMqNqHiuzwPhYwePCqs4XSEs69gokzlXTa5zdP.jpg','paid','confirmed',NULL,'2026-10-05 09:00:47','2026-10-05 09:02:50'),(6,'KB-20261005-NUV5Y',NULL,'ddddddd','01908850133',NULL,'dcvef','efefasdfqwde','fjhththt','inside_dhaka',280.00,0.00,70.00,350.00,'bkash',NULL,NULL,NULL,'01908850133','efasfsderweWDE','payments/screenshots/oct9lj2uyFFOYzAO6xeJwlmoY5y9faRw336FbzFZ.jpg','pending','confirmed',NULL,'2026-10-05 09:26:33','2026-10-05 09:28:10'),(7,'KB-20261005-V5UVX',NULL,'vvvvvv','01908850133',NULL,'dcvef','efefasdfqwde','dssfs','inside_dhaka',340.00,0.00,70.00,410.00,'cod',3,'Pathao Courier',NULL,NULL,NULL,NULL,'pending','pending',NULL,'2026-10-05 09:52:57','2026-10-05 09:52:57'),(8,'KB-20261005-I2JEG',NULL,'aaa','01908850133',NULL,'dcvef','efefasdfqwde','cccdd','inside_dhaka',1090.00,0.00,70.00,1160.00,'cod',3,'Pathao Courier','236453TY',NULL,NULL,NULL,'unpaid','confirmed',NULL,'2026-10-05 10:16:04','2026-10-05 10:22:57');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_variants_product_id_status_index` (`product_id`,`status`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,1,'1 KG Pack','1 KG','Green','KB-CFC-1KG',800.00,100,'active','2026-10-01 06:09:32','2026-10-03 11:02:50'),(2,1,'2 KG Pack','2 KG','Green','KB-CFC-2KG',1400.00,80,'active','2026-10-01 06:09:32','2026-10-03 11:02:50'),(3,1,'3 KG Pack','3 KG','Yellow','KB-CFC-3KG',2000.00,60,'active','2026-10-01 06:09:32','2026-10-03 11:02:50'),(4,1,'4 KG',NULL,NULL,'KB-CFC-4KG',2500.00,50,'active','2026-10-01 06:09:32','2026-10-01 06:09:32'),(5,1,'6 KG Mega Pack','6 KG','Red','KB-CFC-6KG',3600.00,40,'active','2026-10-01 06:09:32','2026-10-03 11:02:50'),(6,1,'12 KG Jumbo Pack','12 KG','Gold','KB-CFC-12KG',6800.00,30,'active','2026-10-01 06:09:32','2026-10-03 11:02:50'),(7,1,'24 KG',NULL,NULL,'KB-CFC-24KG',13300.00,20,'active','2026-10-01 06:09:32','2026-10-01 06:09:32'),(8,2,'1 Liter Bottle','1 L','White','KB-VET-101-1L',580.00,25,'active','2026-10-03 11:02:50','2026-10-03 11:02:50'),(9,2,'5 Liter Jar','5 L','Blue','KB-VET-101-5L',2500.00,20,'active','2026-10-03 11:02:50','2026-10-03 11:02:50');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `regular_price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_super_offer` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_status_is_featured_is_super_offer_index` (`status`,`is_featured`,`is_super_offer`),
  KEY `products_regular_price_index` (`regular_price`),
  KEY `products_sale_price_index` (`sale_price`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,3,1,'CFC Plus Combo Feed Concentrate','cfc-plus-combo-feed-concentrate','KB-CFC-01','গবাদিপশুর স্বাস্থ্য সুরক্ষা, দুধ উৎপাদন বৃদ্ধি এবং দ্রুত ওজন বাড়ানোর প্রিমিয়াম ফর্মুলা।','সিএফসি প্লাস পাউডার খামারের গরু, ছাগল ও মহিষের হজমশক্তি বৃদ্ধি, রোগ প্রতিরোধ ক্ষমতা বাড়ানো এবং দ্রুত শারীরিক বৃদ্ধির জন্য অত্যন্ত কার্যকরী একটি পণ্য।','products/cfc_plus.png',800.00,800.00,NULL,500,'active',1,1,1,'2026-10-01 06:09:32','2026-10-05 07:58:31'),(2,1,2,'Super Vet Calcium & Mineral Solution','super-vet-calcium-mineral-solution','KB-VET-101','দুগ্ধবতী গাভীর জন্য উচ্চমাত্রার লিকুইড ক্যালসিয়াম ও ফসফরাস।','গাভীর হাড় শক্ত করতে এবং দুগ্ধ নিঃসরণ ক্ষমতা বহুগুণ বাড়াতে সহায়তা করে। প্রতিদিন নির্ধারিত মাত্রায় ব্যবহার্য।','products/super_vet.png',650.00,580.00,11,45,'active',1,0,2,'2026-10-01 06:09:32','2026-10-05 07:58:31'),(3,2,3,'BioAqua Fish Growth Booster 500g','bioaqua-fish-growth-booster-500g','KB-FISH-201','পুকুরের পানির গুণমান বৃদ্ধি এবং মাছের দ্রুত দৈহিক বৃদ্ধি সহায়তায় প্রবায়োটিক।','পুকুরের অ্যামোনিয়া গ্যাস দূর করে এবং মাছকে সব ধরনের রোগবালাই থেকে রক্ষা করে।','products/bioaqua_fish.png',450.00,390.00,13,60,'active',1,1,3,'2026-10-01 06:09:32','2026-10-05 07:58:31'),(4,4,1,'Organic Farm Soil Enhancer 5KG','organic-farm-soil-enhancer-5kg','KB-AGRI-301','১০০% জৈব উপাদানে তৈরি মাটির উর্বরতা বৃদ্ধিকারী প্রাকৃতিক সার।','ফসলের ফলন দ্বিগুণ করতে এবং মাটির অনুজীব সক্রিয় রাখতে দারুণ উপযোগী।','products/soil_enhancer.png',500.00,450.00,10,100,'active',1,0,4,'2026-10-01 06:09:32','2026-10-05 07:58:31'),(5,3,NULL,'CFC Plus Combo Feed Concentrate 1kg','cfc-plus-combo-feed-concentrate-1kg','M7F9SWLC',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',800.00,750.00,NULL,99,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 10:16:04'),(6,1,NULL,'Bio-Blue Spray for Animal Wounds 200ml','bio-blue-spray-for-animal-wounds','YSYFI1GV',NULL,'100% genuine quality product for your farm.','products/bioblue.png',450.00,390.00,NULL,40,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(7,1,NULL,'Lactiva Cattle Milk Growth Powder 1kg','lactiva-cattle-milk-growth-powder-1kg','MDOCZXAJ',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',650.00,580.00,NULL,60,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(8,2,NULL,'Aqua-Prob+ Soil & Water Probiotic 1kg','aqua-prob-soil-water-probiotic-1kg','3GDXKNKE',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',550.00,490.00,NULL,30,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(9,2,NULL,'Argunil Fish Parasite Treatment 100ml','argunil-fish-parasite-treatment-100ml','7OBEJ8UC',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',600.00,530.00,NULL,45,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(10,2,NULL,'Reegain Fish Growth Supplement 1kg','reegain-fish-growth-supplement-1kg','CEQIYG4G',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',650.00,590.00,NULL,70,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(11,1,NULL,'Cal-D-Phos Cattle Calcium Syrup 1L','cal-d-phos-cattle-calcium-syrup-1l','PGKXSCHR',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',420.00,380.00,NULL,80,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(12,1,NULL,'Vet-Mineral Premix 500g','vet-mineral-premix-500g','JLVJOLVO',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',350.00,310.00,NULL,90,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(13,2,NULL,'Fish-Vita Vitamin Premix 250g','fish-vita-vitamin-premix-250g','DC5C7WBG',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',280.00,240.00,NULL,50,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(14,3,NULL,'Super CFC Plus 3kg Big Saver Pack','super-cfc-plus-3kg-big-saver-pack','QVXLURXQ',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',2000.00,1850.00,NULL,25,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(15,1,NULL,'Cattle Liver Tonic 1L','cattle-liver-tonic-1l','9KMHHLG4',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',480.00,420.00,NULL,34,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(16,2,NULL,'Pond Oxygen Tablets 1kg','pond-oxygen-tablets-1kg','9PZNUB7H',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',520.00,460.00,NULL,60,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(17,2,NULL,'Aqua Clean Pond Sanitizer 500ml','aqua-clean-pond-sanitizer-500ml','3KL2F5EV',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',390.00,340.00,NULL,40,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(18,3,NULL,'Biofit CFC Plus Feed Supplement 2kg','biofit-cfc-plus-feed-supplement-2kg','GWQRCHHY',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',1500.00,1380.00,NULL,20,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(19,1,NULL,'Poultry Growth Booster 1kg','poultry-growth-booster-1kg','80CPTGDP',NULL,'100% genuine quality product for your farm.','products/cfc_plus.png',580.00,510.00,NULL,55,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(20,1,NULL,'Mastitis Care Teat Spray 250ml','mastitis-care-teat-spray-250ml','DIJMGZZV',NULL,'100% genuine quality product for your farm.','products/mastitis.png',320.00,280.00,NULL,64,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 09:26:33'),(21,1,NULL,'Dewormer Vet Bolus 10 Tablets','dewormer-vet-bolus-10-tablets','YNEDQIG9',NULL,'100% genuine quality product for your farm.','products/dewormer.png',220.00,190.00,NULL,100,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(22,2,NULL,'Fish Plankton Growth Enhancer 1kg','fish-plankton-growth-enhancer-1kg','EIUZG2OE',NULL,'100% genuine quality product for your farm.','products/plankton.png',720.00,650.00,NULL,40,'active',1,1,1,'2026-10-01 09:59:15','2026-10-05 08:31:09'),(23,2,1,'BioAqua Emergency Oxygen Powder 500g','bio-aqua-plus-feed-attractant-500g','RKOKNIRV',NULL,'xcccccccccccccccccccccccccc','products/oxygen_powder.png',340.00,350.00,NULL,497,'active',1,1,0,'2026-10-01 09:59:15','2026-10-05 10:16:04');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('3eOTXdO8vpQ01uNlo8gdBXVW5uctfKuD6aEJguck',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZWp0a3dTWXVsVnFaWElYcjdaTk43UllxSTVuUjhuWmFIcVB3R1VERSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1791270916),('6rD2C8tW9Zbx6HpaerirnfTP92IHNggrw3y8qral',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoic2JyTWh1WEhCV1IzN3NKMGFYc24ydWtZTFFVdlpQTUlPeTIzTkJDdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJ0L2NvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1791195658),('DwbPGFvNeV6VfXPV1IkTqGOwrlRUGdUvWGNfjUnT',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoib1Y4MkxnQzRwb1BQN1JDRnpwQndnZDV4RERKYVVBNGd2YUxPMGs4eCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9vcmRlcnMvOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1791195777),('RBhT4mzSyrmv2UXujo5JnBVyVK4oGAk1gGOWWGGm',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZnQ3QnFwZnJaeDZwT0plaWFMcFZ3ckdpdTdWWmpjTFBVZzl4Z1BlVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJ0L2NvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1791271120),('tBvAnO8VMnCpLpEBIRbm6ZPmjGP9JJm8QfZdqSc0',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNm5PWUJnUGVHeE94R2x3b21UODY5NjZSdkJXUWRYR1FDQzlkOXNBRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1791187332),('ULxCJg2P31KWQBwdMkf4AHrFxkuJAYcikLyV8dD0',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655','YTozOntzOjY6Il90b2tlbiI7czo0MDoibjhsWndOdFMxN1ExU2tyejVMU0pGVU9RNTJvR1cxb0lDSTB6NU9KayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1791185904),('xydZuPvQxHj8wT4jrAsNPhwBCX7OFJSAzDiQftW1',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2s4V2NBWlNpYzNSUWFwalI4SUpZQmFxYzFESnhicVBxbjhCcGd6SSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fX0=',1791188035),('yFqf9YQPUrAYV5H8Wj7unEvgckZPxqerAmPmo3Wj',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2FPMDRuUU83MUdlT05ONEZVaDVQWDU4WFM0VmJXQkVveEJneU9iZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1791190611);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_name','খাঁটি বাজার (Khati Bazar)','general','Website Name','text','2026-10-01 06:09:32','2026-10-01 06:09:32'),(2,'phone','01711-000000','contact','Phone Number','text','2026-10-01 06:09:32','2026-10-01 06:09:32'),(3,'email','info@khatibajar.com','contact','Email Address','text','2026-10-01 06:09:32','2026-10-01 06:09:32'),(4,'address','ঢাকা, বাংলাদেশ','contact','Full Address','textarea','2026-10-01 06:09:32','2026-10-01 06:09:32'),(5,'inside_dhaka_charge','70','shipping','Inside Dhaka Delivery Charge (BDT)','number','2026-10-01 06:09:32','2026-10-01 06:09:32'),(6,'outside_dhaka_charge','130','shipping','Outside Dhaka Delivery Charge (BDT)','number','2026-10-01 06:09:32','2026-10-01 06:09:32'),(7,'currency_symbol','৳','general','Currency Symbol','text','2026-10-01 06:09:32','2026-10-01 06:09:32'),(8,'facebook_url','https://facebook.com/khatibajar','social','Facebook Page URL','text','2026-10-01 06:09:32','2026-10-01 06:09:32'),(9,'youtube_url','https://youtube.com/khatibajar','social','YouTube Channel URL','text','2026-10-01 06:09:32','2026-10-01 06:09:32'),(10,'whatsapp_number','8801711000000','social','WhatsApp Number','text','2026-10-01 06:09:32','2026-10-01 06:09:32'),(11,'bkash_status','1','payment','bKash Status','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(12,'bkash_number','01711-000000','payment','bKash Number','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(13,'bkash_type','Personal','payment','bKash Account Type','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(14,'bkash_instruction','bKash Personal নম্বর 01711-000000 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।','payment','bKash Instructions','textarea','2026-10-01 10:28:55','2026-10-01 10:28:55'),(15,'nagad_status','1','payment','Nagad Status','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(16,'nagad_number','01822-000000','payment','Nagad Number','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(17,'nagad_type','Personal','payment','Nagad Account Type','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(18,'nagad_instruction','Nagad Personal নম্বর 01822-000000 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।','payment','Nagad Instructions','textarea','2026-10-01 10:28:55','2026-10-01 10:28:55'),(19,'rocket_status','1','payment','Rocket Status','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(20,'rocket_number','01933-000000-8','payment','Rocket Number','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(21,'rocket_type','Personal','payment','Rocket Account Type','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(22,'rocket_instruction','Rocket Personal নম্বর 01933-000000-8 এ Send Money করুন। এরপর প্রেরকের নম্বর, TrxID ও পেমেন্টের স্ক্রিনশট আপলোড করুন।','payment','Rocket Instructions','textarea','2026-10-01 10:28:55','2026-10-01 10:28:55'),(23,'cod_status','1','payment','COD Status','text','2026-10-01 10:28:55','2026-10-01 10:28:55'),(24,'cod_instruction','পণ্য বুঝে পেয়ে ক্যাশ টাকা পরিশোধ করুন।','payment','COD Instructions','textarea','2026-10-01 10:28:55','2026-10-01 10:28:55');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Khati Bazar Admin','admin@khatibajar.com','01711112222','admin','active',NULL,'$2y$12$KIEpWrsU6KX/eQCItVVuAul8Yl1EIKKkcMCfClk00lJOopNWP/mxG',NULL,'2026-10-01 06:09:32','2026-10-05 07:59:55'),(2,'Demo Customer','customer@khatibajar.com','01700000000','customer','active',NULL,'$2y$12$.OnM/LtPqojyQvXA4j9U9OT4gcFEtHD5g7EzfswFXLLUjYjzSv9jC',NULL,'2026-10-01 06:09:32','2026-10-05 07:59:55'),(3,'Monika','monika@khatibajar.com','01722223333','admin','active',NULL,'$2y$12$7wF1TyzxFelAr4rCVKGC1u3c3U9NsNyqrEbb36otgjx2MXz7/YhPi',NULL,'2026-10-01 07:07:42','2026-10-05 07:59:55');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-07 11:11:25
