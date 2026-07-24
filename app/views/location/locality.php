<?php 
/** Locality listing view with project cards + filters */ 
$bannerImg = $city['banner_image'] ? upload($city['banner_image']) : 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=1920&q=80';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');
.hero-title { font-family: 'Outfit', sans-serif; letter-spacing: -1px; text-shadow: 0 10px 30px rgba(0,0,0,0.5); }
.premium-sidebar { background: #fff; border-radius: 16px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.03); padding: 25px; position: sticky; top: 100px; }
.filter-title { font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 800; color: #1e293b; border-bottom: 2px solid rgba(0,0,0,0.05); padding-bottom: 15px; margin-bottom: 20px; }
.filter-group-label { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 700; margin-bottom: 15px; }
.filter-check { margin-bottom: 12px; }
.filter-check .form-check-input { width: 1.2em; height: 1.2em; border-color: #cbd5e1; cursor: pointer; }
.filter-check .form-check-input:checked { background-color: var(--pr-primary); border-color: var(--pr-primary); }
.filter-check .form-check-label { font-size: 0.95rem; color: #334155; cursor: pointer; transition: color 0.2s; padding-left: 5px; }
.filter-check:hover .form-check-label { color: var(--pr-primary); }
.filter-group { margin-bottom: 25px; border-bottom: 1px dashed rgba(0,0,0,0.05); padding-bottom: 25px; }
.filter-group:last-of-type { border-bottom: none; padding-bottom: 0; }
.btn-premium { background: linear-gradient(135deg, var(--pr-primary), #d49830); color: #fff; border: none; font-weight: 700; letter-spacing: 0.5px; border-radius: 50px; padding: 12px 20px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(229,175,83,0.3); }
.btn-premium:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(229,175,83,0.4); color: #fff; }
.btn-clear { background: transparent; color: #64748b; border: 1px solid #cbd5e1; font-weight: 600; border-radius: 50px; padding: 10px 20px; transition: all 0.3s ease; }
.btn-clear:hover { background: #f1f5f9; color: #0f172a; }
.empty-state-card { background: #fff; border-radius: 20px; border: 1px dashed #cbd5e1; padding: 60px 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.02); }
.empty-icon { width: 80px; height: 80px; background: rgba(229,175,83,0.1); color: var(--pr-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 25px; }
</style>

<!-- Cinematic Hero Section -->
<div class="position-relative" style="height: 50vh; min-height: 400px; overflow: hidden; margin-top: -1px;">
  <img src="<?= $bannerImg ?>" alt="<?= e($locality) ?>" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.5) contrast(1.1); transform: scale(1.1); transition: transform 12s cubic-bezier(0.25, 0.46, 0.45, 0.94);" id="heroImg">
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.1) 40%, rgba(0,0,0,0.9) 100%);"></div>
  
  <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center px-3" style="z-index: 2;">
    <div class="badge mb-4 px-4 py-2" style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); font-weight: 400; letter-spacing: 3px; text-transform: uppercase; border-radius: 50px; backdrop-filter: blur(8px);">
      <i class="fas fa-map-marked-alt me-2" style="color: var(--pr-primary);"></i> Premium Neighborhood
    </div>
    <h1 class="display-3 fw-800 text-white mb-3 hero-title">
      Projects in <span style="color: var(--pr-primary); position: relative; display: inline-block;">
        <?= e($locality) ?>
        <svg width="100%" height="12" viewBox="0 0 100 15" preserveAspectRatio="none" style="position:absolute; bottom:-2px; left:0; z-index:-1; opacity: 0.7;">
            <path d="M0,10 Q50,0 100,10" stroke="var(--pr-primary)" stroke-width="4" fill="none"/>
        </svg>
      </span>
    </h1>
    <p class="lead text-white-50 mb-0" style="max-width: 600px; font-weight: 400; font-size: 1.1rem; line-height: 1.6;">
      <?= e($city['name']) ?>, <?= e($city['state_name']) ?>
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
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>/<?= e($city['state_slug']) ?>" class="text-decoration-none text-muted"><?= e($city['state_name']) ?></a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>location/<?= e($city['country_slug']) ?>/<?= e($city['state_slug']) ?>/<?= e($city['slug']) ?>" class="text-decoration-none text-muted"><?= e($city['name']) ?></a></li>
        <li class="breadcrumb-item active text-dark fw-bold"><?= e($locality) ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="section py-5" style="background:#f8f9fa;">
  <div class="container-fluid px-3 px-md-5">
    <div class="row g-5">
      
      <!-- Filters Sidebar -->
      <div class="col-lg-3">
        <div class="premium-sidebar">
          <form method="get">
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
            
            <div class="filter-group border-0 pb-0">
              <div class="filter-group-label">Construction Status</div>
              <?php foreach (['upcoming'=>'Upcoming','under_construction'=>'Under Construction','ready_to_move'=>'Ready to Move'] as $v=>$l): ?>
              <div class="form-check filter-check">
                <input class="form-check-input" type="radio" name="status" value="<?= $v ?>" id="s_<?= $v ?>" <?= ($filters['status']===$v)?'checked':'' ?>>
                <label class="form-check-label" for="s_<?= $v ?>"><?= $l ?></label>
              </div>
              <?php endforeach; ?>
            </div>
            
            <div class="d-grid gap-3 mt-4 pt-4" style="border-top: 1px solid rgba(0,0,0,0.05);">
              <button type="submit" class="btn btn-premium">Apply Filters</button>
              <a href="?" class="btn btn-clear text-center text-decoration-none">Reset All</a>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Results Area -->
      <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
          <h2 class="h4 fw-800 mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">
            <?= e($locality) ?> <span class="fw-normal text-muted ms-2" style="font-size: 1.1rem;">(<?= number_format($pager->total) ?> Projects)</span>
          </h2>
        </div>
        
        <?php if ($projects): ?>
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
          <div class="empty-icon">
            <i class="fas fa-building"></i>
          </div>
          <h3 class="fw-800 text-dark mb-3" style="font-family: 'Outfit', sans-serif;">No Projects Found</h3>
          <p class="mb-4" style="font-size: 1.1rem;">We couldn't find any exclusive projects in <?= e($locality) ?> matching your exact criteria.</p>
          <a href="?" class="btn btn-premium px-5">Clear All Filters</a>
        </div>
        <?php endif; ?>
      </div>
      
    </div>
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
