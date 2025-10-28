<?php
namespace Src;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable $handler)
    {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function run()
    {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Hilangkan base path agar cocok dengan route di index.php
        $basePath = '/api-php-native/public';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Hilangkan trailing slash
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            $routePath = rtrim($route['path'], '/') ?: '/';
            if ($route['method'] === $method && $routePath === $uri) {
                call_user_func($route['handler']);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(["success" => false, "error" => "Route not found"]);
    }
}
