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
    crudDelete($pdo, 'properties', $id, BASE_URL . 'admin/properties/');
}

// ── Load row for edit
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: [];
}

// ── Save (new or edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['new','edit'])) {
    csrfCheck();

    $title   = trim($_POST['title']   ?? '');
    $slug    = trim($_POST['slug']    ?? '');
    $projId  = (int)($_POST['project_id'] ?? 0);
    $buildId = (int)($_POST['builder_id'] ?? 0);
    $cityId  = (int)($_POST['city_id']    ?? 0);

    if (!$title) $errors[] = 'Property title is required.';

    if (!$errors) {
        if (!$slug) $slug = slugify($title);
        $slug = uniqueSlug('properties', $slug, $id ?: null);

        $data = [
            'project_id'       => $projId ?: null,
            'builder_id'       => $buildId ?: null,
            'city_id'          => $cityId ?: null,
            'locality_id'      => (int)($_POST['locality_id'] ?? 0) ?: null,
            
            'title'            => $title,
            'slug'             => $slug,
            'property_type'    => $_POST['property_type'] ?? 'Apartment',
            'listing_type'     => $_POST['listing_type']  ?? 'Sale',
            'market_type'      => $_POST['market_type'] ?? 'Secondary (Resale)',
            'possession_status'=> $_POST['possession_status'] ?? 'Ready to Move',
            
            'price'                  => $_POST['price'] !== '' ? (float)$_POST['price'] : null,
            'price_display_override' => trim($_POST['price_display_override'] ?? ''),
            'price_unit'             => $_POST['price_unit'] ?? 'Total',
            'is_gst_inclusive'       => isset($_POST['is_gst_inclusive']) ? 1 : 0,
            
            'vastu_compliant'        => isset($_POST['vastu_compliant']) ? 1 : 0,
            'bedrooms'               => $_POST['bedrooms'] !== '' ? (int)$_POST['bedrooms'] : null,
            'bathrooms'              => $_POST['bathrooms'] !== '' ? (int)$_POST['bathrooms'] : null,
            'balconies'              => $_POST['balconies'] !== '' ? (int)$_POST['balconies'] : null,
            'parking_spaces'         => $_POST['parking_spaces'] !== '' ? (int)$_POST['parking_spaces'] : null,
            
            'super_built_up_area'    => $_POST['super_built_up_area'] !== '' ? (int)$_POST['super_built_up_area'] : null,
            'built_up_area'          => $_POST['built_up_area'] !== '' ? (int)$_POST['built_up_area'] : null,
            'carpet_area'            => $_POST['carpet_area'] !== '' ? (int)$_POST['carpet_area'] : null,
            'area_unit'              => $_POST['area_unit'] ?? 'sqft',
            
            'furnishing_status'      => $_POST['furnishing_status'] ?? 'Unfurnished',
            'floor_number'           => $_POST['floor_number'] !== '' ? (int)$_POST['floor_number'] : null,
            'total_floors'           => $_POST['total_floors'] !== '' ? (int)$_POST['total_floors'] : null,
            'facing'                 => $_POST['facing'] !== '' ? $_POST['facing'] : null,
            'age_of_construction'    => $_POST['age_of_construction'] !== '' ? $_POST['age_of_construction'] : null,
            
            'address'                => trim($_POST['address'] ?? ''),
            'pincode'                => trim($_POST['pincode'] ?? ''),
            'latitude'               => $_POST['latitude'] !== '' ? (float)$_POST['latitude'] : null,
            'longitude'              => $_POST['longitude'] !== '' ? (float)$_POST['longitude'] : null,
            
            'rera_id'                => trim($_POST['rera_id'] ?? ''),
            'possession_date'        => trim($_POST['possession_date'] ?? ''),
            
            'description'            => $_POST['description'] ?? '',
            'amenities'              => trim($_POST['amenities'] ?? ''),
            'video_url'              => trim($_POST['video_url'] ?? ''),
            
            'owner_name'             => trim($_POST['owner_name'] ?? ''),
            'owner_phone'            => trim($_POST['owner_phone'] ?? ''),
            'owner_email'            => trim($_POST['owner_email'] ?? ''),
            
            'is_featured'            => isset($_POST['is_featured']) ? 1 : 0,
            'status'                 => $_POST['status'] ?? 'Active',
        ];

        // Handle single thumbnail image
        if (!empty($_POST['delete_thumbnail_image'])) {
            $data['thumbnail_image'] = null;
        }
        if (!empty($_FILES['thumbnail_image']['name'])) {
            $up = uploadImage($_FILES['thumbnail_image'], 'properties');
            if ($up['success']) $data['thumbnail_image'] = $up['path'];
            else $errors[] = 'Thumbnail: ' . $up['error'];
        }

        // Handle brochure PDF/Image
        if (!empty($_POST['delete_brochure_pdf'])) {
            $data['brochure_pdf'] = null;
        }
        if (!empty($_FILES['brochure_pdf']['name'])) {
            $up = uploadPdf($_FILES['brochure_pdf'], 'properties/brochures');
            if ($up['success']) $data['brochure_pdf'] = $up['path'];
            else $errors[] = 'Brochure: ' . $up['error'];
        }

        // Handle multiple images (Gallery & Floor Plans)
        $processMultiUpload = function($field, $subdir) {
            $uploaded = [];
            if (isset($_FILES[$field]) && is_array($_FILES[$field]['name'])) {
                foreach ($_FILES[$field]['name'] as $i => $name) {
                    if ($_FILES[$field]['error'][$i] === UPLOAD_ERR_OK) {
                        $fileData = [
                            'name' => $_FILES[$field]['name'][$i],
                            'type' => $_FILES[$field]['type'][$i],
                            'tmp_name' => $_FILES[$field]['tmp_name'][$i],
                            'error' => $_FILES[$field]['error'][$i],
                            'size' => $_FILES[$field]['size'][$i],
                        ];
                        $up = uploadImage($fileData, $subdir);
                        if ($up['success']) $uploaded[] = $up['path'];
                    }
                }
            }
            return $uploaded;
        };

        // Gallery Images
        $existingGallery = $id && !empty($row['gallery_images']) ? json_decode($row['gallery_images'], true) : [];
        if (!is_array($existingGallery)) $existingGallery = [];

        $deleteGallery = $_POST['delete_gallery_images'] ?? [];
        if (!empty($deleteGallery)) {
            $existingGallery = array_values(array_filter($existingGallery, fn($img) => !in_array($img, $deleteGallery)));
        }

        $newGallery = $processMultiUpload('gallery_images', 'properties/gallery');
        if ($newGallery || isset($_POST['delete_gallery_images'])) {
            $data['gallery_images'] = json_encode(array_merge($existingGallery, $newGallery));
        }

        // Floor Plans
        $existingFloorPlans = $id && !empty($row['floor_plan_images']) ? json_decode($row['floor_plan_images'], true) : [];
        if (!is_array($existingFloorPlans)) $existingFloorPlans = [];

        $deleteFloorPlans = $_POST['delete_floor_plan_images'] ?? [];
        if (!empty($deleteFloorPlans)) {
            $existingFloorPlans = array_values(array_filter($existingFloorPlans, fn($img) => !in_array($img, $deleteFloorPlans)));
        }

        $newFloorPlans = $processMultiUpload('floor_plan_images', 'properties/floor_plans');
        if ($newFloorPlans || isset($_POST['delete_floor_plan_images'])) {
            $data['floor_plan_images'] = json_encode(array_merge($existingFloorPlans, $newFloorPlans));
        }
    }

    if (!$errors) {
        if ($id) {
            $sets = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
            $stmt = $pdo->prepare("UPDATE properties SET $sets WHERE id=?");
            $stmt->execute([...array_values($data), $id]);
            logAction('UPDATE', 'properties', $id);
        } else {
            $cols = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
            $vals = implode(', ', array_fill(0, count($data), '?'));
            $stmt = $pdo->prepare("INSERT INTO properties ($cols) VALUES ($vals)");
            $stmt->execute(array_values($data));
            $id = (int)$pdo->lastInsertId();
            logAction('CREATE', 'properties', $id);
        }
        header('Location: ' . BASE_URL . 'admin/properties/?saved=1');
        exit;
    }
}

// ── List
$list = crudList($pdo, 'properties', 20, 'properties.created_at DESC',
    'LEFT JOIN projects p ON p.id=properties.project_id LEFT JOIN cities c ON c.id=properties.city_id',
    ', p.name AS project_name, c.name AS city_name',
    $search, ['properties.title', 'properties.address']
);

$projects   = $pdo->query("SELECT id, name FROM projects ORDER BY name")->fetchAll();
$builders   = $pdo->query("SELECT id, name FROM builders ORDER BY name")->fetchAll();
$cities     = $pdo->query("SELECT id, name FROM cities ORDER BY name")->fetchAll();
$localities = $pdo->query("SELECT id, city_id, name FROM localities WHERE status='active' ORDER BY name")->fetchAll();

$pageTitle = 'Properties';
require __DIR__ . '/../includes/header.php';
?>

<?= flashMsg() ?>

<?php if ($action === 'list'): ?>
<!-- List View -->
<div class="adm-table-wrap">
  <div class="adm-table-header">
    <h2 class="adm-table-title">🏠 Properties (<?= number_format($list['total']) ?>)</h2>
    <div class="d-flex gap-2">
      <form class="d-flex gap-2" method="get">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search…" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-sm btn-outline-secondary">Search</button>
      </form>
      <a href="?action=new" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New Property</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="adm-table">
      <thead><tr><th>Property</th><th>Location</th><th>Type</th><th>Listing</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($list['rows'] as $p): ?>
        <tr>
          <td>
            <div class="fw-600"><?= htmlspecialchars($p['title']) ?></div>
            <small class="text-muted"><?= htmlspecialchars($p['project_name'] ?? 'Independent') ?></small>
          </td>
          <td><?= htmlspecialchars($p['city_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($p['property_type']) ?></td>
          <td><span class="adm-badge badge-<?= strtolower($p['listing_type']) ?>"><?= htmlspecialchars($p['listing_type']) ?></span></td>
          <td>₹<?= number_format($p['price'] ?: 0) ?></td>
          <td><span class="adm-badge badge-<?= strtolower($p['status']) ?>"><?= htmlspecialchars($p['status']) ?></span></td>
          <td>
            <a href="?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
            <a href="?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-danger" data-confirm="Delete property?">Del</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$list['rows']): ?><tr><td colspan="7" class="text-center text-muted py-4">No properties found.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if ($list['totalPages'] > 1): ?>
  <div class="p-3 border-top">
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
<!-- Form View (New/Edit) -->
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="?" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
  <h2 class="mb-0"><?= $id ? 'Edit Property' : 'New Property' ?></h2>
</div>

<?php if ($errors): ?>
  <div class="alert alert-danger mb-4">
    <ul class="mb-0"><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
  </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="adm-form" novalidate>
  <?= csrfField() ?>
  <div class="row g-4">
    <div class="col-lg-8">
      
      <!-- Basic Information -->
      <div class="adm-card">
        <div class="adm-card-title">Basic Information</div>
        <div class="row g-3">
          <div class="col-md-8">
            <label class="adm-form-label">Property Title *</label>
            <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($row['title'] ?? '') ?>">
            <div class="form-text">e.g., "Beautiful 3 BHK Flat in DLF Phase 5"</div>
          </div>
          <div class="col-md-4">
            <label class="adm-form-label">URL Slug</label>
            <input type="text" name="slug" class="form-control" placeholder="auto-generated from title" value="<?= htmlspecialchars($row['slug'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Sub-type</label>
            <select name="property_type" class="form-select">
              <?php foreach(['Apartment','Villa','Independent House','Plot','Commercial Office','Retail Space'] as $t): ?>
              <option value="<?= $t ?>" <?= ($row['property_type']??'')===$t ? 'selected' : '' ?>><?= $t ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Transaction</label>
            <select name="listing_type" class="form-select">
              <option value="Sale" <?= ($row['listing_type']??'')==='Sale' ? 'selected' : '' ?>>For Sale</option>
              <option value="Rent" <?= ($row['listing_type']??'')==='Rent' ? 'selected' : '' ?>>For Rent</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Market Type</label>
            <select name="market_type" class="form-select">
              <option value="Secondary (Resale)" <?= ($row['market_type']??'')==='Secondary (Resale)' ? 'selected' : '' ?>>Secondary (Resale)</option>
              <option value="Primary" <?= ($row['market_type']??'')==='Primary' ? 'selected' : '' ?>>Primary (New)</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Possession Status</label>
            <select name="possession_status" class="form-select">
              <?php foreach(['Ready to Move','Under Construction','Upcoming'] as $ps): ?>
              <option value="<?= $ps ?>" <?= ($row['possession_status']??'')===$ps ? 'selected' : '' ?>><?= $ps ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Builder</label>
            <select name="builder_id" class="form-select">
              <option value="">-- None --</option>
              <?php foreach ($builders as $b): ?>
              <option value="<?= $b['id'] ?>" <?= ($row['builder_id']??0)==$b['id']?'selected':'' ?>><?= htmlspecialchars($b['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="adm-card">
        <div class="adm-card-title">Description</div>
        <div class="row g-3">
          <div class="col-12">
            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <!-- Configuration & Area -->
      <div class="adm-card">
        <div class="adm-card-title">Configuration & Area</div>
        <div class="row g-3">
          <div class="col-md-3">
            <label class="adm-form-label">BHK / Bedrooms</label>
            <input type="number" name="bedrooms" class="form-control" value="<?= htmlspecialchars($row['bedrooms'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Bathrooms</label>
            <input type="number" name="bathrooms" class="form-control" value="<?= htmlspecialchars($row['bathrooms'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Balconies</label>
            <input type="number" name="balconies" class="form-control" value="<?= htmlspecialchars($row['balconies'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Parking (cars)</label>
            <input type="number" name="parking_spaces" class="form-control" value="<?= htmlspecialchars($row['parking_spaces'] ?? '') ?>">
          </div>
          
          <div class="col-md-3">
            <label class="adm-form-label">Carpet Area</label>
            <input type="number" name="carpet_area" class="form-control" value="<?= htmlspecialchars($row['carpet_area'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Built-up Area</label>
            <input type="number" name="built_up_area" class="form-control" value="<?= htmlspecialchars($row['built_up_area'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Super Built-up</label>
            <input type="number" name="super_built_up_area" class="form-control" value="<?= htmlspecialchars($row['super_built_up_area'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Area Unit</label>
            <select name="area_unit" class="form-select">
              <option value="sqft" <?= ($row['area_unit']??'')==='sqft' ? 'selected' : '' ?>>sqft</option>
              <option value="sqm" <?= ($row['area_unit']??'')==='sqm' ? 'selected' : '' ?>>sqm</option>
              <option value="sqyrd" <?= ($row['area_unit']??'')==='sqyrd' ? 'selected' : '' ?>>sqyrd</option>
              <option value="acre" <?= ($row['area_unit']??'')==='acre' ? 'selected' : '' ?>>acre</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="adm-form-label">Floor No.</label>
            <input type="number" name="floor_number" class="form-control" placeholder="e.g. 5" value="<?= htmlspecialchars($row['floor_number'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Total Floors</label>
            <input type="number" name="total_floors" class="form-control" placeholder="e.g. 18" value="<?= htmlspecialchars($row['total_floors'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Furnishing</label>
            <select name="furnishing_status" class="form-select">
              <option value="">--</option>
              <?php foreach(['Unfurnished','Semi-Furnished','Furnished'] as $f): ?>
              <option value="<?= $f ?>" <?= ($row['furnishing_status']??'')===$f ? 'selected' : '' ?>><?= $f ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="adm-form-label">Facing</label>
            <select name="facing" class="form-select">
              <option value="">--</option>
              <?php foreach(['East','North','North-East','West','South','Other'] as $f): ?>
              <option value="<?= $f ?>" <?= ($row['facing']??'')===$f ? 'selected' : '' ?>><?= $f ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="adm-form-label">Age of Property</label>
            <input type="text" name="age_of_construction" class="form-control" placeholder="e.g. 5 years" value="<?= htmlspecialchars($row['age_of_construction'] ?? '') ?>">
          </div>
          <div class="col-md-6 d-flex align-items-center">
            <div class="form-check mt-3">
              <input class="form-check-input" type="checkbox" id="vastu_compliant" name="vastu_compliant" <?= (!empty($row['vastu_compliant'])) ? 'checked' : '' ?>>
              <label class="form-check-label fw-600" for="vastu_compliant">Vastu Compliant</label>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Amenities -->
      <div class="adm-card">
        <div class="adm-card-title">Amenities</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="adm-form-label">Amenities</label>
            <textarea name="amenities" class="form-control" rows="3" placeholder="Swimming Pool, Gym, Parking, Power Backup, 24x7 Security"><?= htmlspecialchars($row['amenities'] ?? '') ?></textarea>
            <div class="form-text">Separate items with either a comma (,) or a pipe (|). e.g. Gym, Pool, Lift</div>
          </div>
        </div>
      </div>
      
      <!-- Images & Media -->
      <div class="adm-card">
        <div class="adm-card-title">Images & Media</div>
        <div class="row g-3">
          <div class="col-md-12">
            <label class="adm-form-label">Featured Image</label>
            <?php if (!empty($row['thumbnail_image'])): ?>
              <div class="mt-2 mb-2">
                <img src="<?= upload($row['thumbnail_image']) ?>" height="60" class="rounded border">
                <label style="font-size:12px;cursor:pointer;display:block;margin-top:4px;">
                  <input type="checkbox" name="delete_thumbnail_image" value="1"> Delete Image
                </label>
              </div>
            <?php endif; ?>
            <input type="file" name="thumbnail_image" class="form-control" accept="image/*">
          </div>
          <div class="col-md-12">
            <label class="adm-form-label">Gallery Images (multiple)</label>
            <?php if (!empty($row['gallery_images'])): $gals = json_decode($row['gallery_images'], true); if ($gals): ?>
              <div class="mt-2 mb-2 d-flex gap-3 flex-wrap">
                <?php foreach ($gals as $g): ?>
                  <div class="text-center">
                    <img src="<?= upload($g) ?>" height="50" style="object-fit:cover;width:50px;display:block;margin-bottom:4px;" class="rounded border">
                    <label style="font-size:11px;cursor:pointer;">
                      <input type="checkbox" name="delete_gallery_images[]" value="<?= htmlspecialchars($g) ?>"> Delete
                    </label>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; endif; ?>
            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
          </div>
          
          <div class="col-md-12">
            <label class="adm-form-label">Floor Plans</label>
            <?php if (!empty($row['floor_plan_images'])): $fps = json_decode($row['floor_plan_images'], true); if ($fps): ?>
              <div class="mt-2 mb-2 d-flex gap-3 flex-wrap">
                <?php foreach ($fps as $fp): ?>
                  <div class="text-center">
                    <img src="<?= upload($fp) ?>" height="50" style="object-fit:cover;width:50px;display:block;margin-bottom:4px;" class="rounded border">
                    <label style="font-size:11px;cursor:pointer;">
                      <input type="checkbox" name="delete_floor_plan_images[]" value="<?= htmlspecialchars($fp) ?>"> Delete
                    </label>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; endif; ?>
            <input type="file" name="floor_plan_images[]" class="form-control" accept="image/*" multiple>
          </div>
          
          <div class="col-md-12">
            <label class="adm-form-label">Property Brochure (PDF or Image)</label>
            <div class="form-text mb-2">Upload brochure file in PDF or image format (under 10MB).</div>
            <?php if (!empty($row['brochure_pdf'])): ?>
              <div class="mt-1 mb-2">
                <a href="<?= upload($row['brochure_pdf']) ?>" target="_blank" class="btn btn-sm btn-outline-info">View Uploaded</a>
                <label style="font-size:12px;cursor:pointer;display:block;margin-top:4px;">
                  <input type="checkbox" name="delete_brochure_pdf" value="1"> Delete Brochure
                </label>
              </div>
            <?php endif; ?>
            <input type="file" name="brochure_pdf" class="form-control" accept="application/pdf,image/*">
          </div>

          <div class="col-12">
            <label class="adm-form-label">Video Tour URL</label>
            <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=... or Vimeo / direct MP4 link" value="<?= htmlspecialchars($row['video_url'] ?? '') ?>">
          </div>
        </div>
      </div>

    </div>
    
    <div class="col-lg-4">
      
      <!-- Pricing Sidebar -->
      <div class="adm-card">
        <div class="adm-card-title">Pricing</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="adm-form-label">Price (₹)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($row['price'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Price Unit</label>
            <select name="price_unit" class="form-select">
              <option value="Total" <?= ($row['price_unit']??'')==='Total' ? 'selected' : '' ?>>Total</option>
              <option value="/sqft" <?= ($row['price_unit']??'')==='/sqft' ? 'selected' : '' ?>>/ sqft</option>
              <option value="/month" <?= ($row['price_unit']??'')==='/month' ? 'selected' : '' ?>>/ month</option>
            </select>
          </div>
          <div class="col-12">
            <label class="adm-form-label">Price Display Override</label>
            <input type="text" name="price_display_override" class="form-control" placeholder="e.g. ₹ 1.2 Cr — 1.5 Cr" value="<?= htmlspecialchars($row['price_display_override'] ?? '') ?>">
          </div>
          <div class="col-12">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="is_gst_inclusive" name="is_gst_inclusive" <?= (!empty($row['is_gst_inclusive'])) ? 'checked' : '' ?>>
              <label class="form-check-label fw-600" for="is_gst_inclusive">Price is GST-inclusive</label>
            </div>
          </div>
        </div>
      </div>

      <!-- Location Sidebar -->
      <div class="adm-card">
        <div class="adm-card-title">Location</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="adm-form-label">Link to Project</label>
            <select name="project_id" class="form-select">
              <option value="">-- Independent Property --</option>
              <?php foreach ($projects as $pr): ?>
              <option value="<?= $pr['id'] ?>" <?= ($row['project_id']??0)==$pr['id']?'selected':'' ?>><?= htmlspecialchars($pr['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <label class="adm-form-label">City</label>
            <select name="city_id" class="form-select" id="city_id">
              <option value="">-- Select --</option>
              <?php foreach ($cities as $c): ?>
              <option value="<?= $c['id'] ?>" <?= ($row['city_id']??0)==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <label class="adm-form-label">Locality</label>
            <select name="locality_id" class="form-select" id="locality_id">
              <option value="">-- Select --</option>
              <?php foreach ($localities as $l): ?>
              <option value="<?= $l['id'] ?>" data-city="<?= $l['city_id'] ?>" <?= ($row['locality_id']??0)==$l['id']?'selected':'' ?>><?= htmlspecialchars($l['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <label class="adm-form-label">Address</label>
            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($row['address'] ?? '') ?>">
          </div>
          <div class="col-6">
            <label class="adm-form-label">Pincode</label>
            <input type="text" name="pincode" class="form-control" value="<?= htmlspecialchars($row['pincode'] ?? '') ?>">
          </div>
          <div class="col-6">
            <label class="adm-form-label">Latitude</label>
            <input type="text" name="latitude" class="form-control" placeholder="19.0760" value="<?= htmlspecialchars($row['latitude'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Longitude</label>
            <input type="text" name="longitude" class="form-control" placeholder="72.8777" value="<?= htmlspecialchars($row['longitude'] ?? '') ?>">
          </div>
        </div>
      </div>

      <!-- RERA & Possession Sidebar -->
      <div class="adm-card">
        <div class="adm-card-title">RERA & Possession</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="adm-form-label">RERA Registration</label>
            <input type="text" name="rera_id" class="form-control" value="<?= htmlspecialchars($row['rera_id'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Possession Date</label>
            <input type="text" name="possession_date" class="form-control" placeholder="e.g. Dec 2026" value="<?= htmlspecialchars($row['possession_date'] ?? '') ?>">
          </div>
        </div>
      </div>

      <!-- Owner Info & Status -->
      <div class="adm-card">
        <div class="adm-card-title">Owner & Status</div>
        <div class="row g-3">
          <div class="col-12">
            <label class="adm-form-label">Contact Name</label>
            <input type="text" name="owner_name" class="form-control" value="<?= htmlspecialchars($row['owner_name'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Phone</label>
            <input type="text" name="owner_phone" class="form-control" value="<?= htmlspecialchars($row['owner_phone'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Email</label>
            <input type="email" name="owner_email" class="form-control" value="<?= htmlspecialchars($row['owner_email'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="adm-form-label">Status</label>
            <select name="status" class="form-select">
              <?php foreach(['Active','Sold','Rented','Inactive'] as $s): ?>
              <option value="<?= $s ?>" <?= ($row['status']??'')===$s ? 'selected' : '' ?>><?= $s ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" <?= (!empty($row['is_featured'])) ? 'checked' : '' ?>>
              <label class="form-check-label fw-600" for="is_featured">Featured Property</label>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>

  <div class="mt-4 pt-3 border-top d-flex gap-2">
    <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="fas fa-save me-2"></i>Save Property</button>
    <a href="?action=list" class="btn btn-outline-secondary px-4">Cancel</a>
  </div>
</form>

<script>
// City -> Locality dynamic dropdown logic
const citySelect = document.getElementById('city_id');
const localitySelect = document.getElementById('locality_id');
if (citySelect && localitySelect) {
  const allLocalities = Array.from(localitySelect.options);
  citySelect.addEventListener('change', function() {
    const cityId = this.value;
    localitySelect.innerHTML = '<option value="">-- Select --</option>';
    allLocalities.forEach(opt => {
      if (!opt.value || opt.dataset.city === cityId) {
        localitySelect.appendChild(opt);
      }
    });
  });
  citySelect.dispatchEvent(new Event('change'));
  // re-select original value if editing
  const origVal = "<?= $row['locality_id'] ?? '' ?>";
  if(origVal) localitySelect.value = origVal;
}
</script>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
