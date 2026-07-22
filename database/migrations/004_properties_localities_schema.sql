-- ============================================================
-- Migration 004: Properties and Localities Schema
-- Scope: localities table, properties table, and relation mapping
-- Created: 2026-07-22
-- ============================================================

CREATE TABLE IF NOT EXISTS `localities` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `city_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `status` ENUM('active','inactive') DEFAULT 'active',
  `sort_order` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_city_locality` (`city_id`, `slug`),
  CONSTRAINT `fk_locality_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional locality mapping on projects
ALTER TABLE `projects` ADD COLUMN `locality_id` INT UNSIGNED DEFAULT NULL AFTER `city_id`;
ALTER TABLE `projects` ADD CONSTRAINT `fk_project_locality` FOREIGN KEY (`locality_id`) REFERENCES `localities` (`id`) ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS `properties` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `project_id` INT UNSIGNED DEFAULT NULL,
  `builder_id` INT UNSIGNED DEFAULT NULL,
  `city_id` INT UNSIGNED DEFAULT NULL,
  `locality_id` INT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `property_type` VARCHAR(100) DEFAULT 'Apartment',
  `listing_type` VARCHAR(50) DEFAULT 'Sale',
  `market_type` VARCHAR(100) DEFAULT 'Secondary (Resale)',
  `possession_status` VARCHAR(100) DEFAULT 'Ready to Move',
  `price` DECIMAL(15,2) DEFAULT NULL,
  `price_display_override` VARCHAR(255) DEFAULT NULL,
  `price_unit` VARCHAR(50) DEFAULT 'Total',
  `is_gst_inclusive` TINYINT(1) DEFAULT 0,
  `vastu_compliant` TINYINT(1) DEFAULT 0,
  `bedrooms` INT DEFAULT NULL,
  `bathrooms` INT DEFAULT NULL,
  `balconies` INT DEFAULT NULL,
  `parking_spaces` INT DEFAULT NULL,
  `super_built_up_area` INT DEFAULT NULL,
  `built_up_area` INT DEFAULT NULL,
  `carpet_area` INT DEFAULT NULL,
  `area_unit` VARCHAR(50) DEFAULT 'sqft',
  `furnishing_status` VARCHAR(100) DEFAULT 'Unfurnished',
  `floor_number` INT DEFAULT NULL,
  `total_floors` INT DEFAULT NULL,
  `facing` VARCHAR(50) DEFAULT NULL,
  `age_of_construction` VARCHAR(100) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `pincode` VARCHAR(20) DEFAULT NULL,
  `latitude` DECIMAL(10,8) DEFAULT NULL,
  `longitude` DECIMAL(11,8) DEFAULT NULL,
  `rera_id` VARCHAR(100) DEFAULT NULL,
  `possession_date` VARCHAR(80) DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `amenities` TEXT DEFAULT NULL,
  `video_url` VARCHAR(255) DEFAULT NULL,
  `owner_name` VARCHAR(255) DEFAULT NULL,
  `owner_phone` VARCHAR(20) DEFAULT NULL,
  `owner_email` VARCHAR(255) DEFAULT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` VARCHAR(50) DEFAULT 'Active',
  `thumbnail_image` VARCHAR(255) DEFAULT NULL,
  `brochure_pdf` VARCHAR(255) DEFAULT NULL,
  `gallery_images` LONGTEXT DEFAULT NULL COMMENT 'JSON array',
  `floor_plan_images` LONGTEXT DEFAULT NULL COMMENT 'JSON array',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_property_slug` (`slug`),
  CONSTRAINT `fk_property_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_property_builder` FOREIGN KEY (`builder_id`) REFERENCES `builders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_property_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_property_locality` FOREIGN KEY (`locality_id`) REFERENCES `localities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
