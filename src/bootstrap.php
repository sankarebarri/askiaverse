<?php
// src/bootstrap.php

// 1. Charger la config globale
$config = require __DIR__ . '/../config/config.php';

// 2. Établir la connexion PDO à la base de données
try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $config['db']['host'],
        $config['db']['port'],
        $config['db']['database']
    );
    $pdo = new PDO(
        $dsn,
        $config['db']['user'],
        $config['db']['pass'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // 3. En cas d'erreur, stopper et afficher le message
    exit('Échec de la connexion : ' . $e->getMessage());
}

// 4. Renvoyer l'instance PDO pour utilisation dans l'application
return $pdo;
