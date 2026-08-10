-- ============================================================
-- Migration 017: Add dedicated floor plan slots + master plan
-- 6 individual floor plans (image + label) + 1 master plan (image + label)
-- All existing project data is preserved — only new columns added
-- ============================================================

ALTER TABLE `projects`
  ADD COLUMN `fp_1_image`   VARCHAR(255) DEFAULT NULL AFTER `floor_plan_images`,
  ADD COLUMN `fp_1_label`   VARCHAR(120) DEFAULT NULL AFTER `fp_1_image`,
  ADD COLUMN `fp_2_image`   VARCHAR(255) DEFAULT NULL AFTER `fp_1_label`,
  ADD COLUMN `fp_2_label`   VARCHAR(120) DEFAULT NULL AFTER `fp_2_image`,
  ADD COLUMN `fp_3_image`   VARCHAR(255) DEFAULT NULL AFTER `fp_2_label`,
  ADD COLUMN `fp_3_label`   VARCHAR(120) DEFAULT NULL AFTER `fp_3_image`,
  ADD COLUMN `fp_4_image`   VARCHAR(255) DEFAULT NULL AFTER `fp_3_label`,
  ADD COLUMN `fp_4_label`   VARCHAR(120) DEFAULT NULL AFTER `fp_4_image`,
  ADD COLUMN `fp_5_image`   VARCHAR(255) DEFAULT NULL AFTER `fp_4_label`,
  ADD COLUMN `fp_5_label`   VARCHAR(120) DEFAULT NULL AFTER `fp_5_image`,
  ADD COLUMN `fp_6_image`   VARCHAR(255) DEFAULT NULL AFTER `fp_5_label`,
  ADD COLUMN `fp_6_label`   VARCHAR(120) DEFAULT NULL AFTER `fp_6_image`,
  ADD COLUMN `master_plan_image` VARCHAR(255) DEFAULT NULL AFTER `fp_6_label`,
  ADD COLUMN `master_plan_label` VARCHAR(120) DEFAULT 'Master Plan' AFTER `master_plan_image`;
