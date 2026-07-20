<?php
/**
 * Admin Login Page
 */
require_once __DIR__ . '/../config/db.php';
require_once APP_PATH . 'helpers/auth.php';
require_once APP_PATH . 'helpers/csrf.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'admin/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfVerify()) {
        $error = 'Security token expired. Please try again.';
    } else {
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$email || !$password) {
            $error = 'Email and password are required.';
        } else {
            $stmt = db()->prepare("SELECT * FROM users WHERE email=? AND status='active' LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                adminLogin($user);
                db()->prepare("UPDATE users SET last_login=NOW() WHERE id=?")->execute([$user['id']]);
                logAction('LOGIN', 'users', $user['id']);
                header('Location: ' . BASE_URL . 'admin/');
                exit;
            } else {
                $error = 'Invalid email or password.';
                // Brief sleep to slow brute-force
                sleep(1);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login | PropertyRubix</title>
<link rel="icon" href="<?= PUBLIC_URL ?>assets/img/favicon.svg" type="image/svg+xml">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body { font-family:'Inter',sans-serif; background:linear-gradient(135deg,#111 0%,#222 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; }
.login-card { background:white; border-radius:20px; padding:48px 40px; max-width:420px; width:100%; box-shadow:0 24px 64px rgba(0,0,0,0.6); }
.login-logo { font-size:1.8rem; font-weight:800; color: #000; font-family:'Poppins',sans-serif; letter-spacing: -0.5px; }
.login-logo span { color: #a9804b; }
.btn-login { background:#a9804b; border-color:#a9804b; font-weight:600; padding:12px; color: #fff; }
.btn-login:hover { background:#8f6b3d; border-color:#8f6b3d; color: #fff; }
.form-control:focus { border-color:#a9804b; box-shadow:0 0 0 3px rgba(169,128,75,0.25); }
.hint-box { background:#fffdf5; border:1px solid #f2e2ba; border-radius:10px; padding:12px 16px; font-size:0.78rem; color:#8f6b3d; }
</style>
</head>
<body>
<div class="login-card">
  <div class="text-center mb-4">
    <p class="login-logo mb-1">property<span>rubix</span></p>
    <p class="text-muted" style="font-size:0.85rem">Admin Control Panel</p>
  </div>

  <?php if ($error): ?>
  <div class="alert alert-danger d-flex align-items-center gap-2 py-2" style="font-size:0.875rem">
    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <form method="post" novalidate>
    <?= csrfField() ?>
    <div class="mb-3">
      <label class="form-label fw-600" style="font-size:0.875rem">Email Address</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
        <input type="email" name="email" class="form-control" placeholder="Enter your email"
               value="<?= htmlspecialchars($_POST['email'] ?? 'admin@propertyrubix.com') ?>" required autofocus>
      </div>
    </div>
    <div class="mb-4">
      <label class="form-label fw-600" style="font-size:0.875rem">Password</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
        <input type="password" name="password" class="form-control" placeholder="Enter your password"
               required id="pwdInput">
        <button class="btn btn-outline-secondary" type="button" onclick="togglePwd()">
          <i class="fas fa-eye" id="pwdIcon"></i>
        </button>
      </div>
    </div>
    <button type="submit" class="btn btn-login btn-primary w-100 mb-3">
      <i class="fas fa-sign-in-alt me-2"></i>Sign In
    </button>
  </form>


  <p class="text-center mt-4 mb-0">
    <a href="<?= PUBLIC_URL ?>" class="text-muted" style="font-size:0.8rem">
      <i class="fas fa-arrow-left me-1"></i>Back to Website
    </a>
  </p>
</div>

<script>
function togglePwd() {
  const i = document.getElementById('pwdInput');
  const ic = document.getElementById('pwdIcon');
  i.type = i.type === 'password' ? 'text' : 'password';
  ic.className = i.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
</body>
</html>
