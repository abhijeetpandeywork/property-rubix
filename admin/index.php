<?php
require_once __DIR__ . '/includes/auth_check.php';

$pdo = db();

// KPI data
$totalLeads     = $pdo->query("SELECT COUNT(*) FROM leads WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")->fetchColumn();
$newLeads       = $pdo->query("SELECT COUNT(*) FROM leads WHERE status='new'")->fetchColumn();
$activeProjects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$totalBuilders  = $pdo->query("SELECT COUNT(*) FROM builders WHERE status='active'")->fetchColumn();
$totalCities    = $pdo->query("SELECT COUNT(*) FROM cities WHERE status='active'")->fetchColumn();
$openDeals      = $pdo->query("SELECT COUNT(*) FROM localities WHERE status='active'")->fetchColumn(); // repurposed for Localities since Deals are removed
$totalProperties= $pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn();
$submissions    = $pdo->query("SELECT COUNT(*) FROM submissions WHERE status='new'")->fetchColumn();
$subscribers    = $pdo->query("SELECT COUNT(*) FROM subscribers WHERE status='active'")->fetchColumn();

// Conversion rate
$converted      = $pdo->query("SELECT COUNT(*) FROM leads WHERE status='converted'")->fetchColumn();
$totalLeadsAll  = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
$convRate       = $totalLeadsAll > 0 ? round($converted / $totalLeadsAll * 100, 1) : 0;

// Recent leads
$recentLeads = $pdo->query(
    "SELECT l.*, p.name AS project_name FROM leads l
     LEFT JOIN projects p ON p.id=l.project_id
     ORDER BY l.created_at DESC LIMIT 8"
)->fetchAll();

// Leads by source (for chart)
$leadsBySource = $pdo->query(
    "SELECT source, COUNT(*) AS cnt FROM leads GROUP BY source ORDER BY cnt DESC"
)->fetchAll();

// Leads by city (for chart)
$leadsByCity = $pdo->query(
    "SELECT c.name AS city_name, COUNT(l.id) AS cnt FROM leads l
     JOIN cities c ON c.id=l.city_id
     GROUP BY l.city_id ORDER BY cnt DESC LIMIT 8"
)->fetchAll();

// Recent audit log
$auditLog = $pdo->query(
    "SELECT al.*, u.name AS user_name FROM audit_log al
     LEFT JOIN users u ON u.id=al.user_id
     ORDER BY al.created_at DESC LIMIT 10"
)->fetchAll();

$pageTitle = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="kpi-card">
      <div class="kpi-icon green"><i class="fas fa-funnel-dollar"></i></div>
      <div>
        <div class="kpi-num"><?= number_format($totalLeads) ?></div>
        <div class="kpi-label">Leads This Month</div>
        <div class="kpi-delta">↑ <?= $newLeads ?> new</div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="kpi-card">
      <div class="kpi-icon blue"><i class="fas fa-building"></i></div>
      <div>
        <div class="kpi-num"><?= number_format($activeProjects) ?></div>
        <div class="kpi-label">Total Projects</div>
        <div class="kpi-delta"><?= $totalBuilders ?> developers</div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="kpi-card">
      <div class="kpi-icon amber"><i class="fas fa-chart-line"></i></div>
      <div>
        <div class="kpi-num"><?= $convRate ?>%</div>
        <div class="kpi-label">Conversion Rate</div>
        <div class="kpi-delta"><?= $converted ?> converted</div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="kpi-card">
      <div class="kpi-icon purple"><i class="fas fa-home"></i></div>
      <div>
        <div class="kpi-num"><?= number_format($totalProperties) ?></div>
        <div class="kpi-label">Total Properties</div>
        <div class="kpi-delta"><?= $submissions ?> unread submissions</div>
      </div>
    </div>
  </div>
</div>

<!-- Quick stats strip -->
<div class="row g-3 mb-4">
  <?php $quickStats = [
    ['icon'=>'fas fa-map-marker-alt','val'=>$totalCities,'label'=>'Cities'],
    ['icon'=>'fas fa-map','val'=>$openDeals,'label'=>'Localities'],
    ['icon'=>'fas fa-envelope','val'=>$subscribers,'label'=>'Subscribers'],
    ['icon'=>'fas fa-inbox','val'=>$submissions,'label'=>'New Submissions'],
  ]; foreach ($quickStats as $qs): ?>
  <div class="col-6 col-xl-3">
    <div class="kpi-card" style="padding:16px">
      <div class="kpi-icon green" style="width:40px;height:40px;font-size:0.9rem">
        <i class="<?= $qs['icon'] ?>"></i>
      </div>
      <div>
        <div class="kpi-num" style="font-size:1.3rem"><?= number_format($qs['val']) ?></div>
        <div class="kpi-label"><?= $qs['label'] ?></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts + Recent Leads -->
<div class="row g-4 mb-4">
  <!-- Leads by Source Chart -->
  <div class="col-lg-5">
    <div class="adm-card">
      <div class="adm-card-title">📊 Leads by Source</div>
      <canvas id="chartLeadsSource" height="240"></canvas>
    </div>
  </div>

  <!-- Leads by City Chart -->
  <div class="col-lg-7">
    <div class="adm-card">
      <div class="adm-card-title">🌆 Leads by City (Top 8)</div>
      <canvas id="chartLeadsCity" height="240"></canvas>
    </div>
  </div>
</div>

<!-- Recent Leads Table -->
<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="adm-table-wrap">
      <div class="adm-table-header">
        <h2 class="adm-table-title mb-0">🎯 Recent Leads</h2>
        <a href="<?= BASE_URL ?>admin/leads/" class="btn btn-sm btn-primary">View All</a>
      </div>
      <div class="table-responsive">
        <table class="adm-table">
          <thead>
            <tr><th>Name</th><th>Phone</th><th>Source</th><th>Status</th><th>Date</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($recentLeads as $lead): ?>
            <tr>
              <td>
                <div class="fw-600"><?= htmlspecialchars($lead['name']) ?></div>
                <?php if ($lead['project_name']): ?><small class="text-muted"><?= htmlspecialchars($lead['project_name']) ?></small><?php endif; ?>
              </td>
              <td><?= htmlspecialchars($lead['phone']) ?></td>
              <td><span class="text-muted small"><?= str_replace('_',' ',ucfirst($lead['source'])) ?></span></td>
              <td><span class="adm-badge badge-<?= htmlspecialchars($lead['status']) ?>"><?= ucfirst($lead['status']) ?></span></td>
              <td class="text-muted small"><?= date('M j', strtotime($lead['created_at'])) ?></td>
              <td>
                <a href="<?= BASE_URL ?>admin/leads/?action=view&id=<?= $lead['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Audit Log -->
  <div class="col-lg-4">
    <div class="adm-card" style="padding:0;overflow:hidden">
      <div class="adm-table-header">
        <h2 class="adm-table-title mb-0">📜 Recent Activity</h2>
        <a href="<?= BASE_URL ?>admin/audit-log/" class="btn btn-sm btn-outline-secondary">View All</a>
      </div>
      <ul class="list-unstyled mb-0">
        <?php foreach ($auditLog as $log): ?>
        <li class="px-4 py-2 border-bottom" style="font-size:0.8rem">
          <span class="fw-600"><?= htmlspecialchars($log['user_name'] ?? 'System') ?></span>
          <span class="text-muted"> <?= htmlspecialchars(strtolower($log['action'])) ?>d </span>
          <span><?= htmlspecialchars($log['entity'] ?? '') ?></span>
          <span class="d-block text-muted" style="font-size:0.7rem"><?= htmlspecialchars(View::timeAgo($log['created_at'])) ?></span>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>

<!-- Quick actions -->
<div class="row g-3">
  <div class="col-12">
    <div class="adm-card">
      <div class="adm-card-title">⚡ Quick Actions</div>
      <div class="d-flex gap-3 flex-wrap">
        <a href="<?= BASE_URL ?>admin/projects/?action=new" class="btn btn-primary"><i class="fas fa-plus me-2"></i>New Project</a>
        <a href="<?= BASE_URL ?>admin/blog/?action=new"     class="btn btn-outline-primary"><i class="fas fa-pencil me-2"></i>New Blog Post</a>
        <a href="<?= BASE_URL ?>admin/leads/"               class="btn btn-outline-secondary"><i class="fas fa-list me-2"></i>All Leads</a>
        <a href="<?= BASE_URL ?>admin/submissions/"         class="btn btn-outline-secondary"><i class="fas fa-inbox me-2"></i>New Submissions</a>
        <a href="<?= BASE_URL ?>admin/system-verifier/"     class="btn btn-outline-secondary"><i class="fas fa-heartbeat me-2"></i>System Health</a>
        <a href="<?= BASE_URL ?>admin/database-backup/"     class="btn btn-outline-warning"><i class="fas fa-download me-2"></i>Backup DB</a>
      </div>
    </div>
  </div>
</div>

<?php
$extraScripts = '
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
const palette = ["#16a34a","#0ea5e9","#f59e0b","#8b5cf6","#ef4444","#06b6d4","#f97316","#84cc16"];

// Leads by source
new Chart(document.getElementById("chartLeadsSource"), {
  type: "doughnut",
  data: {
    labels: ' . json_encode(array_column($leadsBySource, 'source')) . ',
    datasets: [{ data: ' . json_encode(array_column($leadsBySource, 'cnt')) . ', backgroundColor: palette, borderWidth: 0 }]
  },
  options: { responsive:true, plugins:{ legend:{ position:"bottom", labels:{ font:{ size:11 } } } } }
});

// Leads by city
new Chart(document.getElementById("chartLeadsCity"), {
  type: "bar",
  data: {
    labels: ' . json_encode(array_column($leadsByCity, 'city_name')) . ',
    datasets: [{ label:"Leads", data: ' . json_encode(array_column($leadsByCity, 'cnt')) . ', backgroundColor:"#16a34a", borderRadius:6 }]
  },
  options: {
    responsive:true,
    plugins:{ legend:{ display:false } },
    scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 } }, x:{ ticks:{ font:{ size:11 } } } }
  }
});
</script>';

require __DIR__ . '/includes/footer.php';
?>
