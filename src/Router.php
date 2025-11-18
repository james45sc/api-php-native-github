<?php
namespace Src;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable $handler)
    {
        $path = rtrim($path, "/") ?: "/";
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function run()
    {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $basePath = '/api-php-native-github/public';

        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            $routePath = $route['path'];


            $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '([0-9]+)', $routePath);
            $pattern = "#^" . $pattern . "$#";

            if ($method === $route['method'] && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                call_user_func_array($route['handler'], $matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(["success" => false, "error" => "Route not found"]);
    }
}
