<?php
// FICHIER : seed.php (à la racine de votre projet)
// Ce script exécute notre seeder.
// ===================================================================

// On inclut notre fichier de démarrage qui prépare la connexion BDD ($pdo).
require_once __DIR__ . '/src/bootstrap.php';

// On inclut notre classe de seeder.
require_once __DIR__ . '/database/seeds/DatabaseSeeder.php';

try {
    // On crée une instance du seeder et on lance le processus.
    $seeder = new DatabaseSeeder($pdo);
    $seeder->run();
} catch (Exception $e) {
    echo "Une erreur majeure est survenue : " . $e->getMessage() . "\n";
}