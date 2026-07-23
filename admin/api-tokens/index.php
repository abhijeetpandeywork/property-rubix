<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/crud_helpers.php';

$pdo = db();

$generatedToken = '';
$error = '';
$success = '';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $clientName = trim($_POST['client_name'] ?? '');
        $limitRpm   = (int)($_POST['rate_limit_rpm'] ?? 60);
        $expiresAt  = trim($_POST['expires_at'] ?? '');
        $selectedScopes = $_POST['scopes'] ?? [];
        
        if (empty($clientName)) {
            $error = 'Client name is required.';
        } else {
            // Generate raw token (40 char hex)
            $rawToken = 'pr_live_' . bin2hex(random_bytes(20));
            $tokenHash = hash('sha256', $rawToken);
            
            $scopesStr = implode(',', $selectedScopes) ?: 'listings:read';
            $expiryVal = !empty($expiresAt) ? $expiresAt : null;
            
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO api_tokens (client_name, token_hash, scopes, rate_limit_rpm, status, expires_at)
                    VALUES (?, ?, ?, ?, 'active', ?)
                ");
                $stmt->execute([$clientName, $tokenHash, $scopesStr, $limitRpm, $expiryVal]);
                
                $generatedToken = $rawToken;
                $success = 'API token generated successfully. Make sure to copy it now, as you won\'t be able to see it again!';
                logAction('CREATE', 'api_tokens', $pdo->lastInsertId());
            } catch (PDOException $e) {
                $error = 'Failed to generate token: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'revoke') {
        $tokenId = (int)($_POST['token_id'] ?? 0);
        if ($tokenId > 0) {
            $pdo->prepare("UPDATE api_tokens SET status='revoked' WHERE id=?")->execute([$tokenId]);
            $success = 'Token successfully revoked.';
            logAction('UPDATE', 'api_tokens', $tokenId);
        }
    } elseif ($action === 'activate') {
        $tokenId = (int)($_POST['token_id'] ?? 0);
        if ($tokenId > 0) {
            $pdo->prepare("UPDATE api_tokens SET status='active' WHERE id=?")->execute([$tokenId]);
            $success = 'Token successfully activated.';
            logAction('UPDATE', 'api_tokens', $tokenId);
        }
    } elseif ($action === 'delete') {
        $tokenId = (int)($_POST['token_id'] ?? 0);
        if ($tokenId > 0) {
            $pdo->prepare("DELETE FROM api_tokens WHERE id=?")->execute([$tokenId]);
            $success = 'Token record deleted successfully.';
            logAction('DELETE', 'api_tokens', $tokenId);
        }
    }
}

// Fetch all existing active & revoked tokens
$tokens = $pdo->query("SELECT * FROM api_tokens ORDER BY id DESC")->fetchAll();

$pageTitle = 'API Tokens Manager';
require __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid py-2">
    <?= flashMsg() ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($generatedToken): ?>
        <div class="card border-warning mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark fw-bold">
                <i class="fas fa-key me-2"></i>Copy Your New API Key
            </div>
            <div class="card-body">
                <p class="card-text text-danger fw-bold">
                    <i class="fas fa-exclamation-triangle me-1"></i> Warning: Copy this key now! It will not be shown again for security reasons.
                </p>
                <div class="input-group mb-2">
                    <input type="text" class="form-control font-monospace bg-light" id="rawTokenField" value="<?= htmlspecialchars($generatedToken) ?>" readonly>
                    <button class="btn btn-dark" type="button" onclick="copyTokenText()">
                        <i class="fas fa-copy me-1"></i>Copy
                    </button>
                </div>
            </div>
        </div>
        <script>
            function copyTokenText() {
                var copyText = document.getElementById("rawTokenField");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);
                alert("API Token copied to clipboard!");
            }
        </script>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Tokens List Grid -->
        <div class="col-lg-8">
            <div class="adm-card h-100">
                <div class="adm-card-title d-flex justify-content-between align-items-center mb-4">
                    <span>🔑 Authorized Integration Keys</span>
                    <span class="badge bg-secondary"><?= count($tokens) ?> Token(s)</span>
                </div>
                
                <?php if (empty($tokens)): ?>
                    <div class="text-center py-5">
                        <span class="fs-1 text-muted">🗝️</span>
                        <p class="text-muted mt-2">No API keys registered. Create one using the generator panel.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Client / Partner</th>
                                    <th>Permissions (Scopes)</th>
                                    <th>Limits</th>
                                    <th>Expiry</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tokens as $tk): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($tk['client_name']) ?></div>
                                            <small class="text-muted font-monospace" title="SHA-256 Hash">
                                                Hash: <?= substr($tk['token_hash'], 0, 10) ?>...
                                            </small>
                                        </td>
                                        <td>
                                            <?php 
                                            $scopes = explode(',', $tk['scopes']);
                                            foreach ($scopes as $sc):
                                                $badgeClass = $sc === 'leads:write' ? 'bg-success' : 'bg-primary';
                                            ?>
                                                <span class="badge <?= $badgeClass ?> me-1"><?= htmlspecialchars($sc) ?></span>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold"><?= (int)$tk['rate_limit_rpm'] ?></span> <small class="text-muted">RPM</small>
                                        </td>
                                        <td>
                                            <?php if ($tk['expires_at']): ?>
                                                <span class="<?= strtotime($tk['expires_at']) < time() ? 'text-danger fw-bold' : '' ?>">
                                                    <?= date('Y-m-d', strtotime($tk['expires_at'])) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Never</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($tk['status'] === 'active'): ?>
                                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Revoked</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <?php if ($tk['status'] === 'active'): ?>
                                                    <form method="post" onsubmit="return confirm('Revoke this key? All active integrations for this partner will fail.');">
                                                        <?= csrfField() ?>
                                                        <input type="hidden" name="action" value="revoke">
                                                        <input type="hidden" name="token_id" value="<?= $tk['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Revoke Access">
                                                            <i class="fas fa-minus-circle"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="post">
                                                        <?= csrfField() ?>
                                                        <input type="hidden" name="action" value="activate">
                                                        <input type="hidden" name="token_id" value="<?= $tk['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Activate Access">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                
                                                <form method="post" onsubmit="return confirm('Permanently delete this key record? Historical rate limits logs won\'t be affected but access will be terminated.');">
                                                    <?= csrfField() ?>
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="token_id" value="<?= $tk['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Permanent">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Token Generator Panel -->
        <div class="col-lg-4">
            <div class="adm-card h-100">
                <div class="adm-card-title mb-4">🆕 Issue Integration Key</div>
                
                <form method="post">
                    <?= csrfField() ?>
                    <input type="hidden" name="action" value="create">
                    
                    <div class="mb-3">
                        <label class="adm-form-label" for="client_name">Client / Partner Name</label>
                        <input type="text" name="client_name" id="client_name" class="form-control" placeholder="e.g. Chatbot Integration" required>
                    </div>

                    <div class="mb-3">
                        <label class="adm-form-label">Authorized Scopes</label>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="scopes[]" value="listings:read" id="scope_listings" checked>
                            <label class="form-check-label" for="scope_listings">
                                <strong>listings:read</strong><br>
                                <small class="text-muted">Allows listing projects, properties, cities.</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="scopes[]" value="leads:write" id="scope_leads" checked>
                            <label class="form-check-label" for="scope_leads">
                                <strong>leads:write</strong><br>
                                <small class="text-muted">Allows dynamic lead submission/CRM sync.</small>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="adm-form-label" for="rate_limit_rpm">Throttling Limit (RPM)</label>
                        <input type="number" name="rate_limit_rpm" id="rate_limit_rpm" class="form-control" value="60" min="1" required>
                        <div class="form-text">Max requests allowed per minute (Rate Limit).</div>
                    </div>

                    <div class="mb-4">
                        <label class="adm-form-label" for="expires_at">Expiration Date (Optional)</label>
                        <input type="date" name="expires_at" id="expires_at" class="form-control">
                        <div class="form-text">Leave empty for a non-expiring credentials token.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2">
                            <i class="fas fa-bolt me-2"></i>Generate Credentials
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
