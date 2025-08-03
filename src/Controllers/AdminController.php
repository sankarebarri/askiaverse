<?php
namespace App\Controllers;

use App\Shared\BaseController;
use PDO;

class AdminController extends BaseController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Admin dashboard
     */
    public function dashboard(): void {
        // Check admin authentication (simplified for now)
        if (!isset($_SESSION['admin_user_id'])) {
            header('Location: /admin/login');
            exit;
        }
        
        $this->renderWithLayout('admin/dashboard_content', [
            'page_title' => 'Tableau de Bord Admin - Askiaverse',
            'current_page' => 'dashboard'
        ]);
    }

    /**
     * Admin login page
     */
    public function login(): void {
        if (isset($_SESSION['admin_user_id'])) {
            header('Location: /admin');
            exit;
        }
        
        $this->render('admin/login', [
            'page_title' => 'Connexion Admin - Askiaverse'
        ]);
    }

    /**
     * Admin login process
     */
    public function processLogin(): void {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $this->render('admin/login', [
                'page_title' => 'Connexion Admin - Askiaverse',
                'error' => 'Veuillez remplir tous les champs.'
            ]);
            return;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, password_hash, role, is_active 
                FROM admin_users 
                WHERE username = ? AND deleted_at IS NULL
            ");
            $stmt->execute([$username]);
            $admin = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$admin || !$admin['is_active']) {
                $this->render('admin/login', [
                    'page_title' => 'Connexion Admin - Askiaverse',
                    'error' => 'Nom d\'utilisateur ou mot de passe incorrect.'
                ]);
                return;
            }
            
            if (password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_user_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_role'] = $admin['role'];
                
                // Update last login
                $stmt = $this->pdo->prepare("UPDATE admin_users SET last_login_at = NOW() WHERE id = ?");
                $stmt->execute([$admin['id']]);
                
                header('Location: /admin');
                exit;
            } else {
                $this->render('admin/login', [
                    'page_title' => 'Connexion Admin - Askiaverse',
                    'error' => 'Nom d\'utilisateur ou mot de passe incorrect.'
                ]);
            }
            
        } catch (\Exception $e) {
            $this->render('admin/login', [
                'page_title' => 'Connexion Admin - Askiaverse',
                'error' => 'Une erreur est survenue lors de la connexion.'
            ]);
        }
    }

    /**
     * Admin logout
     */
    public function logout(): void {
        unset($_SESSION['admin_user_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_role']);
        
        header('Location: /admin/login');
        exit;
    }

    /**
     * Questions management page
     */
    public function questions(): void {
        if (!isset($_SESSION['admin_user_id'])) {
            header('Location: /admin/login');
            exit;
        }
        
        // Get all questions with their options
        $stmt = $this->pdo->query("
            SELECT 
                q.id,
                q.text,
                q.difficulty_level,
                q.question_type,
                q.display_order,
                s.name as subject_name,
                t.name as topic_name,
                qu.title as quiz_title,
                COUNT(o.id) as option_count
            FROM questions q
            JOIN quizzes qu ON q.quiz_id = qu.id
            JOIN topics t ON qu.topic_id = t.id
            JOIN subjects s ON t.subject_id = s.id
            LEFT JOIN options o ON q.id = o.question_id AND o.deleted_at IS NULL
            WHERE q.deleted_at IS NULL
            GROUP BY q.id
            ORDER BY s.name, t.name, q.display_order
        ");
        $questions = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $this->renderWithLayout('admin/questions_content', [
            'page_title' => 'Gérer les Questions - Askiaverse',
            'current_page' => 'questions',
            'questions' => $questions,
            'extra_js' => ['/assets/js/admin/questions.js']
        ]);
    }

    /**
     * Add new question form
     */
    public function addQuestion(): void {
        if (!isset($_SESSION['admin_user_id'])) {
            header('Location: /admin/login');
            exit;
        }
        
        // Get subjects and topics for dropdowns
        $stmt = $this->pdo->query("
            SELECT s.id as subject_id, s.name as subject_name, t.id as topic_id, t.name as topic_name
            FROM subjects s
            JOIN topics t ON s.id = t.subject_id
            WHERE s.deleted_at IS NULL AND t.deleted_at IS NULL
            ORDER BY s.name, t.name
        ");
        $topics = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $this->renderWithLayout('admin/add_question_content', [
            'page_title' => 'Ajouter une Question - Askiaverse',
            'current_page' => 'questions',
            'topics' => $topics,
            'extra_js' => ['/assets/js/admin/questions.js']
        ]);
    }

    /**
     * API endpoint to create a new question
     */
    public function createQuestion(): void {
        if (!isset($_SESSION['admin_user_id'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['topic_id']) || !isset($input['question_text']) || !isset($input['options'])) {
            $this->jsonResponse(['error' => 'Missing required data'], 400);
            return;
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Get or create quiz for this topic
            $stmt = $this->pdo->prepare("SELECT id FROM quizzes WHERE topic_id = ? AND deleted_at IS NULL LIMIT 1");
            $stmt->execute([$input['topic_id']]);
            $quiz = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$quiz) {
                // Create a default quiz for this topic
                $stmt = $this->pdo->prepare("
                    INSERT INTO quizzes (topic_id, title, description) 
                    VALUES (?, 'Quiz par défaut', 'Quiz automatiquement créé')
                ");
                $stmt->execute([$input['topic_id']]);
                $quizId = $this->pdo->lastInsertId();
            } else {
                $quizId = $quiz['id'];
            }
            
            // Insert question
            $stmt = $this->pdo->prepare("
                INSERT INTO questions (quiz_id, text, difficulty_level, question_type, display_order)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $quizId,
                $input['question_text'],
                $input['difficulty_level'] ?? 'medium',
                $input['question_type'] ?? 'regular',
                $input['display_order'] ?? 0
            ]);
            
            $questionId = $this->pdo->lastInsertId();
            
            // Insert options
            $stmt = $this->pdo->prepare("
                INSERT INTO options (question_id, text, is_correct, display_order)
                VALUES (?, ?, ?, ?)
            ");
            
            foreach ($input['options'] as $index => $option) {
                $stmt->execute([
                    $questionId,
                    $option['text'],
                    $option['is_correct'] ? 1 : 0,
                    $index
                ]);
            }
            
            $this->pdo->commit();
            
            $this->jsonResponse([
                'message' => 'Question créée avec succès',
                'question_id' => $questionId
            ]);
            
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            $this->jsonResponse(['error' => 'Échec de la création de la question: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API endpoint to get quizzes for a topic
     */
    public function getQuizzes(): void {
        $topicId = $_GET['topic_id'] ?? null;
        
        if (!$topicId) {
            $this->jsonResponse(['error' => 'Topic ID required'], 400);
            return;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, title, description 
                FROM quizzes 
                WHERE topic_id = ? AND deleted_at IS NULL
                ORDER BY title
            ");
            $stmt->execute([$topicId]);
            $quizzes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            $this->jsonResponse(['quizzes' => $quizzes]);
            
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'Échec du chargement des quiz: ' . $e->getMessage()], 500);
        }
    }
} 