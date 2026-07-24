<!-- ══ MOST POPULAR LINKS ════════════════════════════════════════════════ -->
<?php
$pdo = db();

$countries = $pdo->query("SELECT id, name, slug FROM countries WHERE status='active' ORDER BY sort_order, name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch active cities (regardless of project count)
$allCities = $pdo->query(
    "SELECT c.name AS city_name, c.slug AS city_slug, 
            s.slug AS state_slug, co.id AS country_id, co.slug AS country_slug
     FROM cities c
     JOIN states s ON s.id = c.state_id
     JOIN countries co ON co.id = s.country_id
     WHERE c.status='active'
     ORDER BY c.name ASC"
)->fetchAll(PDO::FETCH_ASSOC);

// Fetch active builders grouped by the country of their listed projects (from before), but we will also fall back to builder's base country if no projects? Actually, the previous query joined projects.
// Let's just use the builder's country_id or project countries.
$allBuilders = $pdo->query(
    "SELECT DISTINCT b.name AS builder_name, b.slug AS builder_slug, co.id AS country_id
     FROM builders b
     JOIN projects p ON p.builder_id = b.id
     JOIN cities c ON p.city_id = c.id
     JOIN states s ON c.state_id = s.id
     JOIN countries co ON s.country_id = co.id
     WHERE b.status='active'
     ORDER BY b.name ASC"
)->fetchAll(PDO::FETCH_ASSOC);

$citiesByCountry = [];
$buildersByCountry = [];

foreach ($countries as $country) {
    $cName = $country['name'];
    $citiesByCountry[$cName] = array_filter($allCities, fn($c) => $c['country_id'] == $country['id']);
    $buildersByCountry[$cName] = array_filter($allBuilders, fn($b) => $b['country_id'] == $country['id']);
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
