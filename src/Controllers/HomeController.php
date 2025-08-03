<?php

// ===================================================================
// FICHIER : src/Controllers/HomeController.php
// Contrôleur pour gérer la page d'accueil
// ===================================================================
namespace App\Controllers;

use App\Shared\BaseController;
use PDO;

class HomeController extends BaseController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Affiche la page d'accueil.
     * @return void
     */
    public function index(): void
    {
        $this->render('index', [
            'page_title' => 'Askiaverse - Apprendre en s\'amusant'
        ]);
    }
} 