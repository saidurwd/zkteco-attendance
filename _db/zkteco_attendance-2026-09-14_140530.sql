/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for osx10.19 (x86_64)
--
-- Host: localhost    Database: zkteco_attendance
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

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

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

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

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

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

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

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

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

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

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_09_14_073204_01_create_zk_devices_table',1),
(5,'2026_09_14_073204_02_create_zk_attendance_logs_table',1),
(6,'2026_09_14_073204_03_create_zk_raw_requests_table',1),
(7,'2026_09_14_073204_04_create_zk_device_commands_table',1),
(8,'2026_09_14_073204_05_create_zk_employee_mappings_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

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

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

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

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sessions` VALUES
('9jKFKrCKn3Z2dheEz1d73EaNgEXTzaqBpLHz4vcR',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:155.0) Gecko/20100101 Firefox/155.0','eyJfdG9rZW4iOiIxVUtRQXI1NkVSNDduZER3NmMwUkQwOXlUbXkxWlc2UWkxU0pDUHZCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3prdGVjby1hdHRlbmRhbmNlLnRlc3RcL3prdGVjb1wvZGV2aWNlcyIsInJvdXRlIjoiemt0ZWNvLmRldmljZXMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1789372224),
('I0rWNxhngcBUDHpAcc3M6q42tJoq4xVvMSvBDh5J',NULL,'127.0.0.1','PostmanRuntime/7.56.1','eyJfdG9rZW4iOiJPQ0txaUVwVVRUNVoxZ2EzbmFVSHVZeWZZY3o5WTJxQTZIRGFrN1Y1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3prdGVjby1hdHRlbmRhbmNlLnRlc3RcL2ljbG9ja1wvZ2V0cmVxdWVzdD9TTj1URVNUMTIzIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1789372697);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

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
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `zk_attendance_logs`
--

DROP TABLE IF EXISTS `zk_attendance_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `zk_attendance_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` bigint(20) unsigned DEFAULT NULL,
  `serial_number` varchar(100) NOT NULL,
  `employee_pin` varchar(50) NOT NULL,
  `attendance_time` datetime NOT NULL,
  `status` int(10) unsigned DEFAULT NULL,
  `verify_type` int(10) unsigned DEFAULT NULL,
  `work_code` int(10) unsigned DEFAULT NULL,
  `reserved_1` varchar(255) DEFAULT NULL,
  `reserved_2` varchar(255) DEFAULT NULL,
  `raw_data` text DEFAULT NULL,
  `source` varchar(50) NOT NULL DEFAULT 'zkteco_push',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zk_attendance_logs_device_id_foreign` (`device_id`),
  KEY `zk_att_logs_sn_pin_time_idx` (`serial_number`,`employee_pin`,`attendance_time`),
  KEY `zk_att_logs_time_idx` (`attendance_time`),
  KEY `zk_att_logs_pin_idx` (`employee_pin`),
  CONSTRAINT `zk_attendance_logs_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `zk_devices` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zk_attendance_logs`
--

LOCK TABLES `zk_attendance_logs` WRITE;
/*!40000 ALTER TABLE `zk_attendance_logs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `zk_attendance_logs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `zk_device_commands`
--

DROP TABLE IF EXISTS `zk_device_commands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `zk_device_commands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` bigint(20) unsigned NOT NULL,
  `command_id` varchar(255) NOT NULL,
  `command` text NOT NULL,
  `status` enum('pending','sent','completed','failed') NOT NULL DEFAULT 'pending',
  `response` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zk_device_commands_command_id_unique` (`command_id`),
  KEY `zk_device_commands_device_id_status_index` (`device_id`,`status`),
  CONSTRAINT `zk_device_commands_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `zk_devices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zk_device_commands`
--

LOCK TABLES `zk_device_commands` WRITE;
/*!40000 ALTER TABLE `zk_device_commands` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `zk_device_commands` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `zk_devices`
--

DROP TABLE IF EXISTS `zk_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `zk_devices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(100) NOT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `device_ip` varchar(45) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `firmware_version` varchar(255) DEFAULT NULL,
  `push_version` varchar(255) DEFAULT NULL,
  `site_code` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zk_devices_serial_number_unique` (`serial_number`),
  KEY `zk_devices_device_ip_index` (`device_ip`),
  KEY `zk_devices_last_seen_at_index` (`last_seen_at`),
  KEY `zk_devices_site_code_index` (`site_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zk_devices`
--

LOCK TABLES `zk_devices` WRITE;
/*!40000 ALTER TABLE `zk_devices` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `zk_devices` VALUES
(1,'TEST123',NULL,'127.0.0.1',NULL,NULL,NULL,NULL,NULL,1,'2026-09-14 07:56:32','{\"SN\":\"TEST123\",\"table\":\"ATTLOG\"}','2026-09-14 07:46:53','2026-09-14 07:56:32');
/*!40000 ALTER TABLE `zk_devices` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `zk_employee_mappings`
--

DROP TABLE IF EXISTS `zk_employee_mappings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `zk_employee_mappings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` bigint(20) unsigned NOT NULL,
  `device_pin` varchar(50) NOT NULL,
  `employee_code` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zk_employee_mappings_device_id_device_pin_unique` (`device_id`,`device_pin`),
  KEY `zk_employee_mappings_employee_code_index` (`employee_code`),
  CONSTRAINT `zk_employee_mappings_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `zk_devices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zk_employee_mappings`
--

LOCK TABLES `zk_employee_mappings` WRITE;
/*!40000 ALTER TABLE `zk_employee_mappings` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `zk_employee_mappings` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `zk_raw_requests`
--

DROP TABLE IF EXISTS `zk_raw_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `zk_raw_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(100) DEFAULT NULL,
  `method` varchar(10) NOT NULL,
  `uri` varchar(500) NOT NULL,
  `query_params` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`query_params`)),
  `headers` longtext DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `remote_ip` varchar(45) DEFAULT NULL,
  `received_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zk_raw_requests_serial_number_index` (`serial_number`),
  KEY `zk_raw_requests_received_at_index` (`received_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zk_raw_requests`
--

LOCK TABLES `zk_raw_requests` WRITE;
/*!40000 ALTER TABLE `zk_raw_requests` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `zk_raw_requests` VALUES
(1,'TEST123','GET','/iclock/cdata?SN=TEST123&options=all&pushver=3.1.2&language=83','{\"SN\":\"TEST123\",\"options\":\"all\",\"pushver\":\"3.1.2\",\"language\":\"83\"}','{\"connection\":[\"keep-alive\"],\"accept-encoding\":[\"gzip, deflate, br\"],\"host\":[\"zkteco-attendance.test\"],\"postman-token\":[\"fb8a6e4c-3729-442a-8f93-c7512ce5ef82\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"PostmanRuntime\\/7.56.1\"]}','','127.0.0.1','2026-09-14 07:46:53','2026-09-14 07:46:53','2026-09-14 07:46:53'),
(2,'TEST123','GET','/iclock/cdata?SN=TEST123&table=ATTLOG','{\"SN\":\"TEST123\",\"table\":\"ATTLOG\"}','{\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IlAwWmsvV0VwZHlPeGxpVUVqT3poYWc9PSIsInZhbHVlIjoidFNQUHRieXZLL0lzNTMzT2t5MnVaVi8xaFp5YVI0SGtsRm95NWI4bklJMDF0UWJLWEFiR0ptZ3B2cEpkcEY3RTVhdDNTREtGZllPWlE3TS9SSFJsaTE4N0VOVUEwZnFkWnFYNWlwU1hOYmlzeEZvc0N5NjJCSDRmNWhkQ1BlVmMiLCJtYWMiOiI5MDQzYWQxZDI0NTE0YzMwZWQ1YTA2NmQxZDFlN2I4YWU2N2EzYjU3ZTZlMDNjYzkzYWZjM2Y5NDdjNzZhMmJiIiwidGFnIjoiIn0%3D; zkteco-attendance-gateway-session=eyJpdiI6IlpYMGQ2VVlJVlVkTGhPbzhSNWdUY0E9PSIsInZhbHVlIjoiNHoybUtwM2RFSmZSTHhaOTNXaVRvZVAwY0E2STdkMmgwcnp1bHlYb3MxZVBPSW9UOVdCQmh3cG5Pa3VtOG90aWh0TTlkWE5nY3N1VmxSczZmelJvOFVaMXk0emw0d053Rml1OHJtNTlxV0wrMFEvNjlRRFczKzhmVWdJWnZQdDYiLCJtYWMiOiJlZGM2M2QwZmJiOGExNDhjYTVlN2M5MDQzMmYwMTlmMGNkMWY2ZmE0NTQ2OTY3NmI3YjVmNDA5MDQ4MGFhMWUxIiwidGFnIjoiIn0%3D\"],\"connection\":[\"keep-alive\"],\"accept-encoding\":[\"gzip, deflate, br\"],\"host\":[\"zkteco-attendance.test\"],\"postman-token\":[\"6251444c-c4a7-41b1-9a35-e4c22904f86c\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"PostmanRuntime\\/7.56.1\"]}','','127.0.0.1','2026-09-14 07:56:32','2026-09-14 07:56:32','2026-09-14 07:56:32');
/*!40000 ALTER TABLE `zk_raw_requests` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-09-14 14:05:31
