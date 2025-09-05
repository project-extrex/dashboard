<?php
namespace App\Core;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class Router
{
    private array $routes = [];
    private array $middleware = [];

    public function get(string $path, callable $handler, array $middleware = [])
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, callable $handler, array $middleware = [])
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, callable $handler, array $middleware = [])
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, callable $handler, array $middleware = [])
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, callable $handler, array $middleware = [])
    {
        $this->routes[] = compact('method', 'path', 'handler', 'middleware');
    }

    public function use(callable $fn)
    {
        $this->middleware[] = $fn;
    }

    public function dispatch(string $httpMethod, string $uri)
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['path'], [
                    'handler' => $route['handler'],
                    'middleware' => $route['middleware']
                ]);
            }
        });

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo '404 Not Found';
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo '405 Method Not Allowed';
                break;

            case Dispatcher::FOUND:
                $data = $routeInfo[1];
                $handler = $data['handler'];
                $routeMiddleware = $data['middleware'];
                $vars = $routeInfo[2];

                // Global middleware
                foreach ($this->middleware as $mw) {
                    $mw($httpMethod, $uri, $vars);
                }

                // Route-specific middleware
                foreach ($routeMiddleware as $mw) {
                    $mw($httpMethod, $uri, $vars);
                }

                // Call the actual route handler
                echo $handler($vars);
                break;
        }
    }
}
