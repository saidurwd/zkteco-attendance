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
-- Table structure for table `attendance_logs`
--

DROP TABLE IF EXISTS `attendance_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(100) NOT NULL,
  `device_id` bigint(20) unsigned DEFAULT NULL,
  `attendance_time` datetime NOT NULL,
  `attendance_type` varchar(30) DEFAULT NULL,
  `verify_mode` varchar(50) DEFAULT NULL,
  `source` varchar(30) NOT NULL DEFAULT 'HIKVISION',
  `hikvision_event_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_logs_hikvision_event_id_unique` (`hikvision_event_id`),
  KEY `attendance_logs_employee_no_attendance_time_index` (`employee_no`,`attendance_time`),
  KEY `attendance_logs_device_id_attendance_time_index` (`device_id`,`attendance_time`),
  CONSTRAINT `attendance_logs_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `hikvision_devices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendance_logs_hikvision_event_id_foreign` FOREIGN KEY (`hikvision_event_id`) REFERENCES `hikvision_events` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance_logs`
--

LOCK TABLES `attendance_logs` WRITE;
/*!40000 ALTER TABLE `attendance_logs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `attendance_logs` VALUES
(1,'EMP001',NULL,'2026-09-15 08:10:00','checkIn','face','HIKVISION',6,'2026-09-15 03:17:20','2026-09-15 03:17:20'),
(2,'EMP001',NULL,'2026-09-15 18:05:00','checkOut','face','HIKVISION',7,'2026-09-15 03:17:31','2026-09-15 03:17:31'),
(3,'40065',NULL,'2026-09-15 08:18:00','checkOut','face','HIKVISION',8,'2026-09-15 03:24:40','2026-09-15 03:24:40');
/*!40000 ALTER TABLE `attendance_logs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

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
INSERT INTO `cache` VALUES
('zkteco-attendance-gateway-cache-admin@example.com|127.0.0.1','i:1;',1789385681),
('zkteco-attendance-gateway-cache-admin@example.com|127.0.0.1:timer','i:1789385681;',1789385681);
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
-- Table structure for table `hikvision_devices`
--

DROP TABLE IF EXISTS `hikvision_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `hikvision_devices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_name` varchar(100) NOT NULL,
  `device_serial` varchar(100) DEFAULT NULL,
  `device_model` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password_encrypted` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_event_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hikvision_devices_device_serial_unique` (`device_serial`),
  KEY `hikvision_devices_is_active_index` (`is_active`),
  KEY `hikvision_devices_ip_address_index` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hikvision_devices`
--

LOCK TABLES `hikvision_devices` WRITE;
/*!40000 ALTER TABLE `hikvision_devices` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `hikvision_devices` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `hikvision_events`
--

DROP TABLE IF EXISTS `hikvision_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `hikvision_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` bigint(20) unsigned DEFAULT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `event_state` varchar(50) DEFAULT NULL,
  `event_time` datetime DEFAULT NULL,
  `employee_no` varchar(100) DEFAULT NULL,
  `employee_name` varchar(150) DEFAULT NULL,
  `card_no` varchar(100) DEFAULT NULL,
  `major_event_type` int(11) DEFAULT NULL,
  `sub_event_type` int(11) DEFAULT NULL,
  `attendance_status` varchar(50) DEFAULT NULL,
  `verify_mode` varchar(50) DEFAULT NULL,
  `serial_no` bigint(20) DEFAULT NULL,
  `raw_payload` longtext NOT NULL,
  `payload_format` enum('XML','JSON','UNKNOWN') NOT NULL DEFAULT 'UNKNOWN',
  `processing_status` enum('PENDING','PROCESSED','FAILED','IGNORED') NOT NULL DEFAULT 'PENDING',
  `processing_message` text DEFAULT NULL,
  `received_at` datetime NOT NULL,
  `processed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hikvision_events_device_id_serial_no_unique` (`device_id`,`serial_no`),
  KEY `hikvision_events_employee_no_index` (`employee_no`),
  KEY `hikvision_events_event_time_index` (`event_time`),
  KEY `hikvision_events_processing_status_index` (`processing_status`),
  KEY `hikvision_events_serial_no_index` (`serial_no`),
  CONSTRAINT `hikvision_events_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `hikvision_devices` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hikvision_events`
--

LOCK TABLES `hikvision_events` WRITE;
/*!40000 ALTER TABLE `hikvision_events` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `hikvision_events` VALUES
(1,NULL,'AccessControllerEvent','active','2026-09-15 08:10:00','EMP001','Test Employee','',5,75,'checkIn','face',100001,'{\n    \"EventNotificationAlert\": {\n      \"ipAddress\": \"192.168.1.50\",\n      \"dateTime\": \"2026-09-15T08:10:00+06:00\",\n      \"eventType\": \"AccessControllerEvent\",\n      \"eventState\": \"active\",\n      \"AccessControllerEvent\": {\n        \"employeeNoString\": \"EMP001\",\n        \"name\": \"Test Employee\",\n        \"cardNo\": \"\",\n        \"majorEventType\": 5,\n        \"subEventType\": 75,\n        \"attendanceStatus\": \"checkIn\",\n        \"currentVerifyMode\": \"face\",\n        \"serialNo\": 100001\n      }\n    }\n  }','JSON','PENDING',NULL,'2026-09-15 08:27:10',NULL,'2026-09-15 02:27:10','2026-09-15 02:27:10'),
(2,NULL,'AccessControllerEvent',NULL,NULL,'EMP002','Test Employee 2',NULL,NULL,NULL,'checkOut','face',200001,'<EventNotificationAlert><eventType>AccessControllerEvent</eventType><AccessControllerEvent><employeeNoString>EMP002</employeeNoString><name>Test Employee 2</name><attendanceStatus>checkOut</attendanceStatus><currentVerifyMode>face</currentVerifyMode><serialNo>200001</serialNo></AccessControllerEvent></EventNotificationAlert>','XML','PENDING',NULL,'2026-09-15 08:30:47',NULL,'2026-09-15 02:30:47','2026-09-15 02:30:47'),
(3,NULL,'AccessControllerEvent','active','2026-09-15 08:15:00','EMP001','Test Employee','',5,75,'checkIn','face',100002,'{\n    \"EventNotificationAlert\": {\n        \"ipAddress\": \"192.168.1.50\",\n        \"dateTime\": \"2026-09-15T08:15:00+06:00\",\n        \"eventType\": \"AccessControllerEvent\",\n        \"eventState\": \"active\",\n        \"AccessControllerEvent\": {\n            \"employeeNoString\": \"EMP001\",\n            \"name\": \"Test Employee\",\n            \"cardNo\": \"\",\n            \"majorEventType\": 5,\n            \"subEventType\": 75,\n            \"attendanceStatus\": \"checkIn\",\n            \"currentVerifyMode\": \"face\",\n            \"serialNo\": 100002\n        }\n    }\n}','JSON','PENDING',NULL,'2026-09-15 08:56:27',NULL,'2026-09-15 02:56:27','2026-09-15 02:56:27'),
(4,NULL,'AccessControllerEvent','active','2026-09-15 08:15:00','40065','Saidur Rahman','',5,75,'checkIn','face',100002,'{\n    \"EventNotificationAlert\": {\n        \"ipAddress\": \"192.168.1.50\",\n        \"dateTime\": \"2026-09-15T08:15:00+06:00\",\n        \"eventType\": \"AccessControllerEvent\",\n        \"eventState\": \"active\",\n        \"AccessControllerEvent\": {\n            \"employeeNoString\": \"40065\",\n            \"name\": \"Saidur Rahman\",\n            \"cardNo\": \"\",\n            \"majorEventType\": 5,\n            \"subEventType\": 75,\n            \"attendanceStatus\": \"checkIn\",\n            \"currentVerifyMode\": \"face\",\n            \"serialNo\": 100002\n        }\n    }\n}','JSON','PENDING',NULL,'2026-09-15 08:57:07',NULL,'2026-09-15 02:57:07','2026-09-15 02:57:07'),
(5,NULL,'AccessControllerEvent','active','2026-09-15 08:15:00','40065','Saidur Rahman','',5,75,'checkIn','face',100002,'{\n    \"EventNotificationAlert\": {\n        \"ipAddress\": \"192.168.1.50\",\n        \"dateTime\": \"2026-09-15T08:15:00+06:00\",\n        \"eventType\": \"AccessControllerEvent\",\n        \"eventState\": \"active\",\n        \"AccessControllerEvent\": {\n            \"employeeNoString\": \"40065\",\n            \"name\": \"Saidur Rahman\",\n            \"cardNo\": \"\",\n            \"majorEventType\": 5,\n            \"subEventType\": 75,\n            \"attendanceStatus\": \"checkIn\",\n            \"currentVerifyMode\": \"face\",\n            \"serialNo\": 100002\n        }\n    }\n}','JSON','PENDING',NULL,'2026-09-15 08:57:08',NULL,'2026-09-15 02:57:08','2026-09-15 02:57:08'),
(6,NULL,'AccessControllerEvent','active','2026-09-15 08:10:00','EMP001','Test Employee','',5,75,'checkIn','face',300001,'{\n    \"EventNotificationAlert\": {\n      \"ipAddress\": \"192.168.1.50\",\n      \"dateTime\": \"2026-09-15T08:10:00+06:00\",\n      \"eventType\": \"AccessControllerEvent\",\n      \"eventState\": \"active\",\n      \"AccessControllerEvent\": {\n        \"employeeNoString\": \"EMP001\",\n        \"name\": \"Test Employee\",\n        \"cardNo\": \"\",\n        \"majorEventType\": 5,\n        \"subEventType\": 75,\n        \"attendanceStatus\": \"checkIn\",\n        \"currentVerifyMode\": \"face\",\n        \"serialNo\": 300001\n      }\n    }\n  }','JSON','PROCESSED',NULL,'2026-09-15 09:17:19','2026-09-15 09:17:20','2026-09-15 03:17:19','2026-09-15 03:17:20'),
(7,NULL,'AccessControllerEvent','active','2026-09-15 18:05:00','EMP001','Test Employee','',5,75,'checkOut','face',300002,'{\n    \"EventNotificationAlert\": {\n      \"ipAddress\": \"192.168.1.50\",\n      \"dateTime\": \"2026-09-15T18:05:00+06:00\",\n      \"eventType\": \"AccessControllerEvent\",\n      \"eventState\": \"active\",\n      \"AccessControllerEvent\": {\n        \"employeeNoString\": \"EMP001\",\n        \"name\": \"Test Employee\",\n        \"cardNo\": \"\",\n        \"majorEventType\": 5,\n        \"subEventType\": 75,\n        \"attendanceStatus\": \"checkOut\",\n        \"currentVerifyMode\": \"face\",\n        \"serialNo\": 300002\n      }\n    }\n  }','JSON','PROCESSED',NULL,'2026-09-15 09:17:31','2026-09-15 09:17:31','2026-09-15 03:17:31','2026-09-15 03:17:31'),
(8,NULL,'AccessControllerEvent','active','2026-09-15 08:18:00','40065','Saidur Rahman','',5,75,'checkOut','face',100002,'{\n    \"EventNotificationAlert\": {\n        \"ipAddress\": \"192.168.1.50\",\n        \"dateTime\": \"2026-09-15T08:18:00+06:00\",\n        \"eventType\": \"AccessControllerEvent\",\n        \"eventState\": \"active\",\n        \"AccessControllerEvent\": {\n            \"employeeNoString\": \"40065\",\n            \"name\": \"Saidur Rahman\",\n            \"cardNo\": \"\",\n            \"majorEventType\": 5,\n            \"subEventType\": 75,\n            \"attendanceStatus\": \"checkOut\",\n            \"currentVerifyMode\": \"face\",\n            \"serialNo\": 100002\n        }\n    }\n}','JSON','PROCESSED',NULL,'2026-09-15 09:24:39','2026-09-15 09:24:40','2026-09-15 03:24:39','2026-09-15 03:24:40');
/*!40000 ALTER TABLE `hikvision_events` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
(8,'2026_09_14_073204_05_create_zk_employee_mappings_table',1),
(9,'2026_09_14_211642_add_profile_picture_to_users_table',2),
(10,'2026_09_15_000000_create_zk_employees_table',3),
(11,'2026_09_15_000001_simplify_zk_employees_name',4),
(12,'2026_09_15_020001_create_hikvision_devices_table',5),
(13,'2026_09_15_020002_create_hikvision_events_table',5),
(14,'2026_09_15_020003_create_attendance_logs_table',5);
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
('69obzWro7SHm9vrssfRdDaSAWF22LGE83hWp6YsY',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:155.0) Gecko/20100101 Firefox/155.0','eyJfdG9rZW4iOiJpaFM2ZXRVeGFDRTgwZ1VVMnBpMm1PUXJLcE53Z29RbDJHTTNZcjUyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3prdGVjby1hdHRlbmRhbmNlLnRlc3RcL2hpa3Zpc2lvblwvcmVwb3J0c1wvYXR0ZW5kYW5jZSIsInJvdXRlIjoiaGlrdmlzaW9uLnJlcG9ydHMuYXR0ZW5kYW5jZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJhdXRoIjp7InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI6MTc4OTQzOTk2MX19',1789442752),
('7CzcFHWOY3Uc8mpePaiiZMg4rW1O8ZLiUBiZJWYz',1,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJiMlpLWDN0UjN1clk1TzhYMkRWWTJudXcyVERRMWRjcTRkazUzWUl4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC96a3RlY29cL3JlcG9ydHNcL2F0dGVuZGFuY2UiLCJyb3V0ZSI6InprdGVjby5yZXBvcnRzLmF0dGVuZGFuY2UifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiYXV0aCI6eyJwYXNzd29yZF9jb25maXJtZWRfYXQiOjE3ODk0MzkyNTh9fQ==',1789442434),
('ceuFr8Rr4VJGCHOIkXRLUEOcGG2c1oXzkorcRVok',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:155.0) Gecko/20100101 Firefox/155.0','eyJfdG9rZW4iOiJIMGpEeFQyazhqWE4zQmVmOENZTEROU01vOHc0Q0VlNnJRNFpDT1BLIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvemt0ZWNvLWF0dGVuZGFuY2UudGVzdFwvaG9tZSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvemt0ZWNvLWF0dGVuZGFuY2UudGVzdFwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1789438832),
('efYXRMBCjTSGOAGIDOZQQgzyx40tpPQ0fCnt1X6m',1,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiIwZjN4YlY3Z01VOG56VFE2Y3drYzhUa1B0Wms4dHNtc01yQ2NwNWVuIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC96a3RlY29cL3JlcG9ydHNcL2F0dGVuZGFuY2UiLCJyb3V0ZSI6InprdGVjby5yZXBvcnRzLmF0dGVuZGFuY2UifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiYXV0aCI6eyJwYXNzd29yZF9jb25maXJtZWRfYXQiOjE3ODk0MzI5MjV9fQ==',1789435523),
('kQgzAUgZXyuKuxrBmnVKPpDwmGT8O7ffDRbAo0LV',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:155.0) Gecko/20100101 Firefox/155.0','eyJfdG9rZW4iOiIyRElwNEJwaFAzUHRNdVlyQVUxTFdCakNIWENoNHp2NkNYWDJJWjRVIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3prdGVjby1hdHRlbmRhbmNlLnRlc3RcL2hvbWUiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiYXV0aCI6eyJwYXNzd29yZF9jb25maXJtZWRfYXQiOjE3ODk0MzEwNjh9fQ==',1789438755),
('XWz85iJoeAVyJUYQ6ivmDUPLrhMBnzF8fZOxJCsc',1,'127.0.0.1','curl/8.7.1','eyJfdG9rZW4iOiJIWmNqSDZDNnVHZnl5SzNjV1RHbU5JbTVkSEM3OU5YUGRmR2IwVHk5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC96a3RlY29cL2VtcGxveWVlcyIsInJvdXRlIjoiemt0ZWNvLmVtcGxveWVlcy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJhdXRoIjp7InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI6MTc4OTQzMjM3NX19',1789432470);
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
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Saidur Rahman','engsaidur@gmail.com',NULL,'$2y$12$2lZhE4fvILsj3CG0jXhUre9F1C8laHdKxWguPl7yMhE72b.6uZnsy','jDMbk8Jsem31GlULSxnLmwEq3CRNS6DL9TC4j5AxcWtJcYUHHSyndN5qEtvi','profile_pictures/Z1PuJROmIo3xxce1NaFpJ9DDLMBvKj7GLusivNwT.png','2026-09-14 11:15:43','2026-09-14 15:43:18');
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
(1,'TEST123',NULL,'127.0.0.1',NULL,NULL,NULL,NULL,NULL,1,'2026-09-14 07:56:32','{\"SN\":\"TEST123\",\"table\":\"ATTLOG\"}','2026-09-14 07:46:53','2026-09-15 00:46:45');
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
-- Table structure for table `zk_employees`
--

DROP TABLE IF EXISTS `zk_employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `zk_employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `site_code` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zk_employees_employee_id_unique` (`employee_id`),
  KEY `zk_employees_site_code_index` (`site_code`),
  KEY `zk_employees_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zk_employees`
--

LOCK TABLES `zk_employees` WRITE;
/*!40000 ALTER TABLE `zk_employees` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `zk_employees` VALUES
(1,'40065','S M Saidur Rahman','saidur.rahman@duncanbd.com','1','Manager IT','1',1,'{\"source_id\":1,\"phone\":\"+8801911731214\",\"joining_date\":null,\"department_id\":1,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(2,'40094','MOHAMMAD MIZANUR RAHMAN','mizanur.rahman@duncanbd.com','2','Finance Director','1',1,'{\"source_id\":2,\"phone\":\"01310115699\",\"joining_date\":null,\"department_id\":2,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(3,'00139','MD. FAHMIDUL ISLAM','fahmidul.islam@duncanbd.com','6','Sr Manager','1',1,'{\"source_id\":3,\"phone\":null,\"joining_date\":null,\"department_id\":6,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(4,'40028','Nazmul Alam','nazmul.alam@duncanbd.com','5','Assistant Manager','1',1,'{\"source_id\":4,\"phone\":null,\"joining_date\":null,\"department_id\":5,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(5,'00020','M. SHAHAB UDDIN','shahab.uddin@duncanbd.com',NULL,'CONSULTANT','1',1,'{\"source_id\":150,\"phone\":\"01303104401\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(6,'00053','ZAHIRUL ISLAM','zahirul.islam@duncanbd.com',NULL,'MANAGER','2',1,'{\"source_id\":151,\"phone\":\"01711539251\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(7,'00059','KAMRUZZAMAN','kamruzzaman@duncanbd.com',NULL,'GENERAL MANAGER','3',1,'{\"source_id\":152,\"phone\":\"01711922813\",\"joining_date\":null,\"department_id\":null,\"location_id\":3}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(8,'00061','MD. IFTEKHER ENAM','iftekher.enam@duncanbd.com',NULL,'DEPUTY GENERAL MANAGER','8',1,'{\"source_id\":153,\"phone\":\"01711922457\",\"joining_date\":null,\"department_id\":null,\"location_id\":8}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(9,'00085','DR. MD. AMINUL   ISLAM','draminul64@gmail.com',NULL,'MANAGER','3',1,'{\"source_id\":154,\"phone\":\"01711810740\",\"joining_date\":null,\"department_id\":null,\"location_id\":3}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(10,'00118','DR. A. B. M. ANWARUL HUQUE','anwarul.haque@duncanbd.com',NULL,'MANAGER','17',1,'{\"source_id\":155,\"phone\":\"01711819889\",\"joining_date\":null,\"department_id\":null,\"location_id\":17}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(11,'00125','SUBHANKAR CHANDRA NAG','subhankar.nag@duncanbd.com',NULL,'MANAGER','5',1,'{\"source_id\":156,\"phone\":\"01715003537\",\"joining_date\":null,\"department_id\":null,\"location_id\":5}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(12,'00138','A.T.M. SIDDIQUR RAHMAN','siddiqur.rahman@duncanbd.com',NULL,'MANAGER','6',1,'{\"source_id\":157,\"phone\":\"01711539250\",\"joining_date\":null,\"department_id\":null,\"location_id\":6}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(13,'00146','MOKBUL HAIDER','mokbul.haider@duncanbd.com',NULL,'MANAGER','16',1,'{\"source_id\":158,\"phone\":\"01711922815\",\"joining_date\":null,\"department_id\":null,\"location_id\":16}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(14,'00147','M. A. TAREQ','m.a.tareqmostaq@gmail.com',NULL,'Executive Asst','2',1,'{\"source_id\":159,\"phone\":\"01712516508\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(15,'00157','MD. MASUM A. C1WDHURY','masum.c1wdhury@duncanbd.com',NULL,'MANAGER','9',1,'{\"source_id\":160,\"phone\":\"01711922459\",\"joining_date\":null,\"department_id\":null,\"location_id\":9}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(16,'00185','M. TARIF AHMED','tarif.ahmed@duncanbd.com',NULL,'MANAGER','14',1,'{\"source_id\":161,\"phone\":\"01711922819\",\"joining_date\":null,\"department_id\":null,\"location_id\":14}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(17,'00187','MASADUZZAMAN SHEIKH','masaduzzaman.sheikh@duncanbd.com',NULL,'ACTING MANAGER','13',1,'{\"source_id\":162,\"phone\":\"01711922814\",\"joining_date\":null,\"department_id\":null,\"location_id\":13}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(18,'00202','KAZI GOLAM AZAM','kazi.azam@duncanbd.com',NULL,'ACTING MANAGER','7',1,'{\"source_id\":163,\"phone\":\"01711135988\",\"joining_date\":null,\"department_id\":null,\"location_id\":7}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(19,'00203','TAUHID. A. C1WDHURY','tauhid.c1wdhury@duncanbd.com',NULL,'SENIOR DEPUTY MANAGER','11',1,'{\"source_id\":164,\"phone\":\"01711364113\",\"joining_date\":null,\"department_id\":null,\"location_id\":11}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(20,'00208','A. KALAM PRAMANIK','abul.pramanik@duncanbd.com',NULL,'MANAGER','15',1,'{\"source_id\":165,\"phone\":\"01711922817\",\"joining_date\":null,\"department_id\":null,\"location_id\":15}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(21,'00209','MD. SALEQ  MAHMUD','saleq.mahmud@duncanbd.com',NULL,'SENIOR DEPUTY MANAGER','10',1,'{\"source_id\":166,\"phone\":\"01711476562\",\"joining_date\":null,\"department_id\":null,\"location_id\":10}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(22,'00212','S.M. SHAFIUL ALAM','shafiul.alam@duncanbd.com',NULL,'SENIOR DEPUTY MANAGER','2',1,'{\"source_id\":167,\"phone\":\"01711102480\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(23,'00235','MD. SAZZADUL HAQUE','sazzadul.haque@duncanbd.com',NULL,'SENIOR DEPUTY MANAGER','12',1,'{\"source_id\":168,\"phone\":\"01711390153\",\"joining_date\":null,\"department_id\":null,\"location_id\":12}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(24,'00236','RAFIQUL ISLAM','rafiqul.islam@duncanbd.com',NULL,'SENIOR DEPUTY MANAGER','6',1,'{\"source_id\":169,\"phone\":\"01711164086\",\"joining_date\":null,\"department_id\":null,\"location_id\":6}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(25,'00238','ARUP CHAKMA','arup.chakma@duncanbd.com',NULL,'SENIOR DEPUTY MANAGER','14',1,'{\"source_id\":170,\"phone\":\"01796958441\",\"joining_date\":null,\"department_id\":null,\"location_id\":14}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(26,'00240','MD. MOKARRAM 1SSAIN','mokarram.1ssain@duncanbd.com',NULL,'SENIOR DEPUTY MANAGER','5',1,'{\"source_id\":171,\"phone\":\"01774390722\",\"joining_date\":null,\"department_id\":null,\"location_id\":5}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(27,'00255','MD. FORHAD RAHMAN','forhad.rahman@duncanbd.com',NULL,'DEPUTY MANAGER','10',1,'{\"source_id\":172,\"phone\":\"01715155509\",\"joining_date\":null,\"department_id\":null,\"location_id\":10}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(28,'00266','IMTIAZ MANNAN','imtiaz.mannan@duncanbd.com',NULL,'DEPUTY MANAGER','16',1,'{\"source_id\":173,\"phone\":\"01711312855\",\"joining_date\":null,\"department_id\":null,\"location_id\":16}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(29,'00269','PROBIR KUMAR HALDER','prodip.chakraborty@duncanbd.com',NULL,'DEPUTY MANAGER','15',1,'{\"source_id\":174,\"phone\":\"01884554575\",\"joining_date\":null,\"department_id\":null,\"location_id\":15}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(30,'00285','SHEIKH MODABBIR HUSSAIN   HUSSAIN','modabbir.hussain@duncanbd.com',NULL,'DEPUTY MANAGER','11',1,'{\"source_id\":175,\"phone\":\"01721223892\",\"joining_date\":null,\"department_id\":null,\"location_id\":11}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(31,'00292','MOMINOL ISLAM','mominol.islam@duncanbd.com',NULL,'DEPUTY MANAGER','1',1,'{\"source_id\":176,\"phone\":\"01819818989\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(32,'00293','MD. HUMAYUN RASHID','humayun.rashid@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":177,\"phone\":\"01819103332\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(33,'00299','ADIL AHAMED','adil.ahamed@duncanbd.com',NULL,'DEPUTY MANAGER','13',1,'{\"source_id\":178,\"phone\":\"01756426448\",\"joining_date\":null,\"department_id\":null,\"location_id\":13}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(34,'00307','PLABON ROY','plabon.roy@duncanbd.com',NULL,'DEPUTY MANAGER','4',1,'{\"source_id\":179,\"phone\":\"01712682694\",\"joining_date\":null,\"department_id\":null,\"location_id\":4}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(35,'00320','SYED MD. SAIFUL ISLAM','md.saiful.islam@duncanbd.com',NULL,'DEPUTY MANAGER','8',1,'{\"source_id\":180,\"phone\":\"01712700391\",\"joining_date\":null,\"department_id\":null,\"location_id\":8}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(36,'00327','MOHAMMAD MONIR 1SSAIN','monir.1ssain@duncanbd.com',NULL,'DEPUTY MANAGER','15',1,'{\"source_id\":181,\"phone\":\"01914913796\",\"joining_date\":null,\"department_id\":null,\"location_id\":15}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(37,'00330','A.K.M. WASIF HUQ','wasif.huq@duncanbd.com',NULL,'DEPUTY MANAGER','3',1,'{\"source_id\":182,\"phone\":\"01715749360\",\"joining_date\":null,\"department_id\":null,\"location_id\":3}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(38,'00332','TARAK MAHMOOD','tarak.mahmood@duncanbd.com',NULL,'DEPUTY MANAGER','4',1,'{\"source_id\":183,\"phone\":\"01760277427\",\"joining_date\":null,\"department_id\":null,\"location_id\":4}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(39,'00334','SHAMIM AHMED','shamim.ahmed@duncanbd.com',NULL,'DEPUTY MANAGER','3',1,'{\"source_id\":184,\"phone\":\"01724187562\",\"joining_date\":null,\"department_id\":null,\"location_id\":3}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(40,'00336','MD. IQBAL 1SSAIN','iqbal.1ssain@duncanbd.com',NULL,'DEPUTY MANAGER','9',1,'{\"source_id\":185,\"phone\":\"01710277534\",\"joining_date\":null,\"department_id\":null,\"location_id\":9}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(41,'00338','MD. ASFAZUL ALAM','asfazul.alam@duncanbd.com',NULL,'DEPUTY MANAGER','6',1,'{\"source_id\":186,\"phone\":\"01712615616\",\"joining_date\":null,\"department_id\":null,\"location_id\":6}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(42,'00343','SHAHADAT 1SSAIN','shahadat.1ssain@duncanbd.com',NULL,'DEPUTY MANAGER','15',1,'{\"source_id\":187,\"phone\":\"01764330682\",\"joining_date\":null,\"department_id\":null,\"location_id\":15}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(43,'00345','MD.SHAHRIA PERVEZ','shahria.pervez@duncanbd.com',NULL,'DEPUTY MANAGER','8',1,'{\"source_id\":188,\"phone\":\"01717566283\",\"joining_date\":null,\"department_id\":null,\"location_id\":8}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(44,'00355','ABDUL TOWAB KHAN','towab.khan@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','2',1,'{\"source_id\":189,\"phone\":\"01874499816\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(45,'00364','BASHIR AHMED BHUIYAN','bashir.bhuiyan@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','12',1,'{\"source_id\":190,\"phone\":\"01716777177\",\"joining_date\":null,\"department_id\":null,\"location_id\":12}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(46,'00367','AL HASAN HIMEL','al.himel@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','12',1,'{\"source_id\":191,\"phone\":\"01716406417\",\"joining_date\":null,\"department_id\":null,\"location_id\":12}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(47,'00371','H.M. TARIQUR RAHMAN RAHMAN','tariqur.rahman@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','2',1,'{\"source_id\":192,\"phone\":\"01779909123\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(48,'00372','SABBIR AHMED','sabbir.ahmed@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','14',1,'{\"source_id\":193,\"phone\":\"01765967389\",\"joining_date\":null,\"department_id\":null,\"location_id\":14}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(49,'00378','JANNATUL ALAM','jannatul.alam@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','9',1,'{\"source_id\":194,\"phone\":\"01703563814\",\"joining_date\":null,\"department_id\":null,\"location_id\":9}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(50,'00382','MD. KAOSARUZZAMAN','md.kaosaruzzaman@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','6',1,'{\"source_id\":195,\"phone\":\"01995462919\",\"joining_date\":null,\"department_id\":null,\"location_id\":6}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(51,'00403','ABU JAFFAR MD. RAFIUL ALAM','rafiul.alam@duncanbd.com',NULL,'ACTING MANAGER','4',1,'{\"source_id\":196,\"phone\":\"01711300331\",\"joining_date\":null,\"department_id\":null,\"location_id\":4}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(52,'00405','TOMAL TORU DAS','tomal.das@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','5',1,'{\"source_id\":197,\"phone\":\"01741734273\",\"joining_date\":null,\"department_id\":null,\"location_id\":5}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(53,'00411','DR. A.H.M. BAHAUDDIN SIDDIQUI','bahauddin.siddiqui@duncanbd.co',NULL,'SENIOR ASSISTANT MANAGER','17',1,'{\"source_id\":198,\"phone\":\"01739836028\",\"joining_date\":null,\"department_id\":null,\"location_id\":17}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(54,'00412','DR. FERDOUSI KAMAL SHILA','ferdousi.shila@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','17',1,'{\"source_id\":199,\"phone\":\"01719337751\",\"joining_date\":null,\"department_id\":null,\"location_id\":17}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(55,'00414','MOHAMMED JAHIRUL 1QUE','mohammed.1que@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','12',1,'{\"source_id\":200,\"phone\":\"01757947875\",\"joining_date\":null,\"department_id\":null,\"location_id\":12}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(56,'00420','RASHIDUL HASAN','rashidul.hasan@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','10',1,'{\"source_id\":201,\"phone\":\"01917649523\",\"joining_date\":null,\"department_id\":null,\"location_id\":10}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(57,'00422','SYED MAHFUJUR RAHMAN RAYHAN','mahfujur.rahman@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','3',1,'{\"source_id\":202,\"phone\":\"01717000859\",\"joining_date\":null,\"department_id\":null,\"location_id\":3}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(58,'00425','DR. MD. ZAKARIA','mohammad.zakaria@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','5',1,'{\"source_id\":203,\"phone\":\"01741980462\",\"joining_date\":null,\"department_id\":null,\"location_id\":5}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(59,'00427','SHUVO BHATTACHARJEE','shuvo.bhattacharjee@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','7',1,'{\"source_id\":204,\"phone\":\"01717905458\",\"joining_date\":null,\"department_id\":null,\"location_id\":7}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(60,'00428','M. SIMON ISLAM','simon.islam@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','4',1,'{\"source_id\":205,\"phone\":\"01303370607\",\"joining_date\":null,\"department_id\":null,\"location_id\":4}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(61,'00434','MD. ABDUR ROB','abdur.rob@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','14',1,'{\"source_id\":206,\"phone\":\"01726660278\",\"joining_date\":null,\"department_id\":null,\"location_id\":14}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(62,'00435','MD. MUSTAFIZUR RAHMAN','mustafizur.rahman@duncanbd.com',NULL,'MANAGING DIRECTOR','1',1,'{\"source_id\":207,\"phone\":\"01819267700\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(63,'00441','MOHAMMAD RAMIJ UDDIN','ramij.uddin@duncanbd.com',NULL,'DEPUTY MANAGER','2',1,'{\"source_id\":208,\"phone\":\"01996790802\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(64,'00442','MD. MEHEDI HASAN TALUKDER','mehedi.talukder@duncanbd.com',NULL,'ASSISTANT MANAGER','6',1,'{\"source_id\":209,\"phone\":\"01714818288\",\"joining_date\":null,\"department_id\":null,\"location_id\":6}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(65,'00450','SARAFAT KHAN','sarafat.khan@duncanbd.com',NULL,'ASSISTANT MANAGER','9',1,'{\"source_id\":210,\"phone\":\"01701134747\",\"joining_date\":null,\"department_id\":null,\"location_id\":9}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(66,'00456','MD. IRFAN HUSSAIN','Irfan.hussain@duncanbd.com',NULL,'ASSISTANT MANAGER','11',1,'{\"source_id\":211,\"phone\":\"01797940561\",\"joining_date\":null,\"department_id\":null,\"location_id\":11}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(67,'00462','MD.ISTAQUE AHMED SUMON','istaque.ahmed@duncanbd.com',NULL,'ASSISTANT MANAGER','3',1,'{\"source_id\":212,\"phone\":\"01765493153\",\"joining_date\":null,\"department_id\":null,\"location_id\":3}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(68,'00463','MD. ABU SALEH AL SABBIR','saleh.sabbir@duncanbd.com',NULL,'ASSISTANT MANAGER','13',1,'{\"source_id\":213,\"phone\":\"01757593851\",\"joining_date\":null,\"department_id\":null,\"location_id\":13}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(69,'00466','SHEIKH MOINUL ISLAM SOBUZ','sobuzmoinul@duncanbd.com',NULL,'ASSISTANT MANAGER (M)','15',1,'{\"source_id\":214,\"phone\":\"01701715237\",\"joining_date\":null,\"department_id\":null,\"location_id\":15}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(70,'00467','SOUROV DEB','sourov.deb@duncanbd.com',NULL,'ASSISTANT MANAGER','2',1,'{\"source_id\":215,\"phone\":\"01738208764\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(71,'00469','S.M.SAQUIB NEWAZ','saquib.newaz@duncanbd.com',NULL,'ASSISTANT MANAGER','2',1,'{\"source_id\":216,\"phone\":\"01700000000\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(72,'00470','BIKASH DEV','bikash.dev@duncanbd.com',NULL,'ASSISTANT MANAGER','13',1,'{\"source_id\":217,\"phone\":\"01700000002\",\"joining_date\":null,\"department_id\":null,\"location_id\":13}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(73,'00473','FEROZE ABDULLAH','feroze.abdullah@duncanbd.com',NULL,'ASSISTANT MANAGER','3',1,'{\"source_id\":218,\"phone\":\"01703020898\",\"joining_date\":null,\"department_id\":null,\"location_id\":3}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(74,'00474','MD. RAKIBUL ALAM','rakibul.alam@duncanbd.com',NULL,'ASSISTANT MANAGER','5',1,'{\"source_id\":219,\"phone\":\"01683711858\",\"joining_date\":null,\"department_id\":null,\"location_id\":5}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(75,'00475','ZUNAYED ISLAM','zunayed.islam@duncanbd.com',NULL,'ASSISTANT MANAGER','8',1,'{\"source_id\":220,\"phone\":\"01615000069\",\"joining_date\":null,\"department_id\":null,\"location_id\":8}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(76,'00477','NUR MEHEDI BAND1N','nur.band1n@duncanbd.com',NULL,'ASSISTANT MANAGER','4',1,'{\"source_id\":221,\"phone\":\"01868744889\",\"joining_date\":null,\"department_id\":null,\"location_id\":4}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(77,'00481','DR. MONISA GOPE AKHA','temp@email.com',NULL,'ASSISTANT MANAGER (M)','17',1,'{\"source_id\":222,\"phone\":\"+8801700000000\",\"joining_date\":null,\"department_id\":null,\"location_id\":17}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(78,'00484','KH. ASHFAK NASER TAWKIR','ashfak.tawkir@duncanbd.com',NULL,'ASSISTANT MANAGER','14',1,'{\"source_id\":223,\"phone\":\"01791947830\",\"joining_date\":null,\"department_id\":null,\"location_id\":14}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(79,'00485','MD. SOADUZZAMAN SOAD','md.soaduzzaman@duncanbd.com',NULL,'ASSISTANT MANAGER','15',1,'{\"source_id\":224,\"phone\":\"01817483486\",\"joining_date\":null,\"department_id\":null,\"location_id\":15}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(80,'00487','MD. SHAMEEM','hshameem64@gmail.com',NULL,'ASSISTANT MANAGER','5',1,'{\"source_id\":225,\"phone\":\"01317365391\",\"joining_date\":null,\"department_id\":null,\"location_id\":5}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(81,'00488','MD. FAHIM FAISAL','faisal.sau99@gmail.com',NULL,'ASSISTANT MANAGER','8',1,'{\"source_id\":226,\"phone\":\"01406555984\",\"joining_date\":null,\"department_id\":null,\"location_id\":8}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(82,'00490','NILLKAMAL CHAKRABORTY','arg17172@gmail.com',NULL,'ASSISTANT MANAGER','9',1,'{\"source_id\":227,\"phone\":\"01861728123\",\"joining_date\":null,\"department_id\":null,\"location_id\":9}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(83,'00492','MD. OWALY ULLAH  SABBIR','owalyullahsabbir016@gmail.com',NULL,'ASSISTANT MANAGER','13',1,'{\"source_id\":228,\"phone\":\"01993749801\",\"joining_date\":null,\"department_id\":null,\"location_id\":13}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(84,'00493','S. M.  ASEEM','sm.aseem1212@gmail.com',NULL,'ASSISTANT MANAGER','6',1,'{\"source_id\":229,\"phone\":\"01792518299\",\"joining_date\":null,\"department_id\":null,\"location_id\":6}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(85,'40003','MOHAMMAD IQBAL MAMUN','iqbal.mamun@duncanbd.com',NULL,'DEPUTY MANAGER','1',1,'{\"source_id\":230,\"phone\":\"01718195421\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(86,'40004','JAKIR 1SSAIN','jakir.1ssain@duncanbd.com',NULL,'DEPUTY MANAGER','1',1,'{\"source_id\":231,\"phone\":\"01935139420\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(87,'40005','MD. HUMAYUN KABIR','humayun.kabir@duncanbd.com',NULL,'MANAGER','1',1,'{\"source_id\":232,\"phone\":\"01715102662\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(88,'40006','MD. ABUL QUASHEM','abul.quashem@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":233,\"phone\":\"01915651893\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(89,'40007','JAMES SIKDER','james.sikder@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":234,\"phone\":\"01552363858\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(90,'40008','PARTHA SARATHI MUTSUDDI','partha.mutsuddi@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','1',1,'{\"source_id\":235,\"phone\":\"01711861189\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(91,'40009','MD. ABUL KALAM AZAD','abul.kalam@duncanbd.com',NULL,'DEPUTY MANAGER','1',1,'{\"source_id\":236,\"phone\":\"01719276100\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(92,'40010','PRODIP KUMAR CHAKRABORTY','prodip.chakrabarti@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":237,\"phone\":\"01715343833\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(93,'40011','MOSTAQUE AHMED C1WDHURY','mostaque.ahmed@duncanbd.com',NULL,'MANAGER','1',1,'{\"source_id\":238,\"phone\":\"01720500600\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(94,'40013','KAZI MOHAMMED EHTESHAMUR RASHID','ehtesham.rashid@duncanbd.com',NULL,'DEPUTY MANAGER','1',1,'{\"source_id\":239,\"phone\":\"01711436025\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(95,'40016','MD. RAFIQ UDDIN','afiqduncan71@gmail.com',NULL,'Executive Asst','17',1,'{\"source_id\":240,\"phone\":\"01720830989\",\"joining_date\":null,\"department_id\":null,\"location_id\":17}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(96,'40017','NAJMUNNESSA TALEYA JABEEN','taleya.jabeen@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":241,\"phone\":\"01553282583\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(97,'40018','BIDYUT KANTI DASTIDER','bidyut.dastidar@duncanbd.com',NULL,'ACTING MANAGER','1',1,'{\"source_id\":242,\"phone\":\"01711905149\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(98,'40019','MD. ABDUS SALAM','salam.duncan@gmail.com',NULL,'ASSISTANT MANAGER','2',1,'{\"source_id\":243,\"phone\":\"01852569291\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(99,'40020','MAHMUD ZAMAN','info@duncanbd.com',NULL,'Accounts Asst','1',1,'{\"source_id\":244,\"phone\":\"01849639626\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(100,'40021','MD. MAINUDDIN','mainuddin167@gmail.com',NULL,'Senior TSE','20',1,'{\"source_id\":245,\"phone\":\"01715788320\",\"joining_date\":null,\"department_id\":null,\"location_id\":20}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(101,'40024','NIRMAL CHANDRA MAZUMDER','nirmal.duncanbd@gmail.com',NULL,'TSE','18',1,'{\"source_id\":246,\"phone\":\"01715771651\",\"joining_date\":null,\"department_id\":null,\"location_id\":18}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(102,'40029','SAJIDUR RAHMAN','sajidur.rahman@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":247,\"phone\":\"01923596954\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(103,'40030','AMAL CHANDRA SAHA','amal.saha@duncanbd.com',NULL,'ASSISTANT MANAGER','19',1,'{\"source_id\":248,\"phone\":\"01718560814\",\"joining_date\":null,\"department_id\":null,\"location_id\":19}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(104,'40031','IMTIAZ MOYEEN SYED','imtiaz.syed@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":249,\"phone\":\"01715788320\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(105,'40033','FARZANA AFROZ','farzana.afroz@duncanbd.com',NULL,'DEPUTY MANAGER','1',1,'{\"source_id\":250,\"phone\":\"01743105037\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(106,'40034','MD. SHAH ALAM','info@duncanbd.com',NULL,'Office Asst (19)','19',1,'{\"source_id\":251,\"phone\":\"01966933363\",\"joining_date\":null,\"department_id\":null,\"location_id\":19}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(107,'40038','SANJAY KUMER PAUL','sanjay.paul@duncanbd.com',NULL,'MANAGER','1',1,'{\"source_id\":252,\"phone\":\"01711003386\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(108,'40040','MUHAMMAD SHAMIULLAH','shamiullah@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":253,\"phone\":\"01712086648\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(109,'40041','SHAHRIAR UL ISLAM','shahriar.islam@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":254,\"phone\":\"01779009895\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(110,'40044','PARTHA SAHA','partha.saha@duncanbd.com',NULL,'Executive Asst','1',1,'{\"source_id\":255,\"phone\":\"01715263770\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(111,'40048','MD. BANI AMIN','info@duncanbd.com',NULL,'Executive Asst','2',1,'{\"source_id\":256,\"phone\":\"01790050508\",\"joining_date\":null,\"department_id\":null,\"location_id\":2}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(112,'40056','MD. SAYEM HOSSEN','sayem.hossen@duncanbd.com',NULL,'Executive Asst','1',1,'{\"source_id\":257,\"phone\":\"01742412469\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(113,'40058','MD. SHAKHAWAT HOSSEN','shakhawat.hossen@duncanbd.com',NULL,'SENIOR ASSISTANT MANAGER','1',1,'{\"source_id\":258,\"phone\":\"01717578867\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(114,'40060','MD. MOHIUDDIN AHMED','mohiuddin@duncanbd.com',NULL,'SENIOR MANAGER','1',1,'{\"source_id\":259,\"phone\":\"01711543298\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(115,'40063','MD. RIYAN ANAM ZAAD','riyan.zaad@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":260,\"phone\":\"01520101930\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(116,'40071','MD. RAJIB UL HASAN','rajib.hasan@duncanbd.com',NULL,'MANAGER','1',1,'{\"source_id\":261,\"phone\":\"01911761075\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(117,'40072','SUJIT DEBNATH','info@duncanbd.com',NULL,'Executive Asst','17',1,'{\"source_id\":262,\"phone\":\"01922823782\",\"joining_date\":null,\"department_id\":null,\"location_id\":17}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(118,'40076','OMAR NASIF','omarnasif87@gmail.com',NULL,'Executive Asst','1',1,'{\"source_id\":263,\"phone\":\"01670872832\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(119,'40077','RANJIT CHANDRA SHIL','ranjit.shil@duncanbd.com',NULL,'SENIOR MANAGER','1',1,'{\"source_id\":264,\"phone\":\"01776485982\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(120,'40078','FARIHA ISLAM','fariha.islam@duncanbd.com',NULL,'Executive Asst','1',1,'{\"source_id\":265,\"phone\":\"01672998917\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(121,'40080','MD. MAHEDI HASAN','m.mahedi.hasan@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":266,\"phone\":\"01303443963\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(122,'40083','FARJANA ALAM SOVA','farjana.sova@duncanbd.com',NULL,'Executive Asst','1',1,'{\"source_id\":267,\"phone\":\"01730996227\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(123,'40084','FARIA SIDDIKA MOUMITA','faria.moumita@duncanbd.com',NULL,'Executive Asst','1',1,'{\"source_id\":268,\"phone\":\"01794669858\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(124,'40085','KAMRUL HASAN','kamrul.hasan@duncanbd.com',NULL,'Executive Asst','1',1,'{\"source_id\":269,\"phone\":\"01760721809\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(125,'40086','MD. MAHMUDUR RAHMAN','mahamudur.rahman@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":270,\"phone\":\"01777028193\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(126,'40087','A.K.M. SAKIBUL HAQUE','sakibul.haque@duncanbd.com',NULL,'ASSISTANT MANAGER','1',1,'{\"source_id\":271,\"phone\":\"01328983203\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(127,'40088','EHTESHAM MASH1OD CHOWDHURY','ehtesham.chowdhury@duncanbd.com',NULL,'Senior Covenanted Officer','1',1,'{\"source_id\":272,\"phone\":\"+8801969616600\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(128,'40089','MD. ABU BAKAR SIDDIK  BADHAN','siddik.badhan@duncanbd.com',NULL,'EXECUTIVE ASSISTANT','1',1,'{\"source_id\":273,\"phone\":\"01932072395\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(129,'40091','MEHEDI SABBIR HASSAN','mehedi.sabbir@duncanbd.com',NULL,'CHIEF INTERNAL AUDITOR','1',1,'{\"source_id\":274,\"phone\":\"01862444390\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(130,'40093','ADNAN SANI','adnan.sani@duncanbd.com',NULL,'EXECUTIVE ASSISTANT','1',1,'{\"source_id\":275,\"phone\":\"01841547706\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(131,'DH0689','Abdul Hannan','info@duncanbd.com',NULL,'Office Assistance (Despatch)','1',1,'{\"source_id\":276,\"phone\":\"01855414841\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(132,'DH0754','Badiul Alam','info@duncanbd.com',NULL,'Peon','1',1,'{\"source_id\":277,\"phone\":\"01825387338\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(133,'DH0931','Nurul Amin','info@duncanbd.com',NULL,'Peon','1',1,'{\"source_id\":278,\"phone\":\"01924744193\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(134,'DH1011','ABDUL LATIF','info@duncanbd.com',NULL,'Peon','1',1,'{\"source_id\":279,\"phone\":\"01736871728\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(135,'DH1082','MD. ASLAM','info@duncanbd.com',NULL,'Maintenance Forman','1',1,'{\"source_id\":280,\"phone\":\"01712272776\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(136,'DH1127','MILAN DAS','info@duncanbd.com',NULL,'Peon','1',1,'{\"source_id\":281,\"phone\":\"01817688809\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(137,'DH1167','MD. ARIFUR RAHMAN  C1WDHURY','info@duncanbd.com',NULL,'Peon','19',1,'{\"source_id\":282,\"phone\":\"01817639992\",\"joining_date\":null,\"department_id\":null,\"location_id\":19}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(138,'DH1205','MD. ANWARUL ISLAM','info@duncanbd.com',NULL,'Peon','1',1,'{\"source_id\":283,\"phone\":\"01911901065\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(139,'DH1206','A. DHIREN SINGHA','info@duncanbd.com',NULL,'Electrician','1',1,'{\"source_id\":284,\"phone\":\"01778072269\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(140,'DH1215','MD. BULBUL 1WLADER','info@duncanbd.com',NULL,'Caretaker','1',1,'{\"source_id\":285,\"phone\":\"01714453884\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(141,'DH1216','ABDUR RAHIM','info@duncanbd.com',NULL,'Peon','1',1,'{\"source_id\":286,\"phone\":\"01716979672\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(142,'DH1217','MD. AZHAR UDDIN SARDER','info@duncanbd.com',NULL,'Mechanic','1',1,'{\"source_id\":287,\"phone\":\"01753076718\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(143,'DP1136','JUEL SHAH','info@duncanbd.com',NULL,'Cook','1',1,'{\"source_id\":288,\"phone\":\"01768895880\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(144,'DP1186','NIBARON MADRAJI','info@duncanbd.com',NULL,'Peon','1',1,'{\"source_id\":289,\"phone\":\"01616513651\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55'),
(145,'DPAA86','MD. AIUB','info@duncanbd.com',NULL,'Driver','1',1,'{\"source_id\":290,\"phone\":\"01716364447\",\"joining_date\":null,\"department_id\":null,\"location_id\":1}','2026-09-15 00:23:55','2026-09-15 00:23:55');
/*!40000 ALTER TABLE `zk_employees` ENABLE KEYS */;
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

-- Dump completed on 2026-09-15  9:26:42
