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
    <a href="<?= PUBLIC_URL ?>" class="header-logo d-flex flex-column align-items-start justify-content-center text-decoration-none" aria-label="<?= e($siteName) ?> home" style="gap: 1px; min-height: 40px;">
      <?php if (!empty($headerLogo)): ?>
        <img src="<?= upload($headerLogo) ?>" alt="<?= e($headerTitle ?? $siteName) ?>" style="max-height: 40px; width: auto; object-fit: contain;">
      <?php else: ?>
        <div class="logo-main d-flex align-items-center" style="font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1.5rem; line-height: 1; letter-spacing: -1px; user-select: none;">
          <span style="color: #0f172a;">property</span><span style="color: #eab308;">rubi</span><span style="color: #22c55e;">x</span><span class="logo-dot-com" style="font-size: 0.55rem; font-weight: 700; color: #0f172a; writing-mode: vertical-rl; transform: rotate(180deg); margin-left: 2px; letter-spacing: 0;">.com</span>
        </div>
        <div class="logo-tagline" style="background: #eab308; color: #0f172a; font-family: 'Inter', sans-serif; font-weight: 800; font-size: 0.45rem; padding: 2px 4px; border-radius: 2px; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1;">
          THE <span style="color: #22c55e;">X</span> FACTOR IN PROPERTY SEARCH
        </div>
      <?php endif; ?>
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
