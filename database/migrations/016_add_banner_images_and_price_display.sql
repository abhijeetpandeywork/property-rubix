-- ============================================================
-- Migration 016: Add banner_images and price_display to projects
-- ============================================================

ALTER TABLE `projects`
ADD COLUMN `banner_images` LONGTEXT DEFAULT NULL AFTER `banner_image`,
ADD COLUMN `price_display` VARCHAR(255) DEFAULT NULL AFTER `price_on_request`;
