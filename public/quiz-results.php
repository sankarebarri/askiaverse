<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: http://localhost:8000/login.php');
    exit;
}

$error = '';
$success = '';
$attemptId = null;

// Handle quiz submission
if ($_POST) {
    $themeId = $_POST['theme_id'] ?? null;
    $totalQuestions = $_POST['total_questions'] ?? 0;
    
    if (!$themeId || $totalQuestions == 0) {
        $error = 'Données de quiz invalides.';
    } else {
        try {
            // Database connection
            $host = '127.0.0.1';
            $port = 3306;
            $dbname = 'askiaverse';
            $username_db = 'root';
            $password_db = '';
            
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, $username_db, $password_db, $options);
            
            // Get theme and subject info
            $sql = "SELECT t.*, s.name as subject_name, s.id as subject_id 
                    FROM themes t 
                    JOIN subjects s ON t.subject_id = s.id 
                    WHERE t.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$themeId]);
            $theme = $stmt->fetch();
            
            if (!$theme) {
                throw new Exception('Thème non trouvé.');
            }
            
            // Calculate score
            $correctAnswers = 0;
            $userAnswers = [];
            
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'answer_') === 0) {
                    $questionId = substr($key, 7);
                    $userAnswer = (int)$value;
                    
                    // Get correct answer for this question
                    $sql = "SELECT correct_answer FROM questions WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$questionId]);
                    $question = $stmt->fetch();
                    
                    if ($question && $userAnswer === $question['correct_answer']) {
                        $correctAnswers++;
                    }
                    
                    $userAnswers[$questionId] = $userAnswer;
                }
            }
            
            $score = round(($correctAnswers / $totalQuestions) * 100, 2);
            
            // Insert quiz attempt
            $sql = "INSERT INTO quiz_attempts (user_id, subject_id, theme_id, score, total_questions, correct_answers, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_SESSION['user_id'],
                $theme['subject_id'],
                $themeId,
                $score,
                $totalQuestions,
                $correctAnswers
            ]);
            
            $attemptId = $pdo->lastInsertId();
            
            // Calculate XP and orbs
            $xpGained = $correctAnswers * 10; // 10 XP per correct answer
            
            // Get current user stats with full orbs support
            $sql = "SELECT 
                        COALESCE(SUM(up.experience), 0) as total_experience,
                        COALESCE(SUM(up.orbs), 0) as total_orbs
                    FROM user_progress up 
                    WHERE up.user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_SESSION['user_id']]);
            $currentStats = $stmt->fetch();
            
            $previousXP = $currentStats['total_experience'];
            $previousOrbs = $currentStats['total_orbs'];
            
            // Calculate new total XP and orbs
            $newTotalXP = $previousXP + $xpGained;
            $newTotalOrbs = $previousOrbs + floor($xpGained / 1000); // 1000 XP = 1 orb
            
            // First, check if a record already exists for this user-subject-theme combination
            $checkSql = "SELECT id, experience, orbs FROM user_progress WHERE user_id = ? AND subject_id = ? AND theme_id = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$_SESSION['user_id'], $theme['subject_id'], $themeId]);
            $existingRecord = $checkStmt->fetch();
            
            if ($existingRecord) {
                // Update existing record
                $newExperience = $existingRecord['experience'] + $xpGained;
                $newOrbs = $existingRecord['orbs'] + floor($xpGained / 1000);
                
                $updateSql = "UPDATE user_progress SET 
                                experience = ?, 
                                orbs = ?, 
                                completed_lessons = completed_lessons + 1, 
                                last_activity = NOW() 
                              WHERE id = ?";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([$newExperience, $newOrbs, $existingRecord['id']]);
                
                error_log("Updated existing record: user_id={$_SESSION['user_id']}, theme_id={$themeId}, old_xp={$existingRecord['experience']}, new_xp={$newExperience}");
            } else {
                // Insert new record
                $insertSql = "INSERT INTO user_progress (user_id, subject_id, theme_id, level, experience, orbs, completed_lessons, last_activity) 
                             VALUES (?, ?, ?, 1, ?, ?, 1, NOW())";
                $insertStmt = $pdo->prepare($insertSql);
                $insertStmt->execute([
                    $_SESSION['user_id'],
                    $theme['subject_id'],
                    $themeId,
                    $xpGained,
                    floor($xpGained / 1000)
                ]);
                
                error_log("Inserted new record: user_id={$_SESSION['user_id']}, theme_id={$themeId}, xp={$xpGained}");
            }
            
            // Store the XP and orbs gained for display
            $orbsGained = floor($xpGained / 1000);
            
            $success = 'Quiz terminé avec succès !';
            
            // Return JSON response for AJAX requests
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'attempt_id' => $attemptId,
                    'message' => $success,
                    'xp_gained' => $xpGained,
                    'orbs_gained' => $orbsGained,
                    'previous_xp' => $previousXP,
                    'previous_orbs' => $previousOrbs,
                    'new_total_xp' => $newTotalXP,
                    'new_total_orbs' => $newTotalOrbs
                ]);
                exit;
            }
            
        } catch (Exception $e) {
            $error = 'Erreur lors du traitement du quiz: ' . $e->getMessage();
            
            // Return JSON response for AJAX requests
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $error
                ]);
                exit;
            }
        }
    }
}

// If we have an attempt ID, get the results
if (isset($_GET['attempt_id']) && !$attemptId) {
    $attemptId = $_GET['attempt_id'];
}

$quizResults = null;
if ($attemptId) {
    try {
        // Database connection
        $host = '127.0.0.1';
        $port = 3306;
        $dbname = 'askiaverse';
        $username_db = 'root';
        $password_db = '';
        
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $username_db, $password_db, $options);
        
        // Get quiz attempt details
        $sql = "SELECT qa.*, t.name as theme_name, s.name as subject_name, s.color as subject_color 
                FROM quiz_attempts qa 
                JOIN themes t ON qa.theme_id = t.id 
                JOIN subjects s ON qa.subject_id = s.id 
                WHERE qa.id = ? AND qa.user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$attemptId, $_SESSION['user_id']]);
        $quizResults = $stmt->fetch();
        
    } catch (Exception $e) {
        $error = 'Erreur lors de la récupération des résultats: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats du Quiz - Askiaverse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="http://localhost:8000/user-dashboard.php" class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">Askiaverse</a>
                    <?php else: ?>
                        <a href="http://localhost:8000/" class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">Askiaverse</a>
                    <?php endif; ?>
                </div>
                <nav class="flex space-x-4">
                    <a href="http://localhost:8000/simple-subjects.php" class="text-gray-600 hover:text-gray-900">Matières</a>
                    <a href="http://localhost:8000/user-dashboard.php" class="text-gray-600 hover:text-gray-900">Tableau de Bord</a>
                    <span class="text-gray-600">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                    <a href="http://localhost:8000/logout.php" class="text-red-600 hover:text-red-700">Déconnexion</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Erreur:</strong> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <strong>Succès:</strong> <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <?php if ($quizResults): ?>
        
        <!-- Results Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full mb-6" 
                     style="background: linear-gradient(135deg, <?= $quizResults['subject_color'] ?>20, <?= $quizResults['subject_color'] ?>10);">
                    <?php if ($quizResults['score'] >= 80): ?>
                        <span class="text-4xl">🏆</span>
                    <?php elseif ($quizResults['score'] >= 60): ?>
                        <span class="text-4xl">🎯</span>
                    <?php else: ?>
                        <span class="text-4xl">📚</span>
                    <?php endif; ?>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Quiz Terminé !</h1>
                <p class="text-xl text-gray-600 mb-4"><?= htmlspecialchars($quizResults['theme_name']) ?></p>
                <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                    <span>📚 <?= htmlspecialchars($quizResults['subject_name']) ?></span>
                    <span>🎯 <?= $quizResults['total_questions'] ?> questions</span>
                    <span>📅 <?= date('d/m/Y H:i', strtotime($quizResults['created_at'])) ?></span>
                </div>
            </div>
        </div>

        <!-- Score Display -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="text-center">
                <div class="text-6xl font-bold mb-4 
                    <?php if ($quizResults['score'] >= 80): ?>text-green-600
                    <?php elseif ($quizResults['score'] >= 60): ?>text-yellow-600
                    <?php else: ?>text-red-600<?php endif; ?>">
                    <?= $quizResults['score'] ?>%
                </div>
                <div class="text-2xl text-gray-700 mb-4">
                    <?= $quizResults['correct_answers'] ?> bonnes réponses sur <?= $quizResults['total_questions'] ?>
                </div>
                
                <!-- Performance Message -->
                <div class="text-lg text-gray-600 mb-6">
                    <?php if ($quizResults['score'] >= 80): ?>
                        🎉 Excellent travail ! Vous maîtrisez parfaitement ce thème !
                    <?php elseif ($quizResults['score'] >= 60): ?>
                        👍 Bon travail ! Continuez à vous améliorer !
                    <?php else: ?>
                        📚 Pas de panique ! C'est en forgeant qu'on devient forgeron !
                    <?php endif; ?>
                </div>
                
                <!-- XP and Orbs Gained -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                        <div class="text-2xl text-blue-800 font-bold">
                            +<?= $quizResults['correct_answers'] * 10 ?> XP
                        </div>
                        <div class="text-blue-600 text-sm">Expérience gagnée</div>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <div class="text-2xl text-yellow-800 font-bold">
                            +<?= floor(($quizResults['correct_answers'] * 10) / 1000) ?> 💎
                        </div>
                        <div class="text-yellow-600 text-sm">Orbes gagnés</div>
                    </div>
                </div>
                
                <!-- Progress Summary -->
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Progression</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">XP Total:</span>
                            <span class="font-medium text-gray-900"><?= $quizResults['correct_answers'] * 10 ?></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Orbes Total:</span>
                            <span class="font-medium text-gray-900"><?= floor(($quizResults['correct_answers'] * 10) / 1000) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="http://localhost:8000/simple-subjects.php" class="bg-blue-600 text-white p-6 rounded-xl shadow-lg hover:bg-blue-700 transition-colors duration-200 text-center">
                <div class="text-3xl mb-2">📚</div>
                <h3 class="text-xl font-bold mb-2">Explorer d'autres Matières</h3>
                <p class="text-blue-100">Découvrir de nouveaux thèmes</p>
            </a>
            
            <a href="http://localhost:8000/user-dashboard.php" class="bg-green-600 text-white p-6 rounded-xl shadow-lg hover:bg-green-700 transition-colors duration-200 text-center">
                <div class="text-3xl mb-2">🏠</div>
                <h3 class="text-xl font-bold mb-2">Mon Tableau de Bord</h3>
                <p class="text-green-100">Voir mes progrès et XP</p>
            </a>
            
            <a href="http://localhost:8000/quiz.php?theme_id=<?= $quizResults['theme_id'] ?>" class="bg-purple-600 text-white p-6 rounded-xl shadow-lg hover:bg-purple-700 transition-colors duration-200 text-center">
                <div class="text-3xl mb-2">🔄</div>
                <h3 class="text-xl font-bold mb-2">Recommencer ce Quiz</h3>
                <p class="text-purple-100">Améliorer mon score</p>
            </a>
        </div>
        
        <?php else: ?>
        
        <!-- No Results Message -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">🎯</div>
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Aucun résultat à afficher</h1>
            <p class="text-gray-600 mb-6">Commencez un quiz pour voir vos résultats ici !</p>
            <a href="http://localhost:8000/simple-subjects.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                Commencer un Quiz
            </a>
        </div>
        
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">
                © 2024 Askiaverse. L'aventure du savoir commence ici.
            </p>
        </div>
    </footer>
</body>
</html>
