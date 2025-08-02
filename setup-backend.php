<?php
// setup-backend.php - Script de configuration du backend

echo "🚀 Configuration du Backend Askiaverse\n";
echo "=====================================\n\n";

// 1. Vérifier les dépendances
echo "1. Vérification des dépendances...\n";
if (!extension_loaded('pdo_mysql')) {
    echo "❌ Extension PDO MySQL non trouvée. Veuillez l'activer dans php.ini\n";
    exit(1);
}
echo "✅ PDO MySQL disponible\n";

if (!extension_loaded('json')) {
    echo "❌ Extension JSON non trouvée\n";
    exit(1);
}
echo "✅ JSON disponible\n";

// 2. Créer le fichier .env
echo "\n2. Configuration de l'environnement...\n";
$envContent = <<<ENV
# Askiaverse Environment Configuration
APP_ENV=local
APP_DEBUG=true
ASSET_PREFIX=/assets/

# Database Configuration
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=askiaverse
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration (for future use)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@askiaverse.com
MAIL_FROM_NAME="Askiaverse"

# Application Settings
APP_NAME="Askiaverse"
APP_URL=http://askiaverse.local
APP_TIMEZONE=Africa/Bamako
ENV;

$envFile = __DIR__ . '/.env';
if (file_put_contents($envFile, $envContent)) {
    echo "✅ Fichier .env créé\n";
} else {
    echo "❌ Erreur lors de la création du fichier .env\n";
    exit(1);
}

// 3. Tester la connexion à la base de données
echo "\n3. Test de connexion à la base de données...\n";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/config/config.php';
    require_once __DIR__ . '/src/Shared/Database.php';
    require_once __DIR__ . '/src/Shared/Config.php';
    
    $db = \Shared\Database::getInstance();
    echo "✅ Connexion à la base de données réussie\n";
} catch (Exception $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    echo "📝 Assurez-vous que:\n";
    echo "   - MySQL est démarré\n";
    echo "   - La base de données 'askiaverse' existe\n";
    echo "   - Les paramètres de connexion sont corrects\n";
    exit(1);
}

// 4. Vérifier les tables
echo "\n4. Vérification des tables...\n";
$requiredTables = ['users', 'subjects', 'themes', 'questions', 'quiz_attempts'];
$missingTables = [];

foreach ($requiredTables as $table) {
    try {
        $result = $db->query("SHOW TABLES LIKE :table", ['table' => $table]);
        if ($result->rowCount() === 0) {
            $missingTables[] = $table;
        }
    } catch (Exception $e) {
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    echo "⚠️  Tables manquantes: " . implode(', ', $missingTables) . "\n";
    echo "📝 Veuillez créer les tables manquantes ou exécuter les migrations\n";
} else {
    echo "✅ Toutes les tables sont présentes\n";
}

// 5. Populer avec les données de test
echo "\n5. Population avec les données de test...\n";
try {
    require_once __DIR__ . '/database/seeds/TestDataSeeder.php';
    $seeder = new TestDataSeeder();
    $seeder->run();
    echo "✅ Données de test ajoutées\n";
} catch (Exception $e) {
    echo "❌ Erreur lors du seeding: " . $e->getMessage() . "\n";
}

// 6. Informations de connexion
echo "\n6. Informations de connexion:\n";
echo "👤 Utilisateur de test: hamza\n";
echo "🔑 Mot de passe: 123456\n";
echo "🏫 École: LYMG\n";
echo "📍 État: Gao\n";

echo "\n🎉 Configuration terminée!\n";
echo "📝 Prochaines étapes:\n";
echo "   1. Accédez à http://askiaverse.local\n";
echo "   2. Connectez-vous avec hamza/123456\n";
echo "   3. Testez les fonctionnalités\n";

echo "\n🔗 URLs utiles:\n";
echo "   - Page d'accueil: http://askiaverse.local\n";
echo "   - Connexion: http://askiaverse.local/?page=login\n";
echo "   - Inscription: http://askiaverse.local/?page=register\n";
echo "   - Dashboard: http://askiaverse.local/?page=dashboard\n";
echo "   - Quiz: http://askiaverse.local/?page=quiz\n"; 