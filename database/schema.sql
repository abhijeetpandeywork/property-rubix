-- ============================================================
-- PropertyRubix — Real Estate Portal Database Schema
-- Compatible: MySQL 5.7+ / MariaDB 10.3+
-- DB Name: property_rubix
-- Import: schema.sql FIRST, then seed.sql
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ============================================================
-- DROP ALL TABLES (clean re-import)
-- ============================================================
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `audit_log`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `wa_templates`;
DROP TABLE IF EXISTS `crm_sync_logs`;
DROP TABLE IF EXISTS `field_setup`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `labels`;
DROP TABLE IF EXISTS `translations`;
DROP TABLE IF EXISTS `branding_settings`;
DROP TABLE IF EXISTS `faqs`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `awards`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `testimonials`;
DROP TABLE IF EXISTS `newsletters`;
DROP TABLE IF EXISTS `subscribers`;
DROP TABLE IF EXISTS `blog_posts`;
DROP TABLE IF EXISTS `blog_categories`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `submissions`;
DROP TABLE IF EXISTS `lead_routing_rules`;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `commissions`;
DROP TABLE IF EXISTS `deals`;
DROP TABLE IF EXISTS `clients`;
DROP TABLE IF EXISTS `leads`;
DROP TABLE IF EXISTS `project_category_map`;
DROP TABLE IF EXISTS `project_amenities`;
DROP TABLE IF EXISTS `project_floor_plans`;
DROP TABLE IF EXISTS `project_images`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `builders`;
DROP TABLE IF EXISTS `cities`;
DROP TABLE IF EXISTS `states`;
DROP TABLE IF EXISTS `countries`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- GEOGRAPHY
-- ============================================================

CREATE TABLE `countries` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`      VARCHAR(100) NOT NULL,
  `slug`      VARCHAR(120) NOT NULL,
  `flag_icon` VARCHAR(10)  DEFAULT NULL COMMENT 'emoji flag e.g. 🇮🇳',
  `sort_order` INT NOT NULL DEFAULT 0,
  `status`    ENUM('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `states` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_id` INT UNSIGNED NOT NULL,
  `name`       VARCHAR(100) NOT NULL,
  `slug`       VARCHAR(120) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_state_slug` (`country_id`, `slug`),
  KEY `idx_country` (`country_id`),
  CONSTRAINT `fk_state_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cities` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `state_id`         INT UNSIGNED NOT NULL,
  `name`             VARCHAR(100) NOT NULL,
  `slug`             VARCHAR(120) NOT NULL,
  `banner_image`     VARCHAR(255) DEFAULT NULL,
  `meta_title`       VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `sort_order`       INT NOT NULL DEFAULT 0,
  `status`           ENUM('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_city_slug` (`state_id`, `slug`),
  KEY `idx_state` (`state_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_city_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- BUILDERS / DEVELOPERS
-- ============================================================

CREATE TABLE `builders` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`            VARCHAR(255) NOT NULL,
  `slug`            VARCHAR(255) NOT NULL,
  `logo`            VARCHAR(255) DEFAULT NULL,
  `description`     TEXT DEFAULT NULL,
  `website`         VARCHAR(255) DEFAULT NULL,
  `established_year` YEAR DEFAULT NULL,
  `total_projects`  INT DEFAULT 0,
  `country_id`      INT UNSIGNED DEFAULT NULL,
  `status`          ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`),
  KEY `idx_country` (`country_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_builder_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PROJECTS / PROPERTIES
-- ============================================================

CREATE TABLE `categories` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(120) NOT NULL,
  `slug`       VARCHAR(120) NOT NULL,
  `type`       ENUM('blog','project','service') NOT NULL DEFAULT 'project',
  `icon`       VARCHAR(80) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug_type` (`slug`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `projects` (
  `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `builder_id`        INT UNSIGNED DEFAULT NULL,
  `city_id`           INT UNSIGNED DEFAULT NULL,
  `name`              VARCHAR(255) NOT NULL,
  `slug`              VARCHAR(255) NOT NULL,
  `type`              ENUM('residential','commercial','plot') NOT NULL DEFAULT 'residential',
  `status`            ENUM('upcoming','under_construction','ready_to_move','new_launch') NOT NULL DEFAULT 'upcoming',
  `price_min`         DECIMAL(15,2) DEFAULT NULL,
  `price_max`         DECIMAL(15,2) DEFAULT NULL,
  `price_on_request`  TINYINT(1) NOT NULL DEFAULT 0,
  `unit_types`        VARCHAR(255) DEFAULT NULL COMMENT 'e.g. 2BHK, 3BHK, 4BHK',
  `area_range`        VARCHAR(100) DEFAULT NULL,
  `rera_id`           VARCHAR(100) DEFAULT NULL,
  `rera_verified`     TINYINT(1) NOT NULL DEFAULT 0,
  `address`           TEXT DEFAULT NULL,
  `location_area`     VARCHAR(255) DEFAULT NULL,
  `latitude`          DECIMAL(10,8) DEFAULT NULL,
  `longitude`         DECIMAL(11,8) DEFAULT NULL,
  `map_embed_url`     TEXT DEFAULT NULL,
  `short_description` TEXT DEFAULT NULL,
  `description`       LONGTEXT DEFAULT NULL,
  `banner_image`      VARCHAR(255) DEFAULT NULL,
  `thumbnail_image`   VARCHAR(255) DEFAULT NULL,
  `brochure_pdf`      VARCHAR(255) DEFAULT NULL,
  `video_url`         VARCHAR(255) DEFAULT NULL,
  `possession_date`   VARCHAR(80) DEFAULT NULL,
  `is_featured`       TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order`        INT NOT NULL DEFAULT 0,
  `meta_title`        VARCHAR(255) DEFAULT NULL,
  `meta_description`  TEXT DEFAULT NULL,
  `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`),
  KEY `idx_builder` (`builder_id`),
  KEY `idx_city` (`city_id`),
  KEY `idx_status` (`status`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_type` (`type`),
  CONSTRAINT `fk_project_builder` FOREIGN KEY (`builder_id`) REFERENCES `builders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_project_city`    FOREIGN KEY (`city_id`)    REFERENCES `cities` (`id`)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `project_images` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `alt_text`   VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_project` (`project_id`),
  CONSTRAINT `fk_pi_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `project_floor_plans` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id`    INT UNSIGNED NOT NULL,
  `plan_name`     VARCHAR(120) DEFAULT NULL,
  `configuration` VARCHAR(80)  DEFAULT NULL,
  `area`          VARCHAR(80)  DEFAULT NULL,
  `price`         VARCHAR(80)  DEFAULT NULL,
  `price_numeric` DECIMAL(15,2) DEFAULT NULL,
  `image`         VARCHAR(255) DEFAULT NULL,
  `sort_order`    INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_project` (`project_id`),
  CONSTRAINT `fk_fp_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `project_amenities` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id`   INT UNSIGNED NOT NULL,
  `amenity_name` VARCHAR(120) NOT NULL,
  `icon`         VARCHAR(80)  DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_project` (`project_id`),
  CONSTRAINT `fk_pa_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `project_category_map` (
  `project_id`  INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`project_id`, `category_id`),
  CONSTRAINT `fk_pcm_project`  FOREIGN KEY (`project_id`)  REFERENCES `projects`   (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pcm_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CRM
-- ============================================================

CREATE TABLE `leads` (
  `id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`               VARCHAR(120) NOT NULL,
  `email`              VARCHAR(150) DEFAULT NULL,
  `phone`              VARCHAR(20)  NOT NULL,
  `source`             ENUM('site_visit_form','contact_form','whatsapp','call','chatbot','import') NOT NULL DEFAULT 'contact_form',
  `project_id`         INT UNSIGNED DEFAULT NULL,
  `city_id`            INT UNSIGNED DEFAULT NULL,
  `message`            TEXT DEFAULT NULL,
  `status`             ENUM('new','contacted','qualified','lost','converted') NOT NULL DEFAULT 'new',
  `assigned_to_user_id` INT UNSIGNED DEFAULT NULL,
  `notes`              TEXT DEFAULT NULL,
  `ip_address`         VARCHAR(45) DEFAULT NULL,
  `created_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_project` (`project_id`),
  KEY `idx_city` (`city_id`),
  KEY `idx_assigned` (`assigned_to_user_id`),
  CONSTRAINT `fk_lead_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_lead_city`    FOREIGN KEY (`city_id`)    REFERENCES `cities`   (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `clients` (
  `id`                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`                 VARCHAR(120) NOT NULL,
  `email`                VARCHAR(150) DEFAULT NULL,
  `phone`                VARCHAR(20)  DEFAULT NULL,
  `converted_from_lead_id` INT UNSIGNED DEFAULT NULL,
  `notes`                TEXT DEFAULT NULL,
  `created_at`           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lead` (`converted_from_lead_id`),
  CONSTRAINT `fk_client_lead` FOREIGN KEY (`converted_from_lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `deals` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lead_id`     INT UNSIGNED DEFAULT NULL,
  `project_id`  INT UNSIGNED DEFAULT NULL,
  `client_id`   INT UNSIGNED DEFAULT NULL,
  `deal_value`  DECIMAL(15,2) DEFAULT NULL,
  `stage`       ENUM('negotiation','booked','closed_won','closed_lost') NOT NULL DEFAULT 'negotiation',
  `notes`       TEXT DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lead`    (`lead_id`),
  KEY `idx_project` (`project_id`),
  KEY `idx_client`  (`client_id`),
  KEY `idx_stage`   (`stage`),
  CONSTRAINT `fk_deal_lead`    FOREIGN KEY (`lead_id`)    REFERENCES `leads`    (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_deal_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_deal_client`  FOREIGN KEY (`client_id`)  REFERENCES `clients`  (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `commissions` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `deal_id`       INT UNSIGNED NOT NULL,
  `agent_user_id` INT UNSIGNED DEFAULT NULL,
  `amount`        DECIMAL(15,2) DEFAULT NULL,
  `percentage`    DECIMAL(5,2)  DEFAULT NULL,
  `status`        ENUM('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_at`       DATETIME DEFAULT NULL,
  `notes`         TEXT DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_deal`  (`deal_id`),
  KEY `idx_agent` (`agent_user_id`),
  CONSTRAINT `fk_comm_deal` FOREIGN KEY (`deal_id`) REFERENCES `deals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tasks` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `assigned_to_user_id` INT UNSIGNED DEFAULT NULL,
  `related_lead_id`  INT UNSIGNED DEFAULT NULL,
  `title`            VARCHAR(255) NOT NULL,
  `description`      TEXT DEFAULT NULL,
  `due_date`         DATE DEFAULT NULL,
  `status`           ENUM('open','in_progress','done') NOT NULL DEFAULT 'open',
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_assigned` (`assigned_to_user_id`),
  KEY `idx_lead`     (`related_lead_id`),
  KEY `idx_status`   (`status`),
  CONSTRAINT `fk_task_lead` FOREIGN KEY (`related_lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lead_routing_rules` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `city_id`          INT UNSIGNED DEFAULT NULL,
  `project_id`       INT UNSIGNED DEFAULT NULL,
  `assign_to_user_id` INT UNSIGNED NOT NULL,
  `priority`         INT NOT NULL DEFAULT 0,
  `is_active`        TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_city`    (`city_id`),
  KEY `idx_project` (`project_id`),
  CONSTRAINT `fk_lr_city`    FOREIGN KEY (`city_id`)    REFERENCES `cities`   (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_lr_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `submissions` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_type`  VARCHAR(50) NOT NULL DEFAULT 'contact' COMMENT 'site_visit, contact, enquiry, etc.',
  `name`       VARCHAR(120) DEFAULT NULL,
  `email`      VARCHAR(150) DEFAULT NULL,
  `phone`      VARCHAR(20)  DEFAULT NULL,
  `payload`    JSON DEFAULT NULL,
  `status`     ENUM('new','processed') NOT NULL DEFAULT 'new',
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_form_type` (`form_type`),
  KEY `idx_status`    (`status`),
  KEY `idx_created`   (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MARKETING
-- ============================================================

CREATE TABLE `subscribers` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`         VARCHAR(150) NOT NULL,
  `subscribed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status`        ENUM('active','unsubscribed') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `newsletters` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject`    VARCHAR(255) NOT NULL,
  `body`       LONGTEXT DEFAULT NULL,
  `status`     ENUM('draft','sent') NOT NULL DEFAULT 'draft',
  `sent_at`    DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CONTENT
-- ============================================================

CREATE TABLE `blog_categories` (
  `id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `blog_posts` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(255) NOT NULL,
  `slug`             VARCHAR(255) NOT NULL,
  `category_id`      INT UNSIGNED DEFAULT NULL,
  `author`           VARCHAR(120) DEFAULT 'Admin',
  `cover_image`      VARCHAR(255) DEFAULT NULL,
  `excerpt`          TEXT DEFAULT NULL,
  `body`             LONGTEXT DEFAULT NULL,
  `meta_title`       VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `status`           ENUM('published','draft') NOT NULL DEFAULT 'draft',
  `published_at`     DATETIME DEFAULT NULL,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`),
  KEY `idx_category`  (`category_id`),
  KEY `idx_status`    (`status`),
  KEY `idx_published` (`published_at`),
  CONSTRAINT `fk_blog_cat` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pages` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(255) NOT NULL,
  `slug`             VARCHAR(120) NOT NULL,
  `body`             LONGTEXT DEFAULT NULL,
  `meta_title`       VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `status`           ENUM('published','draft') NOT NULL DEFAULT 'published',
  `sort_order`       INT NOT NULL DEFAULT 0,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `testimonials` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(120) NOT NULL,
  `designation` VARCHAR(150) DEFAULT NULL,
  `photo`       VARCHAR(255) DEFAULT NULL,
  `message`     TEXT NOT NULL,
  `rating`      TINYINT(1) NOT NULL DEFAULT 5,
  `project_id`  INT UNSIGNED DEFAULT NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `sort_order`  INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_testi_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reviews` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` INT UNSIGNED NOT NULL,
  `name`       VARCHAR(120) NOT NULL,
  `rating`     TINYINT(1) NOT NULL DEFAULT 5,
  `comment`    TEXT DEFAULT NULL,
  `status`     ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_project` (`project_id`),
  KEY `idx_status`  (`status`),
  CONSTRAINT `fk_review_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `awards` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`      VARCHAR(255) NOT NULL,
  `year`       YEAR DEFAULT NULL,
  `image`      VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `services` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255) NOT NULL,
  `icon`        VARCHAR(80)  DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `faqs` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question`   TEXT NOT NULL,
  `answer`     TEXT NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status`     ENUM('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SYSTEM / ADMIN
-- ============================================================

CREATE TABLE `branding_settings` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_name`       VARCHAR(255) DEFAULT 'PropertyRubix',
  `logo`            VARCHAR(255) DEFAULT NULL,
  `favicon`         VARCHAR(255) DEFAULT NULL,
  `primary_color`   VARCHAR(20)  DEFAULT '#16a34a',
  `secondary_color` VARCHAR(20)  DEFAULT '#0f172a',
  `tagline`         VARCHAR(255) DEFAULT NULL,
  `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `translations` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lang_code` VARCHAR(10) NOT NULL DEFAULT 'en',
  `key_name`  VARCHAR(255) NOT NULL,
  `value`     TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lang_key` (`lang_code`, `key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `labels` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key_name`     VARCHAR(255) NOT NULL,
  `default_text` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_key` (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `settings` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key_name`   VARCHAR(100) NOT NULL,
  `value`      LONGTEXT DEFAULT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_key` (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `field_setup` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity`      VARCHAR(50) NOT NULL COMMENT 'leads, projects, etc.',
  `field_name`  VARCHAR(100) NOT NULL,
  `field_type`  ENUM('text','number','date','select','checkbox','textarea') NOT NULL DEFAULT 'text',
  `options`     TEXT DEFAULT NULL COMMENT 'JSON array for select options',
  `is_required` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order`  INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `crm_sync_logs` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `direction`  ENUM('outbound','inbound') NOT NULL DEFAULT 'outbound',
  `payload`    LONGTEXT DEFAULT NULL,
  `status`     ENUM('success','error','pending') NOT NULL DEFAULT 'pending',
  `response`   TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `wa_templates` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(100) NOT NULL,
  `template_body` TEXT NOT NULL,
  `variables`     TEXT DEFAULT NULL COMMENT 'JSON array of variable names',
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(120) NOT NULL,
  `email`         VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role`          ENUM('super_admin','admin','agent','editor') NOT NULL DEFAULT 'agent',
  `status`        ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `last_login`    DATETIME DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role`       ENUM('super_admin','admin','agent','editor') NOT NULL,
  `module`     VARCHAR(100) NOT NULL,
  `can_view`   TINYINT(1) NOT NULL DEFAULT 1,
  `can_edit`   TINYINT(1) NOT NULL DEFAULT 0,
  `can_delete` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_module` (`role`, `module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `audit_log` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED DEFAULT NULL,
  `action`     VARCHAR(50) NOT NULL,
  `entity`     VARCHAR(100) DEFAULT NULL,
  `entity_id`  INT UNSIGNED DEFAULT NULL,
  `old_value`  LONGTEXT DEFAULT NULL,
  `new_value`  LONGTEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user`    (`user_id`),
  KEY `idx_entity`  (`entity`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- END OF SCHEMA
-- ============================================================
