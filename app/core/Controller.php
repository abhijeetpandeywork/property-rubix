<?php
/**
 * PropertyRubix — Base Controller
 */

class Controller {
    protected function view(string $viewPath, array $data = [], string $layout = 'main'): void {
        // Make data variables available in view
        extract($data);

        // Capture view content
        ob_start();
        $file = APP_PATH . 'views/' . $viewPath . '.php';
        if (!file_exists($file)) {
            echo "<p>View not found: $file</p>";
        } else {
            require $file;
        }
        $content = ob_get_clean();

        // Render layout
        $layoutFile = APP_PATH . 'views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function json(mixed $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }

    protected function input(string $key, mixed $default = null): mixed {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function isPost(): bool {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
    }

    protected function isAjax(): bool {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}
