<?php /** State/City listing view showing cities as gray boxes */ ?>
<div class="breadcrumb-section bg-white border-bottom">
  <div class="container-fluid px-3 px-md-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 py-3">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($state['country_slug']) ?>"><?= e($state['country_name']) ?></a></li>
        <li class="breadcrumb-item active"><?= e($state['name']) ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="container-fluid px-3 px-md-5 py-5">
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

  <!-- Search Filter -->
  <div class="mb-5">
    <input type="text" class="form-control form-control-lg" placeholder="Choose City / Filter By Keyword..." style="max-width: 1200px; margin: 0 auto; border: 1px solid #ddd; border-radius: 6px; padding-left: 20px; font-size: 1rem; color: #555;">
  </div>

  <!-- Cities Grid (Gray Boxes) -->
  <div class="row g-3" style="max-width: 1200px; margin: 0 auto;">
    <?php if ($cities): ?>
      <?php foreach ($cities as $c): ?>
      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
        <a href="<?= PUBLIC_URL ?>location/<?= e($state['country_slug']) ?>/<?= e($state['slug']) ?>/<?= e($c['slug']) ?>" class="text-decoration-none d-block">
          <div style="background-color: #d9d9d9; border-radius: 6px; height: 140px; display: flex; align-items: center; justify-content: center; padding: 20px; text-align: center; transition: background-color 0.2s;">
            <h5 class="mb-0 fw-bold" style="color: #333; font-size: 1.05rem;">
              <?= e($c['name']) ?>
              <?php if ($c['project_count'] > 0): ?>
              <div class="mt-2" style="font-size: 0.8rem; font-weight: normal; color: #666;"><?= $c['project_count'] ?> Projects</div>
              <?php endif; ?>
            </h5>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center text-muted">
        No cities listed in this state yet.
      </div>
    <?php endif; ?>
  </div>
</div>

<style>
/* Hover effect for the gray boxes */
.row.g-3 a > div:hover {
  background-color: #c9c9c9 !important;
}
</style>
