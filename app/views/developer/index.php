<?php /** Developer Index */ ?>

<!-- Authoritative Hero Section -->
<div class="position-relative" style="height: 50vh; min-height: 350px; max-height: 450px; overflow: hidden; margin-top: -1px;">
  <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80" 
       alt="Premium Real Estate Developers" class="position-absolute w-100 h-100 object-fit-cover" style="top:0; left:0; z-index:0; filter: brightness(0.4);">
  <div class="position-absolute w-100 h-100" style="top:0; left:0; background: linear-gradient(to top, var(--pr-secondary) 0%, transparent 100%); z-index:1;"></div>
  
  <div class="container-fluid px-3 px-md-5 position-relative z-2 h-100 d-flex flex-column justify-content-center pb-4 text-center text-white">
    <nav aria-label="breadcrumb" class="mb-3 d-flex justify-content-center">
      <ol class="breadcrumb mb-0" style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 50px; backdrop-filter: blur(10px);">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-white text-decoration-none opacity-75">Home</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">Developers</li>
      </ol>
    </nav>
    <h1 class="display-4 fw-bold mb-3">Partnering With the <span style="color:var(--pr-primary)">Finest</span></h1>
    <p class="fs-5 opacity-75 mx-auto" style="max-width: 600px;">Explore a curated directory of the world's most trusted, innovative, and prestigious real estate developers.</p>
  </div>
</div>

<div class="section py-5" style="background:#f8f9fa;">
  <div class="container-fluid px-3 px-md-5">
    <?php foreach ($byCountry as $countryName => $builderList): ?>
    
    <div class="mb-5 pb-4">
      
      <!-- Elegant Country Header -->
      <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
        <h2 class="fw-bold h3 mb-0 me-3 text-dark"><?= e($countryName) ?> <span class="text-muted fw-normal">Developers</span></h2>
        <div class="flex-grow-1" style="height: 1px; background: rgba(0,0,0,0.05);"></div>
      </div>

      <div class="row g-4 justify-content-center">
        <?php foreach ($builderList as $b): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
          <a href="<?= PUBLIC_URL ?>developer/<?= e($b['slug']) ?>" class="premium-brand-plaque text-decoration-none d-flex flex-column align-items-center justify-content-center p-4 bg-white rounded-4 position-relative h-100">
            
            <div class="plaque-logo-wrapper mb-3 d-flex align-items-center justify-content-center">
              <?php if ($b['logo']): ?>
                <img src="<?= upload($b['logo']) ?>" alt="<?= e($b['name']) ?>" class="plaque-logo img-fluid">
              <?php else: ?>
                <div class="plaque-logo-placeholder fw-bold text-uppercase d-flex align-items-center justify-content-center">
                  <?= e(substr($b['name'],0,2)) ?>
                </div>
              <?php endif; ?>
            </div>
            
            <h3 class="fw-bold text-dark text-center mb-2" style="font-size: 1.15rem; line-height: 1.3;"><?= e($b['name']) ?></h3>
            
            <div class="mt-auto">
              <span class="badge rounded-pill fw-normal shadow-sm" style="background:var(--pr-primary); color:var(--pr-secondary); padding: 6px 12px; font-size: 0.8rem; letter-spacing: 1px;">
                <i class="fas fa-building me-1"></i> <?= (int)$b['project_count'] ?> PROJECTS
              </span>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      
    </div>
    <?php endforeach; ?>
  </div>
</div>

<style>
/* Premium Brand Plaque */
.premium-brand-plaque {
  border: 1px solid rgba(0,0,0,0.05);
  box-shadow: 0 4px 15px rgba(0,0,0,0.02);
  transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  overflow: hidden;
}
.premium-brand-plaque::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  border-radius: inherit;
  box-shadow: 0 15px 35px rgba(0,0,0,0.1);
  opacity: 0;
  transition: opacity 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  pointer-events: none;
}
.premium-brand-plaque::after {
  content: '';
  position: absolute;
  top: 0; left: 0; width: 100%; height: 4px;
  background: var(--pr-primary);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.4s ease;
}

/* Plaque Logo */
.plaque-logo-wrapper {
  height: 90px;
  width: 100%;
}
.plaque-logo {
  max-height: 100%;
  max-width: 80%;
  object-fit: contain;
  transition: transform 0.4s ease;
  filter: grayscale(20%);
}
.plaque-logo-placeholder {
  width: 70px; height: 70px;
  background: var(--pr-secondary);
  color: var(--pr-primary);
  border-radius: 50%;
  font-size: 1.5rem;
  letter-spacing: 2px;
  transition: transform 0.4s ease;
}

/* Hover States */
.premium-brand-plaque:hover {
  transform: translateY(-8px);
  border-color: transparent;
}
.premium-brand-plaque:hover::before {
  opacity: 1;
}
.premium-brand-plaque:hover::after {
  transform: scaleX(1);
}
.premium-brand-plaque:hover .plaque-logo {
  transform: scale(1.08);
  filter: grayscale(0%);
}
.premium-brand-plaque:hover .plaque-logo-placeholder {
  transform: scale(1.08);
  background: var(--pr-primary);
  color: var(--pr-secondary);
}
.premium-brand-plaque:hover h3 {
  color: var(--pr-primary) !important;
}
</style>
