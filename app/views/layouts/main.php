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
html, body {
    overflow-x: hidden;
    width: 100%;
    position: relative;
}
</style>

<?= isset($extraHead) ? $extraHead : '' ?>
</head>
<body class="mesh-bg <?= isset($bodyClass) ? e($bodyClass) : '' ?>">

<!-- ══ DRAWER OVERLAY ══════════════════════════════════════════════════════ -->
<div class="drawer-overlay" id="drawerOverlay" aria-hidden="true" onclick="document.getElementById('siteDrawer')?.classList.remove('open'); this.classList.remove('visible'); document.body.style.overflow='';"></div>

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

<!-- ══ PREMIUM EXPANDING FLOATING BUTTONS ════════════════════════════════════ -->
<div class="fab-container" style="position: fixed; bottom: 80px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 14px; align-items: flex-end;">
    <style>
      .fab-btn {
        height: 56px;
        border-radius: 28px;
        display: flex;
        align-items: center;
        border: none;
        text-decoration: none;
        overflow: hidden;
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        white-space: nowrap;
        width: 56px; /* Starts as a perfect circle */
        transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1), background 0.3s, transform 0.3s;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        position: relative;
      }
      .fab-btn:hover {
        width: 160px; /* Expands to show text */
        text-decoration: none;
        color: #fff;
        transform: translateY(-3px);
      }
      .fab-icon {
        position: absolute;
        right: 0;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
      }
      .fab-text {
        position: absolute;
        right: 52px;
        opacity: 0;
        transition: opacity 0.25s ease;
        transition-delay: 0s;
      }
      .fab-btn:hover .fab-text {
        opacity: 1;
        transition-delay: 0.1s;
      }
      
      /* Site Visit Button */
      .fab-sv {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #1a0a00;
        animation: pulseSV 2s infinite;
      }
      .fab-sv:hover { color: #1a0a00; box-shadow: 0 12px 28px rgba(245,158,11,0.35); }
      @keyframes pulseSV {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.6); }
        70% { box-shadow: 0 0 0 16px rgba(245, 158, 11, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
      }

      /* Call Button */
      .fab-call { background: linear-gradient(135deg, #3b82f6, #2563eb); }
      .fab-call:hover { box-shadow: 0 12px 28px rgba(37,99,235,0.35); }

      /* WhatsApp Button */
      .fab-wa { background: linear-gradient(135deg, #22c55e, #16a34a); }
      .fab-wa:hover { box-shadow: 0 12px 28px rgba(22,163,74,0.35); }

      /* Mobile Responsive App Tab Bar */
      @media (max-width: 768px) {
        body {
          padding-bottom: 75px !important;
        }
        .fab-container {
          bottom: 0 !important;
          right: 0 !important;
          width: 100% !important;
          flex-direction: row !important;
          gap: 0 !important;
          padding: 8px 10px 15px 10px !important;
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          border-top: 1px solid #eaeaea;
          justify-content: space-between !important;
          box-shadow: 0 -4px 15px rgba(0,0,0,0.05);
        }
        .fab-btn {
          width: 32% !important;
          border-radius: 12px !important;
          height: 48px !important;
          flex-direction: column !important;
          padding: 0 !important;
          animation: none !important; /* disable pulse */
          box-shadow: none !important;
          position: relative !important;
          justify-content: center !important;
        }
        .fab-btn:hover {
          width: 32% !important;
          transform: none !important;
        }
        .fab-icon {
          position: static !important;
          height: 20px !important;
          width: 100% !important;
          font-size: 1.1rem !important;
          margin-top: 4px !important;
        }
        .fab-text {
          position: static !important;
          opacity: 1 !important;
          font-size: 0.65rem !important;
          text-align: center !important;
          margin: 0 !important;
          width: 100% !important;
          line-height: 1.2 !important;
          margin-top: 2px !important;
          font-weight: 600 !important;
        }
      }
    </style>

    <button type="button" class="fab-btn fab-sv" data-bs-toggle="modal" data-bs-target="#siteVisitModal">
        <span class="fab-icon"><i class="fas fa-car-side"></i></span>
        <span class="fab-text">Site Visit</span>
    </button>
    
    <a href="tel:<?= e(preg_replace('/[^+\d]/', '', $phone)) ?>" class="fab-btn fab-call">
        <span class="fab-icon"><i class="fas fa-phone-alt"></i></span>
        <span class="fab-text">Call Us</span>
    </a>
    
    <a href="https://wa.me/<?= e($wa) ?>?text=<?= urlencode('Hi, I found you on PropertyRubix. I need help with a property.') ?>" class="fab-btn fab-wa" target="_blank" rel="noopener">
        <span class="fab-icon"><i class="fab fa-whatsapp" style="font-size:1.4rem;"></i></span>
        <span class="fab-text">WhatsApp</span>
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
              <div class="input-group">
                <select name="phone_code" class="form-select fw-bold shadow-none" style="max-width:110px;">
                  <option value="+91" selected>🇮🇳 +91</option>
                  <option value="+1">🇺🇸 +1</option>
                  <option value="+1">🇨🇦 +1</option>
                  <option value="+44">🇬🇧 +44</option>
                  <option value="+971">🇦🇪 +971</option>
                  <option value="+61">🇦🇺 +61</option>
                  <option value="+65">🇸🇬 +65</option>
                  <option value="+60">🇲🇾 +60</option>
                  <option value="+49">🇩🇪 +49</option>
                  <option value="+33">🇫🇷 +33</option>
                  <option value="+966">🇸🇦 +966</option>
                  <option value="+974">🇶🇦 +974</option>
                  <option value="+965">🇰🇼 +965</option>
                  <option value="+880">🇧🇩 +880</option>
                  <option value="+92">🇵🇰 +92</option>
                  <option value="+977">🇳🇵 +977</option>
                </select>
                <input type="tel" class="form-control" name="phone" placeholder="Phone Number" required pattern="[0-9]{7,15}">
              </div>
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
