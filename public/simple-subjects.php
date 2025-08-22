<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Simple subjects page with direct database connection
try {
    // Load environment variables
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    
    // Database connection using environment variables
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? 3306;
    $dbname = $_ENV['DB_DATABASE'] ?? 'u379844049_askiagames_db';
    $username = $_ENV['DB_USERNAME'] ?? 'u379844049_askiagames';
    $password = $_ENV['DB_PASSWORD'] ?? '';
    
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Fetch all subjects with their themes
    $sql = "
        SELECT 
            s.id,
            s.name,
            s.description,
            s.icon,
            s.color,
            COUNT(t.id) as theme_count
        FROM subjects s
        LEFT JOIN themes t ON s.id = t.subject_id
        GROUP BY s.id
        ORDER BY s.name
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matières - Askiaverse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <?php include 'components/header.php'; ?>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Erreur:</strong> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Découvrez nos matières
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Plongez dans un univers d'apprentissage riche et varié, conçu spécialement pour les jeunes esprits brillants du Mali.
            </p>
        </div>

        <!-- Subjects Grid -->
        <?php if (isset($subjects) && !empty($subjects)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($subjects as $subject): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Subject Header -->
                <div class="p-6" style="background: linear-gradient(135deg, <?= $subject['color'] ?>20, <?= $subject['color'] ?>10);">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-4xl"><?= $subject['icon'] ?></div>
                        <div class="text-sm bg-white px-3 py-1 rounded-full text-gray-600 font-medium">
                            <?= $subject['theme_count'] ?> thèmes
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($subject['name']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($subject['description']) ?></p>
                </div>
                
                <!-- Subject Actions -->
                <div class="p-6 bg-gray-50">
                    <a href="simple-subject.php?id=<?= $subject['id'] ?>" 
                       class="w-full bg-white border-2 border-gray-200 text-gray-700 px-4 py-3 rounded-lg hover:border-gray-300 hover:bg-gray-50 transition-colors duration-200 text-center block font-medium">
                        Explorer les thèmes
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-xl">Aucune matière trouvée.</p>
        </div>
        <?php endif; ?>

        <!-- Call to Action -->
        <div class="text-center mt-16">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white">
                <h2 class="text-3xl font-bold mb-4">Prêt à commencer l'aventure ?</h2>
                <p class="text-xl mb-6 opacity-90">
                    Rejoignez des milliers d'élèves qui apprennent en s'amusant sur Askiaverse
                </p>
                <a href="index.php" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-bold hover:bg-gray-100 transition-colors duration-200 inline-block">
                    🚀 Commencer maintenant
                </a>
            </div>
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
