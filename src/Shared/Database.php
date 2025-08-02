<?php

// ===================================================================
// FICHIER : src/Shared/Database.php (NOUVEAU FICHIER)
// Une classe dédiée pour gérer la connexion à la base de données.
// C'est une approche très propre et réutilisable.
// ===================================================================

namespace App\Shared;

use PDO;
use PDOException;

class Database
{
    /**
     * @var PDO|null L'instance unique de la connexion PDO.
     */
    private static ?PDO $instance = null;

    /**
     * Crée et retourne une instance unique de la connexion à la base de données (Singleton Pattern).
     *
     * @return PDO L'objet de connexion PDO.
     */
    public static function getInstance(): PDO
    {
        // Si une connexion n'a pas encore été créée...
        if (self::$instance === null) {
            // On charge la configuration de la base de données.
            $config = require __DIR__ . '/../../config/database.php';

            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                // On crée la nouvelle connexion et on la stocke.
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                // En mode de débogage, afficher l'erreur. Sinon, un message générique.
                if (env('APP_DEBUG', false)) {
                    die("Erreur de connexion à la base de données : " . $e->getMessage());
                } else {
                    // En production, on loggerait cette erreur dans un fichier.
                    error_log($e->getMessage());
                    die("Une erreur de serveur est survenue. Veuillez réessayer plus tard.");
                }
            }
        }

        // On retourne l'instance de connexion existante.
        return self::$instance;
    }

    // Le constructeur privé empêche la création d'instances directes.
    private function __construct() {}

    // Empêche le clonage de l'instance.
    private function __clone() {}

    // Empêche la désérialisation de l'instance.
    public function __wakeup() {}
}
