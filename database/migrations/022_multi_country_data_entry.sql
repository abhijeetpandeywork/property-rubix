-- ============================================================
-- Migration 022: Multi-Country Data Entry
-- Safe insertion of Builders and Projects for UAE, Canada, USA, and UK
-- ZERO impact on existing Godrej India projects
-- ============================================================

-- 1. BUILDERS / DEVELOPERS
-- UAE
INSERT INTO builders (name, slug, description, established_year, total_projects, country_id, status) VALUES
('Emaar Properties', 'emaar-properties', 'Global luxury master developer in Dubai, creator of Burj Khalifa and Dubai Mall.', 1997, 120, (SELECT id FROM countries WHERE slug IN ('united-arab-emirates', 'uae') LIMIT 1), 'active'),
('DAMAC Properties', 'damac-properties', 'Leading luxury real estate developer in the Middle East delivering iconic residential towers.', 2002, 85, (SELECT id FROM countries WHERE slug IN ('united-arab-emirates', 'uae') LIMIT 1), 'active'),
('Sobha Realty Dubai', 'sobha-realty-dubai', 'Multi-national luxury developer renowned for backward integration and flawless quality in Dubai.', 1976, 45, (SELECT id FROM countries WHERE slug IN ('united-arab-emirates', 'uae') LIMIT 1), 'active')
ON DUPLICATE KEY UPDATE status='active';

-- Canada
INSERT INTO builders (name, slug, description, established_year, total_projects, country_id, status) VALUES
('Menkes Developments', 'menkes-developments', 'Premier Canadian builder with over 65 years of creating landmark master-planned communities in Toronto.', 1954, 90, (SELECT id FROM countries WHERE slug='canada' LIMIT 1), 'active'),
('Tridel', 'tridel', 'Canadas top luxury condominium builder committed to green building and exceptional architecture.', 1934, 150, (SELECT id FROM countries WHERE slug='canada' LIMIT 1), 'active'),
('Pinnacle International', 'pinnacle-international', 'One of Canadas leading builders of luxury condominium residences, hotels, and commercial developments.', 1978, 60, (SELECT id FROM countries WHERE slug='canada' LIMIT 1), 'active')
ON DUPLICATE KEY UPDATE status='active';

-- USA
INSERT INTO builders (name, slug, description, established_year, total_projects, country_id, status) VALUES
('Extell Development', 'extell-development', 'Nationally acclaimed developer of residential, office, and hospitality properties in New York.', 1989, 40, (SELECT id FROM countries WHERE slug='usa' LIMIT 1), 'active'),
('Related Companies', 'related-companies', 'Premier real estate firm known for Hudson Yards and luxury developments across major US cities.', 1972, 110, (SELECT id FROM countries WHERE slug='usa' LIMIT 1), 'active')
ON DUPLICATE KEY UPDATE status='active';

-- UK
INSERT INTO builders (name, slug, description, established_year, total_projects, country_id, status) VALUES
('Berkeley Group', 'berkeley-group', 'Londons leading residential developer building fantastic luxury homes in prime locations.', 1976, 130, (SELECT id FROM countries WHERE slug='uk' LIMIT 1), 'active'),
('Ballymore', 'ballymore', 'Leader in urban regeneration and luxury riverside developments in Central London.', 1982, 50, (SELECT id FROM countries WHERE slug='uk' LIMIT 1), 'active')
ON DUPLICATE KEY UPDATE status='active';


-- 2. PROJECTS

-- UAE - Dubai Marina & Downtown Dubai Projects
SET @emaar_id = (SELECT id FROM builders WHERE slug = 'emaar-properties' LIMIT 1);
SET @damac_id = (SELECT id FROM builders WHERE slug = 'damac-properties' LIMIT 1);
SET @sobha_uae_id = (SELECT id FROM builders WHERE slug = 'sobha-realty-dubai' LIMIT 1);
SET @dubai_marina_city = (SELECT id FROM cities WHERE slug = 'dubai-marina' LIMIT 1);
SET @downtown_dubai_city = (SELECT id FROM cities WHERE slug = 'downtown-dubai' LIMIT 1);

SET @jbr_loc = (SELECT id FROM localities WHERE slug = 'jbr' AND city_id = @dubai_marina_city LIMIT 1);
SET @harbour_loc = (SELECT id FROM localities WHERE slug = 'dubai-harbour' AND city_id = @dubai_marina_city LIMIT 1);
SET @burj_loc = (SELECT id FROM localities WHERE slug = 'burj-khalifa-district' AND city_id = @downtown_dubai_city LIMIT 1);
SET @opera_loc = (SELECT id FROM localities WHERE slug = 'opera-district' AND city_id = @downtown_dubai_city LIMIT 1);

INSERT INTO projects (
    builder_id, city_id, locality_id, name, slug, type, status, 
    price_min, price_max, price_on_request, unit_types, area_range, 
    rera_id, rera_verified, address, location_area, short_description, description, 
    banner_image, thumbnail_image, is_featured, possession_date, contact_phone, whatsapp_number,
    highlights, connectivity, gallery_images
) VALUES 
(
    @emaar_id, @dubai_marina_city, @harbour_loc, 'Emaar Beachfront Residences', 'emaar-beachfront-residences', 'residential', 'under_construction',
    2500000.00, 7500000.00, 0, '1, 2, 3, 4 BHK', '750 - 2800 Sq.Ft.',
    'DLD-EB-101', 1, 'Dubai Harbour, Dubai Marina, UAE', 'Dubai Harbour',
    'Exclusive private island luxury waterfront residences overlooking the Arabian Gulf.',
    '<p>Emaar Beachfront is a prestigious residential community nestled in Dubai Harbour, featuring stunning uninterrupted views of the sea and Palm Jumeirah with private beach access.</p>',
    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=2070&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=2070&auto=format&fit=crop',
    1, 'Q4 2026', '+971 4 366 1688', '+971 50 123 4567',
    'Private Beach Access<br>Panoramic Gulf Views<br>Infinity Pool & Spa<br>Yacht Club Access',
    '{"Airport":"Dubai International Airport (DXB) - 20 Mins","Metro":"Sobha Realty Metro - 5 Mins","Malls":"Dubai Marina Mall - 7 Mins"}',
    '["https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=2070&auto=format&fit=crop","https://images.unsplash.com/photo-1580587771525-78b9dba3b914?q=80&w=1974&auto=format&fit=crop"]'
),
(
    @damac_id, @dubai_marina_city, @jbr_loc, 'DAMAC Cavalli Tower', 'damac-cavalli-tower', 'residential', 'under_construction',
    3200000.00, 11000000.00, 0, '2, 3, 4, 5 BHK', '1100 - 4500 Sq.Ft.',
    'DLD-CT-202', 1, 'JBR, Dubai Marina, UAE', 'JBR (Jumeirah Beach Residence)',
    'Ultra-luxury branded residences designed exclusively by Roberto Cavalli.',
    '<p>The only Cavalli-branded tower in the world, overlooking Palm Jumeirah and the sparkling waters of Dubai Marina.</p>',
    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=2070&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=2070&auto=format&fit=crop',
    1, 'Q2 2027', '+971 4 373 1000', '+971 50 123 4567',
    'Cavalli Branded Interiors<br>Private Sky Pool<br>Beach Access<br>24/7 Butler Service',
    '{"Airport":"Al Maktoum Airport - 25 Mins","Metro":"DMCC Metro Station - 4 Mins","Malls":"Mall of the Emirates - 12 Mins"}',
    '["https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=2070&auto=format&fit=crop","https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=2070&auto=format&fit=crop"]'
),
(
    @emaar_id, @downtown_dubai_city, @burj_loc, 'Burj Crown by Emaar', 'burj-crown-downtown', 'residential', 'ready_to_move',
    1850000.00, 4900000.00, 0, '1, 2, 3 BHK', '650 - 1800 Sq.Ft.',
    'DLD-BC-303', 1, 'Sheikh Mohammed bin Rashid Blvd, Downtown Dubai', 'Burj Khalifa District',
    'Prestigious high-rise residences steps away from the iconic Burj Khalifa.',
    '<p>Burj Crown offers refined urban living on the trendsetting Boulevard with direct views of Burj Khalifa and the Dubai Fountains.</p>',
    'https://images.unsplash.com/photo-1518684079-3c830dcef090?q=80&w=2070&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1518684079-3c830dcef090?q=80&w=2070&auto=format&fit=crop',
    1, 'Ready to Move', '+971 4 366 1688', '+971 50 123 4567',
    'Burj Khalifa View<br>Direct Boulevard Access<br>Rooftop Leisure Deck<br>Children Playground',
    '{"Airport":"Dubai Int Airport - 15 Mins","Metro":"Burj Khalifa/Dubai Mall Metro - 5 Mins","Malls":"Dubai Mall - 3 Mins"}',
    '["https://images.unsplash.com/photo-1518684079-3c830dcef090?q=80&w=2070&auto=format&fit=crop"]'
)
ON DUPLICATE KEY UPDATE name=VALUES(name), price_min=VALUES(price_min);


-- CANADA - Toronto Projects
SET @menkes_id = (SELECT id FROM builders WHERE slug = 'menkes-developments' LIMIT 1);
SET @tridel_id = (SELECT id FROM builders WHERE slug = 'tridel' LIMIT 1);
SET @pinnacle_id = (SELECT id FROM builders WHERE slug = 'pinnacle-international' LIMIT 1);
SET @toronto_city = (SELECT id FROM cities WHERE slug = 'toronto' LIMIT 1);

SET @downtown_to_loc = (SELECT id FROM localities WHERE slug = 'downtown-toronto' AND city_id = @toronto_city LIMIT 1);
SET @yorkville_loc = (SELECT id FROM localities WHERE slug = 'yorkville' AND city_id = @toronto_city LIMIT 1);
SET @waterfront_loc = (SELECT id FROM localities WHERE slug = 'waterfront' AND city_id = @toronto_city LIMIT 1);

INSERT INTO projects (
    builder_id, city_id, locality_id, name, slug, type, status, 
    price_min, price_max, price_on_request, unit_types, area_range, 
    rera_id, rera_verified, address, location_area, short_description, description, 
    banner_image, thumbnail_image, is_featured, possession_date, contact_phone, whatsapp_number,
    highlights, connectivity, gallery_images
) VALUES 
(
    @menkes_id, @toronto_city, @waterfront_loc, 'Sugar Wharf Residences', 'sugar-wharf-residences-toronto', 'residential', 'under_construction',
    680000.00, 2400000.00, 0, '1, 2, 3 BHK, Studio', '450 - 1650 Sq.Ft.',
    'TARION-SW-501', 1, '95 Lake Shore Blvd E, Toronto, ON, Canada', 'Waterfront',
    'Canadas largest master-planned waterfront community in Downtown Toronto.',
    '<p>Sugar Wharf is an exciting new community transforming the Toronto waterfront with luxury high-rise towers, new retail, school, and sprawling 2-acre park.</p>',
    'https://images.unsplash.com/photo-1517935703635-2719054540d2?q=80&w=2070&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1517935703635-2719054540d2?q=80&w=2070&auto=format&fit=crop',
    1, 'Q1 2027', '+1 416 491 2222', '+1 416 491 2222',
    'Lake Ontario Views<br>Direct PATH Access<br>Indoor Swimming Pool<br>2-Acre Public Park',
    '{"Transit":"Union Station - 8 Mins Walk","Highways":"Gardiner Expressway - 1 Min","Shopping":"Eaton Centre - 10 Mins"}',
    '["https://images.unsplash.com/photo-1517935703635-2719054540d2?q=80&w=2070&auto=format&fit=crop","https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=2070&auto=format&fit=crop"]'
),
(
    @tridel_id, @toronto_city, @yorkville_loc, 'The Well & Royal York Condos', 'royal-yorkville-condos', 'residential', 'new_launch',
    950000.00, 4200000.00, 0, '1, 2, 3, 4 BHK', '600 - 2400 Sq.Ft.',
    'TARION-YK-602', 1, 'Avenue Rd & Bloor St W, Yorkville, Toronto, Canada', 'Yorkville',
    'Ultra-prestige boutique condominiums in the cultural epicenter of Yorkville.',
    '<p>Located in Torontos most glamorous enclave, this architectural marvel offers bespoke finishes, valet service, and haute couture retail at your doorstep.</p>',
    'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=2080&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=2080&auto=format&fit=crop',
    1, 'Q3 2027', '+1 416 661 9290', '+1 416 661 9290',
    'Yorkville Mink Mile<br>Private Wine Cellars<br>Concierge & Valet<br>Rooftop Wellness Spa',
    '{"Subway":"Bay & Museum Stations - 2 Mins","University":"University of Toronto - 5 Mins","Museum":"Royal Ontario Museum - 3 Mins"}',
    '["https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=2080&auto=format&fit=crop"]'
)
ON DUPLICATE KEY UPDATE name=VALUES(name), price_min=VALUES(price_min);


-- USA - New York City Projects
SET @extell_id = (SELECT id FROM builders WHERE slug = 'extell-development' LIMIT 1);
SET @related_id = (SELECT id FROM builders WHERE slug = 'related-companies' LIMIT 1);
SET @nyc_city = (SELECT id FROM cities WHERE slug = 'new-york-city' LIMIT 1);

SET @manhattan_loc = (SELECT id FROM localities WHERE slug = 'manhattan' AND city_id = @nyc_city LIMIT 1);
SET @tribeca_loc = (SELECT id FROM localities WHERE slug = 'tribeca' AND city_id = @nyc_city LIMIT 1);

INSERT INTO projects (
    builder_id, city_id, locality_id, name, slug, type, status, 
    price_min, price_max, price_on_request, unit_types, area_range, 
    rera_id, rera_verified, address, location_area, short_description, description, 
    banner_image, thumbnail_image, is_featured, possession_date, contact_phone, whatsapp_number,
    highlights, connectivity, gallery_images
) VALUES 
(
    @extell_id, @nyc_city, @manhattan_loc, 'Central Park Tower NYC', 'central-park-tower-nyc', 'residential', 'ready_to_move',
    4500000.00, 28000000.00, 0, '2, 3, 4, 5 BHK', '1500 - 8000 Sq.Ft.',
    'NY-DOB-CPT', 1, '217 W 57th St, Billionaires Row, New York, NY, USA', 'Manhattan',
    'The tallest residential tower in the world rising above Central Park on Billionaires Row.',
    '<p>Central Park Tower defines luxury on a global scale, offering dramatic 360-degree vistas across Central Park, Manhattan, and the rivers.</p>',
    'https://images.unsplash.com/photo-1534430480872-3498386e7856?q=80&w=2070&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1534430480872-3498386e7856?q=80&w=2070&auto=format&fit=crop',
    1, 'Ready to Move', '+1 212 555 0199', '+1 212 555 0199',
    'Direct Central Park Views<br>100th Floor Private Club<br>Indoor & Outdoor Pools<br>Billionaires Row Location',
    '{"Subway":"Columbus Circle - 2 Mins","Park":"Central Park - 1 Block","Airport":"JFK International - 35 Mins"}',
    '["https://images.unsplash.com/photo-1534430480872-3498386e7856?q=80&w=2070&auto=format&fit=crop"]'
)
ON DUPLICATE KEY UPDATE name=VALUES(name), price_min=VALUES(price_min);


-- UK - London Projects
SET @berkeley_id = (SELECT id FROM builders WHERE slug = 'berkeley-group' LIMIT 1);
SET @london_city = (SELECT id FROM cities WHERE slug = 'london' LIMIT 1);
SET @mayfair_loc = (SELECT id FROM localities WHERE slug = 'mayfair' AND city_id = @london_city LIMIT 1);
SET @canary_loc = (SELECT id FROM localities WHERE slug = 'canary-wharf' AND city_id = @london_city LIMIT 1);

INSERT INTO projects (
    builder_id, city_id, locality_id, name, slug, type, status, 
    price_min, price_max, price_on_request, unit_types, area_range, 
    rera_id, rera_verified, address, location_area, short_description, description, 
    banner_image, thumbnail_image, is_featured, possession_date, contact_phone, whatsapp_number,
    highlights, connectivity, gallery_images
) VALUES 
(
    @berkeley_id, @london_city, @canary_loc, 'South Quay Plaza London', 'south-quay-plaza-london', 'residential', 'under_construction',
    750000.00, 3200000.00, 0, '1, 2, 3 BHK, Studio', '550 - 2100 Sq.Ft.',
    'UK-NHBC-SQP', 1, 'Marsh Wall, Canary Wharf, London, UK', 'Canary Wharf',
    'Iconic Foster + Partners designed waterfront residential tower in London Canary Wharf.',
    '<p>South Quay Plaza sets a new benchmark in London luxury living with high-specification apartments, private 56th-floor terrace, and 2.6 acres of landscaped gardens.</p>',
    'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?q=80&w=2070&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?q=80&w=2070&auto=format&fit=crop',
    1, 'Q4 2026', '+44 20 3675 4400', '+44 20 3675 4400',
    'Foster + Partners Design<br>56th Floor Sky Lounge<br>20m Swimming Pool<br>2.6 Acres Waterfront Gardens',
    '{"Underground":"Canary Wharf Tube - 3 Mins","DLR":"South Quay DLR - 1 Min","Elizabeth Line":"Canary Wharf Crossrail - 4 Mins"}',
    '["https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?q=80&w=2070&auto=format&fit=crop"]'
)
ON DUPLICATE KEY UPDATE name=VALUES(name), price_min=VALUES(price_min);
