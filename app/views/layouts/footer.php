<?php
/**
 * 4-column footer
 */
$addr1  = getSetting('address_1', '123, Real Estate Tower, Noida, UP');
$addr2  = getSetting('address_2', '456, Business Hub, Gurugram, HR');
$phone1 = getSetting('phone_primary',  '+91 98765 43210');
$phone2 = getSetting('phone_secondary','+91 91234 56789');
$email1 = getSetting('email_primary',  'info@propertyrubix.com');
$email2 = getSetting('email_secondary','sales@propertyrubix.com');
$rera1  = getSetting('rera_id_1', 'UPRERAPRJ123456');
$psUrl  = getSetting('playstore_url', '#');
$asUrl  = getSetting('appstore_url',  '#');
$fbUrl  = getSetting('social_facebook',  '#');
$twUrl  = getSetting('social_twitter',   '#');
$ytUrl  = getSetting('social_youtube',   '#');
$igUrl  = getSetting('social_instagram', '#');
$siteName = getSetting('site_name') ?: 'PropertyRubix';
?>
<footer class="site-footer" id="siteFooter" style="background-color: #000; padding: 40px 0 20px;">
  <div class="container-fluid px-3 px-md-5">
    
    <!-- White Pill Header -->
    <div class="bg-white rounded-pill px-4 py-3 d-flex flex-wrap justify-content-between align-items-center mb-5 shadow-sm">
      <a href="<?= PUBLIC_URL ?>" class="text-decoration-none d-flex flex-column">
        <?php $siteLogo = getSetting('site_logo'); if (!empty($siteLogo)): ?>
          <img src="<?= upload($siteLogo) ?>" alt="<?= e($siteName) ?>" style="height: 36px; width: auto; object-fit: contain;">
        <?php elseif (!empty($branding['logo'])): ?>
          <img src="<?= upload($branding['logo']) ?>" alt="<?= e($siteName) ?>" style="height: 36px; width: auto; object-fit: contain;">
        <?php else: ?>
          <div class="d-flex align-items-center">
            <h2 class="fw-bold mb-0 text-dark m-0" style="font-size:1.8rem; letter-spacing:-0.5px;">property<span style="color:#a9804b;">rubix</span></h2>
          </div>
          <div style="margin-top: -2px;">
            <span style="background: #ffc107; color: #000; font-size: 0.6rem; font-weight: 800; padding: 1px 6px; letter-spacing: 0.5px; border-radius: 2px;">THE X FACTOR IN PROPERTY SEARCH</span>
          </div>
        <?php endif; ?>
      </a>
      
      <div class="d-flex gap-4">
        <a href="<?= e($fbUrl) ?>" class="text-dark fs-5"><i class="fab fa-facebook-f"></i></a>
        <a href="<?= e($igUrl) ?>" class="text-dark fs-5"><i class="fab fa-instagram"></i></a>
        <a href="<?= e($twUrl) ?>" class="text-dark fs-5"><i class="fab fa-x-twitter"></i></a>
        <a href="<?= e($ytUrl) ?>" class="text-dark fs-5"><i class="fab fa-linkedin-in"></i></a>
        <a href="<?= e($ytUrl) ?>" class="text-dark fs-5"><i class="fab fa-youtube"></i></a>
      </div>
    </div>
    
    <!-- Useful Links -->
    <div class="mb-4">
      <h5 class="fw-bold text-white mb-3" style="font-size: 1.1rem;">Useful links</h5>
      <div class="d-flex gap-4 flex-wrap">
        <a href="<?= PUBLIC_URL ?>developer" class="text-white text-decoration-none" style="font-size: 0.95rem;">Search by developer</a>
        <a href="<?= PUBLIC_URL ?>location" class="text-white text-decoration-none" style="font-size: 0.95rem;">Search by location</a>
      </div>
    </div>
    
    <!-- Quick Links -->
    <div class="mb-4">
      <h5 class="fw-bold text-white mb-3" style="font-size: 1.1rem;">Quick Links</h5>
      <div class="d-flex gap-4 flex-wrap">
        <a href="<?= PUBLIC_URL ?>" class="text-white text-decoration-none" style="font-size: 0.95rem;">Home</a>
        <a href="<?= PUBLIC_URL ?>about-us" class="text-white text-decoration-none" style="font-size: 0.95rem;">About us</a>
        <a href="<?= PUBLIC_URL ?>projects" class="text-white text-decoration-none" style="font-size: 0.95rem;">All Projects</a>
        <a href="<?= PUBLIC_URL ?>contact-us" class="text-white text-decoration-none" style="font-size: 0.95rem;">Support</a>
        <a href="<?= PUBLIC_URL ?>blog" class="text-white text-decoration-none" style="font-size: 0.95rem;">Blogs</a>
        <a href="<?= PUBLIC_URL ?>advertise-with-us" class="text-white text-decoration-none" style="font-size: 0.95rem;">Advertise with us</a>
        <a href="<?= PUBLIC_URL ?>privacy-policy" class="text-white text-decoration-none" style="font-size: 0.95rem;">Privacy policy</a>
        <a href="<?= PUBLIC_URL ?>terms-conditions" class="text-white text-decoration-none" style="font-size: 0.95rem;">Terms & conditions</a>
      </div>
    </div>
    
    <!-- Contact Us -->
    <div class="mb-4">
      <h5 class="fw-bold text-white mb-3" style="font-size: 1.1rem;">Contact us</h5>
      <div class="d-flex gap-4 gap-md-5 flex-wrap text-white align-items-center" style="font-size: 0.95rem;">
        <div><i class="fas fa-phone me-2"></i> +91 9971963336</div>
        <div><i class="fas fa-envelope me-2"></i> info@propertyrubix.com</div>
        <div><i class="fas fa-map-marker-alt me-2"></i> C-25, C Block, Sector 58, Noida, Uttar Pradesh 201301</div>
        <div><i class="fas fa-file-alt me-2"></i> RERA ID: 1234567989</div>
      </div>
    </div>
    
    <!-- Copyright -->
    <div class="text-center mt-5 pt-3">
      <p class="text-white mb-0" style="font-size: 0.85rem;">© <?= date('Y') ?> Property Rubix | All rights reserved.</p>
    </div>
    
  </div>
</footer>
