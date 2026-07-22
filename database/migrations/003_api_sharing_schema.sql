-- ============================================================
-- Migration 003: API Sharing and Token Management
-- Scope: API token authentication, rate limit tracking, and partner configuration
-- Created: 2026-07-22
-- ============================================================

CREATE TABLE IF NOT EXISTS `api_tokens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` VARCHAR(150) NOT NULL COMMENT 'Name of the third-party client/partner',
  `client_email` VARCHAR(255) NOT NULL,
  `token_hash` VARCHAR(64) NOT NULL COMMENT 'SHA-256 hash of the generated API token',
  `token_preview` VARCHAR(15) NOT NULL COMMENT 'First 10 characters of the token for display (e.g. pr_live_...)',
  `scopes` TEXT NOT NULL COMMENT 'JSON array of access scopes, e.g. ["listings:read", "leads:write"]',
  `rate_limit_rpm` INT UNSIGNED NOT NULL DEFAULT 60 COMMENT 'Requests per minute allowed',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` DATETIME DEFAULT NULL,
  `last_used_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_token_hash` (`token_hash`),
  KEY `idx_active_expiry` (`is_active`, `expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `api_rate_limits` (
  `token_id` INT UNSIGNED NOT NULL,
  `request_minute` VARCHAR(16) NOT NULL COMMENT 'Format: YYYY-MM-DD HH:MM',
  `request_count` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`token_id`, `request_minute`),
  CONSTRAINT `fk_rate_token` FOREIGN KEY (`token_id`) REFERENCES `api_tokens` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
