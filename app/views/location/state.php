<?php 
/** State listing view showing cities as premium cards */ 
$cSlug = strtolower($state['country_slug']);
$heroImage = 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop'; // Default
if ($cSlug === 'india') {
    $heroImage = 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?q=80&w=2071&auto=format&fit=crop';
} elseif ($cSlug === 'uae' || $cSlug === 'united-arab-emirates') {
    $heroImage = 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=2070&auto=format&fit=crop';
} elseif ($cSlug === 'usa' || $cSlug === 'united-states') {
    $heroImage = 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?q=80&w=2070&auto=format&fit=crop';
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');
.hero-title { font-family: 'Outfit', sans-serif; letter-spacing: -1px; text-shadow: 0 10px 30px rgba(0,0,0,0.5); }
.city-card { border-radius: 16px; padding: 35px 25px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid rgba(255,255,255,0.8); box-shadow: 0 10px 30px rgba(0,0,0,0.03); background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); }
.city-card-link:hover .city-card { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(229,175,83,0.15); border-color: rgba(229,175,83,0.4); background: #fff; }
.city-card-border { height: 4px; background: linear-gradient(90deg, var(--pr-primary), #d49830); transform: scaleX(0); transform-origin: left; transition: transform 0.4s ease; border-radius: 16px 16px 0 0; }
.city-card-link:hover .city-card-border { transform: scaleX(1); }
.city-icon { width: 55px; height: 55px; border-radius: 14px; background: rgba(229,175,83,0.1); color: var(--pr-primary); font-size: 1.5rem; transition: all 0.4s ease; }
.city-card-link:hover .city-icon { background: linear-gradient(135deg, var(--pr-primary), #d49830); color: white; transform: rotate(10deg) scale(1.1); box-shadow: 0 10px 20px rgba(229,175,83,0.3); }
.city-name { font-family: 'Outfit', sans-serif; font-size: 1.5rem; transition: color 0.3s ease; }
.city-card-link:hover .city-name { color: var(--pr-primary) !important; }
.explore-text { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; transition: all 0.3s ease; color: #64748b; }
.city-card-link:hover .explore-text { color: var(--pr-primary); }
.city-card-link:hover .explore-text i { transform: translateX(8px); }
</style>

<!-- Cinematic Hero Section -->
<div class="position-relative" style="height: 55vh; min-height: 450px; overflow: hidden; margin-top: -1px;">
  <img src="<?= $heroImage ?>" alt="<?= e($state['name']) ?>" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.5) contrast(1.1); transform: scale(1.1); transition: transform 12s cubic-bezier(0.25, 0.46, 0.45, 0.94);" id="heroImg">
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.1) 40%, rgba(0,0,0,0.9) 100%);"></div>
  
  <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center px-3" style="z-index: 2;">
    <div class="badge mb-4 px-4 py-2" style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); font-weight: 400; letter-spacing: 3px; text-transform: uppercase; border-radius: 50px; backdrop-filter: blur(8px);">
      <i class="fas fa-map-marker-alt me-2" style="color: var(--pr-primary);"></i> Premium Real Estate Region
    </div>
    <h1 class="display-2 fw-800 text-white mb-3 hero-title">
      Explore <span style="color: var(--pr-primary); position: relative; display: inline-block;">
        <?= e($state['name']) ?>
        <svg width="100%" height="15" viewBox="0 0 100 15" preserveAspectRatio="none" style="position:absolute; bottom:-5px; left:0; z-index:-1; opacity: 0.7;">
            <path d="M0,10 Q50,0 100,10" stroke="var(--pr-primary)" stroke-width="4" fill="none"/>
        </svg>
      </span>
    </h1>
    <p class="lead text-white-50 mb-0" style="max-width: 700px; font-weight: 400; font-size: 1.15rem; line-height: 1.6;">
      Discover exclusive luxury projects, premium developments, and elite real estate opportunities across <?= e($state['name']) ?>, <?= e($state['country_name']) ?>.
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
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($state['country_slug']) ?>" class="text-decoration-none text-muted"><?= e($state['country_name']) ?></a></li>
        <li class="breadcrumb-item active text-dark fw-bold"><?= e($state['name']) ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="container-fluid px-3 px-md-5 py-5" style="background-color: #f4f6f9; position: relative;">

  <!-- Premium Ad Banner shifted to top -->
  <div style="max-width: 1400px; margin: 0 auto; position: relative; z-index: 5; margin-bottom: 40px;">
    <?php require __DIR__ . '/../partials/_advertise_banner.php'; ?>
  </div>

  <div class="text-center mb-5 pt-2">
    <span style="color: var(--pr-primary); font-weight: 800; font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase;">Discover By City</span>
    <h2 class="fw-800 mb-3 text-dark mt-2" style="font-size: 2.5rem; font-family: 'Outfit', sans-serif;">Select a City</h2>
    <p class="text-muted mx-auto" style="max-width: 500px; font-size: 1.05rem;">Choose a city to explore curated projects and luxury developments tailored to your lifestyle.</p>
  </div>

  <!-- Cities Grid (Premium Cards) -->
  <div class="row g-4 mt-2 mb-5 pb-4" style="max-width: 1400px; margin: 0 auto;">
    <?php if ($cities): ?>
      <?php foreach ($cities as $c): ?>
      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <a href="<?= PUBLIC_URL ?>location/<?= e($state['country_slug']) ?>/<?= e($state['slug']) ?>/<?= e($c['slug']) ?>" class="text-decoration-none d-block city-card-link h-100">
          <div class="city-card position-relative overflow-hidden h-100 d-flex flex-column">
            
            <!-- Hover Top Border -->
            <div class="city-card-border position-absolute top-0 start-0 w-100"></div>
            
            <div class="d-flex align-items-start justify-content-between mb-4">
              <div class="city-icon d-flex align-items-center justify-content-center">
                <i class="fas fa-city"></i>
              </div>
              <?php if ($c['project_count'] > 0): ?>
              <span class="badge bg-white text-dark shadow-sm px-3 py-2 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px; border: 1px solid #eaeaea;">
                <?= $c['project_count'] ?> <?= $c['project_count'] == 1 ? 'PROJECT' : 'PROJECTS' ?>
              </span>
              <?php endif; ?>
            </div>
            
            <div class="mt-auto">
                <h4 class="mb-2 fw-800 text-dark city-name"><?= e($c['name']) ?></h4>
                <div class="d-flex align-items-center mt-3 explore-text">
                  Explore Neighborhoods <i class="fas fa-arrow-right ms-2 transition-all"></i>
                </div>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center py-5 my-5 bg-white shadow-sm" style="border-radius: 20px; border: 1px solid rgba(0,0,0,0.05);">
        <div class="text-muted mb-4"><i class="fas fa-building fa-4x" style="color: #e2e8f0;"></i></div>
        <h3 class="fw-800 text-dark" style="font-family: 'Outfit', sans-serif;">No Cities Found</h3>
        <p class="text-muted fs-5">We are currently expanding our portfolio in <?= e($state['name']) ?>.</p>
        <a href="<?= PUBLIC_URL ?>" class="btn btn-primary px-4 py-2 rounded-pill mt-3 fw-bold">Return Home</a>
      </div>
    <?php endif; ?>
  </div>

</div>

<script>
/* Cinematic zoom effect for the hero image */
window.addEventListener('load', function() {
  setTimeout(() => {
    const heroImg = document.getElementById('heroImg');
    if (heroImg) heroImg.style.transform = 'scale(1)';
  }, 100);
});
</script>
