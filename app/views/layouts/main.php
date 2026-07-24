<?php
/**
 * Main layout — wraps all public views.
 * $content is set by Controller::view()
 * $pageTitle, $metaDesc, $metaKeywords can be set in the view via $data
 */

require_once APP_PATH . 'helpers/settings.php';
$branding = getBranding();
$phone    = getSetting('phone_primary', '+91 98765 43210');
$wa       = getSetting('whatsapp_number', '919876543210');
$siteName = $branding['site_name'] ?? 'PropertyRubix';

$pageTitle  = isset($pageTitle)  ? e($pageTitle) . ' | ' . $siteName : $siteName . ' — ' . ($branding['tagline'] ?? 'Find Your Perfect Property');
$metaDesc   = isset($metaDesc)   ? e($metaDesc)  : 'Discover verified residential, commercial & plot projects across India, UAE, USA and Canada.';
$canonicalUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<meta name="description" content="<?= $metaDesc ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= e($canonicalUrl) ?>">

<!-- Open Graph -->
<meta property="og:title"       content="<?= $pageTitle ?>">
<meta property="og:description" content="<?= $metaDesc ?>">
<meta property="og:type"        content="website">
<meta property="og:url"         content="<?= e($canonicalUrl) ?>">
<meta property="og:site_name"   content="<?= e($siteName) ?>">

<!-- Favicon -->
<link rel="icon" href="<?= asset('img/favicon.svg') ?>" type="image/svg+xml">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Swiper -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=<?= @filemtime(__DIR__ . '/../../../assets/css/style.css') ?>">

<!-- Dynamic brand colors -->
<style>
:root {
    --pr-primary:   #f7cb46;
    --pr-secondary: <?= e($branding['secondary_color'] ?? '#0f172a') ?>;
}
</style>

<?= isset($extraHead) ? $extraHead : '' ?>
</head>
<body class="mesh-bg <?= isset($bodyClass) ? e($bodyClass) : '' ?>">

<!-- ══ DRAWER OVERLAY ══════════════════════════════════════════════════════ -->
<div class="drawer-overlay" id="drawerOverlay" aria-hidden="true"></div>

<!-- ══ SLIDE-IN DRAWER ═════════════════════════════════════════════════════ -->
<?php require __DIR__ . '/drawer.php'; ?>

<!-- ══ HEADER ══════════════════════════════════════════════════════════════ -->
<?php require __DIR__ . '/header.php'; ?>

<!-- ══ MAIN CONTENT ════════════════════════════════════════════════════════ -->
<main id="main-content">
<?= $content ?>
</main>

<!-- ══ POPULAR LINKS ═══════════════════════════════════════════════════════ -->
<?php require __DIR__ . '/popular_links.php'; ?>

<!-- ══ FOOTER ══════════════════════════════════════════════════════════════ -->
<?php require __DIR__ . '/footer.php'; ?>

<!-- ══ FLOATING BUTTONS ════════════════════════════════════════════════════ -->
<div class="floating-actions d-flex flex-column gap-2" style="position: fixed; bottom: 80px; right: 20px; z-index: 9999; align-items: flex-end;">
    <button type="button" class="btn shadow-lg d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#siteVisitModal" style="background: var(--pr-secondary); color: var(--pr-primary); border-radius: 50%; width: 50px; height: 50px; border: 3px solid var(--pr-primary); margin-bottom: 5px; padding: 0;">
        <i class="fas fa-bullseye" style="font-size: 1.5rem;"></i>
    </button>
    <a href="tel:<?= e(preg_replace('/[^+\d]/', '', $phone)) ?>"
       class="btn d-flex align-items-center shadow-lg"
       style="background: #007bff; color: #ffffff; border-radius: 30px; font-weight: bold; padding: 8px 20px;"
       title="Call Us">
        <i class="fas fa-phone-alt me-2"></i> Call Us
    </a>
    <a href="https://wa.me/<?= e($wa) ?>?text=<?= urlencode('Hi, I found you on PropertyRubix. I need help with a property.') ?>"
       class="btn d-flex align-items-center shadow-lg"
       target="_blank" rel="noopener"
       style="background: #25D366; color: #ffffff; border-radius: 30px; font-weight: bold; padding: 8px 20px;"
       title="WhatsApp Us">
        <i class="fab fa-whatsapp me-2 fs-5"></i> WhatsApp
    </a>
</div>

<!-- ══ SITE VISIT MODAL ════════════════════════════════════════════════════ -->
<div class="modal fade" id="siteVisitModal" tabindex="-1" aria-labelledby="siteVisitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content glass-panel" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
      <div class="modal-header border-0 pb-0 justify-content-center position-relative">
        <h4 class="modal-title fw-bold text-center w-100" id="siteVisitModalLabel" style="color: var(--pr-primary);">Book Free Site Visit</h4>
        <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" style="right: 20px; top: 20px;"></button>
      </div>
      <div class="modal-body pt-1 px-4 pb-4">
        <p class="text-muted small mb-4 text-center">India by No developer assigned</p>
        <form id="siteVisitForm" novalidate>
          <?= csrfField() ?>
          <input type="text" name="hp_name" style="display:none" tabindex="-1" autocomplete="off"> <!-- honeypot -->
          <input type="hidden" name="form_type" value="site_visit">
          <input type="hidden" name="project_name" id="svProjectName" value="India by No developer assigned">
          <div class="row g-3">
            <div class="col-12">
              <input type="text" class="form-control" name="name" placeholder="Name" required minlength="2">
            </div>
            <div class="col-12">
              <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="col-12">
              <input type="tel" class="form-control" name="phone" placeholder="Phone Number" required pattern="[0-9+\s\-]{8,15}">
            </div>
            <div class="col-12">
              <textarea class="form-control" name="query" rows="3" placeholder="Query"></textarea>
            </div>
            <div class="col-6">
              <label class="small text-muted mb-1">Visit Date</label>
              <input type="date" class="form-control" name="visit_date" required min="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-6">
              <label class="small text-muted mb-1">Visit Time</label>
              <input type="time" class="form-control" name="visit_time" required>
            </div>
            <div class="col-12 mt-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="svConsent" name="consent" required checked>
                <label class="form-check-label" for="svConsent" style="font-size: 0.8rem;">
                  I authorize Property Rubix and its representatives to Call, SMS, Email or WhatsApp me about its products and offers.
                </label>
              </div>
            </div>
            <div class="col-12 mt-4">
              <button type="submit" class="btn w-100 py-2 fw-600" id="svSubmitBtn" style="background: var(--pr-secondary); color: var(--pr-primary); border-radius: 4px;">
                <span id="svBtnText">Submit</span>
                <span id="svBtnLoader" class="d-none"><i class="fas fa-circle-notch fa-spin me-2"></i>Submitting…</span>
              </button>
            </div>
            <div id="svResult" class="col-12 d-none"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ══ SCRIPTS ══════════════════════════════════════════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>window.BASE_URL = '<?= PUBLIC_URL ?>';</script>
<script src="<?= asset('js/app.js') ?>?v=<?= @filemtime(__DIR__ . '/../../../assets/js/app.js') ?>"></script>

<?= isset($extraScripts) ? $extraScripts : '' ?>
</body>
</html>
