<?php

// ===================================================================
// FICHIER : src/Controllers/CommunityController.php
// Contrôleur pour gérer la page de communauté
// ===================================================================
namespace App\Controllers;

use App\Shared\BaseController;
use PDO;

class CommunityController extends BaseController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Affiche la page de communauté.
     * @return void
     */
    public function index(): void
    {
        $this->render('community', [
            'page_title' => 'Communauté - Askiaverse'
        ]);
    }
} 