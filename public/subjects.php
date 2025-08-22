<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Simple subjects page for testing
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
    echo "Erreur: " . $e->getMessage();
    exit;
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
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Nos Matières</h1>
            <p class="text-xl text-gray-600">Découvrez notre collection de matières éducatives</p>
        </div>

        <!-- Subjects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($subjects as $subject): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="text-4xl mb-4"><?= htmlspecialchars($subject['icon'] ?? '📚') ?></div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($subject['name']) ?></h3>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($subject['description']) ?></p>
                    <div class="text-sm text-gray-500 mb-4"><?= $subject['theme_count'] ?> thèmes disponibles</div>
                    <a href="simple-subject.php?id=<?= $subject['id'] ?>" 
                       class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center block">
                        Explorer
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
