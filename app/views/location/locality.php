<?php /** City listing view with project cards + filters */ ?>
<div class="breadcrumb-section">
  <div class="container-fluid px-3 px-md-4">
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>">Home</a></li>
      <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location">Locations</a></li>
      <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>"><?= e($city['country_name']) ?></a></li>
      <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>/<?= e($city['state_slug']) ?>"><?= e($city['state_name']) ?></a></li>
      <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>/<?= e($city['state_slug']) ?>/<?= e($city['slug']) ?>"><?= e($city['name']) ?></a></li>
      <li class="breadcrumb-item active"><?= e($locality) ?></li>
    </ol></nav>
  </div>
</div>

<!-- City banner -->
<?php if ($city['banner_image']): ?>
<div style="height:260px;overflow:hidden;position:relative">
  <img src="<?= upload($city['banner_image']) ?>" alt="<?= e($city['name']) ?>" style="width:100%;height:100%;object-fit:cover">
  <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,0.7),transparent);display:flex;align-items:flex-end;padding:24px">
    <h1 class="text-white fw-800 mb-0" style="font-size:2rem">Properties in <?= e($locality) ?>, <?= e($city['name']) ?></h1>
  </div>
</div>
<?php else: ?>
<div class="section-sm" style="background:var(--pr-secondary);color:white">
  <div class="container-fluid px-3 px-md-4">
    <h1 class="fw-800 mb-0">Properties in <span style="color:var(--pr-primary)"><?= e($locality) ?></span></h1>
    <p style="opacity:0.7"><?= e($city['name']) ?>, <?= e($city['state_name']) ?></p>
  </div>
</div>
<?php endif; ?>

<div class="section">
  <div class="container-fluid px-3 px-md-4">
    <div class="row g-4">
      <!-- Filters -->
      <div class="col-lg-3">
        <div class="filter-sidebar">
          <form method="get">
            <div class="filter-title"><i class="fas fa-filter" style="color:var(--pr-primary)"></i> Filters</div>
            <div class="filter-group">
              <div class="filter-group-label">Property Type</div>
              <?php foreach (['residential'=>'Residential','commercial'=>'Commercial','plot'=>'Plot'] as $v=>$l): ?>
              <div class="form-check filter-check">
                <input class="form-check-input" type="radio" name="type" value="<?= $v ?>" id="t_<?= $v ?>" <?= ($filters['type']===$v)?'checked':'' ?>>
                <label class="form-check-label" for="t_<?= $v ?>"><?= $l ?></label>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="filter-group">
              <div class="filter-group-label">Status</div>
              <?php foreach (['upcoming'=>'Upcoming','under_construction'=>'Under Construction','ready_to_move'=>'Ready to Move'] as $v=>$l): ?>
              <div class="form-check filter-check">
                <input class="form-check-input" type="radio" name="status" value="<?= $v ?>" id="s_<?= $v ?>" <?= ($filters['status']===$v)?'checked':'' ?>>
                <label class="form-check-label" for="s_<?= $v ?>"><?= $l ?></label>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Apply</button>
              <a href="?" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
          </form>
        </div>
      </div>
      <!-- Results -->
      <div class="col-lg-9">
        <p class="text-muted mb-4"><strong><?= number_format($pager->total) ?></strong> properties in <?= e($locality) ?></p>
        <?php if ($projects): ?>
        <div class="row g-4 mb-4">
          <?php foreach ($projects as $p): ?>
          <div class="col-md-6 col-xl-4">
            <?php require __DIR__ . '/../partials/_property_card.php'; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?= $pager->render() ?>
        <?php else: ?>
        <div class="text-center py-5">
          <i class="fas fa-city" style="font-size:3rem;color:var(--pr-border)"></i>
          <h3 class="mt-3 text-muted">No properties found in <?= e($locality) ?> with selected filters</h3>
          <a href="?" class="btn btn-primary mt-3">Clear Filters</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
