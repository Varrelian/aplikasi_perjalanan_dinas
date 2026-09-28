-- TravelSys - corrected SQL dump
-- MySQL 8.0+
-- Main fixes:
-- 1. Removed invalid department manager references to non-existent users.
-- 2. Added the missing trip record for TRIP-10291.
-- 3. Made department_budgets.utilized_percentage safe when budget_amount = 0.
-- 4. Changed booking_items departure/arrival datetime fields to DATETIME.
-- 5. Kept the existing application-facing columns to minimize breaking changes.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `travelsys`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `travelsys`;

DROP TABLE IF EXISTS `trip_coordination_members`;
DROP TABLE IF EXISTS `trip_coordination_suggestions`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `booking_items`;
DROP TABLE IF EXISTS `approval_steps`;
DROP TABLE IF EXISTS `trips`;
DROP TABLE IF EXISTS `travel_requests`;
DROP TABLE IF EXISTS `travel_policy_rules`;
DROP TABLE IF EXISTS `travel_policies`;
DROP TABLE IF EXISTS `department_budgets`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `code` varchar(50) NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'IDR',
  `timezone` varchar(50) NOT NULL DEFAULT 'Asia/Jakarta',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `companies`
(`id`,`name`,`code`,`currency`,`timezone`,`created_at`,`updated_at`) VALUES
(1,'Nusantara Technology Group','NTG','IDR','Asia/Jakarta','2026-09-22 00:42:38','2026-09-22 00:42:38');

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `employee_code` varchar(50) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `band` enum('Band 1','Band 2','Band 3','Band 4','Band 5') NOT NULL DEFAULT 'Band 2',
  `role` varchar(50) NOT NULL DEFAULT 'Employee / Traveler',
  `phone` varchar(50) DEFAULT NULL,
  `avatar_url` varchar(500) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `employee_code` (`employee_code`),
  KEY `idx_users_email` (`email`),
  KEY `idx_users_employee_code` (`employee_code`),
  KEY `idx_users_department` (`department`),
  KEY `idx_users_band` (`band`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users`
(`id`,`name`,`email`,`email_verified_at`,`password`,`employee_code`,`job_title`,`department`,`band`,`role`,`phone`,`avatar_url`,`remember_token`,`created_at`,`updated_at`) VALUES
(1,'Andi Pratama','andi.pratama@travelsys.internal',NULL,'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','EMP-4091','Senior Cloud Consultant','Engineering & Technology','Band 2','Employee / Traveler',NULL,'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',NULL,'2026-09-22 02:16:26','2026-09-22 02:16:26'),
(2,'Siti Rahma','siti.rahma@travelsys.internal',NULL,'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','EMP-1022','VP of Product Management','Product & Design','Band 4','Approver / Line Manager',NULL,'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',NULL,'2026-09-22 02:16:26','2026-09-22 02:16:26'),
(3,'Budi Santoso','budi.santoso@travelsys.internal',NULL,'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','EMP-0504','Corporate Finance Director','Corporate Finance','Band 5','Finance Approver / C-Suite',NULL,'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',NULL,'2026-09-22 02:16:26','2026-09-22 02:16:26'),
(4,'Raion','farhan260908@gmail.com',NULL,'$2y$12$dWKkWJW0Mb4/h5Xyn4ChVODKGkTlReKOEgNmF0mxfVD418Un86sMG','EMP-5913','Lead Software Engineer','Engineering & Tech','Band 2','Employee / Traveler',NULL,'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',NULL,'2026-09-21 19:21:34','2026-09-21 19:21:34');

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`,`name`,`display_name`,`created_at`) VALUES
(1,'employee','Employee','2026-09-22 00:42:38'),
(2,'manager','Manager','2026-09-22 00:42:38'),
(3,'finance','Finance','2026-09-22 00:42:38'),
(4,'travel_admin','Travel Admin','2026-09-22 00:42:38');

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(30) NOT NULL,
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_department_company_code` (`company_id`,`code`),
  KEY `fk_departments_manager` (`manager_id`),
  CONSTRAINT `fk_departments_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_departments_manager` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invalid manager IDs 7 and 8 from the original dump were replaced with
-- valid existing users. Adjust these managers later if your organization differs.
INSERT INTO `departments`
(`id`,`company_id`,`name`,`code`,`manager_id`,`is_active`,`created_at`,`updated_at`) VALUES
(1,1,'Engineering','ENG',1,1,'2026-09-22 00:42:38','2026-09-22 00:42:38'),
(2,1,'Sales','SALES',2,1,'2026-09-22 00:42:38','2026-09-22 00:42:38'),
(3,1,'Operations','OPS',3,1,'2026-09-22 00:42:38','2026-09-22 00:42:38'),
(4,1,'Finance','FIN',3,1,'2026-09-22 00:42:38','2026-09-22 00:42:38');

CREATE TABLE `department_budgets` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) NOT NULL,
  `cost_center` varchar(50) NOT NULL,
  `fiscal_year` year NOT NULL DEFAULT '2026',
  `budget_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `spent_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) NOT NULL DEFAULT 'IDR',
  `utilized_percentage` decimal(6,2)
    GENERATED ALWAYS AS (
      CASE
        WHEN `budget_amount` > 0
        THEN (`spent_amount` / `budget_amount`) * 100
        ELSE 0
      END
    ) STORED,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cost_center` (`cost_center`),
  KEY `idx_dept_budgets_cost_center` (`cost_center`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `department_budgets`
(`id`,`department_name`,`cost_center`,`fiscal_year`,`budget_amount`,`spent_amount`,`currency`,`created_at`,`updated_at`) VALUES
(1,'Engineering & Technology','CC-402',2026,250000000.00,180000000.00,'IDR','2026-09-22 02:16:26','2026-09-22 02:16:26'),
(2,'Enterprise Sales','CC-201',2026,200000000.00,195000000.00,'IDR','2026-09-22 02:16:26','2026-09-22 02:16:26'),
(3,'Global Operations','CC-305',2026,300000000.00,150000000.00,'IDR','2026-09-22 02:16:26','2026-09-22 02:16:26'),
(4,'Corporate Finance','CC-101',2026,100000000.00,35000000.00,'IDR','2026-09-22 02:16:26','2026-09-22 02:16:26');

CREATE TABLE `travel_policies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rule_type` enum('flight_limit','hotel_limit','travel_class','per_diem') NOT NULL,
  `scope` enum('domestic','international','all') NOT NULL DEFAULT 'all',
  `applicable_band` enum('All','Band 1','Band 2','Band 3','Band 4','Band 5') NOT NULL DEFAULT 'All',
  `amount_limit` decimal(15,2) DEFAULT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'IDR',
  `allowed_class` varchar(100) DEFAULT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_travel_policies_rule_type` (`rule_type`),
  KEY `idx_travel_policies_scope` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `travel_policy_rules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `rule_type` enum('flight_limit','hotel_limit','travel_class') NOT NULL,
  `travel_scope` enum('domestic','international','all') NOT NULL DEFAULT 'all',
  `amount_limit` decimal(15,2) DEFAULT NULL,
  `currency` char(3) NOT NULL DEFAULT 'IDR',
  `allowed_class` enum('economy','premium_economy','business','first') DEFAULT NULL,
  `minimum_job_level` varchar(50) DEFAULT NULL,
  `is_blocking` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_policy_company` (`company_id`),
  CONSTRAINT `fk_policy_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `travel_policy_rules`
(`id`,`company_id`,`name`,`rule_type`,`travel_scope`,`amount_limit`,`currency`,`allowed_class`,`minimum_job_level`,`is_blocking`,`is_active`,`created_at`,`updated_at`) VALUES
(1,1,'Domestic Flight Limit','flight_limit','domestic',2000000.00,'IDR',NULL,NULL,1,1,'2026-09-22 00:42:38','2026-09-22 00:42:38'),
(2,1,'Hotel Nightly Limit','hotel_limit','all',500000.00,'IDR',NULL,NULL,1,1,'2026-09-22 00:42:38','2026-09-22 00:42:38'),
(3,1,'Default Travel Class','travel_class','all',NULL,'IDR','economy',NULL,1,1,'2026-09-22 00:42:38','2026-09-22 00:42:38'),
(4,1,'Manager Business Class','travel_class','all',NULL,'IDR','business','manager',0,1,'2026-09-22 00:42:38','2026-09-22 00:42:38');

CREATE TABLE `travel_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_code` varchar(50) NOT NULL,
  `trip_id` varchar(50) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cost_center` varchar(50) NOT NULL DEFAULT 'CC-402',
  `origin` varchar(100) NOT NULL,
  `origin_code` varchar(10) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `dest_code` varchar(10) NOT NULL,
  `departure_date` date NOT NULL,
  `return_date` date NOT NULL,
  `departure_time_slot` varchar(100) DEFAULT 'Morning Flight (06:00 - 11:00)',
  `return_time_slot` varchar(100) DEFAULT 'Evening Flight (17:00 - 22:00)',
  `purpose_type` varchar(100) NOT NULL DEFAULT 'Client Meeting',
  `purpose_title` varchar(255) NOT NULL,
  `purpose_description` text NOT NULL,
  `flight_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hotel_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `transit_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) NOT NULL DEFAULT 'IDR',
  `policy_status` enum('compliant','warning','violation','exceeded') NOT NULL DEFAULT 'compliant',
  `budget_status` enum('available','warning','insufficient') NOT NULL DEFAULT 'available',
  `approval_stage` varchar(100) NOT NULL DEFAULT 'Line Manager Review',
  `stage_step` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `total_steps` tinyint UNSIGNED NOT NULL DEFAULT '6',
  `overall_status` enum('draft','pending_review','pending_approval','approved','booking_ready','booked','completed','cancelled') NOT NULL DEFAULT 'pending_review',
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_code` (`request_code`),
  KEY `idx_travel_requests_code` (`request_code`),
  KEY `idx_travel_requests_user` (`user_id`),
  KEY `idx_travel_requests_status` (`overall_status`),
  CONSTRAINT `fk_travel_requests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `travel_requests`
(`id`,`request_code`,`trip_id`,`user_id`,`cost_center`,`origin`,`origin_code`,`destination`,`dest_code`,`departure_date`,`return_date`,`departure_time_slot`,`return_time_slot`,`purpose_type`,`purpose_title`,`purpose_description`,`flight_cost`,`hotel_cost`,`transit_cost`,`total_cost`,`currency`,`policy_status`,`budget_status`,`approval_stage`,`stage_step`,`total_steps`,`overall_status`,`submitted_at`,`created_at`,`updated_at`) VALUES
(1,'TRV-10291','TRIP-10291',1,'CC-402','Jakarta (CGK)','CGK','Surabaya (SUB)','SUB','2026-10-12','2026-10-15','Morning Flight (06:00 - 11:00)','Evening Flight (17:00 - 22:00)','Client Meeting','PT Surabaya Digital Mandiri Kickoff','Technical kickoff meeting for phase 2 system integration and on-site infrastructure review.',1700000.00,1500000.00,0.00,3200000.00,'IDR','compliant','available','Finance Review',5,6,'booking_ready','2026-10-08 02:21:00','2026-09-22 02:16:26','2026-09-22 02:16:26');

CREATE TABLE `trips` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trip_code` varchar(30) NOT NULL,
  `travel_request_id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `primary_traveler_id` bigint UNSIGNED NOT NULL,
  `status` enum('approved','booking_ready','booked','ongoing','completed','cancelled') NOT NULL DEFAULT 'approved',
  `booked_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trip_code` (`trip_code`),
  UNIQUE KEY `travel_request_id` (`travel_request_id`),
  KEY `idx_trips_company_status` (`company_id`,`status`),
  KEY `fk_trips_primary_traveler` (`primary_traveler_id`),
  CONSTRAINT `fk_trips_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_trips_primary_traveler` FOREIGN KEY (`primary_traveler_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_trips_request` FOREIGN KEY (`travel_request_id`) REFERENCES `travel_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Missing trip row from the original dump.
INSERT INTO `trips`
(`id`,`trip_code`,`travel_request_id`,`company_id`,`primary_traveler_id`,`status`,`booked_at`,`started_at`,`completed_at`,`created_at`,`updated_at`) VALUES
(1,'TRIP-10291',1,1,1,'booking_ready',NULL,NULL,NULL,'2026-09-22 02:16:26','2026-09-22 02:16:26');

CREATE TABLE `approval_steps` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `travel_request_id` bigint UNSIGNED NOT NULL,
  `step_order` tinyint UNSIGNED NOT NULL,
  `step_type` enum('manager','finance') NOT NULL,
  `approver_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected','skipped') NOT NULL DEFAULT 'pending',
  `comment` text,
  `acted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_approval_request_order` (`travel_request_id`,`step_order`),
  KEY `idx_approval_approver_status` (`approver_id`,`status`),
  CONSTRAINT `fk_approval_approver` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_approval_request` FOREIGN KEY (`travel_request_id`) REFERENCES `travel_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `booking_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `travel_request_id` bigint UNSIGNED NOT NULL,
  `item_type` enum('flight','hotel','transit') NOT NULL,
  `provider` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `booking_code` varchar(50) DEFAULT NULL,
  `seat_or_room_type` varchar(100) DEFAULT NULL,
  `departure_datetime` datetime DEFAULT NULL,
  `arrival_datetime` datetime DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `nights` int UNSIGNED DEFAULT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) NOT NULL DEFAULT 'IDR',
  `is_compliant` tinyint(1) NOT NULL DEFAULT '1',
  `is_selected` tinyint(1) NOT NULL DEFAULT '1',
  `hotel_image_url` varchar(500) DEFAULT NULL,
  `distance_to_client` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_booking_items_request` (`travel_request_id`),
  CONSTRAINT `fk_booking_items_request` FOREIGN KEY (`travel_request_id`) REFERENCES `travel_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trip_id` bigint UNSIGNED NOT NULL,
  `booking_type` enum('flight','hotel','transport') NOT NULL,
  `provider_name` varchar(150) NOT NULL,
  `reference_code` varchar(100) DEFAULT NULL,
  `origin` varchar(150) DEFAULT NULL,
  `destination` varchar(150) DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currency` char(3) NOT NULL DEFAULT 'IDR',
  `policy_status` enum('compliant','violation','exception_approved') NOT NULL DEFAULT 'compliant',
  `status` enum('selected','pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bookings_trip` (`trip_id`),
  KEY `idx_bookings_type` (`booking_type`),
  CONSTRAINT `fk_bookings_trip` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `trip_coordination_suggestions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NOT NULL,
  `base_trip_id` bigint UNSIGNED NOT NULL,
  `destination` varchar(150) NOT NULL,
  `travel_date_from` date NOT NULL,
  `travel_date_to` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('new','reviewed','accepted','dismissed') NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_coordination_company_status` (`company_id`,`status`),
  KEY `idx_coordination_destination_dates` (`destination`,`travel_date_from`,`travel_date_to`),
  KEY `fk_coordination_base_trip` (`base_trip_id`),
  CONSTRAINT `fk_coordination_base_trip` FOREIGN KEY (`base_trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_coordination_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `trip_coordination_members` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `suggestion_id` bigint UNSIGNED NOT NULL,
  `trip_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_coordination_member` (`suggestion_id`,`trip_id`),
  KEY `idx_coordination_member_user` (`user_id`),
  KEY `fk_coordination_members_trip` (`trip_id`),
  CONSTRAINT `fk_coordination_members_suggestion` FOREIGN KEY (`suggestion_id`) REFERENCES `trip_coordination_suggestions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_coordination_members_trip` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_coordination_members_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES
(1,'0001_01_01_000001_create_cache_table',1),
(2,'0001_01_01_000002_create_jobs_table',1),
(3,'2026_09_22_003914_create_sessions_table',1);

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions`
(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) VALUES
('84wcvJ03uXqKtpGSIHpzQYv5i2Zjln83FPXwbR4M',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.138.0 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36','eyJfdG9rZW4iOiJ2ZEZNSVFranNyb2JRTnY2TXkyZkVEOG00M1JsYVVHMFE4WkVVaUFhIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvZGFzaGJvYXJkIn19',1790048313),
('se0lYl8H0ZhTYru6CTdNlxvzwHBOFh0M9W5nrqDY',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36','eyJfdG9rZW4iOiIyaHdPd29kMU5aU0V3alpZV1hsOVdUazRxRDN3bmkwOXFkTXRGbmk2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hcHByb3ZhbHM/ZmlsdGVyPWFsbCIsInJvdXRlIjoiYXBwcm92YWxzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Rhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6NH0=',1790047625);

CREATE TABLE IF NOT EXISTS `__travelsys_fix_marker` (
  `id` tinyint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE `__travelsys_fix_marker`;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
