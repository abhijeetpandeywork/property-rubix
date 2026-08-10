<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';
require_once APP_PATH . 'helpers/slug.php';

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
        crudDelete($pdo, 'localities', $id, BASE_URL . 'admin/localities/');
    } catch (PDOException $e) {
        $errors[] = 'Cannot delete neighborhood: ' . $e->getMessage();
    }
}

// ── Load row for edit
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM `localities` WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: [];
    if (!$row) {
        header('Location: ' . BASE_URL . 'admin/localities/');
        exit;
    }
}

// ── Save (new or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['new','edit'])) {
    csrfCheck();
    
    $cityId    = (int)($_POST['city_id'] ?? 0);
    $name      = trim($_POST['name'] ?? '');
    $slug      = trim($_POST['slug'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $status    = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

    if (!$cityId) {
        $errors[] = 'Please select a City.';
    }
    if (!$name) {
        $errors[] = 'Neighborhood / Locality name is required.';
    }
    
    if (!$errors) {
        if (!$slug) {
            $slug = slugify($name);
        } else {
            $slug = slugify($slug);
        }
        
        // Ensure unique slug per city
        $stmt = $pdo->prepare("SELECT id FROM `localities` WHERE city_id = ? AND slug = ? AND id != ?");
        $stmt->execute([$cityId, $slug, $id]);
        if ($stmt->fetch()) {
            $slug .= '-' . rand(100, 999);
        }

        $data = [
            'city_id'    => $cityId,
            'name'       => $name,
            'slug'       => $slug,
            'sort_order' => $sortOrder,
            'status'     => $status,
        ];
        
        try {
            if ($id) {
                $sets = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
                $stmt = $pdo->prepare("UPDATE `localities` SET $sets WHERE id=?");
                $stmt->execute([...array_values($data), $id]);
                logAction('UPDATE', 'localities', $id);
            } else {
                $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
                $vals = implode(', ', array_fill(0, count($data), '?'));
                $stmt = $pdo->prepare("INSERT INTO `localities` ($cols) VALUES ($vals)");
                $stmt->execute(array_values($data));
                $id = (int)$pdo->lastInsertId();
                logAction('CREATE', 'localities', $id);
            }
            header('Location: ' . BASE_URL . 'admin/localities/?saved=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// ── List
$list = crudList(
    $pdo,
    'localities',
    20,
    'localities.sort_order ASC, localities.name ASC',
    'LEFT JOIN cities c ON c.id = localities.city_id LEFT JOIN states s ON s.id = c.state_id LEFT JOIN countries co ON co.id = s.country_id',
    ', c.name AS city_name, s.name AS state_name, co.name AS country_name',
    $search,
    ['localities.name', 'localities.slug', 'c.name', 's.name']
);

$cities = $pdo->query("
    SELECT c.id, c.name, s.name AS state_name, co.name AS country_name, co.flag_icon 
    FROM `cities` c 
    JOIN `states` s ON s.id = c.state_id 
    JOIN `countries` co ON co.id = s.country_id 
    ORDER BY co.name ASC, c.name ASC
")->fetchAll();

$pageTitle = 'Neighborhoods & Localities';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title"><?= $pageTitle ?> (<?= $list['total'] ?? 0 ?>)</h2>
    <div class="d-flex gap-2">
      <form class="d-flex gap-2" method="get">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search neighborhoods…" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-sm btn-outline-secondary">Search</button>
      </form>
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New Neighborhood</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Neighborhood</th>
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
          <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
          <td><?= htmlspecialchars($p['city_name'] ?? '—') ?></td>
          <td>
            <span class="text-muted small">
              <?= htmlspecialchars($p['state_name'] ?? '') ?><?= !empty($p['country_name']) ? ', ' . htmlspecialchars($p['country_name']) : '' ?>
            </span>
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
                 data-confirm="Delete this neighborhood?">Delete</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($list['rows'])): ?>
        <tr><td colspan="8" class="text-center py-4 text-muted">No neighborhoods found.</td></tr>
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
  <h2 class="mb-0"><?= $id ? 'Edit Neighborhood' : 'New Neighborhood' ?></h2>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="post" class="adm-form" novalidate>
  <?= csrfField() ?>
  <div class="adm-card">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="adm-form-label">City *</label>
        <select name="city_id" class="form-select" required>
          <option value="">-- Select City --</option>
          <?php foreach ($cities as $c): ?>
          <option value="<?= $c['id'] ?>" <?= (($row['city_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?> (<?= htmlspecialchars($c['state_name']) ?>, <?= htmlspecialchars($c['country_name']) ?>)
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label class="adm-form-label">Neighborhood / Locality Name *</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name'] ?? '') ?>" placeholder="e.g. Bandra West, Downtown, Palm Jumeirah" required>
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
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-save me-2"></i><?= $id ? 'Update Neighborhood' : 'Save Neighborhood' ?></button>
      <a href="?" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
  </div>
</form>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
