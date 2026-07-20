<?php
/**
 * Property Detail — mirrors DLF Camellias / project detail design
 */
$prop  = $property;
$phone = getSetting('phone_primary', '+91 98765 43210');
$wa    = getSetting('whatsapp_number', '919876543210');

// Gallery images
$galleryImages = [];
if (!empty($prop['gallery_images'])) {
    $galleryImages = json_decode($prop['gallery_images'], true) ?: [];
}
if (empty($galleryImages)) {
    // Fallback placeholder
    $galleryImages = [];
}

$floorPlanImages = [];
if (!empty($prop['floor_plan_images'])) {
    $floorPlanImages = json_decode($prop['floor_plan_images'], true) ?: [];
}

$amenitiesRaw = trim($prop['amenities'] ?? '');
$propAmenities = $amenitiesRaw ? preg_split('/[,|]+/', $amenitiesRaw) : [];

$price = $prop['price_display_override'] ?: '₹ ' . number_format((float)$prop['price']);
?>

<style>
/* ── Same design system as project/detail.php ──────────────────────── */
body { background-color: #fafafa; }

/* 1. Immersive Edge-to-Edge Hero Gallery */
.luxury-hero-gallery {
    position: relative;
    width: 100%;
    height: 100vh;
    min-height: 100vh;
    background: #111;
    overflow: hidden;
}
.hero-swiper { width: 100%; height: 100%; }
.hero-swiper .swiper-slide img {
    width: 100%; height: 100%;
    object-fit: cover; opacity: 0.8;
    transition: opacity 0.5s ease;
}
.hero-swiper .swiper-slide-active img { opacity: 1; }
.luxury-hero-gallery::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; width: 100%; height: 60%;
    background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);
    z-index: 1; pointer-events: none;
}
.hero-content {
    position: absolute;
    bottom: 0; left: 0; width: 100%; z-index: 2;
    padding-top: 60px; padding-bottom: 120px; color: white;
}
.luxury-badge {
    display: inline-block;
    padding: 8px 16px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    font-size: 0.85rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1px;
    margin-right: 10px; margin-bottom: 15px;
}
.luxury-title {
    font-size: 3.5rem; font-weight: 900;
    letter-spacing: -1px; margin-bottom: 10px;
    text-shadow: 0 4px 15px rgba(0,0,0,0.5);
}
.luxury-location {
    font-size: 1.2rem; font-weight: 500;
    opacity: 0.9; display: flex; align-items: center; gap: 8px;
}

/* 2. Glassmorphic Sticky Sidebar */
.sticky-enquiry-wrapper { position: sticky; top: 90px; z-index: 10; }
.glass-sidebar {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.5);
    border-radius: 24px; padding: 30px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
}
.price-display {
    font-size: 2rem; font-weight: 800;
    color: var(--pr-primary);
    margin-bottom: 25px; padding-bottom: 20px;
    border-bottom: 1px solid #eaeaea;
}

/* 3. Bento Box */
.bento-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px; margin-bottom: 40px;
}
.bento-box {
    background: #fff; padding: 20px;
    border-radius: 16px; border: 1px solid #f0f0f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    display: flex; flex-direction: column; justify-content: center;
    transition: transform 0.2s, box-shadow 0.2s;
}
.bento-box:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
.bento-icon { font-size: 1.5rem; color: var(--pr-primary); margin-bottom: 10px; }
.bento-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #888; font-weight: 700; margin-bottom: 5px; }
.bento-value { font-size: 1.1rem; font-weight: 800; color: #111; }

/* 4. Section cards */
.lux-section {
    background: #fff; padding: 40px;
    border-radius: 24px; border: 1px solid #f0f0f0;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.01);
}
.lux-section-title {
    font-size: 1.5rem; font-weight: 800;
    margin-bottom: 25px; color: #111;
    display: flex; align-items: center; gap: 12px;
}
.lux-section-title i { color: var(--pr-primary); }
.lux-description { font-size: 1.05rem; line-height: 1.8; color: #555; white-space: pre-wrap; }

/* 5. Amenities */
.lux-amenities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}
.lux-amenity {
    display: flex; align-items: center; gap: 15px;
    font-size: 1.05rem; font-weight: 600; color: #333;
}
.lux-amenity i {
    color: var(--pr-primary); font-size: 1.2rem;
    background: rgba(229,175,83,0.1);
    width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%;
}

/* 6. Gallery & Floor plan grid */
.fp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}
.fp-item {
    position: relative; border-radius: 16px;
    overflow: hidden; border: 1px solid #eaeaea;
    cursor: pointer; background: #fff;
}
.fp-item img { width: 100%; height: 200px; object-fit: cover; transition: transform 0.4s; }
.fp-item:hover img { transform: scale(1.05); }
.fp-overlay {
    position: absolute; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.4);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.3s;
}
.fp-item:hover .fp-overlay { opacity: 1; }

/* 7. Spec table */
.spec-table { width: 100%; }
.spec-table tr:not(:last-child) td { border-bottom: 1px solid #f5f5f5; }
.spec-table td { padding: 12px 6px; font-size: .97rem; }
.spec-table td:first-child { color: #888; font-weight: 600; width: 48%; }
.spec-table td:last-child  { color: #111; font-weight: 700; }
</style>

<!-- ─── 1. IMMERSIVE HERO GALLERY ─────────────────────────────────── -->
<div class="luxury-hero-gallery">
  <div class="swiper hero-swiper">
    <div class="swiper-wrapper">
      <?php if (!empty($galleryImages)): ?>
        <?php foreach ($galleryImages as $img): ?>
        <div class="swiper-slide">
          <img src="<?= upload($img) ?>" alt="<?= e($prop['title']) ?>">
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="swiper-slide">
          <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075&auto=format&fit=crop" alt="<?= e($prop['title']) ?>">
        </div>
      <?php endif; ?>
    </div>
    <div class="swiper-button-next" style="color:white; text-shadow:0 2px 4px rgba(0,0,0,0.5);"></div>
    <div class="swiper-button-prev" style="color:white; text-shadow:0 2px 4px rgba(0,0,0,0.5);"></div>
  </div>

  <div class="hero-content">
    <div class="container-fluid px-3 px-md-5">
      <span class="luxury-badge"><?= e($prop['listing_type'] ?: 'For Sale') ?></span>
      <span class="luxury-badge" style="background:var(--pr-primary); color:#111; border:none;"><?= e($prop['property_type'] ?: 'Apartment') ?></span>
      <?php if($prop['possession_status']): ?>
        <span class="luxury-badge" style="background:rgba(34,197,94,0.2); border-color:rgba(34,197,94,0.4);"><?= e($prop['possession_status']) ?></span>
      <?php endif; ?>
      <h1 class="luxury-title"><?= e($prop['title']) ?></h1>
      <div class="luxury-location">
        <i class="fas fa-map-marker-alt" style="color:var(--pr-primary);"></i>
        <?= $prop['location_area'] ? e($prop['location_area']) . ', ' : '' ?><?= e($prop['city_name']) ?>
      </div>
    </div>
  </div>
</div>

<!-- ─── 2. MAIN CONTENT AREA ─────────────────────────────────────── -->
<div class="section pt-5" style="background:#fafafa;">
  <div class="container-fluid px-3 px-md-5">
    <div class="row g-5">

      <!-- ── LEFT COLUMN ── -->
      <div class="col-lg-8">

        <!-- Bento Quick Facts -->
        <div class="bento-grid">
          <?php if ($prop['bedrooms']): ?>
          <div class="bento-box">
            <i class="fas fa-bed bento-icon"></i>
            <div class="bento-label">Bedrooms</div>
            <div class="bento-value"><?= (int)$prop['bedrooms'] ?> BHK</div>
          </div>
          <?php endif; ?>

          <?php if ($prop['bathrooms']): ?>
          <div class="bento-box">
            <i class="fas fa-bath bento-icon"></i>
            <div class="bento-label">Bathrooms</div>
            <div class="bento-value"><?= (int)$prop['bathrooms'] ?></div>
          </div>
          <?php endif; ?>

          <?php if ($prop['carpet_area']): ?>
          <div class="bento-box">
            <i class="fas fa-vector-square bento-icon"></i>
            <div class="bento-label">Carpet Area</div>
            <div class="bento-value"><?= number_format((float)$prop['carpet_area']) ?> <?= e($prop['area_unit']) ?></div>
          </div>
          <?php endif; ?>

          <?php if ($prop['built_up_area']): ?>
          <div class="bento-box">
            <i class="fas fa-home bento-icon"></i>
            <div class="bento-label">Built-Up Area</div>
            <div class="bento-value"><?= number_format((float)$prop['built_up_area']) ?> <?= e($prop['area_unit']) ?></div>
          </div>
          <?php endif; ?>

          <?php if ($prop['furnishing_status']): ?>
          <div class="bento-box">
            <i class="fas fa-couch bento-icon"></i>
            <div class="bento-label">Furnishing</div>
            <div class="bento-value"><?= e($prop['furnishing_status']) ?></div>
          </div>
          <?php endif; ?>

          <?php if ($prop['facing']): ?>
          <div class="bento-box">
            <i class="fas fa-compass bento-icon"></i>
            <div class="bento-label">Facing</div>
            <div class="bento-value"><?= e($prop['facing']) ?></div>
          </div>
          <?php endif; ?>

          <?php if ($prop['parking_spaces']): ?>
          <div class="bento-box">
            <i class="fas fa-car bento-icon"></i>
            <div class="bento-label">Parking</div>
            <div class="bento-value"><?= (int)$prop['parking_spaces'] ?> Car(s)</div>
          </div>
          <?php endif; ?>

          <?php if ($prop['possession_date']): ?>
          <div class="bento-box">
            <i class="fas fa-calendar-check bento-icon"></i>
            <div class="bento-label">Possession</div>
            <div class="bento-value"><?= e($prop['possession_date']) ?></div>
          </div>
          <?php endif; ?>
        </div>

        <!-- About This Property -->
        <?php if ($prop['description']): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-info-circle"></i> About This Property</h2>
          <div class="lux-description"><?= nl2br(e($prop['description'])) ?></div>
        </div>
        <?php endif; ?>

        <!-- Full Specifications -->
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-clipboard-list"></i> Specifications</h2>
          <div class="row g-4">
            <div class="col-md-6">
              <table class="spec-table">
                <?php
                $col1 = [
                  'Property Type'   => $prop['property_type'],
                  'Transaction'     => $prop['listing_type'],
                  'Market Type'     => $prop['market_type'] ?? '',
                  'Possession'      => $prop['possession_status'],
                  'Age'             => $prop['age_of_construction'] ?? '',
                  'RERA No.'        => $prop['rera_id'] ?? '',
                  'Floor'           => ($prop['floor_number'] && $prop['total_floors']) ? 'Floor ' . $prop['floor_number'] . ' of ' . $prop['total_floors'] : '',
                ];
                foreach ($col1 as $k => $v): if (!trim((string)$v)) continue; ?>
                <tr><td><?= $k ?></td><td><?= e($v) ?></td></tr>
                <?php endforeach; ?>
              </table>
            </div>
            <div class="col-md-6">
              <table class="spec-table">
                <?php
                $col2 = [
                  'Bedrooms'        => $prop['bedrooms'] ? (int)$prop['bedrooms'] : null,
                  'Bathrooms'       => $prop['bathrooms'] ? (int)$prop['bathrooms'] : null,
                  'Balconies'       => !empty($prop['balconies']) ? $prop['balconies'] : null,
                  'Parking'         => $prop['parking_spaces'] ? (int)$prop['parking_spaces'] . ' Cars' : null,
                  'Carpet Area'     => $prop['carpet_area'] ? number_format((float)$prop['carpet_area']) . ' ' . $prop['area_unit'] : null,
                  'Built-Up Area'   => $prop['built_up_area'] ? number_format((float)$prop['built_up_area']) . ' ' . $prop['area_unit'] : null,
                  'Super Built-Up'  => $prop['super_built_up_area'] ? number_format((float)$prop['super_built_up_area']) . ' ' . $prop['area_unit'] : null,
                  'Furnishing'      => $prop['furnishing_status'] ?? null,
                  'Facing'          => $prop['facing'] ?? null,
                ];
                foreach ($col2 as $k => $v): if ($v === null || $v === '') continue; ?>
                <tr><td><?= $k ?></td><td><?= e($v) ?></td></tr>
                <?php endforeach; ?>
              </table>
            </div>
          </div>
        </div>

        <!-- Amenities -->
        <?php if (!empty($propAmenities)): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-gem"></i> Premium Amenities</h2>
          <div class="lux-amenities-grid">
            <?php foreach ($propAmenities as $am): $am = trim($am); if (!$am) continue; ?>
            <div class="lux-amenity">
              <i class="fas fa-check"></i>
              <?= e($am) ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Gallery Grid -->
        <?php if (!empty($galleryImages)): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-images"></i> Property Gallery</h2>
          <div class="fp-grid">
            <?php foreach ($galleryImages as $img): ?>
            <div class="fp-item">
              <a href="<?= upload($img) ?>" target="_blank">
                <img src="<?= upload($img) ?>" alt="Gallery Image">
                <div class="fp-overlay"><i class="fas fa-search-plus fa-2x text-white"></i></div>
              </a>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Floor Plans -->
        <?php if (!empty($floorPlanImages)): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-ruler-combined"></i> Floor Plans</h2>
          <div class="fp-grid">
            <?php foreach ($floorPlanImages as $fp): ?>
            <div class="fp-item">
              <a href="<?= upload($fp) ?>" target="_blank">
                <img src="<?= upload($fp) ?>" alt="Floor Plan">
                <div class="fp-overlay"><i class="fas fa-search-plus fa-2x text-white"></i></div>
              </a>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Location Map -->
        <?php if ($prop['latitude'] && $prop['longitude']): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-map-marked-alt"></i> Location Map</h2>
          <div id="property-map" style="height:400px; border-radius:16px;"
               data-lat="<?= e($prop['latitude']) ?>"
               data-lng="<?= e($prop['longitude']) ?>"
               data-name="<?= e($prop['title']) ?>">
          </div>
        </div>
        <?php endif; ?>

        <!-- Part of Project -->
        <?php if ($prop['project_name']): ?>
        <div class="lux-section">
          <h2 class="lux-section-title"><i class="fas fa-building"></i> Part of Project</h2>
          <div class="d-flex gap-4 align-items-center">
            <div>
              <h3 class="fw-800 mb-1" style="font-size:1.8rem;">
                <a href="<?= PUBLIC_URL ?>project/<?= e($prop['project_slug']) ?>" class="text-dark text-decoration-none"><?= e($prop['project_name']) ?></a>
              </h3>
              <?php if($prop['builder_name']): ?>
                <p class="text-muted mb-2">By <?= e($prop['builder_name']) ?></p>
              <?php endif; ?>
              <a href="<?= PUBLIC_URL ?>project/<?= e($prop['project_slug']) ?>" class="btn btn-outline-dark rounded-pill px-4 mt-2">View Project</a>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div>

      <!-- ── RIGHT SIDEBAR ── -->
      <div class="col-lg-4">
        <div class="sticky-enquiry-wrapper">
          <div class="glass-sidebar">
            <h3 class="fw-800 mb-2">Interested?</h3>
            <p class="text-muted small mb-4">Request pricing details, a brochure, or schedule a priority site visit.</p>

            <div class="price-display"><?= e($price) ?></div>

            <form id="propEnquiryForm" novalidate>
              <input type="text" name="hp_name" style="display:none" tabindex="-1">
              <input type="hidden" name="form_type" value="enquiry">
              <input type="hidden" name="property_id" value="<?= $prop['id'] ?>">
              <input type="hidden" name="property_name" value="<?= e($prop['title']) ?>">

              <div class="mb-3">
                <input type="text" class="form-control form-control-lg shadow-none" name="name" placeholder="Full Name" required style="border-radius:12px; background:#f9f9f9;">
              </div>
              <div class="mb-3">
                <input type="tel" class="form-control form-control-lg shadow-none" name="phone" placeholder="Phone Number" required style="border-radius:12px; background:#f9f9f9;">
              </div>
              <div class="mb-3">
                <input type="email" class="form-control form-control-lg shadow-none" name="email" placeholder="Email Address" style="border-radius:12px; background:#f9f9f9;">
              </div>
              <button type="submit" class="btn btn-primary w-100 py-3 mb-3 fw-bold shadow-lg" style="border-radius:12px; font-size:1.1rem; background:var(--pr-primary); border:none;">
                Request Information
              </button>
              <div id="propEnquiryAlert" class="d-none alert rounded-3 mt-2"></div>
            </form>

            <div class="d-grid gap-3 mt-2">
              <a href="https://wa.me/<?= e($wa) ?>?text=<?= urlencode("Hi, I'm interested in {$prop['title']}. Please share details.") ?>"
                 target="_blank" class="btn py-3 fw-bold text-white shadow-sm" style="background:#25D366; border-radius:12px;">
                <i class="fab fa-whatsapp me-2"></i> Chat on WhatsApp
              </a>
              <a href="tel:<?= e(preg_replace('/[^+\d]/', '', $phone)) ?>" class="btn btn-dark py-3 fw-bold shadow-sm" style="border-radius:12px;">
                <i class="fas fa-phone-alt me-2"></i> Call Now
              </a>
            </div>

            <?php if (!empty($prop['rera_id'])): ?>
            <div class="mt-4 p-3 rounded text-center" style="background:#f4f4f4; border:1px dashed #ccc;">
              <p class="mb-0 small fw-bold text-muted">
                <i class="fas fa-shield-alt me-1 text-success"></i> RERA No: <span class="text-dark"><?= e($prop['rera_id']) ?></span>
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
$mapLat  = $prop['latitude']  ?? '';
$mapLng  = $prop['longitude'] ?? '';
$mapName = addslashes($prop['title'] ?? '');
$ajaxUrl = PUBLIC_URL . 'ajax/submit-enquiry';

ob_start();
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    new Swiper(".hero-swiper", {
        loop: true,
        effect: "fade",
        autoplay: { delay: 3500, disableOnInteraction: false },
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" }
    });

    // Enquiry AJAX
    document.getElementById("propEnquiryForm")?.addEventListener("submit", async function(e) {
        e.preventDefault();
        if (!this.checkValidity()) { this.classList.add("was-validated"); return; }
        const btn   = this.querySelector("button[type=submit]");
        const alertBox = document.getElementById("propEnquiryAlert");
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Sending…';
        try {
            const res  = await fetch("<?= $ajaxUrl ?>", {
                method: "POST",
                body: new FormData(this),
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            const text = await res.text();
            let json;
            try { json = JSON.parse(text); } catch(pe) {
                console.error('Non-JSON response:', text);
                throw new Error('Invalid server response');
            }
            alertBox.classList.remove("d-none","alert-success","alert-danger");
            alertBox.classList.add(json.success ? "alert-success" : "alert-danger");
            alertBox.innerHTML = json.message || (json.success ? "Sent successfully!" : "Error occurred.");
            if (json.success) { this.reset(); this.classList.remove("was-validated"); }
        } catch(err) {
            console.error('Form error:', err);
            alertBox.classList.remove("d-none","alert-success");
            alertBox.classList.add("alert-danger");
            alertBox.innerHTML = "Failed to send. Please try again.";
        }
        btn.disabled = false;
        btn.innerHTML = "Request Information";
    });

    // Leaflet map
    <?php if ($mapLat && $mapLng): ?>
    (function() {
        const map = L.map("property-map").setView([<?= $mapLat ?>, <?= $mapLng ?>], 15);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { attribution: "© OpenStreetMap" }).addTo(map);
        L.marker([<?= $mapLat ?>, <?= $mapLng ?>]).addTo(map).bindPopup("<?= $mapName ?>").openPopup();
    })();
    <?php endif; ?>
});
</script>
<?php
$extraScripts = ob_get_clean();
?>
