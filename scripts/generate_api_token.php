<?php
/**
 * PropertyRubix — API Token Generator CLI Tool
 * ============================================================
 * Usage:
 *   php scripts/generate_api_token.php --client="Trovit Feed" --email="partner@trovit.com" --scopes="listings:read" --rpm=120
 * ============================================================
 */

define('CLI_ONLY', true);
require_once __DIR__ . '/../config/db.php';

// Parse command line arguments
$args = getopt('', ['client:', 'email:', 'scopes::', 'rpm::']);

$clientName  = trim($args['client'] ?? '');
$clientEmail = trim($args['email'] ?? '');
$scopesRaw   = trim($args['scopes'] ?? 'listings:read');
$rateLimit   = (int)($args['rpm'] ?? 60);

if (!$clientName || !$clientEmail) {
    echo "Error: Missing required parameters." . PHP_EOL;
    echo "Usage: php scripts/generate_api_token.php --client=\"Partner Name\" --email=\"partner@email.com\" [--scopes=\"listings:read,leads:write\"] [--rpm=60]" . PHP_EOL;
    exit(1);
}

if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
    echo "Error: Invalid email format." . PHP_EOL;
    exit(1);
}

// Format scopes as JSON array
$scopesArray = array_map('trim', explode(',', $scopesRaw));
$scopesJson  = json_encode($scopesArray);

// Generate secure token (prefix + random string)
$rawToken = 'pr_live_' . bin2hex(random_bytes(20));
$tokenHash = hash('sha256', $rawToken);
$tokenPreview = substr($rawToken, 0, 12) . '...';

try {
    $pdo = db();
    $stmt = $pdo->prepare("
        INSERT INTO api_tokens (client_name, client_email, token_hash, token_preview, scopes, rate_limit_rpm) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $clientName,
        $clientEmail,
        $tokenHash,
        $tokenPreview,
        $scopesJson,
        $rateLimit
    ]);

    echo "============================================================" . PHP_EOL;
    echo "  API Token Registered Successfully!" . PHP_EOL;
    echo "============================================================" . PHP_EOL;
    echo "  Client Name:   " . $clientName . PHP_EOL;
    echo "  Client Email:  " . $clientEmail . PHP_EOL;
    echo "  Scopes:        " . implode(', ', $scopesArray) . PHP_EOL;
    echo "  Rate Limit:    " . $rateLimit . " requests per minute" . PHP_EOL;
    echo "  Token Preview: " . $tokenPreview . PHP_EOL;
    echo PHP_EOL;
    echo "  YOUR PLAINTEXT API TOKEN (STORE SECURELY - SHOWN ONLY ONCE):" . PHP_EOL;
    echo "  >> " . $rawToken . " <<" . PHP_EOL;
    echo "============================================================" . PHP_EOL;
    
} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
