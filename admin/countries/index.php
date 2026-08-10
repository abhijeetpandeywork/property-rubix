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
        crudDelete($pdo, 'countries', $id, BASE_URL . 'admin/countries/');
    } catch (PDOException $e) {
        $errors[] = 'Cannot delete country: ' . $e->getMessage();
    }
}

// ── Load row for edit
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM `countries` WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: [];
    if (!$row) {
        header('Location: ' . BASE_URL . 'admin/countries/');
        exit;
    }
}

// ── Save (new or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['new','edit'])) {
    csrfCheck();
    
    $name       = trim($_POST['name'] ?? '');
    $slug       = trim($_POST['slug'] ?? '');
    $continent  = trim($_POST['continent'] ?? 'Asia-Pacific');
    $flagIcon   = trim($_POST['flag_icon'] ?? '');
    $sortOrder  = (int)($_POST['sort_order'] ?? 0);
    $status     = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';
    
    if (!$name) {
        $errors[] = 'Country name is required.';
    }
    
    if (!$errors) {
        if (!$slug) {
            $slug = slugify($name);
        } else {
            $slug = slugify($slug);
        }
        $slug = uniqueSlug('countries', $slug, $id ?: null);
        
        $data = [
            'name'       => $name,
            'slug'       => $slug,
            'continent'  => $continent,
            'flag_icon'  => $flagIcon ?: null,
            'sort_order' => $sortOrder,
            'status'     => $status,
        ];
        
        try {
            if ($id) {
                $sets = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
                $stmt = $pdo->prepare("UPDATE `countries` SET $sets WHERE id=?");
                $stmt->execute([...array_values($data), $id]);
                logAction('UPDATE', 'countries', $id);
            } else {
                $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
                $vals = implode(', ', array_fill(0, count($data), '?'));
                $stmt = $pdo->prepare("INSERT INTO `countries` ($cols) VALUES ($vals)");
                $stmt->execute(array_values($data));
                $id = (int)$pdo->lastInsertId();
                logAction('CREATE', 'countries', $id);
            }
            header('Location: ' . BASE_URL . 'admin/countries/?saved=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// ── List
$list = crudList($pdo, 'countries', 20, 'sort_order ASC, name ASC', '', '', $search, ['name', 'slug', 'continent']);

$continents = ['Asia-Pacific', 'North America', 'Europe', 'Middle East', 'Africa', 'South America'];

$pageTitle = 'Countries';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title"><?= $pageTitle ?> (<?= $list['total'] ?? 0 ?>)</h2>
    <div class="d-flex gap-2">
      <form class="d-flex gap-2" method="get">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search countries…" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-sm btn-outline-secondary">Search</button>
      </form>
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New Country</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Flag & Name</th>
          <th>Slug</th>
          <th>Continent</th>
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
            <span class="me-1 fs-5"><?= htmlspecialchars($p['flag_icon'] ?? '🌐') ?></span>
            <strong><?= htmlspecialchars($p['name']) ?></strong>
          </td>
          <td><code class="text-muted"><?= htmlspecialchars($p['slug']) ?></code></td>
          <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['continent'] ?? 'Asia-Pacific') ?></span></td>
          <td><?= (int)$p['sort_order'] ?></td>
          <td>
            <span class="adm-badge badge-<?= $p['status'] ?? 'active' ?>"><?= ucfirst($p['status'] ?? 'active') ?></span>
          </td>
          <td>
            <div class="actions">
              <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= csrfToken() ?>"
                 class="btn btn-sm btn-outline-danger"
                 data-confirm="Delete this country? All associated states and cities will also be removed.">Delete</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($list['rows'])): ?>
        <tr><td colspan="7" class="text-center py-4 text-muted">No countries found.</td></tr>
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
  <h2 class="mb-0"><?= $id ? 'Edit Country' : 'New Country' ?></h2>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="post" class="adm-form" novalidate>
  <?= csrfField() ?>
  <div class="adm-card">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="adm-form-label">Country Name *</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name'] ?? '') ?>" placeholder="e.g. India, United Arab Emirates" required>
      </div>

      <div class="col-md-6">
        <label class="adm-form-label">URL Slug</label>
        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($row['slug'] ?? '') ?>" placeholder="auto-generated from name if blank">
      </div>

      <div class="col-md-4">
        <label class="adm-form-label">Continent *</label>
        <select name="continent" class="form-select">
          <?php foreach ($continents as $continent): ?>
          <option value="<?= $continent ?>" <?= (($row['continent'] ?? 'Asia-Pacific') === $continent) ? 'selected' : '' ?>><?= $continent ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="adm-form-label">Flag Emoji / Icon</label>
        <input type="text" name="flag_icon" class="form-control" value="<?= htmlspecialchars($row['flag_icon'] ?? '') ?>" placeholder="e.g. 🇮🇳, 🇦🇪, 🇨🇦, 🇺🇸">
      </div>

      <div class="col-md-2">
        <label class="adm-form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($row['sort_order'] ?? 0) ?>">
      </div>

      <div class="col-md-2">
        <label class="adm-form-label">Status</label>
        <select name="status" class="form-select">
          <option value="active" <?= (($row['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Active</option>
          <option value="inactive" <?= (($row['status'] ?? 'active') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-save me-2"></i><?= $id ? 'Update Country' : 'Save Country' ?></button>
      <a href="?" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
  </div>
</form>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>