-- ============================================================
-- Migration 002: Seed Data
-- PropertyRubix — Default seed data (settings, admin user, etc.)
-- Run: php database/migrate.php
-- Created: 2024-01-01
-- ============================================================

-- Default settings
INSERT IGNORE INTO `settings` (`key_name`, `value`, `label`, `type`) VALUES
('site_name',         'PropertyRubix',             'Site Name',         'text'),
('site_tagline',      'Find Your Perfect Property', 'Site Tagline',      'text'),
('contact_email',     'info@propertyrubix.com',    'Contact Email',     'email'),
('contact_phone',     '',                          'Contact Phone',     'text'),
('properties_per_page', '12',                      'Properties Per Page','number'),
('projects_per_page', '12',                        'Projects Per Page', 'number'),
('google_maps_key',   '',                          'Google Maps API Key','text'),
('meta_description',  'Find your perfect property with PropertyRubix', 'Default Meta Description', 'textarea');

-- Default branding
INSERT IGNORE INTO `branding_settings` (`id`, `site_name`, `primary_color`, `secondary_color`, `tagline`)
VALUES (1, 'PropertyRubix', '#a9804b', '#0f172a', 'Find Your Perfect Property');

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
INSERT IGNORE INTO `permissions` (`role`, `module`, `can_view`, `can_create`, `can_edit`, `can_delete`) VALUES
('admin', 'all', 1, 1, 1, 0),
('editor', 'properties', 1, 1, 1, 0),
('editor', 'projects', 1, 1, 1, 0),
('editor', 'blog', 1, 1, 1, 0),
('viewer', 'all', 1, 0, 0, 0);
