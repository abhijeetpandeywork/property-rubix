<?php
/**
 * PropertyRubix — API Authentication & Rate Limiting Helper
 */

class ApiAuth {
    private static ?array $tokenData = null;

    /**
     * Authenticate the request via Bearer Token or URL query param.
     * Logs 401/429 JSON response and exits if authentication fails.
     */
    public static function authenticate(string $requiredScope = ''): array {
        if (self::$tokenData !== null) {
            if ($requiredScope) {
                self::checkScope($requiredScope);
            }
            return self::$tokenData;
        }

        $token = self::getBearerToken();

        if (!$token) {
            self::abort(401, 'Unauthorized: Token is missing.');
        }

        $tokenHash = hash('sha256', $token);
        $pdo = db();

        $stmt = $pdo->prepare("
            SELECT id, client_name, client_email, scopes, rate_limit_rpm, is_active, expires_at 
            FROM api_tokens 
            WHERE token_hash = ? 
            LIMIT 1
        ");
        $stmt->execute([$tokenHash]);
        $tokenRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tokenRecord) {
            self::abort(401, 'Unauthorized: Invalid token.');
        }

        if (!(int)$tokenRecord['is_active']) {
            self::abort(401, 'Unauthorized: Token is suspended.');
        }

        if ($tokenRecord['expires_at'] && strtotime($tokenRecord['expires_at']) < time()) {
            self::abort(401, 'Unauthorized: Token has expired.');
        }

        // Parse scopes
        try {
            $scopes = json_decode($tokenRecord['scopes'], true) ?: [];
        } catch (Throwable $e) {
            $scopes = [];
        }
        $tokenRecord['scopes'] = $scopes;

        // Perform Rate Limiting
        self::enforceRateLimit((int)$tokenRecord['id'], (int)$tokenRecord['rate_limit_rpm']);

        // Update Last Used timestamp
        $pdo->prepare("UPDATE api_tokens SET last_used_at = NOW() WHERE id = ?")
            ->execute([$tokenRecord['id']]);

        self::$tokenData = $tokenRecord;

        if ($requiredScope) {
            self::checkScope($requiredScope);
        }

        return self::$tokenData;
    }

    /**
     * Check if the authenticated token possesses the required scope.
     */
    public static function checkScope(string $requiredScope): void {
        if (self::$tokenData === null) {
            self::abort(401, 'Unauthorized: Access token not validated.');
        }

        $scopes = self::$tokenData['scopes'] ?? [];
        if (!in_array($requiredScope, $scopes, true) && !in_array('admin', $scopes, true)) {
            self::abort(403, "Forbidden: Missing required scope '$requiredScope'.");
        }
    }

    /**
     * Extract token from request headers or query parameter.
     */
    private static function getBearerToken(): ?string {
        $headers = self::getRequestHeaders();
        $authHeader = $headers['Authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return trim($matches[1]);
        }

        // Fallback to query parameter
        return $_GET['api_key'] ?? $_GET['token'] ?? null;
    }

    /**
     * Retrieve all HTTP headers.
     */
    private static function getRequestHeaders(): array {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * Database-backed API Rate Limiting.
     */
    private static function enforceRateLimit(int $tokenId, int $limitRpm): void {
        $pdo = db();
        $minute = date('Y-m-d H:i');

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("
                SELECT request_count 
                FROM api_rate_limits 
                WHERE token_id = ? AND request_minute = ? 
                FOR UPDATE
            ");
            $stmt->execute([$tokenId, $minute]);
            $count = $stmt->fetchColumn();

            if ($count === false) {
                $pdo->prepare("
                    INSERT INTO api_rate_limits (token_id, request_minute, request_count) 
                    VALUES (?, ?, 1)
                ")->execute([$tokenId, $minute]);
                $count = 1;
            } else {
                $count = (int)$count;
                if ($count >= $limitRpm) {
                    $pdo->commit();
                    header('Retry-After: 60');
                    self::abort(429, 'Rate Limit Exceeded: Limit is ' . $limitRpm . ' requests per minute.');
                }
                $pdo->prepare("
                    UPDATE api_rate_limits 
                    SET request_count = request_count + 1 
                    WHERE token_id = ? AND request_minute = ?
                ")->execute([$tokenId, $minute]);
                $count++;
            }
            $pdo->commit();

            // Set rate limit headers
            header('X-RateLimit-Limit: ' . $limitRpm);
            header('X-RateLimit-Remaining: ' . max(0, $limitRpm - $count));
            header('X-RateLimit-Reset: ' . (60 - (int)date('s')));
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // Fallback: allow requests on DB rate limiting error
        }
    }

    /**
     * Output standardized JSON error and exit.
     */
    private static function abort(int $code, string $message): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error'   => [
                'code'    => $code,
                'message' => $message
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
