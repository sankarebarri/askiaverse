<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Simple admin dashboard with direct database connection
try {
    // Direct database connection
    $host = '127.0.0.1';
    $port = 3306;
    $dbname = 'askiaverse';
    $username = 'root';
    $password = '';
    
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Fetch statistics
    $stats = [];
    
    // Count subjects
    $stmt = $pdo->query("SELECT COUNT(*) FROM subjects");
    $stats['subjects'] = $stmt->fetchColumn();
    
    // Count themes
    $stmt = $pdo->query("SELECT COUNT(*) FROM themes");
    $stats['themes'] = $stmt->fetchColumn();
    
    // Count questions
    $stmt = $pdo->query("SELECT COUNT(*) FROM questions");
    $stats['questions'] = $stmt->fetchColumn();
    
    // Count users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $stats['users'] = $stmt->fetchColumn();
    
    // Fetch recent quiz attempts
    $sql = "
        SELECT 
            qa.*,
            u.username,
            s.name as subject_name,
            t.name as theme_name
        FROM quiz_attempts qa
        JOIN users u ON qa.user_id = u.id
        LEFT JOIN subjects s ON qa.subject_id = s.id
        LEFT JOIN themes t ON qa.theme_id = t.id
        ORDER BY qa.created_at DESC
        LIMIT 10
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recentAttempts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Askiaverse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="user-dashboard.php" class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">Askiaverse Admin</a>
                    <?php else: ?>
                        <a href="index.php" class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">Askiaverse Admin</a>
                    <?php endif; ?>
                </div>
                <nav class="flex space-x-4">
                    <a href="simple-subjects.php" class="text-gray-600 hover:text-gray-900">Matières</a>
                    <a href="user-dashboard.php" class="text-gray-600 hover:text-gray-900">Tableau de Bord</a>
                    <a href="simple-admin.php" class="text-blue-600 font-medium">Admin</a>
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

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-3xl font-bold text-blue-600"><?= $stats['subjects'] ?? 0 ?></div>
                <div class="text-gray-600">Matières</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-3xl font-bold text-green-600"><?= $stats['themes'] ?? 0 ?></div>
                <div class="text-gray-600">Thèmes</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-3xl font-bold text-purple-600"><?= $stats['questions'] ?? 0 ?></div>
                <div class="text-gray-600">Questions</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-3xl font-bold text-orange-600"><?= $stats['users'] ?? 0 ?></div>
                <div class="text-gray-600">Utilisateurs</div>
            </div>
        </div>

        <!-- Recent Quiz Attempts -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Tentatives de Quiz Récentes</h2>
            <?php if (!empty($recentAttempts)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matière</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thème</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($recentAttempts as $attempt): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($attempt['username']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($attempt['subject_name'] ?? 'N/A') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($attempt['theme_name'] ?? 'N/A') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $attempt['score'] ?>% (<?= $attempt['correct_answers'] ?>/<?= $attempt['total_questions'] ?>)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y H:i', strtotime($attempt['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-gray-500 text-center py-8">Aucune tentative de quiz pour le moment.</p>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="simple-subjects.php" class="bg-blue-600 text-white p-6 rounded-xl shadow-lg hover:bg-blue-700 transition-colors duration-200 text-center">
                <div class="text-3xl mb-2">📚</div>
                <h3 class="text-xl font-bold mb-2">Voir les Matières</h3>
                <p class="text-blue-100">Explorer le contenu éducatif</p>
            </a>
            
            <a href="simple-subject.php?id=1" class="bg-green-600 text-white p-6 rounded-xl shadow-lg hover:bg-green-700 transition-colors duration-200 text-center">
                <div class="text-3xl mb-2">🔢</div>
                <h3 class="text-xl font-bold mb-2">Mathématiques</h3>
                <p class="text-green-100">Voir les thèmes et questions</p>
            </a>
            
            <a href="index.php" class="bg-purple-600 text-white p-6 rounded-xl shadow-lg hover:bg-purple-700 transition-colors duration-200 text-center">
                <div class="text-3xl mb-2">🏠</div>
                <h3 class="text-xl font-bold mb-2">Accueil</h3>
                <p class="text-purple-100">Retour à la page principale</p>
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
