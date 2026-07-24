<?php /** Advanced Luxury Project Listing View */ ?>

<style>
/* --- Premium Search Header --- */
.listing-hero {
    background: linear-gradient(135deg, #111 0%, #2a2a2a 100%);
    padding: 60px 0;
    position: relative;
    overflow: hidden;
    color: white;
}
.listing-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075&auto=format&fit=crop') center/cover;
    opacity: 0.15;
}
.hero-content {
    position: relative;
    z-index: 2;
}

/* --- Advanced Filter Sidebar --- */
.adv-filter-sidebar {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    padding: 25px;
    border: 1px solid rgba(0,0,0,0.03);
    position: sticky;
    top: 90px;
}
.filter-section-title {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
    color: #888;
    margin-bottom: 15px;
    display: block;
}

/* Custom Radio Pills */
.custom-radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.custom-radio-group input[type="radio"] {
    display: none;
}
.custom-radio-group label {
    padding: 8px 16px;
    border: 1px solid #e0e0e0;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #555;
    background: #fbfbfb;
}
.custom-radio-group input[type="radio"]:checked + label {
    background: var(--pr-primary);
    color: #fff;
    border-color: var(--pr-primary);
    box-shadow: 0 4px 10px rgba(229,175,83,0.3);
}
.custom-radio-group label:hover {
    border-color: var(--pr-primary);
    color: var(--pr-primary);
}

/* --- Luxury Property Cards --- */
.lux-property-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--glass-shadow);
    border: var(--glass-border);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.lux-property-card:hover {
    transform: translateY(-10px);
    background: var(--glass-bg-hover);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border-color: rgba(229,175,83,0.5);
}
.lux-img-wrapper {
    position: relative;
    height: 240px;
    overflow: hidden;
}
.lux-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.lux-property-card:hover .lux-img-wrapper img {
    transform: scale(1.08);
}
.lux-badges {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    gap: 8px;
    z-index: 2;
}
.lux-badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(5px);
}
.lux-badge-status {
    background: rgba(0,0,0,0.7);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.2);
}
.lux-badge-type {
    background: rgba(229,175,83,0.9);
    color: #111;
}
.lux-card-body {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.lux-title {
    font-size: 1.3rem;
    font-weight: 800;
    color: #111;
    margin-bottom: 5px;
    text-decoration: none;
    transition: color 0.2s;
}
.lux-property-card:hover .lux-title {
    color: var(--pr-primary);
}
.lux-location {
    font-size: 0.9rem;
    color: #777;
    margin-bottom: 15px;
}
.lux-price {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--pr-primary);
    margin-bottom: 15px;
}
.lux-meta {
    display: flex;
    gap: 15px;
    font-size: 0.85rem;
    color: #555;
    font-weight: 600;
    border-top: 1px solid #eee;
    padding-top: 15px;
    margin-top: auto;
}
.lux-footer {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

/* Control Bar */
.control-bar {
    background: #fff;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    border: 1px solid #eaeaea;
    margin-bottom: 20px;
}
</style>

<!-- Premium Search Header -->
<div class="listing-hero">
  <div class="container-fluid px-3 px-md-5 hero-content text-center">
    <nav aria-label="breadcrumb" class="mb-3 d-flex justify-content-center">
      <ol class="breadcrumb mb-0" style="font-size: 0.9rem; font-weight: 500;">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-white fw-bold">Explore Projects</li>
      </ol>
    </nav>
    <h1 class="display-4 fw-900 mb-2">
      <?= $filters['q'] ? 'Results for <span style="color:var(--pr-primary);">"' . e($filters['q']) . '"</span>' : 'Discover <span style="color:var(--pr-primary);">Premium</span> Real Estate' ?>
    </h1>
    <p class="lead text-white-50 mb-0" style="max-width: 600px; margin: 0 auto;">
      Find your dream home or next investment opportunity among our curated luxury projects.
    </p>
  </div>
</div>

<div class="container-fluid px-3 px-md-5 py-5" style="background: #fcfcfc;">
  <div class="row g-4">

    <!-- Advanced Filter Sidebar -->
    <div class="col-lg-3">
      <div class="adv-filter-sidebar">
        <form method="get">
          <h4 class="fw-800 mb-4 pb-3 border-bottom" style="font-size: 1.2rem; color: #111;">
            <i class="fas fa-sliders-h me-2" style="color:var(--pr-primary);"></i> Filter Projects
          </h4>

          <!-- Keyword Search -->
          <div class="mb-4">
            <span class="filter-section-title">Keyword Search</span>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                <input type="text" name="q" class="form-control border-start-0 ps-0 shadow-none" placeholder="City, developer, project..." value="<?= e($filters['q']) ?>">
            </div>
          </div>

          <!-- Property Type (Pills) -->
          <div class="mb-4">
            <span class="filter-section-title">Property Type</span>
            <div class="custom-radio-group">
                <input type="radio" name="type" value="" id="type_all" <?= !$filters['type'] ? 'checked' : '' ?>>
                <label for="type_all">All Types</label>
                
                <?php foreach (['residential' => 'Residential', 'commercial' => 'Commercial', 'plot' => 'Plot'] as $val => $lbl): ?>
                <input type="radio" name="type" value="<?= $val ?>" id="type_<?= $val ?>" <?= $filters['type'] === $val ? 'checked' : '' ?>>
                <label for="type_<?= $val ?>"><?= $lbl ?></label>
                <?php endforeach; ?>
            </div>
          </div>

          <!-- Project Status (Pills) -->
          <div class="mb-4">
            <span class="filter-section-title">Project Status</span>
            <div class="custom-radio-group">
                <input type="radio" name="status" value="" id="status_all" <?= !$filters['status'] ? 'checked' : '' ?>>
                <label for="status_all">All Status</label>
                
                <?php foreach (['upcoming' => 'Upcoming', 'under_construction' => 'Under Construction', 'ready_to_move' => 'Ready to Move', 'new_launch' => 'New Launch'] as $val => $lbl): ?>
                <input type="radio" name="status" value="<?= $val ?>" id="status_<?= $val ?>" <?= $filters['status'] === $val ? 'checked' : '' ?>>
                <label for="status_<?= $val ?>"><?= $lbl ?></label>
                <?php endforeach; ?>
            </div>
          </div>

          <!-- Budget (Pills) -->
          <div class="mb-4">
            <span class="filter-section-title">Budget Limit</span>
            <div class="custom-radio-group">
                <input type="radio" name="budget" value="" id="budget_all" <?= !$filters['budget'] ? 'checked' : '' ?>>
                <label for="budget_all">Any Price</label>
                
                <?php foreach (['under50l' => 'Under ₹50 Lakh', '50l-1cr' => '₹50L – ₹1 Cr', '1cr-3cr' => '₹1 Cr – ₹3 Cr', 'above3cr' => 'Above ₹3 Cr'] as $val => $lbl): ?>
                <input type="radio" name="budget" value="<?= $val ?>" id="budget_<?= $val ?>" <?= $filters['budget'] === $val ? 'checked' : '' ?>>
                <label for="budget_<?= $val ?>"><?= $lbl ?></label>
                <?php endforeach; ?>
            </div>
          </div>

          <!-- City -->
          <div class="mb-5">
            <span class="filter-section-title">Location (City)</span>
            <select name="city" class="form-select shadow-none border" style="cursor: pointer;">
              <option value="">Worldwide (All Cities)</option>
              <?php foreach ($cities as $city): ?>
              <option value="<?= $city['id'] ?>" <?= $filters['cityId'] == $city['id'] ? 'selected' : '' ?>>
                <?= e($city['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm" style="border-radius: 6px;">Apply Filters</button>
            <a href="<?= PUBLIC_URL ?>projects" class="btn text-muted fw-bold py-2" style="background:#f5f5f5; border-radius: 6px;">Clear Filters</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Results Section -->
    <div class="col-lg-9">
        
      <!-- Control Bar -->
      <div class="control-bar d-flex align-items-center justify-content-between flex-wrap gap-3">
        <p class="mb-0 fw-600 text-dark">
          Showing <span style="color:var(--pr-primary); font-size:1.1rem;"><?= number_format($total) ?></span> highly curated projects
        </p>
        <form method="get" class="d-flex align-items-center gap-3">
          <?php foreach ($filters as $k => $v): if (!$v || $k === 'sort') continue; ?>
          <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
          <?php endforeach; ?>
          <span class="text-muted fw-bold small text-uppercase">Sort By</span>
          <select name="sort" class="form-select form-select-sm shadow-none fw-600 border-0 bg-light" style="width: auto; cursor: pointer; border-radius: 6px; padding: 6px 30px 6px 12px;" onchange="this.form.submit()">
            <option value="featured"   <?= $filters['sort'] === 'featured'   ? 'selected' : '' ?>>Featured</option>
            <option value="newest"     <?= $filters['sort'] === 'newest'     ? 'selected' : '' ?>>Newest Arrivals</option>
            <option value="price_asc"  <?= $filters['sort'] === 'price_asc'  ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price_desc" <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
          </select>
        </form>
      </div>

      <?php if ($projects): ?>
      <div class="row g-4 mb-5">
        <?php foreach ($projects as $p): ?>
        <div class="col-md-6 col-xl-4">
          <?php require __DIR__ . '/../partials/_property_card.php'; ?>
        </div>
        <?php endforeach; ?>
      </div>
      
      <!-- Pagination -->
      <div class="d-flex justify-content-center">
        <?= $pager->render() ?>
      </div>
      
      <?php else: ?>
      <div class="text-center py-5 bg-white shadow-sm" style="border-radius: 12px; border: 1px solid #eaeaea; margin-top: 20px;">
        <div style="width: 80px; height: 80px; background: rgba(229,175,83,0.1); color: var(--pr-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-search fa-2x"></i>
        </div>
        <h3 class="fw-bold text-dark mb-2">No Premium Projects Found</h3>
        <p class="text-muted mb-4" style="max-width: 400px; margin: 0 auto;">We couldn't find any projects matching your exact criteria. Try adjusting your filters or expanding your search area.</p>
        <a href="<?= PUBLIC_URL ?>projects" class="btn btn-primary px-4 py-2 fw-bold" style="border-radius: 50px;">Reset All Filters</a>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>
