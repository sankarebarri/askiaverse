<?php
// config/config.php

// 1. Charger l'autoloader de Composer (nécessaire pour phpdotenv)
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Charger le fichier .env et injecter les variables dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// 3. Définir la fonction env() si elle n'existe pas
if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

// 4. Retourner un tableau de configuration lisible
return [
    // Mode de l'application : 'development' ou 'production'
    'app_env'   => env('APP_ENV', 'production'),

    // Activation du mode debug (affiche les erreurs détaillées)
    'app_debug' => filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),

    // Paramètres de connexion à la base de données
    'db' => [
        'host'     => env('DB_HOST', 'localhost'),  // Hôte MySQL
        'port'     => env('DB_PORT', 3306),         // Port MySQL
        'database' => env('DB_DATABASE', 'askiaverse'),// Nom de la base
        'user'     => env('DB_USERNAME', 'root'),       // Utilisateur
        'pass'     => env('DB_PASSWORD', ''),           // Mot de passe
    ],
];
