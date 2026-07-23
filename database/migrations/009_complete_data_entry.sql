-- ============================================================
-- Migration 009: Complete Data Entry for Projects and Properties
-- Scope: Ensure NO field is empty for Project ID 1 and Property ID 1.
-- Created: 2026-07-23
-- ============================================================

-- 1. Ensure Builder and City/Locality exist
INSERT IGNORE INTO `builders` (`id`, `name`, `slug`, `established_year`, `total_projects`, `status`, `description`) 
VALUES (3, 'Prestige Group', 'prestige-group', 1986, 210, 'active', 'Prestige Group is a premier real estate developer known for luxury and excellence.');

INSERT IGNORE INTO `states` (`id`, `country_id`, `name`, `slug`) 
VALUES (1, 5, 'Maharashtra', 'maharashtra');

INSERT IGNORE INTO `cities` (`id`, `state_id`, `name`, `slug`, `status`) 
VALUES (1, 1, 'Mumbai', 'mumbai', 'active');

INSERT IGNORE INTO `localities` (`id`, `city_id`, `name`, `slug`, `status`) 
VALUES (1, 1, 'Byculla (Jacob Circle)', 'byculla-jacob-circle', 'active');

-- 2. Fully Populate Project ID 1
INSERT INTO `projects` (
  `id`, `builder_id`, `city_id`, `locality_id`, `name`, `slug`, `type`, `status`,
  `price_min`, `price_max`, `price_on_request`, `unit_types`, `area_range`,
  `rera_id`, `rera_verified`, `address`, `location_area`, `latitude`, `longitude`,
  `map_embed_url`, `map_url`, `short_description`, `description`,
  `banner_image`, `thumbnail_image`, `brochure_pdf`, `video_url`,
  `possession_date`, `total_area`, `total_units`, `amenities`,
  `gallery_images`, `floor_plan_images`, `is_featured`, `sort_order`, `meta_title`, `meta_description`
) VALUES (
  1, 3, 1, 1, 'Prestige Jasdan Classic', 'prestige-jasdan-classic', 'residential', 'ready_to_move',
  35000000.00, 75000000.00, 0, '2 BHK, 3 BHK, 4 BHK Suite', '883 - 1740 Sq.Ft.',
  'P51900031285', 1, 'Jacob Circle, Mahalaxmi, Mumbai, Maharashtra 400011', 'Mahalaxmi', 18.9818, 72.8276,
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3772.637775553077!2d72.8276!3d18.9818!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7ce0000000001%3A0x123456789abcdef!2sPrestige%20Jasdan%20Classic!5e0!3m2!1sen!2sin!4v1700000000000',
  'https://goo.gl/maps/PrestigeJasdanClassic',
  'A landmark of ultra-luxury living in South Mumbai featuring expansive 2, 3 and 4 bed residences.',
  'Prestige Jasdan Classic is an ultra-premium residential project developed by the prestigious Prestige Group. Located in the heart of South Mumbai at Mahalaxmi (Jacob Circle), this high-rise marvel offers bespoke 2, 3, and 4 BHK residences. Featuring two magnificent towers, the project is designed with low-density living in mind, providing unmatched privacy, sprawling balconies with sweeping views of the Arabian Sea and the Mahalaxmi Racecourse. Residents enjoy access to a multi-level clubhouse, infinity edge swimming pool, private cinema, state-of-the-art gymnasium, and manicured sky gardens. Designed by global architects, it redefines the Mumbai skyline with its striking façade and sustainable, smart-home ready infrastructure.',
  'assets/images/placeholder/project-banner.jpg', 'assets/images/placeholder/project-thumb.jpg', 'assets/docs/brochure.pdf', 'https://www.youtube.com/embed/dQw4w9WgXcQ',
  'Dec 2025', '2.5 Acres', 233, 'Swimming Pool,Gymnasium,Clubhouse,Spa,Kids Play Area,Jogging Track,24x7 Security,Smart Home Automation',
  'assets/images/placeholder/gallery1.jpg,assets/images/placeholder/gallery2.jpg',
  'assets/images/placeholder/floor1.jpg,assets/images/placeholder/floor2.jpg',
  1, 1, 'Prestige Jasdan Classic | Luxury 3,4 BHK in Mahalaxmi', 'Explore Prestige Jasdan Classic in South Mumbai. Premium 2,3,4 BHK residences with sweeping sea views and world-class amenities.'
) ON DUPLICATE KEY UPDATE
  builder_id=VALUES(builder_id), city_id=VALUES(city_id), locality_id=VALUES(locality_id), name=VALUES(name), slug=VALUES(slug), type=VALUES(type), status=VALUES(status),
  price_min=VALUES(price_min), price_max=VALUES(price_max), price_on_request=VALUES(price_on_request), unit_types=VALUES(unit_types), area_range=VALUES(area_range),
  rera_id=VALUES(rera_id), rera_verified=VALUES(rera_verified), address=VALUES(address), location_area=VALUES(location_area), latitude=VALUES(latitude), longitude=VALUES(longitude),
  map_embed_url=VALUES(map_embed_url), map_url=VALUES(map_url), short_description=VALUES(short_description), description=VALUES(description),
  banner_image=VALUES(banner_image), thumbnail_image=VALUES(thumbnail_image), brochure_pdf=VALUES(brochure_pdf), video_url=VALUES(video_url),
  possession_date=VALUES(possession_date), total_area=VALUES(total_area), total_units=VALUES(total_units), amenities=VALUES(amenities),
  gallery_images=VALUES(gallery_images), floor_plan_images=VALUES(floor_plan_images), is_featured=VALUES(is_featured), sort_order=VALUES(sort_order), meta_title=VALUES(meta_title), meta_description=VALUES(meta_description);

-- 3. Fully Populate Property ID 1 (Linked to the project above)
INSERT INTO `properties` (
  `id`, `project_id`, `builder_id`, `city_id`, `locality_id`, `title`, `slug`, `property_type`,
  `listing_type`, `market_type`, `possession_status`, `price`, `price_display_override`, `price_unit`,
  `is_gst_inclusive`, `vastu_compliant`, `bedrooms`, `bathrooms`, `balconies`, `parking_spaces`,
  `super_built_up_area`, `built_up_area`, `carpet_area`, `area_unit`, `furnishing_status`,
  `floor_number`, `total_floors`, `facing`, `age_of_construction`, `address`, `pincode`,
  `latitude`, `longitude`, `rera_id`, `possession_date`, `description`, `amenities`,
  `video_url`, `owner_name`, `owner_phone`, `owner_email`, `is_featured`, `status`,
  `thumbnail_image`, `brochure_pdf`, `gallery_images`, `floor_plan_images`
) VALUES (
  1, 1, 3, 1, 1, 'Ultra-Luxury 4 BHK Suite at Prestige Jasdan Classic', 'ultra-luxury-4-bhk-prestige-jasdan', 'Apartment',
  'Sale', 'New Launch', 'Under Construction', 75000000.00, 'â‚¹ 7.50 Cr (All Inclusive)', 'INR',
  1, 1, 4, 5, 3, 3,
  3500, 2800, 2100, 'sqft', 'Semi-Furnished',
  35, 45, 'East', 'New Construction', 'Tower 1, Prestige Jasdan Classic, Jacob Circle, Mahalaxmi, Mumbai', '400011',
  18.9818, 72.8276, 'P51900031285', 'December 2025', 'This expansive 4-bedroom suite is the crown jewel of the Prestige Jasdan Classic. Designed for the elite, it features a grand living room opening up to a massive sun-deck that overlooks the Arabian Sea and Mahalaxmi Racecourse. The apartment comes with imported marble flooring, modular kitchen fittings, VRV central air-conditioning, and premium bathroom fixtures. Each bedroom features an en-suite bath and walk-in wardrobe space. With exclusive access to the sky lounge and dedicated 3-car parking spaces, this property offers a lifestyle reserved for a select few.', 'Swimming Pool,Gymnasium,Clubhouse,Spa,Kids Play Area,Jogging Track,24x7 Security,Smart Home Automation,Private Elevator,Sky Lounge',
  'https://www.youtube.com/embed/dQw4w9WgXcQ', 'Arjun Kapoor', '+919876543210', 'arjun@prestige.com', 1, 'Active',
  'assets/images/placeholder/property-thumb.jpg', 'assets/docs/brochure.pdf', '["assets/images/placeholder/gallery1.jpg","assets/images/placeholder/gallery2.jpg"]', '["assets/images/placeholder/floor1.jpg"]'
) ON DUPLICATE KEY UPDATE
  project_id=VALUES(project_id), builder_id=VALUES(builder_id), city_id=VALUES(city_id), locality_id=VALUES(locality_id), title=VALUES(title), slug=VALUES(slug), property_type=VALUES(property_type),
  listing_type=VALUES(listing_type), market_type=VALUES(market_type), possession_status=VALUES(possession_status), price=VALUES(price), price_display_override=VALUES(price_display_override), price_unit=VALUES(price_unit),
  is_gst_inclusive=VALUES(is_gst_inclusive), vastu_compliant=VALUES(vastu_compliant), bedrooms=VALUES(bedrooms), bathrooms=VALUES(bathrooms), balconies=VALUES(balconies), parking_spaces=VALUES(parking_spaces),
  super_built_up_area=VALUES(super_built_up_area), built_up_area=VALUES(built_up_area), carpet_area=VALUES(carpet_area), area_unit=VALUES(area_unit), furnishing_status=VALUES(furnishing_status),
  floor_number=VALUES(floor_number), total_floors=VALUES(total_floors), facing=VALUES(facing), age_of_construction=VALUES(age_of_construction), address=VALUES(address), pincode=VALUES(pincode),
  latitude=VALUES(latitude), longitude=VALUES(longitude), rera_id=VALUES(rera_id), possession_date=VALUES(possession_date), description=VALUES(description), amenities=VALUES(amenities),
  video_url=VALUES(video_url), owner_name=VALUES(owner_name), owner_phone=VALUES(owner_phone), owner_email=VALUES(owner_email), is_featured=VALUES(is_featured), status=VALUES(status),
  thumbnail_image=VALUES(thumbnail_image), brochure_pdf=VALUES(brochure_pdf), gallery_images=VALUES(gallery_images), floor_plan_images=VALUES(floor_plan_images);
