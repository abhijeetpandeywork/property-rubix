-- ============================================================
-- Migration 002: Seed Data
-- PropertyRubix — Default seed data (settings, countries, builders, admin user, etc.)
-- Run: php database/migrate.php
-- Created: 2024-01-01
-- ============================================================

-- Default settings
INSERT IGNORE INTO `settings` (`key_name`, `value`) VALUES
('site_name',         'PropertyRubix'),
('site_tagline',      'Find Your Perfect Property'),
('contact_email',     'info@propertyrubix.com'),
('contact_phone',     ''),
('properties_per_page', '12'),
('projects_per_page', '12'),
('google_maps_key',   ''),
('meta_description',  'Find your perfect property with PropertyRubix');

-- Default branding
INSERT IGNORE INTO `branding_settings` (`id`, `site_name`, `primary_color`, `secondary_color`, `tagline`)
VALUES (1, 'PropertyRubix', '#a9804b', '#0f172a', 'Find Your Perfect Property');

-- Default countries
INSERT IGNORE INTO `countries` (`name`, `slug`, `flag_icon`, `status`) VALUES
('United States', 'usa', '🇺🇸', 'active'),
('Canada', 'canada', '🇨🇦', 'active'),
('United Kingdom', 'uk', '🇬🇧', 'active'),
('United Arab Emirates', 'uae', '🇦🇪', 'active'),
('India', 'india', '🇮🇳', 'active');

-- Default builders
INSERT IGNORE INTO `builders` (`name`, `slug`, `established_year`, `total_projects`, `status`) VALUES
('Godrej Properties', 'godrej-properties', 1990, 87, 'active'),
('DLF', 'dlf', 1946, 120, 'active'),
('Prestige Group', 'prestige-group', 1986, 210, 'active');

-- Default super admin user (password: admin123 — CHANGE IMMEDIATELY)
-- Password hash for 'admin123'
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `status`)
VALUES (
    1,
    'Super Admin',
    'admin@propertyrubix.com',
    '$2y$12$xhWQf2X8DcF5S2V6K7V.2e0LHNFqY1G5mRBHPQ1MzBVMuWOzDUWVG',
    'super_admin',
    'active'
);

-- Default permissions for admin role
INSERT IGNORE INTO `permissions` (`role`, `module`, `can_view`, `can_edit`, `can_delete`) VALUES
('admin', 'all', 1, 1, 0),
('editor', 'properties', 1, 1, 0),
('editor', 'projects', 1, 1, 0),
('editor', 'blog', 1, 1, 0);
