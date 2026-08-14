<?php
/**
 * Slide-in Drawer Navigation
 */
$fbUrl  = getSetting('social_facebook',  '#');
$twUrl  = getSetting('social_twitter',   '#');
$ytUrl  = getSetting('social_youtube',   '#');
$igUrl  = getSetting('social_instagram', '#');
?>
<aside class="site-drawer glass-panel" id="siteDrawer" role="navigation" aria-label="Mobile navigation" aria-hidden="true" style="border-radius: 0; border-right: none; border-top: none; border-bottom: none;">

  <div class="drawer-header" style="padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: none;">
    <a href="<?= PUBLIC_URL ?>" class="drawer-logo text-decoration-none">
      <?php $siteLogo = getSetting('site_logo'); if (!empty($siteLogo)): ?>
        <img src="<?= upload($siteLogo) ?>" alt="<?= e($siteName) ?>" style="height: 34px; width: auto; object-fit: contain;">
      <?php elseif (!empty($branding['logo'])): ?>
        <img src="<?= upload($branding['logo']) ?>" alt="<?= e($siteName) ?>" style="height: 34px; width: auto; object-fit: contain;">
      <?php else: ?>
        <h3 class="fw-bold mb-0 text-dark m-0" style="font-size:1.4rem; letter-spacing:-0.5px;">property<span style="color:#a9804b;">rubix</span></h3>
      <?php endif; ?>
    </a>
    <button type="button" class="drawer-close" id="drawerClose" aria-label="Close menu" onclick="document.getElementById('siteDrawer')?.classList.remove('open'); document.getElementById('drawerOverlay')?.classList.remove('visible'); document.body.style.overflow='';" ontouchstart="document.getElementById('siteDrawer')?.classList.remove('open'); document.getElementById('drawerOverlay')?.classList.remove('visible'); document.body.style.overflow='';" style="background: #ffffff !important; border: 2px solid #eab308 !important; border-radius: 50% !important; width: 40px !important; height: 40px !important; display: flex !important; align-items: center !important; justify-content: center !important; color: #000000 !important; cursor: pointer !important; box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important; position: relative !important; z-index: 1000001 !important; pointer-events: auto !important;">
      <i class="fas fa-times fs-5" style="pointer-events: none !important; color: #000000 !important;"></i>
    </button>
  </div>

  <nav class="drawer-nav" style="padding: 10px 30px; display: flex; flex-direction: column; gap: 15px;">
    <a href="<?= PUBLIC_URL ?>"              class="text-dark text-decoration-none" style="font-size: 0.95rem;">Home</a>
    <a href="<?= PUBLIC_URL ?>about-us"      class="text-dark text-decoration-none" style="font-size: 0.95rem;">About Us</a>
    <a href="<?= PUBLIC_URL ?>blog"          class="text-dark text-decoration-none" style="font-size: 0.95rem;">Blogs</a>
    <a href="<?= PUBLIC_URL ?>contact-us"    class="text-dark text-decoration-none" style="font-size: 0.95rem;">Contact Us</a>
    <a href="<?= PUBLIC_URL ?>select-location" class="text-dark text-decoration-none" style="font-size: 0.95rem;">Search By Region</a>
    <a href="<?= PUBLIC_URL ?>developer"     class="text-dark text-decoration-none" style="font-size: 0.95rem;">Search By Developer</a>
  </nav>

  <div class="drawer-social" style="margin-top: auto; padding: 30px; display: flex; gap: 12px; border-top: none;">
    <a href="<?= e($fbUrl) ?>" target="_blank" rel="noopener" aria-label="Facebook" class="social-icon" style="background: #000; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
    <a href="<?= e($twUrl) ?>" target="_blank" rel="noopener" aria-label="Twitter/X" class="social-icon" style="background: #000; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; text-decoration: none;"><i class="fab fa-x-twitter"></i></a>
    <a href="<?= e($igUrl) ?>" target="_blank" rel="noopener" aria-label="Instagram" class="social-icon" style="background: #000; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; text-decoration: none;"><i class="fab fa-instagram"></i></a>
    <a href="<?= e($ytUrl) ?>" target="_blank" rel="noopener" aria-label="YouTube" class="social-icon" style="background: #000; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; text-decoration: none;"><i class="fab fa-youtube"></i></a>
  </div>

</aside>
