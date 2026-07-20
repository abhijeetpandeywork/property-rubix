<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';

$pdo    = db();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);
$search = trim($_GET['q'] ?? '');
$errors = [];
$row    = [];

if ($action === 'delete' && $id) { csrfCheck(); crudDelete($pdo, 'leads', $id, BASE_URL . 'admin/leads/'); }

if ($action === 'view' && $id) {
    $stmt = $pdo->prepare("SELECT l.*, p.name AS project_name, c.name AS city_name FROM leads l LEFT JOIN projects p ON p.id=l.project_id LEFT JOIN cities c ON c.id=l.city_id WHERE l.id=?");
    $stmt->execute([$id]);
    $row  = $stmt->fetch() ?: [];

    // Update status to contacted if still new
    if ($row && $row['status'] === 'new') {
        $pdo->prepare("UPDATE leads SET status='contacted' WHERE id=?")->execute([$id]);
        $row['status'] = 'contacted';
        logAction('UPDATE', 'leads', $id, 'new', 'contacted');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_status') {
    csrfCheck();
    $lid    = (int)($_POST['lead_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $note   = trim($_POST['note'] ?? '');
    if ($lid && $status) {
        $old = $pdo->query("SELECT status FROM leads WHERE id=$lid")->fetchColumn();
        $pdo->prepare("UPDATE leads SET status=? WHERE id=?")->execute([$status, $lid]);
        if ($note) {
            $pdo->prepare("INSERT INTO lead_notes (lead_id, user_id, note) VALUES (?,?,?)")
                ->execute([$lid, currentUser()['id'], $note]);
        }
        logAction('UPDATE_STATUS', 'leads', $lid, $old, $status);
        header('Location: ' . BASE_URL . 'admin/leads/?action=view&id=' . $lid . '&saved=1');
        exit;
    }
}

// Filter options
$statusFilter = $_GET['status'] ?? '';
$sourceFilter = $_GET['source'] ?? '';
$extraWhere = '1=1'; $extraArgs = [];
if ($statusFilter) { $extraWhere .= ' AND l.status=?'; $extraArgs[] = $statusFilter; }
if ($sourceFilter) { $extraWhere .= ' AND l.source=?'; $extraArgs[] = $sourceFilter; }
if ($search) { $extraWhere .= ' AND (l.name LIKE ? OR l.phone LIKE ? OR l.email LIKE ?)'; $extraArgs = array_merge($extraArgs, ["%$search%","%$search%","%$search%"]); }

$page = max(1,(int)($_GET['page']??1));
$total = $pdo->prepare("SELECT COUNT(*) FROM leads l WHERE $extraWhere"); $total->execute($extraArgs); $total = (int)$total->fetchColumn();
$rows = $pdo->prepare("SELECT l.*, p.name AS project_name FROM leads l LEFT JOIN projects p ON p.id=l.project_id WHERE $extraWhere ORDER BY l.created_at DESC LIMIT 20 OFFSET ?");
$rows->execute(array_merge($extraArgs, [($page-1)*20]));

$pageTitle = 'CRM — Leads';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'view' && $row): ?>
<!-- Lead Detail View -->
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="?" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
  <h2 class="mb-0">Lead: <?= htmlspecialchars($row['name']) ?></h2>
  <span class="adm-badge badge-<?= htmlspecialchars($row['status']) ?> ms-2"><?= ucfirst($row['status']) ?></span>
</div>
<div class="row g-4">
  <div class="col-lg-8">
    <div class="adm-card">
      <div class="adm-card-title">Lead Information</div>
      <div class="row g-3">
        <div class="col-md-6"><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></div>
        <div class="col-md-6"><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($row['phone']) ?>"><?= htmlspecialchars($row['phone']) ?></a></div>
        <div class="col-md-6"><strong>Email:</strong> <?= htmlspecialchars($row['email'] ?? '—') ?></div>
        <div class="col-md-6"><strong>Source:</strong> <?= str_replace('_',' ',ucfirst($row['source'])) ?></div>
        <div class="col-md-6"><strong>Project:</strong> <?= htmlspecialchars($row['project_name'] ?? '—') ?></div>
        <div class="col-md-6"><strong>City:</strong> <?= htmlspecialchars($row['city_name'] ?? '—') ?></div>
        <div class="col-12"><strong>Message:</strong><p class="text-muted mb-0"><?= nl2br(htmlspecialchars($row['message'] ?? '')) ?></p></div>
        <div class="col-md-6"><strong>IP:</strong> <?= htmlspecialchars($row['ip_address'] ?? '—') ?></div>
        <div class="col-md-6"><strong>Received:</strong> <?= htmlspecialchars($row['created_at']) ?></div>
      </div>
      <div class="mt-3 d-flex gap-2">
        <a href="tel:<?= htmlspecialchars(preg_replace('/[^+\d]/','', $row['phone'])) ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-phone me-1"></i>Call</a>
        <a href="https://wa.me/<?= htmlspecialchars(preg_replace('/[^+\d]/','', $row['phone'])) ?>" target="_blank" class="btn btn-sm" style="background:#25d366;color:white"><i class="fab fa-whatsapp me-1"></i>WhatsApp</a>
        <?php if ($row['email']): ?><a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-envelope me-1"></i>Email</a><?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="adm-card">
      <div class="adm-card-title">Update Status</div>
      <form method="post" action="?action=update_status">
        <?= csrfField() ?>
        <input type="hidden" name="lead_id" value="<?= $row['id'] ?>">
        <div class="mb-3">
          <select name="status" class="form-select">
            <?php foreach (['new','contacted','qualified','converted','lost'] as $s): ?>
            <option value="<?= $s ?>" <?= $row['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <textarea name="note" class="form-control" rows="3" placeholder="Add a note…"></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Update</button>
      </form>
    </div>
  </div>
</div>

<?php else: ?>
<!-- Leads List -->
<div class="adm-table-wrap">
  <div class="adm-table-header flex-wrap gap-2">
    <h2 class="adm-table-title">🎯 Leads (<?= number_format($total) ?>)</h2>
    <form class="d-flex gap-2 flex-wrap" method="get">
      <input type="text" name="q" class="form-control form-control-sm" placeholder="Search name/phone…" value="<?= htmlspecialchars($search) ?>">
      <select name="status" class="form-select form-select-sm w-auto">
        <option value="">All Status</option>
        <?php foreach (['new','contacted','qualified','converted','lost'] as $s): ?>
        <option value="<?= $s ?>" <?= $statusFilter===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
      <select name="source" class="form-select form-select-sm w-auto">
        <option value="">All Sources</option>
        <?php foreach (['site_visit_form','contact_form','enquiry_form','website','call','referral'] as $s): ?>
        <option value="<?= $s ?>" <?= $sourceFilter===$s?'selected':'' ?>><?= str_replace('_',' ',ucfirst($s)) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-sm btn-outline-secondary">Filter</button>
    </form>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>Name</th><th>Phone</th><th>Project</th><th>Source</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($rows->fetchAll() as $l): ?>
        <tr>
          <td class="fw-600"><?= htmlspecialchars($l['name']) ?></td>
          <td><?= htmlspecialchars($l['phone']) ?></td>
          <td><?= htmlspecialchars($l['project_name'] ?? '—') ?></td>
          <td class="text-muted small"><?= str_replace('_',' ',ucfirst($l['source'])) ?></td>
          <td><span class="adm-badge badge-<?= htmlspecialchars($l['status']) ?>"><?= ucfirst($l['status']) ?></span></td>
          <td class="text-muted small"><?= date('M j, H:i', strtotime($l['created_at'])) ?></td>
          <td>
            <div class="actions">
              <a href="?action=view&id=<?= $l['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
              <a href="?action=delete&id=<?= $l['id'] ?>&csrf_token=<?= csrfToken() ?>"
                 class="btn btn-sm btn-outline-danger" data-confirm="Delete this lead?">Del</a>
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
