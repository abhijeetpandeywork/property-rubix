<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';

$pdo    = db();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);
$search = trim($_GET['q'] ?? '');
$errors = [];
$row    = [];

// ── Delete
if ($action === 'delete' && $id) {
    csrfCheck();
    crudDelete($pdo, 'testimonials', $id, BASE_URL . 'admin/testimonials/');
}

// ── Load row for edit
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM `testimonials` WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: [];
}

// ── Save (new or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['new','edit'])) {
    csrfCheck();
    
    // Auto-gather all fields except id, created_at, updated_at
    $data = [];
    foreach ($_POST as $k => $v) {
        if (!in_array($k, ['csrf_token'])) {
            $data[$k] = $v;
        }
    }
    
    if (!$errors) {
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
            $stmt = $pdo->prepare("UPDATE `testimonials` SET $sets WHERE id=?");
            $stmt->execute([...array_values($data), $id]);
            logAction('UPDATE', 'testimonials', $id);
        } else {
            $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
            $vals = implode(', ', array_fill(0, count($data), '?'));
            $stmt = $pdo->prepare("INSERT INTO `testimonials` ($cols) VALUES ($vals)");
            $stmt->execute(array_values($data));
            $id = (int)$pdo->lastInsertId();
            logAction('CREATE', 'testimonials', $id);
        }
        header('Location: ' . BASE_URL . 'admin/testimonials/?saved=1');
        exit;
    }
}

// ── List
$list = crudList($pdo, 'testimonials', 20, 'id DESC', '', '', $search, ['id']);

$pageTitle = 'Testimonials';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title"><?= $pageTitle ?></h2>
    <div class="d-flex gap-2">
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>ID</th><th>Details</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($list['rows'] as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td>
            <pre style="margin:0; font-size:0.75rem; max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars(json_encode($p)) ?></pre>
          </td>
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
      </tbody>
    </table>
  </div>
</div>

<?php else: ?>
<!-- New/Edit Form -->
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="?" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
  <h2 class="mb-0"><?= $id ? 'Edit' : 'New' ?></h2>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="post" class="adm-form" novalidate>
  <?= csrfField() ?>
  <div class="adm-card">
    <div class="row g-3">
        <?php
        // Dynamically build form fields from table schema
        $stmt = $pdo->query("DESCRIBE `testimonials`");
        $columns = $stmt->fetchAll();
        foreach ($columns as $col) {
            $c = $col['Field'];
            if (in_array($c, ['id', 'created_at', 'updated_at'])) continue;
            $val = htmlspecialchars($row[$c] ?? '');
            echo '<div class="col-md-6">';
            echo '<label class="adm-form-label">'.ucfirst(str_replace('_',' ',$c)).'</label>';
            if (strpos($col['Type'], 'text') !== false) {
                echo '<textarea name="'.$c.'" class="form-control" rows="3">'.$val.'</textarea>';
            } else {
                echo '<input type="text" name="'.$c.'" class="form-control" value="'.$val.'">';
            }
            echo '</div>';
        }
        ?>
    </div>
    <div class="mt-4">
      <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-save me-2"></i>Save</button>
    </div>
  </div>
</form>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>