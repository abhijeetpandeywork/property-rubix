<?php
/**
 * PropertyRubix — Database Configuration
 * -------------------------------------------------------
 * Reads from .env file automatically.
 * Copy .env.example → .env and fill in your local values.
 * NEVER commit your .env file.
 * -------------------------------------------------------
 */

// ── Load .env file ─────────────────────────────────────────────────────────
(function () {
    $envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
    if (!file_exists($envFile)) return;

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments and blank lines
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (!str_contains($line, '=')) continue;

        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);

        // Strip surrounding quotes
        if (preg_match('/^(["\'])(.*)(\1)$/', $value, $m)) {
            $value = $m[2];
        }

        if ($key && !isset($_ENV[$key])) {
            $_ENV[$key]    = $value;
            putenv("$key=$value");
        }
    }
})();

// ── Helper to read env with fallback ───────────────────────────────────────
function env(string $key, string $default = ''): string {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

// ── Database constants ──────────────────────────────────────────────────────
define('DB_HOST',    env('DB_HOST',    'localhost'));
define('DB_NAME',    env('DB_NAME',    'property_rubix'));
define('DB_USER',    env('DB_USER',    'root'));
define('DB_PASS',    env('DB_PASS',    ''));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));
define('DB_PORT',    env('DB_PORT',    '3306'));

// ── Application constants ───────────────────────────────────────────────────
define('APP_ENV',   env('APP_ENV',   'local'));      // local | staging | production
define('APP_DEBUG', env('APP_DEBUG', 'true') === 'true');

// ── PDO singleton ───────────────────────────────────────────────────────────
/**
 * Returns a singleton PDO instance.
 * Usage: $pdo = db();
 */
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
        );
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            $hint = APP_DEBUG ? htmlspecialchars($e->getMessage()) : 'Check server error logs.';
            die('
            <div style="font-family:sans-serif;padding:30px;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;max-width:600px;margin:40px auto">
                <h2 style="color:#dc2626">⚠ Database Connection Error</h2>
                <p>Could not connect to MySQL. Please check:</p>
                <ul>
                    <li>MySQL service is running</li>
                    <li>Your <code>.env</code> file exists and has correct values</li>
                    <li>Database <strong>' . DB_NAME . '</strong> exists</li>
                    <li>You have imported <code>database/schema.sql</code></li>
                </ul>
                <p style="color:#6b7280;font-size:0.875rem">Error: ' . $hint . '</p>
                <p style="font-size:0.8rem">Copy <code>.env.example</code> → <code>.env</code> and update credentials.</p>
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
define('SITE_NAME',    env('SITE_NAME',    'PropertyRubix'));
define('SITE_TAGLINE', env('SITE_TAGLINE', 'Find Your Perfect Property'));

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
    if (str_starts_with($path, 'http')) return $path;
    $path = ltrim($path, '/\\');
    if (str_starts_with(strtolower($path), 'uploads/')) {
        $path = substr($path, 8);
    } elseif (str_starts_with(strtolower($path), 'uploads\\')) {
        $path = substr($path, 8);
    }
    return UPLOAD_URL . ltrim($path, '/\\');
}
