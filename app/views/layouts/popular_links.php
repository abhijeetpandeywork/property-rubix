<!-- ══ MOST POPULAR LINKS ════════════════════════════════════════════════ -->
<?php
$pdo = db();

// Fetch active cities that have projects, grouped by country
$popularCities = $pdo->query(
    "SELECT c.name AS city_name, c.slug AS city_slug, 
            s.slug AS state_slug, co.name AS country_name, co.slug AS country_slug,
            COUNT(p.id) as proj_count
     FROM cities c
     JOIN states s ON s.id = c.state_id
     JOIN countries co ON co.id = s.country_id
     JOIN projects p ON p.city_id = c.id
     WHERE c.status='active'
     GROUP BY c.id
     ORDER BY co.sort_order, proj_count DESC"
)->fetchAll(PDO::FETCH_ASSOC);

$citiesByCountry = [];
foreach ($popularCities as $row) {
    $citiesByCountry[$row['country_name']][] = $row;
}

// Fetch active builders grouped by country
$popularBuilders = $pdo->query(
    "SELECT b.name AS builder_name, b.slug AS builder_slug, co.name AS country_name
     FROM builders b
     JOIN countries co ON co.id = b.country_id
     WHERE b.status='active'
     ORDER BY co.sort_order, b.name ASC"
)->fetchAll(PDO::FETCH_ASSOC);

$buildersByCountry = [];
foreach ($popularBuilders as $row) {
    $buildersByCountry[$row['country_name']][] = $row;
}
?>

<section style="background-color: #e5af53; padding: 70px 0; min-height: 50vh;">
  <div class="container-fluid px-3 px-md-5">
    <h2 class="fw-bold mb-5 text-dark" style="font-size: 2.2rem; letter-spacing: -0.5px;">Most Popular Links</h2>
    
    <div class="row text-dark mb-5">
      <?php if (!empty($citiesByCountry)): ?>
        <?php foreach ($citiesByCountry as $countryName => $cities): ?>
          <div class="col-12 mb-4">
            <h5 class="fw-bold mb-3 text-dark" style="font-size: 1.05rem; text-decoration: underline;">Real Estate in <?= e($countryName) ?></h5>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px 15px; font-size: 0.9rem;">
              <?php foreach ($cities as $city): ?>
                <a href="<?= PUBLIC_URL ?>location/<?= urlencode($city['country_slug']) ?>/<?= urlencode($city['state_slug']) ?>/<?= urlencode($city['city_slug']) ?>" class="text-dark text-decoration-none">
                  <?= e($city['city_name']) ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
          <div class="col-12"><p>No locations available yet.</p></div>
      <?php endif; ?>
    </div>
    
    <!-- Developers section -->
    <div class="row text-dark">
      <?php if (!empty($buildersByCountry)): ?>
        <?php foreach ($buildersByCountry as $countryName => $builders): ?>
          <div class="col-12 mb-2 mt-2">
            <h5 class="fw-bold text-dark m-0" style="font-size: 1.05rem; text-decoration: underline;">Developers in <?= e($countryName) ?></h5>
          </div>
          <div class="col-12 mb-4">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; font-size: 0.9rem;">
              <?php foreach ($builders as $builder): ?>
                <a href="<?= PUBLIC_URL ?>developer/<?= urlencode($builder['builder_slug']) ?>" class="text-dark text-decoration-none">
                  <?= e($builder['builder_name']) ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
          <div class="col-12"><p>No developers available yet.</p></div>
      <?php endif; ?>
    </div>

  </div>
</section>
