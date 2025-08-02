<?php

// ===================================================================
// FICHIER : src/bootstrap.php (MODIFIÉ)
// On supprime les 'require_once' manuels car Composer s'en charge maintenant.
// ===================================================================

declare(strict_types=1);

// Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloading via Composer - C'est la seule inclusion de classe dont nous avons besoin !
require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Fonctions d'aide
require_once __DIR__ . '/Shared/Helpers.php';

/**
 * Crée et retourne une instance de connexion à la base de données (PDO).
 *
 * @return PDO
 */
function getDatabaseConnection(): PDO
{
    try {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbname = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        // En mode de débogage, afficher l'erreur. Sinon, afficher un message générique.
        if ($_ENV['APP_DEBUG'] === 'true') {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        } else {
            die("Erreur de serveur. Veuillez réessayer plus tard.");
        }
    }
}

// On crée la connexion à la base de données pour que le reste de l'application puisse l'utiliser.
$pdo = getDatabaseConnection();

// src/bootstrap.php

// ... (tout votre code de bootstrap existant) ...

// On crée la connexion à la base de données en utilisant notre nouvelle classe.
// La variable $pdo sera maintenant disponible pour le reste de l'application.
$pdo = App\Shared\Database::getInstance();