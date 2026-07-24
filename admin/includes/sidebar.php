<?php
/**
 * Admin sidebar navigation
 * Usage: require 'includes/sidebar.php';
 */
$adminBase = BASE_URL . 'admin/';
$curFile   = basename($_SERVER['PHP_SELF']);
$curDir    = basename(dirname($_SERVER['PHP_SELF']));

function adminLink(string $href, string $icon, string $label, string $curDir = '', string $match = ''): string {
    $active = ($match && ($curDir === $match || basename($_SERVER['PHP_SELF']) === $match)) ? 'active' : '';
    return "<a href=\"$href\" class=\"adm-nav-link $active\"><span class=\"nav-icon\">$icon</span> $label</a>";
}
?>
<aside class="adm-sidebar" id="adminSidebar">
  <div class="adm-logo d-flex flex-column align-items-start" style="padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); gap: 2px;">
    <a href="<?= $adminBase ?>" class="text-decoration-none d-flex flex-column align-items-start" style="gap: 1px;">
      <div class="logo-main d-flex align-items-center" style="font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1.3rem; line-height: 1; letter-spacing: -1px; user-select: none;">
        <span style="color: #ffffff;">property</span><span style="color: #eab308;">rubi</span><span style="color: #22c55e;">x</span><span class="logo-dot-com" style="font-size: 0.5rem; font-weight: 700; color: #ffffff; writing-mode: vertical-rl; transform: rotate(180deg); margin-left: 2px; letter-spacing: 0;">.com</span>
      </div>
      <div class="logo-tagline" style="background: #eab308; color: #0f172a; font-family: 'Inter', sans-serif; font-weight: 800; font-size: 0.4rem; padding: 1px 3px; border-radius: 2px; text-transform: uppercase; letter-spacing: 0.3px; line-height: 1;">
        THE <span style="color: #22c55e;">X</span> FACTOR IN PROPERTY SEARCH
      </div>
    </a>
    <span style="font-size: 0.65rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-top: 4px;">Admin Control Panel</span>
  </div>

  <nav class="adm-nav">
    <!-- Dashboard -->
    <?= adminLink($adminBase, '📊', 'Dashboard', $curDir, 'index.php') ?>

    <!-- Content & Inventory -->
    <div class="adm-nav-section">Content & Inventory</div>
    <?= adminLink($adminBase . 'projects/', '🏗️', 'Projects', $curDir, 'projects') ?>
    <?= adminLink($adminBase . 'builders/', '👷', 'Builders', $curDir, 'builders') ?>
    <?= adminLink($adminBase . 'countries/', '🌍', 'Countries', $curDir, 'countries') ?>
    <?= adminLink($adminBase . 'states/', '🗺️', 'States', $curDir, 'states') ?>
    <?= adminLink($adminBase . 'cities/', '🌆', 'Cities', $curDir, 'cities') ?>
    <?= adminLink($adminBase . 'localities/', '📍', 'Localities', $curDir, 'localities') ?>
    <?= adminLink($adminBase . 'import/', '📥', 'Import', $curDir, 'import') ?>

    <!-- CRM -->
    <div class="adm-nav-section">CRM</div>
    <?= adminLink($adminBase . 'leads/', '🎯', 'Leads', $curDir, 'leads') ?>
    <?= adminLink($adminBase . 'submissions/', '📋', 'Submissions', $curDir, 'submissions') ?>

    <!-- Marketing -->
    <div class="adm-nav-section">Marketing</div>
    <?= adminLink($adminBase . 'subscribers/', '📬', 'Subscribers', $curDir, 'subscribers') ?>
    <?= adminLink($adminBase . 'newsletter/', '📧', 'Newsletter', $curDir, 'newsletter') ?>
    <?= adminLink($adminBase . 'blog/', '✍️', 'Blog', $curDir, 'blog') ?>
    <?= adminLink($adminBase . 'testimonials/', '⭐', 'Testimonials', $curDir, 'testimonials') ?>
    <?= adminLink($adminBase . 'reviews/', '💬', 'Reviews', $curDir, 'reviews') ?>
    <?= adminLink($adminBase . 'faqs/', '❓', 'FAQs', $curDir, 'faqs') ?>

    <!-- Site Structure -->
    <div class="adm-nav-section">Site Structure</div>
    <?= adminLink($adminBase . 'pages/', '📄', 'Pages', $curDir, 'pages') ?>
    <?php /* <?= adminLink($adminBase . 'branding/', '🎨', 'Branding', $curDir, 'branding') ?> */ ?>

    <!-- System -->
    <div class="adm-nav-section">System</div>
    <?= adminLink($adminBase . 'settings/', '⚙️', 'Settings', $curDir, 'settings') ?>
    <?= adminLink($adminBase . 'api-tokens/', '🔑', 'API Tokens', $curDir, 'api-tokens') ?>
    <?php /* 
    <?= adminLink($adminBase . 'field-setup/', '🔧', 'Field Setup', $curDir, 'field-setup') ?>
    <?= adminLink($adminBase . 'crm-sync/', '🔄', 'CRM Sync', $curDir, 'crm-sync') ?>
    <?= adminLink($adminBase . 'wa-templates/', '💬', 'WA Templates', $curDir, 'wa-templates') ?>
    <?= adminLink($adminBase . 'tools/', '🛠️', 'Tools', $curDir, 'tools') ?>
    <?= adminLink($adminBase . 'database-backup/', '💾', 'DB Backup', $curDir, 'database-backup') ?>
    */ ?>
    <?= adminLink($adminBase . 'audit-log/', '📜', 'Audit Log', $curDir, 'audit-log') ?>
    <?php /* <?= adminLink($adminBase . 'system-verifier/', '🔍', 'System Verifier', $curDir, 'system-verifier') ?> */ ?>
    <?= adminLink($adminBase . 'users-permissions/', '👤', 'Users & Roles', $curDir, 'users-permissions') ?>
    <?php /* <?= adminLink($adminBase . 'help-guide/', '📖', 'Help Guide', $curDir, 'help-guide') ?> */ ?>

    <div style="height:12px"></div>
    <?= adminLink(BASE_URL, '🌐', 'View Website') ?>
    <?= adminLink($adminBase . 'logout.php', '🚪', 'Logout') ?>
  </nav>

  <div class="adm-sidebar-footer">
    <div class="adm-user-badge">
      <div class="adm-user-avatar"><?= strtoupper(substr($currentUser['name'],0,1)) ?></div>
      <div>
        <p class="adm-user-name mb-0"><?= htmlspecialchars($currentUser['name']) ?></p>
        <p class="adm-user-role mb-0"><?= ucfirst(str_replace('_',' ',$currentUser['role'])) ?></p>
      </div>
    </div>
  </div>
</aside>
