<?php 
/** Country view showing states as premium cards */ 
$cSlug = strtolower($country['slug']);
$heroImage = 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop'; // Default
if ($cSlug === 'india') {
    $heroImage = 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?q=80&w=2071&auto=format&fit=crop';
} elseif ($cSlug === 'uae' || $cSlug === 'united-arab-emirates') {
    $heroImage = 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=2070&auto=format&fit=crop';
} elseif ($cSlug === 'usa' || $cSlug === 'united-states') {
    $heroImage = 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?q=80&w=2070&auto=format&fit=crop';
}
?>

<!-- Cinematic Hero Section -->
<div class="position-relative" style="height: 45vh; min-height: 350px; overflow: hidden; margin-top: -1px;">
  <img src="<?= $heroImage ?>" alt="<?= e($country['name']) ?>" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.7); transform: scale(1.05); transition: transform 10s ease-out;" id="heroImg">
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.6) 100%);"></div>
  
  <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center px-3" style="z-index: 2;">
    <span class="badge mb-3 px-4 py-2" style="background: rgba(229,175,83,0.2); color: #e5af53; border: 1px solid rgba(229,175,83,0.3); font-weight: 600; letter-spacing: 2px; text-transform: uppercase; border-radius: 50px; backdrop-filter: blur(4px);">
      Global Destinations
    </span>
    <h1 class="display-3 fw-900 text-white mb-3" style="letter-spacing: -1px; text-shadow: 0 4px 15px rgba(0,0,0,0.3);">
      Explore <span style="color: var(--pr-primary);"><?= e($country['name']) ?></span>
    </h1>
    <p class="lead text-white-50 mb-0" style="max-width: 600px; font-weight: 300;">
      Discover exclusive luxury properties, premium developments, and elite real estate opportunities across <?= e($country['name']) ?>.
    </p>
  </div>
</div>

<!-- Breadcrumb over white -->
<div class="bg-white border-bottom sticky-top" style="z-index: 10; top: 76px;">
  <div class="container-fluid px-3 px-md-4">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 py-3" style="font-size: 0.9rem; font-weight: 500;">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-decoration-none text-muted"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location" class="text-decoration-none text-muted">Locations</a></li>
        <li class="breadcrumb-item active text-dark fw-bold"><?= e($country['name']) ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="container-fluid px-3 px-md-4 py-5" style="background-color: #f8f9fa;">
  
  <!-- Premium Ad Banner -->
  <div class="w-100 mb-5 d-flex justify-content-center">
    <div style="width: 100%; max-width: 1000px; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: space-between; padding: 30px 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-left: 4px solid var(--pr-primary); flex-wrap: wrap; gap: 20px;">
      <div>
        <h2 class="fw-900 mb-1 text-white" style="font-size: 1.8rem; letter-spacing: -0.5px;">ADVERTISE WITH US</h2>
        <p class="mb-0 text-white-50" style="font-weight: 500; font-size: 0.95rem;">Showcase your premium projects to global investors.</p>
      </div>
      <div class="d-flex align-items-center gap-4">
        <h2 class="fw-bold mb-0 text-white d-none d-md-block" style="font-size: 1.8rem; letter-spacing: -0.5px; opacity: 0.8;">property<span style="color:var(--pr-primary);">rubix</span></h2>
        <a href="<?= PUBLIC_URL ?>advertise-with-us" class="btn btn-primary fw-600 px-4 py-2" style="border-radius: 50px; text-decoration: none;">Know More</a>
      </div>
    </div>
  </div>

  <div class="text-center mb-5">
    <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
      <div style="height: 2px; width: 40px; background: var(--pr-primary);"></div>
      <h2 class="fw-900 mb-0 text-dark" style="font-size: 2rem; letter-spacing: -0.5px;">Select a Region</h2>
      <div style="height: 2px; width: 40px; background: var(--pr-primary);"></div>
    </div>
    <p class="text-muted">Choose a state or territory to view listed cities.</p>
  </div>

  <!-- States Grid (Premium Cards) -->
  <div class="row g-4 mt-2" style="max-width: 1400px; margin: 0 auto;">
    <?php if ($states): ?>
      <?php foreach ($states as $s): ?>
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a href="<?= PUBLIC_URL ?>location/<?= e($country['slug']) ?>/<?= e($s['slug']) ?>" class="text-decoration-none d-block state-card-link">
          <div class="state-card bg-white position-relative overflow-hidden" style="border-radius: 12px; padding: 30px 25px; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            
            <!-- Hover Top Border -->
            <div class="state-card-border position-absolute top-0 start-0 w-100" style="height: 3px; background: var(--pr-primary); transform: scaleX(0); transform-origin: left; transition: transform 0.3s ease;"></div>
            
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="state-icon d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 50%; background: rgba(229,175,83,0.1); color: var(--pr-primary); font-size: 1.2rem; transition: all 0.3s ease;">
                <i class="fas fa-map-marked-alt"></i>
              </div>
              <?php if ($s['city_count'] > 0): ?>
              <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-600" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <?= $s['city_count'] ?> <?= $s['city_count'] == 1 ? 'CITY' : 'CITIES' ?>
              </span>
              <?php endif; ?>
            </div>
            
            <h4 class="mb-1 fw-bold text-dark state-name" style="font-size: 1.3rem; transition: color 0.3s ease;"><?= e($s['name']) ?></h4>
            <div class="d-flex align-items-center text-muted mt-3 explore-text" style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease;">
              Explore Cities <i class="fas fa-arrow-right ms-2 transition-all" style="font-size: 0.8rem;"></i>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center py-5">
        <div class="text-muted mb-3"><i class="fas fa-map-signs fa-3x opacity-25"></i></div>
        <h4 class="fw-bold text-dark">No Regions Found</h4>
        <p class="text-muted">We are currently expanding our portfolio in <?= e($country['name']) ?>.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<style>
/* State Card Hover Animations */
.state-card-link:hover .state-card {
  transform: translateY(-8px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
  border-color: rgba(229,175,83,0.3) !important;
}

.state-card-link:hover .state-card-border {
  transform: scaleX(1) !important;
}

.state-card-link:hover .state-icon {
  background: var(--pr-primary) !important;
  color: white !important;
  transform: scale(1.1);
}

.state-card-link:hover .state-name {
  color: var(--pr-primary) !important;
}

.state-card-link:hover .explore-text {
  color: var(--pr-primary) !important;
}

.state-card-link:hover .explore-text i {
  transform: translateX(5px);
}
</style>

<script>
/* Subtle zoom effect for the hero image */
window.addEventListener('load', function() {
  setTimeout(() => {
    const heroImg = document.getElementById('heroImg');
    if (heroImg) heroImg.style.transform = 'scale(1)';
  }, 100);
});
</script>
