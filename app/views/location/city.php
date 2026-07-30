<?php 
/** 
 * City page — shows ALL projects in the city.
 * Localities are shown as filter tabs (not a separate click-through page).
 * This fixes the bug where projects without locality_id were invisible.
 */
$bannerImg = !empty($city['banner_image']) 
    ? upload($city['banner_image']) 
    : 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=1920&q=80';

$cityBase  = PUBLIC_URL . 'location/' . e($city['country_slug']) . '/' . e($city['state_slug']) . '/' . e($city['slug']);
$activeLocId = (int)($filters['localityId'] ?? 0);
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');
.hero-title { font-family:'Outfit',sans-serif; letter-spacing:-1px; text-shadow:0 10px 30px rgba(0,0,0,0.5); }
/* Sidebar */
.premium-sidebar { background:#fff; border-radius:16px; border:1px solid rgba(0,0,0,0.05); box-shadow:0 10px 30px rgba(0,0,0,0.03); padding:25px; position:sticky; top:100px; }
.filter-title    { font-family:'Outfit',sans-serif; font-size:1.25rem; font-weight:800; color:#1e293b; border-bottom:2px solid rgba(0,0,0,0.05); padding-bottom:15px; margin-bottom:20px; }
.filter-group-label { font-size:0.85rem; text-transform:uppercase; letter-spacing:1px; color:#64748b; font-weight:700; margin-bottom:15px; }
.filter-check { margin-bottom:12px; }
.filter-check .form-check-input { width:1.2em; height:1.2em; border-color:#cbd5e1; cursor:pointer; }
.filter-check .form-check-input:checked { background-color:var(--pr-primary); border-color:var(--pr-primary); }
.filter-check .form-check-label { font-size:0.95rem; color:#334155; cursor:pointer; transition:color .2s; padding-left:5px; }
.filter-check:hover .form-check-label { color:var(--pr-primary); }
.filter-group { margin-bottom:25px; border-bottom:1px dashed rgba(0,0,0,0.05); padding-bottom:25px; }
.filter-group:last-of-type { border-bottom:none; padding-bottom:0; }
.btn-premium { background:linear-gradient(135deg,var(--pr-primary),#d49830); color:#fff; border:none; font-weight:700; letter-spacing:.5px; border-radius:50px; padding:12px 20px; transition:all .3s; box-shadow:0 4px 15px rgba(229,175,83,.3); }
.btn-premium:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(229,175,83,.4); color:#fff; }
.btn-clear { background:transparent; color:#64748b; border:1px solid #cbd5e1; font-weight:600; border-radius:50px; padding:10px 20px; transition:all .3s; }
.btn-clear:hover { background:#f1f5f9; color:#0f172a; }
.empty-state-card { background:#fff; border-radius:20px; border:1px dashed #cbd5e1; padding:60px 30px; box-shadow:0 10px 40px rgba(0,0,0,.02); }
.empty-icon { width:80px; height:80px; background:rgba(229,175,83,.1); color:var(--pr-primary); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2rem; margin:0 auto 25px; }
/* Locality filter pills */
.loc-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 50px;
    font-size: 0.82rem; font-weight: 600;
    text-decoration: none;
    border: 1px solid #dee2e6;
    color: #334155;
    background: #fff;
    transition: all .2s ease;
}
.loc-pill:hover, .loc-pill.active {
    background: var(--pr-primary); border-color: var(--pr-primary);
    color: #fff; text-decoration: none;
    box-shadow: 0 4px 12px rgba(229,175,83,.3);
}
.loc-pill .badge-count {
    background: rgba(0,0,0,0.08); border-radius: 50px;
    padding: 1px 7px; font-size: 0.72rem;
}
.loc-pill.active .badge-count { background: rgba(255,255,255,0.25); }
</style>

<!-- Hero -->
<div class="position-relative" style="height:55vh; min-height:420px; overflow:hidden; margin-top:-1px;">
  <img src="<?= $bannerImg ?>" alt="<?= e($city['name']) ?>" class="w-100 h-100 object-fit-cover"
       style="filter:brightness(0.5) contrast(1.1); transform:scale(1.1); transition:transform 12s cubic-bezier(.25,.46,.45,.94);" id="heroImg">
  <div class="position-absolute top-0 start-0 w-100 h-100"
       style="background:linear-gradient(180deg,rgba(0,0,0,.8) 0%,rgba(0,0,0,.1) 40%,rgba(0,0,0,.9) 100%);"></div>
  <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center px-3" style="z-index:2;">
    <div class="badge mb-4 px-4 py-2"
         style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);font-weight:400;letter-spacing:3px;text-transform:uppercase;border-radius:50px;backdrop-filter:blur(8px);">
      <i class="fas fa-city me-2" style="color:var(--pr-primary);"></i> Premium City Destination
    </div>
    <h1 class="display-2 fw-800 text-white mb-3 hero-title">
      Properties in
      <span style="color:var(--pr-primary); position:relative; display:inline-block;">
        <?= e($city['name']) ?>
        <svg width="100%" height="15" viewBox="0 0 100 15" preserveAspectRatio="none"
             style="position:absolute;bottom:-5px;left:0;z-index:-1;opacity:.7;">
          <path d="M0,10 Q50,0 100,10" stroke="var(--pr-primary)" stroke-width="4" fill="none"/>
        </svg>
      </span>
    </h1>
    <p class="lead text-white-50 mb-0" style="max-width:700px;font-weight:400;font-size:1.1rem;line-height:1.6;">
      <?= e($city['state_name']) ?>, <?= e($city['country_name']) ?>
      &nbsp;·&nbsp; <strong class="text-white"><?= number_format($totalCount) ?> Projects</strong>
    </p>
  </div>
</div>

<!-- Breadcrumb -->
<div class="bg-white border-bottom sticky-top" style="z-index:10;top:76px;box-shadow:0 4px 20px rgba(0,0,0,.03);">
  <div class="container-fluid px-3 px-md-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 py-3" style="font-size:.9rem;font-weight:500;">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-decoration-none text-muted"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location" class="text-decoration-none text-muted">Locations</a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>" class="text-decoration-none text-muted"><?= e($city['country_name']) ?></a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>/<?= e($city['state_slug']) ?>" class="text-decoration-none text-muted"><?= e($city['state_name']) ?></a></li>
        <li class="breadcrumb-item active text-dark fw-bold"><?= e($city['name']) ?></li>
      </ol>
    </nav>
  </div>
</div>

<!-- Main Content -->
<div class="section py-5" style="background:#f8f9fa;">
  <div class="container-fluid px-3 px-md-5">

    <!-- Locality filter pills -->
    <?php if (!empty($localities)): ?>
    <div class="mb-4 pb-3 border-bottom d-flex flex-wrap gap-2 align-items-center">
      <span class="text-muted small fw-600 me-2 text-uppercase" style="letter-spacing:.8px;">Filter by Area:</span>

      <a href="<?= $cityBase ?><?= $filters['type'] ? '?type='.$filters['type'] : '' ?>"
         class="loc-pill <?= !$activeLocId ? 'active' : '' ?>">
        All Areas
        <span class="badge-count"><?= $totalCount ?></span>
      </a>

      <?php foreach ($localities as $loc): 
        $locCount = (int)$loc['project_count'];
        $qstr = http_build_query(array_filter([
            'locality' => $loc['id'],
            'type'     => $filters['type'],
            'status'   => $filters['status'],
            'budget'   => $filters['budget'],
        ]));
      ?>
      <a href="<?= $cityBase ?>?<?= $qstr ?>"
         class="loc-pill <?= ($activeLocId === (int)$loc['id']) ? 'active' : '' ?>">
        <?= e($loc['location_area']) ?>
        <span class="badge-count"><?= $locCount ?></span>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="row g-5">

      <!-- Filters Sidebar -->
      <div class="col-lg-3">
        <div class="premium-sidebar">
          <form method="get">
            <?php if ($activeLocId): ?>
              <input type="hidden" name="locality" value="<?= $activeLocId ?>">
            <?php endif; ?>
            <div class="filter-title">
              <i class="fas fa-sliders-h me-2" style="color:var(--pr-primary)"></i> Refine Search
            </div>

            <div class="filter-group">
              <div class="filter-group-label">Project Type</div>
              <?php foreach (['residential'=>'Residential','commercial'=>'Commercial','plot'=>'Plot'] as $v=>$l): ?>
              <div class="form-check filter-check">
                <input class="form-check-input" type="radio" name="type" value="<?= $v ?>" id="t_<?= $v ?>" <?= ($filters['type']===$v)?'checked':'' ?>>
                <label class="form-check-label" for="t_<?= $v ?>"><?= $l ?></label>
              </div>
              <?php endforeach; ?>
            </div>

            <div class="filter-group">
              <div class="filter-group-label">Status</div>
              <?php foreach (['upcoming'=>'Upcoming','under_construction'=>'Under Construction','ready_to_move'=>'Ready to Move','new_launch'=>'New Launch'] as $v=>$l): ?>
              <div class="form-check filter-check">
                <input class="form-check-input" type="radio" name="status" value="<?= $v ?>" id="s_<?= $v ?>" <?= ($filters['status']===$v)?'checked':'' ?>>
                <label class="form-check-label" for="s_<?= $v ?>"><?= $l ?></label>
              </div>
              <?php endforeach; ?>
            </div>

            <div class="filter-group border-0 pb-0">
              <div class="filter-group-label">Budget</div>
              <?php foreach (['under50l'=>'Under ₹50L','50l-1cr'=>'₹50L – ₹1Cr','1cr-3cr'=>'₹1Cr – ₹3Cr','above3cr'=>'Above ₹3Cr'] as $v=>$l): ?>
              <div class="form-check filter-check">
                <input class="form-check-input" type="radio" name="budget" value="<?= $v ?>" id="b_<?= $v ?>" <?= ($filters['budget']===$v)?'checked':'' ?>>
                <label class="form-check-label" for="b_<?= $v ?>"><?= $l ?></label>
              </div>
              <?php endforeach; ?>
            </div>

            <div class="d-grid gap-3 mt-4 pt-4" style="border-top:1px solid rgba(0,0,0,.05);">
              <button type="submit" class="btn btn-premium">Apply Filters</button>
              <a href="<?= $cityBase ?>" class="btn btn-clear text-center text-decoration-none">Reset All</a>
            </div>
          </form>
        </div>
      </div>

      <!-- Results -->
      <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
          <h2 class="h4 fw-800 mb-0 text-dark" style="font-family:'Outfit',sans-serif;">
            <?= $activeLocId ? e(array_column($localities,'location_area','id')[$activeLocId] ?? $city['name']) : e($city['name']) ?>
            <span class="fw-normal text-muted ms-2" style="font-size:1rem;">(<?= number_format($totalCount) ?> Properties)</span>
          </h2>
        </div>

        <?php if (!empty($projects)): ?>
        <div class="row g-4 mb-5">
          <?php foreach ($projects as $p): ?>
          <div class="col-md-6 col-xl-4">
            <?php require __DIR__ . '/../partials/_property_card.php'; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?= $pager->render() ?>

        <?php else: ?>
        <div class="empty-state-card text-center text-muted mt-2">
          <div class="empty-icon"><i class="fas fa-building"></i></div>
          <h3 class="fw-800 text-dark mb-3" style="font-family:'Outfit',sans-serif;">No Projects Found</h3>
          <p class="mb-4" style="font-size:1.1rem;">No projects match your current filter criteria.</p>
          <a href="<?= $cityBase ?>" class="btn btn-premium px-5">Clear All Filters</a>
        </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<script>
window.addEventListener('load', function() {
  setTimeout(() => {
    const img = document.getElementById('heroImg');
    if (img) img.style.transform = 'scale(1)';
  }, 100);
});
</script>
