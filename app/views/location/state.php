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
  <?php require __DIR__ . '/../partials/_advertise_banner.php'; ?>

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
