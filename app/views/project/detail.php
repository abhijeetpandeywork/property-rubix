<?php
/**
 * Advanced Luxury Project Detail View
 */
$p = $project;
// Use project specific phone/wa if available, else fallback to global
$phone = $p['contact_phone'] ?: getSetting('phone_primary', '+91 98765 43210');
$wa    = $p['whatsapp_number'] ?: getSetting('whatsapp_number', '919876543210');

// Parse JSON arrays
$bannerImages = !empty($p['banner_images']) ? json_decode($p['banner_images'], true) ?: [] : [];
if (empty($bannerImages) && !empty($p['banner_image'])) {
    $bannerImages = [$p['banner_image']];
}
$galleryImages = !empty($p['gallery_images']) ? json_decode($p['gallery_images'], true) ?: [] : [];
if (empty($galleryImages)) {
    if ($p['banner_image']) $galleryImages[] = $p['banner_image'];
    foreach ($images as $img) $galleryImages[] = $img['image_path'];
}
$interiorImages = !empty($p['interior_images']) ? json_decode($p['interior_images'], true) ?: [] : [];
$exteriorImages = !empty($p['exterior_images']) ? json_decode($p['exterior_images'], true) ?: [] : [];
$floorPlanImages = !empty($p['floor_plan_images']) ? json_decode($p['floor_plan_images'], true) ?: [] : [];
$projectAmenities = !empty($p['amenities']) ? json_decode($p['amenities'], true) ?: [] : [];
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;800;900&display=swap');
body {
    background-color: #f8f9fa;
    font-family: 'Outfit', sans-serif;
    padding-top: 0 !important; /* Fix header whitespace */
}
/* --- Hide global header --- */
#siteHeader { display: none !important; }
.main-wrapper { padding-top: 0 !important; } /* In case main.php has padding */

/* --- Custom Project Header --- */
.custom-proj-header {
    background: #fff; border-bottom: 1px solid #eaeaea; position: sticky; top: 0; z-index: 1000;
    padding: 15px 0;
}
.custom-proj-header .container-fluid {
    display: flex; align-items: center; justify-content: space-between;
}
.cph-logo img { max-height: 70px; object-fit: contain; }
.cph-actions { display: flex; align-items: center; gap: 15px; }
.cph-btn {
    display: inline-flex; align-items: center; gap: 8px; font-weight: 700; color: #fff;
    background: #111; padding: 8px 20px; border-radius: 6px; text-decoration: none;
    border: 1px solid #b08d55; /* gold border matching image */
    font-size: 0.95rem; transition: background 0.3s;
}
.cph-btn:hover { background: #b08d55; color: #fff; }
.cph-hamburger { font-size: 1.5rem; color: #111; cursor: pointer; padding-left: 15px; border-left: 1px solid #ccc; display: flex; flex-direction: column; gap: 5px; }
.cph-hamburger span { display: block; width: 25px; height: 3px; background: #111; }

/* --- Marquee --- */
.marquee-bar {
    background: var(--pr-primary);
    color: #fff;
    padding: 8px 0;
    overflow: hidden;
    white-space: nowrap;
    font-size: 0.95rem;
    font-weight: 600;
    letter-spacing: 1px;
}
.marquee-content {
    display: inline-block;
    padding-left: 100%;
    animation: marquee 25s linear infinite;
}
@keyframes marquee { 0% { transform: translate(0, 0); } 100% { transform: translate(-100%, 0); } }

/* --- Cinematic Hero in HD --- */
.luxury-hero-gallery {
    position: relative;
    width: 100%;
    height: 85vh;
    min-height: 560px;
    background: #0f172a;
    overflow: hidden;
}
.hero-swiper { width: 100%; height: 100%; }
.hero-swiper .swiper-slide img {
    width: 100%; height: 100%; object-fit: cover; opacity: 1; image-rendering: -webkit-optimize-contrast;
    transition: transform 10s cubic-bezier(0.25, 1, 0.5, 1);
}
.hero-swiper .swiper-slide-active img { transform: scale(1.03); }

.luxury-hero-gallery::after {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.05) 45%, rgba(0,0,0,0.78) 100%);
    z-index: 1; pointer-events: none;
}

.hero-content {
    position: absolute; bottom: 0; left: 0; width: 100%; z-index: 2;
    padding-bottom: 80px; color: white; display: flex; flex-direction: column; align-items: center; text-align: center;
    pointer-events: none;
}
.dual-logo-container {
    display: flex; align-items: center; gap: 20px; margin-bottom: 20px;
}
.logo-box {
    background: rgba(255,255,255,0.95); padding: 10px 20px; border-radius: 12px;
    height: 70px; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.2);
}
.logo-box img { max-height: 45px; max-width: 120px; object-fit: contain; }

.luxury-badge {
    display: inline-block; padding: 6px 14px; background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);
    border-radius: 50px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 2px; margin-right: 10px; margin-bottom: 15px;
}
.luxury-title {
    font-size: 4rem; font-weight: 900; letter-spacing: -1px; margin-bottom: 10px;
    text-shadow: 0 10px 30px rgba(0,0,0,0.5); font-family: 'Outfit', sans-serif;
}
.luxury-location {
    font-size: 1.25rem; font-weight: 500; opacity: 0.9; display: flex; align-items: center; justify-content: center; gap: 10px;
}

/* --- Hero Animations & Glass Banner --- */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }

.hero-stats-banner {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    padding: 25px 30px;
    margin-top: 30px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}
.hero-stat-item {
    border-right: 1px solid rgba(255,255,255,0.2);
}
.hero-stat-item:last-child {
    border-right: none;
}
.hero-stat-label {
    font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.8); margin-bottom: 5px; font-weight: 600;
}
.hero-stat-val {
    font-size: 1.5rem; font-weight: 800; color: #fff; text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

@media (max-width: 768px) {
    .hero-content { padding-bottom: 40px; }
    .luxury-title { font-size: 2.2rem; }
    .luxury-location { font-size: 1rem; flex-wrap: wrap; }
    
    .hero-stats-banner { grid-template-columns: repeat(2, 1fr); padding: 15px; }
    .hero-stat-item { border-right: 1px solid rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 15px; }
    .hero-stat-item:nth-child(even) { border-right: none; }
    .hero-stat-item:nth-last-child(-n+2) { border-bottom: none; padding-bottom: 0; }
    .hero-stat-val { font-size: 1.2rem; }

    .lux-section { padding: 25px 20px; border-radius: 16px; margin-bottom: 20px; }
    .lux-section-title { font-size: 1.4rem; margin-bottom: 20px; }
    
    .bento-grid { grid-template-columns: 1fr; gap: 10px; }
    .conn-list { grid-template-columns: 1fr; }
    
    .logo-box img, .cph-logo img { max-height: 45px !important; }
}
@media (max-width: 480px) {
    .hero-stats-banner { grid-template-columns: 1fr; }
    .hero-stat-item { border-right: none !important; border-bottom: 1px solid rgba(255,255,255,0.2) !important; padding-bottom: 15px !important; }
    .hero-stat-item:last-child { border-bottom: none !important; padding-bottom: 0 !important; }
}

/* --- Glassmorphic Sections --- */
.lux-section {
    background: #fff; border-radius: 24px; border: 1px solid #f1f5f9;
    padding: 40px; margin-bottom: 30px; box-shadow: 0 15px 40px rgba(0,0,0,0.02);
}
.lux-section-title {
    font-size: 1.75rem; font-weight: 800; margin-bottom: 30px; color: #0f172a;
    display: flex; align-items: center; gap: 12px; position: relative;
}
.lux-section-title i { color: var(--pr-primary); font-size: 1.5rem; }
.lux-description { font-size: 1.1rem; line-height: 1.8; color: #475569; white-space: pre-wrap; }

/* --- Bento Grid --- */
.bento-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 30px; }
.bento-box {
    background: #f8fafc; padding: 25px 20px; border-radius: 16px;
    border: 1px solid #e2e8f0; display: flex; flex-direction: column; align-items: center; text-center;
    transition: all 0.3s ease;
}
.bento-box:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-color: var(--pr-primary); }
.bento-icon { font-size: 2rem; color: var(--pr-primary); margin-bottom: 12px; }
.bento-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 700; margin-bottom: 5px; }
.bento-value { font-size: 1.25rem; font-weight: 800; color: #0f172a; }

/* --- Connectivity Tabs --- */
.conn-tabs .nav-link {
    color: #111; background: #fff; border: 1px solid var(--pr-primary); border-radius: 4px;
    padding: 10px 20px; font-size: 1rem; margin-right: 10px; font-weight: 600; white-space: nowrap;
}
.conn-tabs .nav-link.active {
    color: #fff; background: var(--pr-primary); border-color: var(--pr-primary);
}
.conn-list { list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: repeat(2, 1fr); column-gap: 30px; }
.conn-list li {
    padding: 15px 0; border-bottom: 1px dashed #e2e8f0; display: flex; flex-direction: row; align-items: center; gap: 15px;
}
.conn-list li:last-child { border-bottom: none; }
.conn-list li .pin-icon { font-size: 2.2rem; color: #111; }
.conn-list li .conn-name { font-size: 1.1rem; color: #334155; margin-bottom: 0; font-weight: 500; }
.conn-list li .conn-dist { font-size: 1.1rem; color: #64748b; margin-left: auto; }

/* --- Virtual Tour Grid Styles --- */
.vt-btn { background: var(--pr-primary); color: #111; border: none; padding: 8px 24px; border-radius: 4px; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; margin-top: 15px; display: inline-block; transition: 0.3s; }
.vt-btn:hover { background: #35251a; color: #fff; }
.vt-box { background: #fff; border-radius: 0; padding: 20px; display: flex; flex-direction: column; align-items: center; }
.vt-title { font-size: 1.1rem; color: #111; margin-bottom: 15px; font-weight: 500; }
.vt-img-wrap { position: relative; width: 100%; aspect-ratio: 16/9; background: #eee; overflow: hidden; }
.vt-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
.vt-img-wrap iframe { width: 100%; height: 100%; border: 0; }
.vt-play-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 3rem; color: #fff; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); z-index: 2; pointer-events: none; }
.vt-mag-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 2.5rem; color: #fff; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); z-index: 2; pointer-events: none; }
.fp-item-override { cursor: pointer; display: block; position: relative; width: 100%; height: 100%; }
.fp-item-override::after { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0.1); pointer-events: none; }

/* --- Book Site Visit Banner --- */
.site-visit-banner {
    background: #e6e4dc; position: relative; overflow: hidden; padding: 60px 0 120px 0; text-align: center;
}
.site-visit-banner::before {
    content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 200px;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23d1d5db" fill-opacity="1" d="M0,192L48,176C96,160,192,128,288,144C384,160,480,224,576,213.3C672,203,768,117,864,106.7C960,96,1056,160,1152,186.7C1248,213,1344,203,1392,197.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover; z-index: 1; opacity: 0.5;
}
.site-visit-title { font-size: 2.5rem; color: #111; font-weight: 500; margin-bottom: 30px; position: relative; z-index: 2; }
.site-visit-btn { background: #684b2c; color: #fff; border: none; padding: 15px 40px; font-size: 1.2rem; font-weight: 600; border-radius: 4px; position: relative; z-index: 2; transition: 0.3s; }
.site-visit-btn:hover { background: #4a3424; color: #fff; }
.site-visit-car { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 3; width: 300px; max-width: 90%; }
.site-visit-road { position: absolute; bottom: 0; left: 0; width: 100%; height: 40px; background: #333; z-index: 2; border-top: 4px dashed #fff; }

/* --- Amenities Grid --- */
.lux-amenities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; }
.lux-amenity-card {
    background: #fff; border: 1px solid #f1f5f9; border-radius: 16px; padding: 20px 15px;
    display: flex; flex-direction: column; align-items: center; text-align: center; gap: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.02); transition: all 0.3s;
}
.lux-amenity-card:hover { border-color: var(--pr-primary); transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
.lux-amenity-icon-box {
    width: 60px; height: 60px; background: rgba(229,175,83,0.1); border-radius: 50%;
    display: flex; align-items: center; justify-content: center; color: var(--pr-primary); font-size: 1.5rem;
}

/* --- Gallery Tabs --- */
.gallery-tabs .nav-link {
    color: #64748b; font-weight: 600; border: none; border-bottom: 3px solid transparent;
    padding: 10px 20px; font-size: 1.1rem; margin-right: 15px; background: transparent;
}
.gallery-tabs .nav-link.active {
    color: var(--pr-primary); border-bottom-color: var(--pr-primary); background: transparent;
}
.fp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
.fp-item { position: relative; border-radius: 16px; overflow: hidden; background: #fff; cursor: pointer; aspect-ratio: 4/3; }
.fp-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.fp-item:hover img { transform: scale(1.1); }
.fp-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s;
}
.fp-item:hover .fp-overlay { opacity: 1; }

/* --- EMI Calculator --- */
.emi-calc-box { background: #f8fafc; border-radius: 20px; padding: 30px; border: 1px solid #e2e8f0; }
.emi-result { background: var(--pr-primary); color: #fff; padding: 25px; border-radius: 16px; text-align: center; }

/* --- Sticky Sidebar Form --- */
.sticky-enquiry-wrapper { position: sticky; top: 100px; z-index: 10; }
.glass-sidebar {
    border: 1px solid rgba(0,0,0,0.05); border-radius: 24px; padding: 35px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.05);
}
.price-display { font-size: 2.25rem; font-weight: 900; color: #0f172a; margin-bottom: 25px; font-family: 'Outfit', sans-serif; }

/* ─── Floor Plan Slot Cards ──────────────────────────── */
.fp-slot-card {
    border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0;
    background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    display: flex; flex-direction: column;
}
.fp-slot-card:hover { transform: translateY(-4px); box-shadow: 0 12px 35px rgba(0,0,0,0.12); }
.fp-slot-thumb {
    display: block; position: relative; overflow: hidden;
    aspect-ratio: 4/3; background: #f1f5f9;
}
.fp-slot-thumb img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.4s ease;
}
.fp-slot-card:hover .fp-slot-thumb img { transform: scale(1.04); }
.fp-slot-zoom {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,0.35); color: #fff; font-size: 1.4rem;
    opacity: 0; transition: opacity 0.25s ease;
}
.fp-slot-card:hover .fp-slot-zoom { opacity: 1; }
.fp-slot-label {
    padding: 10px 14px; font-size: 0.82rem; font-weight: 700;
    text-align: center; background: #f8fafc; color: #1e293b;
    border-top: 1px solid #e2e8f0; letter-spacing: 0.3px;
}

/* ─── Master Plan ────────────────────────────────────── */
.fp-master-wrap {
    border-radius: 18px; overflow: hidden; border: 2px solid var(--pr-primary);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1); position: relative;
    background: #f8fafc;
}
.fp-master-badge {
    background: var(--pr-primary); color: #fff;
    padding: 10px 22px; font-weight: 700; font-size: 0.95rem;
    letter-spacing: 0.5px;
}
.fp-master-link {
    display: block; position: relative; overflow: hidden;
    max-height: 520px;
}
.fp-master-img {
    width: 100%; max-height: 520px; object-fit: contain;
    background: #fff; display: block;
    transition: transform 0.4s ease;
}
.fp-master-link:hover .fp-master-img { transform: scale(1.02); }
.fp-master-overlay {
    position: absolute; inset: 0; display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 10px;
    background: rgba(0,0,0,0.35); color: #fff;
    opacity: 0; transition: opacity 0.3s ease;
}
.fp-master-link:hover .fp-master-overlay { opacity: 1; }
.fp-master-overlay span { font-size: 0.9rem; font-weight: 600; letter-spacing: 1px; }

/* --- Lightbox Modal Styles --- */
.pr-lightbox-modal {
    position: fixed; inset: 0; z-index: 99999;
    background: rgba(10, 15, 29, 0.95);
    backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
    display: none; opacity: 0; transition: opacity 0.3s ease;
    align-items: center; justify-content: center; flex-direction: column;
    user-select: none;
}
.pr-lightbox-modal.active { display: flex; opacity: 1; }
.pr-lightbox-toolbar {
    position: absolute; top: 0; left: 0; right: 0; padding: 20px 30px;
    display: flex; align-items: center; justify-content: space-between;
    color: #fff; z-index: 100001; background: linear-gradient(180deg, rgba(0,0,0,0.6) 0%, transparent 100%);
}
.pr-lightbox-counter { font-size: 0.95rem; font-weight: 600; color: rgba(255,255,255,0.75); letter-spacing: 1px; }
.pr-lightbox-actions { display: flex; align-items: center; gap: 15px; }
.pr-lightbox-btn {
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25);
    color: #fff; width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; cursor: pointer; transition: all 0.25s ease;
    text-decoration: none;
}
.pr-lightbox-btn:hover { background: var(--pr-primary, #b08d55); color: #fff; border-color: var(--pr-primary, #b08d55); transform: scale(1.08); }
.pr-lightbox-body {
    position: relative; max-width: 92vw; max-height: 82vh;
    display: flex; align-items: center; justify-content: center;
}
.pr-lightbox-img {
    max-width: 92vw; max-height: 80vh; object-fit: contain;
    border-radius: 8px; box-shadow: 0 25px 60px rgba(0,0,0,0.5);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease;
}
.pr-lightbox-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25);
    color: #fff; width: 54px; height: 54px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; cursor: pointer; transition: all 0.25s ease;
    z-index: 100001;
}
.pr-lightbox-nav:hover { background: var(--pr-primary, #b08d55); border-color: var(--pr-primary, #b08d55); transform: translateY(-50%) scale(1.1); }
.pr-lightbox-prev { left: 30px; }
.pr-lightbox-next { right: 30px; }
.pr-lightbox-caption {
    position: absolute; bottom: 25px; left: 50%; transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(10px);
    padding: 10px 24px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.2);
    color: #fff; font-size: 0.95rem; font-weight: 600; text-align: center;
    max-width: 80%; text-shadow: 0 1px 3px rgba(0,0,0,0.5); z-index: 100001;
}
.cursor-pointer { cursor: pointer !important; }
@media (max-width: 768px) {
    .pr-lightbox-prev { left: 10px; width: 44px; height: 44px; font-size: 1.2rem; }
    .pr-lightbox-next { right: 10px; width: 44px; height: 44px; font-size: 1.2rem; }
    .pr-lightbox-toolbar { padding: 15px; }
    .pr-lightbox-caption { bottom: 15px; font-size: 0.85rem; padding: 8px 18px; }
}
</style>

<!-- 2. Custom Project Header (Image 2) -->
<header class="custom-proj-header glass-header">
    <div class="container-fluid px-3 px-md-5">
        <div class="cph-logo d-flex align-items-center gap-3">
            <?php if ($p['project_logo']): ?>
                <a href="<?= PUBLIC_URL ?>"><img src="<?= upload($p['project_logo']) ?>" alt="<?= e($p['name']) ?>" style="height:70px; width:auto; object-fit:contain;"></a>
            <?php endif; ?>
            <?php if ($p['builder_logo']): ?>
                <a href="<?= PUBLIC_URL ?>"><img src="<?= upload($p['builder_logo']) ?>" alt="<?= e($p['builder_name']) ?>" style="<?= $p['project_logo'] ? 'height:70px; border-left:2px solid #ddd; padding-left:15px;' : 'height:70px;' ?> width:auto; object-fit:contain;"></a>
            <?php elseif (!$p['project_logo']): ?>
                <a href="<?= PUBLIC_URL ?>" class="text-dark fw-bold text-decoration-none fs-4"><?= e($p['builder_name']) ?></a>
            <?php endif; ?>
        </div>
        <div class="cph-actions d-none d-md-flex">
            <a href="tel:<?= e(str_replace(' ','',$phone)) ?>" class="cph-btn">
                <i class="fas fa-phone-alt" style="color:var(--pr-primary);"></i> <?= e($phone) ?>
            </a>
            <a href="https://wa.me/<?= e(str_replace(['+',' '],'',$wa)) ?>?text=<?= urlencode("Hi, I'm interested in {$p['name']}.") ?>" target="_blank" class="cph-btn">
                <i class="fab fa-whatsapp" style="color:#25D366;"></i> WhatsApp
            </a>
            <div class="cph-hamburger" onclick="document.getElementById('drawerToggle').click();">
                <span></span><span></span><span></span>
            </div>
        </div>
    </div>
</header>

<!-- Marquee Text -->
<?php if (!empty($p['marquee_text'])): ?>
<div class="marquee-bar">
    <div class="marquee-content"><?= e($p['marquee_text']) ?> &nbsp;&nbsp;&bull;&nbsp;&nbsp; <?= e($p['marquee_text']) ?> &nbsp;&nbsp;&bull;&nbsp;&nbsp; <?= e($p['marquee_text']) ?></div>
</div>
<?php endif; ?>

<!-- 1. Immersive Hero Gallery -->
<div class="luxury-hero-gallery">
  <div class="swiper hero-swiper">
    <div class="swiper-wrapper">
      <?php if (!empty($bannerImages)): ?>
          <?php foreach ($bannerImages as $img): ?>
          <div class="swiper-slide cursor-pointer" data-lightbox="gallery" data-src="<?= upload($img) ?>" data-title="<?= e($p['name']) ?> - Banner View"><img src="<?= upload($img) ?>" alt="<?= e($p['name']) ?>" loading="eager"></div>
          <?php endforeach; ?>
      <?php elseif (!empty($exteriorImages)): ?>
          <?php foreach ($exteriorImages as $img): ?>
          <div class="swiper-slide cursor-pointer" data-lightbox="gallery" data-src="<?= upload($img) ?>" data-title="<?= e($p['name']) ?> - Exterior View"><img src="<?= upload($img) ?>" alt="<?= e($p['name']) ?>"></div>
          <?php endforeach; ?>
      <?php elseif (!empty($galleryImages)): ?>
          <?php foreach ($galleryImages as $img): ?>
          <div class="swiper-slide cursor-pointer" data-lightbox="gallery" data-src="<?= upload($img) ?>" data-title="<?= e($p['name']) ?> - Gallery View"><img src="<?= upload($img) ?>" alt="<?= e($p['name']) ?>"></div>
          <?php endforeach; ?>
      <?php else: ?>
          <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075&auto=format&fit=crop" alt="Default Hero"></div>
      <?php endif; ?>
    </div>
    <div class="swiper-button-next" style="color:white; text-shadow:0 2px 4px rgba(0,0,0,0.5);"></div>
    <div class="swiper-button-prev" style="color:white; text-shadow:0 2px 4px rgba(0,0,0,0.5);"></div>
  </div>

  <div class="hero-content">
    <div class="container-fluid px-3 px-md-5">
      
      <!-- Dual Logos Moved to Header -->

      <div class="animate-fade-up">
          <span class="luxury-badge"><?= e(str_replace('_', ' ', ucfirst($p['status']))) ?></span>
          <span class="luxury-badge" style="background: var(--pr-primary); color: #111; border:none;"><?= e(ucfirst($p['type'])) ?></span>
      </div>
      
      <h1 class="luxury-title animate-fade-up delay-1"><?= e($p['name']) ?></h1>
      <div class="luxury-location animate-fade-up delay-2">
        <i class="fas fa-map-marker-alt" style="color:var(--pr-primary);"></i> 
        <?= e($p['location_area']) ? e($p['location_area']) . ', ' : '' ?><?= e($p['city_name']) ?>
      </div>

      <!-- Glassmorphic Core Stats Banner -->
      <div class="hero-stats-banner animate-fade-up delay-3">
          <?php if (!empty($p['price_display']) || !empty($p['price_min'])): ?>
          <div class="hero-stat-item">
              <div class="hero-stat-label">Starting Price</div>
              <div class="hero-stat-val"><?= View::priceRange($p['price_min'], $p['price_max'], (bool)$p['price_on_request'], $p['price_display'] ?? '') ?></div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($p['unit_types'])): ?>
          <div class="hero-stat-item">
              <div class="hero-stat-label">Configurations</div>
              <div class="hero-stat-val"><?= e($p['unit_types']) ?></div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($p['area_range'])): ?>
          <div class="hero-stat-item">
              <div class="hero-stat-label">Carpet Area</div>
              <div class="hero-stat-val"><?= e($p['area_range']) ?></div>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($p['possession_date'])): ?>
          <div class="hero-stat-item">
              <div class="hero-stat-label">Possession</div>
              <div class="hero-stat-val"><?= e($p['possession_date']) ?></div>
          </div>
          <?php endif; ?>
      </div>
    </div>
  </div>
</div>


<div class="section pt-5 pb-5">
  <div class="container-fluid px-3 px-md-5">
    <div class="row g-5">

      <!-- ── LEFT COLUMN ── -->
      <div class="col-lg-8">

        <!-- Bento Box Facts -->
        <div class="bento-grid">
            <?php if ($p['unit_types']): ?>
            <div class="bento-box glass-panel">
                <i class="fas fa-bed bento-icon"></i>
                <div class="bento-label">Configurations</div>
                <div class="bento-value"><?= e($p['unit_types']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($p['area_range']): ?>
            <div class="bento-box glass-panel">
                <i class="fas fa-expand-arrows-alt bento-icon"></i>
                <div class="bento-label">Carpet Area</div>
                <div class="bento-value"><?= e($p['area_range']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($p['total_area']): ?>
            <div class="bento-box glass-panel">
                <i class="fas fa-vector-square bento-icon"></i>
                <div class="bento-label">Project Area</div>
                <div class="bento-value"><?= e($p['total_area']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($p['possession_date']): ?>
            <div class="bento-box glass-panel">
                <i class="fas fa-calendar-check bento-icon"></i>
                <div class="bento-label">Possession</div>
                <div class="bento-value"><?= e($p['possession_date']) ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Plain Text Description -->
        <?php if ($p['description'] || $p['short_description']): ?>
        <div class="lux-section glass-panel">
          <h2 class="lux-section-title"><i class="fas fa-info-circle"></i> About <?= e($p['name']) ?></h2>
          <div class="lux-description"><?= e($p['description'] ?: $p['short_description']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Project Highlights -->
        <?php if ($p['highlights']): ?>
        <div class="lux-section glass-panel">
          <h2 class="lux-section-title"><i class="fas fa-star"></i> Project Highlights</h2>
          <ul class="highlight-list">
              <?php 
              $hlines = explode("\n", $p['highlights']);
              foreach ($hlines as $hl): 
                  if(trim($hl)):
              ?>
              <li><i class="fas fa-check-circle"></i> <span><?= html_entity_decode(e(trim($hl))) ?></span></li>
              <?php endif; endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Connectivity (Tabbed as per Image 2) -->
        <?php 
        $connData = [];
        if (!empty($p['connectivity'])) {
            $decoded = json_decode($p['connectivity'], true);
            if (is_array($decoded)) {
                // Filter out empty tabs
                foreach($decoded as $k => $v) {
                    if (trim($v)) $connData[$k] = trim($v);
                }
            } else {
                $connData = ['Connectivity' => trim($p['connectivity'])];
            }
        }
        if (!empty($connData)): 
            $tabIndex = 0;
        ?>
        <div class="lux-section glass-panel">
          <div class="d-flex justify-content-between align-items-center mb-4">
              <h2 class="lux-section-title mb-0" style="font-size: 1.5rem;"><i class="fas fa-route"></i> Connectivity</h2>
              <button type="button" class="btn btn-sm text-white" style="background: var(--pr-primary); font-weight: 600;" data-bs-toggle="modal" data-bs-target="#enquiryModal"><i class="fas fa-download me-1"></i> Download Connectivity</button>
          </div>
          
          <ul class="nav nav-pills conn-tabs mb-4 flex-nowrap overflow-auto" id="connTabs" role="tablist" style="padding-bottom: 10px;">
            <?php foreach ($connData as $tabName => $tabContent): ?>
            <li class="nav-item" role="presentation">
              <button class="nav-link <?= $tabIndex === 0 ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#conn-tab-<?= $tabIndex ?>" type="button"><?= e($tabName) ?></button>
            </li>
            <?php $tabIndex++; endforeach; ?>
          </ul>

        <div class="tab-content" id="connTabsContent">
            <?php $tabIndex = 0; foreach ($connData as $tabName => $tabContent): ?>
            <div class="tab-pane fade <?= $tabIndex === 0 ? 'show active' : '' ?>" id="conn-tab-<?= $tabIndex ?>" role="tabpanel">
                <ul class="conn-list">
                    <?php 
                    $lines = explode("\n", $tabContent);
                    foreach ($lines as $line): 
                        if(trim($line)):
                    ?>
                    <li>
                        <i class="fas fa-map-marker-alt pin-icon"></i>
                        <div class="conn-name"><?= html_entity_decode(e(trim($line))) ?></div>
                    </li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>
            <?php $tabIndex++; endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Premium Amenities Grid -->
        <?php if (!empty($projectAmenities)): ?>
        <div class="lux-section glass-panel">
          <h2 class="lux-section-title"><i class="fas fa-gem"></i> Premium Lifestyle Amenities</h2>
          <div class="lux-amenities-grid">
            <?php foreach ($projectAmenities as $am): 
                $amL = strtolower($am);
                $icon = 'fa-check';
                if(strpos($amL, 'pool')!==false) $icon='fa-swimmer';
                if(strpos($amL, 'gym')!==false || strpos($amL, 'fitness')!==false) $icon='fa-dumbbell';
                if(strpos($amL, 'park')!==false || strpos($amL, 'garden')!==false) $icon='fa-tree';
                if(strpos($amL, 'security')!==false) $icon='fa-shield-alt';
                if(strpos($amL, 'club')!==false) $icon='fa-glass-cheers';
                if(strpos($amL, 'parking')!==false) $icon='fa-car';
            ?>
            <div class="lux-amenity-card glass-panel">
              <div class="lux-amenity-icon-box"><i class="fas <?= $icon ?>"></i></div>
              <div class="fw-bold text-dark" style="font-size:1.1rem;"><?= e($am) ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Visual Galleries (Tabs for Interior/Exterior/Legacy) -->
        <?php if (!empty($interiorImages) || !empty($exteriorImages) || !empty($galleryImages)): ?>
        <div class="lux-section glass-panel">
          <h2 class="lux-section-title"><i class="fas fa-camera-retro"></i> Project Gallery</h2>
          
          <ul class="nav nav-tabs gallery-tabs mb-4" id="galleryTabs" role="tablist">
            <?php if(!empty($exteriorImages)): ?>
            <li class="nav-item" role="presentation">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-ext" type="button">Exterior Views</button>
            </li>
            <?php endif; ?>
            <?php if(!empty($interiorImages)): ?>
            <li class="nav-item" role="presentation">
              <button class="nav-link <?= empty($exteriorImages) ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#tab-int" type="button">Interior Views</button>
            </li>
            <?php endif; ?>
            <?php if(!empty($galleryImages) && empty($exteriorImages) && empty($interiorImages)): ?>
            <li class="nav-item" role="presentation">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-gal" type="button">All Images</button>
            </li>
            <?php endif; ?>
          </ul>
          
          <div class="tab-content" id="galleryTabsContent">
            <?php if(!empty($exteriorImages)): ?>
            <div class="tab-pane fade show active" id="tab-ext" role="tabpanel">
                <div class="fp-grid">
                    <?php foreach ($exteriorImages as $img): ?>
                    <div class="fp-item"><a href="<?= upload($img) ?>" data-lightbox="gallery" data-title="Exterior View - <?= e($p['name']) ?>"><img src="<?= upload($img) ?>" alt="Exterior"><div class="fp-overlay"><i class="fas fa-search-plus fa-2x text-white"></i></div></a></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($interiorImages)): ?>
            <div class="tab-pane fade <?= empty($exteriorImages) ? 'show active' : '' ?>" id="tab-int" role="tabpanel">
                <div class="fp-grid">
                    <?php foreach ($interiorImages as $img): ?>
                    <div class="fp-item"><a href="<?= upload($img) ?>" data-lightbox="gallery" data-title="Interior View - <?= e($p['name']) ?>"><img src="<?= upload($img) ?>" alt="Interior"><div class="fp-overlay"><i class="fas fa-search-plus fa-2x text-white"></i></div></a></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($galleryImages) && empty($exteriorImages) && empty($interiorImages)): ?>
            <div class="tab-pane fade show active" id="tab-gal" role="tabpanel">
                <div class="fp-grid">
                    <?php foreach ($galleryImages as $img): ?>
                    <div class="fp-item"><a href="<?= upload($img) ?>" data-lightbox="gallery" data-title="Project Gallery - <?= e($p['name']) ?>"><img src="<?= upload($img) ?>" alt="Gallery"><div class="fp-overlay"><i class="fas fa-search-plus fa-2x text-white"></i></div></a></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php
        // ── Helper for Rendering Floor Plan Cards ─────────────────────────
        if (!function_exists('renderFrontendFloorPlanCard')) {
            function renderFrontendFloorPlanCard($fp) {
                $img     = !empty($fp['image']) ? upload($fp['image']) : '';
                $title   = trim($fp['plan_name'] ?? '');
                $config  = trim($fp['configuration'] ?? '');
                $area    = trim($fp['area'] ?? '');
                $price   = trim($fp['price'] ?? '');
                $ctaText = trim($fp['cta_text'] ?? '') ?: 'View Floor Plan';
                $ctaUrl  = trim($fp['cta_url'] ?? '');
                $target  = ($ctaUrl && (str_starts_with($ctaUrl, 'http') || str_starts_with($ctaUrl, '/'))) ? '_blank' : '';
                $modalTarget = (!$ctaUrl || str_starts_with($ctaUrl, '#')) ? ($ctaUrl ?: '#enquiryModal') : '';
                ?>
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white hover-shadow transition" style="border: 1px solid #eaeaea !important;">
                    <?php if ($img): ?>
                      <div class="position-relative overflow-hidden bg-light text-center border-bottom" style="height: 220px;">
                        <a href="<?= $img ?>" data-lightbox="gallery" data-title="<?= e($title ?: ($config ? $config . ' Floor Plan' : 'Floor Plan')) ?><?= $area ? ' (' . e($area) . ')' : '' ?>">
                          <img src="<?= $img ?>" alt="<?= e($title ?: $config ?: 'Floor Plan') ?>" class="w-100 h-100" style="object-fit: contain; padding: 12px;" loading="lazy">
                          <div class="position-absolute top-0 end-0 m-2 bg-dark bg-opacity-75 text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                            <i class="fas fa-search-plus"></i>
                          </div>
                        </a>
                      </div>
                    <?php endif; ?>

                    <div class="card-body p-3 d-flex flex-column">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <?php if ($config): ?>
                          <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1" style="font-size:0.85rem; border:1px solid rgba(13,110,253,0.2);"><?= e($config) ?></span>
                        <?php endif; ?>
                        <?php if ($price): ?>
                          <span class="fw-bold ms-auto" style="font-size:0.95rem; color:#b08d55 !important;"><?= e($price) ?></span>
                        <?php endif; ?>
                      </div>

                      <?php if ($title): ?>
                        <h5 class="fw-bold text-dark mb-1" style="font-size:1.05rem;"><?= e($title) ?></h5>
                      <?php endif; ?>

                      <?php if ($area): ?>
                        <div class="text-muted small mb-3"><i class="fas fa-ruler-combined me-1 text-secondary"></i>Carpet Area: <span class="fw-semibold text-dark"><?= e($area) ?></span></div>
                      <?php endif; ?>

                      <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center gap-2">
                        <?php if ($img): ?>
                          <a href="<?= $img ?>" data-lightbox="gallery" data-title="<?= e($title ?: ($config ? $config . ' Floor Plan' : 'Floor Plan')) ?><?= $area ? ' (' . e($area) . ')' : '' ?>" class="btn btn-sm btn-outline-secondary flex-grow-1"><i class="fas fa-eye me-1"></i>Preview</a>
                        <?php endif; ?>

                        <?php if ($modalTarget): ?>
                          <button type="button" class="btn btn-sm btn-dark fw-bold flex-grow-1" style="background:#111; border-color:#b08d55;" data-bs-toggle="modal" data-bs-target="<?= e($modalTarget) ?>"><i class="fas fa-calendar-check me-1"></i><?= e($ctaText) ?></button>
                        <?php elseif ($ctaUrl): ?>
                          <a href="<?= e($ctaUrl) ?>" <?= $target ? 'target="_blank"' : '' ?> class="btn btn-sm btn-dark fw-bold flex-grow-1" style="background:#111; border-color:#b08d55;"><i class="fas fa-arrow-right me-1"></i><?= e($ctaText) ?></a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
            }
        }

        // ── Dynamic & Custom Floor Plans ───────────────────────────────────
        $dbFloorPlans = $floorPlans ?? [];

        // Fallback for legacy slots if project_floor_plans database entries are empty
        if (empty($dbFloorPlans)) {
            for ($s = 1; $s <= 6; $s++) {
                $img = $p["fp_{$s}_image"] ?? null;
                $lbl = trim($p["fp_{$s}_label"] ?? '');
                if (!empty($img)) {
                    $dbFloorPlans[] = [
                        'id'            => 0,
                        'configuration' => '',
                        'plan_name'     => $lbl ?: "Floor Plan {$s}",
                        'area'          => '',
                        'price'         => '',
                        'image'         => $img,
                        'cta_text'      => 'View Floor Plan',
                        'cta_url'       => '',
                    ];
                }
            }
        }

        $masterPlanImg   = $p['master_plan_image'] ?? null;
        $masterPlanLabel = trim($p['master_plan_label'] ?? 'Master Plan') ?: 'Master Plan';
        $masterPlanDesc  = trim($p['master_plan_description'] ?? '');
        $masterPlanPdf   = trim($p['master_plan_pdf'] ?? '');
        $hasFpSection    = !empty($dbFloorPlans) || !empty($masterPlanImg) || !empty($masterPlanPdf);

        // Group floor plans by configuration for filter tabs if multiple configurations exist
        $configsMap = [];
        foreach ($dbFloorPlans as $fp) {
            $cfg = trim($fp['configuration'] ?? '') ?: 'General';
            $configsMap[$cfg][] = $fp;
        }
        $hasMultiConfigs = count($configsMap) > 1;
        ?>

        <?php if ($hasFpSection): ?>
        <div class="lux-section glass-panel" id="floor-plans">
          <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 border-bottom pb-3">
            <h2 class="lux-section-title mb-0"><i class="fas fa-layer-group text-primary me-2"></i> Floor Plans & Layouts</h2>
            <?php if (!empty($p['brochure_pdf'])): ?>
              <a href="<?= upload($p['brochure_pdf']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold"><i class="fas fa-download me-1"></i> Download Project Brochure</a>
            <?php endif; ?>
          </div>

          <?php if (!empty($dbFloorPlans)): ?>
            <?php if ($hasMultiConfigs): ?>
              <!-- Filter Tabs by Configuration -->
              <ul class="nav nav-pills conn-tabs mb-4 flex-nowrap overflow-auto" id="fpFilterTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#fp-tab-all" type="button">All Floor Plans</button>
                </li>
                <?php $cIdx = 0; foreach ($configsMap as $cfgName => $cfgList): $cIdx++; ?>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fp-tab-<?= $cIdx ?>" type="button"><?= e($cfgName) ?></button>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <div class="tab-content" id="fpTabsContent">
              <!-- All Floor Plans Tab -->
              <div class="tab-pane fade show active" id="fp-tab-all" role="tabpanel">
                <div class="row g-4 mb-4">
                  <?php foreach ($dbFloorPlans as $fp): ?>
                    <?php renderFrontendFloorPlanCard($fp); ?>
                  <?php endforeach; ?>
                </div>
              </div>

              <?php if ($hasMultiConfigs): ?>
                <?php $cIdx = 0; foreach ($configsMap as $cfgName => $cfgList): $cIdx++; ?>
                  <div class="tab-pane fade" id="fp-tab-<?= $cIdx ?>" role="tabpanel">
                    <div class="row g-4 mb-4">
                      <?php foreach ($cfgList as $fp): ?>
                        <?php renderFrontendFloorPlanCard($fp); ?>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <?php
          $masterPlans = !empty($p['master_plans_json']) ? json_decode($p['master_plans_json'], true) : [];
          if (!is_array($masterPlans)) $masterPlans = [];
          if (empty($masterPlans) && (!empty($masterPlanImg) || !empty($masterPlanPdf) || !empty($masterPlanDesc))) {
              $masterPlans[] = [
                  'label'       => $masterPlanLabel ?: 'Master Plan',
                  'description' => $masterPlanDesc,
                  'image'       => $masterPlanImg,
                  'pdf'         => $masterPlanPdf,
              ];
          }
          ?>

          <?php if (!empty($masterPlans)): ?>
          <div class="mt-5 d-flex flex-column gap-4">
            <?php foreach ($masterPlans as $mpIdx => $mpItem): 
                $mpImg  = !empty($mpItem['image']) ? upload($mpItem['image']) : '';
                $mpPdf  = !empty($mpItem['pdf']) ? upload($mpItem['pdf']) : '';
                $mpLbl  = trim($mpItem['label'] ?? '') ?: 'Master Plan';
                $mpDesc = trim($mpItem['description'] ?? '');
            ?>
            <div class="fp-master-wrap p-4 bg-white rounded-3 border shadow-sm">
              <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="fp-master-badge mb-0" style="font-size:1.1rem; font-weight:700;"><i class="fas fa-map me-2 text-primary"></i><?= e($mpLbl) ?></div>
                <?php if (!empty($mpPdf)): ?>
                  <a href="<?= e($mpPdf) ?>" target="_blank" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold"><i class="fas fa-file-pdf me-1"></i> Download <?= e($mpLbl) ?> Layout PDF</a>
                <?php endif; ?>
              </div>

              <?php if (!empty($mpDesc)): ?>
                <p class="text-muted small mb-3"><?= nl2br(e($mpDesc)) ?></p>
              <?php endif; ?>

              <?php if (!empty($mpImg)): ?>
                <a href="<?= e($mpImg) ?>" data-lightbox="gallery" data-title="<?= e($mpLbl) ?> - <?= e($p['name']) ?>" class="fp-master-link d-block position-relative rounded overflow-hidden text-center border">
                  <img src="<?= e($mpImg) ?>" alt="<?= e($mpLbl) ?>" class="fp-master-img img-fluid" style="max-height:500px; width:100%; object-fit:contain; background:#fafafa; padding:10px;" loading="lazy">
                  <div class="fp-master-overlay"><i class="fas fa-search-plus fa-2x mb-2 text-white"></i><span class="d-block text-white fw-bold">View Full <?= e($mpLbl) ?></span></div>
                </a>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Custom Virtual Tour & Video Walkthrough Grid -->
        <?php if (!empty($p['video_url']) || !empty($p['virtual_tour_url']) || count($floorPlanImages) > 0): 
            $embedVideoUrl = !empty($p['video_url']) ? View::videoEmbedUrl($p['video_url']) : '';
            $embedTourUrl  = !empty($p['virtual_tour_url']) ? View::videoEmbedUrl($p['virtual_tour_url']) : '';
        ?>
        <div class="lux-section glass-panel" style="background:#fdfdfd; border:1px solid #f1f5f9; padding:40px 30px;">
          <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 flex-wrap gap-2">
              <h2 class="lux-section-title mb-0" style="font-size: 1.5rem;"><i class="fas fa-play-circle text-primary"></i> Project Video Tour & Walkthrough</h2>
              <?php if (!empty($p['video_url'])): ?>
              <a href="<?= e(str_starts_with($p['video_url'], 'http') ? $p['video_url'] : $embedVideoUrl) ?>" target="_blank" class="btn btn-sm btn-outline-danger px-3 py-2 rounded-pill fw-600">
                  <i class="fab fa-youtube me-1"></i> Watch on YouTube
              </a>
              <?php endif; ?>
          </div>
          <div class="row g-4 mt-1">
              
              <!-- Video / Sample Tour -->
              <?php if ($embedVideoUrl): ?>
              <div class="<?= (!empty($embedTourUrl) || count($floorPlanImages) > 0) ? 'col-lg-6' : 'col-12' ?>">
                  <div class="vt-box p-3 bg-white rounded-3 border shadow-sm">
                      <div class="vt-title fw-bold text-dark mb-3"><i class="fas fa-video text-primary me-2"></i> Project Video Tour</div>
                      <div class="vt-img-wrap rounded-3 overflow-hidden shadow-sm" style="aspect-ratio: 16/9; background:#000;">
                          <iframe src="<?= e($embedVideoUrl) ?>" title="<?= e($p['name']) ?> Video Tour" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="width:100%; height:100%; border:0;"></iframe>
                      </div>
                  </div>
              </div>
              <?php endif; ?>

              <!-- Drone / Virtual Tour -->
              <?php if ($embedTourUrl): ?>
              <div class="<?= (!empty($embedVideoUrl) || count($floorPlanImages) > 0) ? 'col-lg-6' : 'col-12' ?>">
                  <div class="vt-box p-3 bg-white rounded-3 border shadow-sm">
                      <div class="vt-title fw-bold text-dark mb-3"><i class="fas fa-vr-cardboard text-primary me-2"></i> 360° Virtual / Drone Tour</div>
                      <div class="vt-img-wrap rounded-3 overflow-hidden shadow-sm" style="aspect-ratio: 16/9; background:#000;">
                          <iframe src="<?= e($embedTourUrl) ?>" title="<?= e($p['name']) ?> Virtual Tour" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="width:100%; height:100%; border:0;"></iframe>
                      </div>
                      <button type="button" class="vt-btn w-100 mt-3" data-bs-toggle="modal" data-bs-target="#enquiryModal">Request Full Virtual Tour</button>
                  </div>
              </div>
              <?php endif; ?>
              
              <!-- Plot Tour -->
              <?php if (isset($floorPlanImages[0])): ?>
              <div class="col-md-6">
                  <div class="vt-box p-3 bg-white rounded-3 border shadow-sm">
                      <div class="vt-title fw-bold text-dark mb-3"><i class="fas fa-map text-primary me-2"></i> Plot / Master Plan</div>
                      <div class="vt-img-wrap rounded-3 overflow-hidden shadow-sm" style="aspect-ratio: 16/9;">
                          <a href="<?= upload($floorPlanImages[0]) ?>" data-lightbox="gallery" data-title="Plot / Master Plan - <?= e($p['name']) ?>" class="fp-item-override">
                              <img src="<?= upload($floorPlanImages[0]) ?>" alt="Plot Tour" style="width:100%; height:100%; object-fit:cover;">
                              <i class="fas fa-search-plus vt-mag-icon"></i>
                          </a>
                      </div>
                      <button type="button" class="vt-btn w-100 mt-3" data-bs-toggle="modal" data-bs-target="#enquiryModal">Request Floor Plan Details</button>
                  </div>
              </div>
              <?php endif; ?>
              
              <!-- Master Layout -->
              <?php if (isset($floorPlanImages[1])): ?>
              <div class="col-md-6">
                  <div class="vt-box p-3 bg-white rounded-3 border shadow-sm">
                      <div class="vt-title fw-bold text-dark mb-3"><i class="fas fa-layer-group text-primary me-2"></i> Master Layout</div>
                      <div class="vt-img-wrap rounded-3 overflow-hidden shadow-sm" style="aspect-ratio: 16/9;">
                          <a href="<?= upload($floorPlanImages[1]) ?>" data-lightbox="gallery" data-title="Master Layout - <?= e($p['name']) ?>" class="fp-item-override">
                              <img src="<?= upload($floorPlanImages[1]) ?>" alt="Master Layout" style="width:100%; height:100%; object-fit:cover;">
                              <i class="fas fa-search-plus vt-mag-icon"></i>
                          </a>
                      </div>
                      <button type="button" class="vt-btn w-100 mt-3" data-bs-toggle="modal" data-bs-target="#enquiryModal">Request Master Plan PDF</button>
                  </div>
              </div>
              <?php endif; ?>

          </div>
        </div>
        <?php endif; ?>

        <!-- Location & Interactive Google Map -->
        <?php 
        $hasCoords = !empty($p['latitude']) && !empty($p['longitude']);
        $hasMapUrl = !empty($p['map_url']);
        $hasAddress = !empty($p['address']) || !empty($p['location_area']);
        
        if ($hasCoords || $hasMapUrl || $hasAddress):
            $mapEmbedSrc = '';
            $directMapUrl = '';
            $rawIframe = '';
            
            if ($hasMapUrl && strpos($p['map_url'], '<iframe') !== false) {
                // Raw iframe embed code provided in map_url
                $rawIframe = $p['map_url'];
            } else {
                if ($hasCoords) {
                    $lat = trim($p['latitude']);
                    $lng = trim($p['longitude']);
                    $mapEmbedSrc = "https://maps.google.com/maps?q={$lat},{$lng}&hl=en&z=15&output=embed";
                    $directMapUrl = "https://www.google.com/maps/search/?api=1&query={$lat},{$lng}";
                } elseif ($hasMapUrl) {
                    $cleanUrl = trim($p['map_url']);
                    if (strpos($cleanUrl, 'google.com/maps/embed') !== false) {
                        $mapEmbedSrc = $cleanUrl;
                        $directMapUrl = str_replace('/embed', '', $cleanUrl);
                    } else {
                        $mapEmbedSrc = "https://maps.google.com/maps?q=" . urlencode($cleanUrl) . "&hl=en&z=15&output=embed";
                        $directMapUrl = $cleanUrl;
                    }
                } else {
                    $queryLocation = trim(($p['address'] ? $p['address'] . ', ' : '') . ($p['location_area'] ? $p['location_area'] . ', ' : '') . ($p['city_name'] ?? ''));
                    $mapEmbedSrc = "https://maps.google.com/maps?q=" . urlencode($queryLocation) . "&hl=en&z=14&output=embed";
                    $directMapUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($queryLocation);
                }
            }
        ?>
        <div class="lux-section glass-panel">
          <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
              <h2 class="lux-section-title mb-0"><i class="fas fa-map-marked-alt text-primary"></i> Project Location & Map</h2>
              <?php if (!empty($directMapUrl)): ?>
              <a href="<?= e($directMapUrl) ?>" target="_blank" class="btn btn-sm text-white px-3 py-2 rounded-pill shadow-sm" style="background: var(--pr-primary); font-weight: 600;">
                  <i class="fas fa-directions me-1"></i> Get Directions on Google Maps
              </a>
              <?php endif; ?>
          </div>

          <?php if (!empty($p['address']) || !empty($p['location_area'])): ?>
          <div class="p-3 mb-4 rounded-3 bg-light border d-flex align-items-start gap-3">
              <div class="rounded-circle p-2 bg-white border text-primary d-flex align-items-center justify-content-center" style="width:42px; height:42px; min-width:42px;">
                  <i class="fas fa-map-pin fs-5" style="color:var(--pr-primary);"></i>
              </div>
              <div>
                  <div class="fw-bold text-dark" style="font-size:1.05rem;">
                      <?= e(!empty($p['address']) ? $p['address'] : $p['location_area']) ?>
                  </div>
                  <div class="text-muted small">
                      <?= e($p['location_area'] ?? '') ?><?= (!empty($p['location_area']) && !empty($p['city_name'])) ? ', ' : '' ?><?= e($p['city_name'] ?? '') ?><?= !empty($p['state_name']) ? ', ' . e($p['state_name']) : '' ?>
                  </div>
                  <?php if ($hasCoords): ?>
                  <div class="mt-2">
                      <span class="badge bg-white text-secondary border small">
                          <i class="fas fa-crosshairs me-1 text-primary"></i> Lat: <?= e($p['latitude']) ?>, Long: <?= e($p['longitude']) ?>
                      </span>
                  </div>
                  <?php endif; ?>
              </div>
          </div>
          <?php endif; ?>

          <div class="map-embed-wrapper rounded-4 overflow-hidden shadow-sm border position-relative" style="height: 400px; background: #eaeaea;">
              <?php if (!empty($rawIframe)): ?>
                  <div class="w-100 h-100 ratio ratio-16x9">
                      <?= $rawIframe ?>
                  </div>
              <?php elseif (!empty($mapEmbedSrc)): ?>
                  <iframe 
                      src="<?= e($mapEmbedSrc) ?>" 
                      width="100%" 
                      height="100%" 
                      style="border:0; min-height:400px;" 
                      allowfullscreen="" 
                      loading="lazy" 
                      referrerpolicy="no-referrer-when-downgrade">
                  </iframe>
              <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- EMI Calculator (Custom JS UI) -->
        <div class="lux-section glass-panel">
          <h2 class="lux-section-title"><i class="fas fa-calculator"></i> EMI Calculator</h2>
          <div class="row align-items-center">
              <div class="col-md-7">
                  <div class="emi-calc-box">
                      <div class="mb-3">
                          <label class="fw-bold mb-2 text-muted">Loan Amount (₹)</label>
                          <?php $basePrice = $p['price_min'] ? $p['price_min'] : 5000000; ?>
                          <input type="range" class="form-range" id="emiLoanRange" min="1000000" max="100000000" step="500000" value="<?= $basePrice * 0.8 ?>">
                          <div class="fw-800 text-dark" style="font-size:1.5rem;" id="emiLoanVal">₹<?= number_format($basePrice * 0.8) ?></div>
                      </div>
                      <div class="mb-3">
                          <label class="fw-bold mb-2 text-muted">Interest Rate (%)</label>
                          <input type="range" class="form-range" id="emiRateRange" min="5" max="15" step="0.1" value="8.5">
                          <div class="fw-800 text-dark" style="font-size:1.5rem;" id="emiRateVal">8.5%</div>
                      </div>
                      <div class="mb-3">
                          <label class="fw-bold mb-2 text-muted">Loan Tenure (Years)</label>
                          <input type="range" class="form-range" id="emiTenureRange" min="1" max="30" step="1" value="20">
                          <div class="fw-800 text-dark" style="font-size:1.5rem;" id="emiTenureVal">20 Years</div>
                      </div>
                  </div>
              </div>
              <div class="col-md-5 mt-4 mt-md-0">
                  <div class="emi-result">
                      <div class="text-white-50 fw-bold mb-2 text-uppercase">Your Monthly EMI</div>
                      <div class="fw-900" style="font-size:3rem; font-family:'Outfit';" id="emiResultVal">₹0</div>
                      <div class="mt-3 text-white-50" style="font-size:0.9rem;">*Estimated figures. Actual bank rates may vary.</div>
                  </div>
              </div>
          </div>
        </div>

      </div>

      <!-- ── RIGHT SIDEBAR (Sticky Glassmorphic) ── -->
      <div class="col-lg-4">
        <div class="sticky-enquiry-wrapper">
          <div class="glass-sidebar glass-panel text-center">
            <h3 class="fw-800 mb-2">Interested?</h3>
            <p class="text-muted small mb-4">Request pricing details, a digital brochure, or schedule a priority site visit.</p>

            <div class="price-display">
                <?= View::priceRange($p['price_min'], $p['price_max'], (bool)$p['price_on_request'], $p['price_display'] ?? '') ?>
            </div>

            <button type="button" class="btn w-100 py-3 mb-3 fw-bold shadow-lg text-white" style="border-radius:12px; font-size:1.1rem; background: var(--pr-primary); border:none;" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                Enquire Now
            </button>

            <div class="d-grid gap-3 mt-4">
              <a href="https://wa.me/<?= e(str_replace(['+',' '],'',$wa)) ?>?text=<?= urlencode("Hi, I'm interested in {$p['name']}. Please share details.") ?>"
                 target="_blank" class="btn py-3 fw-bold text-white shadow-sm" style="background: #25D366; border-radius:12px;">
                <i class="fab fa-whatsapp me-2"></i> Chat on WhatsApp
              </a>
              <?php if ($p['brochure_pdf']): ?>
              <a href="<?= upload($p['brochure_pdf']) ?>" target="_blank" class="btn btn-outline-dark py-3 fw-bold shadow-sm" style="border-radius:12px;">
                <i class="fas fa-download me-2"></i> Download Brochure
              </a>
              <?php endif; ?>
            </div>
            
            <!-- RERA Block Centered -->
            <?php if (!empty($p['builder_name']) || !empty($p['rera_id']) || !empty($p['rera_qr_code'])): ?>
            <div class="text-center mt-5 pt-4 border-top" style="background:#fdfcf9; border-radius:16px; padding:25px 20px; border:1px solid #f0eade;">
                <?php if (!empty($p['builder_logo'])): ?>
                    <img src="<?= upload($p['builder_logo']) ?>" alt="<?= e($p['builder_name']) ?>" style="max-height:80px; max-width:220px; object-fit:contain; margin-bottom:15px; border-radius:8px;">
                <?php elseif (!empty($p['builder_name'])): ?>
                    <h4 class="fw-bold mb-3" style="color:var(--pr-primary); text-transform:uppercase; letter-spacing:2px;"><?= e($p['builder_name']) ?></h4>
                <?php endif; ?>
                
                <p class="mb-3 text-dark fw-bold" style="font-size:1.15rem;">This project is RERA registered.</p>
                
                <?php
                $reraLink = 'https://maharera.mahaonline.gov.in/';
                $stateName = $p['state_name'] ?? '';
                if (stripos($stateName, 'Haryana') !== false) {
                    $reraLink = 'https://haryanarera.gov.in/';
                } elseif (stripos($stateName, 'Uttar Pradesh') !== false || stripos($stateName, 'UP') !== false) {
                    $reraLink = 'https://www.up-rera.in/verify';
                } elseif (stripos($stateName, 'Karnataka') !== false) {
                    $reraLink = 'https://rera.karnataka.gov.in/';
                } elseif (stripos($stateName, 'Delhi') !== false) {
                    $reraLink = 'https://rera.delhi.gov.in/';
                } elseif (stripos($stateName, 'Gujarat') !== false) {
                    $reraLink = 'https://gujrera.gujarat.gov.in/';
                } elseif (stripos($stateName, 'Rajasthan') !== false) {
                    $reraLink = 'https://rera.rajasthan.gov.in/';
                } elseif (stripos($stateName, 'Tamil Nadu') !== false) {
                    $reraLink = 'https://www.rera.tn.gov.in/';
                } elseif (stripos($stateName, 'Telangana') !== false) {
                    $reraLink = 'https://rerait.telangana.gov.in/';
                }

                // Determine QR Code: custom uploaded image OR dynamically auto-generated from RERA ID + portal
                $qrCodeUrl = '';
                if (!empty($p['rera_qr_code'])) {
                    $qrCodeUrl = upload($p['rera_qr_code']);
                } elseif (!empty($p['rera_id'])) {
                    $qrData = $reraLink . ' (RERA Reg: ' . $p['rera_id'] . ')';
                    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qrData) . '&margin=6';
                }
                ?>
                
                <?php if ($qrCodeUrl): ?>
                <div class="mb-3 d-inline-block p-2 bg-white rounded-3 shadow-sm border cursor-pointer" data-lightbox="gallery" data-src="<?= e($qrCodeUrl) ?>" data-title="RERA QR Code - <?= e($p['name']) ?>">
                    <img src="<?= e($qrCodeUrl) ?>" alt="RERA QR Code" style="width:160px; height:160px; object-fit:contain; display:block;">
                </div>
                <?php endif; ?>
                
                <p class="mb-1 text-muted" style="font-size:0.9rem;">RERA Website:</p>
                <div class="mb-2">
                    <a href="<?= e($reraLink) ?>" target="_blank" class="fw-bold text-decoration-none d-inline-block px-3 py-1 rounded" style="color:var(--pr-primary); word-break:break-all; font-size:1rem; background:rgba(229,175,83,0.12); border:1px solid rgba(229,175,83,0.35);"><?= e($reraLink) ?></a>
                </div>
                
                <?php if (!empty($p['rera_id'])): ?>
                <p class="mt-2 mb-0 text-dark fw-bold" style="font-size:1.05rem;">
                    <strong>Reg:</strong> <?= e($p['rera_id']) ?>
                </p>
                <?php endif; ?>
                
                <p class="mt-4 text-muted" style="font-size:0.75rem; line-height:1.5;">
                    The content presented on this website is solely for informational purposes and does not constitute a service offer.
                    <span id="reraDisclaimerMore" style="display:none;">
                        All project information, dimensions, specifications, and images shown are subject to change without notice. PropertyRubix is an independent real estate discovery platform. Prospective buyers are advised to verify all details directly with the builder or authorized RERA registration authority before taking any purchasing decision.
                    </span>
                    <a id="reraDisclaimerToggle" class="cursor-pointer fw-bold ms-1" style="color:var(--pr-primary);" onclick="var m=document.getElementById('reraDisclaimerMore'); if(m.style.display==='none'){m.style.display='inline'; this.textContent='read less';}else{m.style.display='none'; this.textContent='read more';}">read more</a>
                </p>
            </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Similar Projects / Recent Projects -->
<?php if (!empty($related)): ?>
<div class="section py-5" style="background:#fff;">
    <div class="container-fluid px-3 px-md-5">
        <h2 class="fw-bold mb-4" style="font-family:'Outfit';">Similar Projects in <?= e($p['city_name']) ?></h2>
        <div class="row g-4">
            <?php foreach ($related as $rProj): 
                $rImg = '';
                if (!empty($rProj['thumbnail_image'])) {
                    $rImg = upload($rProj['thumbnail_image']);
                } elseif (!empty($rProj['banner_image'])) {
                    $rImg = upload($rProj['banner_image']);
                } elseif (!empty($rProj['banner_images'])) {
                    $bArr = json_decode($rProj['banner_images'], true);
                    if (!empty($bArr[0])) $rImg = upload($bArr[0]);
                } elseif (!empty($rProj['exterior_images'])) {
                    $eArr = json_decode($rProj['exterior_images'], true);
                    if (!empty($eArr[0])) $rImg = upload($eArr[0]);
                } elseif (!empty($rProj['gallery_images'])) {
                    $gArr = json_decode($rProj['gallery_images'], true);
                    if (!empty($gArr[0])) $rImg = upload($gArr[0]);
                }
                if (empty($rImg)) {
                    $rImg = 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=600&q=80';
                }
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm" style="border-radius:16px; overflow:hidden; transition:all 0.3s ease; border:1px solid #f1f5f9;" onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 30px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                    <a href="<?= PUBLIC_URL ?>project/<?= e($rProj['slug']) ?>" style="display:block; overflow:hidden;">
                        <img src="<?= e($rImg) ?>" class="card-img-top" alt="<?= e($rProj['name']) ?>" style="height:220px; width:100%; object-fit:cover; transition:transform 0.5s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    </a>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="text-muted small mb-2"><i class="fas fa-map-marker-alt" style="color:var(--pr-primary);"></i> <?= e($rProj['location_area'] ?: $p['city_name']) ?></div>
                        <h5 class="card-title fw-bold mb-1"><a href="<?= PUBLIC_URL ?>project/<?= e($rProj['slug']) ?>" class="text-dark text-decoration-none"><?= e($rProj['name']) ?></a></h5>
                        <p class="card-text text-muted mb-3" style="font-size:0.88rem;"><?= e($rProj['builder_name'] ?? '') ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                            <div class="fw-bold fs-5 text-dark"><?= View::priceRange($rProj['price_min'], $rProj['price_max'], (bool)$rProj['price_on_request'], $rProj['price_display'] ?? '') ?></div>
                            <a href="<?= PUBLIC_URL ?>project/<?= e($rProj['slug']) ?>" class="btn btn-sm rounded-pill px-3 text-white fw-600" style="background:var(--pr-primary);">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Auto-Open Enquiry Modal -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content glass-panel" style="border-radius:24px; border:none; box-shadow:0 30px 60px rgba(0,0,0,0.2);">
      <div class="modal-header border-0 pb-0 flex-column justify-content-center position-relative pt-4">
        <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Close" style="right:20px; top:20px;"></button>
        <?php if ($p['project_logo']): ?>
            <img src="<?= upload($p['project_logo']) ?>" alt="Logo" style="max-height:100px; object-fit:contain; margin-bottom:15px;">
        <?php elseif ($p['builder_logo']): ?>
            <img src="<?= upload($p['builder_logo']) ?>" alt="Logo" style="max-height:100px; object-fit:contain; margin-bottom:15px;">
        <?php endif; ?>
        <h4 class="modal-title fw-900" id="enquiryModalLabel">Enquire Now</h4>
      </div>
      <div class="modal-body p-4 p-md-5">
        <p class="text-center text-muted mb-4">Leave your details and our property experts will contact you immediately regarding <strong><?= e($p['name']) ?></strong>.</p>
        <form id="projectEnquiryForm" novalidate>
          <?= csrfField() ?>
          <input type="text" name="hp_name" style="display:none" tabindex="-1">
          <input type="hidden" name="form_type" value="enquiry">
          <input type="hidden" name="project_name" value="<?= e($p['name']) ?>">
          <div class="mb-3">
            <input type="text"  class="form-control form-control-lg shadow-none" name="name"  placeholder="Full Name" required style="border-radius:12px; background:#f9f9f9; border:1px solid #eaeaea;">
          </div>
          <div class="mb-3">
            <input type="tel"   class="form-control form-control-lg shadow-none" name="phone" placeholder="Phone Number" required style="border-radius:12px; background:#f9f9f9; border:1px solid #eaeaea;">
          </div>
          <div class="mb-4">
            <input type="email" class="form-control form-control-lg shadow-none" name="email" placeholder="Email Address" style="border-radius:12px; background:#f9f9f9; border:1px solid #eaeaea;">
          </div>
          <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-lg text-white" style="border-radius:12px; font-size:1.1rem; background: var(--pr-primary); border:none;">
            Submit Enquiry
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
$ajaxUrl = PUBLIC_URL . 'ajax/submit-enquiry';
ob_start();
?>
<script>
// Auto-Open Modal on Page Load
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        var myModal = new bootstrap.Modal(document.getElementById('enquiryModal'));
        myModal.show();
    }, 1000); // Wait 1 second before popping up for better UX

    // Hero Gallery Swiper
    if(typeof Swiper !== 'undefined') {
        new Swiper(".hero-swiper", {
            loop: true, autoplay: { delay: 4500, disableOnInteraction: false },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" }
        });
    }

    // EMI Calculator Logic
    const loanRange = document.getElementById('emiLoanRange');
    const rateRange = document.getElementById('emiRateRange');
    const tenureRange = document.getElementById('emiTenureRange');
    const loanVal = document.getElementById('emiLoanVal');
    const rateVal = document.getElementById('emiRateVal');
    const tenureVal = document.getElementById('emiTenureVal');
    const resultVal = document.getElementById('emiResultVal');

    function calculateEMI() {
        if(!loanRange) return;
        const p = parseFloat(loanRange.value);
        const r = parseFloat(rateRange.value) / 12 / 100;
        const n = parseFloat(tenureRange.value) * 12;
        
        loanVal.textContent = '₹' + p.toLocaleString('en-IN');
        rateVal.textContent = rateRange.value + '%';
        tenureVal.textContent = tenureRange.value + ' Years';

        let emi = 0;
        if(r > 0) {
            emi = p * r * (Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        } else {
            emi = p / n;
        }
        resultVal.textContent = '₹' + Math.round(emi).toLocaleString('en-IN');
    }
    
    if(loanRange) {
        loanRange.addEventListener('input', calculateEMI);
        rateRange.addEventListener('input', calculateEMI);
        tenureRange.addEventListener('input', calculateEMI);
        calculateEMI();
    }
});

// Project Enquiry AJAX
document.getElementById("projectEnquiryForm")?.addEventListener("submit", async function(e) {
  e.preventDefault();
  if (!this.checkValidity()) { this.classList.add("was-validated"); return; }
  const btn = this.querySelector("button[type=submit]");
  btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Sending…';
  const data = new FormData(this);
  try {
    const res  = await fetch("<?= $ajaxUrl ?>", { method:"POST", body:data, headers:{"X-Requested-With":"XMLHttpRequest"} });
    const json = await res.json();
    showToast(json.message, json.success ? "success" : "error");
    if (json.success) { 
        this.reset(); this.classList.remove("was-validated"); 
        setTimeout(() => {
            const modalEl = document.getElementById('enquiryModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if(modal) modal.hide();
        }, 1500);
    }
  } catch(e) { 
    showToast("Failed to send. Please try again.", "error"); 
  }
  finally { btn.disabled = false; btn.innerHTML = 'Submit Enquiry'; }
});

// Interactive Lightbox Controller
(function() {
    let lightboxItems = [];
    let currentIndex = 0;

    const modal = document.createElement('div');
    modal.className = 'pr-lightbox-modal';
    modal.id = 'prLightboxModal';
    modal.innerHTML = `
        <div class="pr-lightbox-toolbar">
            <div class="pr-lightbox-counter" id="prLightboxCounter">1 / 1</div>
            <div class="pr-lightbox-actions">
                <a href="#" id="prLightboxDownload" target="_blank" download class="pr-lightbox-btn" title="Download Image"><i class="fas fa-download"></i></a>
                <button type="button" class="pr-lightbox-btn" id="prLightboxClose" title="Close (Esc)"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <button type="button" class="pr-lightbox-nav pr-lightbox-prev" id="prLightboxPrev" title="Previous (Left Arrow)"><i class="fas fa-chevron-left"></i></button>
        <div class="pr-lightbox-body">
            <img src="" alt="" class="pr-lightbox-img" id="prLightboxImg">
        </div>
        <button type="button" class="pr-lightbox-nav pr-lightbox-next" id="prLightboxNext" title="Next (Right Arrow)"><i class="fas fa-chevron-right"></i></button>
        <div class="pr-lightbox-caption" id="prLightboxCaption"></div>
    `;
    document.body.appendChild(modal);

    const imgEl = modal.querySelector('#prLightboxImg');
    const counterEl = modal.querySelector('#prLightboxCounter');
    const captionEl = modal.querySelector('#prLightboxCaption');
    const downloadEl = modal.querySelector('#prLightboxDownload');

    function openLightbox(index) {
        if (lightboxItems.length === 0) return;
        currentIndex = (index + lightboxItems.length) % lightboxItems.length;
        updateLightbox();
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    function updateLightbox() {
        const item = lightboxItems[currentIndex];
        if (!item) return;

        imgEl.style.opacity = '0';
        imgEl.style.transform = 'scale(0.95)';

        setTimeout(() => {
            imgEl.src = item.src;
            imgEl.alt = item.title || 'Image View';
            counterEl.textContent = `${currentIndex + 1} / ${lightboxItems.length}`;
            captionEl.textContent = item.title || '';
            captionEl.style.display = item.title ? 'block' : 'none';
            downloadEl.href = item.src;

            imgEl.onload = () => {
                imgEl.style.opacity = '1';
                imgEl.style.transform = 'scale(1)';
            };
            imgEl.style.opacity = '1';
            imgEl.style.transform = 'scale(1)';
        }, 120);
    }

    function initLightboxTriggers() {
        lightboxItems = [];
        const triggers = Array.from(document.querySelectorAll('[data-lightbox="gallery"]'));

        triggers.forEach((el) => {
            const src = el.getAttribute('data-src') || el.getAttribute('href') || el.querySelector('img')?.src;
            const title = el.getAttribute('data-title') || el.getAttribute('title') || el.querySelector('img')?.alt || '';

            if (src && !src.startsWith('#') && !src.includes('javascript:')) {
                const itemIndex = lightboxItems.length;
                lightboxItems.push({ src, title });

                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openLightbox(itemIndex);
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initLightboxTriggers();

        document.getElementById('prLightboxClose')?.addEventListener('click', closeLightbox);
        document.getElementById('prLightboxPrev')?.addEventListener('click', () => openLightbox(currentIndex - 1));
        document.getElementById('prLightboxNext')?.addEventListener('click', () => openLightbox(currentIndex + 1));

        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.classList.contains('pr-lightbox-body')) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (!modal.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') openLightbox(currentIndex - 1);
            if (e.key === 'ArrowRight') openLightbox(currentIndex + 1);
        });

        // Touch Swipe on mobile
        let touchStartX = 0;
        modal.addEventListener('touchstart', (e) => { touchStartX = e.changedTouches[0].screenX; }, {passive: true});
        modal.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 50) openLightbox(currentIndex + 1);
            if (touchEndX - touchStartX > 50) openLightbox(currentIndex - 1);
        }, {passive: true});
    });
})();
</script>
<?php
$extraScripts = ob_get_clean();
?>
