<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';

$pdo    = db();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);
$search = trim($_GET['q'] ?? '');
$errors = [];
$row    = [];

if ($action === 'delete' && $id) { csrfCheck(); crudDelete($pdo, 'blog_posts', $id, BASE_URL . 'admin/blog/'); }
if ($action === 'edit'   && $id) { $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id=?"); $stmt->execute([$id]); $row = $stmt->fetch() ?: []; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $title  = trim($_POST['title'] ?? '');
    $slug   = trim($_POST['slug']  ?? '');
    $body   = $_POST['body'] ?? '';

    if (!$title) $errors[] = 'Title required.';
    if (!$body)  $errors[] = 'Content required.';

    if (!$errors) {
        if (!$slug) $slug = slugify($title);
        $slug = uniqueSlug('blog_posts', $slug, $id ?: null);

        $data = [
            'category_id'      => (int)($_POST['category_id'] ?? 0) ?: null,
            'title'            => $title,
            'slug'             => $slug,
            'author'           => trim($_POST['author']  ?? 'Admin'),
            'excerpt'          => trim($_POST['excerpt'] ?? ''),
            'body'             => $body,
            'status'           => $_POST['status'] ?? 'draft',
            'published_at'     => $_POST['status'] === 'published' ? date('Y-m-d H:i:s') : null,
            'meta_title'       => trim($_POST['meta_title']       ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
        ];

        if (!empty($_FILES['cover_image']['name'])) {
            $up = uploadImage($_FILES['cover_image'], 'blog');
            if ($up['success']) $data['cover_image'] = $up['path'];
            else $errors[] = $up['error'];
        }
    }

    if (!$errors) {
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "`$k`=?", array_keys($data)));
            $pdo->prepare("UPDATE blog_posts SET $sets WHERE id=?")->execute([...array_values($data), $id]);
            logAction('UPDATE', 'blog_posts', $id);
        } else {
            $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
            $vals = implode(', ', array_fill(0, count($data), '?'));
            $pdo->prepare("INSERT INTO blog_posts ($cols) VALUES ($vals)")->execute(array_values($data));
            $id = (int)$pdo->lastInsertId();
            logAction('CREATE', 'blog_posts', $id);
        }
        header('Location: ' . BASE_URL . 'admin/blog/?saved=1');
        exit;
    }
}

$list = crudList($pdo, 'blog_posts', 15, 'blog_posts.published_at DESC',
    'LEFT JOIN blog_categories bc ON bc.id=blog_posts.category_id',
    ', bc.name AS category_name', $search, ['blog_posts.title','blog_posts.author']
);
$categories = $pdo->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll();
$pageTitle = 'Blog Posts';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title">✍️ Blog Posts (<?= $list['total'] ?>)</h2>
    <div class="d-flex gap-2">
      <form class="d-flex gap-2" method="get">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search…" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-sm btn-outline-secondary">Search</button>
      </form>
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New Post</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($list['rows'] as $p): ?>
        <tr>
          <td><div class="fw-600"><?= htmlspecialchars($p['title']) ?></div><small class="text-muted">/blog/<?= htmlspecialchars($p['slug']) ?></small></td>
          <td><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($p['author']) ?></td>
          <td><span class="adm-badge badge-<?= htmlspecialchars($p['status']) ?>"><?= ucfirst($p['status']) ?></span></td>
          <td class="text-muted small"><?= $p['published_at'] ? date('M j, Y', strtotime($p['published_at'])) : '—' ?></td>
          <td>
            <div class="actions">
              <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="<?= BASE_URL ?>blog/<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
              <a href="?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-danger" data-confirm="Delete this post?">Del</a>
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
  <h2 class="mb-0"><?= $id ? 'Edit Post' : 'New Post' ?></h2>
</div>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="adm-form">
  <?= csrfField() ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="adm-card">
        <div class="mb-3"><label class="adm-form-label">Title *</label><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title'] ?? '') ?>" required></div>
        <div class="mb-3"><label class="adm-form-label">Slug</label><input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($row['slug'] ?? '') ?>" placeholder="auto"></div>
        <div class="mb-3"><label class="adm-form-label">Excerpt</label><textarea name="excerpt" class="form-control" rows="2"><?= htmlspecialchars($row['excerpt'] ?? '') ?></textarea></div>
        <div><label class="adm-form-label">Content *</label><textarea name="body" id="blogEditor" class="form-control" rows="14"><?= htmlspecialchars($row['body'] ?? '') ?></textarea></div>
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
            <option value="draft" <?= ($row['status']??'draft')==='draft'?'selected':'' ?>>Draft</option>
            <option value="published" <?= ($row['status']??'')==='published'?'selected':'' ?>>Published</option>
          </select>
        </div>
        <div class="mb-3"><label class="adm-form-label">Category</label>
          <select name="category_id" class="form-select">
            <option value="">— None —</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($row['category_id']??'')==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3"><label class="adm-form-label">Author</label><input type="text" name="author" class="form-control" value="<?= htmlspecialchars($row['author'] ?? 'Admin') ?>"></div>
        <div><label class="adm-form-label">Cover Image</label>
          <?php if (!empty($row['cover_image'])): ?><img src="<?= upload($row['cover_image']) ?>" class="img-fluid rounded mb-2" style="height:80px;object-fit:cover"><?php endif; ?>
          <input type="file" name="cover_image" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-save me-2"></i>Save Post</button>
        <a href="?" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>
<?php $extraScripts = '<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script><script>tinymce.init({selector:"#blogEditor",height:400,menubar:false,plugins:"lists link image code",toolbar:"undo redo | formatselect | bold italic | bullist numlist | link image | code"});</script>'; ?>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
