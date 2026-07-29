-- ============================================================
-- Migration 014: Add continent field to countries
-- ============================================================

ALTER TABLE `countries` 
ADD COLUMN `continent` VARCHAR(100) DEFAULT 'Asia-Pacific' AFTER `slug`;

-- Set defaults based on existing sample data
UPDATE `countries` SET `continent` = 'Asia-Pacific' WHERE `slug` = 'india';
UPDATE `countries` SET `continent` = 'Middle East' WHERE `slug` = 'uae';
UPDATE `countries` SET `continent` = 'North America' WHERE `slug` IN ('usa', 'canada');
UPDATE `countries` SET `continent` = 'Europe' WHERE `slug` = 'uk';
