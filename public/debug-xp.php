<?php
// Debug page to see XP data in database
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

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

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username_db, $password_db, $options);
    
    $userId = $_SESSION['user_id'];
    
    // Get user info
    $userSql = "SELECT username, email FROM users WHERE id = ?";
    $userStmt = $pdo->prepare($userSql);
    $userStmt->execute([$userId]);
    $user = $userStmt->fetch();
    
    // Get all XP records for this user
    $xpSql = "SELECT 
                up.*,
                s.name as subject_name,
                t.name as theme_name
               FROM user_progress up
               JOIN subjects s ON up.subject_id = s.id
               JOIN themes t ON up.theme_id = t.id
               WHERE up.user_id = ?
               ORDER BY up.last_activity DESC";
    $xpStmt = $pdo->prepare($xpSql);
    $xpStmt->execute([$userId]);
    $xpRecords = $xpStmt->fetchAll();
    
    // Calculate totals
    $totalXP = array_sum(array_column($xpRecords, 'experience'));
    $totalOrbs = array_sum(array_column($xpRecords, 'orbs'));
    
} catch (Exception $e) {
    $error = 'Database error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug XP - Askiaverse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <?php include 'components/header.php'; ?>
    
    <div class="max-w-6xl mx-auto p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">🔍 Debug XP Data</h1>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php else: ?>
        
        <!-- User Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">👤 User Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="font-medium">Username:</span> <?= htmlspecialchars($user['username']) ?>
                </div>
                <div>
                    <span class="font-medium">Email:</span> <?= htmlspecialchars($user['email']) ?>
                </div>
                <div>
                    <span class="font-medium">User ID:</span> <?= $userId ?>
                </div>
            </div>
        </div>
        
        <!-- Summary -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">📊 XP Summary</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600"><?= $totalXP ?></div>
                    <div class="text-gray-600">Total XP</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600"><?= $totalOrbs ?></div>
                    <div class="text-gray-600">Total Orbs</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600"><?= count($xpRecords) ?></div>
                    <div class="text-gray-600">Theme Records</div>
                </div>
            </div>
        </div>
        
        <!-- Detailed Records -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">📝 Detailed XP Records</h2>
            
            <?php if (empty($xpRecords)): ?>
                <p class="text-gray-500 text-center py-8">No XP records found for this user.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Theme</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">XP</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orbs</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lessons</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($xpRecords as $record): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2"><?= htmlspecialchars($record['subject_name']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($record['theme_name']) ?></td>
                                <td class="px-4 py-2 font-medium text-blue-600"><?= $record['experience'] ?></td>
                                <td class="px-4 py-2 font-medium text-yellow-600"><?= $record['orbs'] ?></td>
                                <td class="px-4 py-2"><?= $record['level'] ?></td>
                                <td class="px-4 py-2"><?= $record['completed_lessons'] ?></td>
                                <td class="px-4 py-2 text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($record['last_activity'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Actions -->
        <div class="mt-8 flex space-x-4">
            <a href="user-dashboard.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                ← Back to Dashboard
            </a>
            <a href="simple-subjects.php" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200">
                Take Another Quiz
            </a>
        </div>
        
        <?php endif; ?>
    </div>
</body>
</html>
