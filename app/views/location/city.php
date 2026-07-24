<?php 
/** City listing view with project cards + filters */ 
$bannerImg = $city['banner_image'] ? upload($city['banner_image']) : 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=1920&q=80';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');
.hero-title { font-family: 'Outfit', sans-serif; letter-spacing: -1px; text-shadow: 0 10px 30px rgba(0,0,0,0.5); }
</style>

<!-- Cinematic Hero Section -->
<div class="position-relative" style="height: 55vh; min-height: 450px; overflow: hidden; margin-top: -1px;">
  <img src="<?= $bannerImg ?>" alt="<?= e($city['name']) ?>" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.5) contrast(1.1); transform: scale(1.1); transition: transform 12s cubic-bezier(0.25, 0.46, 0.45, 0.94);" id="heroImg">
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.1) 40%, rgba(0,0,0,0.9) 100%);"></div>
  
  <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center px-3" style="z-index: 2;">
    <div class="badge mb-4 px-4 py-2" style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); font-weight: 400; letter-spacing: 3px; text-transform: uppercase; border-radius: 50px; backdrop-filter: blur(8px);">
      <i class="fas fa-city me-2" style="color: var(--pr-primary);"></i> Premium City Destinations
    </div>
    <h1 class="display-2 fw-800 text-white mb-3 hero-title">
      Discover <span style="color: var(--pr-primary); position: relative; display: inline-block;">
        <?= e($city['name']) ?>
        <svg width="100%" height="15" viewBox="0 0 100 15" preserveAspectRatio="none" style="position:absolute; bottom:-5px; left:0; z-index:-1; opacity: 0.7;">
            <path d="M0,10 Q50,0 100,10" stroke="var(--pr-primary)" stroke-width="4" fill="none"/>
        </svg>
      </span>
    </h1>
    <p class="lead text-white-50 mb-0" style="max-width: 700px; font-weight: 400; font-size: 1.15rem; line-height: 1.6;">
      Explore premium neighborhoods and exclusive projects in <?= e($city['state_name']) ?>, <?= e($city['country_name']) ?>.
    </p>
  </div>
</div>

<!-- Breadcrumb over white -->
<div class="bg-white border-bottom sticky-top" style="z-index: 10; top: 76px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
  <div class="container-fluid px-3 px-md-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 py-3" style="font-size: 0.9rem; font-weight: 500;">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-decoration-none text-muted"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location" class="text-decoration-none text-muted">Locations</a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>" class="text-decoration-none text-muted"><?= e($city['country_name']) ?></a></li>
        <li class="breadcrumb-item active text-dark fw-bold"><?= e($city['name']) ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="section py-5 position-relative" style="background:#f8f9fa;">
  <div class="container-fluid px-3 px-md-5">
    
    <!-- Prominent Search Bar -->
    <div class="row justify-content-center mb-5" style="position: relative; z-index: 10; padding-top: 15px;">
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
          <p class="text-muted">We are actively adding new exclusive projects in this city.</p>
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
