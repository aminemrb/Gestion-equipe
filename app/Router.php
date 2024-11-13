<?php

class Router {
    private $routes = [];

    public function addRoute($path, $controllerName, $methodName) {
        $this->routes[$path] = ['controller' => $controllerName, 'method' => $methodName];
    }

    public function dispatch($path) {
        if (isset($this->routes[$path])) {
            $route = $this->routes[$path];
            $controllerName = $route['controller'];
            $methodName = $route['method'];

            $controllerFile = __DIR__ . "/controleurs/$controllerName.php";
            if (file_exists($controllerFile)) {
                require_once $controllerFile;

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();

                    if (method_exists($controller, $methodName)) {
                        call_user_func_array([$controller, $methodName], []);
                        return;
                    } else {
                        $this->sendNotFound("Méthode '$methodName' non trouvée dans le contrôleur '$controllerName'");
                    }
                } else {
                    $this->sendNotFound("Contrôleur '$controllerName' introuvable.");
                }
            } else {
                $this->sendNotFound("Fichier du contrôleur '$controllerFile' introuvable.");
            }
        } else {
            $this->sendNotFound("Route '$path' non définie.");
        }
    }

    // Méthode pour envoyer une réponse 404
    private function sendNotFound($message = "Page non trouvée") {
        http_response_code(404);
        echo "<h1>404 - $message</h1>";
    }
}
