<?php
namespace App\Controllers;

use App\Shared\BaseController;
use PDO;

class QuizController extends BaseController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Affiche la page de quiz avec paramètres
     */
    public function showQuiz(): void {
        $subject = $_GET['subject'] ?? '';
        $theme = $_GET['theme'] ?? '';
        $mode = $_GET['mode'] ?? '';
        
        $this->render('quiz', [
            'page_title' => 'Quiz - Askiaverse',
            'subject' => $subject,
            'theme' => $theme,
            'mode' => $mode
        ]);
    }

    /**
     * API endpoint to get quiz questions
     */
    public function getQuestions(): void {
        $subject = $_GET['subject'] ?? '';
        $theme = $_GET['theme'] ?? '';
        
        try {
            // Find the subject (handle both English and French names)
            $subjectMappings = [
                'mathematics' => 'Mathématiques',
                'french' => 'Français',
                'history-geo' => 'Histoire-Géo'
            ];
            
            $subjectName = $subjectMappings[$subject] ?? $subject;
            
            $stmt = $this->pdo->prepare("SELECT id FROM subjects WHERE name = ? AND deleted_at IS NULL");
            $stmt->execute([$subjectName]);
            $subjectData = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$subjectData) {
                $this->jsonResponse(['error' => 'Subject not found'], 404);
                return;
            }
            
            // Find the topic/theme (handle both English and French names)
            $themeMappings = [
                'arithmetic' => 'Calcul',
                'geometry' => 'Géométrie',
                'algebra' => 'Algèbre'
            ];
            
            $themeName = $themeMappings[$theme] ?? $theme;
            
            $stmt = $this->pdo->prepare("SELECT id FROM topics WHERE subject_id = ? AND name = ? AND deleted_at IS NULL");
            $stmt->execute([$subjectData['id'], $themeName]);
            $topicData = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$topicData) {
                $this->jsonResponse(['error' => 'Topic not found'], 404);
                return;
            }
            
            // Get quiz
            $stmt = $this->pdo->prepare("SELECT id FROM quizzes WHERE topic_id = ? AND deleted_at IS NULL LIMIT 1");
            $stmt->execute([$topicData['id']]);
            $quizData = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$quizData) {
                $this->jsonResponse(['error' => 'Quiz not found'], 404);
                return;
            }
            
            // Get questions with JSON options
            $stmt = $this->pdo->prepare("
                SELECT 
                    q.id as question_id,
                    q.text as question_text,
                    q.question_type,
                    q.jackpot_value,
                    q.options,
                    q.correct_answer_index
                FROM questions q
                WHERE q.quiz_id = ? AND q.deleted_at IS NULL
                ORDER BY q.display_order
            ");
            $stmt->execute([$quizData['id']]);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Format questions
            $questions = [];
            
            foreach ($results as $row) {
                $options = json_decode($row['options'], true);
                
                // Add option IDs for compatibility
                foreach ($options as $index => &$option) {
                    $option['id'] = $row['question_id'] . '_' . $index;
                }
                
                $questions[] = [
                    'id' => $row['question_id'],
                    'text' => $row['question_text'],
                    'type' => $row['question_type'],
                    'jackpot_value' => $row['jackpot_value'],
                    'options' => $options,
                    'correct_answer_index' => $row['correct_answer_index']
                ];
            }
            
            $this->jsonResponse([
                'questions' => $questions,
                'total_questions' => count($questions),
                'quiz_id' => $quizData['id']
            ]);
            
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'Failed to load questions: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API endpoint to submit quiz results
     */
    public function submitResult(): void {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['error' => 'User not authenticated'], 401);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['quiz_id']) || !isset($input['score']) || !isset($input['total_questions'])) {
            $this->jsonResponse(['error' => 'Missing required data'], 400);
            return;
        }
        
        try {
            // Insert quiz attempt
            $stmt = $this->pdo->prepare("
                INSERT INTO quiz_attempts (user_id, quiz_id, score, total_questions, started_at, completed_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->execute([
                $_SESSION['user_id'],
                $input['quiz_id'],
                $input['score'],
                $input['total_questions']
            ]);
            
            $attemptId = $this->pdo->lastInsertId();
            
            // Calculate XP and orbs earned
            $xpEarned = $input['score'] * 10; // 10 XP per correct answer
            $orbsEarned = $input['score'] >= ($input['total_questions'] * 0.7) ? 50 : 0; // 50 orbs if 70% or higher
            
            // Update user stats
            if ($xpEarned > 0 || $orbsEarned > 0) {
                $stmt = $this->pdo->prepare("
                    UPDATE users 
                    SET xp = xp + ?, orbs = orbs + ?
                    WHERE id = ?
                ");
                $stmt->execute([$xpEarned, $orbsEarned, $_SESSION['user_id']]);
            }
            
            $this->jsonResponse([
                'message' => 'Quiz completed successfully',
                'attempt_id' => $attemptId,
                'xp_earned' => $xpEarned,
                'orbs_earned' => $orbsEarned,
                'new_total_xp' => $xpEarned,
                'new_total_orbs' => $orbsEarned
            ]);
            
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'Failed to save results: ' . $e->getMessage()], 500);
        }
    }
} 