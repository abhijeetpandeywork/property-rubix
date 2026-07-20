<?php
/**
 * PropertyRubix — Database Configuration
 * XAMPP Defaults: host=localhost, user=root, pass=''
 * DB Name: property_rubix
 */

define('DB_HOST',    'localhost');
define('DB_NAME',    'property_rubix');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Returns a singleton PDO instance.
 * Usage: $pdo = db();
 */
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('
            <div style="font-family:sans-serif;padding:30px;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;max-width:600px;margin:40px auto">
                <h2 style="color:#dc2626">⚠ Database Connection Error</h2>
                <p>Could not connect to MySQL. Please check:</p>
                <ul>
                    <li>XAMPP MySQL service is running</li>
                    <li>Database <strong>' . DB_NAME . '</strong> exists in phpMyAdmin</li>
                    <li>Credentials in <code>config/db.php</code> are correct</li>
                    <li>You have imported <code>database/schema.sql</code> and <code>database/seed.sql</code></li>
                </ul>
                <p style="color:#6b7280;font-size:0.875rem">Error: ' . htmlspecialchars($e->getMessage()) . '</p>
            </div>');
        }
    }
    return $pdo;
}

// ── URL & path helpers ─────────────────────────────────────────────────────

// Detect site root automatically — works on any subfolder in XAMPP
$_script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$_admin_pos = stripos($_script, '/admin/');
if ($_admin_pos !== false) {
    $_site_root = substr($_script, 0, $_admin_pos);
} else {
    // Strip /public/index.php or similar from the path
    $_public_pos = stripos($_script, '/public/');
    if ($_public_pos !== false) {
        $_site_root = substr($_script, 0, $_public_pos);
    } else {
        $_site_root = rtrim(dirname($_script), '/\\');
    }
}

define('SITE_ROOT', rtrim($_site_root, '/'));
define('BASE_URL',
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
    . SITE_ROOT . '/'
);
define('PUBLIC_URL', BASE_URL);

// Absolute filesystem paths
define('ROOT_PATH',   dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH',    ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);
define('UPLOAD_PATH', ROOT_PATH . 'uploads' . DIRECTORY_SEPARATOR);
define('UPLOAD_URL',  PUBLIC_URL . 'uploads/');

// Brand
define('SITE_NAME', 'PropertyRubix');
define('SITE_TAGLINE', 'Find Your Perfect Property');

// ── Global helper functions ─────────────────────────────────────────────────

/**
 * HTML-escape a string for output.
 */
function e(?string $str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Return the public URL for an asset file (CSS, JS, img).
 */
function asset(string $path): string {
    return PUBLIC_URL . 'assets/' . ltrim($path, '/');
}

/**
 * Return the public URL for an uploaded file.
 */
function upload(?string $path): string {
    if (!$path) return '';
    // If it's already an absolute URL, return as-is
    if (str_starts_with($path, 'http')) return $path;
    return UPLOAD_URL . ltrim($path, '/');
}
