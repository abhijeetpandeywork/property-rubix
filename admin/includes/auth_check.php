<?php
/**
 * Admin auth guard — include at top of every admin page.
 */
require_once __DIR__ . '/../../config/db.php';
require_once APP_PATH . 'helpers/auth.php';
require_once APP_PATH . 'helpers/csrf.php';
require_once APP_PATH . 'helpers/settings.php';
require_once APP_PATH . 'helpers/upload.php';
require_once APP_PATH . 'helpers/slug.php';
require_once APP_PATH . 'core/View.php';

if (session_status() === PHP_SESSION_NONE) session_start();
authCheck();

$currentUser = currentUser();
$branding    = getBranding();
$siteName    = $branding['site_name'] ?? 'PropertyRubix';
