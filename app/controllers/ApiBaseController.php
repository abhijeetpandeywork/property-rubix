<?php
/**
 * PropertyRubix — Base API Controller
 */

class ApiBaseController extends Controller {
    
    /**
     * Enforce API token authentication and scope check.
     */
    protected function authenticate(string $requiredScope = ''): array {
        return ApiAuth::authenticate($requiredScope);
    }

    /**
     * Format a standard success response.
     */
    protected function apiSuccess(mixed $data, array $meta = [], int $code = 200): void {
        $response = [
            'success' => true,
            'data'    => $data
        ];
        if (!empty($meta)) {
            $response['meta'] = $meta;
        }
        
        $this->json($response, $code);
    }

    /**
     * Format a standard API error response.
     */
    protected function apiError(string $message, int $code = 400, array $details = []): void {
        $response = [
            'success' => false,
            'error'   => [
                'code'    => $code,
                'message' => $message
            ]
        ];
        if (!empty($details)) {
            $response['error']['details'] = $details;
        }
        
        $this->json($response, $code);
    }

    /**
     * Retrieve payload from raw JSON post requests.
     */
    protected function getJsonInput(): array {
        $raw = file_get_contents('php://input');
        if (empty($raw)) {
            return [];
        }
        try {
            return json_decode($raw, true) ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }
}
