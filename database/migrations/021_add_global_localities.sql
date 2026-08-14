-- ============================================================
-- Migration 021: Add Localities for Global Cities (Canada, UAE, USA, UK)
-- Safe insertion of popular neighborhoods for global cities
-- ============================================================

-- Localities for Toronto (Canada)
SET @toronto_id = (SELECT id FROM cities WHERE slug = 'toronto' LIMIT 1);
INSERT IGNORE INTO localities (city_id, name, slug, status, sort_order) VALUES
(@toronto_id, 'Downtown Toronto', 'downtown-toronto', 'active', 1),
(@toronto_id, 'Yorkville', 'yorkville', 'active', 2),
(@toronto_id, 'King West', 'king-west', 'active', 3),
(@toronto_id, 'Waterfront', 'waterfront', 'active', 4),
(@toronto_id, 'North York', 'north-york', 'active', 5);

-- Localities for Dubai Marina & Downtown Dubai (UAE)
SET @dubai_marina_id = (SELECT id FROM cities WHERE slug = 'dubai-marina' LIMIT 1);
INSERT IGNORE INTO localities (city_id, name, slug, status, sort_order) VALUES
(@dubai_marina_id, 'Marina Walk', 'marina-walk', 'active', 1),
(@dubai_marina_id, 'JBR (Jumeirah Beach Residence)', 'jbr', 'active', 2),
(@dubai_marina_id, 'Dubai Harbour', 'dubai-harbour', 'active', 3);

SET @downtown_dubai_id = (SELECT id FROM cities WHERE slug = 'downtown-dubai' LIMIT 1);
INSERT IGNORE INTO localities (city_id, name, slug, status, sort_order) VALUES
(@downtown_dubai_id, 'Burj Khalifa District', 'burj-khalifa-district', 'active', 1),
(@downtown_dubai_id, 'Opera District', 'opera-district', 'active', 2),
(@downtown_dubai_id, 'Old Town', 'old-town', 'active', 3);

-- Localities for New York City (USA)
SET @nyc_id = (SELECT id FROM cities WHERE slug = 'new-york-city' LIMIT 1);
INSERT IGNORE INTO localities (city_id, name, slug, status, sort_order) VALUES
(@nyc_id, 'Manhattan', 'manhattan', 'active', 1),
(@nyc_id, 'Tribeca', 'tribeca', 'active', 2),
(@nyc_id, 'Upper East Side', 'upper-east-side', 'active', 3),
(@nyc_id, 'SoHo', 'soho', 'active', 4);

-- Localities for London (UK)
SET @london_id = (SELECT id FROM cities WHERE slug = 'london' LIMIT 1);
INSERT IGNORE INTO localities (city_id, name, slug, status, sort_order) VALUES
(@london_id, 'Mayfair', 'mayfair', 'active', 1),
(@london_id, 'Kensington', 'kensington', 'active', 2),
(@london_id, 'Canary Wharf', 'canary-wharf', 'active', 3),
(@london_id, 'Chelsea', 'chelsea', 'active', 4);
