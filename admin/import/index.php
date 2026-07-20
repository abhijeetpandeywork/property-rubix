<?php
require_once __DIR__ . '/../includes/auth_check.php';

$pdo = db();
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));
        if ($ext === 'csv') {
            // Stub for processing CSV.
            $success = true;
        } else {
            $error = 'Please upload a valid CSV file.';
        }
    } else {
        $error = 'Failed to upload file.';
    }
}

$pageTitle = 'Import Data';
require __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <h2 class="mb-0">📥 Import Data</h2>
</div>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i> CSV Uploaded Successfully! Data is queued for processing.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="adm-card">
            <div class="adm-card-title">Upload Projects CSV</div>
            <p class="text-muted small mb-4">Upload a standard CSV file to bulk import properties/projects into the database. Make sure your CSV follows the standard header format (Name, City, Builder, Status, Price).</p>
            
            <form method="post" enctype="multipart/form-data">
                <?= csrfField() ?>
                <div class="mb-4">
                    <label class="adm-form-label">Select CSV File *</label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-download me-1"></i> Download Sample CSV</a>
                    <button type="submit" class="btn btn-primary fw-600"><i class="fas fa-upload me-2"></i> Start Import</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="adm-card" style="background: #f8f9fa;">
            <div class="adm-card-title">Recent Import History</div>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-history" style="font-size: 2rem; color: #ddd; margin-bottom: 10px;"></i>
                <p>No recent imports found.</p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
