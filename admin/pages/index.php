<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';

$pdo    = db();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);
$search = trim($_GET['q'] ?? '');
$errors = [];
$row    = [];

if ($action === 'delete' && $id) { csrfCheck(); crudDelete($pdo, 'pages', $id, BASE_URL . 'admin/pages/'); }
if ($action === 'edit'   && $id) { $stmt = $pdo->prepare("SELECT * FROM pages WHERE id=?"); $stmt->execute([$id]); $row = $stmt->fetch() ?: []; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $title = trim($_POST['title'] ?? '');
    $slug  = trim($_POST['slug'] ?? '');
    if (!$title) $errors[] = 'Title is required.';

    if (!$errors) {
        if (!$slug) $slug = slugify($title);
        $slug = uniqueSlug('pages', $slug, $id ?: null);

        $data = [
            'title'            => $title,
            'slug'             => $slug,
            'body'             => $_POST['body'] ?? '',
            'status'           => $_POST['status'] ?? 'published',
            'meta_title'       => trim($_POST['meta_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
        ];

        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "`$k`=?", array_keys($data)));
            $pdo->prepare("UPDATE pages SET $sets WHERE id=?")->execute([...array_values($data), $id]);
            logAction('UPDATE', 'pages', $id);
        } else {
            $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
            $vals = implode(', ', array_fill(0, count($data), '?'));
            $pdo->prepare("INSERT INTO pages ($cols) VALUES ($vals)")->execute(array_values($data));
            $id = (int)$pdo->lastInsertId();
            logAction('CREATE', 'pages', $id);
        }
        header('Location: ' . BASE_URL . 'admin/pages/?saved=1');
        exit;
    }
}

$list = crudList($pdo, 'pages', 20, 'title ASC', '', '', $search, ['title','slug']);
$pageTitle = 'Pages';
require __DIR__ . '/../includes/header.php';
?>
<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title">📄 Pages (<?= $list['total'] ?>)</h2>
    <div class="d-flex gap-2">
      <form class="d-flex gap-2" method="get">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search…" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-sm btn-outline-secondary">Search</button>
      </form>
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New Page</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Updated</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($list['rows'] as $p): ?>
        <tr>
          <td class="fw-600"><?= htmlspecialchars($p['title']) ?></td>
          <td class="text-muted">/<?= htmlspecialchars($p['slug']) ?></td>
          <td><span class="adm-badge badge-<?= htmlspecialchars($p['status']) ?>"><?= ucfirst($p['status']) ?></span></td>
          <td class="text-muted small"><?= date('M j, Y', strtotime($p['updated_at'])) ?></td>
          <td>
            <div class="actions">
              <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="<?= BASE_URL ?><?= htmlspecialchars($p['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
              <a href="?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-danger" data-confirm="Delete this page?">Del</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php else: ?>
<div class="d-flex gap-3 align-items-center mb-4">
  <a href="?" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
  <h2 class="mb-0"><?= $id ? 'Edit Page' : 'New Page' ?></h2>
</div>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" class="adm-form">
  <?= csrfField() ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="adm-card">
        <div class="mb-3"><label class="adm-form-label">Title *</label><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title'] ?? '') ?>" required></div>
        <div class="mb-3"><label class="adm-form-label">Slug</label><input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($row['slug'] ?? '') ?>" placeholder="auto"></div>
        <div><label class="adm-form-label">Content (HTML)</label><textarea name="body" id="pageEditor" class="form-control" rows="15"><?= htmlspecialchars($row['body'] ?? '') ?></textarea></div>
      </div>
      <div class="adm-card">
        <div class="adm-card-title">SEO</div>
        <div class="mb-3"><label class="adm-form-label">Meta Title</label><input type="text" name="meta_title" class="form-control" value="<?= htmlspecialchars($row['meta_title'] ?? '') ?>"></div>
        <div><label class="adm-form-label">Meta Description</label><textarea name="meta_description" class="form-control" rows="2"><?= htmlspecialchars($row['meta_description'] ?? '') ?></textarea></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="adm-card">
        <div class="mb-3"><label class="adm-form-label">Status</label>
          <select name="status" class="form-select">
            <option value="published" <?= ($row['status']??'published')==='published'?'selected':'' ?>>Published</option>
            <option value="draft" <?= ($row['status']??'')==='draft'?'selected':'' ?>>Draft</option>
          </select>
        </div>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-save me-2"></i>Save Page</button>
        <a href="?" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>
<?php $extraScripts = '<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script><script>tinymce.init({selector:"#pageEditor",height:500,menubar:false,plugins:"lists link image code",toolbar:"undo redo | formatselect | bold italic | bullist numlist | link image | code"});</script>'; ?>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
