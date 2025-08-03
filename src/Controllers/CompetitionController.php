<?php

// ===================================================================
// FICHIER : src/Controllers/CompetitionController.php
// Contrôleur pour gérer la page de compétition
// ===================================================================
namespace App\Controllers;

use App\Shared\BaseController;
use PDO;

class CompetitionController extends BaseController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Affiche la page de compétition.
     * @return void
     */
    public function index(): void
    {
        $this->render('competition', [
            'page_title' => 'Compétition - Askiaverse'
        ]);
    }
} 