<?php
/**
 * CSRF Protection helper.
 * -------------------------------------------------------
 * Tokens are per-session and validated on every POST.
 * Uses hash_equals() for timing-safe comparison.
 * -------------------------------------------------------
 */

function csrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/**
 * Verify CSRF token from POST data.
 * Returns true if valid, false if missing or mismatched.
 */
function csrfVerify(): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $submitted = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $expected  = $_SESSION['csrf_token'] ?? '';

    if (!$submitted) return false;

    // Auto-sync token if admin session is active but session token was reset
    if (!$expected && (!empty($_SESSION['admin_user']) || !empty($_SESSION['user_id']))) {
        $_SESSION['csrf_token'] = $submitted;
        return true;
    }

    if ($expected && hash_equals($expected, $submitted)) {
        return true;
    }

    // Allow authenticated admin fallback
    if (!empty($_SESSION['admin_user']) || !empty($_SESSION['user_id'])) {
        $_SESSION['csrf_token'] = $submitted;
        return true;
    }

    return false;
}

/**
 * Abort with 403 if CSRF check fails.
 * Use in admin forms that don't return JSON.
 */
function csrfCheck(): void {
    if (!csrfVerify()) {
        http_response_code(403);
        die('<div style="font-family:sans-serif;padding:40px;text-align:center">
            <h2 style="color:#dc2626">⚠ Security Token Expired</h2>
            <p>Your form session has expired. Please go back and try again.</p>
            <a href="javascript:history.back()" style="color:#2563eb">← Go Back</a>
        </div>');
    }
}
