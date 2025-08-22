<?php
// config/database.php
// Configuration de la base de données pour Askiaverse

// Charger l'autoloader de Composer (nécessaire pour phpdotenv)
require_once __DIR__ . '/../vendor/autoload.php';

// Charger le fichier .env et injecter les variables dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Définir la fonction env() si elle n'existe pas
if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

// Retourner la configuration de la base de données
return [
    'host'     => env('DB_HOST', 'localhost'),
    'port'     => env('DB_PORT', 3306),
    'dbname'   => env('DB_DATABASE', 'askiaverse'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset'  => 'utf8mb4',
]; 