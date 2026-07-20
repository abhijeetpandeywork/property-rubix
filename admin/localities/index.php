<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';
require_once __DIR__ . '/../../app/helpers/slug.php';

$pdo    = db();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);
$search = trim($_GET['q'] ?? '');
$errors = [];
$row    = [];

// ── Delete
if ($action === 'delete' && $id) {
    csrfCheck();
    crudDelete($pdo, 'localities', $id, BASE_URL . 'admin/localities/');
}

// ── Load row for edit
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM localities WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: [];
}

// ── Save (new or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['new','edit'])) {
    csrfCheck();
    
    $city_id = (int)($_POST['city_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $status = $_POST['status'] ?? 'active';

    if (!$city_id) $errors[] = 'City is required.';
    if (!$name) $errors[] = 'Neighborhood name is required.';
    
    if (!$errors) {
        if (!$slug) $slug = slugify($name);
        
        // Ensure unique slug per city
        $stmt = $pdo->prepare("SELECT id FROM localities WHERE city_id=? AND slug=? AND id!=?");
        $stmt->execute([$city_id, $slug, $id]);
        if ($stmt->fetch()) {
            $slug .= '-' . rand(100,999);
        }

        $data = [
            'city_id' => $city_id,
            'name' => $name,
            'slug' => $slug,
            'status' => $status
        ];
        
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
            $stmt = $pdo->prepare("UPDATE localities SET $sets WHERE id=?");
            $stmt->execute([...array_values($data), $id]);
            logAction('UPDATE', 'localities', $id);
        } else {
            $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
            $vals = implode(', ', array_fill(0, count($data), '?'));
            $stmt = $pdo->prepare("INSERT INTO localities ($cols) VALUES ($vals)");
            $stmt->execute(array_values($data));
            $id = (int)$pdo->lastInsertId();
            logAction('CREATE', 'localities', $id);
        }
        header('Location: ' . BASE_URL . 'admin/localities/?saved=1');
        exit;
    }
}

// ── List
$list = crudList($pdo, 'localities', 20, 'id DESC', 
    'LEFT JOIN cities c ON c.id=localities.city_id', 
    ', c.name AS city_name', 
    $search, ['localities.name', 'c.name']);

$cities = $pdo->query("SELECT id, name FROM cities ORDER BY name")->fetchAll();

$pageTitle = 'Neighborhoods';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title"><?= $pageTitle ?> (<?= $list['total'] ?? 0 ?>)</h2>
    <div class="d-flex gap-2">
      <form class="d-flex gap-2" method="get">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search…" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-sm btn-outline-secondary">Search</button>
      </form>
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>Name</th><th>City</th><th>Slug</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($list['rows'] as $p): ?>
        <tr>
          <td class="fw-bold"><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['city_name']) ?></td>
          <td class="text-muted"><?= htmlspecialchars($p['slug']) ?></td>
          <td><span class="adm-badge badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
          <td>
            <div class="actions">
              <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= csrfToken() ?>"
                 class="btn btn-sm btn-outline-danger"
                 data-confirm="Delete?">Del</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($list['rows'])): ?>
        <tr><td colspan="5" class="text-center py-4 text-muted">No neighborhoods found.</td></tr>
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
                <?php foreach($cities as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($row['city_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="adm-form-label">Neighborhood Name *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
            <label class="adm-form-label">URL Slug (leave blank to auto-generate)</label>
            <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($row['slug'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="adm-form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" <?= ($row['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($row['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
    </div>
    <div class="mt-4">
      <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-save me-2"></i>Save Neighborhood</button>
    </div>
  </div>
</form>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
