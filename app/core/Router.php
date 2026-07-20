<?php
/**
 * PropertyRubix — Router
 * Simple regex-based front-controller router.
 */

class Router {
    private array $routes = [];

    public function get(string $path, string $controller, string $method, array $defaults = []): void {
        $this->addRoute('GET', $path, $controller, $method, $defaults);
    }

    public function post(string $path, string $controller, string $method, array $defaults = []): void {
        $this->addRoute('POST', $path, $controller, $method, $defaults);
    }

    private function addRoute(string $verb, string $path, string $controller, string $method, array $defaults): void {
        // Convert {param} placeholders to named capture groups
        $pattern = preg_replace('/\{([a-z_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        $this->routes[] = compact('verb', 'pattern', 'controller', 'method', 'defaults');
    }

    public function dispatch(): void {
        $httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri        = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        // Strip site sub-path prefix (e.g. /property-rubix/public)
        $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($scriptDir && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }
        $uri = '/' . ltrim($uri, '/');
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['verb'] !== $httpMethod) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Merge URL params + defaults
                $params = array_merge($route['defaults'], array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));

                $controllerFile = APP_PATH . 'controllers/' . $route['controller'] . '.php';
                if (!file_exists($controllerFile)) {
                    $this->abort(500, "Controller {$route['controller']} not found.");
                    return;
                }
                require_once $controllerFile;
                $ctrl = new $route['controller']();
                $ctrl->{$route['method']}($params);
                return;
            }
        }

        // No route matched
        $this->abort(404);
    }

    private function abort(int $code, string $message = ''): void {
        http_response_code($code);
        $view = APP_PATH . 'views/errors/' . $code . '.php';
        if (file_exists($view)) {
            require $view;
        } else {
            echo "<h1>$code Error</h1><p>" . htmlspecialchars($message) . "</p>";
        }
        exit;
    }
}
