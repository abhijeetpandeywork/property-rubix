-- ============================================================
-- Migration 006: Add Project Gallery and Floor Plan Image Columns
-- Scope: Add gallery_images and floor_plan_images columns to projects table
-- Created: 2026-07-23
-- ============================================================

ALTER TABLE `projects` 
ADD COLUMN `gallery_images` LONGTEXT DEFAULT NULL AFTER `description`,
ADD COLUMN `floor_plan_images` LONGTEXT DEFAULT NULL AFTER `gallery_images`;
