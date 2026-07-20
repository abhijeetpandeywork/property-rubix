<?php
$title = "Premium Properties";
?>

<style>
.pr-hero {
  background: linear-gradient(135deg, #111 0%, #2b2013 100%);
  padding: 80px 0 60px;
  position: relative;
  overflow: hidden;
}
.pr-hero::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1600&q=80') center/cover;
  opacity: 0.2;
  mix-blend-mode: overlay;
}
.property-card {
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  height: 100%;
  display: flex;
  flex-direction: column;
}
.property-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.1);
  border-color: rgba(180,139,78,0.3);
}
.property-card-img-wrap {
  position: relative;
  height: 240px;
  overflow: hidden;
}
.property-card-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}
.property-card:hover .property-card-img {
  transform: scale(1.05);
}
.property-badge {
  position: absolute;
  top: 15px;
  left: 15px;
  background: #aa7d46;
  color: #fff;
  padding: 6px 14px;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 700;
  letter-spacing: 0.5px;
}
.property-badge-right {
  position: absolute;
  bottom: 0px;
  right: 0px;
  background: rgba(255,255,255,0.95);
  color: #333;
  padding: 6px 14px;
  border-top-left-radius: 8px;
  font-size: 0.8rem;
  font-weight: 600;
}
.property-title {
  font-size: 1.35rem;
  font-weight: 800;
  color: #111;
  margin-bottom: 5px;
}
.property-rating {
  font-size: 0.85rem;
  color: #666;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 4px;
}
.property-rating i {
  color: #f5c518;
}
.property-location {
  font-size: 0.95rem;
  color: #666;
  margin-bottom: 15px;
}
.property-price-wrap {
  display: flex;
  align-items: baseline;
  gap: 8px;
  margin-bottom: 15px;
  padding-bottom: 15px;
  border-bottom: 1px solid #eaeaea;
}
.property-price-label {
  font-size: 1rem;
  color: #333;
  font-weight: 600;
}
.property-price {
  font-size: 1.3rem;
  font-weight: 700;
  color: #aa7d46;
  margin-bottom: 0;
}
.meta-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 20px;
}
.meta-item {
  font-size: 0.9rem;
  color: #444;
  display: flex;
  align-items: center;
  gap: 8px;
}
.meta-item i {
  color: #666;
  width: 16px;
  text-align: center;
}
.card-actions {
  display: flex;
  gap: 8px;
  margin-top: auto;
}
.btn-card-action {
  background: #b1874f;
  color: white;
  border: none;
  font-weight: 600;
  border-radius: 6px;
  padding: 10px 0;
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  transition: all 0.2s;
  text-decoration: none;
  font-size: 0.95rem;
}
.btn-card-action:hover {
  background: #9d7541;
  color: white;
}
.btn-card-icon {
  background: #a67c42;
  color: white;
  border: none;
  border-radius: 6px;
  width: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
  text-decoration: none;
  font-size: 1.1rem;
}
.btn-card-icon:hover {
  background: #8e6834;
  color: white;
}
</style>

<!-- Hero Section -->
<section class="pr-hero text-white text-center">
  <div class="container position-relative z-1">
    <h1 class="display-4 fw-bold mb-3 anim-fade-up">Exclusive Properties</h1>
    <p class="fs-5 opacity-75 mx-auto anim-fade-up" style="max-width: 600px; animation-delay: 0.1s;">
      Discover luxury apartments, villas, and premium residences curated for the elite.
    </p>
    
    <div class="mt-5 mx-auto anim-fade-up" style="max-width: 700px; animation-delay: 0.2s;">
      <form action="<?= PUBLIC_URL ?>properties" method="get" class="d-flex bg-white rounded-pill p-2 shadow-lg">
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-control border-0 bg-transparent shadow-none px-4" placeholder="Search by property title, project or city..." style="font-size: 1.1rem;">
        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" style="background: #aa7d46; border:none;">Search</button>
      </form>
    </div>
  </div>
</section>

<!-- Content -->
<section class="py-5" style="background-color: #fcfbf9;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold m-0 text-dark">
        Showing <span style="color: #aa7d46;"><?= number_format($pager->total) ?></span> Properties
      </h4>
      <?php if (!empty($search)): ?>
        <a href="<?= PUBLIC_URL ?>properties" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Clear Search</a>
      <?php endif; ?>
    </div>

    <?php if (empty($properties)): ?>
      <div class="text-center py-5">
        <div class="display-1 text-muted opacity-25 mb-3"><i class="fas fa-home"></i></div>
        <h3 class="fw-bold">No Properties Found</h3>
        <p class="text-muted">Try adjusting your search criteria.</p>
        <a href="<?= PUBLIC_URL ?>properties" class="btn btn-primary rounded-pill px-4 mt-3" style="background: #aa7d46; border:none;">View All Properties</a>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($properties as $prop): 
          $images = json_decode($prop['gallery_images'] ?? '[]', true) ?: [];
          $firstImg = !empty($images) ? upload($images[0]) : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&q=80';
          $price = $prop['price_display_override'] ?: '₹ ' . number_format((float)$prop['price']);
          
          $phone = getSetting('phone_primary', '+91 98765 43210');
          $wa    = getSetting('whatsapp_number', '919876543210');
        ?>
        <div class="col-md-6 col-lg-4 anim-fade-up">
            <div class="property-card">
              <a href="<?= PUBLIC_URL ?>property/<?= e($prop['slug']) ?>" class="text-decoration-none">
                  <div class="property-card-img-wrap">
                    <img src="<?= e($firstImg) ?>" alt="<?= e($prop['title']) ?>" class="property-card-img" loading="lazy">
                    <div class="property-badge"><?= e($prop['possession_status'] ?: 'Ready to move') ?></div>
                    <div class="property-badge-right">Artistic Impression</div>
                  </div>
              </a>
              
              <div class="p-4 d-flex flex-column flex-grow-1">
                <a href="<?= PUBLIC_URL ?>property/<?= e($prop['slug']) ?>" class="text-decoration-none">
                    <h5 class="property-title"><?= e($prop['title']) ?></h5>
                    <div class="property-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        <span>4.5 Rating</span>
                    </div>
                    <div class="property-location">
                        <i class="fas fa-map-marker-alt text-muted me-1"></i> <?= e($prop['city_name'] ?: 'Location') ?>
                    </div>
                    
                    <div class="property-price-wrap">
                        <span class="property-price-label">Price</span>
                        <h4 class="property-price"><?= e($price) ?></h4>
                    </div>
                    
                    <div class="meta-grid">
                      <div class="meta-item">
                          <i class="fas fa-vector-square"></i> 
                          <?= $prop['carpet_area'] ? number_format((float)$prop['carpet_area']) . ' ' . e($prop['area_unit']) : 'N/A' ?>
                      </div>
                      <div class="meta-item">
                          <i class="fas fa-building"></i> 
                          <?= e($prop['property_type'] ?: 'N/A') ?>
                      </div>
                      <div class="meta-item">
                          <i class="fas fa-tag"></i> 
                          <?= e($prop['listing_type'] ?: 'Sale') ?>
                      </div>
                      <div class="meta-item">
                          <i class="fas fa-home"></i> 
                          <?= $prop['bedrooms'] ? (int)$prop['bedrooms'] . ' BHK' : 'N/A' ?>
                      </div>
                    </div>
                </a>
                
                <div class="card-actions">
                  <a href="<?= PUBLIC_URL ?>property/<?= e($prop['slug']) ?>" class="btn-card-action">
                    <i class="far fa-eye"></i> View
                  </a>
                  <a href="<?= PUBLIC_URL ?>property/<?= e($prop['slug']) ?>#enquiry" class="btn-card-action">
                    <i class="fas fa-car"></i> Visit
                  </a>
                  <a href="tel:<?= e(preg_replace('/[^+\d]/', '', $phone)) ?>" class="btn-card-icon" title="Call">
                    <i class="fas fa-phone-alt"></i>
                  </a>
                  <a href="https://wa.me/<?= e($wa) ?>?text=<?= urlencode('Hi, I am interested in '.$prop['title']) ?>" class="btn-card-icon" target="_blank" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                  </a>
                </div>

              </div>
            </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if ($pager->hasPages()): ?>
      <div class="mt-5 d-flex justify-content-center">
        <?= $pager->render() ?>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
