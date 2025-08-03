<?php

// ===================================================================
// FICHIER : src/Controllers/DashboardController.php
// Contrôleur pour gérer le tableau de bord
// ===================================================================
namespace App\Controllers;

use App\Shared\BaseController;
use PDO;

class DashboardController extends BaseController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Affiche le tableau de bord.
     * @return void
     */
    public function index(): void
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Fetch user data from database
        $userData = $this->getUserData($_SESSION['user_id']);
        
        // Fetch user statistics
        $userStats = $this->getUserStats($_SESSION['user_id']);

        $this->render('dashboard', [
            'page_title' => 'Tableau de Bord - Askiaverse',
            'user' => $userData,
            'stats' => $userStats
        ]);
    }

    /**
     * Get user data from database
     * @param int $userId
     * @return array
     */
    private function getUserData(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, username, level, xp, orbs, created_at 
            FROM users 
            WHERE id = ? AND deleted_at IS NULL
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            return [
                'id' => 0,
                'username' => 'Unknown',
                'level' => 1,
                'xp' => 0,
                'orbs' => 0,
                'focus_tokens' => 3,
                'created_at' => date('Y-m-d')
            ];
        }

        // Calculate XP percentage for current level
        $xpForCurrentLevel = ($user['level'] - 1) * 100;
        $xpForNextLevel = $user['level'] * 100;
        $xpProgress = $user['xp'] - $xpForCurrentLevel;
        $xpNeeded = $xpForNextLevel - $xpForCurrentLevel;
        $xpPercentage = $xpNeeded > 0 ? ($xpProgress / $xpNeeded) * 100 : 100;

        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'level' => $user['level'] ?? 1,
            'xp' => $user['xp'] ?? 0,
            'orbs' => $user['orbs'] ?? 0,
            'focus_tokens' => 3, // Default value since column doesn't exist
            'created_at' => $user['created_at'],
            'xp_percentage' => $xpPercentage
        ];
    }

    /**
     * Get user statistics from database
     * @param int $userId
     * @return array
     */
    private function getUserStats(int $userId): array
    {
        // Get quiz attempts statistics using only existing columns
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(*) as total_quizzes,
                AVG(score) as avg_score,
                MAX(score) as best_score,
                SUM(score) as total_score
            FROM quiz_attempts 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $quizStats = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Get recent activity
        $stmt = $this->pdo->prepare("
            SELECT started_at, score, quiz_id
            FROM quiz_attempts 
            WHERE user_id = ? 
            ORDER BY started_at DESC 
            LIMIT 5
        ");
        $stmt->execute([$userId]);
        $recentActivity = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Calculate accuracy based on score (assuming max score is 100)
        $totalQuizzes = $quizStats['total_quizzes'] ?? 0;
        $totalScore = $quizStats['total_score'] ?? 0;
        $accuracy = $totalQuizzes > 0 ? round(($totalScore / ($totalQuizzes * 100)) * 100, 1) : 0;

        return [
            'total_quizzes' => $totalQuizzes,
            'avg_score' => round($quizStats['avg_score'] ?? 0, 1),
            'best_score' => $quizStats['best_score'] ?? 0,
            'total_correct' => 0, // Not available in current schema
            'total_questions' => 0, // Not available in current schema
            'accuracy' => $accuracy,
            'recent_activity' => $recentActivity
        ];
    }
} 