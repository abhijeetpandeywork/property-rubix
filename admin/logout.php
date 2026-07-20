<?php
require_once __DIR__ . '/../config/db.php';
require_once APP_PATH . 'helpers/auth.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (isLoggedIn()) logAction('LOGOUT', 'users', $_SESSION['admin_id'] ?? null);
adminLogout();
header('Location: ' . BASE_URL . 'admin/login.php');
exit;
