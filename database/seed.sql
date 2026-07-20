-- ============================================================
-- PropertyRubix — Seed Data
-- Run AFTER schema.sql
-- Default admin: admin@propertyrubix.com / admin123
-- ============================================================

-- Branding
INSERT INTO `branding_settings` (`site_name`, `primary_color`, `secondary_color`, `tagline`) VALUES
('PropertyRubix', '#16a34a', '#0f172a', 'Find Your Perfect Property');

-- Default settings
INSERT INTO `settings` (`key_name`, `value`) VALUES
('phone_primary', '+91 98765 43210'),
('phone_secondary', '+91 91234 56789'),
('whatsapp_number', '919876543210'),
('email_primary', 'info@propertyrubix.com'),
('email_secondary', 'sales@propertyrubix.com'),
('address_1', '123, Real Estate Tower, Sector 18, Noida, UP 201301'),
('address_2', '456, Business Hub, DLF Phase 2, Gurugram, HR 122002'),
('rera_id_1', 'UPRERAPRJ123456'),
('social_facebook', 'https://facebook.com/propertyrubix'),
('social_twitter', 'https://twitter.com/propertyrubix'),
('social_youtube', 'https://youtube.com/propertyrubix'),
('social_instagram', 'https://instagram.com/propertyrubix'),
('google_analytics_id', ''),
('smtp_host', 'smtp.gmail.com'),
('smtp_port', '587'),
('smtp_user', ''),
('smtp_pass', ''),
('smtp_from_name', 'PropertyRubix'),
('playstore_url', '#'),
('appstore_url', '#');

-- Admin user: admin123
INSERT INTO `users` (`name`, `email`, `password_hash`, `role`, `status`) VALUES
('Super Admin', 'admin@propertyrubix.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active');

-- Countries
INSERT INTO `countries` (`name`, `slug`, `flag_icon`, `sort_order`) VALUES
('India', 'india', '🇮🇳', 1),
('UAE', 'uae', '🇦🇪', 2),
('USA', 'usa', '🇺🇸', 3),
('Canada', 'canada', '🇨🇦', 4),
('UK', 'uk', '🇬🇧', 5);

-- States (India)
INSERT INTO `states` (`country_id`, `name`, `slug`) VALUES
(1, 'Uttar Pradesh', 'uttar-pradesh'),
(1, 'Maharashtra', 'maharashtra'),
(1, 'Karnataka', 'karnataka'),
(1, 'Haryana', 'haryana'),
(1, 'Delhi', 'delhi'),
(1, 'Gujarat', 'gujarat'),
(1, 'Rajasthan', 'rajasthan'),
(1, 'Tamil Nadu', 'tamil-nadu'),
(1, 'Telangana', 'telangana'),
(1, 'Punjab', 'punjab');

-- States (UAE)
INSERT INTO `states` (`country_id`, `name`, `slug`) VALUES
(2, 'Dubai', 'dubai'),
(2, 'Abu Dhabi', 'abu-dhabi'),
(2, 'Sharjah', 'sharjah');

-- States (USA)
INSERT INTO `states` (`country_id`, `name`, `slug`) VALUES
(3, 'California', 'california'),
(3, 'New York', 'new-york'),
(3, 'Texas', 'texas');

-- States (Canada)
INSERT INTO `states` (`country_id`, `name`, `slug`) VALUES
(4, 'Ontario', 'ontario'),
(4, 'British Columbia', 'british-columbia');

-- Cities (India - UP)
INSERT INTO `cities` (`state_id`, `name`, `slug`, `meta_title`, `meta_description`) VALUES
(1, 'Noida', 'noida', 'Properties in Noida | PropertyRubix', 'Find residential & commercial properties in Noida'),
(1, 'Greater Noida', 'greater-noida', 'Properties in Greater Noida | PropertyRubix', 'Explore properties in Greater Noida'),
(1, 'Lucknow', 'lucknow', 'Properties in Lucknow | PropertyRubix', 'Find your dream home in Lucknow'),
(1, 'Ghaziabad', 'ghaziabad', 'Properties in Ghaziabad | PropertyRubix', 'Property listings in Ghaziabad');

-- Cities (India - Maharashtra)
INSERT INTO `cities` (`state_id`, `name`, `slug`, `meta_title`, `meta_description`) VALUES
(2, 'Mumbai', 'mumbai', 'Properties in Mumbai | PropertyRubix', 'Luxury and affordable properties in Mumbai'),
(2, 'Pune', 'pune', 'Properties in Pune | PropertyRubix', 'Find properties in Pune'),
(2, 'Nashik', 'nashik', 'Properties in Nashik | PropertyRubix', 'Property listings in Nashik');

-- Cities (India - Karnataka)
INSERT INTO `cities` (`state_id`, `name`, `slug`, `meta_title`, `meta_description`) VALUES
(3, 'Bangalore', 'bangalore', 'Properties in Bangalore | PropertyRubix', 'IT hub property listings in Bangalore');

-- Cities (India - Haryana)
INSERT INTO `cities` (`state_id`, `name`, `slug`, `meta_title`, `meta_description`) VALUES
(4, 'Gurugram', 'gurugram', 'Properties in Gurugram | PropertyRubix', 'Premium properties in Gurugram'),
(4, 'Faridabad', 'faridabad', 'Properties in Faridabad | PropertyRubix', 'Affordable homes in Faridabad');

-- Cities (India - Delhi)
INSERT INTO `cities` (`state_id`, `name`, `slug`, `meta_title`, `meta_description`) VALUES
(5, 'New Delhi', 'new-delhi', 'Properties in New Delhi | PropertyRubix', 'Property listings in New Delhi');

-- Cities (UAE)
INSERT INTO `cities` (`state_id`, `name`, `slug`, `meta_title`, `meta_description`) VALUES
(11, 'Dubai Marina', 'dubai-marina', 'Properties in Dubai Marina | PropertyRubix', 'Waterfront luxury in Dubai Marina'),
(11, 'Downtown Dubai', 'downtown-dubai', 'Properties in Downtown Dubai | PropertyRubix', 'Premium Downtown Dubai properties'),
(12, 'Abu Dhabi City', 'abu-dhabi-city', 'Properties in Abu Dhabi | PropertyRubix', 'Property listings in Abu Dhabi');

-- Builders
INSERT INTO `builders` (`name`, `slug`, `description`, `established_year`, `total_projects`, `country_id`, `status`) VALUES
('Prestige Group', 'prestige-group', 'One of India''s leading real estate developers with projects across major cities.', 1986, 285, 1, 'active'),
('DLF Limited', 'dlf-limited', 'India''s largest real estate developer, known for premium residential and commercial projects.', 1946, 340, 1, 'active'),
('Godrej Properties', 'godrej-properties', 'Part of the Godrej Group, delivering quality homes across India.', 1990, 165, 1, 'active'),
('Sobha Developers', 'sobha-developers', 'Premium real estate developer known for quality construction.', 1995, 128, 1, 'active'),
('Lodha Group', 'lodha-group', 'Mumbai-based developer with iconic projects across India and the UK.', 1980, 210, 1, 'active'),
('Emaar Properties', 'emaar-properties', 'Dubai-based global real estate developer, creators of Burj Khalifa.', 1997, 85, 2, 'active'),
('DAMAC Properties', 'damac-properties', 'Leading luxury real estate developer in the Middle East.', 2002, 47, 2, 'active'),
('Puravankara', 'puravankara', 'Trusted real estate developer in South India.', 1975, 95, 1, 'active'),
('Mahindra Lifespaces', 'mahindra-lifespaces', 'Sustainable urban development from the Mahindra Group.', 1994, 42, 1, 'active'),
('Tata Housing', 'tata-housing', 'Tata Group''s real estate arm offering quality homes across India.', 1984, 75, 1, 'active');

-- Projects (Sample 15)
INSERT INTO `projects` (`builder_id`, `city_id`, `name`, `slug`, `type`, `status`, `price_min`, `price_max`, `unit_types`, `area_range`, `rera_id`, `rera_verified`, `address`, `location_area`, `short_description`, `description`, `is_featured`, `possession_date`, `latitude`, `longitude`) VALUES
(1, 1, 'Prestige Fairfield', 'prestige-fairfield', 'residential', 'under_construction', 7500000, 15000000, '2BHK, 3BHK, 4BHK', '1050-2800 sq.ft.', 'UPRERAPRJ123001', 1, 'Sector 150, Noida', 'Sector 150, Noida', 'Luxury high-rise living with world-class amenities', 'Prestige Fairfield offers a grand lifestyle with beautifully designed 2, 3 & 4 BHK apartments in the heart of Noida''s greenest sector.', 1, 'Dec 2026', 28.5355, 77.3910),
(2, 9, 'DLF Camellias', 'dlf-camellias', 'residential', 'ready_to_move', 50000000, 120000000, '4BHK, 5BHK', '5500-9800 sq.ft.', 'HRRERAPRJ456002', 1, 'DLF Golf Course Road, Gurugram', 'Golf Course Road, Gurugram', 'Ultra-luxury residences on Golf Course Road', 'DLF The Camellias is the most exclusive address in Gurugram, offering ultra-luxury 4 & 5 BHK residences.', 1, 'Ready to Move', 28.4595, 77.1022),
(3, 3, 'Godrej Park Greens', 'godrej-park-greens', 'residential', 'upcoming', 4500000, 9000000, '2BHK, 3BHK', '850-1750 sq.ft.', 'UPRERAPRJ789003', 1, 'Gomti Nagar, Lucknow', 'Gomti Nagar Extension, Lucknow', 'Modern living surrounded by lush greenery', 'Godrej Park Greens brings you smartly designed homes with sustainable features in the growing Lucknow market.', 1, 'Mar 2027', 26.8467, 80.9462),
(4, 8, 'Sobha Dream Acres', 'sobha-dream-acres', 'residential', 'ready_to_move', 3500000, 7000000, '1BHK, 2BHK', '650-1350 sq.ft.', 'KARRERAPRJ321004', 1, 'Panathur, Bangalore', 'Whitefield, Bangalore', 'Dream homes at the heart of Bangalore''s IT corridor', 'Sobha Dream Acres is a sprawling township offering premium 1 & 2 BHK homes near Whitefield.', 1, 'Ready to Move', 12.9716, 77.5946),
(5, 5, 'Lodha Altamount', 'lodha-altamount', 'residential', 'ready_to_move', 100000000, 500000000, '3BHK, 4BHK, Penthouse', '2400-12000 sq.ft.', 'MAHARERAPRJ654005', 1, 'Altamount Road, Mumbai', 'Peddar Road, Mumbai', 'Mumbai''s most prestigious ultra-luxury address', 'Lodha Altamount represents the pinnacle of luxury living on Mumbai''s most exclusive address.', 1, 'Ready to Move', 18.9750, 72.8063),
(6, 13, 'Emaar Beachfront', 'emaar-beachfront', 'residential', 'under_construction', 2500000, 15000000, '1BR, 2BR, 3BR, 4BR', '650-4200 sq.ft.', 'DLD-12345-UAE', 1, 'Dubai Harbour, Dubai Marina', 'Dubai Marina', 'Private beach island living in Dubai', 'Emaar Beachfront is an exclusive island community offering premium beach-facing apartments.', 1, 'Q4 2026', 25.0849, 55.1381),
(7, 14, 'DAMAC Hills 2', 'damac-hills-2', 'residential', 'upcoming', 1200000, 8000000, 'Studio, 1BR, 2BR, 3BR', '450-2500 sq.ft.', 'DLD-67890-UAE', 1, 'Dubailand, Dubai', 'Downtown Dubai', 'Affordable luxury in Dubai''s fastest-growing community', 'DAMAC Hills 2 is a master-planned community with world-class amenities.', 0, 'Q2 2027', 25.2048, 55.2708),
(8, 6, 'Puravankara Zenium', 'puravankara-zenium', 'residential', 'under_construction', 5500000, 12000000, '2BHK, 3BHK', '1100-2200 sq.ft.', 'MAHARERAPRJ111006', 1, 'Hinjewadi, Pune', 'Hinjewadi IT Park, Pune', 'Tech-zone living with smart home features', 'Puravankara Zenium is designed for the modern tech professional with smart home integrations.', 0, 'Jun 2027', 18.5204, 73.8567),
(9, 2, 'Mahindra Luminare', 'mahindra-luminare', 'residential', 'ready_to_move', 8000000, 18000000, '3BHK, 4BHK', '1850-3500 sq.ft.', 'UPRERAPRJ222007', 1, 'Sector 59, Greater Noida', 'Sector 59, Greater Noida', 'Green certified luxury homes', 'Mahindra Luminare offers IGBC Gold rated homes with sustainable features.', 0, 'Ready to Move', 28.4744, 77.5040),
(10, 1, 'Tata Eureka Park', 'tata-eureka-park', 'residential', 'ready_to_move', 4000000, 9500000, '2BHK, 3BHK', '1000-1900 sq.ft.', 'UPRERAPRJ333008', 1, 'Sector 150, Noida', 'Sector 150, Noida', 'Homes by Tata in Noida''s greenest sector', 'Tata Eureka Park offers contemporary homes with 80% open green spaces.', 0, 'Ready to Move', 28.5230, 77.3875),
(1, 5, 'Prestige Ocean Towers', 'prestige-ocean-towers', 'commercial', 'upcoming', 20000000, 80000000, 'Office, Retail', '800-8000 sq.ft.', 'MAHARERAPRJ444009', 1, 'BKC, Mumbai', 'Bandra Kurla Complex, Mumbai', 'Premium commercial spaces in BKC', 'Prestige Ocean Towers offers Grade A office spaces and retail units in Mumbai''s prime business district.', 1, 'Dec 2027', 19.0596, 72.8656),
(2, 11, 'DLF Privana', 'dlf-privana', 'residential', 'upcoming', 12000000, 35000000, '4BHK, 5BHK', '3500-8500 sq.ft.', 'HRRERAPRJ555010', 1, 'Sector 76-77, Gurugram', 'Southern Peripheral Road, Gurugram', 'Ultra-luxury living on Gurugram''s Southern Periphery', 'DLF Privana is an exclusive super-luxury project offering palatial residences.', 1, 'Q1 2028', 28.3981, 77.0504),
(3, 4, 'Godrej Nest', 'godrej-nest', 'residential', 'under_construction', 3000000, 6500000, '1BHK, 2BHK, 3BHK', '650-1650 sq.ft.', 'UPRERAPRJ666011', 1, 'Sector 150, Ghaziabad', 'NH-58, Ghaziabad', 'Smart homes for first-time buyers', 'Godrej Nest brings affordable smart homes to Ghaziabad with modern amenities.', 0, 'Sep 2026', 28.6692, 77.4538),
(5, 6, 'Lodha Palava', 'lodha-palava', 'residential', 'ready_to_move', 3500000, 12000000, '1BHK, 2BHK, 3BHK', '600-1800 sq.ft.', 'MAHARERAPRJ777012', 1, 'Dombivli, Pune Road', 'Dombivli East, Mumbai MMR', 'A city within a city — Palava', 'Lodha Palava is India''s smartest city with 4000+ acres of planned development.', 1, 'Ready to Move', 18.9620, 73.0879),
(4, 3, 'Sobha City', 'sobha-city', 'residential', 'under_construction', 5000000, 13000000, '2BHK, 3BHK, 4BHK', '1200-3000 sq.ft.', 'UPRERAPRJ888013', 1, 'Thrippunithura, Lucknow', 'ISBT Road, Lucknow', 'Premium township living in Lucknow', 'Sobha City is a premium integrated township offering luxury villas and apartments.', 0, 'Mar 2027', 26.8467, 80.9662);

-- Project Amenities (for project 1)
INSERT INTO `project_amenities` (`project_id`, `amenity_name`, `icon`) VALUES
(1, 'Swimming Pool', 'fas fa-swimming-pool'),
(1, 'Gymnasium', 'fas fa-dumbbell'),
(1, 'Clubhouse', 'fas fa-building'),
(1, 'Children''s Play Area', 'fas fa-child'),
(1, 'Landscaped Gardens', 'fas fa-leaf'),
(1, 'Indoor Games', 'fas fa-gamepad'),
(1, '24/7 Security', 'fas fa-shield-alt'),
(1, 'Power Backup', 'fas fa-bolt'),
(1, 'Jogging Track', 'fas fa-running'),
(1, 'Multi-purpose Hall', 'fas fa-users'),
(2, 'Private Pool', 'fas fa-swimming-pool'),
(2, 'Concierge Service', 'fas fa-concierge-bell'),
(2, 'Golf Course View', 'fas fa-golf-ball'),
(2, 'Home Automation', 'fas fa-home'),
(3, 'Swimming Pool', 'fas fa-swimming-pool'),
(3, 'Gym', 'fas fa-dumbbell'),
(3, 'Park', 'fas fa-leaf'),
(4, 'Pool', 'fas fa-swimming-pool'),
(4, 'Gym', 'fas fa-dumbbell'),
(5, 'Rooftop Pool', 'fas fa-swimming-pool'),
(5, 'Spa', 'fas fa-spa'),
(5, 'Private Elevator', 'fas fa-elevator');

-- Blog categories
INSERT INTO `blog_categories` (`name`, `slug`) VALUES
('Real Estate News', 'real-estate-news'),
('Investment Tips', 'investment-tips'),
('Home Buying Guide', 'home-buying-guide'),
('Interior Design', 'interior-design'),
('Market Trends', 'market-trends');

-- Blog posts (5 sample)
INSERT INTO `blog_posts` (`title`, `slug`, `category_id`, `author`, `excerpt`, `body`, `status`, `published_at`) VALUES
('Top 10 Real Estate Investment Hotspots in India 2024', 'top-10-real-estate-investment-hotspots-india-2024', 2, 'Admin', 'Discover the best cities and micro-markets for real estate investment in India this year.', '<h2>Introduction</h2><p>Real estate investment in India has seen remarkable growth over the past decade. With rapid urbanization, infrastructure development, and rising disposable incomes, several cities have emerged as top investment destinations.</p><h2>1. Noida / Greater Noida</h2><p>With the upcoming Jewar Airport, Noida and Greater Noida have witnessed unprecedented growth in property prices. The Yamuna Expressway corridor is particularly promising.</p><h2>2. Gurugram</h2><p>Gurugram continues to be a top pick for investors, especially along the Dwarka Expressway and Southern Peripheral Road corridors.</p><h2>3. Bangalore</h2><p>India''s IT capital offers strong rental yields and consistent price appreciation, particularly in Whitefield, Electronic City, and Sarjapur Road.</p><p>Contact our investment advisory team for personalized guidance on your real estate investment journey.</p>', 'published', NOW()),
('How to Check RERA Registration Before Buying a Property', 'how-to-check-rera-registration-before-buying', 3, 'Admin', 'A step-by-step guide to verifying RERA registration of any property in India.', '<h2>What is RERA?</h2><p>The Real Estate (Regulation and Development) Act, 2016 (RERA) was enacted to protect home buyers and boost investments in the real estate sector.</p><h2>Why RERA Verification Matters</h2><p>Before booking any under-construction property, verifying RERA registration protects you from fraudulent projects and ensures the developer is compliant.</p><h2>Steps to Verify</h2><ol><li>Visit your state''s RERA website</li><li>Search by project name or RERA registration number</li><li>Check project details, completion timeline, and complaint status</li></ol>', 'published', DATE_SUB(NOW(), INTERVAL 7 DAY)),
('Luxury vs Affordable Housing: Which Offers Better ROI?', 'luxury-vs-affordable-housing-roi-comparison', 2, 'Admin', 'An in-depth comparison of returns on luxury vs affordable housing segments.', '<h2>The Great Debate</h2><p>Investors often wonder whether luxury or affordable housing offers better returns. The answer depends on several factors including location, holding period, and market conditions.</p><h2>Affordable Housing Advantages</h2><ul><li>Higher rental yields (4-6%)</li><li>Government incentives under PMAY</li><li>Faster liquidity</li></ul><h2>Luxury Housing Advantages</h2><ul><li>Higher absolute appreciation</li><li>Premium tenant base</li><li>Better maintenance of value</li></ul>', 'published', DATE_SUB(NOW(), INTERVAL 14 DAY)),
('Smart Home Technology Trends in Indian Real Estate 2024', 'smart-home-technology-trends-india-2024', 4, 'Admin', 'How IoT and automation are transforming the Indian real estate landscape.', '<h2>The Smart Home Revolution</h2><p>Indian homebuyers are increasingly demanding smart home features, from automated lighting and security systems to voice-controlled appliances.</p><h2>Key Technologies</h2><ul><li>Smart security systems with facial recognition</li><li>Automated climate control</li><li>Voice-controlled lighting</li><li>Remote access via smartphone apps</li></ul>', 'published', DATE_SUB(NOW(), INTERVAL 21 DAY)),
('Dubai Real Estate Market: Why Indians are Investing Abroad', 'dubai-real-estate-market-indians-investing-abroad', 5, 'Admin', 'Exploring the growing trend of Indian investors buying properties in Dubai.', '<h2>The Dubai Advantage</h2><p>Dubai has emerged as a preferred destination for Indian real estate investors due to its tax-free environment, strong rental yields, and transparent property laws.</p><h2>Key Benefits</h2><ul><li>No property tax or capital gains tax</li><li>Rental yields of 6-8%</li><li>Residency visa benefits</li><li>World-class infrastructure</li></ul>', 'published', DATE_SUB(NOW(), INTERVAL 28 DAY));

-- Static Pages
INSERT INTO `pages` (`title`, `slug`, `body`, `meta_title`, `meta_description`, `status`) VALUES
('About Us', 'about-us', '<h2>About PropertyRubix</h2><p>PropertyRubix is a leading real estate discovery platform connecting homebuyers, investors, and developers across India and the UAE. Founded with a mission to make property discovery transparent, simple, and trustworthy, we have helped thousands of families find their dream homes.</p><h3>Our Mission</h3><p>To democratize real estate information and empower buyers with the data they need to make informed decisions.</p><h3>Our Vision</h3><p>To be the most trusted real estate platform in South Asia and the Middle East.</p><h3>Why Choose Us</h3><ul><li>Verified RERA-registered projects</li><li>Expert advisory team</li><li>10,000+ satisfied customers</li><li>500+ partner developers</li></ul>', 'About Us | PropertyRubix', 'Learn about PropertyRubix — India''s trusted real estate discovery platform.', 'published'),
('Privacy Policy', 'privacy-policy', '<h2>Privacy Policy</h2><p>Last updated: January 2024</p><p>PropertyRubix ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, and share information about you when you use our services.</p><h3>Information We Collect</h3><p>We collect information you provide directly to us, such as when you create an account, fill out a form, or contact us for support.</p><h3>How We Use Your Information</h3><ul><li>To provide, maintain, and improve our services</li><li>To send you property recommendations and updates</li><li>To respond to your comments and questions</li></ul><h3>Contact Us</h3><p>If you have questions about this Privacy Policy, please contact us at privacy@propertyrubix.com</p>', 'Privacy Policy | PropertyRubix', 'Read PropertyRubix''s privacy policy to understand how we handle your data.', 'published'),
('Terms & Conditions', 'terms-conditions', '<h2>Terms & Conditions</h2><p>Last updated: January 2024</p><p>Welcome to PropertyRubix. By using our website, you agree to these Terms & Conditions.</p><h3>Use of Service</h3><p>PropertyRubix provides a platform for discovering real estate projects. We do not directly sell or develop any properties listed on the platform.</p><h3>Disclaimer</h3><p>All property information is provided for reference purposes only. Please verify all details with the developer or our advisory team before making any investment decisions.</p>', 'Terms & Conditions | PropertyRubix', 'Read PropertyRubix''s terms and conditions.', 'published'),
('Advertise With Us', 'advertise-with-us', '<h2>Advertise With Us</h2><p>Reach millions of property seekers on PropertyRubix. We offer tailored advertising solutions for developers, agents, and real estate service providers.</p><h3>Advertising Options</h3><ul><li>Featured Project Listings</li><li>Banner Advertising</li><li>Email Newsletter Campaigns</li><li>Social Media Promotions</li></ul><h3>Get in Touch</h3><p>Contact our advertising team at ads@propertyrubix.com or call +91 98765 43210</p>', 'Advertise With Us | PropertyRubix', 'Advertise your real estate projects on PropertyRubix and reach millions of buyers.', 'published');

-- Testimonials
INSERT INTO `testimonials` (`name`, `designation`, `message`, `rating`, `status`) VALUES
('Rajesh Kumar', 'IT Professional, Bangalore', 'PropertyRubix helped me find my dream 3BHK in Whitefield. The process was smooth and the team was very professional. Highly recommended!', 5, 'active'),
('Priya Sharma', 'Business Owner, Mumbai', 'Excellent service! I was looking for a commercial space in BKC and PropertyRubix connected me with the right developer within days.', 5, 'active'),
('Ahmed Al-Rashid', 'Investor, Dubai', 'As an NRI investor, I needed reliable information about Dubai properties. PropertyRubix''s team guided me perfectly.', 5, 'active'),
('Suresh Patel', 'Doctor, Ahmedabad', 'Very helpful platform with genuine listings. Found a great investment property in Pune through PropertyRubix.', 4, 'active'),
('Meera Nair', 'Teacher, Kochi', 'The site visit booking feature is fantastic. The team arranged everything promptly and professionally.', 5, 'active');

-- FAQs
INSERT INTO `faqs` (`question`, `answer`, `sort_order`) VALUES
('How do I search for properties on PropertyRubix?', 'You can search for properties using our location-based search (by city, state, or country), developer search, or our property listing page with filters for type, budget, and status.', 1),
('Are all projects on PropertyRubix RERA registered?', 'We prioritize RERA-verified projects and display RERA registration numbers prominently. However, we recommend independently verifying RERA status on your state''s RERA website before making any investment.', 2),
('How can I book a site visit?', 'Click the "Book Site Visit" button on any project page, fill in your details and preferred date/time, and our team will confirm your appointment within 24 hours.', 3),
('Does PropertyRubix charge any fee from buyers?', 'No! Our services are completely free for property seekers. We earn through developer partnerships and advertising.', 4),
('How can I list my property or project on PropertyRubix?', 'Developers and builders can contact us through our "Advertise With Us" page or email ads@propertyrubix.com to discuss listing options.', 5),
('What is the difference between ready-to-move and under-construction properties?', 'Ready-to-move properties are completed and can be occupied immediately. Under-construction properties are still being built and offer lower prices but carry timeline risk. Always verify RERA timelines for under-construction projects.', 6);

-- Services
INSERT INTO `services` (`title`, `icon`, `description`, `sort_order`) VALUES
('Property Search', 'fas fa-search', 'Advanced search with filters to find your perfect property across all cities.', 1),
('Investment Advisory', 'fas fa-chart-line', 'Expert guidance on real estate investment opportunities and ROI analysis.', 2),
('Site Visit Booking', 'fas fa-calendar-check', 'Hassle-free site visit scheduling with our developer partners.', 3),
('Home Loan Assistance', 'fas fa-hand-holding-usd', 'Connect with top banks and NBFCs for the best home loan deals.', 4),
('Legal & Documentation', 'fas fa-file-contract', 'Expert assistance with property documentation and legal verification.', 5),
('Interior Design', 'fas fa-paint-brush', 'End-to-end interior design solutions for your new home.', 6);

-- Awards
INSERT INTO `awards` (`title`, `year`, `sort_order`) VALUES
('Best Real Estate Portal — PropTech India Awards', 2023, 1),
('Most Trusted Property Platform — ET Now', 2023, 2),
('Excellence in Customer Service — Realty+ Awards', 2022, 3),
('Best Emerging Portal — NAR India', 2022, 4);

-- Sample Leads
INSERT INTO `leads` (`name`, `email`, `phone`, `source`, `project_id`, `city_id`, `message`, `status`) VALUES
('Amit Singh', 'amit.singh@gmail.com', '9876543210', 'contact_form', 1, 1, 'Interested in 3BHK apartment in Noida Sector 150', 'new'),
('Sunita Verma', 'sunita.v@yahoo.com', '9988776655', 'site_visit_form', 2, 9, 'Looking for luxury villa in Gurugram', 'contacted'),
('Mohammad Ali', 'mali@gmail.com', '9123456789', 'whatsapp', 6, 13, 'Interested in Dubai Marina apartments', 'qualified'),
('Pooja Mehta', 'pooja.m@outlook.com', '8877665544', 'contact_form', NULL, 8, 'Looking for 2BHK in Bangalore under 50 lakhs', 'new'),
('Ravi Shankar', 'ravi.s@gmail.com', '7766554433', 'call', 5, 5, 'Interested in Mumbai luxury project', 'contacted');

-- Sample Submissions
INSERT INTO `submissions` (`form_type`, `name`, `email`, `phone`, `payload`, `status`) VALUES
('site_visit', 'Amit Singh', 'amit.singh@gmail.com', '9876543210', '{"project":"Prestige Fairfield","visit_date":"2024-02-15","visit_time":"11:00","consent":true}', 'processed'),
('contact', 'Sunita Verma', 'sunita.v@yahoo.com', '9988776655', '{"message":"Looking for luxury properties","source":"homepage"}', 'new');

-- Sample Subscribers
INSERT INTO `subscribers` (`email`, `status`) VALUES
('newsletter1@test.com', 'active'),
('newsletter2@test.com', 'active'),
('newsletter3@test.com', 'unsubscribed');

-- Default permissions
INSERT INTO `permissions` (`role`, `module`, `can_view`, `can_edit`, `can_delete`) VALUES
('super_admin', 'all', 1, 1, 1),
('admin', 'properties', 1, 1, 1),
('admin', 'projects', 1, 1, 1),
('admin', 'leads', 1, 1, 1),
('admin', 'blog', 1, 1, 1),
('admin', 'settings', 1, 1, 0),
('agent', 'leads', 1, 1, 0),
('agent', 'tasks', 1, 1, 0),
('editor', 'blog', 1, 1, 0),
('editor', 'pages', 1, 1, 0);
