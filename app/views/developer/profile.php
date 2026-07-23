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

<div class="container py-4" style="max-width: 1000px;">

  <!-- Premium Ad Banner — same max-width as content below -->
  <?php require __DIR__ . '/../partials/_advertise_banner.php'; ?>

  <!-- Developer Info Card -->
  <div class="row g-0 mb-5" style="background: #fff; border: 1px solid #eaeaea; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">

    <!-- Left: Logo + Highlights -->
    <div class="col-lg-4 d-flex flex-column align-items-center justify-content-center text-center" style="padding: 40px 30px; border-right: 1px solid #f0f0f0;">
      <?php if ($builder['logo']): ?>
      <div style="background: #fff; border-radius: 12px; padding: 15px; border: 1px solid #eaeaea; display: flex; align-items: center; justify-content: center; width: 140px; height: 140px; margin-bottom: 20px;">
        <img src="<?= upload($builder['logo']) ?>" alt="<?= e($builder['name']) ?>" style="max-height: 110px; max-width: 110px; object-fit: contain;">
      </div>
      <?php else: ?>
      <div style="background: #f8f8f8; border-radius: 12px; width: 140px; height: 140px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 800; color: #555; border: 1px solid #eaeaea; margin-bottom: 20px;">
        <?= e(strtoupper(substr($builder['name'], 0, 2))) ?>
      </div>
      <h2 class="fw-bold mb-0" style="font-size: 1.6rem; color: #0f172a; letter-spacing: -0.5px;"><?= e($builder['name']) ?></h2>
      <?php endif; ?>

      <div style="width: 100%; margin-top: 24px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
        <h6 class="fw-bold mb-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #999;">Developer Highlights</h6>
        <div class="d-flex justify-content-center gap-4">
          <div>
            <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;"><?= (int)$builder['total_projects'] ?></div>
            <div style="font-size: 0.75rem; color: #888; font-weight: 500;">Projects</div>
          </div>
          <div style="border-left: 1px solid #eee;"></div>
          <div>
            <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;"><?= (int)$builder['established_year'] ? (date('Y') - $builder['established_year']) : 'N/A' ?></div>
            <div style="font-size: 0.75rem; color: #888; font-weight: 500;">Years</div>
          </div>
          <div style="border-left: 1px solid #eee;"></div>
          <div>
            <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">0</div>
            <div style="font-size: 0.75rem; color: #888; font-weight: 500;">Ongoing</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: Description -->
    <div class="col-lg-8 d-flex align-items-start" style="padding: 40px 35px;">
      <div style="font-size: 0.95rem; line-height: 1.9; color: #555;">
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
