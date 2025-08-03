<?php
// public/api/quiz.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load database connection
$pdo = require __DIR__ . '/../../src/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            handleGet($pdo);
            break;
        case 'POST':
            handlePost($pdo);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
}

function handleGet($pdo) {
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'subjects':
            getSubjects($pdo);
            break;
        case 'topics':
            $subjectId = $_GET['subject_id'] ?? null;
            if (!$subjectId) {
                http_response_code(400);
                echo json_encode(['error' => 'ID du sujet requis']);
                return;
            }
            getTopics($pdo, $subjectId);
            break;
        case 'questions':
            $subjectId = $_GET['subject_id'] ?? null;
            $topicId = $_GET['topic_id'] ?? null;
            if (!$subjectId || !$topicId) {
                http_response_code(400);
                echo json_encode(['error' => 'ID du sujet et du thème requis']);
                return;
            }
            getQuestions($pdo, $subjectId, $topicId);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Action non reconnue']);
    }
}

function handlePost($pdo) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Données JSON invalides']);
        return;
    }

    $action = $input['action'] ?? '';
    
    switch ($action) {
        case 'submit_quiz':
            submitQuiz($pdo, $input);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Action non reconnue']);
    }
}

function getSubjects($pdo) {
    $stmt = $pdo->prepare("
        SELECT id, name, description 
        FROM subjects 
        WHERE deleted_at IS NULL 
        ORDER BY name
    ");
    $stmt->execute();
    $subjects = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'subjects' => $subjects
    ]);
}

function getTopics($pdo, $subjectId) {
    $stmt = $pdo->prepare("
        SELECT id, name, display_order 
        FROM topics 
        WHERE subject_id = ? AND deleted_at IS NULL 
        ORDER BY display_order, name
    ");
    $stmt->execute([$subjectId]);
    $topics = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'topics' => $topics
    ]);
}

function getQuestions($pdo, $subjectId, $topicId) {
    $stmt = $pdo->prepare("
        SELECT 
            id,
            question_text,
            option_a,
            option_b,
            option_c,
            option_d,
            difficulty,
            points
        FROM questions 
        WHERE subject_id = ? AND topic_id = ? AND deleted_at IS NULL 
        ORDER BY RAND()
        LIMIT 10
    ");
    $stmt->execute([$subjectId, $topicId]);
    $questions = $stmt->fetchAll();
    
    // Remove correct answer from response for security
    foreach ($questions as &$question) {
        unset($question['correct_answer']);
    }
    
    echo json_encode([
        'success' => true,
        'questions' => $questions
    ]);
}

function submitQuiz($pdo, $input) {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Utilisateur non connecté']);
        return;
    }

    $userId = $_SESSION['user_id'];
    $subjectId = $input['subject_id'] ?? null;
    $topicId = $input['topic_id'] ?? null;
    $answers = $input['answers'] ?? [];
    $timeSpent = $input['time_spent'] ?? 0;
    $score = $input['score'] ?? 0;

    if (!$subjectId || !$topicId) {
        http_response_code(400);
        echo json_encode(['error' => 'Données de quiz incomplètes']);
        return;
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Create quiz attempt
        $stmt = $pdo->prepare("
            INSERT INTO quiz_attempts (
                user_id, subject_id, topic_id, score, time_spent, completed_at
            ) VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$userId, $subjectId, $topicId, $score, $timeSpent]);
        $attemptId = $pdo->lastInsertId();

        // Save individual answers
        foreach ($answers as $answer) {
            $stmt = $pdo->prepare("
                INSERT INTO attempt_answers (
                    attempt_id, question_id, selected_answer, is_correct, time_spent
                ) VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $attemptId,
                $answer['question_id'],
                $answer['selected_answer'],
                $answer['is_correct'] ? 1 : 0,
                $answer['time_spent'] ?? 0
            ]);
        }

        // Update user XP and orbs
        $xpGained = calculateXPGain($score);
        $orbsGained = calculateOrbsGain($score);
        
        $stmt = $pdo->prepare("
            UPDATE users 
            SET xp = xp + ?, orbs = orbs + ?, level = FLOOR((xp + ?) / 100) + 1
            WHERE id = ?
        ");
        $stmt->execute([$xpGained, $orbsGained, $xpGained, $userId]);

        // Log XP gain
        $stmt = $pdo->prepare("
            INSERT INTO xp_log (user_id, amount, source, description)
            VALUES (?, ?, 'quiz', ?)
        ");
        $stmt->execute([$userId, $xpGained, "Quiz terminé avec un score de {$score}%"]);

        // Log orbs gain
        $stmt = $pdo->prepare("
            INSERT INTO orb_log (user_id, amount, source, description)
            VALUES (?, ?, 'quiz', ?)
        ");
        $stmt->execute([$userId, $orbsGained, "Quiz terminé avec un score de {$score}%"]);

        $pdo->commit();

        // Get updated user stats
        $stmt = $pdo->prepare("
            SELECT xp, orbs, level 
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        $userStats = $stmt->fetch();

        echo json_encode([
            'success' => true,
            'message' => 'Quiz soumis avec succès',
            'attempt_id' => $attemptId,
            'xp_gained' => $xpGained,
            'orbs_gained' => $orbsGained,
            'user_stats' => $userStats
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function calculateXPGain($score) {
    // Base XP: 10 points for completing
    // Bonus XP: up to 20 points based on score
    $baseXP = 10;
    $bonusXP = round(($score / 100) * 20);
    return $baseXP + $bonusXP;
}

function calculateOrbsGain($score) {
    // Base orbs: 5 for completing
    // Bonus orbs: up to 15 based on score
    $baseOrbs = 5;
    $bonusOrbs = round(($score / 100) * 15);
    return $baseOrbs + $bonusOrbs;
} 