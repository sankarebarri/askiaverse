<?php

// ===================================================================
// FICHIER : src/Shared/Router.php
// NOTE : Le namespace a été ajouté pour correspondre à la structure des dossiers.
// ===================================================================
namespace App\Shared;

use PDO;

class Router
{
    protected array $routes = [];

    public function get(string $uri, string $controller, string $method): void
    {
        $this->routes['GET'][$uri] = ['controller' => $controller, 'method' => $method];
    }

    public function post(string $uri, string $controller, string $method): void
    {
        $this->routes['POST'][$uri] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch(string $uri, string $requestMethod, PDO $pdo): void
    {
        if (isset($this->routes[$requestMethod][$uri])) {
            $route = $this->routes[$requestMethod][$uri];
            $controllerName = $route['controller'];
            $methodName = $route['method'];

            // Add namespace if not already present
            if (strpos($controllerName, '\\') === false) {
                $controllerName = 'App\\Controllers\\' . $controllerName;
            }

            $controller = new $controllerName($pdo);
            $controller->$methodName();
        } else {
            http_response_code(404);
            echo "Page non trouvée.";
        }
    }
}
