<?php
// Set session directory to a writable location
ini_set('session.save_path', '/tmp');
session_start();

// If user is already logged in, redirect to subjects
if (isset($_SESSION['user_id'])) {
    header('Location: simple-subjects.php');
    exit;
}

$error = '';
$success = '';

// Handle login form submission
if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
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
            
            // Check if user exists and password is correct
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['school'] = $user['school'];
                $_SESSION['city'] = $user['city'];
                $_SESSION['class_level'] = $user['class_level'];
                
                $success = 'Connexion réussie! Redirection...';
                
                // Redirect after 2 seconds
                header('Refresh: 2; URL=user-dashboard.php');
            } else {
                $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
            }
            
        } catch (Exception $e) {
            $error = 'Erreur de connexion à la base de données: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Askiaverse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-white rounded-full flex items-center justify-center mb-4">
                    <span class="text-4xl">🚀</span>
                </div>
                <h2 class="text-3xl font-bold text-white">Askiaverse</h2>
                <p class="mt-2 text-blue-200">Connectez-vous pour continuer votre aventure</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-xl shadow-2xl p-8">
                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Nom d'utilisateur
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Entrez votre nom d'utilisateur"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mot de passe
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Entrez votre mot de passe"
                        >
                    </div>

                    <div>
                        <button 
                            type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        >
                            Se Connecter
                        </button>
                    </div>
                </form>

                <!-- Demo Accounts Info -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">📚 Comptes de démonstration:</h3>
                    <div class="text-xs text-blue-700 space-y-1">
                        <div><strong>hamza</strong> / 123456 (LYMG, Gao)</div>
                        <div><strong>fatou</strong> / 123456 (Lycée Moderne, Bamako)</div>
                        <div><strong>moussa</strong> / 123456 (École Fondamentale, Sikasso)</div>
                    </div>
                </div>

                <!-- Registration Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Pas encore de compte? 
                        <a href="register.php" class="font-medium text-blue-600 hover:text-blue-500">
                            Inscrivez-vous
                        </a>
                    </p>
                </div>

                <!-- Back to Home -->
                <div class="mt-4 text-center">
                    <a href="index.php" class="text-sm text-gray-500 hover:text-gray-700">
                        ← Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
