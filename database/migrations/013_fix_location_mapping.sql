-- ============================================================
-- Migration 013: Fix Location Mapping
-- Fixes the issue where Mumbai was mapped to Canada/Toronto
-- ============================================================

SET @india_id = (SELECT id FROM countries WHERE slug = 'india' LIMIT 1);
INSERT IGNORE INTO states (id, country_id, name, slug) VALUES (1, @india_id, 'Maharashtra', 'maharashtra');
UPDATE states SET country_id = @india_id WHERE slug = 'maharashtra';
SET @maha_id = (SELECT id FROM states WHERE slug = 'maharashtra' LIMIT 1);

INSERT IGNORE INTO cities (id, state_id, name, slug) VALUES (1, @maha_id, 'Mumbai', 'mumbai');
-- In case Mumbai exists under a different ID, find it
SET @mumbai_id = (SELECT id FROM cities WHERE slug = 'mumbai' AND state_id = @maha_id LIMIT 1);

-- Ensure Locality 1 is Mahalaxmi and belongs to Mumbai
UPDATE localities SET city_id = @mumbai_id, name = 'Mahalaxmi', slug = 'mahalaxmi' WHERE id = 1;

-- Move any projects from 'Toronto' to 'Mumbai' if a separate city was created
UPDATE projects SET city_id = @mumbai_id WHERE city_id IN (SELECT id FROM cities WHERE slug = 'toronto');

-- If projects are still somehow linked to the old ID 1 (which was Toronto), move them to the real Mumbai
UPDATE projects SET city_id = @mumbai_id WHERE city_id = 1;
