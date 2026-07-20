<?php /** Developer Profile View */ ?>
<div class="breadcrumb-section">
  <div class="container-fluid px-3 px-md-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 py-3">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>developer">Developers</a></li>
        <li class="breadcrumb-item active"><?= e($builder['name']) ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="container py-5" style="max-width: 1100px;">
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

  <div class="row gx-5 mb-5">
    <!-- Left Column: Logo & Highlights -->
    <div class="col-lg-5 mb-4 mb-lg-0">
      <div class="d-flex flex-column align-items-start">
        <?php if ($builder['logo']): ?>
        <div class="mb-4" style="background:white; border-radius:8px; padding:10px; border: 1px solid #eaeaea; display:flex; align-items:center; justify-content:center; width:120px; height:120px;">
          <img src="<?= upload($builder['logo']) ?>" alt="<?= e($builder['name']) ?>" style="max-height:100px; max-width:100px; object-fit:contain;">
        </div>
        <?php else: ?>
        <div class="mb-4 d-flex align-items-center justify-content-center" style="background:#f1f1f1; border-radius:8px; width:120px; height:120px; font-size:2rem; font-weight:bold; color:#555; border: 1px solid #eaeaea;">
          <?= e(substr($builder['name'],0,2)) ?>
        </div>
        <?php endif; ?>

        <h1 class="fw-bold mb-4" style="font-size: 2rem; color: #000; letter-spacing: -0.5px;"><?= e($builder['name']) ?></h1>

        <h5 class="fw-bold mb-3" style="font-size: 1rem; color: #000;">Developer Highlights</h5>
        
        <div class="d-flex gap-5">
          <div>
            <div class="text-decoration-underline mb-1" style="font-size: 0.9rem; font-weight: 500;">Total Projects</div>
            <div class="fw-bold"><?= (int)$builder['total_projects'] ?></div>
          </div>
          <div>
            <div class="text-decoration-underline mb-1" style="font-size: 0.9rem; font-weight: 500;">Total Years</div>
            <div class="fw-bold"><?= (int)$builder['established_year'] ? (date('Y') - $builder['established_year']) : 'N/A' ?></div>
          </div>
          <div>
            <div class="text-decoration-underline mb-1" style="font-size: 0.9rem; font-weight: 500;">Ongoing Projects</div>
            <div class="fw-bold">0</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Description -->
    <div class="col-lg-7">
      <div style="font-size: 0.95rem; line-height: 1.8; color: #444;">
        <?php if (trim($builder['description'])): ?>
          <p style="margin-top: 0;"><?= nl2br(e($builder['description'])) ?></p>
        <?php else: ?>
          <p style="margin-top: 0;"><?= e($builder['name']) ?> is a prominent real estate developer, with a rich legacy spanning over several decades. The company has earned a strong reputation for its innovative approach to residential, commercial, and mixed-use developments.</p>
        <?php endif; ?>
        
        <p>The company's portfolio includes residential complexes, integrated townships, IT parks, and commercial properties across major cities. <?= e($builder['name']) ?> is committed to creating sustainable and modern living spaces, with a focus on design excellence, superior construction quality, and timely delivery.</p>
        <p>The company has a customer-centric approach, consistently striving to enhance the living experience through thoughtful designs, cutting-edge technology, and world-class amenities. With numerous awards and accolades to its name, <?= e($builder['name']) ?> continues to lead the industry by shaping vibrant communities that offer both luxury and affordability.</p>
      </div>
    </div>
  </div>

  <hr style="border-color: #eee; margin-bottom: 40px;">

  <!-- Projects Section -->
  <h2 class="fw-bold mb-4" style="font-size: 2rem; color: #000; letter-spacing: -0.5px;">Projects</h2>

  <?php if ($projects): ?>
  <div class="row g-4 mb-5">
    <?php foreach ($projects as $p): ?>
    <div class="col-lg-6 col-xl-4">
      <?php require __DIR__ . '/../partials/_property_card.php'; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <p class="text-muted mb-5">No projects listed for this developer yet.</p>
  <?php endif; ?>
</div>
