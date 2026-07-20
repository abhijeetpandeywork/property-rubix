<?php
/**
 * Advanced Luxury Project Detail View
 */
$p = $project;
$phone = getSetting('phone_primary', '+91 98765 43210');
$wa    = getSetting('whatsapp_number', '919876543210');

// Parse JSON arrays
$galleryImages = [];
if (!empty($p['gallery_images'])) {
    $galleryImages = json_decode($p['gallery_images'], true) ?: [];
}
// Fallback to legacy images table if gallery is empty
if (empty($galleryImages)) {
    if ($p['banner_image']) $galleryImages[] = $p['banner_image'];
    foreach ($images as $img) $galleryImages[] = $img['image_path'];
}

$floorPlanImages = [];
if (!empty($p['floor_plan_images'])) {
    $floorPlanImages = json_decode($p['floor_plan_images'], true) ?: [];
}

$projectAmenities = [];
if (!empty($p['amenities'])) {
    $projectAmenities = json_decode($p['amenities'], true) ?: [];
}
?>

<style>
/* --- Advanced Psychological UI Styling --- */
body {
    background-color: #fafafa;
}

/* 1. Immersive Edge-to-Edge Hero Gallery */
.luxury-hero-gallery {
    position: relative;
    width: 100%;
    height: 100vh;
    min-height: 100vh;
    background: #111;
    overflow: hidden;
}
.hero-swiper {
    width: 100%;
    height: 100%;
}
.hero-swiper .swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.8;
    transition: opacity 0.5s ease;
}
.hero-swiper .swiper-slide-active img {
    opacity: 1;
}

/* Gradient overlay for hero text */
.luxury-hero-gallery::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; width: 100%; height: 60%;
    background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);
    z-index: 1;
    pointer-events: none;
}

.hero-content {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    z-index: 2;
    padding-top: 60px;
    padding-bottom: 120px;
    color: white;
}

.luxury-badge {
    display: inline-block;
    padding: 8px 16px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-right: 10px;
    margin-bottom: 15px;
}

.luxury-title {
    font-size: 3.5rem;
    font-weight: 900;
    letter-spacing: -1px;
    margin-bottom: 10px;
    text-shadow: 0 4px 15px rgba(0,0,0,0.5);
}

.luxury-location {
    font-size: 1.2rem;
    font-weight: 500;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* 2. Glassmorphic Sticky Sidebar */
.sticky-enquiry-wrapper {
    position: sticky;
    top: 90px;
    z-index: 10;
}
.glass-sidebar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.5);
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
}
.price-display {
    font-size: 2rem;
    font-weight: 800;
    color: var(--pr-primary);
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eaeaea;
}

/* 3. Bento Box Facts Grid */
.bento-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 40px;
}
.bento-box {
    background: #fff;
    padding: 20px;
    border-radius: 16px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.bento-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}
.bento-icon {
    font-size: 1.5rem;
    color: var(--pr-primary);
    margin-bottom: 10px;
}
.bento-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #888;
    font-weight: 700;
    margin-bottom: 5px;
}
.bento-value {
    font-size: 1.1rem;
    font-weight: 800;
    color: #111;
}

/* Section Styling */
.lux-section {
    background: #fff;
    padding: 40px;
    border-radius: 24px;
    border: 1px solid #f0f0f0;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.01);
}
.lux-section-title {
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 25px;
    color: #111;
    display: flex;
    align-items: center;
    gap: 12px;
}
.lux-section-title i {
    color: var(--pr-primary);
}

/* Description Text */
.lux-description {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #555;
    white-space: pre-wrap;
}

/* Amenities Grid */
.lux-amenities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}
.lux-amenity {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 1.05rem;
    font-weight: 600;
    color: #333;
}
.lux-amenity i {
    color: var(--pr-primary);
    font-size: 1.2rem;
    background: rgba(229,175,83,0.1);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

/* Floor Plan Lightbox Grid */
.fp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}
.fp-item {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #eaeaea;
    cursor: pointer;
    background: #fff;
}
.fp-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.fp-item:hover img {
    transform: scale(1.05);
}
.fp-overlay {
    position: absolute;
    top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}
.fp-item:hover .fp-overlay {
    opacity: 1;
}
</style>

<!-- 1. Immersive Hero Gallery -->
<div class="luxury-hero-gallery">
  <div class="swiper hero-swiper">
    <div class="swiper-wrapper">
      <?php if (!empty($galleryImages)): ?>
          <?php foreach ($galleryImages as $img): ?>
          <div class="swiper-slide">
            <img src="<?= upload($img) ?>" alt="<?= e($p['name']) ?>">
          </div>
          <?php endforeach; ?>
      <?php else: ?>
          <div class="swiper-slide">
            <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075&auto=format&fit=crop" alt="Default Hero">
          </div>
      <?php endif; ?>
    </div>
    <div class="swiper-button-next" style="color:white; text-shadow:0 2px 4px rgba(0,0,0,0.5);"></div>
    <div class="swiper-button-prev" style="color:white; text-shadow:0 2px 4px rgba(0,0,0,0.5);"></div>
  </div>

  <div class="hero-content">
    <div class="container-fluid px-3 px-md-5">
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

<div class="section pt-5" style="background: #fafafa;">
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
            
            <?php if ($p['total_area']): ?>
            <div class="bento-box">
                <i class="fas fa-vector-square bento-icon"></i>
                <div class="bento-label">Project Area</div>
                <div class="bento-value"><?= e($p['total_area']) ?></div>
            </div>
            <?php endif; ?>

            <?php if ($p['total_units']): ?>
            <div class="bento-box">
                <i class="fas fa-building bento-icon"></i>
                <div class="bento-label">Total Units</div>
                <div class="bento-value"><?= e($p['total_units']) ?></div>
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
          <h2 class="lux-section-title"><i class="fas fa-info-circle"></i> About this Project</h2>
          <div class="lux-description"><?= e($p['description'] ?: $p['short_description']) ?></div>
        </div>
        <?php endif; ?>

        <!-- New Amenities Grid -->
        <?php if (!empty($projectAmenities)): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-gem"></i> Premium Amenities</h2>
          <div class="lux-amenities-grid">
            <?php foreach ($projectAmenities as $am): ?>
            <div class="lux-amenity">
              <i class="fas fa-check"></i>
              <?= e($am) ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Project Gallery -->
        <?php if (!empty($galleryImages)): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-images"></i> Project Gallery</h2>
          <div class="fp-grid">
            <?php foreach ($galleryImages as $img): ?>
            <div class="fp-item">
              <a href="<?= upload($img) ?>" target="_blank">
                <img src="<?= upload($img) ?>" alt="Gallery Image">
                <div class="fp-overlay">
                    <i class="fas fa-search-plus fa-2x text-white"></i>
                </div>
              </a>
            </div>
            <?php endforeach; ?>
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
                <img src="<?= upload($fp) ?>" alt="Floor Plan">
                <div class="fp-overlay">
                    <i class="fas fa-search-plus fa-2x text-white"></i>
                </div>
              </a>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Advanced Map View -->
        <?php if ($p['map_url'] || ($p['latitude'] && $p['longitude'])): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-map-marked-alt"></i> Location Map</h2>
          <?php if ($p['map_url'] && strpos($p['map_url'], '<iframe') !== false): ?>
              <div class="w-100 rounded" style="overflow:hidden; height: 400px;">
                  <?= $p['map_url'] ?>
              </div>
          <?php elseif ($p['map_url']): ?>
              <iframe src="<?= e($p['map_url']) ?>" width="100%" height="400" style="border:0; border-radius: 16px;" allowfullscreen="" loading="lazy"></iframe>
          <?php else: ?>
              <div id="project-map" style="height:400px; border-radius:16px;" data-lat="<?= e($p['latitude']) ?>" data-lng="<?= e($p['longitude']) ?>" data-name="<?= e($p['name']) ?>"></div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Developer Profile -->
        <?php if ($p['builder_name']): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-hard-hat"></i> The Developer</h2>
          <div class="d-flex gap-4 align-items-start flex-wrap flex-md-nowrap">
            <?php if ($p['builder_logo']): ?>
            <div style="width: 120px; height:120px; border-radius:16px; background:#fff; border:1px solid #eaeaea; display:flex; align-items:center; justify-content:center; padding:10px;">
                <img src="<?= upload($p['builder_logo']) ?>" alt="<?= e($p['builder_name']) ?>" style="max-width:100%; max-height:100%; object-fit:contain">
            </div>
            <?php endif; ?>
            <div>
              <h3 class="fw-800 mb-2" style="font-size:1.8rem;">
                <a href="<?= PUBLIC_URL ?>developer/<?= e($p['builder_slug']) ?>" class="text-dark text-decoration-none"><?= e($p['builder_name']) ?></a>
              </h3>
              <?php if ($p['builder_desc']): ?>
              <p class="text-muted" style="line-height:1.6;"><?= e(View::excerpt($p['builder_desc'], 200)) ?></p>
              <?php endif; ?>
              <a href="<?= PUBLIC_URL ?>developer/<?= e($p['builder_slug']) ?>" class="btn btn-outline-dark rounded-pill px-4 mt-2">View Profile</a>
            </div>
          </div>
        </div>
        <?php endif; ?>

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
              <a href="https://wa.me/<?= e($wa) ?>?text=<?= urlencode("Hi, I'm interested in {$p['name']}. Please share details.") ?>"
                 target="_blank" class="btn py-3 fw-bold text-white shadow-sm" style="background: #25D366; border-radius:12px;">
                <i class="fab fa-whatsapp me-2"></i> Chat on WhatsApp
              </a>
              <?php if ($p['brochure_pdf']): ?>
              <a href="<?= upload($p['brochure_pdf']) ?>" target="_blank" class="btn btn-outline-dark py-3 fw-bold shadow-sm" style="border-radius:12px;">
                <i class="fas fa-download me-2"></i> Download Brochure
              </a>
              <?php endif; ?>
            </div>

            <?php if ($p['rera_id']): ?>
            <div class="mt-4 p-3 rounded text-center" style="background:#f4f4f4; border:1px dashed #ccc;">
              <p class="mb-0 small fw-bold text-muted">
                <i class="fas fa-shield-alt me-1 text-success"></i> RERA Approved: <span class="text-dark"><?= e($p['rera_id']) ?></span>
              </p>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$extraHead    = '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">';
$ajaxUrl = PUBLIC_URL . 'ajax/submit-enquiry';
ob_start();
?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Hero Gallery Swiper
document.addEventListener("DOMContentLoaded", function() {
    new Swiper(".hero-swiper", {
        loop: true,
        effect: "fade",
        autoplay: { delay: 3500, disableOnInteraction: false },
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" }
    });
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
    console.error(e);
    showToast("Failed to send. Please try again.", "error"); 
  }
  finally { btn.disabled = false; btn.innerHTML = 'Request Information'; }
});

// Leaflet Map (Fallback)
const mapEl = document.getElementById("project-map");
if (mapEl && !document.querySelector("iframe")) {
  const lat = parseFloat(mapEl.dataset.lat);
  const lng = parseFloat(mapEl.dataset.lng);
  const name = mapEl.dataset.name;
  if(lat && lng) {
    const map = L.map('project-map').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
    L.marker([lat, lng]).addTo(map).bindPopup(name).openPopup();
  }
}
</script>
<?php
$extraScripts = ob_get_clean();
?>
