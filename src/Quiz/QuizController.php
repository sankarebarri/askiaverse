<?php

// ===================================================================
// FICHIER : src/Controllers/QuizController.php
// NOTE : Le namespace a été ajouté pour correspondre à la structure des dossiers.
// ===================================================================
namespace App\Controllers;

use App\Shared\BaseController;
use PDO;

class QuizController extends BaseController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Affiche la liste de tous les quiz disponibles.
     *
     * @return void
     */
    public function index(): void
    {
        // 1. Prépare et exécute la requête pour récupérer tous les quiz.
        $stmt = $this->pdo->query("
            SELECT 
                q.title, 
                q.description, 
                t.name AS topic_name
            FROM quizzes AS q
            JOIN topics AS t ON q.topic_id = t.id
            WHERE q.deleted_at IS NULL
        ");
        
        // 2. Récupère tous les résultats.
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Inclut le fichier de la vue et lui passe les données.
        require_once __DIR__ . '/../../resources/views/quiz.php';
    }
}
