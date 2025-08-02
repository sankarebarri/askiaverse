<?php
// public/api/auth.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit();
}

// Load database connection
$pdo = require __DIR__ . '/../../src/bootstrap.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Données JSON invalides']);
    exit();
}

$action = $input['action'] ?? '';

try {
    switch ($action) {
        case 'login':
            handleLogin($pdo, $input);
            break;
        case 'register':
            handleRegister($pdo, $input);
            break;
        case 'logout':
            handleLogout();
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Action non reconnue']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
}

function handleLogin($pdo, $input) {
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Nom d\'utilisateur et mot de passe requis']);
        return;
    }

    // Get user from database
    $stmt = $pdo->prepare("
        SELECT id, username, password_hash, display_name, xp, orbs, level, school, city, grade
        FROM users 
        WHERE username = ? AND deleted_at IS NULL
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Nom d\'utilisateur ou mot de passe incorrect']);
        return;
    }

    // Start session and store user data
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['display_name'] = $user['display_name'];

    // Return user data (without password)
    unset($user['password_hash']);
    echo json_encode([
        'success' => true,
        'message' => 'Connexion réussie',
        'user' => $user
    ]);
}

function handleRegister($pdo, $input) {
    $username = $input['username'] ?? '';
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';
    $displayName = $input['display_name'] ?? '';
    $school = $input['school'] ?? '';
    $city = $input['city'] ?? '';
    $grade = $input['grade'] ?? '';

    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Nom d\'utilisateur et mot de passe requis']);
        return;
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND deleted_at IS NULL");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Ce nom d\'utilisateur existe déjà']);
        return;
    }

    // Create new user
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password_hash, display_name, school, city, grade, country_code)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'ML')
    ");
    
    $stmt->execute([$username, $email, $passwordHash, $displayName, $school, $city, $grade]);
    $userId = $pdo->lastInsertId();

    // Start session
    session_start();
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['display_name'] = $displayName;

    echo json_encode([
        'success' => true,
        'message' => 'Compte créé avec succès',
        'user_id' => $userId
    ]);
}

function handleLogout() {
    session_start();
    session_destroy();
    echo json_encode([
        'success' => true,
        'message' => 'Déconnexion réussie'
    ]);
} 