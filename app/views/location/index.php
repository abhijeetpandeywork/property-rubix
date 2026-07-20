<?php /** Location Index View */ ?>

<!-- Emotional Hero Section -->
<div class="position-relative" style="height: 50vh; min-height: 350px; max-height: 450px; overflow: hidden; margin-top: -1px;">
  <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80" 
       alt="Explore Locations" class="position-absolute w-100 h-100 object-fit-cover" style="top:0; left:0; z-index:0; filter: brightness(0.6);">
  <div class="position-absolute w-100 h-100" style="top:0; left:0; background: linear-gradient(to top, var(--pr-secondary) 0%, transparent 100%); z-index:1;"></div>
  
  <div class="container-fluid px-3 px-md-5 position-relative z-2 h-100 d-flex flex-column justify-content-center pb-4 text-center text-white">
    <nav aria-label="breadcrumb" class="mb-3 d-flex justify-content-center">
      <ol class="breadcrumb mb-0" style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 50px; backdrop-filter: blur(10px);">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-white text-decoration-none opacity-75">Home</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">Global Locations</li>
      </ol>
    </nav>
    <h1 class="display-4 fw-bold mb-3">Find Your Place in the <span style="color:var(--pr-primary)">World</span></h1>
    <p class="fs-5 opacity-75 mx-auto" style="max-width: 600px;">Explore the most exclusive real estate markets across the globe. Your next masterpiece awaits.</p>
  </div>
</div>

<div class="section py-5" style="background:#f8f9fa;">
  <div class="container-fluid px-3 px-md-5">
    
    <div class="text-center mb-5">
      <h2 class="fw-bold h2 mb-3">Discover Our Markets</h2>
      <div style="width:60px; height:4px; background:var(--pr-primary); margin: 0 auto;"></div>
    </div>

    <div class="row g-4 justify-content-center">
      <?php foreach ($countries as $co): ?>
      <?php
          // Assign an iconic Unsplash image based on the country slug
          $bgImg = 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=600&q=80'; // default cityscape
          switch(strtolower($co['slug'])) {
              case 'uae': $bgImg = 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=600&q=80'; break; // Dubai
              case 'india': $bgImg = 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=600&q=80'; break; // Taj/India
              case 'usa': $bgImg = 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=600&q=80'; break; // New York
              case 'uk': $bgImg = 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=600&q=80'; break; // London
              case 'canada': $bgImg = 'https://images.unsplash.com/photo-1519999482648-25049ddd37b1?w=600&q=80'; break; // Toronto
          }
      ?>
      <div class="col-lg-4 col-md-6">
        <a href="<?= PUBLIC_URL ?>location/<?= e($co['slug']) ?>" class="country-image-card text-decoration-none d-block overflow-hidden rounded-4 shadow-sm position-relative">
          <img src="<?= $bgImg ?>" alt="<?= e($co['name']) ?>" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0 country-bg-img">
          <div class="country-card-overlay position-absolute w-100 h-100 top-0 start-0"></div>
          
          <div class="country-card-content position-relative z-2 p-4 d-flex flex-column justify-content-end h-100 text-white">
            <h3 class="fw-bold mb-1 display-6"><?= e($co['name']) ?></h3>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <span class="badge" style="background:var(--pr-primary); color:var(--pr-secondary); padding: 8px 12px; font-size: 0.85rem; letter-spacing: 1px;">
                <?= (int)$co['project_count'] ?> PROPERTIES
              </span>
              <span class="explore-btn text-white opacity-75">
                Explore <i class="fas fa-arrow-right ms-1"></i>
              </span>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<style>
.country-image-card {
  height: 350px;
  border: 1px solid rgba(255,255,255,0.1);
  transition: all 0.4s ease;
  transform: translateZ(0); /* Hardware acceleration */
}
.country-bg-img {
  transition: transform 0.8s ease;
  z-index: 0;
}
.country-card-overlay {
  background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.2) 60%, rgba(0,0,0,0) 100%);
  transition: background 0.4s ease;
  z-index: 1;
}
.country-image-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
  border-color: var(--pr-primary);
}
.country-image-card:hover .country-bg-img {
  transform: scale(1.1);
}
.country-image-card:hover .country-card-overlay {
  background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0) 100%);
}
.explore-btn {
  font-weight: 600;
  transition: all 0.3s ease;
}
.country-image-card:hover .explore-btn {
  opacity: 1 !important;
  color: var(--pr-primary) !important;
  transform: translateX(5px);
}
</style>
