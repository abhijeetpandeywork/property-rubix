<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';

$pdo = db();

// Save settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    
    // Handle standard text settings
    foreach ($_POST as $key => $value) {
        if ($key === 'csrf_token' || $key === 'delete_sliders') continue;
        if (is_array($value)) continue; // skip arrays like delete_sliders
        
        $existing = $pdo->prepare("SELECT id FROM settings WHERE key_name=?");
        $existing->execute([$key]);
        if ($existing->fetch()) {
            $pdo->prepare("UPDATE settings SET value=? WHERE key_name=?")->execute([trim($value), $key]);
        } else {
            $pdo->prepare("INSERT INTO settings (key_name, value) VALUES (?,?)")->execute([trim($key), trim($value)]);
        }
    }
    
    // Handle Site Logo Upload
    if (!empty($_FILES['site_logo']['name'])) {
        $up = uploadImage($_FILES['site_logo'], 'settings');
        if ($up['success']) {
            $existing = $pdo->prepare("SELECT id FROM settings WHERE key_name='site_logo'");
            $existing->execute();
            if ($existing->fetch()) {
                $pdo->prepare("UPDATE settings SET value=? WHERE key_name='site_logo'")->execute([$up['path']]);
            } else {
                $pdo->prepare("INSERT INTO settings (key_name, value) VALUES ('site_logo', ?)")->execute([$up['path']]);
            }
        }
    }
    
    // Handle Sliders Deletion
    $existingSliders = [];
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE key_name='hero_sliders'");
    $stmt->execute();
    if ($row = $stmt->fetch()) {
        $existingSliders = json_decode($row['value'], true) ?: [];
    }
    
    if (!empty($_POST['delete_sliders'])) {
        foreach ($_POST['delete_sliders'] as $idx) {
            unset($existingSliders[$idx]);
        }
        $existingSliders = array_values($existingSliders); // re-index
    }
    
    // Handle New Slider Uploads
    if (!empty($_FILES['hero_sliders']['name'][0])) {
        foreach ($_FILES['hero_sliders']['name'] as $i => $name) {
            if ($name) {
                $file = [
                    'name'     => $_FILES['hero_sliders']['name'][$i],
                    'type'     => $_FILES['hero_sliders']['type'][$i],
                    'tmp_name' => $_FILES['hero_sliders']['tmp_name'][$i],
                    'error'    => $_FILES['hero_sliders']['error'][$i],
                    'size'     => $_FILES['hero_sliders']['size'][$i],
                ];
                $up = uploadImage($file, 'sliders');
                if ($up['success']) {
                    $existingSliders[] = $up['path'];
                }
            }
        }
    }
    
    // Save sliders array back to DB
    $slidersJson = json_encode($existingSliders);
    $existing = $pdo->prepare("SELECT id FROM settings WHERE key_name='hero_sliders'");
    $existing->execute();
    if ($existing->fetch()) {
        $pdo->prepare("UPDATE settings SET value=? WHERE key_name='hero_sliders'")->execute([$slidersJson]);
    } else {
        $pdo->prepare("INSERT INTO settings (key_name, value) VALUES ('hero_sliders', ?)")->execute([$slidersJson]);
    }

    logAction('UPDATE', 'settings', null);
    header('Location: ' . BASE_URL . 'admin/settings/?saved=1');
    exit;
}

// Load all settings
$rows  = $pdo->query("SELECT key_name, value FROM settings ORDER BY key_name")->fetchAll();
$settings = [];
foreach ($rows as $r) $settings[$r['key_name']] = $r['value'];

$sv = fn($key, $default='') => htmlspecialchars($settings[$key] ?? $default);

$pageTitle = 'Site Settings';
require __DIR__ . '/../includes/header.php';
?>
<?= flashMsg() ?>

<form method="post" enctype="multipart/form-data" class="adm-form">
  <?= csrfField() ?>
  <div class="row g-4">
    <div class="col-lg-8">

      <!-- Media & Branding -->
      <div class="adm-card">
        <div class="adm-card-title">🎨 Branding & Media</div>
        
        <div class="mb-4">
            <label class="adm-form-label">Site Logo</label>
            <?php if (!empty($settings['site_logo'])): ?>
                <div class="mb-2">
                    <img src="<?= upload($settings['site_logo']) ?>" alt="Site Logo" style="height: 60px; object-fit: contain; background: #f1f1f1; padding: 10px; border-radius: 4px;">
                </div>
            <?php endif; ?>
            <input type="file" name="site_logo" class="form-control" accept="image/*">
            <div class="form-text">Upload a new logo to replace the current one in Header, Footer, and Drawer.</div>
        </div>

        <div>
            <label class="adm-form-label">Homepage Hero Sliders</label>
            
            <?php 
            $sliders = json_decode($settings['hero_sliders'] ?? '[]', true) ?: []; 
            if ($sliders): 
            ?>
            <div class="d-flex flex-wrap gap-3 mb-3">
                <?php foreach ($sliders as $idx => $img): ?>
                <div style="position: relative; width: 150px; height: 100px; border-radius: 6px; overflow: hidden; border: 1px solid #ddd;">
                    <img src="<?= upload($img) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); padding: 4px; text-align: center;">
                        <div class="form-check form-check-inline m-0 text-white" style="font-size: 0.8rem;">
                            <input class="form-check-input" type="checkbox" name="delete_sliders[]" value="<?= $idx ?>" id="del_sl_<?= $idx ?>">
                            <label class="form-check-label" for="del_sl_<?= $idx ?>">Delete</label>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <input type="file" name="hero_sliders[]" class="form-control" accept="image/*" multiple>
            <div class="form-text">You can select multiple images at once. New images will be added to the existing sliders. Check "Delete" to remove existing sliders on save.</div>
        </div>
      </div>

      <!-- Contact -->
      <div class="adm-card">
        <div class="adm-card-title">📞 Contact Details</div>
        <div class="row g-3">
          <div class="col-md-6"><label class="adm-form-label">Primary Phone</label><input type="text" name="phone_primary" class="form-control" value="<?= $sv('phone_primary','+91 98765 43210') ?>"></div>
          <div class="col-md-6"><label class="adm-form-label">Secondary Phone</label><input type="text" name="phone_secondary" class="form-control" value="<?= $sv('phone_secondary') ?>"></div>
          <div class="col-md-6"><label class="adm-form-label">Primary Email</label><input type="email" name="email_primary" class="form-control" value="<?= $sv('email_primary','info@propertyrubix.com') ?>"></div>
          <div class="col-md-6"><label class="adm-form-label">Secondary Email</label><input type="email" name="email_secondary" class="form-control" value="<?= $sv('email_secondary') ?>"></div>
          <div class="col-12"><label class="adm-form-label">WhatsApp Number (country code, no +)</label><input type="text" name="whatsapp_number" class="form-control" value="<?= $sv('whatsapp_number','919876543210') ?>"></div>
          <div class="col-12"><label class="adm-form-label">Head Office Address</label><input type="text" name="address_1" class="form-control" value="<?= $sv('address_1') ?>"></div>
          <div class="col-12"><label class="adm-form-label">Sales Office Address</label><input type="text" name="address_2" class="form-control" value="<?= $sv('address_2') ?>"></div>
        </div>
      </div>

      <!-- Social Media -->
      <div class="adm-card">
        <div class="adm-card-title">📱 Social Media URLs</div>
        <div class="row g-3">
          <?php foreach (['social_facebook'=>'Facebook','social_twitter'=>'Twitter/X','social_youtube'=>'YouTube','social_instagram'=>'Instagram'] as $k=>$l): ?>
          <div class="col-md-6"><label class="adm-form-label"><?= $l ?></label><input type="url" name="<?= $k ?>" class="form-control" value="<?= $sv($k,'#') ?>"></div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- App Downloads -->
      <div class="adm-card">
        <div class="adm-card-title">📲 App Store Links</div>
        <div class="row g-3">
          <div class="col-md-6"><label class="adm-form-label">Google Play Store URL</label><input type="url" name="playstore_url" class="form-control" value="<?= $sv('playstore_url','#') ?>"></div>
          <div class="col-md-6"><label class="adm-form-label">Apple App Store URL</label><input type="url" name="appstore_url" class="form-control" value="<?= $sv('appstore_url','#') ?>"></div>
        </div>
      </div>

      <!-- RERA -->
      <div class="adm-card">
        <div class="adm-card-title">🏛️ Compliance</div>
        <div class="row g-3">
          <div class="col-md-6"><label class="adm-form-label">RERA ID 1</label><input type="text" name="rera_id_1" class="form-control" value="<?= $sv('rera_id_1') ?>"></div>
          <div class="col-md-6"><label class="adm-form-label">RERA ID 2</label><input type="text" name="rera_id_2" class="form-control" value="<?= $sv('rera_id_2') ?>"></div>
          <div class="col-12"><label class="adm-form-label">GSTIN</label><input type="text" name="gstin" class="form-control" value="<?= $sv('gstin') ?>"></div>
        </div>
      </div>

      <!-- Analytics -->
      <div class="adm-card">
        <div class="adm-card-title">📊 Analytics & Tracking</div>
        <div class="mb-3"><label class="adm-form-label">Google Analytics ID</label><input type="text" name="ga_id" class="form-control" value="<?= $sv('ga_id') ?>" placeholder="G-XXXXXXXXXX"></div>
        <div class="mb-3"><label class="adm-form-label">Facebook Pixel ID</label><input type="text" name="fb_pixel_id" class="form-control" value="<?= $sv('fb_pixel_id') ?>"></div>
        <div><label class="adm-form-label">Custom Header Scripts</label><textarea name="custom_header_scripts" class="form-control font-monospace" rows="4"><?= $sv('custom_header_scripts') ?></textarea></div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- SEO & Homepage Stats -->
      <div class="adm-card">
        <div class="adm-card-title">🔍 SEO & Homepage</div>
        <div class="mb-3"><label class="adm-form-label">Homepage SEO Title</label><input type="text" name="home_seo_title" class="form-control" value="<?= $sv('home_seo_title','Find Your Perfect Property in India & UAE') ?>"></div>
        <div class="mb-3"><label class="adm-form-label">Homepage SEO Description</label><textarea name="home_seo_desc" class="form-control" rows="3"><?= $sv('home_seo_desc','Discover verified residential, commercial & plot projects across India, UAE, USA and Canada. RERA registered, trusted developers.') ?></textarea></div>
        <div class="mb-3"><label class="adm-form-label">"Happy Families" Stat Number</label><input type="number" name="happy_families_count" class="form-control" value="<?= $sv('happy_families_count','10000') ?>"></div>
      </div>

      <div class="adm-card">
        <div class="adm-card-title">⚙️ General</div>
        <div class="mb-3"><label class="adm-form-label">Site Tagline</label><input type="text" name="site_tagline" class="form-control" value="<?= $sv('site_tagline','Find Your Perfect Property') ?>"></div>
        <div class="mb-3"><label class="adm-form-label">Default Country</label><input type="text" name="default_country" class="form-control" value="<?= $sv('default_country','India') ?>"></div>
        <div class="mb-3"><label class="adm-form-label">Default Currency Symbol</label><input type="text" name="currency_symbol" class="form-control" value="<?= $sv('currency_symbol','₹') ?>"></div>
        <div class="mb-3"><label class="adm-form-label">Lead Auto-Reply Message</label><textarea name="lead_auto_reply" class="form-control" rows="3"><?= $sv('lead_auto_reply') ?></textarea></div>
      </div>
      <div class="d-grid"><button type="submit" class="btn btn-primary fw-600 py-3"><i class="fas fa-save me-2"></i>Save All Settings</button></div>
    </div>
  </div>
</form>

<?php require __DIR__ . '/../includes/footer.php'; ?>
