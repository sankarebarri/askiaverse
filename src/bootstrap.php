<?php

// ===================================================================
// FICHIER : src/bootstrap.php (MODIFIÉ)
// On supprime les 'require_once' manuels car Composer s'en charge maintenant.
// ===================================================================

declare(strict_types=1);

// Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    // Set session directory to a writable location
ini_set('session.save_path', '/tmp');
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
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? 3306;
        $dbname = $_ENV['DB_DATABASE'] ?? 'askiaverse';
        $username = $_ENV['DB_USERNAME'] ?? 'root';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        // En mode de débogage, afficher l'erreur. Sinon, afficher un message générique.
        $debug = $_ENV['APP_DEBUG'] ?? 'true';
        if ($debug === 'true') {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        } else {
            die("Erreur de serveur. Veuillez réessayer plus tard.");
        }
    }
}

// On crée la connexion à la base de données en utilisant notre nouvelle classe.
// La variable $pdo sera maintenant disponible pour le reste de l'application.
try {
    $pdo = getDatabaseConnection();
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}