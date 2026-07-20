<?php
/**
 * Admin layout header — output at top of every admin page
 * Variables expected: $pageTitle (string)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> | <?= htmlspecialchars($siteName) ?></title>
<meta name="robots" content="noindex,nofollow">
<link rel="icon" href="<?= PUBLIC_URL ?>assets/img/favicon.svg" type="image/svg+xml">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= PUBLIC_URL ?>assets/css/admin.css">
<?= isset($extraHead) ? $extraHead : '' ?>
</head>
<body class="admin-body">

<?php require __DIR__ . '/sidebar.php'; ?>

<!-- Admin Header -->
<header class="adm-header">
  <div class="d-flex align-items-center gap-3">
    <button class="btn btn-sm btn-light d-lg-none" id="sidebarToggle" aria-label="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
    <h1 class="adm-header-title mb-0"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
  </div>
  <div class="adm-header-actions">
    <a href="<?= BASE_URL ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
      <i class="fas fa-external-link-alt me-1"></i>View Site
    </a>
    <a href="<?= BASE_URL ?>admin/logout.php" class="btn btn-sm btn-outline-danger">
      <i class="fas fa-sign-out-alt me-1"></i>Logout
    </a>
  </div>
</header>

<!-- Admin Main Content -->
<main class="adm-main">
