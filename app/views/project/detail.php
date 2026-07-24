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
}

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

/* --- Quick Contact Top Bar --- */
.quick-contact-bar {
    background: rgba(255,255,255,0.95); backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0,0,0,0.05); padding: 15px 0;
    position: sticky; top: 0; z-index: 50; box-shadow: 0 4px 20px rgba(0,0,0,0.03);
}
.qc-btn {
    border-radius: 50px; padding: 8px 25px; font-weight: 700; font-size: 0.95rem;
    transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
}
.qc-btn-wa { background: #25D366; color: white; box-shadow: 0 4px 15px rgba(37,211,102,0.3); }
.qc-btn-wa:hover { background: #1ebd5a; color: white; transform: translateY(-2px); }
.qc-btn-call { background: #111; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
.qc-btn-call:hover { background: var(--pr-primary); color: white; transform: translateY(-2px); }

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

/* --- Connectivity Matrix & Highlights --- */
.highlight-list { list-style: none; padding: 0; margin: 0; }
.highlight-list li {
    padding: 15px 0; border-bottom: 1px dashed #e2e8f0; display: flex; align-items: flex-start; gap: 15px;
    font-size: 1.1rem; color: #334155;
}
.highlight-list li:last-child { border-bottom: none; }
.highlight-list li i { color: var(--pr-primary); margin-top: 5px; }

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

/* --- RERA Block --- */
.rera-block {
    background: #fff; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 25px;
    display: flex; align-items: center; gap: 25px; margin-top: 25px;
}
.rera-qr { width: 100px; height: 100px; border-radius: 12px; object-fit: cover; border: 1px solid #eee; }

/* --- Sticky Sidebar Form --- */
.sticky-enquiry-wrapper { position: sticky; top: 100px; z-index: 10; }
.glass-sidebar {
    background: #fff; border: 1px solid rgba(0,0,0,0.05); border-radius: 24px; padding: 35px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.05);
}
.price-display { font-size: 2.25rem; font-weight: 900; color: #0f172a; margin-bottom: 25px; font-family: 'Outfit', sans-serif; }
</style>

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
      
      <!-- Dual Logos -->
      <div class="dual-logo-container">
          <?php if ($p['builder_logo']): ?>
              <div class="logo-box"><img src="<?= upload($p['builder_logo']) ?>" alt="<?= e($p['builder_name']) ?>"></div>
          <?php endif; ?>
          <?php if ($p['project_logo']): ?>
              <div class="logo-box"><img src="<?= upload($p['project_logo']) ?>" alt="<?= e($p['name']) ?>"></div>
          <?php endif; ?>
      </div>

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

<!-- Quick Contact Bar -->
<div class="quick-contact-bar d-none d-md-block">
    <div class="container-fluid px-3 px-md-5 d-flex justify-content-between align-items-center">
        <div class="fw-800 text-dark" style="font-size:1.25rem;">
            <?= e($p['name']) ?> <span class="fw-normal text-muted" style="font-size:1rem; margin-left:10px;">by <?= e($p['builder_name']) ?></span>
        </div>
        <div class="d-flex gap-3">
            <a href="tel:<?= e(str_replace(' ','',$phone)) ?>" class="qc-btn qc-btn-call"><i class="fas fa-phone-alt"></i> <?= e($phone) ?></a>
            <a href="https://wa.me/<?= e(str_replace(['+',' '],'',$wa)) ?>?text=<?= urlencode("Hi, I'm interested in {$p['name']}.") ?>" target="_blank" class="qc-btn qc-btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
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

        <!-- Connectivity -->
        <?php if ($p['connectivity']): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-route"></i> Location & Connectivity</h2>
          <ul class="highlight-list">
              <?php 
              $clines = explode("\n", $p['connectivity']);
              foreach ($clines as $cl): 
                  if(trim($cl)):
              ?>
              <li><i class="fas fa-map-marker-alt"></i> <span><?= html_entity_decode(e(trim($cl))) ?></span></li>
              <?php endif; endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Premium Amenities Grid -->
        <?php if (!empty($projectAmenities)): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-gem"></i> Premium Lifestyle Amenities</h2>
          <div class="lux-amenities-grid">
            <?php foreach ($projectAmenities as $am): 
                // Very basic icon mapping for visual flair if images aren't used
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

        <!-- Virtual Tour -->
        <?php if ($p['virtual_tour_url']): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-vr-cardboard"></i> 360° Virtual Tour</h2>
          <div class="w-100 rounded" style="overflow:hidden; height: 500px; border-radius: 20px;">
              <?php if(strpos($p['virtual_tour_url'], '<iframe') !== false): ?>
                  <?= $p['virtual_tour_url'] ?>
              <?php else: ?>
                  <iframe src="<?= e($p['virtual_tour_url']) ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
              <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Visual Floor Plans -->
        <?php if (!empty($floorPlanImages)): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-ruler-combined"></i> Master Floor Plans</h2>
          <div class="fp-grid">
            <?php foreach ($floorPlanImages as $fp): ?>
            <div class="fp-item">
              <a href="<?= upload($fp) ?>" target="_blank">
                <img src="<?= upload($fp) ?>" alt="Floor Plan" style="object-fit:contain; background:#f9f9f9;">
                <div class="fp-overlay"><i class="fas fa-search-plus fa-2x text-white"></i></div>
              </a>
            </div>
            <?php endforeach; ?>
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
          <div class="glass-sidebar">
            <h3 class="fw-800 mb-2">Interested?</h3>
            <p class="text-muted small mb-4">Request pricing details, a digital brochure, or schedule a priority site visit.</p>

            <div class="price-display">
                <?= View::priceRange($p['price_min'], $p['price_max'], (bool)$p['price_on_request']) ?>
            </div>

            <form id="projectEnquiryForm" novalidate>
              <?= csrfField() ?>
              <input type="text" name="hp_name" style="display:none" tabindex="-1">
              <input type="hidden" name="form_type" value="enquiry">
              <input type="hidden" name="project_name" value="<?= e($p['name']) ?>">
              <div class="mb-3">
                <input type="text"  class="form-control form-control-lg shadow-none" name="name"  placeholder="Full Name" required style="border-radius:12px; background:#f9f9f9;">
              </div>
              <div class="mb-3">
                <input type="tel"   class="form-control form-control-lg shadow-none" name="phone" placeholder="Phone Number" required style="border-radius:12px; background:#f9f9f9;">
              </div>
              <div class="mb-3">
                <input type="email" class="form-control form-control-lg shadow-none" name="email" placeholder="Email Address" style="border-radius:12px; background:#f9f9f9;">
              </div>
              <button type="submit" class="btn btn-primary w-100 py-3 mb-3 fw-bold shadow-lg" style="border-radius:12px; font-size:1.1rem; background: var(--pr-primary); border:none;">
                Request Information
              </button>
            </form>

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
            
            <!-- RERA Block -->
            <?php if ($p['rera_id'] || $p['rera_qr_code']): ?>
            <div class="rera-block">
                <?php if($p['rera_qr_code']): ?>
                <img src="<?= upload($p['rera_qr_code']) ?>" alt="RERA QR" class="rera-qr">
                <?php endif; ?>
                <div>
                    <h5 class="fw-bold text-dark mb-1"><i class="fas fa-shield-alt text-success me-1"></i> RERA Approved</h5>
                    <?php if($p['rera_id']): ?>
                    <p class="text-muted mb-0 small" style="word-break:break-all;"><strong>Reg:</strong> <?= e($p['rera_id']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Developer Info in Sidebar -->
            <?php if ($p['builder_name']): ?>
            <div class="mt-4 pt-4 border-top">
                <div class="text-muted small text-uppercase fw-bold mb-3">Developed By</div>
                <div class="d-flex align-items-center gap-3">
                    <?php if ($p['builder_logo']): ?>
                    <div style="width: 60px; height:60px; border-radius:12px; background:#fff; border:1px solid #eaeaea; display:flex; align-items:center; justify-content:center; padding:5px;">
                        <img src="<?= upload($p['builder_logo']) ?>" alt="<?= e($p['builder_name']) ?>" style="max-width:100%; max-height:100%; object-fit:contain">
                    </div>
                    <?php endif; ?>
                    <div>
                        <h5 class="fw-800 mb-1"><a href="<?= PUBLIC_URL ?>developer/<?= e($p['builder_slug']) ?>" class="text-dark text-decoration-none"><?= e($p['builder_name']) ?></a></h5>
                        <a href="<?= PUBLIC_URL ?>developer/<?= e($p['builder_slug']) ?>" class="text-primary small fw-bold text-decoration-none">View Profile <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$ajaxUrl = PUBLIC_URL . 'ajax/submit-enquiry';
ob_start();
?>
<script>
// Hero Gallery Swiper
document.addEventListener("DOMContentLoaded", function() {
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
    if (json.success) { this.reset(); this.classList.remove("was-validated"); }
  } catch(e) { 
    showToast("Failed to send. Please try again.", "error"); 
  }
  finally { btn.disabled = false; btn.innerHTML = 'Request Information'; }
});
</script>
<?php
$extraScripts = ob_get_clean();
?>
