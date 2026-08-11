-- ============================================================
-- Migration 019: Add master_plans_json to projects
-- Supports unlimited dynamic master plans & site layouts
-- ============================================================

ALTER TABLE `projects`
  ADD COLUMN `master_plans_json` LONGTEXT DEFAULT NULL AFTER `master_plan_pdf`;
