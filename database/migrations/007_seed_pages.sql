-- ============================================================
-- Migration 007: Seed Static Pages
-- Scope: Insert default static pages for About Us, Advertise, Privacy Policy, Terms
-- Created: 2026-07-23
-- ============================================================

INSERT IGNORE INTO `pages` (`title`, `slug`, `body`, `meta_title`, `meta_description`, `status`) VALUES
('About Us', 'about-us', 'Welcome to PropertyRubix. We are dedicated to finding your perfect property.', 'About Us | PropertyRubix', 'Learn more about PropertyRubix and our mission.', 'published'),
('Advertise With Us', 'advertise-with-us', 'Advertise your properties with us and reach thousands of potential buyers.', 'Advertise With Us | PropertyRubix', 'Advertise your properties on PropertyRubix.', 'published'),
('Privacy Policy', 'privacy-policy', 'Your privacy is important to us. This policy outlines how we handle your data.', 'Privacy Policy | PropertyRubix', 'Privacy Policy of PropertyRubix.', 'published'),
('Terms & Conditions', 'terms-conditions', 'By using our website, you agree to the following terms and conditions.', 'Terms & Conditions | PropertyRubix', 'Terms and Conditions for using PropertyRubix.', 'published');
