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
    // Update project count for builder
    $bid = $pdo->prepare("SELECT builder_id FROM projects WHERE id=?")->execute([$id]) ? $pdo->query("SELECT builder_id FROM projects WHERE id=$id")->fetchColumn() : null;
    crudDelete($pdo, 'projects', $id, BASE_URL . 'admin/projects/');
}

// ── Load row for edit
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: [];
}

// ── Save (new or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['new','edit'])) {
    csrfCheck();

    $name    = trim($_POST['name']    ?? '');
    $slug    = trim($_POST['slug']    ?? '');
    $buildId = (int)($_POST['builder_id'] ?? 0);
    $cityId  = (int)($_POST['city_id']    ?? 0);

    if (!$name) $errors[] = 'Project name is required.';

    if (!$errors) {
        if (!$slug) $slug = slugify($name);
        $slug = uniqueSlug('projects', $slug, $id ?: null);

        // Helper function for multiple image uploads defined FIRST
        $processMultiUpload = function($field, $subdir) {
            $uploaded = [];
            if (isset($_FILES[$field]) && is_array($_FILES[$field]['name'])) {
                foreach ($_FILES[$field]['name'] as $i => $fname) {
                    if (!empty($fname) && isset($_FILES[$field]['error'][$i]) && $_FILES[$field]['error'][$i] === UPLOAD_ERR_OK) {
                        $fileData = [
                            'name'     => $_FILES[$field]['name'][$i],
                            'type'     => $_FILES[$field]['type'][$i],
                            'tmp_name' => $_FILES[$field]['tmp_name'][$i],
                            'error'    => $_FILES[$field]['error'][$i],
                            'size'     => $_FILES[$field]['size'][$i],
                        ];
                        $up = uploadImage($fileData, $subdir);
                        if ($up['success']) $uploaded[] = $up['path'];
                    }
                }
            }
            return $uploaded;
        };

        $priceMin = (isset($_POST['price_min']) && $_POST['price_min'] !== '' && is_numeric($_POST['price_min'])) ? (float)$_POST['price_min'] : null;
        $priceMax = (isset($_POST['price_max']) && $_POST['price_max'] !== '' && is_numeric($_POST['price_max'])) ? (float)$_POST['price_max'] : null;
        $totalUnits = (isset($_POST['total_units']) && trim($_POST['total_units']) !== '' && is_numeric($_POST['total_units'])) ? (int)$_POST['total_units'] : null;
        $latitude = (isset($_POST['latitude']) && $_POST['latitude'] !== '' && is_numeric($_POST['latitude'])) ? (float)$_POST['latitude'] : null;
        $longitude = (isset($_POST['longitude']) && $_POST['longitude'] !== '' && is_numeric($_POST['longitude'])) ? (float)$_POST['longitude'] : null;

        $data = [
            'builder_id'       => $buildId ?: null,
            'city_id'          => $cityId ?: null,
            'name'             => $name,
            'slug'             => $slug,
            'type'             => $_POST['type']   ?? 'residential',
            'status'           => $_POST['status'] ?? 'upcoming',
            'price_min'        => $priceMin,
            'price_max'        => $priceMax,
            'price_display'    => trim($_POST['price_display'] ?? '') ?: null,
            'price_on_request' => isset($_POST['price_on_request']) ? 1 : 0,
            'unit_types'       => trim($_POST['unit_types']    ?? ''),
            'area_range'       => trim($_POST['area_range']    ?? ''),
            'total_area'       => trim($_POST['total_area']    ?? ''),
            'total_units'      => $totalUnits,
            'rera_id'          => trim($_POST['rera_id']       ?? ''),
            'rera_verified'    => isset($_POST['rera_verified']) ? 1 : 0,
            'address'          => trim($_POST['address']       ?? ''),
            'locality_id'      => (int)($_POST['locality_id'] ?? 0) ?: null,
            'location_area'    => trim($_POST['location_area'] ?? ''),
            'latitude'         => $latitude,
            'longitude'        => $longitude,
            'map_url'          => trim($_POST['map_url']       ?? ''),
            'short_description'=> trim($_POST['short_description'] ?? ''),
            'description'      => $_POST['description'] ?? '',
            'possession_date'  => trim($_POST['possession_date'] ?? ''),
            'is_featured'      => isset($_POST['is_featured']) ? 1 : 0,
            'sort_order'       => (int)($_POST['sort_order'] ?? 0),
            'meta_title'       => trim($_POST['meta_title']       ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'contact_phone'    => trim($_POST['contact_phone']    ?? ''),
            'whatsapp_number'  => trim($_POST['whatsapp_number']  ?? ''),
            'virtual_tour_url' => trim($_POST['virtual_tour_url'] ?? ''),
            'video_url'        => trim($_POST['video_url']        ?? ''),
            'marquee_text'     => trim($_POST['marquee_text']     ?? ''),
            'connectivity'     => isset($_POST['conn_main']) ? json_encode([
                'Connectivity' => trim($_POST['conn_main']),
                'Education Hub' => trim($_POST['conn_edu'] ?? ''),
                'Corporate & Business' => trim($_POST['conn_corp'] ?? ''),
                'Hospitals' => trim($_POST['conn_hosp'] ?? ''),
                'Commercial' => trim($_POST['conn_comm'] ?? ''),
                'Airports & Transit' => trim($_POST['conn_transit'] ?? ''),
                'Shopping & Retail' => trim($_POST['conn_shop'] ?? ''),
                'Entertainment' => trim($_POST['conn_ent'] ?? '')
            ]) : ($_POST['connectivity'] ?? ''),
            'highlights'       => $_POST['highlights'] ?? '',
        ];

        // Process Amenities text to JSON
        if (isset($_POST['amenities'])) {
            $amenitiesArr = array_filter(array_map('trim', explode("\n", $_POST['amenities'])));
            $data['amenities'] = json_encode(array_values($amenitiesArr));
        }

        // Handle single and multiple banner image uploads
        $existingBanners = $id && !empty($row['banner_images']) ? json_decode($row['banner_images'], true) : [];
        if (!is_array($existingBanners)) $existingBanners = [];
        if (empty($existingBanners) && !empty($row['banner_image'])) {
            $existingBanners = [$row['banner_image']];
        }

        $deleteBanners = $_POST['delete_banner_images'] ?? [];
        if (!empty($deleteBanners)) {
            $existingBanners = array_values(array_filter($existingBanners, fn($img) => !in_array($img, $deleteBanners)));
        }

        $newBanners = $processMultiUpload('banner_images', 'projects/banners');
        if ($newBanners || isset($_POST['delete_banner_images'])) {
            $allBanners = array_merge($existingBanners, $newBanners);
            $data['banner_images'] = json_encode($allBanners);
            $data['banner_image']  = !empty($allBanners[0]) ? $allBanners[0] : null;
        } elseif (!empty($existingBanners)) {
            $data['banner_images'] = json_encode($existingBanners);
            $data['banner_image']  = $existingBanners[0];
        }

        if (!empty($_POST['delete_thumbnail_image'])) {
            $data['thumbnail_image'] = null;
        }
        if (!empty($_FILES['thumbnail_image']['name'])) {
            $up = uploadImage($_FILES['thumbnail_image'], 'projects');
            if ($up['success']) $data['thumbnail_image'] = $up['path'];
            else $errors[] = 'Thumbnail: ' . $up['error'];
        }

        if (!empty($_POST['delete_project_logo'])) {
            $data['project_logo'] = null;
        }
        if (!empty($_FILES['project_logo']['name'])) {
            $up = uploadImage($_FILES['project_logo'], 'projects');
            if ($up['success']) $data['project_logo'] = $up['path'];
            else $errors[] = 'Project Logo: ' . $up['error'];
        }

        if (!empty($_POST['delete_rera_qr_code'])) {
            $data['rera_qr_code'] = null;
        }
        if (!empty($_FILES['rera_qr_code']['name'])) {
            $up = uploadImage($_FILES['rera_qr_code'], 'projects');
            if ($up['success']) $data['rera_qr_code'] = $up['path'];
            else $errors[] = 'RERA QR Code: ' . $up['error'];
        }

        if (!empty($_POST['delete_brochure_pdf'])) {
            $data['brochure_pdf'] = null;
        }
        if (!empty($_FILES['brochure_pdf']['name'])) {
            $up = uploadPdf($_FILES['brochure_pdf'], 'brochures');
            if ($up['success']) $data['brochure_pdf'] = $up['path'];
            else $errors[] = 'Brochure: ' . $up['error'];
        }

        // Handle gallery images
        $existingGallery = $id && !empty($row['gallery_images']) ? json_decode($row['gallery_images'], true) : [];
        if (!is_array($existingGallery)) $existingGallery = [];

        $deleteGallery = $_POST['delete_gallery_images'] ?? [];
        if (!empty($deleteGallery)) {
            $existingGallery = array_values(array_filter($existingGallery, fn($img) => !in_array($img, $deleteGallery)));
        }

        $newGallery = $processMultiUpload('gallery_images', 'projects/gallery');
        if ($newGallery || isset($_POST['delete_gallery_images'])) {
            $data['gallery_images'] = json_encode(array_merge($existingGallery, $newGallery));
        }

        // Handle interior images
        $existingInterior = $id && !empty($row['interior_images']) ? json_decode($row['interior_images'], true) : [];
        if (!is_array($existingInterior)) $existingInterior = [];

        $deleteInterior = $_POST['delete_interior_images'] ?? [];
        if (!empty($deleteInterior)) {
            $existingInterior = array_values(array_filter($existingInterior, fn($img) => !in_array($img, $deleteInterior)));
        }

        $newInterior = $processMultiUpload('interior_images', 'projects/interior');
        if ($newInterior || isset($_POST['delete_interior_images'])) {
            $data['interior_images'] = json_encode(array_merge($existingInterior, $newInterior));
        }

        // Handle exterior images
        $existingExterior = $id && !empty($row['exterior_images']) ? json_decode($row['exterior_images'], true) : [];
        if (!is_array($existingExterior)) $existingExterior = [];

        $deleteExterior = $_POST['delete_exterior_images'] ?? [];
        if (!empty($deleteExterior)) {
            $existingExterior = array_values(array_filter($existingExterior, fn($img) => !in_array($img, $deleteExterior)));
        }

        $newExterior = $processMultiUpload('exterior_images', 'projects/exterior');
        if ($newExterior || isset($_POST['delete_exterior_images'])) {
            $data['exterior_images'] = json_encode(array_merge($existingExterior, $newExterior));
        }

        // Handle floor plan images
        $existingFloorPlans = $id && !empty($row['floor_plan_images']) ? json_decode($row['floor_plan_images'], true) : [];
        if (!is_array($existingFloorPlans)) $existingFloorPlans = [];

        $deleteFloorPlans = $_POST['delete_floor_plan_images'] ?? [];
        if (!empty($deleteFloorPlans)) {
            $existingFloorPlans = array_values(array_filter($existingFloorPlans, fn($img) => !in_array($img, $deleteFloorPlans)));
        }

        $newFloorPlans = $processMultiUpload('floor_plan_images', 'projects/floor_plans');
        if ($newFloorPlans || isset($_POST['delete_floor_plan_images'])) {
            $data['floor_plan_images'] = json_encode(array_merge($existingFloorPlans, $newFloorPlans));
        }

        if (!$errors) {
            try {
                if ($id) {
                    // UPDATE
                    $sets = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
                    $stmt = $pdo->prepare("UPDATE projects SET $sets WHERE id=?");
                    $stmt->execute([...array_values($data), $id]);
                    logAction('UPDATE', 'projects', $id);
                } else {
                    // INSERT
                    $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
                    $vals = implode(', ', array_fill(0, count($data), '?'));
                    $stmt = $pdo->prepare("INSERT INTO projects ($cols) VALUES ($vals)");
                    $stmt->execute(array_values($data));
                    $id = (int)$pdo->lastInsertId();
                    logAction('CREATE', 'projects', $id);
                }
                header('Location: ' . BASE_URL . 'admin/projects/?saved=1');
                exit;
            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// ── List
$list = crudList($pdo, 'projects', 20, 'projects.created_at DESC',
    'LEFT JOIN builders b ON b.id=projects.builder_id LEFT JOIN cities c ON c.id=projects.city_id',
    ', b.name AS builder_name, c.name AS city_name',
    $search, ['projects.name', 'projects.address']
);

$builders = $pdo->query("SELECT id, name FROM builders ORDER BY name")->fetchAll();
$countries = $pdo->query("SELECT id, name FROM countries ORDER BY sort_order, name")->fetchAll();
$states   = $pdo->query("SELECT id, country_id, name FROM states ORDER BY name")->fetchAll();
$cities   = $pdo->query("SELECT id, state_id, name FROM cities ORDER BY name")->fetchAll();
$localities = $pdo->query("SELECT id, city_id, name FROM localities WHERE status='active' ORDER BY name")->fetchAll();

$pageTitle = 'Projects';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<!-- List View -->
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title">🏗️ Projects (<?= number_format($list['total']) ?>)</h2>
    <div class="d-flex gap-2">
      <form class="d-flex gap-2" method="get">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search…" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-sm btn-outline-secondary">Search</button>
      </form>
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New Project</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>Project</th><th>Builder</th><th>City</th><th>Type</th><th>Status</th><th>Price</th><th>Featured</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($list['rows'] as $p): ?>
        <tr>
          <td>
            <div class="fw-600"><?= htmlspecialchars($p['name']) ?></div>
            <small class="text-muted"><?= htmlspecialchars($p['slug']) ?></small>
          </td>
          <td><?= htmlspecialchars($p['builder_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($p['city_name']    ?? '—') ?></td>
          <td><span class="adm-badge" style="background:#ede9fe;color:#7c3aed"><?= ucfirst($p['type']) ?></span></td>
          <td><span class="adm-badge badge-<?= htmlspecialchars($p['status']) ?>"><?= str_replace('_',' ',ucfirst($p['status'])) ?></span></td>
          <td><?= View::priceRange($p['price_min'],$p['price_max'],(bool)$p['price_on_request']) ?></td>
          <td><?= $p['is_featured'] ? '⭐' : '' ?></td>
          <td>
            <div class="actions">
              <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="<?= BASE_URL ?>project/<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
              <a href="?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= csrfToken() ?>"
                 class="btn btn-sm btn-outline-danger"
                 data-confirm="Delete this project? This cannot be undone.">Delete</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if ($list['totalPages'] > 1): ?>
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
  <h2 class="mb-0"><?= $id ? 'Edit Project' : 'New Project' ?></h2>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="adm-form" novalidate>
  <?= csrfField() ?>
  <div class="row g-4">
    <div class="col-lg-8">

      <!-- Basic Info -->
      <div class="adm-card">
        <div class="adm-card-title">Basic Information</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="adm-form-label">Project Name *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name'] ?? '') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">URL Slug</label>
            <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($row['slug'] ?? '') ?>" placeholder="auto-generated">
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Builder</label>
            <select name="builder_id" class="form-select">
              <option value="">-- Select Builder --</option>
              <?php foreach ($builders as $b): ?>
              <option value="<?= $b['id'] ?>" <?= ($row['builder_id'] ?? '') == $b['id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Country</label>
            <select id="countrySelect" class="form-select">
              <option value="">-- Select Country --</option>
              <?php foreach ($countries as $co): ?>
              <option value="<?= $co['id'] ?>"><?= htmlspecialchars($co['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">State</label>
            <select id="stateSelect" class="form-select">
              <option value="">-- Select State --</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">City</label>
            <select name="city_id" id="citySelect" class="form-select">
              <option value="">-- Select City --</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Locality</label>
            <select name="locality_id" id="localitySelect" class="form-select">
              <option value="">-- Select Locality --</option>
            </select>
            <input type="hidden" name="location_area" id="locationAreaInput" value="<?= htmlspecialchars($row['location_area'] ?? '') ?>">
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">Type</label>
            <select name="type" class="form-select">
              <?php foreach (['residential'=>'Residential','commercial'=>'Commercial','plot'=>'Plot'] as $v=>$l): ?>
              <option value="<?= $v ?>" <?= ($row['type'] ?? 'residential') === $v ? 'selected' : '' ?>><?= $l ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">Status</label>
            <select name="status" class="form-select">
              <?php foreach (['upcoming'=>'Upcoming','under_construction'=>'Under Construction','ready_to_move'=>'Ready to Move','new_launch'=>'New Launch'] as $v=>$l): ?>
              <option value="<?= $v ?>" <?= ($row['status'] ?? 'upcoming') === $v ? 'selected' : '' ?>><?= $l ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">Possession Date</label>
            <input type="text" name="possession_date" class="form-control" value="<?= htmlspecialchars($row['possession_date'] ?? '') ?>" placeholder="e.g. Dec 2026">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Full Address</label>
            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($row['address'] ?? '') ?>">
          </div>
        </div>
      </div>

      <!-- Pricing -->
      <div class="adm-card">
        <div class="adm-card-title">Pricing & Units</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="adm-form-label fw-bold text-primary">Custom Price Display Text (Recommended)</label>
            <input type="text" name="price_display" class="form-control" value="<?= htmlspecialchars($row['price_display'] ?? '') ?>" placeholder="e.g. ₹3.5 Cr – ₹5.2 Cr, ₹85 Lakh Onwards, $1.2M – $2.5M, 2.5M AED, Price on Request">
            <small class="text-muted">Type any currency, units (Cr, Lakh, $, AED) or custom text. If entered, this exact text will display on the website.</small>
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">Min Price (Numeric ₹ for sorting/filters)</label>
            <input type="number" name="price_min" class="form-control" value="<?= htmlspecialchars($row['price_min'] ?? '') ?>" placeholder="e.g. 35000000">
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">Max Price (Numeric ₹ for sorting/filters)</label>
            <input type="number" name="price_max" class="form-control" value="<?= htmlspecialchars($row['price_max'] ?? '') ?>" placeholder="e.g. 52000000">
          </div>
          <div class="col-md-4">
            <div class="form-check mt-4 pt-2">
              <input class="form-check-input" type="checkbox" name="price_on_request" id="por" <?= !empty($row['price_on_request']) ? 'checked' : '' ?>>
              <label class="form-check-label fw-600" for="por">Price on Request</label>
            </div>
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">Unit Types</label>
            <input type="text" name="unit_types" class="form-control" value="<?= htmlspecialchars($row['unit_types'] ?? '') ?>" placeholder="2BHK, 3BHK">
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">Area Range</label>
            <input type="text" name="area_range" class="form-control" value="<?= htmlspecialchars($row['area_range'] ?? '') ?>" placeholder="850–2200 sq.ft.">
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">Total Area</label>
            <input type="text" name="total_area" class="form-control" value="<?= htmlspecialchars($row['total_area'] ?? '') ?>" placeholder="e.g. 5 Acres">
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">Total Units</label>
            <input type="number" name="total_units" class="form-control" value="<?= htmlspecialchars($row['total_units'] ?? '') ?>" placeholder="e.g. 500">
          </div>
        </div>
      </div>

      <!-- Description & Additional Details -->
      <div class="adm-card">
        <div class="adm-card-title">Description & Details</div>
        <div class="mb-3">
          <label class="adm-form-label">Short Description</label>
          <textarea name="short_description" class="form-control" rows="2"><?= htmlspecialchars($row['short_description'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Full Description (Plain Text)</label>
          <textarea name="description" class="form-control" rows="6"><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
        </div>
        <?php
        $cMain = $cEdu = $cCorp = $cHosp = $cComm = $cTransit = $cShop = $cEnt = '';
        if (!empty($row['connectivity'])) {
            $cArr = json_decode($row['connectivity'], true);
            if (is_array($cArr)) {
                $cMain = $cArr['Connectivity'] ?? '';
                $cEdu  = $cArr['Education Hub'] ?? '';
                $cCorp = $cArr['Corporate & Business'] ?? '';
                $cHosp = $cArr['Hospitals'] ?? '';
                $cComm = $cArr['Commercial'] ?? '';
                $cTransit = $cArr['Airports & Transit'] ?? '';
                $cShop = $cArr['Shopping & Retail'] ?? '';
                $cEnt = $cArr['Entertainment'] ?? '';
            } else {
                $cMain = $row['connectivity']; // Legacy fallback
            }
        }
        ?>
        <div class="mb-3">
          <label class="adm-form-label fw-bold text-primary">Connectivity (Tabs format, 1 item per line e.g., "School Name::2 km")</label>
          <div class="row g-2 mt-1">
              <div class="col-md-6"><label class="small">Main Connectivity</label><textarea name="conn_main" class="form-control" rows="2"><?= htmlspecialchars($cMain) ?></textarea></div>
              <div class="col-md-6"><label class="small">Education Hub</label><textarea name="conn_edu" class="form-control" rows="2"><?= htmlspecialchars($cEdu) ?></textarea></div>
              <div class="col-md-6"><label class="small">Corporate & Business</label><textarea name="conn_corp" class="form-control" rows="2"><?= htmlspecialchars($cCorp) ?></textarea></div>
              <div class="col-md-6"><label class="small">Hospitals</label><textarea name="conn_hosp" class="form-control" rows="2"><?= htmlspecialchars($cHosp) ?></textarea></div>
              <div class="col-md-6"><label class="small">Commercial</label><textarea name="conn_comm" class="form-control" rows="2"><?= htmlspecialchars($cComm) ?></textarea></div>
              <div class="col-md-6"><label class="small">Airports & Transit</label><textarea name="conn_transit" class="form-control" rows="2"><?= htmlspecialchars($cTransit) ?></textarea></div>
              <div class="col-md-6"><label class="small">Shopping & Retail</label><textarea name="conn_shop" class="form-control" rows="2"><?= htmlspecialchars($cShop) ?></textarea></div>
              <div class="col-md-6"><label class="small">Entertainment</label><textarea name="conn_ent" class="form-control" rows="2"><?= htmlspecialchars($cEnt) ?></textarea></div>
          </div>
        </div>
        <div>
          <label class="adm-form-label">Project Highlights (One per line or HTML)</label>
          <textarea name="highlights" class="form-control" rows="4"><?= htmlspecialchars($row['highlights'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Amenities -->
      <div class="adm-card">
        <div class="adm-card-title">Amenities (one per line)</div>
        <?php
        $amenitiesStr = '';
        if (!empty($row['amenities'])) {
            $arr = json_decode($row['amenities'], true);
            if (is_array($arr)) $amenitiesStr = implode("\n", $arr);
        }
        ?>
        <textarea name="amenities" class="form-control" rows="5" placeholder="Swimming Pool&#10;Gymnasium&#10;Clubhouse"><?= htmlspecialchars($amenitiesStr) ?></textarea>
        <small class="text-muted">Enter amenity names, one per line.</small>
      </div>

      <!-- SEO -->
      <div class="adm-card">
        <div class="adm-card-title">SEO</div>
        <div class="mb-3">
          <label class="adm-form-label">Meta Title</label>
          <input type="text" name="meta_title" class="form-control" value="<?= htmlspecialchars($row['meta_title'] ?? '') ?>">
        </div>
        <div>
          <label class="adm-form-label">Meta Description</label>
          <textarea name="meta_description" class="form-control" rows="2"><?= htmlspecialchars($row['meta_description'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <!-- Right sidebar fields -->
    <div class="col-lg-4">
      <div class="adm-card">
        <div class="adm-card-title">Settings</div>
        <div class="mb-3">
          <label class="adm-form-label">RERA ID</label>
          <input type="text" name="rera_id" id="reraIdInput" class="form-control" value="<?= htmlspecialchars($row['rera_id'] ?? '') ?>" placeholder="e.g. RC/REP/HARERA/GGM/1059/791/2026/31">
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="rera_verified" id="rera_v" <?= !empty($row['rera_verified']) ? 'checked' : '' ?>>
          <label class="form-check-label" for="rera_v">RERA Verified</label>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">RERA QR Code</label>
          <?php if (!empty($row['rera_qr_code'])): ?>
          <div class="mb-2">
            <img src="<?= upload($row['rera_qr_code']) ?>" class="img-fluid rounded border" style="height:75px;object-fit:contain">
            <label style="font-size:12px;cursor:pointer;display:block;margin-top:4px;">
              <input type="checkbox" name="delete_rera_qr_code" value="1"> Delete Custom Image
            </label>
          </div>
          <?php endif; ?>
          <div id="autoQrPreviewWrapper" class="mb-2" style="<?= empty($row['rera_qr_code']) && !empty($row['rera_id']) ? '' : 'display:none;' ?>">
            <div class="p-2 border rounded bg-light d-inline-block text-center">
              <img id="autoQrPreview" src="<?= !empty($row['rera_id']) ? 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($row['rera_id']) : '' ?>" style="width:100px;height:100px;display:block;margin:0 auto 4px;">
              <span class="badge bg-success" style="font-size:10px;">Auto-Generated QR</span>
            </div>
          </div>
          <input type="file" name="rera_qr_code" id="reraQrFileInput" class="form-control" accept="image/*">
          <small class="text-muted">Upload custom QR code image, or leave blank to automatically generate from RERA ID.</small>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_featured" id="featured" <?= !empty($row['is_featured']) ? 'checked' : '' ?>>
          <label class="form-check-label" for="featured">Featured Project</label>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Sort Order</label>
          <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($row['sort_order'] ?? 0) ?>">
        </div>
        <hr>
        <div class="mb-3">
          <label class="adm-form-label">Marquee Text (News/Updates)</label>
          <input type="text" name="marquee_text" class="form-control" value="<?= htmlspecialchars($row['marquee_text'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Contact Phone</label>
          <input type="text" name="contact_phone" class="form-control" value="<?= htmlspecialchars($row['contact_phone'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">WhatsApp Number</label>
          <input type="text" name="whatsapp_number" class="form-control" value="<?= htmlspecialchars($row['whatsapp_number'] ?? '') ?>">
        </div>
        <hr>
        <div class="mb-3">
          <label class="adm-form-label">Latitude</label>
          <input type="text" name="latitude" class="form-control" value="<?= htmlspecialchars($row['latitude'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Longitude</label>
          <input type="text" name="longitude" class="form-control" value="<?= htmlspecialchars($row['longitude'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Map Link (URL)</label>
          <input type="url" name="map_url" class="form-control" placeholder="https://maps.google.com/..." value="<?= htmlspecialchars($row['map_url'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Video URL / YouTube Link</label>
          <input type="text" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/... or embed code" value="<?= htmlspecialchars($row['video_url'] ?? '') ?>">
          <small class="text-muted">Paste any YouTube link, short link (youtu.be), Vimeo URL, or iframe embed code.</small>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Virtual Tour (URL/Embed)</label>
          <input type="text" name="virtual_tour_url" class="form-control" value="<?= htmlspecialchars($row['virtual_tour_url'] ?? '') ?>">
        </div>
      </div>

      <div class="adm-card">
        <div class="adm-card-title">Main Images</div>
        <div class="mb-3">
          <label class="adm-form-label">Project Logo</label>
          <?php if (!empty($row['project_logo'])): ?>
          <div class="mb-2">
            <img src="<?= upload($row['project_logo']) ?>" class="img-fluid rounded" style="height:60px;object-fit:contain;background:#f8f9fa;">
            <label style="font-size:12px;cursor:pointer;display:block;margin-top:4px;">
              <input type="checkbox" name="delete_project_logo" value="1"> Delete Image
            </label>
          </div>
          <?php endif; ?>
          <input type="file" name="project_logo" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
          <label class="adm-form-label fw-bold text-primary">Hero Banner Images (Multiple HD)</label>
          <?php 
          $bArr = !empty($row['banner_images']) ? json_decode($row['banner_images'], true) : [];
          if (!is_array($bArr)) $bArr = [];
          if (empty($bArr) && !empty($row['banner_image'])) {
              $bArr = [$row['banner_image']];
          }
          if (!empty($bArr)):
          ?>
          <div class="d-flex flex-wrap gap-2 mb-2 p-2 bg-light rounded border">
            <?php foreach ($bArr as $bi): ?>
            <div class="text-center p-1 bg-white rounded border shadow-sm">
              <img src="<?= upload($bi) ?>" style="height:65px;width:105px;object-fit:cover;border-radius:4px;display:block;margin-bottom:4px;">
              <label style="font-size:11px;cursor:pointer;color:#dc2626;" class="d-block mb-0">
                 <input type="checkbox" name="delete_banner_images[]" value="<?= htmlspecialchars($bi) ?>"> Delete
              </label>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <input type="file" name="banner_images[]" class="form-control" accept="image/*" multiple>
          <small class="text-muted">Upload one or multiple high-definition (HD 1920x1080) hero banner images. These display in the project details hero banner.</small>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Thumbnail</label>
          <?php if (!empty($row['thumbnail_image'])): ?>
          <div class="mb-2">
            <img src="<?= upload($row['thumbnail_image']) ?>" class="img-fluid rounded" style="height:60px;object-fit:cover">
            <label style="font-size:12px;cursor:pointer;display:block;margin-top:4px;">
              <input type="checkbox" name="delete_thumbnail_image" value="1"> Delete Image
            </label>
          </div>
          <?php endif; ?>
          <input type="file" name="thumbnail_image" class="form-control" accept="image/*">
        </div>
      </div>

      <div class="adm-card">
        <div class="adm-card-title">Gallery & Media</div>
        <div class="mb-3">
          <label class="adm-form-label">Interior Images (Multiple)</label>
          <?php if (!empty($row['interior_images'])): $iArr = json_decode($row['interior_images'], true); if (is_array($iArr) && count($iArr)): ?>
          <div class="d-flex flex-wrap gap-3 mb-2">
            <?php foreach ($iArr as $gi): ?>
            <div class="text-center">
              <img src="<?= upload($gi) ?>" style="height:50px;width:50px;object-fit:cover;border-radius:4px;display:block;margin-bottom:4px;">
              <label style="font-size:11px;cursor:pointer;">
                 <input type="checkbox" name="delete_interior_images[]" value="<?= htmlspecialchars($gi) ?>"> Delete
              </label>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; endif; ?>
          <input type="file" name="interior_images[]" class="form-control" accept="image/*" multiple>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Exterior Images (Multiple)</label>
          <?php if (!empty($row['exterior_images'])): $eArr = json_decode($row['exterior_images'], true); if (is_array($eArr) && count($eArr)): ?>
          <div class="d-flex flex-wrap gap-3 mb-2">
            <?php foreach ($eArr as $gi): ?>
            <div class="text-center">
              <img src="<?= upload($gi) ?>" style="height:50px;width:50px;object-fit:cover;border-radius:4px;display:block;margin-bottom:4px;">
              <label style="font-size:11px;cursor:pointer;">
                 <input type="checkbox" name="delete_exterior_images[]" value="<?= htmlspecialchars($gi) ?>"> Delete
              </label>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; endif; ?>
          <input type="file" name="exterior_images[]" class="form-control" accept="image/*" multiple>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Legacy Gallery Images (Optional)</label>
          <?php if (!empty($row['gallery_images'])): $gArr = json_decode($row['gallery_images'], true); if (is_array($gArr) && count($gArr)): ?>
          <div class="d-flex flex-wrap gap-3 mb-2">
            <?php foreach ($gArr as $gi): ?>
            <div class="text-center">
              <img src="<?= upload($gi) ?>" style="height:50px;width:50px;object-fit:cover;border-radius:4px;display:block;margin-bottom:4px;">
              <label style="font-size:11px;cursor:pointer;">
                 <input type="checkbox" name="delete_gallery_images[]" value="<?= htmlspecialchars($gi) ?>"> Delete
              </label>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; endif; ?>
          <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
        </div>
        <div class="mb-3">
          <label class="adm-form-label">Floor Plan Images (Multiple)</label>
          <?php if (!empty($row['floor_plan_images'])): $fArr = json_decode($row['floor_plan_images'], true); if (is_array($fArr) && count($fArr)): ?>
          <div class="d-flex flex-wrap gap-3 mb-2">
            <?php foreach ($fArr as $fi): ?>
            <div class="text-center">
              <img src="<?= upload($fi) ?>" style="height:50px;width:50px;object-fit:cover;border-radius:4px;display:block;margin-bottom:4px;">
              <label style="font-size:11px;cursor:pointer;">
                 <input type="checkbox" name="delete_floor_plan_images[]" value="<?= htmlspecialchars($fi) ?>"> Delete
              </label>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; endif; ?>
          <input type="file" name="floor_plan_images[]" class="form-control" accept="image/*" multiple>
        </div>
        <div>
          <label class="adm-form-label">Brochure PDF</label>
          <?php if (!empty($row['brochure_pdf'])): ?>
          <div class="mb-2">
            <a href="<?= upload($row['brochure_pdf']) ?>" target="_blank" style="font-size:12px;"><i class="fas fa-file-pdf me-1"></i>View PDF</a>
            <label style="font-size:12px;cursor:pointer;display:block;margin-top:4px;">
              <input type="checkbox" name="delete_brochure_pdf" value="1"> Delete PDF
            </label>
          </div>
          <?php endif; ?>
          <input type="file" name="brochure_pdf" class="form-control" accept="application/pdf">
        </div>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-save me-2"></i>Save Project</button>
        <a href="?" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>

<?php
$extraScripts = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const states = ' . json_encode($states) . ';
    const cities = ' . json_encode($cities) . ';
    const localities = ' . json_encode($localities) . ';
    
    const countrySelect = document.getElementById("countrySelect");
    const stateSelect = document.getElementById("stateSelect");
    const citySelect = document.getElementById("citySelect");
    const localitySelect = document.getElementById("localitySelect");
    const locationAreaInput = document.getElementById("locationAreaInput");
    
    let currentCityId = "' . ((int)($row['city_id'] ?? 0) ?: '') . '";
    let currentLocalityId = "' . ((int)($row['locality_id'] ?? 0) ?: '') . '";
    
    let currentCountryId = "";
    let currentStateId = "";
    
    if (currentCityId) {
        const city = cities.find(c => String(c.id) === String(currentCityId));
        if (city) {
            currentStateId = String(city.state_id);
            const state = states.find(s => String(s.id) === String(currentStateId));
            if (state) currentCountryId = String(state.country_id);
        }
    }
    
    if (countrySelect && currentCountryId) {
        countrySelect.value = currentCountryId;
    }
    
    function updateStates() {
        if (!stateSelect) return;
        const cId = countrySelect ? countrySelect.value : "";
        stateSelect.innerHTML = \'<option value="">-- Select State --</option>\';
        if (cId) {
            states.filter(s => String(s.country_id) === String(cId)).forEach(s => {
                const opt = new Option(s.name, s.id);
                if (String(s.id) === String(currentStateId)) opt.selected = true;
                stateSelect.appendChild(opt);
            });
        }
        updateCities();
    }
    
    function updateCities() {
        if (!citySelect) return;
        const sId = stateSelect ? stateSelect.value : "";
        citySelect.innerHTML = \'<option value="">-- Select City --</option>\';
        if (sId) {
            cities.filter(c => String(c.state_id) === String(sId)).forEach(c => {
                const opt = new Option(c.name, c.id);
                if (String(c.id) === String(currentCityId)) opt.selected = true;
                citySelect.appendChild(opt);
            });
        }
        updateLocalities();
    }
    
    function updateLocalities() {
        if (!localitySelect) return;
        const cId = citySelect ? citySelect.value : "";
        localitySelect.innerHTML = \'<option value="">-- Select Locality --</option>\';
        if (cId) {
            localities.filter(l => String(l.city_id) === String(cId)).forEach(loc => {
                const opt = new Option(loc.name, loc.id);
                opt.dataset.name = loc.name;
                if (String(loc.id) === String(currentLocalityId)) opt.selected = true;
                localitySelect.appendChild(opt);
            });
        }
        syncLocationArea();
    }

    function syncLocationArea() {
        if (!localitySelect || !locationAreaInput) return;
        const selectedOpt = localitySelect.options[localitySelect.selectedIndex];
        if (selectedOpt && selectedOpt.value && selectedOpt.dataset.name) {
            locationAreaInput.value = selectedOpt.dataset.name;
        }
    }
    
    if (countrySelect) {
        countrySelect.addEventListener("change", function() {
            currentStateId = "";
            currentCityId = "";
            currentLocalityId = "";
            updateStates();
        });
    }

    if (stateSelect) {
        stateSelect.addEventListener("change", function() {
            currentStateId = this.value;
            currentCityId = "";
            currentLocalityId = "";
            updateCities();
        });
    }

    if (citySelect) {
        citySelect.addEventListener("change", function() {
            currentCityId = this.value;
            currentLocalityId = "";
            updateLocalities();
        });
    }
    
    if (localitySelect) {
        localitySelect.addEventListener("change", syncLocationArea);
    }

    // RERA Auto QR Preview
    const reraIdInput = document.getElementById("reraIdInput");
    const autoQrPreviewWrapper = document.getElementById("autoQrPreviewWrapper");
    const autoQrPreview = document.getElementById("autoQrPreview");
    const reraQrFileInput = document.getElementById("reraQrFileInput");

    function updateReraQrPreview() {
        const val = reraIdInput ? reraIdInput.value.trim() : "";
        const hasCustomFile = reraQrFileInput && reraQrFileInput.files && reraQrFileInput.files.length > 0;
        if (val && !hasCustomFile && autoQrPreviewWrapper && autoQrPreview) {
            autoQrPreview.src = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + encodeURIComponent(val);
            autoQrPreviewWrapper.style.display = "block";
        } else if (!val && autoQrPreviewWrapper) {
            autoQrPreviewWrapper.style.display = "none";
        }
    }

    if (reraIdInput) {
        reraIdInput.addEventListener("input", updateReraQrPreview);
    }
    if (reraQrFileInput) {
        reraQrFileInput.addEventListener("change", function() {
            if (this.files && this.files.length > 0 && autoQrPreviewWrapper) {
                autoQrPreviewWrapper.style.display = "none";
            } else {
                updateReraQrPreview();
            }
        });
    }

    // Initialize dropdowns on page load
    updateStates();
});
</script>'; 
require __DIR__ . '/../includes/footer.php'; 
?>

<?php endif; ?>
