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
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Get user stats for the header
        $userStats = $this->getUserStats();

        $this->renderPublicWithLayout('community_content', [
            'page_title' => 'Communauté - Askiaverse',
            'current_page' => 'community'
        ], $userStats);
    }

    /**
     * Get user statistics for the header
     * @return array
     */
    private function getUserStats(): array
    {
        if (!isset($_SESSION['user_id'])) {
            return [
                'level' => 1,
                'orbs' => 0,
                'focus_tokens' => 0,
                'xp_percentage' => 0
            ];
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    level,
                    orbs,
                    focus_tokens,
                    experience_points,
                    (experience_points % 100) as xp_percentage
                FROM users 
                WHERE id = ? AND deleted_at IS NULL
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                return [
                    'level' => $user['level'] ?? 1,
                    'orbs' => $user['orbs'] ?? 0,
                    'focus_tokens' => $user['focus_tokens'] ?? 0,
                    'xp_percentage' => $user['xp_percentage'] ?? 0
                ];
            }
        } catch (\Exception $e) {
            // Log error in production
        }

        return [
            'level' => 1,
            'orbs' => 0,
            'focus_tokens' => 0,
            'xp_percentage' => 0
        ];
    }
} 