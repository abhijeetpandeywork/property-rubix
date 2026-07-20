#!/usr/bin/env php
<?php
/**
 * PropertyRubix — Database Migration Runner
 * ============================================================
 * Usage:
 *   php database/migrate.php          # Run all pending migrations
 *   php database/migrate.php --status # Show migration status
 *   php database/migrate.php --reset  # Reset all (DESTRUCTIVE!)
 *   php database/migrate.php --fresh  # Reset + re-run all
 *
 * Each .sql file in database/migrations/ is run once and tracked
 * in a `migrations` table so it's never run twice.
 * ============================================================
 */

define('CLI_ONLY', true);

// Load .env and db connection
require_once __DIR__ . '/../config/db.php';

// ── CLI helpers ─────────────────────────────────────────────────────────────
function cli(string $msg, string $color = 'white'): void {
    $colors = [
        'green'  => "\033[0;32m",
        'red'    => "\033[0;31m",
        'yellow' => "\033[1;33m",
        'cyan'   => "\033[0;36m",
        'white'  => "\033[0m",
        'bold'   => "\033[1m",
    ];
    $reset = "\033[0m";
    $c = $colors[$color] ?? $reset;
    echo $c . $msg . $reset . PHP_EOL;
}

function separator(): void {
    cli(str_repeat('─', 60), 'cyan');
}

// ── Ensure migrations table exists ──────────────────────────────────────────
function ensureMigrationsTable(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `migrations` (
        `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `filename`   VARCHAR(255) NOT NULL,
        `applied_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `applied_by` VARCHAR(100) DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uq_filename` (`filename`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
}

// ── Get applied migrations ───────────────────────────────────────────────────
function getApplied(PDO $pdo): array {
    return $pdo->query("SELECT filename FROM migrations ORDER BY filename")
               ->fetchAll(PDO::FETCH_COLUMN);
}

// ── Run a single SQL file ────────────────────────────────────────────────────
function runMigration(PDO $pdo, string $file): bool {
    $sql = file_get_contents($file);
    if (!$sql || trim($sql) === '') {
        cli("  ⚠ Empty file, skipping", 'yellow');
        return true;
    }

    try {
        // Split on semicolons but keep statements intact
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            fn($s) => $s !== '' && !preg_match('/^--/', trim($s))
        );

        $pdo->beginTransaction();
        foreach ($statements as $stmt) {
            if (trim($stmt)) {
                $pdo->exec($stmt);
            }
        }
        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        cli("  ✗ SQL Error: " . $e->getMessage(), 'red');
        return false;
    }
}

// ── Mark as applied ──────────────────────────────────────────────────────────
function markApplied(PDO $pdo, string $filename): void {
    $user = get_current_user() ?: 'unknown';
    $pdo->prepare("INSERT INTO migrations (filename, applied_by) VALUES (?, ?)")
        ->execute([$filename, $user]);
}

// ── Commands ─────────────────────────────────────────────────────────────────

$args    = array_slice($argv, 1);
$command = $args[0] ?? '';

separator();
cli('  PropertyRubix — Database Migration Runner', 'bold');
cli('  DB: ' . DB_NAME . '@' . DB_HOST, 'cyan');
separator();

try {
    $pdo = db();
    ensureMigrationsTable($pdo);
} catch (Throwable $e) {
    cli("✗ Cannot connect to database: " . $e->getMessage(), 'red');
    cli("  → Copy .env.example to .env and configure DB settings", 'yellow');
    exit(1);
}

// Migration files (skip template and non-.sql files)
$migrationDir   = __DIR__ . '/migrations/';
$migrationFiles = glob($migrationDir . '[0-9]*.sql');
sort($migrationFiles);

// ── STATUS ───────────────────────────────────────────────────────────────────
if ($command === '--status') {
    $applied = getApplied($pdo);
    cli('Migration Status:', 'bold');
    echo PHP_EOL;

    if (empty($migrationFiles)) {
        cli('  No migration files found in database/migrations/', 'yellow');
    }

    foreach ($migrationFiles as $file) {
        $name     = basename($file);
        $isApplied = in_array($name, $applied, true);
        $status   = $isApplied ? '✓ Applied' : '○ Pending';
        $color    = $isApplied ? 'green' : 'yellow';
        cli("  [$status] $name", $color);
    }
    echo PHP_EOL;
    cli('Total: ' . count($migrationFiles) . ' migrations, ' . count($applied) . ' applied', 'cyan');
    exit(0);
}

// ── RESET ────────────────────────────────────────────────────────────────────
if ($command === '--reset') {
    cli('⚠ WARNING: This will DROP ALL TABLES! Type "yes" to confirm:', 'red');
    $confirm = trim(fgets(STDIN));
    if ($confirm !== 'yes') {
        cli('Aborted.', 'yellow');
        exit(0);
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `$table`");
        cli("  Dropped: $table", 'yellow');
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    cli('✓ All tables dropped.', 'green');
    exit(0);
}

// ── FRESH (reset + migrate) ───────────────────────────────────────────────────
if ($command === '--fresh') {
    cli('⚠ WARNING: This will DROP ALL TABLES and re-run migrations! Type "yes":', 'red');
    $confirm = trim(fgets(STDIN));
    if ($confirm !== 'yes') {
        cli('Aborted.', 'yellow');
        exit(0);
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `$table`");
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    ensureMigrationsTable($pdo);
    cli('✓ All tables dropped. Running fresh migrations...', 'green');
    // Fall through to run all
}

// ── RUN PENDING ───────────────────────────────────────────────────────────────
$applied = getApplied($pdo);
$pending = array_filter($migrationFiles, fn($f) => !in_array(basename($f), $applied, true));

if (empty($pending)) {
    cli('✓ All migrations are up to date.', 'green');
    exit(0);
}

cli('Running ' . count($pending) . ' pending migration(s)...', 'cyan');
echo PHP_EOL;

$successCount = 0;
$failCount    = 0;

foreach ($pending as $file) {
    $name = basename($file);
    cli("  → $name", 'white');

    if (runMigration($pdo, $file)) {
        markApplied($pdo, $name);
        cli("    ✓ Applied", 'green');
        $successCount++;
    } else {
        cli("    ✗ Failed — stopping here", 'red');
        $failCount++;
        break; // Stop on first failure to maintain order
    }
}

echo PHP_EOL;
separator();
cli("  Done: $successCount applied, $failCount failed", $failCount > 0 ? 'red' : 'green');
separator();

exit($failCount > 0 ? 1 : 0);
