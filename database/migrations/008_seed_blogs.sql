-- ============================================================
-- Migration 008: Seed Blog Categories and High-Quality Blog Posts
-- Scope: Insert default categories and 5 highly realistic, psychology-focused real estate blogs
-- Created: 2026-07-23
-- ============================================================

-- 1. Insert Categories
INSERT IGNORE INTO `blog_categories` (`id`, `name`, `slug`) VALUES
(1, 'Market Trends', 'market-trends'),
(2, 'Buying Guide', 'buying-guide'),
(3, 'Lifestyle & Decor', 'lifestyle-decor'),
(4, 'Investment Tips', 'investment-tips'),
(5, 'Smart Homes', 'smart-homes');

-- 2. Insert 5 detailed blog posts
INSERT IGNORE INTO `blog_posts` (`title`, `slug`, `category_id`, `author`, `cover_image`, `excerpt`, `body`, `meta_title`, `meta_description`, `status`, `published_at`) VALUES
(
  'The Psychology of Space: How Home Layouts Affect Mental Wellbeing', 
  'psychology-of-space-wellbeing', 
  3, 
  'Sarah Jenkins', 
  'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800&q=80', 
  'Discover how architectural layout, natural lighting, and color choices in modern properties unconsciously influence happiness, focus, and stress levels.', 
  'Our physical surroundings have a profound, often subconscious impact on our emotions and behaviors. Environmental psychology reveals that the layout of a home plays a central role in mental health and relationship dynamics. Open-plan living spaces can foster connection, but without dedicated quiet zones, they can also elevate stress. Natural sunlight triggers serotonin production, making large window designs key to combatting seasonal depression. In this article, we explore the science behind layout design, color psychology (such as the soothing power of soft greens and neutrals), and how you can optimize your home to be a true sanctuary for mental wellbeing.', 
  'Psychology of Space & Home Layouts | PropertyRubix', 
  'Learn how home layouts, lighting, and colors unconsciously impact stress levels and happiness in modern properties.', 
  'published', 
  NOW() - INTERVAL 1 DAY
),
(
  '5 Golden Rules for Real Estate Investment in a Shifting Market', 
  'golden-rules-real-estate-investment', 
  4, 
  'Marcus Vance', 
  'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80', 
  'Navigating volatile markets requires a strategic approach. Here are the 5 timeless principles of property investment to secure steady passive income and growth.', 
  'Real estate remains one of the most reliable wealth-building tools, but success requires more than just picking a property at random. Shifting economic cycles demand strict adherence to proven investment strategies. Rule 1: Always prioritize location and transit connectivity. Rule 2: Calculate the net cash flow after all hidden maintenance and tax expenses. Rule 3: Diversify across different residential and commercial asset types. Rule 4: Understand the demographics driving the area\'s demand. Rule 5: Have a long-term exit strategy. By mastering these rules, you can weather market fluctuations and construct a resilient, income-generating portfolio.', 
  '5 Rules for Real Estate Investment | PropertyRubix', 
  'Master the timeless rules of real estate investing during changing market cycles to build long-term passive income.', 
  'published', 
  NOW() - INTERVAL 3 DAY
),
(
  'Smart Homes: The Future of Comfortable and Sustainable Living', 
  'smart-homes-future-sustainable-living', 
  5, 
  'David Chen', 
  'https://images.unsplash.com/photo-1558002038-1055907df827?w=800&q=80', 
  'From automated climate control to AI-driven security, smart home technologies are no longer a luxury but an essential standard for modern efficiency.', 
  'The modern home is transitioning from a static shelter to an active, responsive environment. Smart home integration is redefining how we interact with our living spaces. Automated thermostats learn your daily routines, cutting heating and cooling energy usage by up to 20%. Smart lighting systems simulate natural daylight progression, supporting circadian rhythms. Meanwhile, integrated home automation hubs allow you to monitor air quality, water flow, and security alerts remotely. Discover the highest-ROI smart integrations that increase both comfort and property value.', 
  'Future of Smart Homes & Sustainable Living | PropertyRubix', 
  'Explore how smart home technology increases efficiency, lowers energy bills, and raises property values.', 
  'published', 
  NOW() - INTERVAL 5 DAY
),
(
  'First-Time Home Buyer Mistakes (And How to Avoid Them)', 
  'first-time-buyer-mistakes-avoid', 
  2, 
  'Elena Rostova', 
  'https://images.unsplash.com/photo-1560520653-9e0e4c89eb11?w=800&q=80', 
  'Avoid the common pitfalls that cost new buyers thousands. Learn how to budget correctly, check hidden costs, and negotiate like a professional.', 
  'Buying your first property is a momentous milestone, but it is also fraught with potential financial traps. Many first-time buyers fall in love with the aesthetics of a house while overlooking foundational issues. Common mistakes include ignoring structural inspection reports, failing to secure pre-approved financing, underestimating closing costs, and settling for the first mortgage quote received. In this guide, we provide a step-by-step checklist to keep your homebuying journey stress-free, cost-effective, and successful.', 
  'First-Time Home Buyer Mistakes Guide | PropertyRubix', 
  'A comprehensive guide for first-time home buyers to avoid costly mistakes and secure the best deals.', 
  'published', 
  NOW() - INTERVAL 7 DAY
),
(
  'How Green Buildings are Reshaping Metropolitan Skylines', 
  'green-buildings-reshaping-skylines', 
  1, 
  'Arthur Pendelton', 
  'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80', 
  'Eco-friendly skyscrapers, vertical forests, and zero-carbon structures are no longer science fiction. They are the new standard of premium commercial real estate.', 
  'Metropolitan cities contribute over 70% of global carbon emissions, primarily driven by building operations. In response, architectural innovation is taking a dramatic turn toward sustainability. Zero-emission materials, rain-water harvesting systems, and solar-façade grids are becoming standard expectations. Cities like Singapore and Toronto are leading the way with vertical forests that clean city air while insulating buildings. This piece examines the economic and ecological motivations pushing top developers toward green certifications.', 
  'How Green Buildings Reshape Skylines | PropertyRubix', 
  'Read about the rise of sustainable architecture, vertical forests, and green skyscraper certifications in major cities.', 
  'published', 
  NOW() - INTERVAL 10 DAY
);
