<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';

$pdo    = db();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);
$search = trim($_GET['q'] ?? '');
$errors = [];
$row    = [];

if ($action === 'delete' && $id) { csrfCheck(); crudDelete($pdo, 'builders', $id, BASE_URL . 'admin/builders/'); }
if ($action === 'edit'   && $id) { $stmt = $pdo->prepare("SELECT * FROM builders WHERE id=?"); $stmt->execute([$id]); $row = $stmt->fetch() ?: []; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $name = trim($_POST['name'] ?? '');
    if (!$name) $errors[] = 'Name required.';

    if (!$errors) {
        $slug = trim($_POST['slug'] ?? '') ?: slugify($name);
        $slug = uniqueSlug('builders', $slug, $id ?: null);

        $data = [
            'name'             => $name,
            'slug'             => $slug,
            'country_id'       => (int)($_POST['country_id'] ?? 0) ?: null,
            'website'          => trim($_POST['website']  ?? ''),
            'description'      => trim($_POST['description'] ?? ''),
            'established_year' => trim($_POST['established_year'] ?? '') ?: null,
            'total_projects'   => (int)($_POST['total_projects'] ?? 0),
            'status'           => $_POST['status'] ?? 'active',
        ];

        if (!empty($_FILES['logo']['name'])) {
            $up = uploadImage($_FILES['logo'], 'builders');
            if ($up['success']) $data['logo'] = $up['path'];
            else $errors[] = $up['error'];
        }

        if (!$errors) {
            if ($id) {
                $sets = implode(', ', array_map(fn($k) => "`$k`=?", array_keys($data)));
                $pdo->prepare("UPDATE builders SET $sets WHERE id=?")->execute([...array_values($data), $id]);
                logAction('UPDATE', 'builders', $id);
            } else {
                $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
                $vals = implode(', ', array_fill(0, count($data), '?'));
                $pdo->prepare("INSERT INTO builders ($cols) VALUES ($vals)")->execute(array_values($data));
                logAction('CREATE', 'builders', (int)$pdo->lastInsertId());
            }
            header('Location: ' . BASE_URL . 'admin/builders/?saved=1');
            exit;
        }
    }
}

$list = crudList($pdo, 'builders', 20, 'builders.name ASC',
    'LEFT JOIN countries co ON co.id=builders.country_id',
    ', co.name AS country_name', $search, ['builders.name']
);
$countries = $pdo->query("SELECT * FROM countries ORDER BY name")->fetchAll();
$pageTitle = 'Builders / Developers';
require __DIR__ . '/../includes/header.php';
?>
<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title">👷 Builders (<?= $list['total'] ?>)</h2>
    <div class="d-flex gap-2">
      <form class="d-flex gap-2" method="get"><input type="text" name="q" class="form-control form-control-sm" placeholder="Search…" value="<?= htmlspecialchars($search) ?>"><button class="btn btn-sm btn-outline-secondary">Search</button></form>
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>Logo</th><th>Name</th><th>Country</th><th>Projects</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($list['rows'] as $b): ?>
        <tr>
          <td><?php if ($b['logo']): ?><img src="<?= upload($b['logo']) ?>" style="height:40px;object-fit:contain"><?php else: ?><div class="builder-logo-placeholder" style="width:48px;height:36px;font-size:0.8rem"><?= e(substr($b['name'],0,2)) ?></div><?php endif; ?></td>
          <td class="fw-600"><?= htmlspecialchars($b['name']) ?></td>
          <td><?= htmlspecialchars($b['country_name'] ?? '—') ?></td>
          <td><?= (int)$b['total_projects'] ?></td>
          <td><span class="adm-badge badge-<?= htmlspecialchars($b['status']) ?>"><?= ucfirst($b['status']) ?></span></td>
          <td><div class="actions">
            <a href="?action=edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <a href="?action=delete&id=<?= $b['id'] ?>&csrf_token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-danger" data-confirm="Delete builder?">Del</a>
          </div></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php else: ?>
<div class="d-flex gap-3 align-items-center mb-4"><a href="?" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a><h2 class="mb-0"><?= $id ? 'Edit Builder' : 'New Builder' ?></h2></div>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="adm-form">
  <?= csrfField() ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="adm-card">
        <div class="row g-3">
          <div class="col-md-8"><label class="adm-form-label">Name *</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name'] ?? '') ?>" required></div>
          <div class="col-md-4"><label class="adm-form-label">Slug</label><input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($row['slug'] ?? '') ?>"></div>
          <div class="col-md-6"><label class="adm-form-label">Country</label>
            <select name="country_id" class="form-select"><option value="">— None —</option>
            <?php foreach ($countries as $c): ?><option value="<?= $c['id'] ?>" <?= ($row['country_id']??'')==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option><?php endforeach; ?></select>
          </div>
          <div class="col-md-3"><label class="adm-form-label">Est. Year</label><input type="text" name="established_year" class="form-control" value="<?= htmlspecialchars($row['established_year'] ?? '') ?>"></div>
          <div class="col-md-3"><label class="adm-form-label">Total Projects</label><input type="number" name="total_projects" class="form-control" value="<?= htmlspecialchars($row['total_projects'] ?? 0) ?>"></div>
          <div class="col-md-8"><label class="adm-form-label">Website</label><input type="url" name="website" class="form-control" value="<?= htmlspecialchars($row['website'] ?? '') ?>"></div>
          <div class="col-md-4"><label class="adm-form-label">Status</label>
            <select name="status" class="form-select"><option value="active" <?= ($row['status']??'active')==='active'?'selected':'' ?>>Active</option><option value="inactive" <?= ($row['status']??'')==='inactive'?'selected':'' ?>>Inactive</option></select>
          </div>
          <div class="col-12"><label class="adm-form-label">Description</label><textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($row['description'] ?? '') ?></textarea></div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="adm-card">
        <div class="adm-card-title">Logo</div>
        <?php if (!empty($row['logo'])): ?><img src="<?= upload($row['logo']) ?>" class="img-fluid mb-2" style="height:60px;object-fit:contain"><?php endif; ?>
        <input type="file" name="logo" class="form-control" accept="image/*">
      </div>
      <div class="d-grid gap-2 mt-3">
        <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-save me-2"></i>Save</button>
        <a href="?" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
