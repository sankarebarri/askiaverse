<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Simple individual subject page with direct database connection
$subjectId = $_GET['id'] ?? 1;

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
    
    // Fetch subject details
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$subjectId]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$subject) {
        $error = "Matière non trouvée";
    } else {
        // Fetch themes for this subject
        $sql = "SELECT * FROM themes WHERE subject_id = ? ORDER BY difficulty, name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$subjectId]);
        $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Fetch sample questions for this subject
        $sql = "
            SELECT 
                q.*,
                t.name as theme_name
            FROM questions q
            JOIN themes t ON q.theme_id = t.id
            WHERE t.subject_id = ?
            ORDER BY RAND()
            LIMIT 5
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$subjectId]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($subject) ? $subject['name'] . ' - Askiaverse' : 'Matière - Askiaverse' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="user-dashboard.php" class="text-2xl font-bold text-gray-900 hover:text-blue-600">Askiaverse</a>
                    <?php else: ?>
                        <a href="index.php" class="text-2xl font-bold text-gray-900 hover:text-blue-600">Askiaverse</a>
                    <?php endif; ?>
                </div>
                <nav class="flex space-x-4">
                    <a href="simple-subjects.php" class="text-blue-600 font-medium">← Retour aux matières</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="user-dashboard.php" class="text-gray-600 hover:text-gray-900">Tableau de Bord</a>
                    <?php endif; ?>
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
        <?php elseif (isset($subject)): ?>
        
        <!-- Subject Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full mb-6" 
                 style="background: linear-gradient(135deg, <?= $subject['color'] ?>20, <?= $subject['color'] ?>10);">
                <span class="text-5xl"><?= $subject['icon'] ?></span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($subject['name']) ?></h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto"><?= htmlspecialchars($subject['description']) ?></p>
        </div>

        <!-- Themes Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Thèmes disponibles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($themes as $theme): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($theme['name']) ?></h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            <?= $theme['difficulty'] === 'facile' ? 'bg-green-100 text-green-800' : 
                                ($theme['difficulty'] === 'moyen' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                            <?= ucfirst($theme['difficulty']) ?>
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($theme['description']) ?></p>
                    <a href="quiz.php?theme_id=<?= $theme['id'] ?>" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium text-center block">
                        🎮 Jouer
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sample Questions Section -->
        <?php if (!empty($questions)): ?>
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Exemples de questions</h2>
            <div class="space-y-6">
                <?php foreach ($questions as $question): ?>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="mb-4">
                        <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium mb-3">
                            <?= htmlspecialchars($question['theme_name']) ?>
                        </span>
                        <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($question['question']) ?></h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        <?php 
                        $options = json_decode($question['options'], true);
                        $correctAnswer = $question['correct_answer'];
                        foreach ($options as $index => $option): 
                        ?>
                        <div class="flex items-center p-3 rounded-lg border-2 
                            <?= $index === $correctAnswer ? 'border-green-200 bg-green-50' : 'border-gray-200 hover:border-gray-300' ?>">
                            <div class="w-4 h-4 rounded-full mr-3 
                                <?= $index === $correctAnswer ? 'bg-green-500' : 'bg-gray-300' ?>"></div>
                            <span class="<?= $index === $correctAnswer ? 'text-green-800 font-medium' : 'text-gray-700' ?>">
                                <?= htmlspecialchars($option) ?>
                            </span>
                            <?php if ($index === $correctAnswer): ?>
                            <span class="ml-auto text-green-600 font-medium">✓ Correct</span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if ($question['explanation']): ?>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                        <p class="text-blue-800">
                            <strong>Explication :</strong> <?= htmlspecialchars($question['explanation']) ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Call to Action -->
        <div class="text-center">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white">
                <h2 class="text-3xl font-bold mb-4">Prêt à tester vos connaissances ?</h2>
                <p class="text-xl mb-6 opacity-90">
                    Commencez par un thème facile et progressez à votre rythme
                </p>
                <a href="index.php" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transition-colors duration-200 inline-block">
                    🚀 Commencer l'apprentissage
                </a>
            </div>
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
