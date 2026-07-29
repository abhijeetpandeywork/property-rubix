-- ============================================================
-- Migration 013: Fix Location Mapping
-- Fixes the issue where Mumbai was mapped to Canada/Toronto
-- ============================================================

-- Find India's ID
SET @india_id = (SELECT id FROM countries WHERE slug = 'india' LIMIT 1);

-- We want Mumbai to belong to Maharashtra which belongs to India.
-- Ensure Maharashtra exists under India
INSERT IGNORE INTO states (id, country_id, name, slug) VALUES (1, @india_id, 'Maharashtra', 'maharashtra');
UPDATE states SET country_id = @india_id WHERE slug = 'maharashtra';

SET @maha_id = (SELECT id FROM states WHERE slug = 'maharashtra' LIMIT 1);

-- Ensure Mumbai exists under Maharashtra
UPDATE cities SET state_id = @maha_id, name = 'Mumbai', slug = 'mumbai' WHERE id = 1;

-- Ensure Locality 1 is Mahalaxmi and belongs to Mumbai
UPDATE localities SET city_id = 1, name = 'Mahalaxmi', slug = 'mahalaxmi' WHERE id = 1;

-- Move any projects from 'Toronto' to 'Mumbai' if a separate city was created
UPDATE projects SET city_id = 1 WHERE city_id IN (SELECT id FROM cities WHERE slug = 'toronto');
