-- ============================================================
-- Migration 005: Fix Projects Columns Mismatch
-- Scope: Add missing fields total_area, total_units, map_url, and amenities to projects table
-- Created: 2026-07-23
-- ============================================================

ALTER TABLE `projects` 
ADD COLUMN `total_area` VARCHAR(100) DEFAULT NULL AFTER `area_range`,
ADD COLUMN `total_units` INT DEFAULT NULL AFTER `total_area`,
ADD COLUMN `map_url` TEXT DEFAULT NULL AFTER `map_embed_url`,
ADD COLUMN `amenities` TEXT DEFAULT NULL AFTER `description`;
