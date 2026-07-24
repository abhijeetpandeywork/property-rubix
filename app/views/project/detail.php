<?php
/**
 * Advanced Luxury Project Detail View
 */
$p = $project;
// Use project specific phone/wa if available, else fallback to global
$phone = $p['contact_phone'] ?: getSetting('phone_primary', '+91 98765 43210');
$wa    = $p['whatsapp_number'] ?: getSetting('whatsapp_number', '919876543210');

// Parse JSON arrays
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

/* --- Cinematic Hero --- */
.luxury-hero-gallery {
    position: relative;
    width: 100%;
    height: 90vh;
    min-height: 600px;
    background: #000;
    overflow: hidden;
}
.hero-swiper { width: 100%; height: 100%; }
.hero-swiper .swiper-slide img {
    width: 100%; height: 100%; object-fit: cover; opacity: 1; transition: transform 10s ease;
}
.hero-swiper .swiper-slide-active img { transform: scale(1.05); }

.luxury-hero-gallery::after {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(180deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.2) 40%, rgba(0,0,0,0.9) 100%);
    z-index: 1; pointer-events: none;
}

.hero-content {
    position: absolute; bottom: 0; left: 0; width: 100%; z-index: 2;
    padding-bottom: 60px; color: white;
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
    font-size: 1.25rem; font-weight: 500; opacity: 0.9; display: flex; align-items: center; gap: 10px;
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
    color: #5e452a; background: #fff; border: 1px solid #d4af37; border-radius: 4px;
    padding: 10px 20px; font-size: 1rem; margin-right: 10px; font-weight: 600;
}
.conn-tabs .nav-link.active {
    color: #fff; background: #a67b45; border-color: #a67b45;
}
.conn-list { list-style: none; padding: 0; margin: 0; }
.conn-list li {
    padding: 15px 0; border-bottom: 1px dashed #e2e8f0; display: flex; flex-direction: row; align-items: center; gap: 15px;
}
.conn-list li:last-child { border-bottom: none; }
.conn-list li .pin-icon { font-size: 2.2rem; color: #111; }
.conn-list li .conn-name { font-size: 1.1rem; color: #334155; margin-bottom: 0; font-weight: 500; }
.conn-list li .conn-dist { font-size: 1.1rem; color: #64748b; margin-left: auto; }

/* --- Virtual Tour Grid Styles --- */
.vt-btn { background: #4a3424; color: #fff; border: none; padding: 8px 24px; border-radius: 4px; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; margin-top: 15px; display: inline-block; transition: 0.3s; }
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
    background: #fff; border: 1px solid rgba(0,0,0,0.05); border-radius: 24px; padding: 35px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.05);
}
.price-display { font-size: 2.25rem; font-weight: 900; color: #0f172a; margin-bottom: 25px; font-family: 'Outfit', sans-serif; }
</style>

<!-- 2. Custom Project Header (Image 2) -->
<header class="custom-proj-header">
    <div class="container-fluid px-3 px-md-5">
        <div class="cph-logo d-flex align-items-center gap-3">
            <?php if ($p['project_logo']): ?>
                <a href="<?= PUBLIC_URL ?>"><img src="<?= upload($p['project_logo']) ?>" alt="<?= e($p['name']) ?>" style="height:70px; width:auto; object-fit:contain;"></a>
            <?php endif; ?>
            <?php if ($p['builder_logo']): ?>
                <a href="<?= PUBLIC_URL ?>"><img src="<?= upload($p['builder_logo']) ?>" alt="<?= e($p['builder_name']) ?>" style="<?= $p['project_logo'] ? 'height:55px; border-left:2px solid #ddd; padding-left:15px;' : 'height:70px;' ?> width:auto; object-fit:contain;"></a>
            <?php elseif (!$p['project_logo']): ?>
                <a href="<?= PUBLIC_URL ?>" class="text-dark fw-bold text-decoration-none fs-4"><?= e($p['builder_name']) ?></a>
            <?php endif; ?>
        </div>
        <div class="cph-actions d-none d-md-flex">
            <a href="tel:<?= e(str_replace(' ','',$phone)) ?>" class="cph-btn">
                <i class="fas fa-phone-alt" style="color:#b08d55;"></i> <?= e($phone) ?>
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
      <?php if (!empty($exteriorImages)): ?>
          <?php foreach ($exteriorImages as $img): ?>
          <div class="swiper-slide"><img src="<?= upload($img) ?>" alt="<?= e($p['name']) ?>"></div>
          <?php endforeach; ?>
      <?php elseif (!empty($galleryImages)): ?>
          <?php foreach ($galleryImages as $img): ?>
          <div class="swiper-slide"><img src="<?= upload($img) ?>" alt="<?= e($p['name']) ?>"></div>
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

      <span class="luxury-badge"><?= e(str_replace('_', ' ', ucfirst($p['status']))) ?></span>
      <span class="luxury-badge" style="background: var(--pr-primary); color: #111; border:none;"><?= e(ucfirst($p['type'])) ?></span>
      
      <h1 class="luxury-title"><?= e($p['name']) ?></h1>
      <div class="luxury-location">
        <i class="fas fa-map-marker-alt" style="color:var(--pr-primary);"></i> 
        <?= e($p['location_area']) ? e($p['location_area']) . ', ' : '' ?><?= e($p['city_name']) ?>
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
            <div class="bento-box">
                <i class="fas fa-bed bento-icon"></i>
                <div class="bento-label">Configurations</div>
                <div class="bento-value"><?= e($p['unit_types']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($p['area_range']): ?>
            <div class="bento-box">
                <i class="fas fa-expand-arrows-alt bento-icon"></i>
                <div class="bento-label">Carpet Area</div>
                <div class="bento-value"><?= e($p['area_range']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($p['total_area']): ?>
            <div class="bento-box">
                <i class="fas fa-vector-square bento-icon"></i>
                <div class="bento-label">Project Area</div>
                <div class="bento-value"><?= e($p['total_area']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($p['possession_date']): ?>
            <div class="bento-box">
                <i class="fas fa-calendar-check bento-icon"></i>
                <div class="bento-label">Possession</div>
                <div class="bento-value"><?= e($p['possession_date']) ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Plain Text Description -->
        <?php if ($p['description'] || $p['short_description']): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-info-circle"></i> About <?= e($p['name']) ?></h2>
          <div class="lux-description"><?= e($p['description'] ?: $p['short_description']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Project Highlights -->
        <?php if ($p['highlights']): ?>
        <div class="lux-section">
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
        <div class="lux-section">
          <div class="d-flex justify-content-between align-items-center mb-4">
              <h2 class="lux-section-title mb-0" style="font-size: 1.5rem;"><i class="fas fa-route"></i> Connectivity</h2>
              <button class="btn btn-sm text-white" style="background: #b08d55; font-weight: 600;"><i class="fas fa-download me-1"></i> Download Connectivity</button>
          </div>
          
          <ul class="nav nav-pills conn-tabs mb-4 flex-nowrap overflow-auto" id="connTabs" role="tablist" style="padding-bottom: 10px; border-bottom: 5px solid #888;">
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
        <div class="lux-section">
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
            <div class="lux-amenity-card">
              <div class="lux-amenity-icon-box"><i class="fas <?= $icon ?>"></i></div>
              <div class="fw-bold text-dark" style="font-size:1.1rem;"><?= e($am) ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Visual Galleries (Tabs for Interior/Exterior/Legacy) -->
        <?php if (!empty($interiorImages) || !empty($exteriorImages) || !empty($galleryImages)): ?>
        <div class="lux-section">
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
                    <div class="fp-item"><a href="<?= upload($img) ?>" target="_blank"><img src="<?= upload($img) ?>" alt="Exterior"><div class="fp-overlay"><i class="fas fa-search-plus fa-2x text-white"></i></div></a></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($interiorImages)): ?>
            <div class="tab-pane fade <?= empty($exteriorImages) ? 'show active' : '' ?>" id="tab-int" role="tabpanel">
                <div class="fp-grid">
                    <?php foreach ($interiorImages as $img): ?>
                    <div class="fp-item"><a href="<?= upload($img) ?>" target="_blank"><img src="<?= upload($img) ?>" alt="Interior"><div class="fp-overlay"><i class="fas fa-search-plus fa-2x text-white"></i></div></a></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($galleryImages) && empty($exteriorImages) && empty($interiorImages)): ?>
            <div class="tab-pane fade show active" id="tab-gal" role="tabpanel">
                <div class="fp-grid">
                    <?php foreach ($galleryImages as $img): ?>
                    <div class="fp-item"><a href="<?= upload($img) ?>" target="_blank"><img src="<?= upload($img) ?>" alt="Gallery"><div class="fp-overlay"><i class="fas fa-search-plus fa-2x text-white"></i></div></a></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Custom 2x2 Virtual Tour Grid (Image 3 mapping) -->
        <?php if ($p['video_url'] || $p['virtual_tour_url'] || count($floorPlanImages) > 0): ?>
        <div class="lux-section" style="background:#f4f4f4; border:none; padding:40px 30px;">
          <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
              <h2 class="mb-0" style="font-size: 1.2rem; font-weight: 500; color: #111;">Virtual Tour</h2>
              <button class="btn btn-sm text-white" style="background: #4a3424; font-weight: 600;"><i class="fas fa-download me-1"></i> View Video</button>
          </div>
          <div class="row g-4 mt-2">
              
              <!-- Sample Tour -->
              <?php if($p['video_url']): ?>
              <div class="col-md-6">
                  <div class="vt-box">
                      <div class="vt-title">Sample Tour</div>
                      <div class="vt-img-wrap">
                          <iframe src="<?= e($p['video_url']) ?>"></iframe>
                      </div>
                      <button type="button" class="vt-btn" data-bs-toggle="modal" data-bs-target="#enquiryModal">Request for video</button>
                  </div>
              </div>
              <?php endif; ?>

              <!-- Drone Tour -->
              <?php if($p['virtual_tour_url']): ?>
              <div class="col-md-6">
                  <div class="vt-box">
                      <div class="vt-title">Drone Tour</div>
                      <div class="vt-img-wrap">
                          <?php if(strpos($p['virtual_tour_url'], '<iframe') !== false): ?>
                              <?= $p['virtual_tour_url'] ?>
                          <?php else: ?>
                              <iframe src="<?= e($p['virtual_tour_url']) ?>"></iframe>
                          <?php endif; ?>
                      </div>
                      <button type="button" class="vt-btn" data-bs-toggle="modal" data-bs-target="#enquiryModal">Request for video</button>
                  </div>
              </div>
              <?php endif; ?>
              
              <!-- Plot Tour -->
              <?php if(isset($floorPlanImages[0])): ?>
              <div class="col-md-6 mt-4">
                  <div class="vt-box">
                      <div class="vt-title">Plot Tour</div>
                      <div class="vt-img-wrap">
                          <a href="<?= upload($floorPlanImages[0]) ?>" target="_blank" class="fp-item-override">
                              <img src="<?= upload($floorPlanImages[0]) ?>" alt="Plot Tour">
                              <i class="fas fa-search-plus vt-mag-icon"></i>
                          </a>
                      </div>
                      <button type="button" class="vt-btn" data-bs-toggle="modal" data-bs-target="#enquiryModal">Request for plan</button>
                  </div>
              </div>
              <?php endif; ?>
              
              <!-- Master Layout -->
              <?php if(isset($floorPlanImages[1])): ?>
              <div class="col-md-6 mt-4">
                  <div class="vt-box">
                      <div class="vt-title">Master Layout</div>
                      <div class="vt-img-wrap">
                          <a href="<?= upload($floorPlanImages[1]) ?>" target="_blank" class="fp-item-override">
                              <img src="<?= upload($floorPlanImages[1]) ?>" alt="Master Layout">
                              <i class="fas fa-search-plus vt-mag-icon"></i>
                          </a>
                      </div>
                      <button type="button" class="vt-btn" data-bs-toggle="modal" data-bs-target="#enquiryModal">Request for plan</button>
                  </div>
              </div>
              <?php endif; ?>

          </div>
        </div>
        <?php endif; ?>

        <!-- EMI Calculator (Custom JS UI) -->
        <div class="lux-section">
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
          <div class="glass-sidebar text-center">
            <h3 class="fw-800 mb-2">Interested?</h3>
            <p class="text-muted small mb-4">Request pricing details, a digital brochure, or schedule a priority site visit.</p>

            <div class="price-display">
                <?= View::priceRange($p['price_min'], $p['price_max'], (bool)$p['price_on_request']) ?>
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
            
            <!-- RERA Block Centered (Image 3) -->
            <?php if ($p['builder_name'] || $p['rera_id'] || $p['rera_qr_code']): ?>
            <div class="text-center mt-5 pt-4 border-top" style="background:#fdfcf9; border-radius:16px; padding:20px; border:1px solid #f0eade;">
                <?php if ($p['builder_logo']): ?>
                    <img src="<?= upload($p['builder_logo']) ?>" alt="<?= e($p['builder_name']) ?>" style="max-height:80px; max-width:200px; object-fit:contain; margin-bottom:15px; border-radius:8px;">
                <?php else: ?>
                    <h4 class="fw-bold mb-3" style="color:#b08d55; text-transform:uppercase; letter-spacing:2px;"><?= e($p['builder_name']) ?></h4>
                <?php endif; ?>
                
                <p class="mb-3 text-dark fw-bold" style="font-size:1.1rem;">This project is RERA registered.</p>
                
                <?php if($p['rera_qr_code']): ?>
                <img src="<?= upload($p['rera_qr_code']) ?>" alt="RERA QR" style="width:180px; height:180px; object-fit:cover; border:3px solid #000; border-radius:12px; margin-bottom:15px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
                <?php endif; ?>
                
                <p class="mb-1 text-muted" style="font-size:0.9rem;">RERA Website:</p>
                <a href="https://www.up-rera.in/verify" target="_blank" class="fw-bold text-decoration-none" style="color:#b08d55; word-break:break-all; font-size:1rem;">https://www.up-rera.in/verify</a>
                <?php if($p['rera_id']): ?><p class="mt-2 text-dark small fw-bold"><strong>Reg:</strong> <?= e($p['rera_id']) ?></p><?php endif; ?>
                
                <p class="mt-4 text-muted" style="font-size:0.75rem; line-height:1.5;">The content presented on this website is solely for informational purposes and does not constitute a service offer.... <a class="cursor-pointer" style="color:#b08d55;">read more</a></p>
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
            <?php foreach ($related as $rProj): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm" style="border-radius:12px; overflow:hidden; transition:0.3s;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                    <img src="<?= $rProj['thumbnail_image'] ? upload($rProj['thumbnail_image']) : 'https://placehold.co/600x400' ?>" class="card-img-top" alt="<?= e($rProj['name']) ?>" style="height:200px; object-fit:cover;">
                    <div class="card-body p-4">
                        <div class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-primary"></i> <?= e($rProj['location_area']) ?></div>
                        <h5 class="card-title fw-bold mb-2"><?= e($rProj['name']) ?></h5>
                        <p class="card-text text-muted mb-3" style="font-size:0.9rem;"><?= e($rProj['builder_name']) ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                            <div class="fw-bold fs-5 text-dark"><?= View::priceRange($rProj['price_min'], $rProj['price_max'], (bool)$rProj['price_on_request']) ?></div>
                            <a href="<?= PUBLIC_URL ?>project/<?= e($rProj['slug']) ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3">View Details</a>
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
    <div class="modal-content" style="border-radius:24px; border:none; box-shadow:0 30px 60px rgba(0,0,0,0.2);">
      <div class="modal-header border-0 pb-0 flex-column justify-content-center position-relative pt-4">
        <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Close" style="right:20px; top:20px;"></button>
        <?php if ($p['project_logo']): ?>
            <img src="<?= upload($p['project_logo']) ?>" alt="Logo" style="max-height:60px; object-fit:contain; margin-bottom:15px;">
        <?php elseif ($p['builder_logo']): ?>
            <img src="<?= upload($p['builder_logo']) ?>" alt="Logo" style="max-height:60px; object-fit:contain; margin-bottom:15px;">
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
            loop: true, effect: "fade", autoplay: { delay: 4500, disableOnInteraction: false },
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
</script>
<?php
$extraScripts = ob_get_clean();
?>
