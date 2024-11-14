<?php

namespace App;

class Router {
    private $routes = [];

    public function addRoute($path, $controller, $action) {
        $this->routes[$path] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($uri) {
        foreach ($this->routes as $path => $route) {
            $pattern = preg_replace('#\{[^\}]+\}#', '([^/]+)', $path);
            if (preg_match("#^$pattern$#", $uri, $matches)) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];
                $controller = new $controllerName();
                call_user_func_array([$controller, $actionName], array_slice($matches, 1));
                return;
            }
        }
        // Si aucune route ne correspond, afficher une erreur 404
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
}
?>