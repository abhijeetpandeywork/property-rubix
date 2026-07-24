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

  <!-- Premium Ad Banner -->
  <?php require __DIR__ . '/../partials/_advertise_banner.php'; ?>

  <!-- Developer Info — Two Column Layout -->
  <div class="row gx-5 mb-5">

    <!-- Left: Logo + Name + Highlights -->
    <div class="col-lg-5 mb-4 mb-lg-0">
      <div class="d-flex flex-column align-items-start">

        <?php if ($builder['logo']): ?>
        <div class="mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
          <img src="<?= upload($builder['logo']) ?>" alt="<?= e($builder['name']) ?>" style="max-height: 80px; max-width: 80px; object-fit: contain;">
        </div>
        <?php else: ?>
        <div class="mb-3 d-flex align-items-center justify-content-center" style="background: #f1f1f1; border-radius: 8px; width: 80px; height: 80px; font-size: 1.8rem; font-weight: bold; color: #555; border: 1px solid #eaeaea;">
          <?= e(strtoupper(substr($builder['name'], 0, 2))) ?>
        </div>
        <?php endif; ?>

        <h1 class="fw-bold mb-4" style="font-size: 2rem; color: #000; letter-spacing: -0.5px; line-height: 1.2;"><?= e($builder['name']) ?></h1>

        <h5 class="fw-bold mb-3" style="font-size: 1rem; color: #000;">Developer Highlights</h5>

        <div class="d-flex gap-5">
          <div>
            <div class="text-decoration-underline mb-1" style="font-size: 0.9rem; font-weight: 500;">Total Projects</div>
            <div class="fw-bold"><?= count($projects) ?></div>
          </div>
          <div>
            <div class="text-decoration-underline mb-1" style="font-size: 0.9rem; font-weight: 500;">Total Years</div>
            <div class="fw-bold"><?= (int)$builder['established_year'] ? (date('Y') - $builder['established_year']) : 'N/A' ?></div>
          </div>
          <div>
            <div class="text-decoration-underline mb-1" style="font-size: 0.9rem; font-weight: 500;">Ongoing Projects</div>
            <div class="fw-bold"><?= count(array_filter($projects, fn($p) => in_array($p['status'], ['under_construction', 'new_launch']))) ?></div>
          </div>
        </div>

      </div>
    </div>

    <!-- Right: Description -->
    <div class="col-lg-7">
      <div style="font-size: 0.95rem; line-height: 1.8; color: #444; text-align: justify;">
        <?php if (trim($builder['description'])): ?>
          <p style="margin-top: 0;"><strong><?= e($builder['name']) ?></strong> <?= nl2br(e($builder['description'])) ?></p>
        <?php else: ?>
          <p style="margin-top: 0;"><strong><?= e($builder['name']) ?></strong> is a prominent real estate developer, with a rich legacy spanning over several decades. The company has earned a strong reputation for its innovative approach to residential, commercial, and mixed-use developments.</p>
        <?php endif; ?>

        <p>The company's portfolio includes residential complexes, integrated townships, IT parks, and commercial projects across major cities. <?= e($builder['name']) ?> is committed to creating sustainable and modern living spaces, with a focus on design excellence, superior construction quality, and timely delivery.</p>
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
