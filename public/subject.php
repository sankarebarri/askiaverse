<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Simple individual subject page for testing
$subjectId = $_GET['id'] ?? 1;

try {
    // Load environment variables
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    
    // Database connection using environment variables
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? 3306;
    $dbname = $_ENV['DB_DATABASE'] ?? 'u379844049_askiagames_db';
    $username_db = $_ENV['DB_USERNAME'] ?? 'u379844049_askiagames';
    $password_db = $_ENV['DB_PASSWORD'] ?? '';
    
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $username_db, $password_db, $options);
    
    // Fetch subject details
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$subjectId]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$subject) {
        echo "Matière non trouvée";
        exit;
    }
    
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
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($subject['name']) ?> - Askiaverse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <?php include 'components/header.php'; ?>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Subject Header -->
        <div class="text-center mb-8">
            <div class="text-6xl mb-4"><?= htmlspecialchars($subject['icon'] ?? '📚') ?></div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($subject['name']) ?></h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto"><?= htmlspecialchars($subject['description']) ?></p>
        </div>

        <!-- Themes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <?php foreach ($themes as $theme): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($theme['name']) ?></h3>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($theme['description']) ?></p>
                    <div class="text-sm text-gray-500 mb-4">Difficulté: <?= $theme['difficulty'] ?></div>
                    <a href="quiz.php?theme_id=<?= $theme['id'] ?>" 
                       class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center block">
                        🎮 Jouer
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Sample Questions -->
        <?php if (!empty($questions)): ?>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Exemples de Questions</h2>
            <div class="space-y-4">
                <?php foreach ($questions as $question): ?>
                <div class="border-l-4 border-blue-500 pl-4">
                    <p class="text-gray-800 mb-2"><?= htmlspecialchars($question['question_text']) ?></p>
                    <p class="text-sm text-gray-600">Thème: <?= htmlspecialchars($question['theme_name']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>
