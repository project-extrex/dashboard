<?php

namespace App\Registry;

use App\Core\Router as CoreRouter;

class Router {
    private static array $routes = [];
    private static ?CoreRouter $router = null;

    /**
     * Adds a route to the wrapper.
     */
    public static function addRoute(string $method, string $url, callable $handler): void {
        self::$routes[] = [
            'method' => strtoupper($method),
            'url' => $url,
            'handler' => $handler,
        ];
    }

    /**
     * Initializes the wrapper with the core router and registers all routes.
     */
    public static function initial(CoreRouter $router): void {
        self::$router = $router;

        foreach (self::$routes as $route) {
            self::$router->addRoute($route['method'], $route['url'], $route['handler']);
        }
    }
}