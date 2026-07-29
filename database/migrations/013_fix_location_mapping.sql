-- ============================================================
-- Migration 013: Fix Location Mapping
-- Fixes the issue where Mumbai was mapped to Canada/Toronto
-- ============================================================

-- Find India's ID
SET @india_id = (SELECT id FROM countries WHERE slug = 'india' LIMIT 1);

-- Force State 1 to be Maharashtra and belong to India
UPDATE states SET country_id = @india_id, name = 'Maharashtra', slug = 'maharashtra' WHERE id = 1;

-- Force City 1 to be Mumbai and belong to Maharashtra
UPDATE cities SET state_id = 1, name = 'Mumbai', slug = 'mumbai' WHERE id = 1;

-- Ensure Locality 1 is Mahalaxmi and belongs to Mumbai
UPDATE localities SET city_id = 1, name = 'Mahalaxmi', slug = 'mahalaxmi' WHERE id = 1;

-- Move any projects from 'Toronto' to 'Mumbai' if a separate city was created
UPDATE projects SET city_id = 1 WHERE city_id IN (SELECT id FROM cities WHERE slug = 'toronto');
