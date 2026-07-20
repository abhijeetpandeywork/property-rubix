<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';

$pdo    = db();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);
$search = trim($_GET['q'] ?? '');

if ($action === 'delete' && $id) { csrfCheck(); crudDelete($pdo, 'submissions', $id, BASE_URL . 'admin/submissions/'); }

if ($action === 'view' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM submissions WHERE id=?");
    $stmt->execute([$id]);
    $row  = $stmt->fetch() ?: [];

    // Mark as read
    if ($row && $row['status'] === 'new') {
        $pdo->prepare("UPDATE submissions SET status='read' WHERE id=?")->execute([$id]);
        $row['status'] = 'read';
    }
}

$page = max(1, (int)($_GET['page'] ?? 1));
$where = '1=1'; $args = [];
if ($search) { $where .= ' AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)'; $args = ["%$search%","%$search%","%$search%"]; }
$total = $pdo->prepare("SELECT COUNT(*) FROM submissions WHERE $where"); $total->execute($args); $total = (int)$total->fetchColumn();
$rows = $pdo->prepare("SELECT * FROM submissions WHERE $where ORDER BY created_at DESC LIMIT 20 OFFSET ?");
$rows->execute(array_merge($args, [($page-1)*20]));

$pageTitle = 'Submissions';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'view' && $row): ?>
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="?" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
  <h2 class="mb-0">Submission from <?= htmlspecialchars($row['name']) ?></h2>
  <span class="adm-badge badge-<?= htmlspecialchars($row['status']) ?> ms-2"><?= ucfirst($row['status']) ?></span>
</div>
<div class="row g-4">
  <div class="col-lg-8">
    <div class="adm-card">
      <div class="row g-3">
        <div class="col-md-6"><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></div>
        <div class="col-md-6"><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($row['phone']) ?>"><?= htmlspecialchars($row['phone']) ?></a></div>
        <div class="col-md-6"><strong>Email:</strong> <?= htmlspecialchars($row['email'] ?? '—') ?></div>
        <div class="col-md-6"><strong>Form Type:</strong> <span class="adm-badge bg-secondary"><?= htmlspecialchars($row['form_type']) ?></span></div>
        <div class="col-md-6"><strong>IP Address:</strong> <?= htmlspecialchars($row['ip_address'] ?? '—') ?></div>
        <div class="col-md-6"><strong>Received:</strong> <?= htmlspecialchars($row['created_at']) ?></div>
        <div class="col-12"><strong>Page URL:</strong> <a href="<?= htmlspecialchars($row['page_url'] ?? '#') ?>" target="_blank" class="text-muted"><?= htmlspecialchars($row['page_url'] ?? '—') ?></a></div>
        <div class="col-12"><strong>Message:</strong><p class="text-muted mb-0 mt-1"><?= nl2br(htmlspecialchars($row['message'] ?? '')) ?></p></div>
      </div>
    </div>
  </div>
</div>

<?php else: ?>
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title">📋 Raw Submissions (<?= number_format($total) ?>)</h2>
    <form class="d-flex gap-2" method="get">
      <input type="text" name="q" class="form-control form-control-sm" placeholder="Search…" value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-sm btn-outline-secondary">Search</button>
    </form>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>Name</th><th>Phone</th><th>Form Type</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($rows->fetchAll() as $r): ?>
        <tr <?= $r['status']==='new' ? 'style="background:#f8fafc;font-weight:600"' : '' ?>>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= htmlspecialchars($r['phone']) ?></td>
          <td><span class="text-muted small"><?= htmlspecialchars($r['form_type']) ?></span></td>
          <td><span class="adm-badge badge-<?= htmlspecialchars($r['status']) ?>"><?= ucfirst($r['status']) ?></span></td>
          <td class="text-muted small"><?= date('M j, H:i', strtotime($r['created_at'])) ?></td>
          <td>
            <div class="actions">
              <a href="?action=view&id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
              <a href="?action=delete&id=<?= $r['id'] ?>&csrf_token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-danger" data-confirm="Delete?">Del</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
