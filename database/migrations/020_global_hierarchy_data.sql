-- ============================================================
-- Migration 020: Global Country and City Data Entry
-- Safe insertion of countries, states, and cities
-- ============================================================

UPDATE countries SET flag_icon = '🇮🇳', continent = 'Asia-Pacific' WHERE slug = 'india';
UPDATE countries SET flag_icon = '🇦🇪', continent = 'Middle East' WHERE slug = 'united-arab-emirates' OR slug = 'uae';

INSERT INTO countries (name, slug, flag_icon, continent, sort_order, status) VALUES 
('Canada', 'canada', '🇨🇦', 'North America', 3, 'active'),
('USA', 'usa', '🇺🇸', 'North America', 4, 'active'),
('United Kingdom', 'uk', '🇬🇧', 'Europe', 5, 'active')
ON DUPLICATE KEY UPDATE flag_icon=VALUES(flag_icon), continent=VALUES(continent), status='active';

-- Add UAE States and Cities
SET @uae_id = (SELECT id FROM countries WHERE slug IN ('united-arab-emirates', 'uae') LIMIT 1);
INSERT IGNORE INTO states (country_id, name, slug) VALUES 
(@uae_id, 'Dubai', 'dubai'),
(@uae_id, 'Abu Dhabi', 'abu-dhabi');

SET @dubai_state_id = (SELECT id FROM states WHERE country_id = @uae_id AND slug = 'dubai' LIMIT 1);
INSERT IGNORE INTO cities (state_id, name, slug) VALUES 
(@dubai_state_id, 'Dubai Marina', 'dubai-marina'),
(@dubai_state_id, 'Downtown Dubai', 'downtown-dubai'),
(@dubai_state_id, 'Palm Jumeirah', 'palm-jumeirah'),
(@dubai_state_id, 'Business Bay', 'business-bay');

-- Add Canada States and Cities
SET @canada_id = (SELECT id FROM countries WHERE slug = 'canada' LIMIT 1);
INSERT IGNORE INTO states (country_id, name, slug) VALUES 
(@canada_id, 'Ontario', 'ontario'),
(@canada_id, 'British Columbia', 'british-columbia');

SET @ontario_id = (SELECT id FROM states WHERE country_id = @canada_id AND slug = 'ontario' LIMIT 1);
INSERT IGNORE INTO cities (state_id, name, slug) VALUES 
(@ontario_id, 'Toronto', 'toronto'),
(@ontario_id, 'Mississauga', 'mississauga'),
(@ontario_id, 'Ottawa', 'ottawa');

-- Add USA States and Cities
SET @usa_id = (SELECT id FROM countries WHERE slug = 'usa' LIMIT 1);
INSERT IGNORE INTO states (country_id, name, slug) VALUES 
(@usa_id, 'California', 'california'),
(@usa_id, 'New York', 'new-york'),
(@usa_id, 'Florida', 'florida');

SET @ny_id = (SELECT id FROM states WHERE country_id = @usa_id AND slug = 'new-york' LIMIT 1);
INSERT IGNORE INTO cities (state_id, name, slug) VALUES 
(@ny_id, 'New York City', 'new-york-city'),
(@ny_id, 'Brooklyn', 'brooklyn');

-- Add UK States and Cities
SET @uk_id = (SELECT id FROM countries WHERE slug = 'uk' LIMIT 1);
INSERT IGNORE INTO states (country_id, name, slug) VALUES 
(@uk_id, 'Greater London', 'greater-london');

SET @london_id = (SELECT id FROM states WHERE country_id = @uk_id AND slug = 'greater-london' LIMIT 1);
INSERT IGNORE INTO cities (state_id, name, slug) VALUES 
(@london_id, 'London', 'london');
