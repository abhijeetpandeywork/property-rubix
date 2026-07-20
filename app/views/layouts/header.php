<?php
/**
 * Sticky Header
 */
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (!function_exists('isActive')) {
    function isActive(string $path): string {
        global $currentPath;
        return str_starts_with($currentPath, $path) ? 'active' : '';
    }
}
$siteName = getSetting('site_name') ?: 'PropertyRubix';
?>
<header class="site-header" id="siteHeader" style="background:#fff; box-shadow:0 2px 10px rgba(0,0,0,0.1); border-bottom: 1px solid #eaeaea;">
  <div class="header-inner container-fluid px-3 px-md-4 justify-content-between">

    <!-- Logo -->
    <a href="<?= PUBLIC_URL ?>" class="header-logo" aria-label="<?= e($siteName) ?> home">
      <?php 
        if (isset($headerLogo) && !empty($headerLogo)):
      ?>
        <img src="<?= upload($headerLogo) ?>" alt="Logo" style="height: 44px; width: auto; object-fit: contain;">
      <?php elseif (isset($headerTitle) && !empty($headerTitle)): ?>
        <h2 class="logo-text fw-bold mb-0 text-dark" style="font-size:1.5rem; letter-spacing:-0.5px;"><?= e($headerTitle) ?></h2>
      <?php else: 
        $siteLogo = getSetting('site_logo') ?: ($branding['logo'] ?? '');
        if (!empty($siteLogo)): 
      ?>
        <img src="<?= upload($siteLogo) ?>" alt="<?= e($siteName) ?>" style="height: 44px; width: auto; object-fit: contain;">
      <?php else: ?>
        <h2 class="logo-text fw-bold mb-0 text-dark" style="font-size:1.8rem; letter-spacing:-0.5px;">property<span style="color:var(--pr-primary);">rubix</span></h2>
      <?php 
        endif; 
      endif;
      ?>
    </a>

    <!-- Right actions -->
    <div class="header-actions d-flex align-items-center gap-3">
      <?php
      $currentCountryName = 'Global'; // Default
      $uri = $_SERVER['REQUEST_URI'] ?? '';
      if (preg_match('#/location/([^/?]+)#', $uri, $m)) {
          $slug = strtolower($m[1]);
          $countryMap = [
              'india' => 'India',
              'uae' => 'UAE',
              'usa' => 'USA',
              'canada' => 'Canada',
              'uk' => 'UK'
          ];
          if (isset($countryMap[$slug])) {
              $currentCountryName = $countryMap[$slug];
          }
      }
      ?>
      <!-- Country selector -->
      <div class="dropdown country-selector d-flex align-items-center me-3">
        <button class="btn btn-link text-decoration-none p-0 d-flex flex-column align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="line-height:1.2; color: #000;">
          <i class="fas fa-globe fs-4 mb-1"></i> 
          <span class="fw-bold" style="font-size:11px;"><?= $currentCountryName ?></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg">
          <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>location/india">🇮🇳 India</a></li>
          <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>location/uae">🇦🇪 UAE</a></li>
          <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>location/usa">🇺🇸 USA</a></li>
          <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>location/canada">🇨🇦 Canada</a></li>
          <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>location/uk">🇬🇧 UK</a></li>
        </ul>
      </div>

      <!-- Hamburger -->
      <button class="hamburger-btn" id="drawerToggle" aria-label="Open menu" style="background: transparent; border: none; padding: 0; display: flex; flex-direction: column; gap: 6px; cursor: pointer; width: 32px;">
        <span style="background-color: #000; height: 3px; width: 100%; border-radius: 2px;"></span>
        <span style="background-color: #000; height: 3px; width: 100%; border-radius: 2px;"></span>
        <span style="background-color: #000; height: 3px; width: 100%; border-radius: 2px;"></span>
      </button>
    </div>

  </div>

</header>
