<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: http://localhost:8000/login.php');
    exit;
}

// Database connection
$host = '127.0.0.1';
$port = 3306;
$dbname = 'askiaverse';
$username_db = 'root';
$password_db = '';

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username_db, $password_db, $options);
    
    // Get user data
    $userId = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    // Get user progress and achievements
    $sql = "SELECT up.*, s.name as subject_name FROM user_progress up 
            JOIN subjects s ON up.subject_id = s.id 
            WHERE up.user_id = ? ORDER BY up.last_activity DESC LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $recentProgress = $stmt->fetchAll();
    
    // Get user achievements
    $sql = "SELECT ua.*, a.name, a.description, a.icon FROM user_achievements ua 
            JOIN achievements a ON ua.achievement_id = a.id 
            WHERE ua.user_id = ? ORDER BY ua.earned_at DESC LIMIT 6";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $achievements = $stmt->fetchAll();
    
    // Calculate user stats
    $sql = "SELECT COUNT(*) as total_attempts, AVG(score) as avg_score FROM quiz_attempts WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $quizStats = $stmt->fetch();
    
    // Get total XP and orbs from user_progress (aggregated across all themes)
    $sql = "SELECT 
                COALESCE(SUM(experience), 0) as total_experience,
                COALESCE(SUM(orbs), 0) as total_orbs
            FROM user_progress 
            WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $progressStats = $stmt->fetch();
    
    $totalXP = $progressStats['total_experience'];
    $orbs = $progressStats['total_orbs'];
    
    // Debug: Let's see what's in the database
    $debugSql = "SELECT subject_id, theme_id, experience, orbs FROM user_progress WHERE user_id = ? ORDER BY last_activity DESC";
    $debugStmt = $pdo->prepare($debugSql);
    $debugStmt->execute([$userId]);
    $debugData = $debugStmt->fetchAll();
    
    // Log debug info (you can remove this later)
    error_log("Debug XP Data for user $userId: " . json_encode($debugData));
    
    // Calculate level based on XP (1000 XP = 1 level)
    $level = max(1, floor($totalXP / 1000) + 1);
    $xpInCurrentLevel = $totalXP % 1000;
    $xpNeededForNextLevel = 1000 - $xpInCurrentLevel;
    
        // Set default values if no data
    if (empty($recentProgress)) {
        $recentProgress = [];
    }
    if (empty($achievements)) {
        $achievements = [];
    }
} catch (Exception $e) {
    $error = 'Erreur de connexion à la base de données: ' . $e->getMessage();
    
    // Set default values on error
    $level = 1;
    $xpInCurrentLevel = 0;
    $xpNeededForNextLevel = 1000;
    $orbs = 0;
    $totalXP = 0;
    $quizStats = ['total_attempts' => 0, 'avg_score' => 0];
    $recentProgress = [];
    $achievements = [];
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Askiaverse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="http://localhost:8000/user-dashboard.php" class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">Askiaverse</a>
                </div>
                <nav class="flex space-x-4">
                    <a href="http://localhost:8000/simple-subjects.php" class="text-gray-600 hover:text-gray-900">Matières</a>
                    <a href="http://localhost:8000/user-dashboard.php" class="text-blue-600 font-medium">Tableau de Bord</a>
                    <span class="text-gray-600">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                    <a href="http://localhost:8000/logout.php" class="text-red-600 hover:text-red-700">Déconnexion</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Erreur:</strong> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <!-- User Profile Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                    <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($_SESSION['username']) ?></h1>
                    <p class="text-gray-600 mb-1"><?= htmlspecialchars($_SESSION['school']) ?> - <?= htmlspecialchars($_SESSION['city']) ?></p>
                    <p class="text-gray-600">Niveau: <?= htmlspecialchars($_SESSION['class_level']) ?></p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold text-yellow-500"><?= $level ?></div>
                    <div class="text-gray-600">Niveau</div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <div class="text-2xl">⭐</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900"><?= $level ?></div>
                        <div class="text-gray-600">Niveau</div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>XP: <?= $xpInCurrentLevel ?>/1000</span>
                        <span><?= $xpNeededForNextLevel ?> XP restants</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: <?= ($xpInCurrentLevel / 1000) * 100 ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <div class="text-2xl">💎</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900"><?= $orbs ?></div>
                        <div class="text-gray-600">Orbes</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <div class="text-2xl">🎯</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900"><?= $quizStats['total_attempts'] ?? 0 ?></div>
                        <div class="text-gray-600">Quiz tentés</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <div class="text-2xl">🏆</div>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900"><?= number_format($quizStats['avg_score'] ?? 0, 1) ?>%</div>
                        <div class="text-gray-600">Score moyen</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Achievements -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Progress -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Activité Récente</h2>
                <?php if (!empty($recentProgress)): ?>
                <div class="space-y-4">
                    <?php foreach ($recentProgress as $progress): ?>
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <div class="text-blue-600">📚</div>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">Progression dans <?= htmlspecialchars($progress['subject_name'] ?? 'Matière') ?></div>
                            <div class="text-sm text-gray-600"><?= date('d/m/Y H:i', strtotime($progress['created_at'])) ?></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-green-600">+<?= $progress['xp_gained'] ?? 10 ?> XP</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-2">🎯</div>
                    <p>Aucune activité récente</p>
                    <p class="text-sm">Commencez à explorer les matières pour gagner de l'XP !</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Achievements -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Succès Débloqués</h2>
                <?php if (!empty($achievements)): ?>
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach ($achievements as $achievement): ?>
                    <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                        <div class="text-3xl mb-2"><?= htmlspecialchars($achievement['icon']) ?></div>
                        <div class="font-medium text-gray-900 text-sm"><?= htmlspecialchars($achievement['name']) ?></div>
                        <div class="text-xs text-gray-600 mt-1"><?= htmlspecialchars($achievement['description']) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-2">🏆</div>
                    <p>Aucun succès débloqué</p>
                    <p class="text-sm">Continuez à apprendre pour débloquer des succès !</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

                    <!-- Quick Actions -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <a href="http://localhost:8000/simple-subjects.php" class="bg-blue-600 text-white p-6 rounded-xl shadow-lg hover:bg-blue-700 transition-colors duration-200 text-center">
                    <div class="text-3xl mb-2">📚</div>
                    <h3 class="text-xl font-bold mb-2">Explorer les Matières</h3>
                    <p class="text-blue-100">Découvrir de nouveaux thèmes</p>
                </a>
                
                <a href="http://localhost:8000/simple-subject.php?id=1" class="bg-green-600 text-white p-6 rounded-xl shadow-lg hover:bg-green-700 transition-colors duration-200 text-center">
                    <div class="text-3xl mb-2">🔢</div>
                    <h3 class="text-xl font-bold mb-2">Mathématiques</h3>
                    <p class="text-green-100">Commencer un quiz</p>
                </a>
                
                <a href="http://localhost:8000/simple-admin.php" class="bg-purple-600 text-white p-6 rounded-xl shadow-lg hover:bg-purple-700 transition-colors duration-200 text-center">
                    <div class="text-3xl mb-2">📊</div>
                    <h3 class="text-xl font-bold mb-2">Statistiques</h3>
                    <p class="text-purple-100">Voir les statistiques globales</p>
                </a>
                
                <a href="http://localhost:8000/debug-xp.php" class="bg-orange-600 text-white p-6 rounded-xl shadow-lg hover:bg-orange-700 transition-colors duration-200 text-center">
                    <div class="text-3xl mb-2">🔍</div>
                    <h3 class="text-xl font-bold mb-2">Debug XP</h3>
                    <p class="text-orange-100">Voir les données XP détaillées</p>
                </a>
            </div>
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
