<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';

$pdo = db();
$search = trim($_GET['q'] ?? '');

$where = '1=1'; $args = [];
if ($search) {
    $where .= ' AND (u.name LIKE ? OR al.action LIKE ? OR al.entity LIKE ?)';
    $args = ["%$search%","%$search%","%$search%"];
}

$page = max(1, (int)($_GET['page'] ?? 1));
$total = $pdo->prepare("SELECT COUNT(*) FROM audit_log al LEFT JOIN users u ON u.id=al.user_id WHERE $where");
$total->execute($args); $total = (int)$total->fetchColumn();

$rows = $pdo->prepare("SELECT al.*, u.name AS user_name, u.email AS user_email FROM audit_log al LEFT JOIN users u ON u.id=al.user_id WHERE $where ORDER BY al.created_at DESC LIMIT 50 OFFSET ?");
$rows->execute(array_merge($args, [($page-1)*50]));

$pageTitle = 'Audit Log';
require __DIR__ . '/../includes/header.php';
?>

<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title">📜 System Audit Log (<?= number_format($total) ?> records)</h2>
    <form class="d-flex gap-2" method="get">
      <input type="text" name="q" class="form-control form-control-sm" placeholder="Search user, action, entity…" value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-sm btn-outline-secondary">Search</button>
    </form>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>Date & Time</th><th>User</th><th>Action</th><th>Entity</th><th>Record ID</th><th>Details</th><th>IP Address</th></tr></thead>
      <tbody>
        <?php foreach ($rows->fetchAll() as $r): ?>
        <tr>
          <td class="text-muted small"><?= htmlspecialchars($r['created_at']) ?></td>
          <td>
            <div class="fw-600"><?= htmlspecialchars($r['user_name'] ?? 'System') ?></div>
            <div class="small text-muted"><?= htmlspecialchars($r['user_email'] ?? '') ?></div>
          </td>
          <td><span class="adm-badge bg-light text-dark border"><?= htmlspecialchars($r['action']) ?></span></td>
          <td><?= htmlspecialchars($r['entity'] ?? '—') ?></td>
          <td><?= !empty($r['entity_id']) ? '#' . $r['entity_id'] : '—' ?></td>
          <td class="small text-muted" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            <?php if ($r['old_value'] || $r['new_value']): ?>
              <?= htmlspecialchars($r['old_value']) ?> → <?= htmlspecialchars($r['new_value']) ?>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td class="text-muted small"><?= htmlspecialchars($r['ip_address']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if ($total > 50): ?>
  <div class="p-3">
    <nav><ul class="pagination mb-0">
      <?php $pages = ceil($total/50); for ($i=max(1,$page-3); $i<=min($pages,$page+3); $i++): ?>
      <li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?page=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a></li>
      <?php endfor; ?>
    </ul></nav>
  </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
