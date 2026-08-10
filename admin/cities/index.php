<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';
require_once APP_PATH . 'helpers/slug.php';
require_once APP_PATH . 'helpers/upload.php';

$pdo    = db();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);
$search = trim($_GET['q'] ?? '');
$errors = [];
$row    = [];

// ── Delete
if ($action === 'delete' && $id) {
    csrfCheck();
    try {
        // Delete banner image if exists
        $stmt = $pdo->prepare("SELECT banner_image FROM `cities` WHERE id=?");
        $stmt->execute([$id]);
        $oldImg = $stmt->fetchColumn();
        if ($oldImg) {
            deleteUpload($oldImg);
        }
        crudDelete($pdo, 'cities', $id, BASE_URL . 'admin/cities/');
    } catch (PDOException $e) {
        $errors[] = 'Cannot delete city: ' . $e->getMessage();
    }
}

// ── Load row for edit
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM `cities` WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: [];
    if (!$row) {
        header('Location: ' . BASE_URL . 'admin/cities/');
        exit;
    }
}

// ── Save (new or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['new','edit'])) {
    csrfCheck();
    
    $stateId         = (int)($_POST['state_id'] ?? 0);
    $name            = trim($_POST['name'] ?? '');
    $slug            = trim($_POST['slug'] ?? '');
    $metaTitle       = trim($_POST['meta_title'] ?? '');
    $metaDescription = trim($_POST['meta_description'] ?? '');
    $sortOrder       = (int)($_POST['sort_order'] ?? 0);
    $status          = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';
    $bannerImage     = $row['banner_image'] ?? null;
    
    if (!$stateId) {
        $errors[] = 'Please select a State / Province.';
    }
    if (!$name) {
        $errors[] = 'City name is required.';
    }
    
    // Delete existing banner image if requested
    if (!empty($_POST['delete_banner_image']) && $bannerImage) {
        deleteUpload($bannerImage);
        $bannerImage = null;
    }
    
    // Handle banner image upload
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadImage($_FILES['banner_image'], 'cities');
        if ($upload['success']) {
            if ($bannerImage) {
                deleteUpload($bannerImage);
            }
            $bannerImage = $upload['path'];
        } else {
            $errors[] = 'Banner Image Error: ' . $upload['error'];
        }
    }
    
    if (!$errors) {
        if (!$slug) {
            $slug = slugify($name);
        } else {
            $slug = slugify($slug);
        }
        
        // Ensure unique slug per state
        $stmt = $pdo->prepare("SELECT id FROM `cities` WHERE state_id = ? AND slug = ? AND id != ?");
        $stmt->execute([$stateId, $slug, $id]);
        if ($stmt->fetch()) {
            $slug .= '-' . rand(100, 999);
        }
        
        $data = [
            'state_id'         => $stateId,
            'name'             => $name,
            'slug'             => $slug,
            'banner_image'     => $bannerImage,
            'meta_title'       => $metaTitle ?: null,
            'meta_description' => $metaDescription ?: null,
            'sort_order'       => $sortOrder,
            'status'           => $status,
        ];
        
        try {
            if ($id) {
                $sets = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
                $stmt = $pdo->prepare("UPDATE `cities` SET $sets WHERE id=?");
                $stmt->execute([...array_values($data), $id]);
                logAction('UPDATE', 'cities', $id);
            } else {
                $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
                $vals = implode(', ', array_fill(0, count($data), '?'));
                $stmt = $pdo->prepare("INSERT INTO `cities` ($cols) VALUES ($vals)");
                $stmt->execute(array_values($data));
                $id = (int)$pdo->lastInsertId();
                logAction('CREATE', 'cities', $id);
            }
            header('Location: ' . BASE_URL . 'admin/cities/?saved=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// ── List
$list = crudList(
    $pdo,
    'cities',
    20,
    'cities.sort_order ASC, cities.name ASC',
    'LEFT JOIN states s ON s.id = cities.state_id LEFT JOIN countries co ON co.id = s.country_id',
    ', s.name AS state_name, co.name AS country_name, co.flag_icon AS country_flag',
    $search,
    ['cities.name', 'cities.slug', 's.name', 'co.name']
);

$states = $pdo->query("
    SELECT s.id, s.name, co.name AS country_name, co.flag_icon 
    FROM `states` s 
    JOIN `countries` co ON co.id = s.country_id 
    ORDER BY co.name ASC, s.name ASC
")->fetchAll();

$pageTitle = 'Cities';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title"><?= $pageTitle ?> (<?= $list['total'] ?? 0 ?>)</h2>
    <div class="d-flex gap-2">
      <form class="d-flex gap-2" method="get">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search cities…" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-sm btn-outline-secondary">Search</button>
      </form>
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New City</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Banner</th>
          <th>City</th>
          <th>State & Country</th>
          <th>Slug</th>
          <th>Sort</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($list['rows'] as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td>
            <?php if (!empty($p['banner_image'])): ?>
              <img src="<?= upload($p['banner_image']) ?>" class="rounded" style="width:50px;height:35px;object-fit:cover;">
            <?php else: ?>
              <span class="text-muted small">No banner</span>
            <?php endif; ?>
          </td>
          <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
          <td>
            <?= htmlspecialchars($p['state_name'] ?? '—') ?>,
            <span class="text-muted"><?= htmlspecialchars($p['country_flag'] ?? '') ?> <?= htmlspecialchars($p['country_name'] ?? '—') ?></span>
          </td>
          <td><code class="text-muted"><?= htmlspecialchars($p['slug']) ?></code></td>
          <td><?= (int)$p['sort_order'] ?></td>
          <td>
            <span class="adm-badge badge-<?= $p['status'] ?? 'active' ?>"><?= ucfirst($p['status'] ?? 'active') ?></span>
          </td>
          <td>
            <div class="actions">
              <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= csrfToken() ?>"
                 class="btn btn-sm btn-outline-danger"
                 data-confirm="Delete this city? Associated properties and neighborhoods will also be affected.">Delete</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($list['rows'])): ?>
        <tr><td colspan="8" class="text-center py-4 text-muted">No cities found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if (!empty($list['totalPages']) && $list['totalPages'] > 1): ?>
  <div class="p-3">
    <nav><ul class="pagination mb-0">
      <?php for ($i = 1; $i <= $list['totalPages']; $i++): ?>
      <li class="page-item <?= $i === $list['page'] ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
      </li>
      <?php endfor; ?>
    </ul></nav>
  </div>
  <?php endif; ?>
</div>

<?php else: ?>
<!-- New/Edit Form -->
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="?" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
  <h2 class="mb-0"><?= $id ? 'Edit City' : 'New City' ?></h2>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="post" class="adm-form" enctype="multipart/form-data" novalidate>
  <?= csrfField() ?>
  <div class="adm-card">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="adm-form-label">State / Province *</label>
        <select name="state_id" class="form-select" required>
          <option value="">-- Select State / Province --</option>
          <?php foreach ($states as $s): ?>
          <option value="<?= $s['id'] ?>" <?= (($row['state_id'] ?? '') == $s['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['country_flag'] ? $s['country_flag'] . ' ' . $s['country_name'] : $s['country_name']) ?>)
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label class="adm-form-label">City Name *</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name'] ?? '') ?>" placeholder="e.g. Mumbai, Dubai, Toronto, Los Angeles" required>
      </div>

      <div class="col-md-6">
        <label class="adm-form-label">URL Slug</label>
        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($row['slug'] ?? '') ?>" placeholder="auto-generated if left blank">
      </div>

      <div class="col-md-3">
        <label class="adm-form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($row['sort_order'] ?? 0) ?>">
      </div>

      <div class="col-md-3">
        <label class="adm-form-label">Status</label>
        <select name="status" class="form-select">
          <option value="active" <?= (($row['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Active</option>
          <option value="inactive" <?= (($row['status'] ?? 'active') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>

      <div class="col-md-12">
        <label class="adm-form-label">City Hero Banner Image</label>
        <?php if (!empty($row['banner_image'])): ?>
        <div class="d-flex align-items-center gap-3 mb-2 p-2 bg-light rounded border">
          <img src="<?= upload($row['banner_image']) ?>" class="rounded" style="height:60px;object-fit:cover;">
          <div>
            <label class="form-check-label text-danger small cursor-pointer">
              <input type="checkbox" name="delete_banner_image" value="1" class="form-check-input me-1"> Delete current banner image
            </label>
          </div>
        </div>
        <?php endif; ?>
        <input type="file" name="banner_image" class="form-control" accept="image/*">
        <small class="text-muted">Recommended: 1920x800 high-resolution landscape photo.</small>
      </div>

      <div class="col-md-12">
        <label class="adm-form-label">Meta Title (SEO)</label>
        <input type="text" name="meta_title" class="form-control" value="<?= htmlspecialchars($row['meta_title'] ?? '') ?>" placeholder="e.g. Luxury Real Estate & Apartments in Mumbai">
      </div>

      <div class="col-md-12">
        <label class="adm-form-label">Meta Description (SEO)</label>
        <textarea name="meta_description" class="form-control" rows="2" placeholder="Brief summary of property listings in this city..."><?= htmlspecialchars($row['meta_description'] ?? '') ?></textarea>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-save me-2"></i><?= $id ? 'Update City' : 'Save City' ?></button>
      <a href="?" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
  </div>
</form>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>