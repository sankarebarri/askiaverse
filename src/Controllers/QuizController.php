<?php
namespace Controllers;

use Models\Subject;
use Models\Quiz;
use Shared\BaseController;

class QuizController extends BaseController {
    private $subjectModel;
    private $quizModel;

    public function __construct() {
        parent::__construct();
        $this->subjectModel = new Subject();
        $this->quizModel = new Quiz();
    }

    public function index() {
        $this->requireAuth();
        
        $subjects = $this->subjectModel->getAll();
        
        $this->render('quiz/index', [
            'subjects' => $subjects
        ]);
    }

    public function getThemes() {
        $this->requireAuth();
        
        $subjectId = $_GET['subject_id'] ?? null;
        
        if (!$subjectId) {
            $this->jsonResponse(['error' => 'Subject ID required'], 400);
            return;
        }

        try {
            $themes = $this->subjectModel->getThemes($subjectId);
            $this->jsonResponse(['themes' => $themes]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getQuestions() {
        $this->requireAuth();
        
        $subjectId = $_GET['subject_id'] ?? null;
        $themeId = $_GET['theme_id'] ?? null;
        $limit = $_GET['limit'] ?? 10;

        if (!$subjectId) {
            $this->jsonResponse(['error' => 'Subject ID required'], 400);
            return;
        }

        try {
            $questions = $this->subjectModel->getRandomQuestions($subjectId, $themeId, $limit);
            
            // Format questions for frontend
            $formattedQuestions = array_map(function($question) {
                return [
                    'id' => $question['id'],
                    'question' => $question['question'],
                    'options' => json_decode($question['options'], true),
                    'correct' => $question['correct_answer'],
                    'explanation' => $question['explanation'] ?? ''
                ];
            }, $questions);

            $this->jsonResponse(['questions' => $formattedQuestions]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function submitResult() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $requiredFields = ['subject_id', 'theme_id', 'score', 'total_questions', 'correct_answers', 'time_spent'];
        
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                $this->jsonResponse(['error' => "Missing required field: {$field}"], 400);
                return;
            }
        }

        try {
            $attemptData = [
                'user_id' => $_SESSION['user_id'],
                'subject_id' => $input['subject_id'],
                'theme_id' => $input['theme_id'],
                'score' => $input['score'],
                'total_questions' => $input['total_questions'],
                'correct_answers' => $input['correct_answers'],
                'time_spent' => $input['time_spent'],
                'mode' => $input['mode'] ?? 'quick_quiz'
            ];

            $attemptId = $this->quizModel->saveAttempt($attemptData);
            
            $this->jsonResponse([
                'success' => true,
                'attempt_id' => $attemptId,
                'message' => 'Résultat sauvegardé avec succès'
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserAttempts() {
        $this->requireAuth();
        
        $limit = $_GET['limit'] ?? 20;
        
        try {
            $attempts = $this->quizModel->getUserAttempts($_SESSION['user_id'], $limit);
            $this->jsonResponse(['attempts' => $attempts]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserStats() {
        $this->requireAuth();
        
        try {
            $stats = $this->quizModel->getUserStats($_SESSION['user_id']);
            $this->jsonResponse(['stats' => $stats]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getLeaderboard() {
        $this->requireAuth();
        
        $limit = $_GET['limit'] ?? 10;
        $school = $_GET['school'] ?? null;
        
        try {
            if ($school) {
                $leaderboard = $this->quizModel->getSchoolLeaderboard($school, $limit);
            } else {
                $leaderboard = $this->quizModel->getLeaderboard($limit);
            }
            
            $this->jsonResponse(['leaderboard' => $leaderboard]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function getDailyStats() {
        $this->requireAuth();
        
        $days = $_GET['days'] ?? 7;
        
        try {
            $stats = $this->quizModel->getDailyStats($_SESSION['user_id'], $days);
            $this->jsonResponse(['stats' => $stats]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    private function requireAuth() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['error' => 'Authentication required'], 401);
            exit;
        }
    }

    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
} 