-- ============================================================
-- Migration 018: Enhance Floor Plans and Master Plan
-- Add cta_text and cta_url to project_floor_plans
-- Add master_plan_description and master_plan_pdf to projects
-- ============================================================

ALTER TABLE `project_floor_plans`
  ADD COLUMN `cta_text` VARCHAR(80) DEFAULT NULL AFTER `image`,
  ADD COLUMN `cta_url` VARCHAR(255) DEFAULT NULL AFTER `cta_text`;

ALTER TABLE `projects`
  ADD COLUMN `master_plan_description` TEXT DEFAULT NULL AFTER `master_plan_label`,
  ADD COLUMN `master_plan_pdf` VARCHAR(255) DEFAULT NULL AFTER `master_plan_description`;
