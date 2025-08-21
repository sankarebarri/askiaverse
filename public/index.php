<?php
// public/index.php - Front Controller

// Notre point d'entrée principal. Il initialise l'application et
// utilise le routeur pour gérer la requête.
// ===================================================================

// Debug: Log the request
error_log("Request URI: " . $_SERVER['REQUEST_URI']);

// On inclut notre fichier de démarrage qui prépare tout.
// Notez que le chemin a changé pour correspondre à votre nouvelle structure.
require_once __DIR__ . '/../src/bootstrap.php';

// Debug: Check session after bootstrap
error_log("Session after bootstrap: " . print_r($_SESSION, true));

// On inclut et récupère notre routeur configuré.
$router = require_once __DIR__ . '/../config/routes.php';

// On récupère l'URI de la requête (ex: /login) et la méthode (GET, POST).
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

error_log("Dispatching to URI: $uri, Method: $requestMethod");

// Le routeur se charge de trouver la bonne action à exécuter.
// On lui passe la connexion PDO qui a été créée dans bootstrap.php.
$router->dispatch($uri, $requestMethod, $pdo);

