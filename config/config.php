<?php
// config/config.php

// 1. Charger l'autoloader de Composer (nécessaire pour phpdotenv)
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Charger le fichier .env et injecter les variables dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// 3. Retourner un tableau de configuration lisible
return [
    // Mode de l'application : 'development' ou 'production'
    'app_env'   => $_ENV['APP_ENV']   ?? 'production',

    // Activation du mode debug (affiche les erreurs détaillées)
    'app_debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),

    // Paramètres de connexion à la base de données
    'db' => [
        'host'     => $_ENV['DB_HOST']     ?? '127.0.0.1',  // Hôte MySQL
        'port'     => $_ENV['DB_PORT']     ?? 3306,         // Port MySQL
        'database' => $_ENV['DB_DATABASE'] ?? 'askiaverse',// Nom de la base
        'user'     => $_ENV['DB_USER']     ?? 'root',       // Utilisateur
        'pass'     => $_ENV['DB_PASSWORD'] ?? '',           // Mot de passe
    ],
];
