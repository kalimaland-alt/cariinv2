-- ============================================================
-- CariIn - Real Estate Marketplace - Database Schema
-- MySQL 8.0+ / MariaDB 10.3+
-- ============================================================
-- Import via phpMyAdmin / CLI mysql:
--   mysql -u root carin_db < carin_schema.sql
-- Atau jalankan migrasi via CLI:
--   php spark migrate
--   php spark db:seed DatabaseSeeder
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;
SET time_zone = '+07:00';

CREATE DATABASE IF NOT EXISTS `carin_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `carin_db`;

-- ------------------------------------------------------------
-- USERS
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `google_id` VARCHAR(100) DEFAULT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password_hash` VARCHAR(255) DEFAULT NULL,
    `name` VARCHAR(150) NOT NULL,
    `avatar_url` TEXT,
    `phone` VARCHAR(20) DEFAULT NULL,
    `role` ENUM('admin','member') NOT NULL DEFAULT 'member',
    `status` ENUM('active','suspended') NOT NULL DEFAULT 'active',
    `free_slot_used` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `google_id` (`google_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- CATEGORIES
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(80) NOT NULL,
    `slug` VARCHAR(80) NOT NULL,
    `form_type` ENUM('building','land') NOT NULL DEFAULT 'building',
    `icon` VARCHAR(50) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- PROPERTIES
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `properties`;
CREATE TABLE `properties` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `type` ENUM('sell','rent') NOT NULL DEFAULT 'sell',
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NOT NULL,
    `description` TEXT,
    `price` BIGINT UNSIGNED NOT NULL DEFAULT 0,
    `price_period` ENUM('-','monthly','yearly') NOT NULL DEFAULT '-',
    `legal_status` ENUM('SHM','AJB','Girik','Waris','HGB','SHGB') DEFAULT NULL,
    `doc_status` ENUM('on_hand','at_bank') NOT NULL DEFAULT 'on_hand',
    `orientation` ENUM('N','S','E','W','NE','NW','SE','SW') DEFAULT NULL,
    `province` VARCHAR(80) DEFAULT NULL,
    `city` VARCHAR(80) DEFAULT NULL,
    `district` VARCHAR(80) DEFAULT NULL,
    `address` TEXT,
    `latitude` DECIMAL(10,7) DEFAULT NULL,
    `longitude` DECIMAL(10,7) DEFAULT NULL,
    `maps_url` TEXT,
    `status` ENUM('draft','pending_review','published','rejected','sold') NOT NULL DEFAULT 'pending_review',
    `reject_reason` TEXT,
    `views` INT UNSIGNED NOT NULL DEFAULT 0,
    `is_paid_slot` TINYINT(1) NOT NULL DEFAULT 0,
    `published_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `user_id` (`user_id`),
    KEY `category_id` (`category_id`),
    KEY `status` (`status`),
    KEY `type_status` (`type`,`status`),
    CONSTRAINT `fk_prop_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_prop_cat`  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- PROPERTY DETAILS (EAV)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `property_details`;
CREATE TABLE `property_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `key_name` VARCHAR(60) NOT NULL,
    `value` VARCHAR(200) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `property_key` (`property_id`,`key_name`),
    CONSTRAINT `fk_pd_prop` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- PROPERTY IMAGES
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `property_images`;
CREATE TABLE `property_images` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `file_name` VARCHAR(200) NOT NULL,
    `is_cover` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `property_id` (`property_id`),
    CONSTRAINT `fk_pi_prop` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- PAYMENTS
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `property_id` BIGINT UNSIGNED DEFAULT NULL,
    `transaction_id` VARCHAR(100) NOT NULL,
    `snap_token` TEXT,
    `amount` INT UNSIGNED NOT NULL DEFAULT 0,
    `payment_method` VARCHAR(30) NOT NULL DEFAULT 'qris',
    `status` ENUM('pending','success','failed','expired') NOT NULL DEFAULT 'pending',
    `paid_at` DATETIME DEFAULT NULL,
    `raw_response` JSON DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `transaction_id` (`transaction_id`),
    KEY `user_id` (`user_id`),
    KEY `status` (`status`),
    CONSTRAINT `fk_pay_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_pay_prop` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- WISHLISTS
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE `wishlists` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `created_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_property` (`user_id`,`property_id`),
    CONSTRAINT `fk_w_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_w_prop` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- CHATS
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `chats`;
CREATE TABLE `chats` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `buyer_id` BIGINT UNSIGNED NOT NULL,
    `seller_id` BIGINT UNSIGNED NOT NULL,
    `last_message_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `property_buyer` (`property_id`,`buyer_id`),
    KEY `buyer_seller` (`buyer_id`,`seller_id`),
    CONSTRAINT `fk_c_prop` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_c_buyer` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_c_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- CHAT MESSAGES
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `chat_id` BIGINT UNSIGNED NOT NULL,
    `sender_id` BIGINT UNSIGNED NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `chat_id` (`chat_id`),
    CONSTRAINT `fk_cm_chat` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_cm_user` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- SELLER RATINGS
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `seller_ratings`;
CREATE TABLE `seller_ratings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `seller_id` BIGINT UNSIGNED NOT NULL,
    `reviewer_id` BIGINT UNSIGNED NOT NULL,
    `property_id` BIGINT UNSIGNED NOT NULL,
    `rating` TINYINT NOT NULL,
    `review` TEXT,
    `created_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `reviewer_seller_prop` (`reviewer_id`,`seller_id`,`property_id`),
    KEY `seller_id` (`seller_id`),
    CONSTRAINT `fk_sr_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_sr_reviewer` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_sr_prop` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Kategori default
INSERT INTO `categories` (`name`,`slug`,`form_type`,`icon`,`is_active`,`sort_order`,`created_at`,`updated_at`) VALUES
('Rumah',     'rumah',     'building', 'bi-house-door', 1, 1, NOW(), NOW()),
('Apartemen', 'apartemen', 'building', 'bi-building',   1, 2, NOW(), NOW()),
('Ruko',      'ruko',      'building', 'bi-shop',       1, 3, NOW(), NOW()),
('Kantor',    'kantor',    'building', 'bi-briefcase',  1, 4, NOW(), NOW()),
('Gudang',    'gudang',    'building', 'bi-box-seam',   1, 5, NOW(), NOW()),
('Tanah',     'tanah',     'land',     'bi-pin-map',    1, 6, NOW(), NOW()),
('Kebun',     'kebun',     'land',     'bi-tree',       1, 7, NOW(), NOW());

-- Admin user default
-- Password: admin123 (hashed with PHP password_hash/bcrypt)
INSERT INTO `users` (`google_id`,`email`,`password_hash`,`name`,`phone`,`role`,`status`,`free_slot_used`,`created_at`,`updated_at`) VALUES
(NULL, 'admin@carin.local', '$2y$10$1J9sBZRZYy9hQ5nL7v3xBOKPZh3kqwMbHoLh9QN7TQm3QCqNkYkEq', 'Administrator', '081234567890', 'admin', 'active', 0, NOW(), NOW());
