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

// Handle registration form submission
if ($_POST) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $school = $_POST['school'] ?? '';
    $city = $_POST['city'] ?? '';
    $class_level = $_POST['class_level'] ?? '';
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
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
            
            // Check if username already exists
            $sql = "SELECT id FROM users WHERE username = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username]);
            
            if ($stmt->fetch()) {
                $error = 'Ce nom d\'utilisateur existe déjà.';
            } else {
                // Check if email already exists
                $sql = "SELECT id FROM users WHERE email = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email]);
                
                if ($stmt->fetch()) {
                    $error = 'Cette adresse email existe déjà.';
                } else {
                    // Create new user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $sql = "INSERT INTO users (username, email, password, school, city, class_level) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$username, $email, $hashed_password, $school, $city, $class_level]);
                    
                    $success = 'Compte créé avec succès! Vous pouvez maintenant vous connecter.';
                    
                    // Clear form data
                    $_POST = [];
                }
            }
            
        } catch (Exception $e) {
            $error = 'Erreur lors de la création du compte: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Askiaverse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-900 via-blue-900 to-purple-900 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-white rounded-full flex items-center justify-center mb-4">
                    <span class="text-4xl">🚀</span>
                </div>
                <h2 class="text-3xl font-bold text-white">Askiaverse</h2>
                <p class="mt-2 text-blue-200">Créez votre compte pour commencer l'aventure</p>
            </div>

            <!-- Registration Form -->
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

                <form method="POST" class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Nom d'utilisateur *
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Choisissez un nom d'utilisateur"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Adresse email *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="votre@email.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mot de passe *
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Au moins 6 caractères"
                        >
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                            Confirmer le mot de passe *
                        </label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Répétez votre mot de passe"
                        >
                    </div>

                    <div>
                        <label for="school" class="block text-sm font-medium text-gray-700">
                            École
                        </label>
                        <input 
                            type="text" 
                            id="school" 
                            name="school" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nom de votre école"
                            value="<?= htmlspecialchars($_POST['school'] ?? '') ?>"
                        >
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">
                            Ville
                        </label>
                        <input 
                            type="text" 
                            id="city" 
                            name="city" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Votre ville"
                            value="<?= htmlspecialchars($_POST['city'] ?? '') ?>"
                        >
                    </div>

                    <div>
                        <label for="class_level" class="block text-sm font-medium text-gray-700">
                            Niveau de classe
                        </label>
                        <select 
                            id="class_level" 
                            name="class_level" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Sélectionnez votre niveau</option>
                            <option value="CP" <?= ($_POST['class_level'] ?? '') === 'CP' ? 'selected' : '' ?>>CP</option>
                            <option value="CE1" <?= ($_POST['class_level'] ?? '') === 'CE1' ? 'selected' : '' ?>>CE1</option>
                            <option value="CE2" <?= ($_POST['class_level'] ?? '') === 'CE2' ? 'selected' : '' ?>>CE2</option>
                            <option value="CM1" <?= ($_POST['class_level'] ?? '') === 'CM1' ? 'selected' : '' ?>>CM1</option>
                            <option value="CM2" <?= ($_POST['class_level'] ?? '') === 'CM2' ? 'selected' : '' ?>>CM2</option>
                            <option value="6ème" <?= ($_POST['class_level'] ?? '') === '6ème' ? 'selected' : '' ?>>6ème</option>
                            <option value="5ème" <?= ($_POST['class_level'] ?? '') === '5ème' ? 'selected' : '' ?>>5ème</option>
                            <option value="4ème" <?= ($_POST['class_level'] ?? '') === '4ème' ? 'selected' : '' ?>>4ème</option>
                            <option value="3ème" <?= ($_POST['class_level'] ?? '') === '3ème' ? 'selected' : '' ?>>3ème</option>
                            <option value="2nde" <?= ($_POST['class_level'] ?? '') === '2nde' ? 'selected' : '' ?>>2nde</option>
                            <option value="1ère" <?= ($_POST['class_level'] ?? '') === '1ère' ? 'selected' : '' ?>>1ère</option>
                            <option value="Terminale" <?= ($_POST['class_level'] ?? '') === 'Terminale' ? 'selected' : '' ?>>Terminale</option>
                        </select>
                    </div>

                    <div>
                        <button 
                            type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                        >
                            Créer mon compte
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Déjà un compte? 
                        <a href="login.php" class="font-medium text-blue-600 hover:text-blue-500">
                            Connectez-vous
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
