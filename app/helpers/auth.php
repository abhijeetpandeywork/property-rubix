<?php
/**
 * Auth helper — session-based authentication for admin panel.
 */

function authCheck(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['admin_id'])) {
        $loginUrl = BASE_URL . 'admin/login.php';
        header('Location: ' . $loginUrl);
        exit;
    }
}

function isLoggedIn(): bool {
    return !empty($_SESSION['admin_id']);
}

function currentUser(): ?array {
    if (!isLoggedIn()) return null;
    return [
        'id'   => $_SESSION['admin_id'],
        'name' => $_SESSION['admin_name'] ?? 'Admin',
        'role' => $_SESSION['admin_role'] ?? 'admin',
        'email'=> $_SESSION['admin_email'] ?? '',
    ];
}

function hasRole(string ...$roles): bool {
    $role = $_SESSION['admin_role'] ?? '';
    return in_array($role, $roles, true);
}

function canDo(string $module, string $action = 'view'): bool {
    if (hasRole('super_admin')) return true;
    $role = $_SESSION['admin_role'] ?? '';
    $pdo  = db();
    $col  = 'can_' . $action;
    $stmt = $pdo->prepare("SELECT $col FROM permissions WHERE role = ? AND (module = ? OR module = 'all')");
    $stmt->execute([$role, $module]);
    $row = $stmt->fetch();
    return $row && (bool)$row[$col];
}

function adminLogin(array $user): void {
    session_regenerate_id(true);
    $_SESSION['admin_id']    = $user['id'];
    $_SESSION['admin_name']  = $user['name'];
    $_SESSION['admin_role']  = $user['role'];
    $_SESSION['admin_email'] = $user['email'];
}

function adminLogout(): void {
    session_destroy();
    session_start();
    session_regenerate_id(true);
}

/**
 * Log an admin action to audit_log.
 */
function logAction(string $action, string $entity, ?int $entityId = null, ?string $oldValue = null, ?string $newValue = null): void {
    try {
        $user = currentUser();
        $pdo  = db();
        $stmt = $pdo->prepare("INSERT INTO audit_log (user_id, action, entity, entity_id, old_value, new_value, ip_address) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([
            $user['id']   ?? null,
            $action,
            $entity,
            $entityId,
            $oldValue,
            $newValue,
            $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    } catch (Throwable) {
        // Non-fatal — don't break the app if audit log fails
    }
}
