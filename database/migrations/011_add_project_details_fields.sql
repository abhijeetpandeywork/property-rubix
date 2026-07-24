-- Migration: 011_add_project_details_fields
-- Adds 10 massive features to the project details

-- 1. Add fields to projects table
ALTER TABLE `projects` 
ADD COLUMN `project_logo` VARCHAR(255) DEFAULT NULL AFTER `banner_image`,
ADD COLUMN `contact_phone` VARCHAR(50) DEFAULT NULL AFTER `meta_description`,
ADD COLUMN `whatsapp_number` VARCHAR(50) DEFAULT NULL AFTER `contact_phone`,
ADD COLUMN `virtual_tour_url` VARCHAR(255) DEFAULT NULL AFTER `whatsapp_number`,
ADD COLUMN `rera_qr_code` VARCHAR(255) DEFAULT NULL AFTER `rera_verified`,
ADD COLUMN `connectivity` TEXT DEFAULT NULL AFTER `description`,
ADD COLUMN `highlights` TEXT DEFAULT NULL AFTER `connectivity`,
ADD COLUMN `marquee_text` VARCHAR(255) DEFAULT NULL AFTER `highlights`,
ADD COLUMN `interior_images` LONGTEXT DEFAULT NULL AFTER `gallery_images`,
ADD COLUMN `exterior_images` LONGTEXT DEFAULT NULL AFTER `interior_images`;

-- 2. Modify project_amenities to support uploaded images instead of just font icons
ALTER TABLE `project_amenities`
ADD COLUMN `image_path` VARCHAR(255) DEFAULT NULL AFTER `icon`;
