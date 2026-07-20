<?php /** City listing view with project cards + filters */ ?>

<!-- Elevated Cinematic Hero Section -->
<div class="position-relative" style="height: 45vh; min-height: 350px; max-height: 450px; overflow: hidden; margin-top: -1px;">
  <?php if ($city['banner_image']): ?>
    <img src="<?= upload($city['banner_image']) ?>" alt="<?= e($city['name']) ?>" class="position-absolute w-100 h-100 object-fit-cover" style="top:0; left:0; z-index:0; filter: brightness(0.5);">
  <?php else: ?>
    <!-- Fallback high-quality cityscape if no banner is uploaded -->
    <img src="https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=1920&q=80" alt="<?= e($city['name']) ?>" class="position-absolute w-100 h-100 object-fit-cover" style="top:0; left:0; z-index:0; filter: brightness(0.5);">
  <?php endif; ?>
  
  <div class="position-absolute w-100 h-100" style="top:0; left:0; background: linear-gradient(to top, var(--pr-secondary) 0%, transparent 100%); z-index:1;"></div>
  
  <div class="container-fluid px-3 px-md-5 position-relative z-2 h-100 d-flex flex-column justify-content-center pb-5 text-center text-white">
    <nav aria-label="breadcrumb" class="mb-3 d-flex justify-content-center">
      <ol class="breadcrumb mb-0" style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 50px; backdrop-filter: blur(10px);">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-white text-decoration-none opacity-75">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location" class="text-white text-decoration-none opacity-75">Locations</a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>" class="text-white text-decoration-none opacity-75"><?= e($city['country_name']) ?></a></li>
        <li class="breadcrumb-item active text-white" aria-current="page"><?= e($city['name']) ?></li>
      </ol>
    </nav>
    <h1 class="display-4 fw-bold mb-3">Discover <span style="color:var(--pr-primary)"><?= e($city['name']) ?></span></h1>
    <p class="fs-5 opacity-75 mx-auto" style="max-width: 600px;">Explore premium neighborhoods and exclusive properties in <?= e($city['state_name']) ?>, <?= e($city['country_name']) ?>.</p>
  </div>
</div>

<div class="section py-5 position-relative" style="background:#f8f9fa;">
  <div class="container-fluid px-3 px-md-5">
    
    <!-- Prominent, Elevated Search Bar -->
    <div class="row justify-content-center mb-5" style="margin-top: -80px; position: relative; z-index: 10;">
      <div class="col-lg-8 col-md-10">
        <div class="bg-white p-3 rounded-pill shadow-lg d-flex align-items-center" style="border: 1px solid rgba(0,0,0,0.05);">
          <i class="fas fa-search fs-4 text-muted ms-3 me-2"></i>
          <input type="text" id="localitySearch" class="form-control form-control-lg border-0 shadow-none bg-transparent" placeholder="Find your perfect neighborhood..." style="font-size: 1.1rem;">
        </div>
      </div>
    </div>
    
    <div class="text-center mb-5">
      <h2 class="fw-bold h3 mb-3 text-dark">Popular Neighborhoods</h2>
      <div style="width:60px; height:4px; background:var(--pr-primary); margin: 0 auto;"></div>
    </div>

    <div class="row g-4 justify-content-center" id="localityGrid">
      <?php if (!empty($localities)): ?>
        <?php foreach($localities as $loc): ?>
          <div class="col-xl-3 col-lg-4 col-md-6 locality-item-wrapper">
            <a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>/<?= e($city['state_slug']) ?>/<?= e($city['slug']) ?>/<?= e(slugify($loc['location_area'])) ?>" 
               class="premium-locality-card text-decoration-none d-flex flex-column align-items-center justify-content-center p-4 bg-white rounded-4 position-relative h-100 text-center">
               
               <div class="locality-icon-wrapper mb-3 d-flex align-items-center justify-content-center rounded-circle">
                 <i class="fas fa-map-marked-alt"></i>
               </div>
               
               <h3 class="fw-bold text-dark mb-2 locality-title" style="font-size: 1.25rem; line-height: 1.3;"><?= e($loc['location_area']) ?></h3>
               
               <div class="mt-auto">
                 <span class="badge rounded-pill fw-normal" style="background: rgba(0,0,0,0.05); color: #64748b; padding: 6px 12px; font-size: 0.85rem; letter-spacing: 0.5px; transition: all 0.3s ease;">
                   <?= (int)$loc['project_count'] ?> PROPERTIES
                 </span>
               </div>
               
               <div class="explore-hint position-absolute bottom-0 w-100 text-center pb-3 opacity-0">
                 <span class="text-primary fw-bold" style="font-size: 0.9rem;">Explore Area <i class="fas fa-arrow-right ms-1"></i></span>
               </div>
               
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <div class="mb-4 text-muted opacity-50"><i class="fas fa-building fa-4x"></i></div>
          <h4 class="fw-bold text-dark">No neighborhoods listed yet.</h4>
          <p class="text-muted">We are actively adding new exclusive properties in this city.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
/* Premium Locality Card */
.premium-locality-card {
  border: 1px solid rgba(0,0,0,0.05);
  box-shadow: 0 5px 15px rgba(0,0,0,0.02);
  transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  overflow: hidden;
  padding-bottom: 2.5rem !important; /* Make room for the hint */
}
.locality-icon-wrapper {
  width: 60px; height: 60px;
  background: rgba(235, 175, 75, 0.1);
  color: var(--pr-primary);
  font-size: 1.5rem;
  transition: all 0.4s ease;
}
.locality-title {
  transition: color 0.3s ease;
}
.explore-hint {
  transform: translateY(10px);
  transition: all 0.4s ease;
}

/* Hover States */
.premium-locality-card:hover {
  transform: translateY(-8px);
  border-color: var(--pr-primary);
  box-shadow: 0 15px 35px rgba(0,0,0,0.08);
}
.premium-locality-card:hover .locality-icon-wrapper {
  background: var(--pr-primary);
  color: var(--pr-secondary);
  transform: scale(1.1);
}
.premium-locality-card:hover .locality-title {
  color: var(--pr-primary) !important;
}
.premium-locality-card:hover .badge {
  background: var(--pr-primary) !important;
  color: var(--pr-secondary) !important;
}
.premium-locality-card:hover .explore-hint {
  opacity: 1;
  transform: translateY(0);
}
</style>

<script>
document.getElementById('localitySearch')?.addEventListener('input', function(e) {
   let filter = e.target.value.toLowerCase();
   document.querySelectorAll('.locality-item-wrapper').forEach(item => {
       let title = item.querySelector('.locality-title').innerText.toLowerCase();
       if (title.includes(filter)) {
           item.style.display = 'block';
       } else {
           item.style.display = 'none';
       }
   });
});
</script>
