<?php /** Developer Profile View */ ?>
<style>
/* Developer Profile Overhaul Styles */
.dev-hero-section {
    position: relative;
    padding: 60px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    overflow: hidden;
    border-radius: 20px;
    margin-bottom: 40px;
    box-shadow: inset 0 0 20px rgba(0,0,0,0.02);
}

.dev-hero-bg-shapes {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    z-index: 0;
    opacity: 0.4;
    pointer-events: none;
}

.dev-shape-1 {
    position: absolute;
    top: -100px; right: -50px;
    width: 300px; height: 300px;
    background: radial-gradient(circle, var(--pr-primary) 0%, transparent 70%);
    opacity: 0.15;
    border-radius: 50%;
}

.dev-shape-2 {
    position: absolute;
    bottom: -150px; left: -50px;
    width: 400px; height: 400px;
    background: radial-gradient(circle, #f39c12 0%, transparent 70%);
    opacity: 0.1;
    border-radius: 50%;
}

.dev-content-wrapper {
    position: relative;
    z-index: 1;
}

.dev-logo-container {
    width: 120px;
    height: 120px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.dev-logo-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.dev-logo-container img {
    max-height: 80px;
    max-width: 80px;
    object-fit: contain;
}

.dev-logo-fallback {
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--pr-primary) 0%, #1a1a2e 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.dev-title {
    font-size: 2.8rem;
    font-weight: 800;
    color: #1a1a2e;
    letter-spacing: -1px;
    margin-bottom: 15px;
    line-height: 1.2;
}

.dev-metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.dev-metric-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}

.dev-metric-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    border-color: rgba(255, 255, 255, 0.9);
}

.dev-metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(var(--pr-primary-rgb), 0.1) 0%, rgba(var(--pr-primary-rgb), 0.05) 100%);
    color: var(--pr-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.dev-metric-content {
    display: flex;
    flex-direction: column;
}

.dev-metric-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1a1a2e;
    line-height: 1.1;
}

.dev-metric-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 4px;
}

.dev-description-card {
    background: #fff;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.04);
    border: 1px solid rgba(0,0,0,0.02);
    font-size: 1.05rem;
    line-height: 1.8;
    color: #4a4a5a;
}

.dev-description-card p:last-child {
    margin-bottom: 0;
}

.dev-description-card strong {
    color: #1a1a2e;
}

@media (max-width: 768px) {
    .dev-title { font-size: 2.2rem; }
    .dev-metrics-grid { grid-template-columns: 1fr; }
    .dev-hero-section { padding: 40px 0; border-radius: 0; }
}
</style>

<div class="breadcrumb-section bg-light border-bottom">
  <div class="container-fluid px-3 px-md-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 py-3 font-medium">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-decoration-none text-primary"><i class="fas fa-home me-1"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>developer" class="text-decoration-none text-primary">Developers</a></li>
        <li class="breadcrumb-item active text-dark fw-bold"><?= e($builder['name']) ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="container py-5" style="max-width: 1200px;">

  <!-- Premium Ad Banner (If applicable) -->
  <div class="mb-5">
    <?php require __DIR__ . '/../partials/_advertise_banner.php'; ?>
  </div>

  <!-- Hero Section (Glassmorphism & Branding) -->
  <div class="dev-hero-section">
      <div class="dev-hero-bg-shapes">
          <div class="dev-shape-1"></div>
          <div class="dev-shape-2"></div>
      </div>
      
      <div class="container dev-content-wrapper px-4 px-md-5">
          <div class="row align-items-center">
              
              <!-- Left: Branding & Metrics -->
              <div class="col-lg-6 mb-5 mb-lg-0 pr-lg-5">
                  <div class="dev-logo-container">
                    <?php if ($builder['logo']): ?>
                      <img src="<?= upload($builder['logo']) ?>" alt="<?= e($builder['name']) ?>">
                    <?php else: ?>
                      <span class="dev-logo-fallback"><?= e(strtoupper(substr($builder['name'], 0, 2))) ?></span>
                    <?php endif; ?>
                  </div>
                  
                  <h1 class="dev-title"><?= e($builder['name']) ?></h1>
                  <p class="text-muted mb-0 fs-5">Leading the way in premium real estate development.</p>

                  <!-- Interactive Trust Metrics -->
                  <div class="dev-metrics-grid">
                      <div class="dev-metric-card">
                          <div class="dev-metric-icon"><i class="fas fa-building"></i></div>
                          <div class="dev-metric-content">
                              <span class="dev-metric-value"><?= count($projects) ?></span>
                              <span class="dev-metric-label">Total Projects</span>
                          </div>
                      </div>
                      
                      <div class="dev-metric-card">
                          <div class="dev-metric-icon"><i class="fas fa-award"></i></div>
                          <div class="dev-metric-content">
                              <span class="dev-metric-value"><?= (int)$builder['established_year'] ? (date('Y') - $builder['established_year']) : 'N/A' ?></span>
                              <span class="dev-metric-label">Years of Trust</span>
                          </div>
                      </div>
                      
                      <div class="dev-metric-card">
                          <div class="dev-metric-icon"><i class="fas fa-hard-hat"></i></div>
                          <div class="dev-metric-content">
                              <span class="dev-metric-value"><?= count(array_filter($projects, fn($p) => in_array($p['status'], ['under_construction', 'new_launch']))) ?></span>
                              <span class="dev-metric-label">Ongoing</span>
                          </div>
                      </div>
                  </div>
              </div>
              
              <!-- Right: Description Card -->
              <div class="col-lg-6 pl-lg-4">
                  <div class="dev-description-card">
                    <?php if (trim($builder['description'])): ?>
                      <p><strong><?= e($builder['name']) ?></strong> <?= nl2br(e($builder['description'])) ?></p>
                    <?php else: ?>
                      <p><strong><?= e($builder['name']) ?></strong> is a prominent real estate developer, with a rich legacy spanning over several decades. The company has earned a strong reputation for its innovative approach to residential, commercial, and mixed-use developments.</p>
                      <p>The company's portfolio includes residential complexes, integrated townships, IT parks, and commercial projects across major cities. <strong><?= e($builder['name']) ?></strong> is committed to creating sustainable and modern living spaces, with a focus on design excellence, superior construction quality, and timely delivery.</p>
                      <p>Consistently striving to enhance the living experience through thoughtful designs, cutting-edge technology, and world-class amenities. With numerous awards and accolades to its name, the company continues to lead the industry by shaping vibrant communities that offer both luxury and affordability.</p>
                    <?php endif; ?>
                  </div>
              </div>
              
          </div>
      </div>
  </div>

  <div class="d-flex align-items-center justify-content-between mb-4 mt-5">
      <h2 class="fw-bold mb-0" style="font-size: 2.2rem; color: #1a1a2e; letter-spacing: -0.5px;">
          <i class="fas fa-city text-primary me-2"></i> Featured Projects
      </h2>
      <div class="d-none d-md-block">
          <span class="badge bg-light text-dark border px-3 py-2 fs-6 rounded-pill">
              <?= count($projects) ?> properties found
          </span>
      </div>
  </div>

  <?php if ($projects): ?>
  <div class="row g-4 mb-5">
    <?php foreach ($projects as $p): ?>
    <div class="col-lg-6 col-xl-4">
      <?php require __DIR__ . '/../partials/_property_card.php'; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="text-center py-5 bg-light rounded-3 border border-dashed">
      <i class="fas fa-search-location fa-3x text-muted mb-3 opacity-50"></i>
      <h4 class="fw-bold text-dark mb-1">No Projects Found</h4>
      <p class="text-muted">There are currently no listed projects for this developer.</p>
  </div>
  <?php endif; ?>
</div>
